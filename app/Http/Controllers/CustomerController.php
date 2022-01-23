<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Sales;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function index()
    {
        $items = Customer::query()
            ->select('customer.*', 'sales.nama AS namaSales')
            ->join('sales', 'sales.id', 'customer.id_sales')
            ->get();

        $data = [
            'items' => $items
        ];

        return view('pages.customer.index', $data);
    }

    public function create()
    {
        $sales = Sales::All();
        $lastcode = Customer::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'CUS'.sprintf("%04s", $lastnumber);

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
            'alamat' => str_replace("\r\n"," ", $request->alamat),
            'telepon' => $request->telepon,
            'contact_person' => $request->contact_person,
            'npwp' => $request->npwp,
            'limit' => (int) str_replace(".", "", $request->limit),
            'tempo' => $request->tempo,
            'id_sales' => $request->id_sales,
            'ktp' => ($request->ktp != '' ? $request->file('ktp')->store('assets/customer', 'public') : NULL)
        ]);

        return redirect()->route('customer.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Customer::query()
            ->select('customer.*', 'sales.nama AS namaSales')
            ->join('sales', 'sales.id', 'customer.id_sales')
            ->findOrFail($id);

        $sales = Sales::All();

        $data = [
            'item' => $item,
            'sales' => $sales
        ];

        return view('pages.customer.edit', $data);
    }

    public function update(CustomerRequest $request, $id)
    {
        $item = Customer::where('id', $id)->first();
        $item->{'nama'} = $request->nama;
        $item->{'alamat'} = str_replace("\r\n"," ", $request->alamat);
        $item->{'telepon'} = $request->telepon;
        $item->{'contact_person'} = $request->contact_person;
        $item->{'npwp'} = $request->npwp;
        $item->{'limit'} = str_replace(".", "", $request->limit);
        $item->{'tempo'} = $request->tempo;
        $item->{'id_sales'} = $request->id_sales;
        if($request->ktp != '')
            $item->{'ktp'} = $request->file('ktp')->store('assets/customer', 'public');

        $item->save();

        // $item->{'ktp'} = ($request->ktp != '' ? $request->file('ktp')->store('assets/customer', 'public') : $item->{'ktp'});

        return redirect()->route('customer.index');
    }

    public function destroy($id)
    {
        $item = Customer::findOrFail($id);
        $item->delete();

        return redirect()->route('customer.index');
    }

    public function trash() {
        $items = Customer::query()
            ->select('customer.*', 'sales.nama AS namaSales')
            ->join('sales', 'sales.id', 'customer.id_sales')
            ->onlyTrashed()
            ->get();

        $data = [
            'items' => $items
        ];

        return view('pages.customer.trash', $data);
    }

    public function restore($id) {
        $item = Customer::onlyTrashed()->where('id', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Customer::onlyTrashed();
        $items->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Customer::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Customer::onlyTrashed();
        $items->forceDelete();

        return redirect()->back();
    }

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new CustomerExport(), 'Master Customer-'.$tglFile.'.xlsx');
    }
}
