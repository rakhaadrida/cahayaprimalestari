<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\Sales;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BarangKeluarExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(String $tglAwal, String $tglAkhir)
    {
        $this->tglAwal = $tglAwal;
        $this->tglAkhir = $tglAkhir;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sales = DetilSO::join('so', 'so.id', 'detilso.id_so')->join('barang', 'barang.id', 'detilso.id_barang')
                ->whereBetween('tgl_so', [$this->tglAwal, $this->tglAkhir])->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->groupBy('id_kategori')->orderBy('id_kategori')->get();

        for($i = 0; $i < ($sales->count() + 1); $i++) {
            if($i == 0) {
                $id = '0';
                $nama = 'KOSONG';
            } else {    
                $id = $sales[$i-1]->id_kategori;
                $nama = $sales[$i-1]->barang->jenis->nama;
            }

            $sheets[] = new BKPerKategoriExport($id, $nama, $this->tglAwal, $this->tglAkhir);
        }

        return $sheets;
    }
}
