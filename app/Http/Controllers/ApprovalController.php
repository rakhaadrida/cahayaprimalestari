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
use App\Models\DetilAR;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index() {
        $items = NeedApproval::with(['so', 'bm'])->get();
        $data = [
            'items' => $items
        ];

        return view('pages.approval.index', $data);
    }

    public function show($id) {
        $approval = NeedApproval::All();
        $cicilPerCust = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(cicil) as totCicil'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $totalPerCust = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(total- retur) as totKredit'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        foreach($totalPerCust as $q) {
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] = $q->totKredit - $h->totCicil;
                }
            }
        }

        $data = [
            'approval' => $approval,
            'kode' => $id,
            'total' => $totalPerCust
        ];

        return view('pages.approval.show', $data);
    }

    public function process(Request $request, $id) {
        if($request->tipe == 'Faktur')
            $item = SalesOrder::where('id', $id)->first();
        else
            $item = BarangMasuk::where('id', $id)->first();

        if($item->{'status'} == 'PENDING_UPDATE') {
            $item->{'status'} = "UPDATE";
            $item->{'total'} = str_replace(".", "", $request->grandtotal);
        }
        elseif($item->{'status'} == 'PENDING_BATAL') {
            $item->{'status'} = "BATAL";
        }
        $item->save();

        $needApp = NeedApproval::where('id_dokumen', $id)->orderBy('id', 'desc')->get();
        Approval::create([
            'id' => $needApp[0]->id,
            'id_dokumen' => $id,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => $item->{'status'},
            'keterangan' => $needApp[0]->keterangan,
            'tipe' => $request->tipe
        ]);

        if($request->tipe == 'Faktur')
            $detil = DetilSO::where('id_so', $id)->get();
        else
            $detil = DetilBM::where('id_bm', $id)->get();

        foreach($detil as $d) {
            DetilApproval::create([
                'id_app' => $needApp[0]->id,
                'id_barang' => $d->id_barang,
                'harga' => $d->harga,
                'qty' => $d->qty,
                'diskon' => $d->diskon
            ]);
        }

        if($request->tipe == 'Faktur')
            DetilSO::where('id_so', $id)->delete();
        else
            DetilBM::where('id_bm', $id)->delete();

        $items = NeedAppDetil::where('id_app', $needApp[0]->id)->get();

        foreach($items as $item) {
            if($request->tipe == 'Faktur') {
                DetilSO::create([
                    'id_so' => $id,
                    'id_barang' => $item->id_barang,
                    'id_gudang' => 'GDG01',
                    'harga' => $item->harga,
                    'qty' => $item->qty,
                    'diskon' => $item->diskon
                ]);
            }
            else {
                DetilBM::create([
                    'id_bm' => $id,
                    'id_barang' => $item->id_barang,
                    'harga' => $item->harga,
                    'qty' => $item->qty,
                    'diskon' => $item->diskon,
                    'disPersen' => NULL,
                    'hpp' => NULL
                ]);
            }
        }

        foreach($needApp as $need) {
            NeedAppDetil::where('id_app', $need->id)->delete();
        }
        NeedApproval::where('id_dokumen', $id)->delete();

        return redirect()->route('approval');
    } 

    public function batal($id) {
        $item = SalesOrder::where('id', $id)->first();
        $item->{'status'} = "CETAK";
        $item->save();

        $ar = AccReceivable::where('id_so', $id)->delete();

        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');  
    }

    public function histori() {
        $items = Approval::with(['so', 'bm'])->get();
        // var_dump($items);
        $data = [
            'items' => $items
        ];
        return view('pages.approval.histori', $data);
    }

    public function detail($id) {
        $approval = Approval::All();

        $data = [
            'approval' => $approval,
            'kode' => $id
        ];
        
        return view('pages.approval.detail', $data);
    }
}
