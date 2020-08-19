<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;
use App\Customer;
use App\Barang;
use App\DetilSO;
use App\HargaBarang;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index() {
        $customer = Customer::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $lastcode = SalesOrder::max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'SO'.sprintf('%02s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = Carbon::parse($tanggal)->format('d-m-Y');

        // $items = SalesOrder::where('id', $newcode)->get();
        $itemsRow = 0;

        $data = [
            'customer' => $customer,
            'barang' => $barang,
            'harga' => $harga,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'itemsRow' => $itemsRow
        ];

        return view('pages.penjualan.so', $data);
    }
}
