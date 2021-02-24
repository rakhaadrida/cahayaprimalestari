<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\View\View;
use App\Models\JenisBarang;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RekapPriceExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];

        $jenis = JenisBarang::All();

        foreach($jenis as $item) {
            $sheets[] = new RekapPricePerBarangExport($item->id, $item->nama);
        }

        return $sheets;
    }
}
