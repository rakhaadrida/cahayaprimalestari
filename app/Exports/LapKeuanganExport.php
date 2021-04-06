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

class LapKeuanganExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(String $tahun, String $month)
    {
        $this->tahun = $tahun;
        $this->month = $month;
    }

    public function sheets(): array
    {
        $sheets = [];

        // $sales = SalesOrder::whereYear('tgl_so', $this->tahun)->whereMonth('tgl_so', $this->month)
        //         ->whereNotIn('status', ['BATAL', 'LIMIT'])->groupBy('id_sales')->orderBy('id_sales')->get();

        $sls = Sales::All();
        $sales = DetilSO::join('so', 'so.id', 'detilso.id_so')->join('barang', 'barang.id', 'detilso.id_barang')
                ->whereYear('tgl_so', $this->tahun)->whereMonth('tgl_so', $this->month)
                ->where('id_customer', '!=', 'CUS1071')->whereNotIn('status', ['BATAL', 'LIMIT'])
                ->groupBy('id_kategori')->orderBy('id_kategori')->get();

        // $urut = 6;
        $urut = 4 + ($sls->count() * 4) + 2;
        for($i = 0; $i < ($sales->count() + 1); $i++) {
            if($i == 0) {
                $id = '0';
                $nama = 'KOSONG';
                $strUrut = '0';
            } else {    
                // $id = $sales[$i-1]->id_sales;
                // $nama = $sales[$i-1]->sales->nama;
                $id = $sales[$i-1]->id_kategori;
                $nama = $sales[$i-1]->barang->jenis->nama;
                // $urut += 4;
                $strUrut = strval($urut);
                $urut++;
            }

            $sheets[] = new LapKeuPerSalesExport($id, $nama, $strUrut, $this->tahun, $this->month);
        }

        return $sheets;
    }
}
