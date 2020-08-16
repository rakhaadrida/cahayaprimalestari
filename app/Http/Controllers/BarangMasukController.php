<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\DetilPO;
use App\Barang;
use App\Supplier;
use Carbon\Carbon;

class BarangMasukController extends Controller
{
    public function index() {
        $tanggal = Carbon::now()->toDateString();
        $tanggal = Carbon::parse($tanggal)->format('d-m-Y');

        $data = [
            'tanggal' => $tanggal
        ];

        return view('pages.pembelian.barangMasuk', $data);
    }
}
