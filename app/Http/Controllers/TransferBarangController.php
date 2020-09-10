<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gudang;
use App\Barang;
use App\StokBarang;
use Carbon\Carbon;

class TransferBarangController extends Controller
{
    public function index() {
        $barang = Barang::All();

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'barang' => $barang,
            'tanggal' => $tanggal
        ];

        return view('pages.pembelian.transferBarang', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }
}
