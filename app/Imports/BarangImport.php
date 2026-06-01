<?php

namespace App\Imports;

use App\Models\HargaBarang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangImport implements ToModel, WithHeadingRow
{
    public function headingRow(): int
    {
        return 4;
    }

    public function model(array $row)
    {
        if (empty(trim($row['harga_baru'] ?? ''))) {
            return null;
        } 
        
        $hargaBaru = (int) preg_replace('/[^0-9]/','',$row['harga_baru']);
        $harga = $hargaBaru / 1.1;
        $ppn = $hargaBaru - $harga;
        
        return HargaBarang::updateOrCreate(
            [
                'id_barang' => $row['kode_barang'],
                'id_harga' => 'HRG01'
            ],
            [
                'harga' => $harga,
                'ppn' => $ppn,
                'harga_ppn' => $hargaBaru
            ]
        );
    }
}
