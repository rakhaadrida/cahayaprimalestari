<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;

class CetakFakturController extends Controller
{
    public function index() {
        $items = SalesOrder::where('status', 'INPUT')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.penjualan.cetakFaktur', $data);
    }
}
