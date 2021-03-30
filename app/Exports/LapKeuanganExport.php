<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\SalesOrder;
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

        $sales = SalesOrder::whereYear('tgl_so', $this->tahun)->whereMonth('tgl_so', $this->month)
                ->whereNotIn('status', ['BATAL', 'LIMIT'])->groupBy('id_sales')->orderBy('id_sales')->get();

        $urut = 6;
        for($i = 0; $i < ($sales->count() + 1); $i++) {
            if($i == 0) {
                $id = '0';
                $nama = 'KOSONG';
                $strUrut = '0';
            } else {    
                $id = $sales[$i-1]->id_sales;
                $nama = $sales[$i-1]->sales->nama;
                $urut += 4;
                $strUrut = strval($urut);
            }

            $sheets[] = new LapKeuPerSalesExport($id, $nama, $strUrut, $this->tahun, $this->month);
        }

        return $sheets;
    }
}
