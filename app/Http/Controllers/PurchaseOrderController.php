<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PurchaseOrder;
use App\Supplier;
use App\Barang;

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

        $data = [
            'newcode' => $newcode,
            'supplier' => $supplier,
            'barang' => $barang
        ];
        
        return view('pages.purchaseOrder', $data);
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
