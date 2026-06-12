<?php

namespace App\Http\Controllers;

use App\Models\Kwitansi;
use App\Models\Rab;
use App\Models\Penjualan;
use App\Models\Proyek;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Exception;

class KwitansiController extends Controller
{
    /**
     * 🔍 Helper: Cek role user (admin atau kasir)
     */
    protected function isKasir()
    {
        return Auth::check() && Auth::user()->role === 'kasir';
    }

    protected function isAdmin()
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * 🔍 Temukan object sumber berdasarkan tabel dan id
     */
    protected function findSumberObject(string $sumberTabel, $id)
    {
        $table = strtolower(trim($sumberTabel ?? ''));

        switch ($table) {
            case 'rabs':
            case 'rab':
                return Rab::where('id_rab', $id)->first();

            case 'penjualans':
            case 'penjualan':
                return Penjualan::where('id_penjualan', $id)->first();

            default:
                return null;
        }
    }

    /**
     * 💰 Helper: Hitung status berdasarkan total pembayaran
     */
    protected function calculateStatus($totalPembayaran, $totalRAB)
    {
        $totalPembayaran = floatval($totalPembayaran);
        $totalRAB = floatval($totalRAB);

        if ($totalRAB <= 0) {
            return 'DP 0%';
        }

        if ($totalPembayaran >= $totalRAB) {
            return 'Lunas';
        }

        $persentase = ($totalPembayaran / $totalRAB) * 100;
        $persentase = round($persentase);
        
        $persentase = max(0, min(99, $persentase));

        return "DP {$persentase}%";
    }

    /**
     * 💰 Helper: Membersihkan input numerik dari format mata uang (Misal: "29.000,00" -> 29000.00)
     */
    protected function cleanNumericInput($input)
    {
        if (!is_string($input)) {
            return floatval($input);
        }
        
        $cleaned = str_replace('.', '', $input); 
        $cleaned = str_replace(',', '.', $cleaned);
        
        return floatval(preg_replace('/[^0-9.]/', '', $cleaned));
    }
    
    /**
     * 💰 Helper: Membersihkan input numerik dari format mata uang (Misal: 29000.00 -> "29.000,00")
     */
    protected function reformatNumericInput($floatValue)
    {
        if (!is_numeric($floatValue)) return '';
        // Konversi float ke string dengan format ribuan/desimal Indonesia
        return number_format($floatValue, 2, ',', '.');
    }


    /**
     * 🏗 Helper: Update status proyek setelah kwitansi dibuat
     */
    protected function updateProyekStatusAfterKwitansi($sumberTabel, $idSumber)
    {
        try {
            $table = strtolower(trim($sumberTabel));

            if ($table !== 'rabs' && $table !== 'rab') {
                return;
            }

            $rab = Rab::where('id_rab', $idSumber)->first();
            
            if (!$rab || !$rab->id_proyek) {
                Log::warning("RAB #{$idSumber} tidak memiliki id_proyek");
                return;
            }

            $proyek = Proyek::where('id_proyek', $rab->id_proyek)->first();
            
            if (!$proyek) {
                Log::warning("Proyek dengan id {$rab->id_proyek} tidak ditemukan");
                return;
            }

            if (defined('Proyek::STATUS_RAB_TELAH_DIBUAT') && defined('Proyek::STATUS_PROYEK_DIKERJAKAN') && $proyek->status === Proyek::STATUS_RAB_TELAH_DIBUAT) {
                $proyek->status = Proyek::STATUS_PROYEK_DIKERJAKAN;
                $proyek->save();

                Log::info("Status proyek #{$proyek->id_proyek} diupdate menjadi 'Proyek Dikerjakan'");
            }

        } catch (\Exception $e) {
            Log::error('Error updating proyek status: ' . $e->getMessage());
        }
    }

