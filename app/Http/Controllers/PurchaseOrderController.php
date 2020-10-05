<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Barang;
use Carbon\Carbon;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $lastcode = PurchaseOrder::max('id');
        // $lastnumber = (int) substr($lastcode, 3, 2);
        $lastcode++;
        $newcode = 'PO'.sprintf('%02s', $lastcode);

        $supplier = Supplier::All();
        $barang = Barang::All();
        $brg = Barang::select('nama')->get();
        foreach($brg as $b) {
            $namaBarang[] = $b->nama;
        }
        $tanggal = Carbon::now()->toDateString();
        $tanggal = Carbon::parse($tanggal)->format('d-m-Y');

        $data = [
            'newcode' => $newcode,
            'supplier' => $supplier,
            'barang' => $barang,
            'namaBarang' => $namaBarang,
            'brg' => $brg,
            'tanggal' => $tanggal
        ];
        
        // return view('pages.purchaseOrder', $data);
        return view('pages.poAlter', $data);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
