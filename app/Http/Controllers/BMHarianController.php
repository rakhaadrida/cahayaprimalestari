<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BMHarianExport;
use App\Exports\BMAllExport;

class BMHarianController extends Controller
{
    public function index() {
        $tanggal = Carbon::now()->toDateString();

        $items = BarangMasuk::where('tanggal', $tanggal)->get(); 
        $data = [
            'items' => $items
        ];

        return view('pages.pembelian.bmharian.index', $data);
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
        
        $items = BarangMasuk::whereBetween('tanggal', [$tglAwal, $tglAkhir])
                ->orderBy('id', 'asc')->get();
        
        $data = [
            'items' => $items,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.pembelian.bmharian.show', $data);
    }

    public function detail(Request $request, $id) {
        $items = BarangMasuk::where('id', $id)
                ->orWhereBetween('tanggal', [$request->tglAwal, $request->tglAkhir])
                ->orderBy('id', 'asc')->get();
        
        $data = [
            'items' => $items,
            'kode' => $id,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.pembelian.bmharian.detail', $data);
    }

    public function excelNow() {
        $tanggal = Carbon::now()->toDateString();
        $tanggalStr = $this->formatTanggal($tanggal, 'd-M-y');

        return Excel::download(new BMHarianExport($tanggal, $tanggalStr), 'BM-Harian.xlsx');
    }

    public function excel(Request $request) {
        $tglAwal = $request->tglAwal;
        $awal = $this->formatTanggal($request->tglAwal, 'Y-m-d');
        $tglAkhir= $request->tglAkhir;
        $akhir = $this->formatTanggal($request->tglAkhir, 'Y-m-d');

        $namaAwal = $this->formatTanggal($tglAwal, 'd');
        $namaAkhir = $this->formatTanggal($tglAkhir, 'd M y');
        if($request->tglAkhir != $request->tglAwal) {
            $tanggal = $namaAwal.'-'.$namaAkhir;
        } else {
            $tanggal = $namaAkhir;
        }

        return Excel::download(new BMAllExport($tglAwal, $tglAkhir, $awal, $akhir), 'BM-Harian'.$tanggal.'.xlsx');
    }
}
