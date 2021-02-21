<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\StokBarang;
use App\Models\JenisBarang;
use App\Models\Barang;
use App\Models\DetilBM;
use App\Models\DetilSO;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RekapStokFilterExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(String $tglAwal)
    {
        $this->awal = $tglAwal;
    }

    public function sheets(): array
    {
        $sheets = [];

        $jenis = JenisBarang::All();
        $awal = $this->awal;

        foreach($jenis as $item) {
            $sheets[] = new RekapFilterPerBarangExport($item->id, $item->nama, $awal);
        }

        return $sheets;
    }
}
