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
                ->select('id', 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe')
                ->orderBy('created_at', 'asc')->groupBy('id_dokumen')->get();

        // return response()->json($approval);
        $gudang = Gudang::All();
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
        else
            $item = BarangMasuk::where('id', $id)->first();

        if($statusApp == 'PENDING_UPDATE') {
            $item->{'status'} = "UPDATE";
            $item->{'total'} = str_replace(".", "", $request->input("grandtotal".$id));
        }
        elseif($status == 'PENDING_BATAL') {
            $item->{'status'} = "BATAL";
            if($tipe == 'Faktur') {
                $ar = AccReceivable::where('id_so', $id)->get();
                $detilar = DetilAR::where('id_ar', $ar[0]->id)->get();
                if($detilar->count() != 0) {
                    $arRetur = AR_Retur::where('id_ap', $ar[0]->id)->get();
                    if($arRetur->count() != 0) {
                        $detilARR = DetilRAR::where('id_retur', $arRetur[0]->id)->delete();
                        AR_Retur::where('id_ar', $ar[0]->id)->delete();
                    }
                    DetilAR::where('id_ar', $ar[0]->id)->delete();
                }
                AccReceivable::where('id_so', $id)->delete(); 
            } else {
                $ap = AccPayable::where('id_bm', $item->{'id_faktur'})->get();
                $detilap = DetilAP::where('id_ap', $ap[0]->id)->get();
                if($detilap->count() != 0) {
                    $apRetur = AP_Retur::where('id_ap', $ap[0]->id)->get();
                    if($apRetur->count() != 0) {
                        $detilAPR = DetilRAP::where('id_retur', $apRetur[0]->id)->delete();
                        AP_Retur::where('id_ap', $ap[0]->id)->delete();
                    }
                    DetilAP::where('id_ap', $ap[0]->id)->delete();
                }
                AccPayable::where('id_bm', $item->{'id_faktur'})->delete(); 
            }
        }
        elseif($status == 'LIMIT') {
            $item->{'status'} = "APPROVE_LIMIT";
        }

        $item->save(); 

        $lastcode = Approval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APR'.sprintf('%04s', $lastnumber);

        $needApp = NeedApproval::where('id_dokumen', $id)->orderBy('id', 'desc')->get();
        Approval::create([
            'id' => $newcode,
            'id_dokumen' => $id,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => $item->{'status'},
            'keterangan' => $needApp[0]->keterangan,
            'tipe' => $tipe,
            'baca' => 'F'
        ]);

        if($tipe == 'Faktur')
            $detil = DetilSO::where('id_so', $id)->get();
        else
            $detil = DetilBM::with(['bm'])->where('id_bm', $id)->get();

        $disPersen = []; $hpp = [];
        foreach($detil as $d) {
            if($tipe == 'Faktur')
                $gudang = $d->id_gudang;
            else
                $gudang = $d->bm->id_gudang;

            DetilApproval::create([
                'id_app' => $newcode,
                'id_barang' => $d->id_barang,
                'id_gudang' => $gudang,
                'harga' => $d->harga,
                'qty' => $d->qty,
                'diskon' => $d->diskon
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
                    $updateStok->{'stok'} += $item->qty;
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
            else
                $items = DetilBM::with(['bm'])->where('id_bm', $kode)->get();

            foreach($items as $item) {
                if($tipe == 'Faktur') 
                    $gudang = $item->id_gudang;
                else
                    $gudang = $item->bm->id_gudang;

                $updateStok = StokBarang::where('id_barang', $item->id_barang)
                        ->where('id_gudang', $gudang)->first();

                if($tipe == 'Faktur') 
                    $updateStok->{'stok'} -= $item->qty;
                else
                    $updateStok->{'stok'} += $item->qty;
                    
                $updateStok->save();
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
        $gudang = Gudang::All();
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
