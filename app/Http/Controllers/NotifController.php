<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\BarangMasuk;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use Illuminate\Support\Facades\DB;

class NotifController extends Controller
{
    public function index() {
        $so = SalesOrder::has('approval')->with(['customer'])
                ->select('id', 'status', 'id_customer')
                ->whereIn('status', ['UPDATE', 'BATAL', 'APPROVE_LIMIT'])
                ->get();
        $bm = BarangMasuk::has('approval')->with(['supplier'])
                ->select('id', 'id_supplier', 'status')
                ->whereIn('status', ['UPDATE', 'BATAL'])
                ->get();

        $items = $so->merge($bm);
        $items = $items->sortBy(function($sort) {
            return $sort->approval[0]->created_at;
        });
        
        // return response()->json($items);

        $data = [
            'items' => $items
        ];

        return view('pages.notif.index', $data);
    }

    public function show($id) {
        $so = SalesOrder::has('approval')->with(['customer'])
                ->select('id', 'tgl_so', 'id_customer', 'status', 'kategori')
                ->whereIn('status', ['UPDATE', 'BATAL', 'APPROVE_LIMIT'])
                ->get();
        $bm = BarangMasuk::has('approval')->with(['supplier', 'gudang'])
                ->select('id', 'tanggal', 'id_supplier', 'status', 'id_gudang')
                ->whereIn('status', ['UPDATE', 'BATAL'])
                ->get();

        $items = $so->merge($bm);
        $items = $items->sortBy(function($sort) {
            return $sort->approval[0]->created_at;
        });

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
            'notif' => $items,
            'kode' => $id,
            'total' => $totalPerCust
        ];

        return view('pages.notif.show', $data);
    }
}
