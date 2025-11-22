<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\LaporanDetail;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::withCount('details')->latest()->get();
        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        // FIX: Kirim data dropdown ke Blade
        $rabs = DB::table('rabs')
            ->select(
                'id_rab as id',
                'no_rab',
                'perihal'
            )
            ->orderBy('id_rab', 'desc')
            ->get();

        $penjualans = DB::table('penjualans')
            ->select(
                'id_barang as id',
                'nama_barang'
            )
            ->orderBy('id_barang', 'desc')
            ->get();

        return view('laporan.create', compact('rabs', 'penjualans'));
    }

    public function getData(Request $request)
    {
        if (!$request->sumber) {
            return response()->json([]);
        }

        if ($request->sumber === 'RAB') {

            $data = DB::table('rabs')
                ->select(
                    'id_rab as id',
                    'no_rab',
                    'perihal',
                    'owner',
                    'jumlah',
                    'rincian_pekerjaan'
                )
                ->orderBy('id_rab', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->rincian = $item->no_rab . " - " . ($item->perihal ?? '');
                    return $item;
                });

            return response()->json($data);
        }

        $data = DB::table('penjualans')
            ->select(
                'id_barang as id',
                'nama_barang as rincian',
                'jumlah',
                'harga_satuan',
                'total'
            )
            ->orderBy('id_barang', 'desc')
            ->get();

        return response()->json($data);
    }

    public function getDetail(Request $request)
    {
        $source = $request->sumber;
        $id     = $request->id;

        if ($source === 'RAB') {

            $rab = DB::table('rabs')
                ->where('id_rab', $id)
                ->first();

            if (!$rab) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }

            $detailList = json_decode($rab->rincian_pekerjaan, true);
            $first = $detailList[0] ?? [];

            return response()->json([
                "id"           => $rab->id_rab,
                "rincian"      => $first['rincian'] ?? "",
                "jumlah"       => $first['jumlah'] ?? "",
                "satuan"       => $first['satuan'] ?? "",
                "total"        => $first['biaya_material'] ?? "",
                "modal_satuan" => 0,
                "total_modal"  => 0,
                "owner"        => $rab->owner,
            ]);
        }

        $penjualan = DB::table('penjualans')
            ->where('id_barang', $id)
            ->first();

        return response()->json([
            "id"           => $penjualan->id_barang,
            "rincian"      => $penjualan->nama_barang,
            "jumlah"       => $penjualan->jumlah,
            "satuan"       => "PCS",
            "total"        => $penjualan->total,
            "modal_satuan" => $penjualan->harga_satuan,
            "total_modal"  => $penjualan->harga_satuan * $penjualan->jumlah,
            "owner"        => "-",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_laporan' => 'required',
            'tanggal'      => 'required',
        ]);

        $totalProfit = 0;

        if ($request->has('detail')) {
            foreach ($request->detail as $d) {
                $profit = floatval($d['total']) - floatval($d['total_modal']);
                $totalProfit += $profit;
            }
        }

        $laporan = Laporan::create([
            'nama_laporan' => $request->nama_laporan,
            'tanggal'      => $request->tanggal,
            'no_nota'      => $request->no_nota,
            'owner'        => $request->owner,
            'total_profit' => $totalProfit,
        ]);

        if ($request->has('detail')) {
            foreach ($request->detail as $d) {

                LaporanDetail::create([
                    'laporan_id'   => $laporan->id,
                    'rincian'      => $d['rincian'],
                    'jumlah'       => $d['jumlah'],
                    'satuan'       => $d['satuan'],
                    'total'        => $d['total'],
                    'modal_satuan' => $d['modal_satuan'],
                    'total_modal'  => $d['total_modal'],
                    'profit'       => floatval($d['total']) - floatval($d['total_modal']),
                ]);
            }
        }

        return redirect()->route('laporan.index')
            ->with('success', 'Laporan berhasil disimpan!');
    }
}
