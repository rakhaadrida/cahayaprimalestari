<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\BarangMasuk;
use App\Models\AccReceivable;
use App\Models\DetilAR;
use App\Models\Approval;
use Illuminate\Support\Facades\DB;

class NotifController extends Controller
{
    public function index() {
        $items = Approval::with(['so', 'bm'])
                ->select('id', 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe', 'baca')
                ->where('baca', 'F')->get();
        $so = SalesOrder::with(['customer'])
                ->select('id', 'status', 'id_customer')
                ->whereIn('status', ['UPDATE', 'BATAL', 'APPROVE_LIMIT'])
                ->whereHas('approval', function($q) {
                    $q->where('baca', 'F');
                })->get();
        $bm = BarangMasuk::with(['supplier'])
                ->select('id', 'id_supplier', 'status')
                ->whereIn('status', ['UPDATE', 'BATAL'])
                ->whereHas('approval', function($q) {
                    $q->where('baca', 'F');
                })->get();

        // return response()->json($items);

        // $items = $so->merge($bm);
        // $items = $items->sortBy(function($sort) {
        //     return $sort->approval[0]->created_at;
        // });

        $data = [
            'items' => $items
        ];

        return view('pages.notif.index', $data);
    }

    public function show($id) {
        $so = SalesOrder::with(['customer'])
                ->select('id', 'tgl_so', 'id_customer', 'status', 'kategori')
                ->whereIn('status', ['UPDATE', 'BATAL', 'APPROVE_LIMIT'])
                ->whereHas('approval', function($q) {
                    $q->where('baca', 'F');
                })->get();
        $bm = BarangMasuk::with(['supplier', 'gudang'])
                ->select('id', 'tanggal', 'id_supplier', 'status', 'id_gudang')
                ->whereIn('status', ['UPDATE', 'BATAL'])
                ->whereHas('approval', function($q) {
                    $q->where('baca', 'F');
                })->get();

        // $items = $so->merge($bm);
        // $items = $items->sortBy(function($sort) {
        //     return $sort->approval[0]->created_at;
        // });

        $items = Approval::with(['so', 'bm'])
                ->select('id', 'id_dokumen', 'tanggal', 'status', 'keterangan', 'tipe', 'baca')
                ->where('baca', 'F')->get();

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

    public function markAsRead($id) {
        $item = Approval::where('id', $id)->first();
        // return response()->json($item);
        $item->{'baca'} = 'T';
        $item->save();

        return redirect()->route('notif');
    }
}
