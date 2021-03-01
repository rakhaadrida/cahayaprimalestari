<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\BarangMasuk;
use App\Models\DetilSO;
use App\Models\DetilBM;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use App\Models\Approval;
use App\Models\DetilApproval;
use App\Models\AccReceivable;
use App\Models\AccPayable;
use App\Models\DetilAR;
use App\Models\DetilAP;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\AP_Retur;
use App\Models\AR_Retur;
use App\Models\DetilRAP;
use App\Models\DetilRAR;
use App\Models\DetilRJ;
use App\Models\DetilRB;
use App\Models\DetilRT;
use App\Models\ReturJual;
use App\Models\ReturBeli;
use App\Models\ReturTerima;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index() {
        $items = NeedApproval::with(['so', 'bm'])
                ->select('id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe')
                ->orderBy('created_at', 'asc')->groupBy('id_dokumen')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.approval.index', $data);
    }

    public function show($id) {
        $approval = NeedApproval::with(['so', 'bm'])
                ->select('id', 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe', 'id_user')
                ->orderBy('created_at', 'asc')->groupBy('id_dokumen')->get();

        // return response()->json($approval);
        $gudang = Gudang::where('tipe', 'BIASA')->get();
        $kenari = Gudang::where('tipe', 'KENARI')->get();
        $cicilPerCust = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(cicil) as totCicil'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $totalPerCust = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(total) as totKredit'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $returPerCust = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(ar_retur.total) as totRetur'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        foreach($totalPerCust as $q) {
            $q['total'] = $q->totKredit;
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] -= $h->totCicil;
                }
            }
            foreach($returPerCust as $r) {
                if($q->id_customer == $r->id_customer) {
                    $q['total'] -= $h->totCicil;
                }
            }
        }

        $data = [
            'approval' => $approval,
            'gudang' => $gudang,
            'kenari' => $kenari,
            'kode' => $id,
            'total' => $totalPerCust
        ];

        return view('pages.approval.show', $data);
    }

    public function process(Request $request, $id) {
        $statusApp = $request->input("statusApp".$id);
        $status = $request->input("status".$id);
        $tipe = $request->input("tipe".$id);

        if($tipe == 'Faktur')
            $item = SalesOrder::where('id', $id)->first();
        elseif($tipe == 'Dokumen')
            $item = BarangMasuk::where('id', $id)->first();
        elseif($tipe == 'RJ')
            $item = ReturJual::where('id', $id)->first();
        elseif($tipe == 'RB')
            $item = ReturBeli::where('id', $id)->first();

        if($statusApp == 'PENDING_UPDATE') {
            $item->{'status'} = "UPDATE";
            // $item->{'total'} = str_replace(".", "", $request->input("grandtotal".$id));
            $item->{'total'} = str_replace(".", "", $request->grandtotalAkhir);
            $item->save(); 
        }
        elseif($status == 'PENDING_BATAL') {
            $item->{'status'} = "BATAL";
            $item->save(); 
            if($tipe == 'Faktur') {
                $ar = AccReceivable::where('id_so', $id)->get();
                $detilar = DetilAR::where('id_ar', $ar->first()->id)->get();
                if($detilar->count() != 0) {
                    DetilAR::where('id_ar', $ar->first()->id)->delete();
                }

                $arRetur = AR_Retur::where('id_ar', $ar->first()->id)->get();
                if($arRetur->count() != 0) {
                    $detilARR = DetilRAR::where('id_retur', $arRetur->first()->id)->delete();
                    AR_Retur::where('id_ar', $ar->first()->id)->delete();
                }
                AccReceivable::where('id_so', $id)->delete(); 
            } elseif($tipe == 'Dokumen') {
                $bm = BarangMasuk::where('id_faktur', $item->{'id_faktur'})
                    ->where('status', '!=', 'BATAL')->count();

                if($bm == 0) { 
                    $ap = AccPayable::where('id_bm', $item->{'id_faktur'})->get();
                    $detilap = DetilAP::where('id_ap', $ap->first()->id)->get();
                    if($detilap->count() != 0) {
                        DetilAP::where('id_ap', $ap->first()->id)->delete();
                    }

                    $apRetur = AP_Retur::where('id_ap', $ap->first()->id)->get();
                    if($apRetur->count() != 0) {
                        $detilAPR = DetilRAP::where('id_retur', $apRetur->first()->id)->delete();
                        AP_Retur::where('id_ap', $ap->first()->id)->delete();
                    }
                    AccPayable::where('id_bm', $item->{'id_faktur'})->delete(); 
                }
            }
        }
        elseif($status == 'LIMIT') {
            $item->{'status'} = "APPROVE_LIMIT";
            $item->save(); 

            $waktu = Carbon::now('+07:00');
            $bulan = $waktu->format('m');
            $month = $waktu->month;
            $tahun = substr($waktu->year, -2);

            $lastcode = AccReceivable::join('so', 'so.id', 'ar.id_so')
                        ->selectRaw('max(ar.id) as id')->whereMonth('ar.created_at', $month)->get();
            $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
            $lastnumber++;
            $newcode = 'AR'.$tahun.$bulan.sprintf('%04s', $lastnumber);

            AccReceivable::create([
                'id' => $newcode,
                'id_so' => $id,
                'keterangan' => 'BELUM LUNAS'
            ]);
        }

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = Approval::selectRaw('max(id) as id')->whereYear('tanggal', $waktu->year)
                    ->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode->first()->id, 7, 4);
        $lastnumber++;
        $newcode = 'APR'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        if(($tipe == 'Faktur') || ($tipe == 'Dokumen'))
            $baca = 'F';
        else
            $baca = 'T';

        $needApp = NeedApproval::where('id_dokumen', $id)->orderBy('id', 'desc')->get();
        Approval::create([
            'id' => $newcode,
            'id_dokumen' => $id,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => $item->{'status'},
            'keterangan' => $needApp->first()->keterangan,
            'tipe' => $tipe,
            'baca' => $baca
        ]);

        if($tipe == 'Faktur')
            $detil = DetilSO::where('id_so', $id)->get();
        elseif($tipe == 'Dokumen')
            $detil = DetilBM::with(['bm'])->where('id_bm', $id)->get();
        elseif($tipe == 'RJ')
            $detil = DetilRJ::where('id_retur', $id)->get();
        elseif($tipe == 'RB')
            $detil = DetilRB::where('id_retur', $id)->get();

        $disPersen = []; $hpp = [];
        foreach($detil as $d) {
            if($tipe == 'Faktur')
                $gudang = $d->id_gudang;
            elseif($tipe == 'Dokumen')
                $gudang = $d->bm->id_gudang;
            else {
                $retur = Gudang::where('tipe', 'RETUR')->get();
                $gudang = $retur->first()->id;
            }

            if(($tipe == 'Faktur') || ($tipe == 'Dokumen')) {
                $harga = $d->harga;
                $qty = $d->qty;
                $disk = $d->diskon;
            } else {
                $harga = NULL;
                $qty = $d->qty_retur;
                $disk = NULL;
            }

            DetilApproval::create([
                'id_app' => $newcode,
                'id_barang' => $d->id_barang,
                'id_gudang' => $gudang,
                'harga' => $harga,
                'qty' => $qty,
                'diskon' => $disk
            ]);

            if($tipe == 'Dokumen') {
                array_push($disPersen, $d->disPersen);
                array_push($hpp, $d->hpp);
            }
        }

        if(($tipe == 'Faktur') && ($statusApp == 'PENDING_UPDATE'))
            DetilSO::where('id_so', $id)->delete();
        elseif(($tipe == 'Dokumen') && ($statusApp == 'PENDING_UPDATE'))
            DetilBM::where('id_bm', $id)->delete();

        if($statusApp == 'PENDING_UPDATE')
            $items = NeedAppDetil::where('id_app', $needApp[0]->id)->get();
        else
            $items = DetilApproval::where('id_app', $newcode)->get();

        foreach($items as $item) {
            if($item->diskon != '') {
                $diskon = 100;
                $item->diskon = str_replace(",", ".", $item->diskon);
                $arrDiskon = explode("+", $item->diskon);
                for($j = 0; $j < sizeof($arrDiskon); $j++) {
                    $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                } 
                $diskon = number_format((($diskon - 100) * -1), 2, ".", "");
                $diskonRp = (($item->qty * $item->harga) * $diskon) / 100;
            } else {
                $diskon = NULL;
            }

            if(($tipe == 'Faktur') && ($statusApp == 'PENDING_UPDATE')) {
                DetilSO::create([
                    'id_so' => $id,
                    'id_barang' => $item->id_barang,
                    'id_gudang' => $item->id_gudang,
                    'harga' => $item->harga,
                    'qty' => $item->qty,
                    'diskon' => $item->diskon,
                    'diskonRp' => $diskonRp
                ]);
            }
            elseif(($tipe == 'Dokumen') && ($statusApp == 'PENDING_UPDATE')) {
                DetilBM::create([
                    'id_bm' => $id,
                    'id_barang' => $item->id_barang,
                    'harga' => $item->harga,
                    'qty' => $item->qty,
                    'diskon' => $item->diskon,
                    'disPersen' => $diskon
                ]);
            }
        } 

        foreach($needApp as $need) {
            NeedAppDetil::where('id_app', $need->id)->delete();
        }
        NeedApproval::where('id_dokumen', $id)->delete(); 

        return redirect()->route('approval');
    } 

    public function batal(Request $request, $id, $kode) {
        $status = $request->input("status".$kode);
        $statusApp = $request->input("statusApp".$kode);
        $tipe = $request->input("tipe".$kode);

        if($statusApp == 'PENDING_UPDATE') {
            // $items = NeedAppDetil::with(['need_app'])
            //         ->whereHas('need_app', function($q) use($kode) {
            //             $q->where('id_dokumen', $kode);
            //         })->latest()->get();
            $items = NeedApproval::with(['need_appdetil'])
                    ->where('id_dokumen', $kode)->latest()->get();
            // return response()->json($items[0]->need_appdetil);
            
            if($tipe == 'Faktur') {
                $detil = DetilSO::where('id_so', $kode)->get();
            } else {
                $detil = DetilBM::where('id_bm', $kode)->get();
            }

            if($items[0]->need_appdetil->count() != $detil->count()) {
                foreach($detil as $d) {
                    $cek = 0;
                    foreach($items[0]->need_appdetil as $item) {
                        if($d->id_barang == $item->id_barang) {
                            $cek = 1; 
                            break;
                        }
                    }

                    if($tipe == 'Faktur')
                        $gudang = $d->id_gudang;
                    else
                        $gudang = $request->$id;

                    if($cek == 0) {
                        $updateStok = StokBarang::where('id_barang', $d->id_barang)
                            ->where('id_gudang', $gudang)->first();
                        if($tipe == 'Faktur')
                            $updateStok->{'stok'} -= $d->qty;
                        else
                            $updateStok->{'stok'} += $d->qty;
                            
                        $updateStok->save();
                    }
                }
            } else {
                foreach($detil as $d) {
                    $cek = 0;
                    foreach($items[0]->need_appdetil as $item) {
                        if($item->id_barang == $d->id_barang) {
                            $cek = 1; 
                            break;
                        }
                    }

                    if($tipe == 'Faktur')
                        $gudang = $d->id_gudang;
                    else
                        $gudang = $request->$id;

                    if($cek == 0) {
                        $updateStok = StokBarang::where('id_barang', $d->id_barang)
                                    ->where('id_gudang', $gudang)->first();
                        if($tipe == 'Faktur')
                            $updateStok->{'stok'} -= $d->qty;
                        else
                            $updateStok->{'stok'} += $d->qty;

                        $updateStok->save();
                    }
                }

                /* for($i = 0; $i < $detil->count(); $i++) {
                    if($detil[$i]->id_barang != $items[0]->need_appdetil[$i]->id_barang) {
                        $updateStok = StokBarang::where('id_barang', $detil[$i]->id_barang)
                                    ->where('id_gudang', $detil[$i]->id_gudang)->first();
                        $updateStok->{'stok'} -= $detil[$i]->qty;
                        $updateStok->save();
                    }
                } */
            }

            foreach($items[0]->need_appdetil as $item) {
                if($tipe == 'Faktur') {
                    $stokAwal = DetilSO::where('id_so', $kode)
                                ->where('id_barang', $item->id_barang)
                                ->where('id_gudang', $item->id_gudang)->first();
                } else {
                    $stokAwal = DetilBM::where('id_bm', $kode)
                                ->where('id_barang', $item->id_barang)->first();
                }
            
                $updateStok = StokBarang::where('id_barang', $item->id_barang)
                            ->where('id_gudang', $item->id_gudang)->first();
                
                if($stokAwal != NULL) {
                    if($stokAwal->{'qty'} > $item->qty) {
                        if($tipe == 'Faktur') 
                            $updateStok->{'stok'} -= ($stokAwal->{'qty'} - $item->qty);
                        else 
                            $updateStok->{'stok'} += ($stokAwal->{'qty'} - $item->qty);
                    }
                    elseif($stokAwal->{'qty'} < $item->qty) {
                        if($tipe == 'Faktur')
                            $updateStok->{'stok'} += ($item->qty - $stokAwal->{'qty'});
                        else
                            $updateStok->{'stok'} -= ($item->qty - $stokAwal->{'qty'});
                    }
                } else {
                    if($tipe == 'Faktur') 
                        $updateStok->{'stok'} += $item->qty;
                    else
                        $updateStok->{'stok'} -= $item->qty;
                } 

                $updateStok->save(); 
                $item = NeedAppDetil::where('id_app', $item->id_app)
                        ->where('id_barang', $item->id_barang)
                        ->where('id_gudang', $item->id_gudang)->delete(); 
            } 

            foreach($items as $item) {
                foreach($item->need_appdetil as $n) {
                    $item = NeedAppDetil::where('id_app', $n->id_app)
                        ->where('id_barang', $n->id_barang)
                        ->where('id_gudang', $n->id_gudang)->delete(); 
                }
            }
        } 
        elseif($status == 'PENDING_BATAL') {
            if($tipe == 'Faktur') 
                $items = DetilSO::where('id_so', $kode)->get();
            elseif($tipe == 'Dokumen')
                $items = DetilBM::with(['bm'])->where('id_bm', $kode)->get();
            elseif($tipe == 'RJ')
                $items = DetilRJ::where('id_retur', $kode)->get();
            elseif($tipe == 'RB') 
                $items = DetilRB::where('id_retur', $kode)->get();

            foreach($items as $item) {
                if($tipe == 'Faktur') 
                    $gudang = $item->id_gudang;
                elseif($tipe == 'Dokumen')
                    $gudang = $request->$id;
                else {
                    $retur = Gudang::where('tipe', 'RETUR')->get();
                    $gudang = $retur->first()->id;
                }

                if(($tipe == 'Faktur') || ($tipe == 'Dokumen')) {
                    $updateStok = StokBarang::where('id_barang', $item->id_barang)
                                ->where('id_gudang', $gudang)->first();
                } else {
                    $updateStok = StokBarang::where('id_barang', $item->id_barang)
                                ->where('id_gudang', $gudang)->where('status', 'F')->first();
                    $updateStokBagus = StokBarang::where('id_barang', $item->id_barang)
                                ->where('id_gudang', $gudang)->where('status', 'T')->first();
                }

                if($tipe == 'Faktur') 
                    $updateStok->{'stok'} -= $item->qty;
                elseif($tipe == 'Dokumen')
                    $updateStok->{'stok'} += $item->qty;
                elseif($tipe == 'RJ') {
                    $updateStok->{'stok'} += $item->qty_retur;
                    $updateStokBagus->{'stok'} -= $item->qty_kirim;
                    $updateStokBagus->save();
                }
                elseif($tipe == 'RB') {
                    $updateStok->{'stok'} -= $item->qty_retur;
                    $rt = DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                            ->selectRaw('sum(qty_terima) as qt, sum(qty_batal) as qb, sum(potong) as qp')
                            ->where('id_retur', $kode)->where('id_barang', $item->id_barang)
                            ->groupBy('id_barang')->get();

                    if($rt->count() != 0) {
                        $updateStokBagus->{'stok'} += $rt->first()->qt;
                        $updateStokBagus->save();

                        $updateStok->{'stok'} += ($rt->first()->qb + $rt->first()->qp);
                    }
                }
                    
                $updateStok->save();
            }

            if($tipe == 'RJ') {
                $item = ReturJual::where('id', $kode)->first();
                $item->{'status'} = 'INPUT';
                $item->save();
            }
            elseif($tipe == 'RB') {
                $item = ReturBeli::where('id', $kode)->first();
                $item->{'status'} = 'INPUT';
                $item->save();
            }

            $items = NeedApproval::with(['need_appdetil'])
                    ->where('id_dokumen', $kode)->latest()->get();
            foreach($items as $item) {
                foreach($item->need_appdetil as $n) {
                    $item = NeedAppDetil::where('id_app', $n->id_app)
                        ->where('id_barang', $n->id_barang)
                        ->where('id_gudang', $n->id_gudang)->delete(); 
                }
            }
        } 
        elseif($status == 'LIMIT') {
            $item = SalesOrder::where('id', $kode)->first();
            $item->{'status'} = "BATAL";
            $item->save();

            $items = DetilSO::where('id_so', $kode)->get();

            foreach($items as $item) {
                $updateStok = StokBarang::where('id_barang', $item->id_barang)
                        ->where('id_gudang', $item->id_gudang)->first();

                $updateStok->{'stok'} += $item->qty;
                $updateStok->save();
            }
        }

        $item = NeedApproval::where('id_dokumen', $kode)->delete();

        return redirect()->route('approval'); 
    }

    public function histori() {
        $items = Approval::with(['so', 'bm'])
                ->select('id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe')
                ->orderBy('created_at', 'desc')->groupBy('id_dokumen')->get();
        
        $data = [
            'items' => $items
        ];
        return view('pages.approval.histori', $data);
    }

    public function detail($id) {
        $approval = Approval::with(['so', 'bm'])
                    ->select(DB::raw('max(id) as id'), 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe')
                    ->orderBy('created_at', 'asc')->groupBy('id_dokumen')->get();

        // return response()->json($items);
        $gudang = Gudang::where('tipe', 'BIASA')->get();
        $cicilPerCust = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(cicil) as totCicil'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $totalPerCust = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(total) as totKredit'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $returPerCust = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(ar_retur.total) as totRetur'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        foreach($totalPerCust as $q) {
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] = $q->totKredit - $h->totCicil;
                }
            }
            foreach($returPerCust as $r) {
                if($q->id_customer == $r->id_customer) {
                    $q['total'] -= $h->totCicil;
                }
            }
        }

        $data = [
            'approval' => $approval,
            'gudang' => $gudang,
            'kode' => $id,
            'total' => $totalPerCust
        ];
        
        return view('pages.approval.detail', $data);
    }
}
