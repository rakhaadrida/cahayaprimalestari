<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetilBM;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangMasukExport;

class RekapBMKController extends Controller
{
    public function index() {
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $tanggal = Carbon::now('+07:00')->toDateString();
        $items = DetilBM::select('id_bm', 'id_barang', 'id_gudang', 'supplier.nama AS namaSupplier', 'barang.nama AS namaBarang', 'gudang.nama AS namaGudang')
                    ->selectRaw('sum(qty) as qty')
                    ->join('barang', 'barang.id', 'detilbm.id_barang')
                    ->join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                    ->join('gudang', 'gudang.id', 'barangmasuk.id_gudang')
                    ->join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                    ->where('tanggal', $tanggal)->where('status', '!=', 'BATAL')
                    ->groupBy('id_supplier', 'id_barang', 'id_gudang')
                    ->orderBy('supplier.nama')->get();
        $tanggal = $this->formatTanggal($tanggal, 'd-M-y');
        // return response()->json($items);

        $data = [
            'items' => $items,
            'waktu' => $waktu,
            'tanggal' => $tanggal,
            'tglAwal' => NULL,
            'tglAkhir' => NULL
        ];

        return view('pages.laporan.barangmasuk.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function show(Request $request) {
        $tglAwal = $request->tglAwal;
        $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
        if($request->tglAkhir != '') {
            $tglAkhir = $request->tglAkhir;
            $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');
        } else {
            $tglAkhir = $tglAwal;
        }

        $items = DetilBM::select('id_bm', 'id_barang', 'id_gudang', 'supplier.nama AS namaSupplier', 'barang.nama AS namaBarang', 'gudang.nama AS namaGudang')
                    ->selectRaw('sum(qty) as qty')
                    ->join('barang', 'barang.id', 'detilbm.id_barang')
                    ->join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                    ->join('gudang', 'gudang.id', 'barangmasuk.id_gudang')
                    ->join('supplier', 'supplier.id', 'barangmasuk.id_supplier')
                    ->where('status', '!=', 'BATAL')->whereBetween('tanggal', [$tglAwal, $tglAkhir])
                    ->groupBy('id_supplier', 'id_barang', 'id_gudang')
                    ->orderBy('supplier.nama')->get();

        $awal = $this->formatTanggal($tglAwal, 'd');
        $akhir = $this->formatTanggal($tglAkhir, 'd M y');
        if($request->tglAkhir != '') {
            $tanggal = $awal.'-'.$akhir;
        } else {
            $tanggal = $akhir;
            $request->tglAkhir = $request->tglAwal;
        }

        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');

        $data = [
            'items' => $items,
            'waktu' => $waktu,
            'tanggal' => $tanggal,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.laporan.barangmasuk.index', $data);
    }

    public function excel(Request $request) {
        if($request->tglAwal != '') {
            $tglAwal = $request->tglAwal;
            $tglAwal = $this->formatTanggal($tglAwal, 'Y-m-d');
        } else {
            $tglAwal = Carbon::now()->toDateString();
        }

        if($request->tglAkhir != '') {
            $tglAkhir = $request->tglAkhir;
            $tglAkhir = $this->formatTanggal($tglAkhir, 'Y-m-d');
        } else {
            $tglAkhir = $tglAwal;
        }

        $awal = $this->formatTanggal($tglAwal, 'd');
        $akhir = $this->formatTanggal($tglAkhir, 'd M y');
        if($request->tglAkhir != $request->tglAwal) {
            $tanggal = $awal.'-'.$akhir;
        } else {
            $tanggal = $akhir;
        }

        return Excel::download(new BarangMasukExport($tglAwal, $tglAkhir), 'Barang-Masuk-'.$tanggal.'.xlsx');
    }
}
