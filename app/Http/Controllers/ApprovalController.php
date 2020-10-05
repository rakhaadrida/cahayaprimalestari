<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use App\Models\TempDetilSO;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;

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
        $items = DetilSO::with(['so', 'barang'])->where('id_so', $id)->get();
        $itemsUpdate = TempDetilSO::with(['barang'])->where('id_so', $id)->get();
        $data = [
            'items' => $items,
            'itemsUpdate' => $itemsUpdate
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

        DetilSO::where('id_so', $id)->delete();
        $items = TempDetilSO::where('id_so', $id)->get();

        foreach($items as $item) {
            DetilSO::create([
                'id_so' => $item->id_so,
                'id_barang' => $item->id_barang,
                'harga' => str_replace(".", "", $item->harga),
                'qty' => $item->qty,
                'diskon' => $item->diskon
            ]);
        }
        
        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');  
    } 

    public function batal($id) {
        $item = SalesOrder::where('id', $id)->first();
        $item->{'status'} = "CETAK";
        $item->save();

        session()->put('url.intended', URL::previous());
        return Redirect::intended('/');  
    }
}
