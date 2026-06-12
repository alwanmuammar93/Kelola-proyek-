<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\LaporanDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Rab;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    /**
     * ============================================
     * INDEX: Menampilkan daftar laporan
     * ============================================
     */
    public function index()
    {
        try {
            $laporan = Laporan::withCount('details')
                ->latest()
                ->get();
            
            Log::info('Laporan index loaded', ['count' => $laporan->count()]);
            
            return view('laporan.index', compact('laporan'));
            
        } catch (\Exception $e) {
            Log::error('Index laporan error', [
                'message' => $e->getMessage()
            ]);
            
            return view('laporan.index', ['laporan' => collect()]);
        }
    }

    /**
     * ============================================
     * CREATE: Menampilkan form create laporan
     * ============================================
     */
    public function create()
    {
        try {
            Log::info('Laporan create form loaded');
            return view('laporan.create');
            
        } catch (\Exception $e) {
            Log::error('Create form error', [
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('laporan.index')
                ->with('error', 'Gagal memuat form tambah laporan!');
        }
    }

    /**
     * ============================================
     * 🎯 AJAX METHOD: getData()
     * Endpoint: GET /laporan/get-data/{sumber}
     * Untuk dropdown "Pilih Data" setelah memilih sumber
     * ============================================
     */
    public function getData($sumber)
    {
        try {
            Log::info('getData called', ['sumber' => $sumber]);

            // Validasi parameter sumber
            if (!in_array($sumber, ['RAB', 'Penjualan'])) {
                Log::warning('getData: sumber tidak valid', ['sumber' => $sumber]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sumber data tidak valid. Harus RAB atau Penjualan',
                    'data' => []
                ], 400);
            }

            // ========== SUMBER: RAB ==========
            if ($sumber === 'RAB') {
                $data = DB::table('rabs')
                    ->select(
                        'id_rab as id',
                        'no_rab',
                        'perihal',
                        'owner',
                        'jumlah',
                        'total',
                        'created_at',
                        'rincian_pekerjaan'
                    )
                    ->orderBy('id_rab', 'desc')
                    ->get()
                    ->map(function ($item) {
                        // Hitung total dari rincian_pekerjaan jika ada
                        $total = 0;
                        if ($item->rincian_pekerjaan) {
                            $rincianList = json_decode($item->rincian_pekerjaan, true);
                            if (is_array($rincianList)) {
                                foreach ($rincianList as $rincian) {
                                    $subtotal = floatval($rincian['subtotal'] ?? 0);
                                    $total += $subtotal;
                                }
                            }
                        }
                        
                        // Fallback ke field total jika ada
                        if ($total === 0 && isset($item->total)) {
                            $total = floatval($item->total);
                        }

                        return [
                            'id' => $item->id,
                            'no_rab' => $item->no_rab,
                            'perihal' => $item->perihal ?? 'Tidak ada perihal',
                            'owner' => $item->owner ?? '-',
                            'total' => $total,
                            'tanggal' => $item->created_at,
                            'display_text' => $item->no_rab . " - " . ($item->perihal ?? 'Tidak ada perihal')
                        ];
                    });

                Log::info('getData RAB success', ['count' => $data->count()]);

                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => 'Data RAB berhasil dimuat'
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

            // ========== SUMBER: Penjualan ==========
            if ($sumber === 'Penjualan') {
                $data = Penjualan::with('details')
                    ->select('id_penjualan', 'nama_sales', 'tanggal', 'total', 'created_at')
                    ->orderBy('id_penjualan', 'desc')
                    ->get()
                    ->map(function ($penjualan) {
                        $tanggalFormatted = $penjualan->tanggal instanceof \Carbon\Carbon 
                            ? $penjualan->tanggal->format('Y-m-d') 
                            : date('Y-m-d', strtotime($penjualan->tanggal));
                        
                        $totalFormatted = 'Rp ' . number_format($penjualan->total, 0, ',', '.');
                        
                        return [
                            'id' => $penjualan->id_penjualan,
                            'nama_sales' => $penjualan->nama_sales,
                            'owner' => $penjualan->nama_sales,
                            'tanggal' => $tanggalFormatted,
                            'created_at' => $penjualan->created_at,
                            'total' => $penjualan->total,
                            'total_formatted' => $totalFormatted,
                            'display_text' => "{$penjualan->id_penjualan} - {$penjualan->nama_sales} ({$tanggalFormatted}) - {$totalFormatted}",
                            'jumlah_items' => $penjualan->details->count()
                        ];
                    });

                Log::info('getData Penjualan success', ['count' => $data->count()]);

                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => 'Data Penjualan berhasil dimuat'
                ], 200, [], JSON_UNESCAPED_UNICODE);
            }

        } catch (\Exception $e) {
            Log::error('getData error', [
                'sumber' => $sumber ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * ============================================
     * 🎯 AJAX METHOD: getDetail()
     * Endpoint: GET /laporan/get-detail/{sumber}/{id}
     * Untuk mendapatkan detail RAB/Penjualan setelah dipilih
     * ============================================
     */
    public function getDetail($sumber, $id)
    {
        try {
            Log::info('getDetail called', ['sumber' => $sumber, 'id' => $id]);

            // Validasi parameter
            if (!in_array($sumber, ['RAB', 'Penjualan'])) {
                Log::warning('getDetail: sumber tidak valid', ['sumber' => $sumber]);
                return response()->json([
                    'success' => false,
                    'message' => 'Sumber data tidak valid'
                ], 400);
            }

            if (empty($id) || !is_numeric($id)) {
                Log::warning('getDetail: ID tidak valid', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'ID tidak valid'
                ], 400);
            }

            // ========== SUMBER: RAB ==========
            if ($sumber === 'RAB') {
                $rab = DB::table('rabs')
                    ->where('id_rab', $id)
                    ->first();

                if (!$rab) {
                    Log::warning('getDetail: RAB tidak ditemukan', ['id' => $id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Data RAB tidak ditemukan'
                    ], 404);
                }

                $rincianList = json_decode($rab->rincian_pekerjaan, true);
                
                if (!is_array($rincianList) || empty($rincianList)) {
                    Log::warning('getDetail: rincian_pekerjaan kosong atau invalid');
                    return response()->json([
                        'success' => false,
                        'message' => 'Data RAB tidak memiliki rincian pekerjaan'
                    ], 404);
                }

                $details = [];
                $totalProfit = 0;

                foreach ($rincianList as $index => $rincian) {
                    $jumlah = floatval($rincian['jumlah'] ?? 0);
                    $biayaMaterial = floatval($rincian['biaya_material'] ?? 0);
                    
                    if (isset($rincian['subtotal'])) {
                        $subtotal = floatval($rincian['subtotal']);
                    } else {
                        $subtotal = $jumlah * $biayaMaterial;
                    }
                    
                    $profit = $subtotal;
                    $totalProfit += $profit;

                    $details[] = [
                        'rincian'      => $rincian['rincian'] ?? '-',
                        'jumlah'       => $jumlah,
                        'satuan'       => $rincian['satuan'] ?? 'UNIT',
                        'total'        => $subtotal,
                        'modal_satuan' => 0,
                        'total_modal'  => 0,
                        'profit'       => $profit
                    ];
                }

                $response = [
                    'success'        => true,
                    'sumber'         => 'RAB',
                    'id'             => $rab->id_rab,
                    'owner'          => $rab->owner ?? '-',
                    'no_rab'         => $rab->no_rab,
                    'perihal'        => $rab->perihal ?? '-',
                    'details'        => $details,
                    'total_profit'   => $totalProfit,
                    'jumlah_rincian' => count($details),
                    'message'        => 'Detail RAB berhasil dimuat'
                ];

                Log::info('getDetail RAB success', [
                    'id' => $id,
                    'jumlah_rincian' => count($details),
                    'total_profit' => $totalProfit
                ]);

                return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
            }

            // ========== SUMBER: Penjualan ==========
            if ($sumber === 'Penjualan') {
                $penjualan = Penjualan::with('details')
                    ->where('id_penjualan', $id)
                    ->first();

                if (!$penjualan) {
                    Log::warning('getDetail: Penjualan tidak ditemukan', ['id' => $id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Data Penjualan tidak ditemukan'
                    ], 404);
                }

                if ($penjualan->details->isEmpty()) {
                    Log::warning('getDetail: Penjualan tidak memiliki detail', ['id' => $id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Penjualan tidak memiliki detail barang'
                    ], 404);
                }

                $details = [];
                $totalProfit = 0;

                foreach ($penjualan->details as $detail) {
                    $jumlah = floatval($detail->jumlah);
                    $hargaSatuan = floatval($detail->harga_satuan);
                    $subtotal = floatval($detail->subtotal);
                    
                    $modalSatuan = 0;
                    $totalModal = 0;
                    $profit = $subtotal;

                    $totalProfit += $profit;

                    $details[] = [
                        'rincian'      => $detail->rincian,
                        'jumlah'       => $jumlah,
                        'satuan'       => 'PCS',
                        'total'        => $subtotal,
                        'modal_satuan' => $modalSatuan,
                        'total_modal'  => $totalModal,
                        'profit'       => $profit
                    ];
                }

                $response = [
                    'success'          => true,
                    'sumber'           => 'Penjualan',
                    'id'               => $penjualan->id_penjualan,
                    'owner'            => $penjualan->nama_sales,
                    'nama_sales'       => $penjualan->nama_sales,
                    'tanggal'          => $penjualan->tanggal,
                    'total_penjualan'  => $penjualan->total,
                    'details'          => $details,
                    'total_profit'     => $totalProfit,
                    'jumlah_rincian'   => count($details),
                    'message'          => 'Detail Penjualan berhasil dimuat'
                ];

                Log::info('getDetail Penjualan success', [
                    'id' => $id,
                    'nama_sales' => $penjualan->nama_sales,
                    'jumlah_rincian' => count($details),
                    'total_profit' => $totalProfit
                ]);

                return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE);
            }

        } catch (\Exception $e) {
            Log::error('getDetail error', [
                'sumber' => $sumber ?? 'unknown',
                'id' => $id ?? 'unknown',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat detail: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ============================================
     * STORE: Menyimpan laporan baru
     * 🔥 WITH NOTIFICATION SYSTEM
     * ============================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_laporan' => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'no_nota'      => 'nullable|string|max:255',
            'owner'        => 'required|string|max:255',
            'sumber'       => 'required|in:RAB,Penjualan',
            'data_id'      => 'required'
        ]);

        try {
            DB::beginTransaction();

            Log::info('Store laporan started', [
                'sumber' => $request->sumber,
                'data_id' => $request->data_id,
                'nama_laporan' => $request->nama_laporan,
                'detail_count' => is_array($request->detail) ? count($request->detail) : 0
            ]);

            $totalProfit = 0;

            if ($request->has('detail') && is_array($request->detail)) {
                foreach ($request->detail as $d) {
                    $profit = floatval($d['profit'] ?? 0);
                    $totalProfit += $profit;
                }
            }

            // SIMPAN LAPORAN UTAMA
            $laporan = Laporan::create([
                'nama_laporan' => $request->nama_laporan,
                'tanggal'      => $request->tanggal,
                'no_nota'      => $request->no_nota ?? '-',
                'owner'        => $request->owner,
                'total_profit' => $totalProfit
            ]);

            Log::info('Laporan header created', [
                'laporan_id' => $laporan->id,
                'total_profit' => $totalProfit
            ]);

            // SIMPAN DETAIL LAPORAN
            if ($request->has('detail') && is_array($request->detail)) {
                foreach ($request->detail as $index => $d) {
                    $total = floatval($d['total'] ?? 0);
                    $modalSatuan = floatval($d['modal_satuan'] ?? 0);
                    $jumlah = floatval($d['jumlah'] ?? 0);
                    $totalModal = floatval($d['total_modal'] ?? 0);
                    $profit = floatval($d['profit'] ?? 0);

                    $detail = LaporanDetail::create([
                        'laporan_id'   => $laporan->id,
                        'rincian'      => $d['rincian'] ?? '-',
                        'jumlah'       => $jumlah,
                        'satuan'       => $d['satuan'] ?? '-',
                        'total'        => $total,
                        'modal_satuan' => $modalSatuan,
                        'total_modal'  => $totalModal,
                        'profit'       => $profit,
                    ]);

                    Log::info('Detail created', [
                        'detail_id' => $detail->id,
                        'rincian' => $detail->rincian,
                        'profit' => $detail->profit
                    ]);
                }
            }

            // 🔥 BUAT NOTIFIKASI OTOMATIS
            Notification::createLaporanNotification('created', $laporan->fresh());
            
            // 🔥 Notifikasi tambahan berdasarkan profit
            if ($totalProfit >= 10000000) { // Profit >= 10 juta
                Notification::createLaporanNotification('high_profit', $laporan->fresh());
            } elseif ($totalProfit < 1000000) { // Profit < 1 juta
                Notification::createLaporanNotification('low_profit', $laporan->fresh());
            }

            DB::commit();

            Log::info('Laporan saved successfully', [
                'laporan_id' => $laporan->id,
                'total_profit' => $totalProfit,
                'detail_count' => $laporan->details()->count()
            ]);

            return redirect()->route('laporan.index')
                ->with('success', 'Laporan berhasil disimpan dengan ' . $laporan->details()->count() . ' rincian dan notifikasi telah dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Store laporan error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage());
        }
    }

    /**
     * ============================================
     * EDIT: Menampilkan form edit laporan
     * ============================================
     */
    public function edit($id)
    {
        try {
            $laporan = Laporan::with('details')->findOrFail($id);
            
            Log::info('Edit laporan loaded', [
                'laporan_id' => $laporan->id,
                'nama_laporan' => $laporan->nama_laporan,
                'details_count' => $laporan->details->count()
            ]);
            
            return view('laporan.edit', compact('laporan'));
            
        } catch (\Exception $e) {
            Log::error('Edit laporan error', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('laporan.index')
                ->with('error', 'Laporan tidak ditemukan!');
        }
    }

    /**
     * ============================================
     * UPDATE: Memproses update laporan
     * 🔥 WITH NOTIFICATION SYSTEM
     * ============================================
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_laporan' => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'no_nota'      => 'nullable|string|max:255',
            'owner'        => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $laporan = Laporan::findOrFail($id);
            
            // Simpan data lama untuk perbandingan
            $oldTotalProfit = $laporan->total_profit;
            
            Log::info('Update laporan started', [
                'laporan_id' => $id,
                'old_data' => [
                    'nama_laporan' => $laporan->nama_laporan,
                    'total_profit' => $oldTotalProfit
                ],
                'new_data' => $request->only(['nama_laporan', 'tanggal', 'no_nota', 'owner'])
            ]);

            // Hitung ulang total profit
            $totalProfit = 0;
            if ($request->has('detail') && is_array($request->detail)) {
                foreach ($request->detail as $d) {
                    $profit = floatval($d['profit'] ?? 0);
                    $totalProfit += $profit;
                }
            }

            // Update laporan header
            $laporan->update([
                'nama_laporan' => $request->nama_laporan,
                'tanggal'      => $request->tanggal,
                'no_nota'      => $request->no_nota ?? '-',
                'owner'        => $request->owner,
                'total_profit' => $totalProfit,
            ]);

            // Hapus detail lama
            $laporan->details()->delete();

            // Simpan detail baru
            if ($request->has('detail') && is_array($request->detail)) {
                foreach ($request->detail as $d) {
                    LaporanDetail::create([
                        'laporan_id'   => $laporan->id,
                        'rincian'      => $d['rincian'] ?? '-',
                        'jumlah'       => floatval($d['jumlah'] ?? 0),
                        'satuan'       => $d['satuan'] ?? '-',
                        'total'        => floatval($d['total'] ?? 0),
                        'modal_satuan' => floatval($d['modal_satuan'] ?? 0),
                        'total_modal'  => floatval($d['total_modal'] ?? 0),
                        'profit'       => floatval($d['profit'] ?? 0),
                    ]);
                }
            }

            // 🔥 BUAT NOTIFIKASI UPDATE
            Notification::createLaporanNotification('updated', $laporan->fresh());
            
            // 🔥 Notifikasi tambahan jika ada perubahan signifikan
            $selisihProfit = abs($totalProfit - $oldTotalProfit);
            if ($selisihProfit > 5000000) { // Selisih > 5 juta
                if ($totalProfit > $oldTotalProfit) {
                    Notification::createLaporanNotification('high_profit', $laporan->fresh());
                } else {
                    Notification::createLaporanNotification('low_profit', $laporan->fresh());
                }
            }

            DB::commit();

            Log::info('Update laporan success', [
                'laporan_id' => $laporan->id,
                'new_total_profit' => $totalProfit,
                'details_count' => $laporan->details()->count()
            ]);

            return redirect()->route('laporan.index')
                ->with('success', 'Laporan berhasil diupdate dan notifikasi telah dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Update laporan error', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal update laporan: ' . $e->getMessage());
        }
    }

    /**
     * ============================================
     * DESTROY: Hapus laporan
     * 🔥 WITH NOTIFICATION SYSTEM
     * ============================================
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $laporan = Laporan::findOrFail($id);
            
            // 🔥 Simpan data laporan sebelum dihapus
            $laporanData = clone $laporan;
            
            Log::info('Delete laporan started', [
                'laporan_id' => $id,
                'nama_laporan' => $laporan->nama_laporan,
                'details_count' => $laporan->details()->count()
            ]);
            
            // Hapus detail
            $laporan->details()->delete();
            
            // Hapus laporan
            $laporan->delete();

            // 🔥 BUAT NOTIFIKASI PENGHAPUSAN
            Notification::createLaporanNotification('deleted', $laporanData);

            DB::commit();

            Log::info('Delete laporan success', ['laporan_id' => $id]);

            return redirect()->route('laporan.index')
                ->with('success', 'Laporan berhasil dihapus dan notifikasi telah dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Delete laporan error', [
                'id' => $id,
                'message' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }
}