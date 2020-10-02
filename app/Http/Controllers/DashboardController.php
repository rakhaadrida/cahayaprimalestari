<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;

class DashboardController extends Controller
{
    public function index() {
        // $items = SalesOrder::where('status', 'LIKE', '%PENDING%')->get();
        // $data = [
        //     'items' => $items
        // ];

        return view('pages.dashboard');
    }
}
