<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $items = Customer::All();
        $data = [
            'items' => $items
        ];

        return view('pages.customer.index', $data);
    }

    public function create()
    {
        $lastcode = Customer::max('id');
        //$lastnumber = (int) substr($lastcode, 3, 2);
        $lastcode++;
        $newcode = 'CUS'.sprintf("%02s", $lastcode);

        $data = [
            'newcode' => $newcode
        ];

        return view('pages.customer.create', $data);
    }

    public function store(Request $request)
    {
        Customer::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'contact_person' => $request->contact_person,
            'tempo' => $request->tempo,
            'limit' => $request->limit,
            'sales_cover' => $request->sales_cover
        ]);

        return redirect()->route('customer.index');
    }

    public function show($id)
    {
        // $item = Customer::findOrFail($id);
        // $data = [
        //     'item' => $item
        // ];

        // return view('pages.customer.show', $data);
    }

    public function edit($id)
    {
        $item = Customer::findOrFail($id);
        $data = [
            'item' => $item
        ];

        return view('pages.customer.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $item = Customer::findOrFail($id);
        $item->update($data);

        return redirect()->route('customer.index');
    }

    public function destroy($id)
    {
        $item = Customer::findOrFail($id);
        $item->delete();

        return redirect()->route('customer.index');
    }
}
