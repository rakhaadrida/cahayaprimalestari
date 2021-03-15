<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisBarang;
use App\Models\Sales;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\Barang;
use App\Models\DetilBM;
use App\Models\AccReceivable;
use App\Models\DetilRAR;
use App\Models\Keuangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class LapKeuController extends Controller
{
    public function index() {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $month = $date->month;

        $arrBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'];
        $bulan = $arrBulan[$month-1];

        $jenis = JenisBarang::All();
        $sales = Sales::All();
        $salesOff = Sales::where('id', 'SLS03')->get();

        $items = $this->getItems($month, $tahun);
        $retur = $this->getRetur($month, $tahun);

        $jenis = $this->getJenis($jenis, $sales);
        $hppPerKat = $this->getHpp($jenis, $month);

        $keu = Keuangan::where('tahun', $tahun)->where('bulan', $month)->get();

        $data = [
            'tahun' => $tahun,
            'bulan' => $bulan,
            'month' => $month,
            'jenis' => $jenis,
            'sales' => $sales,
            'salesOff' => $salesOff,
            'items' => $items,
            'retur' => $retur,
            'hppPerKat' => $hppPerKat,
            'keu' => $keu
        ];

        return view('pages.keuangan.index', $data);
    }

    public function show(Request $request, $tah, $mo) {
        $tahun = ($tah == 'now' ? $request->tahun : $tah);
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                'September', 'Oktober', 'November', 'Desember'];
        if($mo == 'now') {
            for($i = 0; $i < sizeof($bulan); $i++) {
                if($request->bulan == $bulan[$i]) {
                    $month = $i+1;
                    break;
                }
                else
                    $month = '';
            }
        }
        else {
            $month = $mo;
        }

        $jenis = JenisBarang::All();
        $sales = Sales::All();
        $salesOff = Sales::where('id', 'SLS03')->get();

        $items = $this->getItems($month, $tahun);
        $retur = $this->getRetur($month, $tahun);

        $jenis = $this->getJenis($jenis, $sales);
        $hppPerKat = $this->getHpp($jenis, $month);

        $keu = Keuangan::where('tahun', $tahun)->where('bulan', $month)->get();

        $data = [
            'tahun' => $tahun,
            'bulan' => $request->bulan,
            'month' => $month,
            'jenis' => $jenis,
            'sales' => $sales,
            'salesOff' => $salesOff,
            'items' => $items,
            'retur' => $retur,
            'hppPerKat' => $hppPerKat,
            'keu' => $keu
        ];

        return view('pages.keuangan.show', $data);
    }

    public function getJenis($jenis, $sales) {
        foreach($jenis as $j) {
            foreach($sales as $s) {
                $j[$s->id] = 0;
            }
        }

        return $jenis;
    }

    public function getHpp($jenis, $month) {
        $qty = 0; $qtySO = 0; $k = 0; $sisaQty = 0; $sisa = 0; $h = 0; $hpp = []; $kode = '';
        $hppPerKat = collect();
        foreach($jenis as $j) {
            $barang = Barang::where('id_kategori', $j->id)->get();
            foreach($barang as $b) {
                $sisa = 0;
                $totBM = DetilBM::selectRaw('sum(qty) as totQty')
                        ->where('id_barang', $b->id)->get();
                $totSO = DetilSO::join('so', 'so.id', 'detilso.id_so')
                        ->selectRaw('sum(qty) as totQty')
                        ->where('id_barang', $b->id)->where('tgl_so', '<', $month)
                        // ->whereMonth('tgl_so', '<', $month)
                        ->whereNotIn('so.status', ['BATAL', 'LIMIT', 'RETUR'])->get();
                
                if($totSO[0]->totQty != null) {
                    $sisa = $totBM[0]->totQty - $totSO[0]->totQty;
                }

                if(($sisa == 0) && ($totSO[0]->totQty == null)) 
                    $bmPerBrg = DetilBM::where('id_barang', $b->id)->get();
                else {
                    $lastBM = DetilBM::where('id_barang', $b->id)
                                ->orderBy('id_bm', 'desc')->take(5)->get();
                    foreach($lastBM as $bm) {
                        if($sisa <= $bm->qty) {
                            $kode = $bm->id_bm;
                            break;
                        }
                        else
                            $sisa -= $bm->qty;
                    }

                    $bmPerBrg = DetilBM::where('id_barang', $b->id)
                                ->where('id_bm', '>=', $kode)->get();
                }

                $soPerBrg = DetilSO::join('so', 'so.id', 'detilso.id_so')
                            ->select('detilso.*')->where('id_barang', $b->id)
                            ->whereMonth('tgl_so', $month)
                            ->whereNotIn('so.status', ['BATAL', 'LIMIT', 'RETUR'])->get();
                           
                if(($bmPerBrg->count() != 0) && ($soPerBrg->count() != 0)) {
                    $k = 0;
                    for($i = 0; $i < $bmPerBrg->count(); $i++) {
                        if(($i == 0) && ($sisa != 0))
                            $bmPerBrg[$i]->qty = $sisa;
                            
                        $qty = $bmPerBrg[$i]->qty;
                        for($m = $k; $m < $soPerBrg->count(); $m++) {
                            $idSales = $soPerBrg[$k]->so->customer->id_sales;
                            if($soPerBrg[$k]->qty <= $qty) {
                                $hrg = $bmPerBrg[$i]->harga * $soPerBrg[$k]->qty;
                                $hrg = $hrg - ($hrg * number_format($bmPerBrg[$i]->disPersen, 2, ".", "") / 100);
                                $j->$idSales = $j->$idSales + $hrg;
                                $qtyHpp = $soPerBrg[$k]->qty;
                                $qty -= $soPerBrg[$k]->qty;
                                $k++;
                            }
                            else {
                                $hrg = $bmPerBrg[$i]->harga * $qty;
                                $hrg = $hrg - ($hrg * number_format($bmPerBrg[$i]->disPersen, 2, ".", "") / 100);
                                $j->$idSales = $j->$idSales + $hrg;
                                $qtyHpp = $qty;
                                $soPerBrg[$k]->qty = $soPerBrg[$k]->qty - $qty;
                                $qty = 0;
                            }

                            $hppPerKat[$h] = collect([
                                'id_kat' => $j->id,
                                'id_sales' => $soPerBrg[$m]->so->customer->id_sales,
                                'nama' => $soPerBrg[$m]->barang->nama,
                                'qty' => $qtyHpp,
                                'harga' => $bmPerBrg[$i]->harga,
                                'diskon' => $bmPerBrg[$i]->diskon,
                                'disPersen' => $bmPerBrg[$i]->disPersen,
                                'hpp' => $hrg
                            ]);
                            $h++;
                            
                            // var_dump($b->id." - ".$bmPerBrg[$i]->id_bm." - ".$bmPerBrg[$i]->qty." - ".$soPerBrg[$m]->id_so." - ".$soPerBrg[$m]->qty." - ".$qty." - HPP - ".number_format($hrg, 0, "", ".")." - BULAN - ".Carbon::parse($soPerBrg[$m]->so->tgl_so)->format('m')." - SALES - ".$soPerBrg[$m]->so->customer->id_sales." - ".$soPerBrg[$m]->so->customer->sales->nama);
                            // echo "<br>";

                            if($qty == 0)
                                break;
                        }
                    }
                    // echo "<br>";
                }
            }
            // echo "<br>";
        }

        return $hppPerKat;
    }

    public function getItems($bulan, $tahun) { 
        $items = DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                    ->join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id', 'customer.id_sales')
                    ->select('customer.id_sales', 'sales.nama', 'barang.id_kategori', DB::raw('sum(harga * qty - diskonRp) as total')) 
                    // ->select('so.id_sales', 'sales.nama', 'barang.id_kategori', DB::raw('sum(harga * qty - diskonRp) as total')) 
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                    ->whereYear('so.tgl_so', $tahun)
                    ->whereMonth('so.tgl_so', $bulan)
                    ->groupBy('customer.id_sales', 'barang.id_kategori')
                    // ->groupBy('so.id_sales', 'barang.id_kategori')
                    ->get();

        return $items;
    }

    public function getRetur($bulan, $tahun) {
        $retur = DetilRAR::join('barang', 'barang.id', 'detilrar.id_barang')
                    ->join('ar_retur', 'ar_retur.id', 'detilrar.id_retur')
                    ->join('ar', 'ar.id', 'ar_retur.id_ar')->join('so', 'so.id', 'ar.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('sales', 'sales.id' , 'customer.id_sales')
                    ->select('customer.id_sales', 'barang.id_kategori', DB::raw('sum((qty * harga) - diskonRp) as total'))
                    // ->select('so.id_sales', 'barang.id_kategori', DB::raw('sum((qty * harga) - diskonRp) as total'))
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT', 'RETUR']) 
                    ->whereYear('so.tgl_so', $tahun)
                    ->whereMonth('so.tgl_so', $bulan)
                    ->groupBy('customer.id_sales', 'barang.id_kategori')
                    // ->groupBy('so.id_sales', 'barang.id_kategori')
                    ->get();
        
        return $retur;
    } 

    public function storeIndex(Request $request) {
        $date = Carbon::now('+07:00');
        $tahun = $date->year;
        $bulan = $date->month;

        $item = Keuangan::where('tahun', $tahun)->where('bulan', $bulan)->first();

        if($item == NULL) {
            Keuangan::create([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'pendapatan' => ($request->pendapatan != '' ? str_replace(".", "", $request->pendapatan) : 0),
                'beban_gaji' => ($request->bebanGaji != '' ? str_replace(".", "", $request->bebanGaji) : 0),
                'beban_jual' => ($request->bebanJual != '' ? str_replace(".", "", $request->bebanJual) : 0),
                'beban_lain' => ($request->bebanLain != '' ? str_replace(".", "", $request->bebanLain) : 0),
                'petty_cash' => ($request->pettyCash != '' ? str_replace(".", "", $request->pettyCash) : 0),
            ]);
        } else {
            $item->{'pendapatan'} = ($request->pendapatan != '' ? str_replace(".", "", $request->pendapatan) : 0);
            $item->{'beban_gaji'} = ($request->bebanGaji != '' ? str_replace(".", "", $request->bebanGaji) : 0);
            $item->{'beban_jual'} = ($request->bebanJual != '' ? str_replace(".", "", $request->bebanJual) : 0);
            $item->{'beban_lain'} = ($request->bebanLain != '' ? str_replace(".", "", $request->bebanLain) : 0);
            $item->{'petty_cash'} = ($request->pettyCash != '' ? str_replace(".", "", $request->pettyCash) : 0);
            $item->save();
        }

        return redirect()->route('lap-keu');
    }

    public function storeShow(Request $request, $tahun, $bulan) {
        $item = Keuangan::where('tahun', $tahun)->where('bulan', $bulan)->first();

        if($item == NULL) {
            Keuangan::create([
                'tahun' => $tahun,
                'bulan' => $bulan,
                'pendapatan' => ($request->pendapatan != '' ? str_replace(".", "", $request->pendapatan) : 0),
                'beban_gaji' => ($request->bebanGaji != '' ? str_replace(".", "", $request->bebanGaji) : 0),
                'beban_jual' => ($request->bebanJual != '' ? str_replace(".", "", $request->bebanJual) : 0),
                'beban_lain' => ($request->bebanLain != '' ? str_replace(".", "", $request->bebanLain) : 0),
                'petty_cash' => ($request->pettyCash != '' ? str_replace(".", "", $request->pettyCash) : 0),
            ]);
        } else {
            $item->{'pendapatan'} = ($request->pendapatan != '' ? str_replace(".", "", $request->pendapatan) : 0);
            $item->{'beban_gaji'} = ($request->bebanGaji != '' ? str_replace(".", "", $request->bebanGaji) : 0);
            $item->{'beban_jual'} = ($request->bebanJual != '' ? str_replace(".", "", $request->bebanJual) : 0);
            $item->{'beban_lain'} = ($request->bebanLain != '' ? str_replace(".", "", $request->bebanLain) : 0);
            $item->{'petty_cash'} = ($request->pettyCash != '' ? str_replace(".", "", $request->pettyCash) : 0);
            $item->save();
        }

        // return redirect()->route('lap-keu'); POST
        return redirect()->route('lap-keu-show', ['tah' => $tahun, 'mo' => $bulan]);
    }

    /* public function getQty($bulan, $tahun) {
        $qtySalesPerItems = DetilSO::join('barang', 'barang.id', '=', 'detilso.id_barang')
                    ->join('so', 'so.id', '=', 'detilso.id_so')
                    ->join('customer', 'customer.id', '=', 'so.id_customer')
                    ->select('id_sales', 'id_barang', 'id_kategori', DB::raw('sum(qty) as qtyItems'), DB::raw('sum(harga * qty - diskonRp) as total'))
                    ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                    ->whereYear('so.tgl_so', $tahun)
                    ->whereMonth('so.tgl_so', $bulan)
                    ->groupBy('id_sales', 'id_barang')
                    ->get();

        $hppPerItems = DetilBM::join('barangmasuk', 'barangmasuk.id', '=', 'detilbm.id_bm')
                    ->select('id_barang', DB::raw('avg(harga) as avgHarga'),
                    DB::raw('avg(disPersen) as avgDisPersen')) 
                    // ->where('diskon', '!=', NULL)
                    ->where('barangmasuk.status', '!=', 'BATAL')
                    ->whereYear('barangmasuk.tanggal', $tahun)
                    ->whereMonth('barangmasuk.tanggal', $bulan)
                    ->groupBy('id_barang')
                    ->get();

        foreach($qtySalesPerItems as $q) {
            foreach($hppPerItems as $h) {
                if($q->id_barang == $h->id_barang) {
                    $q['avgDis'] = number_format($h->avgDisPersen, 2, ".", "");
                    $q['hrg'] = number_format($h->avgHarga, 0, "", "");
                    $q['disHpp'] = number_format(($h->avgHarga * $h->avgDisPersen) / 100, 0, "", "");
                    $q['hrgHpp'] = number_format($h->avgHarga - $q['disHpp'], 0, "", "");
                    $q['totHpp'] = $q['hrgHpp'] * $q->qtyItems;
                }
            }
        }

        // echo "<br>";
        // foreach($qtySalesPerItems as $qty) {
        // echo "<br>";
        // var_dump($qty->id_sales." = ".$qty->id_barang." = ".$qty->id_kategori." = ".$qty->qtyItems." = ".$qty->avgDis." = ".$qty->hrg." = ".$qty->disHpp." = ".$qty->hrgHpp." = ".$qty->totHpp);
        // } 

        return $qtySalesPerItems;
    } */
}
