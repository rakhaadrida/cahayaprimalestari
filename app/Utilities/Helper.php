<?php


namespace App\Utilities;

use App\Models\DetilSO;
use App\Models\StokBarang;
use Carbon\Carbon;

class Helper
{
    public static function getStokGudangRetur($idBarang, $idGudang)
    {
        $stok = StokBarang::selectRaw('sum(stok) as stok')
            ->where('id_barang', $idBarang)
            ->where('id_gudang', $idGudang)->get();

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
}
