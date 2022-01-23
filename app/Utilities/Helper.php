<?php


namespace App\Utilities;

use App\Models\AR_Retur;
use App\Models\DetilAR;
use App\Models\DetilSO;
use App\Models\StokBarang;
use Carbon\Carbon;

class Helper
{
    public static function getStokGudangRetur($idBarang, $idGudang)
    {
        $stok = StokBarang::selectRaw('sum(stok) as stok')
            ->where('id_barang', $idBarang)
            ->where('id_gudang', $idGudang)
            ->get();

        return $stok;
    }

    public static function getStokGudangBiasa($idBarang, $idGudang)
    {
        $stok = StokBarang::where('id_barang', $idBarang)
            ->where('id_gudang', $idGudang)
            ->get();

        return $stok;
    }

    public static function getStokBarangOffice($idBarang)
    {
        $stok = StokBarang::query()
            ->selectRaw('sum(stok) as stok')
            ->join('gudang', 'gudang.id', 'stok.id_gudang')
            ->where('id_barang', $idBarang)
            ->where('tipe', '!=', 'RETUR')
            ->get();

        return $stok;
    }

    public static function getBarangKeluarPerKategori($id, $tglAwal, $tglAkhir) {
        $barang = DetilSO::select('id_so as id', 'detilso.*', 'barang.nama AS namaBarang')
            ->selectRaw('sum(qty) as qty, sum(diskonRp) as diskonRp')
            ->join('barang', 'barang.id', 'detilso.id_barang')
            ->join('so', 'so.id', 'detilso.id_so')
            ->where('id_kategori', $id)
            ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
            ->where('id_customer', '!=', 'CUS1071')
            ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
            ->groupBy('id_so', 'id_barang')
            ->orderBy('id_barang')
            ->orderBy('id_so')
            ->get();

        return $barang;
    }

    public static function getReceivableTotal($id) {
        $total = DetilAR::selectRaw('sum(cicil) as totCicil')
            ->where('id_ar', $id)->get();

        return $total;
    }

    public static function getReceivableRetur($id) {
        $retur = AR_Retur::selectRaw('sum(total) as total')
            ->where('id_ar', $id)->get();

        return $retur;
    }

    public static function getReceivableDate($date) {
        $arDate = Carbon::createFromFormat('Y-m-d', $date);

        return $arDate;
    }

    public static function getReceivableTempo($date, $tempo) {
        $arTempo = \Carbon\Carbon::parse($date)->add($tempo, 'days')->format('Y-m-d');

        return $arTempo;
    }
}