            /**
     * 📋 INDEX - Tampilkan semua kwitansi
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'terbaru');
        $sumberFilter = $request->query('sumber');
        
        $query = Kwitansi::query();

        // Filter berdasarkan sumber (RAB/Penjualan)
        if ($sumberFilter && in_array($sumberFilter, ['rabs', 'penjualan'])) {
            $query->where('Sumber_Tabel', $sumberFilter);
        }

        // Sorting
        switch ($sortBy) {
            case 'terlama':
                $query->orderBy('Tanggal_Kwitansi', 'asc');
                break;
            case 'total_tertinggi':
                $query->orderBy('Total', 'desc');
                break;
            case 'total_terendah':
                $query->orderBy('Total', 'asc');
                break;
            case 'lunas':
                $query->where('Status', 'Lunas')->orderBy('Tanggal_Kwitansi', 'desc');
                break;
            case 'belum_lunas':
                $query->where('Status', 'like', 'DP %')->orderBy('Tanggal_Kwitansi', 'desc');
                break;
            case 'dp_50':
                $query->where(function($q) {
                    for ($i = 50; $i <= 99; $i++) {
                        $q->orWhere('Status', "DP {$i}%");
                    }
                })->orderBy('Tanggal_Kwitansi', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('Tanggal_Kwitansi', 'desc');
                break;
        }

        $kwitansi = $query->get();

        // ✅ PERBAIKAN: Tambahkan informasi sumber object untuk setiap kwitansi
        $kwitansi->each(function ($k) {
            $k->sumber_obj = $this->findSumberObject($k->Sumber_Tabel, $k->Id_Sumber) ?? null;
            
            // Tambahkan informasi Owner/Sales
            if ($k->sumber_obj) {
                if ($k->Sumber_Tabel === 'rabs' || $k->Sumber_Tabel === 'rab') {
                    // Jika dari RAB, ambil nama proyek
                    $rab = $k->sumber_obj;
                    if ($rab && isset($rab->id_proyek)) {
                        $proyek = Proyek::where('id_proyek', $rab->id_proyek)->first();
                        $k->owner = $proyek->nama_owner ?? '-';
                    } else {
                        $k->owner = '-';
                    }
                } elseif ($k->Sumber_Tabel === 'penjualan' || $k->Sumber_Tabel === 'penjualans') {
                    // Jika dari Penjualan, ambil nama sales
                    $k->owner = $k->sumber_obj->nama_sales ?? '-';
                }
            } else {
                $k->owner = '-';
            }
        });

        return view('kwitansi.index', compact('kwitansi', 'search'));
    }

    /**
     * 📝 CREATE - Form tambah kwitansi baru
     */
    public function create(Request $request)
    {
        $isKasir = $this->isKasir();

        $rabs = !$isKasir ? Rab::where('status', 'Disetujui')->get() : collect([]);

        $penjualan = Penjualan::with('details')->get();

        $prefillIdSumber = $request->query('id_sumber');
        $prefillSumberTabel = $request->query('sumber_tabel');

        if ($isKasir) {
            $prefillSumberTabel = 'penjualan';
        }

        return view('kwitansi.create', compact(
            'rabs',
            'penjualan',
            'prefillIdSumber',
            'prefillSumberTabel',
            'isKasir'
        ));
    }

