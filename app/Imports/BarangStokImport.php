<?php

namespace App\Imports;

use App\Models\StokBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangStokImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        if (empty(trim($row['stok_baru'] ?? ''))) {
            return null;
        } 
        
        $stokBaru = (int) preg_replace('/[^0-9]/','',$row['stok_baru']);
        
        return StokBarang::updateOrCreate(
            [
                'id_barang' => $row['kode_barang'],
                'id_gudang' => 'GDG10'
            ],
            [
                'status' => 'T',
                'stok' => $stokBaru
            ]
        );
    }
}
