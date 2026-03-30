<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class KartuStokExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct(String $awal, String $akhir, String $kodeAwal, String $kodeAkhir)
    {
        $this->awal = $awal;
        $this->akhir = $akhir;
        $this->kodeAwal = $kodeAwal;
        $this->kodeAkhir = $kodeAkhir;
    }

    public function sheets(): array
    {
        $sheets = [];

        $itemsBRG = Barang::whereBetween('id', [$this->kodeAwal, $this->kodeAkhir])
                    ->get();

        foreach($itemsBRG as $item) {
            $sheets[] = new KartuPerBarangExport($item->id, $this->awal, $this->akhir);
        }

        return $sheets;
    }
}
