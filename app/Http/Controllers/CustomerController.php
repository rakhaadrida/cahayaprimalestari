<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\Sales;
use App\Models\SuratJalan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomerExport;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        // $sj = SuratJalan::All();
        // foreach($sj as $s) {
        //     $item = Customer::withTrashed()->where('id', $s->id)->first();
        //     $item->{'tempo'} = $s->tempo;
        //     $item->save();
        // }
        
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
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'contact_person' => $request->contact_person,
            'npwp' => $request->npwp,
            'limit' => (int) str_replace(".", "", $request->limit),
            'tempo' => $request->tempo,
            'id_sales' => $request->id_sales,
            // 'ktp' => $request->file('ktp')->store('assets/product', 'public')
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
        $item = Customer::where('id', $id)->first();
        $item->{'nama'} = $request->nama;
        $item->{'alamat'} = $request->alamat;
        $item->{'telepon'} = $request->telepon;
        $item->{'contact_person'} = $request->contact_person;
        $item->{'npwp'} = $request->npwp;
        $item->{'limit'} = str_replace(".", "", $request->limit);
        $item->{'tempo'} = $request->tempo;
        $item->{'id_sales'} = $request->id_sales;
        $item->save();

        return redirect()->route('customer.index');
    }

    public function destroy($id)
    {
        $item = Customer::findOrFail($id);
        $item->delete();

        return redirect()->route('customer.index');
    }

    public function trash() {
        $items = Customer::onlyTrashed()->get();
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

        return Excel::download(new CustomerExport(), 'Customer-'.$tglFile.'.xlsx');
    }
}