    /**
     * 🔄 AJAX: Get data list berdasarkan sumber (RAB/Penjualan)
     */
    public function getDataBySumber(Request $request)
    {
        try {
            $sumberTabel = strtolower(trim($request->input('sumber_tabel')));
            $isKasir = $this->isKasir();

            if ($isKasir && !in_array($sumberTabel, ['penjualan', 'penjualans'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasir hanya dapat mengakses data penjualan',
                ], 403);
            }

            $data = [];

            if (in_array($sumberTabel, ['rab', 'rabs'])) {
                $data = Rab::where('status', 'Disetujui')
                    ->get()
                    ->map(function ($rab) {
                        return [
                            'id' => $rab->id_rab,
                            'text' => "RAB #{$rab->no_rab} - {$rab->nama_pekerjaan}",
                            'total' => $rab->total,
                        ];
                    });
            } 
            elseif (in_array($sumberTabel, ['penjualan', 'penjualans'])) {
                $data = Penjualan::with('details')
                    ->get()
                    ->map(function ($penjualan) {
                        return [
                            'id' => $penjualan->id_penjualan,
                            'text' => "Penjualan #{$penjualan->id_penjualan} - {$penjualan->nama_sales} ({$penjualan->tanggal})",
                            'total' => $penjualan->total,
                            'sales' => $penjualan->nama_sales,
                        ];
                    });
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error getDataBySumber: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
            ], 500);
        }
    }

                /**
         * 🔍 SEARCH - Mencari kwitansi berdasarkan Nama Proyek (RAB) atau Nama Sales (Penjualan)
         * Route: GET /kwitansi/search?keyword=xxx
         */
        public function search(Request $request)
        {
            try {
                $keyword = $request->input('keyword', '');
                
                // Validasi keyword
                if (empty($keyword)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Keyword pencarian tidak boleh kosong',
                        'data' => []
                    ]);
                }

                // ✅ PERBAIKAN: Ambil semua kwitansi dulu
                $kwitansi = Kwitansi::orderBy('Tanggal_Kwitansi', 'desc')->get();

                // ✅ PERBAIKAN: Filter berdasarkan nama proyek atau nama sales
                $hasil = collect();

                foreach ($kwitansi as $k) {
                    $k->sumber_obj = $this->findSumberObject($k->Sumber_Tabel, $k->Id_Sumber);
                    
                    $cocok = false;
                    
                    // Cek berdasarkan sumber
                    if ($k->Sumber_Tabel === 'rabs' || $k->Sumber_Tabel === 'rab') {
                        // ✅ Untuk RAB: Cari berdasarkan Nama Proyek
                        if ($k->sumber_obj && isset($k->sumber_obj->id_proyek)) {
                            $proyek = Proyek::where('id_proyek', $k->sumber_obj->id_proyek)->first();
                            if ($proyek) {
                                $namaProyek = $proyek->nama_proyek ?? '';
                                // Case-insensitive search
                                if (stripos($namaProyek, $keyword) !== false) {
                                    $cocok = true;
                                    $k->owner = $proyek->nama_owner ?? '-';
                                }
                            }
                        }
                    } elseif ($k->Sumber_Tabel === 'penjualan' || $k->Sumber_Tabel === 'penjualans') {
                        // ✅ Untuk Penjualan: Cari berdasarkan Nama Sales
                        if ($k->sumber_obj) {
                            $namaSales = $k->sumber_obj->nama_sales ?? '';
                            // Case-insensitive search
                            if (stripos($namaSales, $keyword) !== false) {
                                $cocok = true;
                                $k->owner = $namaSales;
                            }
                        }
                    }
                    
                    // Tambahkan ke hasil jika cocok
                    if ($cocok) {
                        $hasil->push($k);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => "Ditemukan {$hasil->count()} hasil pencarian",
                    'data' => $hasil->values()
                ]);

            } catch (\Throwable $e) {
                Log::error('Error search kwitansi: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mencari data',
                    'error' => $e->getMessage()
                ], 500);
            }
        }
    
    /**
     * 💰 AJAX: Get total dari sumber yang dipilih
     */
    public function getTotalSumber(Request $request)
    {
        try {
            $sumberTabel = strtolower(trim($request->input('sumber_tabel')));
            $idSumber = $request->input('id_sumber');

            if (!$sumberTabel || !$idSumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter sumber_tabel dan id_sumber harus diisi',
                ], 400);
            }

            $obj = $this->findSumberObject($sumberTabel, $idSumber);

            if (!$obj) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sumber tidak ditemukan',
                ], 404);
            }

            $total = 0;
            $sales = null;

            if ($sumberTabel === 'rabs' || $sumberTabel === 'rab') {
                $total = $obj->total ?? 0;
            } elseif ($sumberTabel === 'penjualan' || $sumberTabel === 'penjualans') {
                $total = $obj->total ?? 0;
                $sales = $obj->nama_sales ?? null;
            }

            return response()->json([
                'success' => true,
                'total' => round(floatval($total), 2),
                'sales' => $sales,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error getTotalSumber: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil total',
            ], 500);
        }
    }

    /**
     * 🔍 AJAX: Get Penjualan Data dengan Search dan Sort untuk Modal
     */
    public function getPenjualanData(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $sort = $request->input('sort', '');
            $isKasir = $this->isKasir();

            $query = Penjualan::with('details');

            // Filter search berdasarkan nama sales
            if (!empty($search)) {
                $query->where('nama_sales', 'like', '%' . $search . '%');
            }

            // Sort berdasarkan total
            if ($sort === 'tertinggi') {
                $query->orderBy('total', 'desc');
            } elseif ($sort === 'terendah') {
                $query->orderBy('total', 'asc');
            } else {
                $query->orderBy('tanggal', 'desc'); // default
            }

            $data = $query->get()->map(function ($penjualan) {
                return [
                    'id' => $penjualan->id_penjualan,
                    'nama_sales' => $penjualan->nama_sales ?? '-',
                    'tanggal' => $penjualan->tanggal,
                    'total' => $penjualan->total,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error getPenjualanData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
            ], 500);
        }
    }

    /**
     * 🔍 AJAX: Get RAB Data dengan Search dan Sort untuk Modal
     */
    public function getRabData(Request $request)
    {
        try {
            $search = $request->input('search', '');
            $sort = $request->input('sort', '');
            $isKasir = $this->isKasir();

            if ($isKasir) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasir tidak memiliki akses ke data RAB',
                ], 403);
            }

            $query = Rab::where('status', 'Disetujui');

            // Filter search berdasarkan no_rab atau nama_pekerjaan
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('no_rab', 'like', '%' . $search . '%')
                      ->orWhere('nama_pekerjaan', 'like', '%' . $search . '%')
                      ->orWhere('perihal', 'like', '%' . $search . '%');
                });
            }

            // Sort berdasarkan total
            if ($sort === 'tertinggi') {
                $query->orderBy('total', 'desc');
            } elseif ($sort === 'terendah') {
                $query->orderBy('total', 'asc');
            } else {
                $query->orderBy('created_at', 'desc'); // default
            }

            $data = $query->get()->map(function ($rab) {
                return [
                    'id' => $rab->id_rab,
                    'no_rab' => $rab->no_rab ?? '-',
                    'nama_pekerjaan' => $rab->nama_pekerjaan ?? '-',
                    'total' => $rab->total,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error getRabData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
            ], 500);
        }
    }


    /**
     * 💾 STORE - Menyimpan kwitansi baru ke database
     */
    public function store(Request $request)
    {
        $isKasir = $this->isKasir();

        // ✅ PERBAIKAN: Hapus min:0 dari validasi string
        $rules = [
            'Id_Sumber'         => 'required|string|max:255', 
            'Sumber_Tabel'      => $isKasir 
                ? 'required|string|in:penjualan' 
                : 'required|string|in:rabs,penjualan',
            'Sales'             => 'required|string|max:100',
            'Tanggal_Kwitansi'  => 'required|date',
            'Total'             => 'required|string', // ✅ Hapus min:0
            'Total_Pembayaran'  => 'required|string', // ✅ Hapus min:0
            'Metode_Pembayaran' => 'required|string|in:Cash,QRIS,Transfer',
            'Untuk_Pembayaran'  => 'nullable|string',
        ];

        // 1. VALIDASI DATA (Validasi String Sederhana)
        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
             throw $e; 
        }

        // 2. DATA PREPARATION & CLEANUP
        $validated['Total'] = $this->cleanNumericInput($validated['Total']);
        $validated['Total_Pembayaran'] = $this->cleanNumericInput($validated['Total_Pembayaran']);
        
        // 3. VALIDASI MANUAL: Cek apakah nilai numeric valid
        if ($validated['Total'] <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['Total' => 'Total harus lebih dari 0']);
        }

        if ($validated['Total_Pembayaran'] <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['Total_Pembayaran' => 'Total Pembayaran harus lebih dari 0']);
        }
        
        // 4. RE-VALIDASI UNTUK PERBANDINGAN NUMERIC (lte) secara manual
        if ($validated['Total_Pembayaran'] > $validated['Total']) {
            $inputBack = $request->input();
            $inputBack['Total'] = $this->reformatNumericInput($validated['Total']);
            $inputBack['Total_Pembayaran'] = $this->reformatNumericInput($validated['Total_Pembayaran']);

            return redirect()
                ->back()
                ->withInput($inputBack)
                ->withErrors(['Total_Pembayaran' => 'Total Pembayaran tidak boleh melebihi Total Tagihan.']);
        }
        
        // Hitung status otomatis
        $validated['Status'] = $this->calculateStatus(
            $validated['Total_Pembayaran'],
            $validated['Total']
        );

        DB::beginTransaction();

        try {
            $kwitansi = Kwitansi::create($validated);

            $this->updateProyekStatusAfterKwitansi(
                $validated['Sumber_Tabel'],
                $validated['Id_Sumber']
            );
            
            $noKwitansi = $kwitansi->Id_Kwitansi;
            $kwitansiWithInfo = clone $kwitansi;
            $kwitansiWithInfo->no_kwitansi = $noKwitansi;
            $kwitansiWithInfo->penerima = $kwitansi->Sales;
            $kwitansiWithInfo->jumlah = $kwitansi->Total_Pembayaran; 

            Notification::createKwitansiNotification('created', $kwitansiWithInfo);
            if ($validated['Status'] == 'Lunas') {
                Notification::createKwitansiNotification('lunas', $kwitansiWithInfo);
            } else {
                Notification::createKwitansiNotification('belum_lunas', $kwitansiWithInfo);
            }
            DB::commit();
            
            return redirect()
                ->route('kwitansi.index')
                ->with('success', 'Kwitansi berhasil ditambahkan dengan ID: ' . $noKwitansi . ', Status: ' . $validated['Status']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating kwitansi: ' . $e->getMessage());

            $inputBack = $request->input();
            $inputBack['Total'] = $this->reformatNumericInput($validated['Total']);
            $inputBack['Total_Pembayaran'] = $this->reformatNumericInput($validated['Total_Pembayaran']);

            return redirect()
                ->back()
                ->withInput($inputBack)
                ->with('error', 'Gagal menyimpan kwitansi: Terjadi kesalahan server. Silakan cek log.');
        }
    }

    /**
     * ✏ EDIT - Form edit kwitansi
     */
    public function edit($id_kwitansi)
    {
        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
        $isKasir = $this->isKasir();

        if ($isKasir && $kwitansi->Sumber_Tabel !== 'penjualan') {
            return redirect()
                ->route('kwitansi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit kwitansi ini');
        }

        $rabs = !$isKasir ? Rab::where('status', 'Disetujui')->get() : collect([]);
        $penjualan = Penjualan::with('details')->get();

        $kwitansi->sumber_obj = $this->findSumberObject($kwitansi->Sumber_Tabel, $kwitansi->Id_Sumber) ?? null;

        return view('kwitansi.edit', compact('kwitansi', 'rabs', 'penjualan', 'isKasir'));
    }

    /**
     * 🔄 UPDATE - Update kwitansi
     */
    public function update(Request $request, $id_kwitansi)
    {
        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
        $isKasir = $this->isKasir();

        $oldStatus = $kwitansi->Status;

        if ($isKasir && $kwitansi->Sumber_Tabel !== 'penjualan') {
            return redirect()
                ->route('kwitansi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate kwitansi ini');
        }

        // ✅ PERBAIKAN: Hapus min:0 dari validasi string
        $rules = [
            'Id_Sumber'         => 'required|string|max:255',
            'Sumber_Tabel'      => $isKasir 
                ? 'required|string|in:penjualan' 
                : 'required|string|in:rabs,penjualan',
            'Sales'             => 'required|string|max:100',
            'Tanggal_Kwitansi'  => 'required|date',
            'Total'             => 'required|string', // ✅ Hapus min:0
            'Total_Pembayaran'  => 'required|string', // ✅ Hapus min:0
            'Metode_Pembayaran' => 'required|string|in:Cash,QRIS,Transfer',
            'Untuk_Pembayaran'  => 'nullable|string',
        ];

        try {
            $validated = $request->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }

        $validated['Total'] = $this->cleanNumericInput($validated['Total']);
        $validated['Total_Pembayaran'] = $this->cleanNumericInput($validated['Total_Pembayaran']);

        // VALIDASI MANUAL: Cek apakah nilai numeric valid
        if ($validated['Total'] <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['Total' => 'Total harus lebih dari 0']);
        }

        if ($validated['Total_Pembayaran'] <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['Total_Pembayaran' => 'Total Pembayaran harus lebih dari 0']);
        }

        // Re-Validasi Numeric (lte) secara manual
        if ($validated['Total_Pembayaran'] > $validated['Total']) {
            $inputBack = $request->input();
            $inputBack['Total'] = $this->reformatNumericInput($validated['Total']);
            $inputBack['Total_Pembayaran'] = $this->reformatNumericInput($validated['Total_Pembayaran']);

            return redirect()
                ->back()
                ->withInput($inputBack)
                ->withErrors(['Total_Pembayaran' => 'Total Pembayaran tidak boleh melebihi Total Tagihan.']);
        }
        
        $validated['Status'] = $this->calculateStatus(
            $validated['Total_Pembayaran'],
            $validated['Total']
        );

        try {
            DB::beginTransaction();

            $kwitansi->update($validated);
            
            $kwitansi->refresh();

            $noKwitansi = $kwitansi->Id_Kwitansi; 
            $kwitansiWithInfo = clone $kwitansi;
            $kwitansiWithInfo->no_kwitansi = $noKwitansi;
            $kwitansiWithInfo->penerima = $kwitansi->Sales;
            $kwitansiWithInfo->jumlah = $kwitansi->Total_Pembayaran;
            
            if ($oldStatus != $validated['Status']) {
                if ($validated['Status'] === 'Lunas') {
                    Notification::createKwitansiNotification('lunas', $kwitansiWithInfo);
                } else {
                    Notification::createKwitansiNotification('belum_lunas', $kwitansiWithInfo);
                }
            } else {
                Notification::createKwitansiNotification('updated', $kwitansiWithInfo);
            }

            DB::commit();

            return redirect()
                ->route('kwitansi.index')
                ->with('success', 'Data kwitansi berhasil diperbarui! Status: ' . $validated['Status']);

        } catch (Exception $e) {
            DB::rollBack();
            
            $inputBack = $request->input();
            $inputBack['Total'] = $this->reformatNumericInput($validated['Total']);
            $inputBack['Total_Pembayaran'] = $this->reformatNumericInput($validated['Total_Pembayaran']);

            return redirect()
                ->back()
                ->withInput($inputBack)
                ->with('error', 'Gagal memperbarui kwitansi: ' . $e->getMessage());
        }
    }

        /**
     * 🗑 DESTROY - Hapus kwitansi
     */
    public function destroy($id_kwitansi)
    {
        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
        $isKasir = $this->isKasir(); // ✅ PERBAIKAN: Tambahkan isKasir()
        
        if ($isKasir && $kwitansi->Sumber_Tabel !== 'penjualan') {
            return redirect()
                ->route('kwitansi.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus kwitansi ini');
        } // ✅ PERBAIKAN: Tambahkan closing brace

        try {
            DB::beginTransaction();

            $noKwitansi = $kwitansi->Id_Kwitansi; 
            $kwitansiData = clone $kwitansi;
            $kwitansiData->no_kwitansi = $noKwitansi;
            $kwitansiData->penerima = $kwitansi->Sales;
            $kwitansiData->jumlah = $kwitansi->Total_Pembayaran;

            $kwitansi->delete();
            
            Notification::createKwitansiNotification('deleted', $kwitansiData);

            DB::commit();

            return redirect()
                ->route('kwitansi.index')
                ->with('success', 'Kwitansi ' . $noKwitansi . ' berhasil dihapus!');

        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus kwitansi: ' . $e->getMessage());
        }
    }

    /**
     * 📥 DOWNLOAD PDF - Download kwitansi dalam format PDF
     */
    public function downloadPDF($id_kwitansi)
    {
        try {
            $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
            $sumber = $this->findSumberObject($kwitansi->Sumber_Tabel, $kwitansi->Id_Sumber);
            $tanggal = \Carbon\Carbon::parse($kwitansi->Tanggal_Kwitansi)
                ->locale('id')
                ->isoFormat('DD MMMM YYYY');
            $noKwitansi = $kwitansi->Id_Kwitansi;
            
            $data = [
                'kwitansi' => $kwitansi,
                'sumber' => $sumber,
                'tanggal' => $tanggal,
                'no_kwitansi' => $noKwitansi,
            ];
            
            $pdf = PDF::loadView('kwitansi.pdf_template', $data);
            $pdf->setPaper('A4', 'landscape');
            
            $kwitansiWithInfo = clone $kwitansi;
            $kwitansiWithInfo->no_kwitansi = $noKwitansi;
            $kwitansiWithInfo->penerima = $kwitansi->Sales;
            $kwitansiWithInfo->jumlah = $kwitansi->Total_Pembayaran;
            
            Notification::createKwitansiNotification('printed', $kwitansiWithInfo);
            
            $fileName = 'Kwitansi_' . str_replace('-', '_', $noKwitansi) . '.pdf';
            
            return $pdf->download($fileName);
            
        } catch (Exception $e) {
            Log::error('Error downloading kwitansi PDF: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Gagal mendownload kwitansi: ' . $e->getMessage());
        }
    }
    }