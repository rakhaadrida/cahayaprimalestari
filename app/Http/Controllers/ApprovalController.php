<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use App\Models\Approval;
use App\Models\DetilApproval;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index() {
        $items = SalesOrder::where('status', 'LIKE', '%PENDING%')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.approval.index', $data);
    }

    public function show($id) {
        $status = SalesOrder::where('status', 'LIKE', '%PENDING%')->get();
        // $items = DetilSO::with(['so', 'barang'])->where('id_so', $id)->get();
        // $itemsUpdate = NeedApproval::with(['barang'])->where('id_so', $id)->get();
        $data = [
            'status' => $status,
            // 'items' => $items,
            // 'itemsUpdate' => $itemsUpdate,
            'kode' => $id
        ];

        return view('pages.approval.show', $data);
    }

    public function process(Request $request, $id) {
        $item = SalesOrder::where('id', $id)->first();
        if($item->{'status'} == 'PENDING_UPDATE') {
            $item->{'status'} = "UPDATE";
            $item->{'total'} = str_replace(".", "", $request->grandtotal);
        }
        elseif($item->{'status'} == 'PENDING_BATAL') {
            $item->{'status'} = "BATAL";
        }
        $item->save();

        $needApp = NeedApproval::where('id_so', $id)->orderBy('id', 'desc')->get();
        Approval::create([
            'id_so' => $id,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => $item->{'status'},
            'keterangan' => $needApp[0]->keterangan
        ]);

        $detil = DetilSO::where('id_so', $id)->get();
        foreach($detil as $d) {
            DetilApproval::create([
                'id_so' => $d->id_so,
                'id_barang' => $d->id_barang,
                'harga' => $d->harga,
                'qty' => $d->qty,
                'diskon' => $d->diskon
            ]);
        }

        DetilSO::where('id_so', $id)->delete();
        $items = NeedAppDetil::where('id_app', $needApp[0]->id)->get();

        foreach($items as $item) {
            DetilSO::create([
                'id_so' => $id,
                'id_barang' => $item->id_barang,
                'id_gudang' => 'GDG01',
                'harga' => $item->harga,
                'qty' => $item->qty,
                'diskon' => $item->diskon
            ]);
        }

        foreach($needApp as $need) {
            NeedAppDetil::where('id_app', $need->id)->delete();
        }
        NeedApproval::where('id_so', $id)->delete();

        return redirect()->route('approval');
    } 

    public function batal($id) {
        $item = SalesOrder::where('id', $id)->first();
        $item->{'status'} = "CETAK";
        $item->save();

        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');  
    }

    public function histori() {
        $items = Approval::All();
        // var_dump($items);
        $data = [
            'items' => $items
        ];
        return view('pages.approval.histori', $data);
    }

    public function detail($id) {
        $status = Approval::whereIn('status', ['UPDATE', 'BATAL'])->get();

        $data = [
            'status' => $status,
            'kode' => $id
        ];
        
        return view('pages.approval.detail', $data);
    }
}
