<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\Supplier;
use App\Barang;
use App\HargaBarang;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index() {
        $supplier = Supplier::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        // $barang = Barang::select('nama')->get();
        // foreach($barang as $b) {
        //     $namaBarang[] = $b->nama;
        // }
        // autonumber
        $lastcode = PurchaseOrder::max('id');
        // $lastnumber = (int) substr($lastcode, 3, 2);
        $lastcode++;
        $newcode = 'PO'.sprintf('%02s', $lastcode);
        // date now
        $tanggal = Carbon::now()->toDateString();
        $tanggal = Carbon::parse($tanggal)->format('d-m-Y');

        $data = [
            'items' => $supplier,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga
        ];

        return view('pages.poAlter', $data);
    }

    public function showHarga($id) {
        $harga = Harga::findOrFail($id)->first();
        
        $data = [
            'hargaBeli' => $harga
        ];

        return redirect()->route('po', $data);
    }
}
