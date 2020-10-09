<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;

class NotifController extends Controller
{
    public function index() {
        $items = SalesOrder::has('approval')->where('status', 'UPDATE')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.notif.index', $data);
    }

    public function show($id) {
        $status = SalesOrder::has('approval')->where('status', 'UPDATE')->get();
        // $items = DetilSO::with(['so', 'barang'])->where('id_so', $id)->get();
        // $itemsUpdate = NeedApproval::with(['barang'])->where('id_so', $id)->get();
        $data = [
            'status' => $status,
            // 'items' => $items,
            // 'itemsUpdate' => $itemsUpdate,
            'kode' => $id
        ];

        return view('pages.notif.show', $data);
    }
}
