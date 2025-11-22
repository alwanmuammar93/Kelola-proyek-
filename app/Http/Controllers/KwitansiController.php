<?php

namespace App\Http\Controllers;

use App\Models\Kwitansi;
use App\Models\Rab;
use App\Models\Penjualan;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class KwitansiController extends Controller
{
    /**
     * Temukan object sumber berdasarkan nama tabel (bisa variasi) dan id.
     * Lebih toleran: jika primary key model tidak standar, coba beberapa kolom (id, id_rab, id_barang).
     */
    protected function findSumberObject(string $sumberTabel, $id)
    {
        $table = strtolower(trim($sumberTabel ?? ''));

        switch ($table) {
            case 'rabs':
            case 'rab':
                // Coba cari dengan find() dulu, jika model punya primaryKey yang benar, ini cepat.
                $obj = Rab::find($id);
                if ($obj) return $obj;

                // Fallback: coba kolom umum yang mungkin dipakai sebagai PK di DB
                $obj = Rab::where('id_rab', $id)->first();
                if ($obj) return $obj;

                // Jika masih tidak ditemukan, coba kolom 'id'
                return Rab::where('id', $id)->first();

            case 'penjualans':
            case 'penjualan':
            case 'penjualan_items':
                // Penjualan kadang disimpan dengan key id_barang -> cari dulu berdasarkan id_barang
                $obj = Penjualan::find($id);
                if ($obj) return $obj;

                $obj = Penjualan::where('id_barang', $id)->first();
                if ($obj) return $obj;

                return Penjualan::where('id', $id)->first();

            case 'proyeks':
            case 'proyek':
                $obj = Proyek::find($id);
                if ($obj) return $obj;

                return Proyek::where('id_proyek', $id)->first();

            default:
                return null;
        }
    }

    public function index(Request $request)
    {
        $idProyek = $request->query('id_proyek');
        $query = Kwitansi::query();

        if ($idProyek) {
            $query->where(function ($q) use ($idProyek) {
                $q->where('Sumber_Tabel', 'rabs')
                  ->orWhere('Sumber_Tabel', 'penjualan');
            });
        }

        if ($idProyek) {
            $idRabs = Rab::where('id_proyek', $idProyek)->pluck('id_rab');
            $idBarang = Penjualan::pluck('id_barang');

            $query->where(function ($q) use ($idRabs, $idBarang) {
                $q->whereIn('Id_Sumber', $idRabs)
                  ->orWhereIn('Id_Sumber', $idBarang);
            });
        }

        $kwitansi = $query->orderBy('created_at', 'desc')->get();

        $kwitansi->each(function ($k) {
            $k->sumber_obj = $this->findSumberObject($k->Sumber_Tabel, $k->Id_Sumber) ?? null;
        });

        $proyek = $idProyek
            ? collect([Proyek::find($idProyek)])->filter()
            : Proyek::all();

        return view('kwitansi.index', compact('kwitansi', 'proyek', 'idProyek'));
    }

    public function indexByProject($id_proyek)
    {
        $request = new Request(['id_proyek' => $id_proyek]);
        return $this->index($request);
    }

    public function create(Request $request)
    {
        $rabs = Rab::where('status', 'Disetujui')->get();

        $penjualan = Penjualan::select('id_barang', 'nama_barang', 'jumlah', 'harga_satuan')
            ->get()
            ->map(function ($p) {
                $p->total_penjualan = ($p->jumlah ?? 0) * ($p->harga_satuan ?? 0);
                return $p;
            });

        // 🔥 PERBAIKAN PENTING: DROPDOWN PROYEK HARUS ADA
        $proyeks = Proyek::all();

        $prefillIdSumber = $request->query('id_rab') ?? $request->query('id_barang') ?? $request->query('id') ?? $request->query('id_sumber') ?? null;
        $prefillSumberTabel = $request->query('sumber_tabel') ?? $request->query('sumber') ?? null;

        return view('kwitansi.create', compact(
            'rabs',
            'penjualan',
            'proyeks',
            'prefillIdSumber',
            'prefillSumberTabel'
        ));
    }

    /**
     * Endpoint AJAX untuk mengambil total dari sumber (RAB / Penjualan).
     * Menerima banyak variasi parameter:
     * - query param atau body: sumber / sumber_tabel / sumberTable
     * - id / id_sumber / id_rab / id_barang
     *
     * Respons JSON:
     * { success: true/false, total: <number>, message: <string> }
     */
    public function getTotalSumber(Request $request)
    {
        try {
            // Ambil nama sumber (bisa lewat query string atau body), toleran terhadap beberapa nama
            $sumberTabel = $request->query('sumber')
                ?? $request->query('sumber_tabel')
                ?? $request->input('sumber_tabel')
                ?? $request->input('sumber')
                ?? $request->input('sumberTable')
                ?? null;

            if ($sumberTabel) {
                $sumberTabel = strtolower(trim($sumberTabel));
            }

            // Ambil id sumber dari beberapa kemungkinan nama parameter
            $idSumber = $request->query('id')
                ?? $request->query('id_sumber')
                ?? $request->query('id_rab')
                ?? $request->query('id_barang')
                ?? $request->input('id_sumber')
                ?? $request->input('id')
                ?? $request->input('id_rab')
                ?? $request->input('id_barang')
                ?? null;

            // Jika masih null, coba cek route param (mis. /rab/total/{id})
            if (!$idSumber && $request->route() && $request->route()->parameters()) {
                $routeParams = $request->route()->parameters();
                // cari value numeric pertama
                foreach ($routeParams as $p) {
                    if (is_numeric($p)) {
                        $idSumber = $p;
                        break;
                    }
                }
            }

            if (!$sumberTabel || !$idSumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter "sumber" atau "id" tidak ditemukan. Pastikan request mengirim salah satu: sumber/sumber_tabel dan id/id_sumber/id_rab/id_barang.',
                ], 400);
            }

            // Normalisasi id jadi integer bila memungkinkan
            if (is_numeric($idSumber)) {
                $idSumber = intval($idSumber);
            }

            $obj = $this->findSumberObject($sumberTabel, $idSumber);

            if (!$obj) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sumber tidak ditemukan untuk kombinasi sumber = ' . $sumberTabel . ' dan id = ' . $idSumber,
                ], 404);
            }

            // Mencari atribut total dengan beberapa nama kemungkinan
            $total = 0;

            switch ($sumberTabel) {
                case 'rabs':
                case 'rab':
                    // coba beberapa nama atribut yang mungkin menyimpan total biaya
                    if (isset($obj->total_rab)) {
                        $total = $obj->total_rab;
                    } elseif (isset($obj->Total_Biaya)) {
                        $total = $obj->Total_Biaya;
                    } elseif (isset($obj->total_biaya)) {
                        $total = $obj->total_biaya;
                    } elseif (isset($obj->total)) {
                        $total = $obj->total;
                    } else {
                        // Fallback: jika RAB memiliki relasi item-biaya, coba sum (jika ada)
                        if (method_exists($obj, 'items')) {
                            try {
                                $total = $obj->items()->sum('total_harga');
                            } catch (\Throwable $e) {
                                $total = 0;
                            }
                        }
                    }
                    break;

                case 'penjualans':
                case 'penjualan':
                case 'penjualan_items':
                    if (isset($obj->total)) {
                        $total = $obj->total;
                    } elseif (isset($obj->total_penjualan)) {
                        $total = $obj->total_penjualan;
                    } else {
                        // Kalkulasi manual jika ada jumlah dan harga_satuan
                        $jumlah = $obj->jumlah ?? 0;
                        $harga = $obj->harga_satuan ?? ($obj->harga ?? 0);
                        $total = ($jumlah * $harga);
                    }
                    break;

                default:
                    $total = 0;
                    break;
            }

            // Pastikan total numeric
            $totalNumeric = is_numeric($total) ? floatval($total) : 0.0;

            return response()->json([
                'success' => true,
                'total' => round($totalNumeric, 2),
            ]);
        } catch (\Throwable $e) {
            // Log error untuk debugging (tidak menampilkan stacktrace ke user)
            Log::error('Error getTotalSumber: ' . $e->getMessage(), [
                'sumber' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil total dari server.',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Id_Sumber' => 'required|integer',
            'Sumber_Tabel' => 'required|string|in:rabs,penjualan',
            'Sales' => 'required|string|max:150',
            'Tanggal_Kwitansi' => 'required|date',
            'Total' => 'required|numeric|min:0',
            'Metode_Pembayaran' => 'required|string|max:150',
            'Status' => 'required|in:Lunas,Belum Lunas',
        ]);

        Kwitansi::create($validated);

        return redirect()
            ->route('kwitansi.index')
            ->with('success', 'Kwitansi berhasil ditambahkan!');
    }

    public function edit($id_kwitansi)
    {
        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
        $rabs = Rab::where('status', 'Disetujui')->get();

        $penjualan = Penjualan::select('id_barang', 'nama_barang', 'jumlah', 'harga_satuan')
            ->get()
            ->map(function ($p) {
                $p->total_penjualan = ($p->jumlah ?? 0) * ($p->harga_satuan ?? 0);
                return $p;
            });

        // 🔥 PERBAIKAN: DROPDOWN PROYEK DINI-UP
        $proyeks = Proyek::all();

        $kwitansi->sumber_obj = $this->findSumberObject($kwitansi->Sumber_Tabel, $kwitansi->Id_Sumber) ?? null;

        return view('kwitansi.edit', compact('kwitansi', 'rabs', 'penjualan', 'proyeks'));
    }

    public function update(Request $request, $id_kwitansi)
    {
        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();

        $validated = $request->validate([
            'Id_Sumber' => 'required|integer',
            'Sumber_Tabel' => 'required|string|in:rabs,penjualan',
            'Sales' => 'required|string|max:150',
            'Tanggal_Kwitansi' => 'required|date',
            'Total' => 'required|numeric|min:0',
            'Metode_Pembayaran' => 'required|string|max:150',
            'Status' => 'required|in:Lunas,Belum Lunas',
        ]);

        $kwitansi->update($validated);

        return redirect()
            ->route('kwitansi.index')
            ->with('success', 'Data kwitansi berhasil diperbarui!');
    }

    public function destroy($id_kwitansi)
    {
        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
        $kwitansi->delete();

        return redirect()
            ->route('kwitansi.index')
            ->with('success', 'Kwitansi berhasil dihapus!');
    }

    public function ubahStatus($id_kwitansi)
    {
        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
        $kwitansi->Status = ($kwitansi->Status === 'Lunas') ? 'Belum Lunas' : 'Lunas';
        $kwitansi->save();

        return back()->with('success', 'Status pembayaran berhasil diubah!');
    }

    public function ubahSales(Request $request, $id_kwitansi)
    {
        $request->validate(['Sales' => 'required|string|max:150']);

        $kwitansi = Kwitansi::where('Id_Kwitansi', $id_kwitansi)->firstOrFail();
        $kwitansi->Sales = $request->Sales;
        $kwitansi->save();

        return back()->with('success', 'Nama sales berhasil diubah!');
    }

    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $query = Kwitansi::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('Sales', 'like', "%{$keyword}%")
                  ->orWhere('Metode_Pembayaran', 'like', "%{$keyword}%")
                  ->orWhere('Status', 'like', "%{$keyword}%");
            });
        }

        $kwitansi = $query->orderBy('created_at', 'desc')->get();

        $kwitansi->each(function ($k) {
            $k->sumber_obj = $this->findSumberObject($k->Sumber_Tabel, $k->Id_Sumber) ?? null;
        });

        // 🔥 PERBAIKAN: dropdown proyek pada halaman search
        $proyek = Proyek::all();

        return view('kwitansi.index', compact('kwitansi', 'proyek'));
    }
}
