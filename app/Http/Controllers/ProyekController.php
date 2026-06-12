<?php

namespace App\Http\Controllers;

use App\Models\Proyek;
use App\Models\Notification; // 🔥 IMPORT MODEL NOTIFICATION
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua proyek dengan relasi rabs (optional)
        $proyeks = Proyek::with('rabs')->latest()->get();
        
        return view('proyek.index', compact('proyeks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ✅ TIDAK PERLU KIRIM statusOptions karena tidak ditampilkan di form
        return view('proyek.create');
    }

    /**
     * Store a newly created resource in storage.
     * 🔥 WITH NOTIFICATION SYSTEM
     * ✅ STATUS OTOMATIS SET KE "RAB BELUM DIBUAT"
     */
    public function store(Request $request)
    {
        // ✅ Validasi input - TANPA STATUS (dihapus dari validasi)
        $validated = $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'nama_owner' => 'nullable|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            // ✅ 'status' DIHAPUS dari validasi
        ], [
            'nama_proyek.required' => 'Nama proyek wajib diisi',
            'nama_proyek.max' => 'Nama proyek maksimal 255 karakter',
            'nama_owner.max' => 'Nama owner maksimal 255 karakter',
            'nomor_hp.max' => 'Nomor HP maksimal 20 karakter',
        ]);

        try {
            DB::beginTransaction();
            
            // ✅ SET STATUS OTOMATIS KE "RAB BELUM DIBUAT"
            $validated['status'] = 'RAB Belum Dibuat';
            
            // Buat proyek baru
            $proyek = Proyek::create($validated);
            
            // 🔥 BUAT NOTIFIKASI OTOMATIS
            Notification::createProyekNotification('created', $proyek);
            
            // 🔥 Notifikasi tambahan untuk status RAB Belum Dibuat
            Notification::createProyekNotification('rab_belum_dibuat', $proyek);
            
            DB::commit();
            
            return redirect()
                ->route('proyek.index')
                ->with('success', 'Proyek berhasil ditambahkan dengan status "RAB Belum Dibuat"!')
                ->with('last_proyek_id', $proyek->id_proyek);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan proyek: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        
        // Load relasi rabs dengan detail
        $proyek->load('rabs');
        
        return view('proyek.show', compact('proyek'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        $statusOptions = Proyek::getStatusOptions();
        
        return view('proyek.edit', compact('proyek', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     * 🔥 WITH NOTIFICATION SYSTEM
     * ✅ STATUS TETAP BISA DIUBAH DI HALAMAN EDIT
     */
    public function update(Request $request, $id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        
        // Simpan status lama untuk deteksi perubahan
        $oldStatus = $proyek->status;
        
        // Validasi input
        $validated = $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'nama_owner' => 'nullable|string|max:255',
            'nomor_hp' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'status' => [
                'required',
                Rule::in(Proyek::getStatusOptions())
            ],
        ], [
            'nama_proyek.required' => 'Nama proyek wajib diisi',
            'nama_proyek.max' => 'Nama proyek maksimal 255 karakter',
            'nama_owner.max' => 'Nama owner maksimal 255 karakter',
            'nomor_hp.max' => 'Nomor HP maksimal 20 karakter',
            'status.required' => 'Status proyek wajib dipilih',
            'status.in' => 'Status proyek tidak valid',
        ]);

        try {
            DB::beginTransaction();
            
            // Update proyek
            $proyek->update($validated);
            
            // 🔥 BUAT NOTIFIKASI JIKA STATUS BERUBAH
            if ($oldStatus != $validated['status']) {
                // Notifikasi spesifik berdasarkan status baru
                $notifType = match($validated['status']) {
                    'RAB Belum Dibuat' => 'rab_belum_dibuat',
                    'RAB Telah Dibuat' => 'rab_telah_dibuat',
                    'Proyek Dikerjakan' => 'proyek_dikerjakan',
                    'Proyek Selesai' => 'proyek_selesai',
                    default => 'status_changed'
                };
                
                Notification::createProyekNotification($notifType, $proyek->fresh());
            } else {
                // Jika tidak ada perubahan status, buat notif update biasa
                Notification::createProyekNotification('updated', $proyek->fresh());
            }
            
            DB::commit();
            
            return redirect()
                ->route('proyek.index')
                ->with('success', 'Proyek berhasil diperbarui dan notifikasi telah dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui proyek: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * 🔥 WITH NOTIFICATION SYSTEM
     */
    public function destroy($id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        
        try {
            DB::beginTransaction();
            
            // Cek apakah proyek memiliki RAB
            if ($proyek->rabs()->count() > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Tidak dapat menghapus proyek yang sudah memiliki RAB');
            }
            
            // 🔥 Simpan data proyek sebelum dihapus untuk notifikasi
            $proyekData = clone $proyek;
            
            $proyek->delete();
            
            // 🔥 Buat notifikasi penghapusan
            Notification::createProyekNotification('deleted', $proyekData);
            
            DB::commit();
            
            return redirect()
                ->route('proyek.index')
                ->with('success', 'Proyek berhasil dihapus dan notifikasi telah dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus proyek: ' . $e->getMessage());
        }
    }

    /**
     * Update status proyek
     * 🔥 WITH NOTIFICATION SYSTEM
     */
    public function updateStatus(Request $request, $id_proyek)
    {
        $proyek = Proyek::findOrFail($id_proyek);
        
        // Simpan status lama untuk deteksi perubahan
        $oldStatus = $proyek->status;
        
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(Proyek::getStatusOptions())
            ],
        ]);

        try {
            DB::beginTransaction();
            
            // Validasi transisi status
            $currentStatus = $proyek->status;
            $newStatus = $validated['status'];
            
            // Aturan transisi status
            $allowedTransitions = [
                Proyek::STATUS_RAB_BELUM_DIBUAT => [
                    Proyek::STATUS_RAB_TELAH_DIBUAT
                ],
                Proyek::STATUS_RAB_TELAH_DIBUAT => [
                    Proyek::STATUS_PROYEK_DIKERJAKAN
                ],
                Proyek::STATUS_PROYEK_DIKERJAKAN => [
                    Proyek::STATUS_PROYEK_SELESAI
                ],
                Proyek::STATUS_PROYEK_SELESAI => [] // Tidak bisa diubah lagi
            ];
            
            // Cek apakah transisi diizinkan
            if (!in_array($newStatus, $allowedTransitions[$currentStatus] ?? [])) {
                return redirect()
                    ->back()
                    ->with('error', 'Transisi status tidak diizinkan');
            }
            
            // Validasi khusus untuk setiap status
            if ($newStatus === Proyek::STATUS_RAB_TELAH_DIBUAT) {
                // Cek apakah proyek sudah memiliki RAB
                if ($proyek->rabs()->count() === 0) {
                    return redirect()
                        ->back()
                        ->with('error', 'Proyek harus memiliki RAB terlebih dahulu');
                }
            }
            
            $proyek->update(['status' => $newStatus]);
            
            // 🔥 BUAT NOTIFIKASI PERUBAHAN STATUS
            $notifType = match($newStatus) {
                'RAB Belum Dibuat' => 'rab_belum_dibuat',
                'RAB Telah Dibuat' => 'rab_telah_dibuat',
                'Proyek Dikerjakan' => 'proyek_dikerjakan',
                'Proyek Selesai' => 'proyek_selesai',
                default => 'status_changed'
            };
            
            Notification::createProyekNotification($notifType, $proyek->fresh());
            
            DB::commit();
            
            return redirect()
                ->back()
                ->with('success', 'Status proyek berhasil diperbarui dan notifikasi telah dibuat.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Filter proyek berdasarkan status
     */
    public function filterByStatus(Request $request)
    {
        $status = $request->query('status');
        
        $query = Proyek::with('rabs');
        
        if ($status && in_array($status, Proyek::getStatusOptions())) {
            $query->where('status', $status);
        }
        
        $proyeks = $query->latest()->get();
        
        return view('proyek.index', compact('proyeks'));
    }
}