<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TandaTerima;

class TandaTerimaController extends Controller
{
    public function index() {
        $items = TandaTerima::groupBy('id')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.penjualan.tandaterima.index', $data);
    }

    public function detail(Request $request, $id) {
        $items = TandaTerima::groupBy('id')->get();
        $data = [
            'items' => $items,
            'kode' => $id
        ];

        return view('pages.penjualan.tandaterima.detail', $data);
    }
}
