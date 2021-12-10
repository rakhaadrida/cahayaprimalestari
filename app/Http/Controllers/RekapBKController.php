<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetilSO;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarangKeluarExport;

class RekapBKController extends Controller
{
    public function index() {
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');
        $tanggal = Carbon::now('+07:00')->toDateString();
        $items = DetilSO::query()
                ->select('id_so', 'id_barang', 'id_gudang', 'customer.nama AS namaCustomer', 'barang.nama AS namaBarang', 'gudang.nama AS namaGudang')
                ->selectRaw('sum(qty) as qty')
                ->join('barang', 'barang.id', 'detilso.id_barang')
                ->join('gudang', 'gudang.id', 'detilso.id_gudang')
                ->join('so', 'so.id', 'detilso.id_so')
                ->join('customer', 'customer.id', 'so.id_customer')
                ->where('tgl_so', $tanggal)->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->where('qty', '!=', 0)->groupBy('id_customer', 'id_barang', 'id_gudang')
                ->orderBy('customer.nama')->get();
        $tanggal = $this->formatTanggal($tanggal, 'd-M-y');

        $data = [
            'items' => $items,
            'waktu' => $waktu,
            'tanggal' => $tanggal,
            'tglAwal' => NULL,
            'tglAkhir' => NULL
        ];

        return view('pages.laporan.barangkeluar.index', $data);
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

        $items = DetilSO::query()
                    ->select('id_so', 'id_barang', 'id_gudang', 'customer.nama AS namaCustomer', 'barang.nama AS namaBarang', 'gudang.nama AS namaGudang')
                    ->selectRaw('sum(qty) as qty')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    ->join('gudang', 'gudang.id', 'detilso.id_gudang')
                    ->join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('qty', '!=', 0)->groupBy('id_customer', 'id_barang', 'id_gudang')
                    ->orderBy('customer.nama')->get();

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

        return view('pages.laporan.barangkeluar.index', $data);
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

        return Excel::download(new BarangKeluarExport($tglAwal, $tglAkhir), 'Barang-Keluar-'.$tanggal.'.xlsx');
    }
}
