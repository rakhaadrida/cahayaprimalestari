<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;
use App\Customer;
use App\Barang;
use App\StokBarang;
use App\DetilSO;
use App\TempDetilSO;
use App\HargaBarang;
use Carbon\Carbon;

class SalesOrderController extends Controller
{
    public function index() {
        $customer = Customer::with(['sales'])->get();
        $barang = Barang::All();
        $harga = HargaBarang::All();

        $lastcode = SalesOrder::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'INV'.sprintf('%04s', $lastnumber);

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
            'id_customer' => $request->kodeCustomer,
            'tgl_kirim' => $request->tanggalKirim,
            'tempo' => $request->tempo,
            'pkp' => $request->pkp
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
            'tempo' => $request->tempo,
            'pkp' => $request->pkp,
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

            // $updateStok = StokBarang::where('id_barang', $td->id_barang)
            //                 ->where('id_gudang', 'GDG01')->first();
            $updateStok = StokBarang::where('id_barang', $td->id_barang)->get();
            foreach($updateStok as $us) {
                if($td->qty <= $us->stok) {
                    $us->stok -= $td->qty;
                }
                else {
                    $td->qty -= $us->stok;
                    $us->stok -= $us->stok;
                }
                $us->save();
            }

            $deleteTemp = TempDetilSO::where('id_so', $id)->where('id_barang', $td->id_barang)->delete();
        }

        return redirect()->route('so');
    }

    public function remove($id, $barang) {
        $tempDetil = TempDetilSO::where('id_so', $id)->where('id_barang', $barang)->delete();

        return redirect()->route('so');
    }

    public function change() {
        $so = SalesOrder::All();
        $customer = Customer::All();

        $data = [
            'so' => $so,
            'customer' => $customer
        ];

        return view('pages.penjualan.ubahFaktur', $data);
    }

    public function show(Request $request) {
        $items = SalesOrder::with('customer')->where('id', $request->kode)
                ->orWhere('id_customer', $request->kodeCustomer)
                ->orWhereBetween('tgl_so', [$request->tglAwal, $request->tglAkhir])
                ->first();
        $itemsDetail = DetilSO::with(['so', 'barang'])->where('id_so', $items->id)->get();
        $itemsRow = SalesOrder::where('id', $request->kode)
                    ->orWhere('id_customer', $request->kodeCustomer)
                    ->orWhereBetween('tgl_so', [$request->tglAwal, $request->tglAkhir])
                    ->count();
        $customer = Customer::All();
        $so = SalesOrder::All();
        
        $data = [
            'items' => $items,
            'itemsDetail' => $itemsDetail,
            'itemsRow' => $itemsRow,
            'customer' => $customer,
            'so' => $so
        ];

        return view('pages.penjualan.detilFaktur', $data);
    }
}
