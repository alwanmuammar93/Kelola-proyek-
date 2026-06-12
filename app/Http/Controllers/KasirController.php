<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir.index');
    }

    // ➕ Tambahan untuk menu Kelola Penjualan
    public function penjualan()
    {
        return view('kasir.penjualan'); // pastikan file ini ada
    }
}
