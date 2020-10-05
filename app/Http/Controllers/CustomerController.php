<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Sales;

class CustomerController extends Controller
{
    public function index()
    {
        $items = Customer::with(['sales'])->get();

        $data = [
            'items' => $items
        ];

        return view('pages.customer.index', $data);
    }

    public function create()
    {
        $sales = Sales::All();
        $lastcode = Customer::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 3);
        $lastnumber++;
        $newcode = 'CUS'.sprintf("%03s", $lastnumber);

        $data = [
            'newcode' => $newcode,
            'sales' => $sales
        ];

        return view('pages.customer.create', $data);
    }

    public function store(CustomerRequest $request)
    {
        Customer::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'contact_person' => $request->contact_person,
            'tempo' => $request->tempo,
            'limit' => $request->limit,
            'id_sales' => $request->id_sales
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
        $item = Customer::with(['sales'])->findOrFail($id);
        $sales = Sales::All();

        $data = [
            'item' => $item,
            'sales' => $sales
        ];

        return view('pages.customer.edit', $data);
    }

    public function update(CustomerRequest $request, $id)
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
