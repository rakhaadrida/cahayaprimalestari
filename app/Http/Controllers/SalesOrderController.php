<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;
use App\Customer;
use App\Barang;
use App\DetilSO;
use App\TempDetilSO;
use App\HargaBarang;
use Carbon\Carbon;

class SalesOrderController extends Controller
{
    public function index() {
        $customer = Customer::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $lastcode = SalesOrder::max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'SO'.sprintf('%02s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $items = TempDetilSO::with(['barang', 'customer'])
                            ->where('id_so', $newcode)->latest()->get();
        $itemsRow = TempDetilSO::where('id_so', $newcode)->count();

        $data = [
            'customer' => $customer,
            'barang' => $barang,
            'harga' => $harga,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'itemsRow' => $itemsRow,
            'items' => $items
        ];

        return view('pages.penjualan.so', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function create(Request $request, $id) {
        TempDetilSO::create([
            'id_so' => $id,
            'id_barang' => $request->kodeBarang,
            'harga' => $request->harga,
            'qty' => $request->pcs,
            'diskon' => $request->diskon,
            'id_customer' => $request->kodeCustomer
        ]);

        return redirect()->route('so');
    }

    public function process(Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        
        SalesOrder::create([
            'id' => $id,
            'tgl_so' => $tanggal,
            'tgl_kirim' => $request->tanggalKirim,
            'total' => $request->grandtotal,
            'diskon' => $request->diskonFaktur,
            'status' => 'PENDING',
            'id_customer' => $request->kodeCustomer
        ]);

        $tempDetil = TempDetilSO::where('id_so', $id)->get();
        foreach($tempDetil as $td) {
            DetilSO::create([
                'id_so' => $td->id_so,
                'id_barang' => $td->id_barang,
                'harga' => $td->harga,
                'qty' => $td->qty,
                'diskon' => $td->diskon
            ]);

            $deleteTemp = TempDetilSO::where('id_so', $id)->where('id_barang', $td->id_barang)->delete();
        }

        return redirect()->route('so');
    }
}
