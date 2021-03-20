<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Gudang;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index() {
        $tanggal = Carbon::now()->toDateString();
        $items = SalesOrder::where('tgl_so', $tanggal)->get(); 

        $data = [
            'items' => $items
        ];

        return view('pages.penjualan.transaksiharian.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function show(Request $request) {
        $tglAwal = $request->tglAwal;
        $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
        $tglAkhir = $request->tglAkhir;
        $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');
        
        $items = SalesOrder::with('customer')
                ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'asc')->get();
        
        $data = [
            'items' => $items,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.transaksiharian.show', $data);
    }

    public function detail(Request $request, $id) {
        $items = SalesOrder::with('customer')->where('id', $id)
                ->orWhereBetween('tgl_so', [$request->tglAwal, $request->tglAkhir])
                ->orderBy('id', 'asc')->get();
        $gudang = Gudang::where('tipe', 'BIASA')->get();
        
        $data = [
            'items' => $items,
            'gudang' => $gudang,
            'kode' => $id,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.penjualan.transaksiharian.detail', $data);
    }
}
