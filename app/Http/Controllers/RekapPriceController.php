<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;
use App\Models\Subjenis;
use App\Models\Barang;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapPriceExport;

class RekapPriceController extends Controller
{
    public function index() {
        $jenis = JenisBarang::All();

        $data = [
            'jenis' => $jenis,
        ];
        
        return view('pages.laporan.rekapprice.index', $data);
    }

    public function cetak_pdf() {
        $jenis = JenisBarang::All();
        $waktu = Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss');

        foreach($jenis as $j) {
            $sub = Subjenis::where('id_kategori', $j->id)->count();
            $brg = Barang::where('id_kategori', $j->id)->count();
            $j->{'total'} = $brg + $sub;
        }

        $el = 0; $k = $jenis->count(); $gabung = 0;
        foreach($jenis as $j) {
            if($j->total <= 80) {
                for($i = $el+1; $i < $k; $i++) { 
                    if($jenis[$el]->total + $jenis[$i]->total <= 134) {
                        // if($jenis[$i]->nama != 'PRIME') {
                            $jenis[$el]->total += $jenis[$i]->total;
                            $jenis[$el]->id = $jenis[$el]->id.','.$jenis[$i]->id;
                            $jenis[$el]->nama = $jenis[$el]->nama.', '.$jenis[$i]->nama;
                            $kode = $jenis[$i]->id;

                            $jenis = $jenis->filter(function($item) use($kode) {
                                return $item->id != $kode;
                            });
                        // }
                        $gabung++;
                    }
                }
                $jenis = $jenis->values();
                $k -= $gabung;
            } 

            $el++;
        }

        $jenis = $jenis->values();

        $data = [
            'jenis' => $jenis,
            'waktu' => $waktu
        ];

        $pdf = PDF::loadview('pages.laporan.rekapprice.pdf', $data)->setPaper('A4', 'portrait');
        ob_end_clean();
        return $pdf->stream('Price-List.pdf');
    }

    public function cetak_excel() {
        return Excel::download(new RekapPriceExport, 'Price-List.xlsx');
    }
}
