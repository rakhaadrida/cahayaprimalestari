<?php

namespace App\Http\Controllers;

use App\Exports\BarangExport;
use App\Models\AccReceivable;
use App\Models\AR_Retur;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\DetilAR;
use App\Models\DetilSO;
use App\Models\Faktur;
use App\Models\FakturItem;
use App\Models\Gudang;
use App\Models\Harga;
use App\Models\HargaBarang;
use App\Models\JenisBarang;
use App\Models\Sales;
use App\Models\SalesOrder;
use App\Models\StokBarang;
use App\Models\Subjenis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class CianjurController extends Controller
{
    public function indexBarang() {
        $items = Barang::query()->where('tipe', 'TOKO')->get();

        $warehouse = Gudang::query()->where('tipe', 'TOKO')->first();
        
        $productStocks = StokBarang::query()
            ->where('id_gudang', $warehouse->id)
            ->whereNull('deleted_at')
            ->get();

        $mapStockByProduct = [];
        foreach($productStocks as $productStock) {
            $mapStockByProduct[$productStock->id_barang] = $productStock->stok;
        }

        $data =  [
            'items' => $items,
            'mapStockByProduct' => $mapStockByProduct
        ];

        return view('pages.cianjur.barang.index', $data);
    }

    public function showBarang($id) {
        $item = Barang::withTrashed()
            ->select('barang.*', 'jenisbarang.nama AS namaJenis', 'subjenis.nama AS namaSub')
            ->leftJoin('jenisbarang', 'jenisbarang.id', 'barang.id_kategori')
            ->leftJoin('subjenis', 'subjenis.id', 'barang.id_sub')
            ->where('barang.id', $id)
            ->first();

        $gudang = Gudang::query()->where('tipe', 'TOKO')->first();

        $stok = StokBarang::query()
            ->where('id_barang', $id)
            ->where('id_gudang', $gudang->id)
            ->whereNull('deleted_at')
            ->first();

        $hargaBarang = HargaBarang::where('id_barang', $id)->get();
        $harga = Harga::query()->where('id', 'HRG01')->get();

        $data = [
            'item' => $item,
            'hargaBarang' => $hargaBarang,
            'harga' => $harga,
            'gudang' => $gudang,
            'stok' => $stok
        ];

        return view('pages.cianjur.barang.show', $data);
    }

    public function createBarang() {
        $lastcode = Barang::withTrashed()->where('tipe', 'TOKO')->max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'BRT'.sprintf("%04s", $lastnumber);

        $jenis = JenisBarang::All();
        $subjenis = Subjenis::All();
        $harga = Harga::query()->where('id', 'HRG01')->get();

        $data = [
            'newcode' => $newcode,
            'jenis' => $jenis,
            'subjenis' => $subjenis,
            'harga' => $harga
        ];

        return view('pages.cianjur.barang.create', $data);
    }

    public function storeBarang(Request $request) {
        Barang::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'id_kategori' => $request->kodeJenis,
            'id_sub' => $request->kodeSub,
            'satuan' => $request->satuan,
            'ukuran' => $request->ukuran,
            'tipe' => 'TOKO'
        ]);

        $gudang = Gudang::query()->where('tipe', 'TOKO')->get();

        foreach($gudang as $g) {
            StokBarang::create([
                'id_barang' => $request->kode,
                'id_gudang' => $g->id,
                'status' => 'T',
                'stok' => $request->stok ?? 0
            ]);
        }

        $harga = Harga::query()->where('id', 'HRG01')->get();
        for($i = 0; $i < $harga->count(); $i++) {
            HargaBarang::create([
                'id_barang' => $request->kode,
                'id_harga' => $request->kodeHarga[$i],
                'harga' => str_replace(".", "", $request->harga[$i]),
                'ppn' => str_replace(".", "", $request->ppn[$i]),
                'harga_ppn' => str_replace(".", "", $request->hargaPPN[$i])
            ]);
            }

        return redirect()->route('barang-cianjur');
    }

    public function editBarang($id) {
        $item = Barang::query()
            ->select('barang.*', 'jenisbarang.nama AS namaJenis', 'subjenis.nama AS namaSub')
            ->leftJoin('jenisbarang', 'jenisbarang.id', 'barang.id_kategori')
            ->leftJoin('subjenis', 'subjenis.id', 'barang.id_sub')
            ->findOrFail($id);

        $jenis = JenisBarang::All();
        $subjenis = Subjenis::All();
        $harga = Harga::query()->where('id', 'HRG01')->get();
        $items = HargaBarang::where('id_barang', $id)->get();

        $gudang = Gudang::query()->where('tipe', 'TOKO')->first();

        $stok = StokBarang::query()
            ->where('id_barang', $id)
            ->where('id_gudang', $gudang->id)
            ->whereNull('deleted_at')
            ->first();

        $data = [
            'item' => $item,
            'jenis' => $jenis,
            'subjenis' => $subjenis,
            'harga' => $harga,
            'items' => $items,
            'stok' => $stok
        ];

        return view('pages.cianjur.barang.edit', $data);
    }

    public function updateBarang(Request $request, $id) {
        $item = Barang::where('id', $id)->first();
        
        $item->{'nama'} = $request->nama;
        $item->{'id_kategori'} = $request->kodeJenis;
        $item->{'id_sub'} = $request->kodeSub;
        $item->{'satuan'} = $request->satuan;
        $item->{'ukuran'} = $request->ukuran;
        
        $item->save();

        $kode = $id;
        $items = HargaBarang::where('id_barang', $kode)->get();
        $itemsRow = HargaBarang::where('id_barang', $kode)->count();
        $harga = Harga::query()->where('id', 'HRG01')->get();

        $j = 0;
        for($i = 0; $i < $harga->count(); $i++) {
            if($items->count() == $harga->count()) {
                $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
            else if(($items->count() > 0) && ($j < $items->count())) {
                if($items[$j]->id_harga == $harga[$i]->id) {
                    $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i],
                    $request->ppn[$i], $request->hargaPPN[$i]);
                    $j++;
                }
                else {
                    $this->createHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
                }
            }
            else {
                $this->createHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
        }

        $gudang = Gudang::query()->where('tipe', 'TOKO')->get();

        foreach($gudang as $g) {
            $this->updateStok($kode, $g->id, $request->stok);
        }

        return redirect()->route('barang-cianjur');
    }

    public function createHargaBarang($id) {
        $items = HargaBarang::where('id_barang', $id)->get();
        $harga = Harga::query()->where('id', 'HRG01')->get();
        $barang = Barang::where('id', $id)->first();

        $data = [
            'items' => $items,
            'harga' => $harga,
            'barang' => $barang
        ];

        return view('pages.cianjur.barang.harga', $data);
    }

    public function storeHargaBarang(Request $request) {
        $kode = $request->kode;

        $items = HargaBarang::where('id_barang', $kode)->get();
        $itemsRow = HargaBarang::where('id_barang', $kode)->count();
        $harga = Harga::query()->where('id', 'HRG01')->get();

        $j = 0;
        for($i = 0; $i < $harga->count(); $i++) {
            if($items->count() == $harga->count()) {
                $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
            else if(($items->count() > 0) && ($j < $items->count())) {
                if($items[$j]->id_harga == $harga[$i]->id) {
                    $this->updateHarga($kode, $harga[$i]->id, $request->harga[$i],
                    $request->ppn[$i], $request->hargaPPN[$i]);
                    $j++;
                }
                else {
                    $this->createHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
                }
            }
            else {
                $this->createHarga($kode, $harga[$i]->id, $request->harga[$i], $request->ppn[$i], $request->hargaPPN[$i]);
            }
        }

        return redirect()->route('barang-cianjur');
    }

    public function createStokBarang($id) {
        $items = StokBarang::where('id_barang', $id)->get();
        $gudang = Gudang::query()->where('tipe', 'TOKO')->get();
        $barang = Barang::where('id', $id)->first();

        $data = [
            'items' => $items,
            'gudang' => $gudang,
            'barang' => $barang
        ];

        return view('pages.cianjur.barang.stok', $data);
    }

    public function storeStokBarang(Request $request) {
        $kode = $request->kode;

        $items = StokBarang::where('id_barang', $kode)->where('status', 'T')->get();
        $itemsRow = StokBarang::where('id_barang', $kode)->count();
        $gudang = Gudang::query()->where('tipe', 'TOKO')->get();

        $j = 0;
        for($i = 0; $i < $gudang->count(); $i++) {
            if($items->count() == $gudang->count()) {
                $this->updateStok($kode, $gudang[$i]->id, $request->stok[$i]);
            }
            else if(($items->count() > 0) && ($j < $items->count())) {
                if($items[$j]->id_gudang == $gudang[$i]->id) {
                    $this->updateStok($kode, $gudang[$i]->id, $request->stok[$i]);
                    $j++;
                }
                else {
                    $this->createStok($kode, $gudang[$i]->id, $request->stok[$i]);
                }
            }
            else {
                $this->createStok($kode, $gudang[$i]->id, $request->stok[$i]);
            }
        }

        return redirect()->route('barang-cianjur');
    }

    public function deleteBarang($id) {
        $item = Barang::findOrFail($id);
        $item->delete();

        $items = StokBarang::where('id_barang', $id);
        $items->delete();

        $item = HargaBarang::where('id_barang', $id);
        $item->delete();

        return redirect()->route('barang-cianjur');
    }

    public function indexDeletedBarang() {
        $items = Barang::onlyTrashed()->where('tipe', 'TOKO')->get();
        $warehouse = Gudang::query()->where('tipe', 'TOKO')->first();
        
        $productStocks = StokBarang::onlyTrashed()
            ->where('id_gudang', $warehouse->id)
            ->get();

        $mapStockByProduct = [];
        foreach($productStocks as $productStock) {
            $mapStockByProduct[$productStock->id_barang] = $productStock->stok;
        }

        $data = [
            'items' => $items,
            'mapStockByProduct' => $mapStockByProduct,
        ];

        return view('pages.cianjur.barang.trash', $data);
    }

    public function restoreBarang($id) {
        try {
            DB::beginTransaction();

            $items = Barang::onlyTrashed()->where('tipe', 'TOKO');
            $stocks = StokBarang::onlyTrashed();
            $prices = HargaBarang::onlyTrashed();

            if($id) {
                $items = $items->where('id', $id);
                $stocks = $stocks->where('id_barang', $id);
                $prices = $prices->where('id_barang', $id);
            }

            $items->restore();
            $stocks->restore();
            $prices->restore();

            DB::commit();

            return redirect()->route('deleted-barang-cianjur');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'message' => 'An error occurred while updating data'
            ]);
        }
    }

    public function destroyBarang($id) {
        try {
            DB::beginTransaction();

            $items = Barang::onlyTrashed()->where('tipe', 'TOKO');
            $stocks = StokBarang::onlyTrashed();
            $prices = HargaBarang::onlyTrashed();

            if($id) {
                $items = $items->where('id', $id);
                $stocks = $stocks->where('id_barang', $id);
                $prices = $prices->where('id_barang', $id);
            }

            $items->forceDelete();
            $stocks->forceDelete();
            $prices->forceDelete();

            DB::commit();

            return redirect()->route('deleted-barang-cianjur');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'message' => 'An error occurred while updating data'
            ]);
        }
    }

    public function excelBarang() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new BarangExport(), 'Master Barang-'.$tglFile.'.xlsx');
    }

    public function so() {
        $barang = Barang::query()->where('tipe', 'TOKO')->get();
        $harga = HargaBarang::All();

        $stok = StokBarang::query()
            ->join('gudang', 'gudang.id', 'stok.id_gudang')
            ->where('tipe', 'TOKO')
            ->get();

        $gudang = Gudang::where('tipe', 'TOKO')->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastInvoice = Faktur::query()
            ->selectRaw('max(nomor) as number')
            ->whereYear('created_at', $waktu->year)
            ->whereMonth('created_at', $month)
            ->get();

        $lastnumber = (int) substr($lastInvoice[0]->number, 6, 4);
        $lastnumber++;

        $newNumber = 'FT'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'barang' => $barang,
            'harga' => $harga,
            'stok' => $stok,
            'gudang' => $gudang,
            'newNumber' => $newNumber,
            'tanggal' => $tanggal
        ];

        return view('pages.cianjur.so.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function process(Request $request, $id) {
        try {
            DB::beginTransaction();

            $jumlah = $request->jumBaris;

            $tanggal = $request->tanggal;
            $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');

            $waktu = Carbon::now('+07:00');
            $bulan = $waktu->format('m');
            $month = $waktu->month;
            $tahun = substr($waktu->year, -2);

            $lastInvoice = Faktur::query()
                ->selectRaw('MAX(nomor) as number')
                ->whereYear('created_at', $waktu->year)
                ->whereMonth('created_at', $month)
                ->get();

            $lastnumber = (int) substr($lastInvoice[0]->number, 6, 4);
            $lastnumber++;

            $newNumber = 'FT'.$tahun.$bulan.sprintf('%04s', $lastnumber);
            $number = $newNumber;

            $invoice = Faktur::create([
                'nomor' => $number,
                'tanggal' => $tanggal,
                'total' => 0,
                'id_user' => Auth::user()->id,
                'id_cabang' => 3
            ]);

            $subtotal = 0;
            for($i = 0; $i < $jumlah; $i++) {
                if(($request->kodeBarang[$i] != "") && ($request->qty[$i] != "")) {
                    $quantity = $request->qty[$i];
                    $price = str_replace(".", "", $request->harga[$i]);
                    $total = $quantity * $price;    

                    $invoice->items()->create([
                        'id_barang' => $request->kodeBarang[$i],
                        'qty' => $quantity,
                        'harga' => $price,
                        'jumlah' => $total
                    ]);

                    $subtotal += $total;

                    $updateStok = StokBarang::query()
                        ->where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $request->kodeGudang[$i])
                        ->first();

                    $updateStok->{'stok'} -= $request->qty[$i];
                    $updateStok->save();
                }
            }

            $invoice->update([
                'total' => $subtotal
            ]);

            DB::commit();

            return redirect()->route('so-cianjur');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'message' => 'An error occurred while saving data'
            ]);
        }
    }

    public function indexTransaction(Request $request) {
        $filter = (object) $request->all();

        $startDate = $filter->start_date ?? Carbon::now()->format('d-m-Y');
        $finalDate = $filter->final_date ?? null;

        if(!$finalDate) {
            $finalDate = $startDate;
        }

        $baseQuery = Faktur::query();

        if($startDate) {
            $baseQuery = $baseQuery->where('faktur.tanggal', '>=',  Carbon::parse($startDate)->startOfDay());
        }

        if($finalDate) {
            $baseQuery = $baseQuery->where('faktur.tanggal', '<=', Carbon::parse($finalDate)->endOfDay());
        }

        $items = $baseQuery
            ->orderBy('faktur.tanggal')
            ->get();

        $data = [
            'startDate' => $startDate,
            'finalDate' => $finalDate,
            'items' => $items
        ];

        return view('pages.cianjur.transaksiharian.index', $data);
    }
    
    public function showTransaction(Request $request, $id) {
        $filter = (object) $request->all();

        $startDate = $filter->start_date ?? Carbon::now()->format('d-m-Y');
        $finalDate = $filter->final_date ?? null;

        if(!$finalDate) {
            $finalDate = $startDate;
        }

        $item = Faktur::query()->find($id);
        
        $data = [
            'startDate' => $startDate,
            'finalDate' => $finalDate,
            'item' => $item,
        ];

        return view('pages.cianjur.transaksiharian.detail', $data);
    }

    public function printTransaction(Request $request) {
        $filter = (object) $request->all();

        $startDate = $filter->start_date ?? Carbon::now()->format('d-m-Y');
        $finalDate = $filter->final_date ?? null;

        if(!$finalDate) {
            $finalDate = $startDate;
        }

        $baseQuery = Faktur::query();

        if($startDate) {
            $baseQuery = $baseQuery->where('faktur.tanggal', '>=',  Carbon::parse($startDate)->startOfDay());
        }

        if($finalDate) {
            $baseQuery = $baseQuery->where('faktur.tanggal', '<=', Carbon::parse($finalDate)->endOfDay());
        }

        $items = $baseQuery
            ->orderBy('faktur.tanggal')
            ->get();

        $printTime = Carbon::now('+07:00')->format('d F Y, H:i:s');

        $startDate = $this->formatTanggal($startDate, 'd-M-y');
        $finalDate = $this->formatTanggal($finalDate, 'd-M-y');

        $data = [
            'items' => $items,
            'startDate' => $startDate,
            'finalDate' => $finalDate,
            'printTime' => $printTime
        ];

        $pdf = PDF::loadview('pages.cianjur.transaksiharian.print', $data)->setPaper('a4', 'landscape');
        ob_end_clean();

        return $pdf->stream('cetak-faktur-toko.pdf');
    }

    public function salesOrderMitra($status) {
        set_time_limit(600);

        $customer = Customer::with(['sales'])->get();
        $cust = Customer::pluck('nama')->toArray();
        $sales = Sales::All();
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $kategori = JenisBarang::orderBy('nama')->get();

        $hrg = Harga::pluck('tipe')->toArray();
        $kodeBarang = Barang::pluck('id')->toArray();
        $namaBarang = Barang::pluck('nama')->toArray();

        $stok = StokBarang::query()
            ->join('gudang', 'gudang.id', 'stok.id_gudang')
            ->where('id_gudang', 'GDG09')->get();

        $gudang = Gudang::query()->where('id', 'GDG09')->get();
        $lastSO = SalesOrder::where('created_at', '!=', NULL)->latest()->take(1)->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = SalesOrder::selectRaw('max(id) as id')->where('id', 'LIKE', 'IV%')
                    ->whereYear('created_at', $waktu->year)
                    ->whereMonth('created_at', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'IV'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $cicilPerCust = DetilAR::join('ar', 'ar.id', '=', 'detilar.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(cicil) as totCicil'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')
                        ->get();

        $totalPerCust = AccReceivable::join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(total) as totKredit'))
                        ->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')->get();

        $returPerCust = AR_Retur::join('ar', 'ar.id', 'ar_retur.id_ar')
                        ->join('so', 'so.id', '=', 'ar.id_so')
                        ->select('id_customer', DB::raw('sum(ar_retur.total) as totRetur'))->where('keterangan', 'BELUM LUNAS')
                        ->groupBy('id_customer')->get();

        foreach($totalPerCust as $q) {
            foreach($cicilPerCust as $h) {
                if($q->id_customer == $h->id_customer) {
                    $q['total'] = $q->totKredit - $h->totCicil;
                } else {
                    $q['total'] = $q->totKredit - 0;
                }
            }

            foreach($returPerCust as $r) {
                if($q->id_customer == $r->id_customer) {
                    $q['total'] -= $r->totRetur;
                } else {
                    $q['total'] -= 0;
                }
            }
        }

        $data = [
            'customer' => $customer,
            'cust' => $cust,
            'sales' => $sales,
            'barang' => $barang,
            'kodeBarang' => $kodeBarang,
            'namaBarang' => $namaBarang,
            'harga' => $harga,
            'kategori' => $kategori,
            'hrg' => $hrg,
            'stok' => $stok,
            'gudang' => $gudang,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'status' => $status,
            'lastcode' => $lastcode,
            'lastSO' => $lastSO,
            'totalKredit' => $totalPerCust
        ];

        return view('pages.cianjur.so.index-mitra', $data);
    }

    public function editSalesOrderMitra(Request $request, $id) {
        $items = SalesOrder::with(['customer', 'need_approval'])->where('id', $id)->get();
        $itemsRow = DetilSO::where('id_so', $id)->groupBy('id_barang')->get();
        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-M-y');
        $barang = Barang::All();
        $harga = HargaBarang::All();
        $kategori = JenisBarang::orderBy('nama')->get();
        $hrg = Harga::All();
        $sales = Sales::All();
        $customer = Customer::All();
        $stok = StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                ->where('tipe', 'BIASA')->get();
        $gudang = Gudang::where('tipe', 'BIASA')->get();

        $data = [
            'items' => $items,
            'itemsRow' => $itemsRow,
            'tanggal' => $tanggal,
            'barang' => $barang,
            'harga' => $harga,
            'kategori' => $kategori,
            'hrg' => $hrg,
            'sales' => $sales,
            'customer' => $customer,
            'gudang' => $gudang,
            'stok' => $stok,
            'id' => $request->id,
            'nama' => $request->nama,
            'tglAwal' => $request->tglAwal,
            'tglAkhir' => $request->tglAkhir
        ];

        return view('pages.cianjur.so.edit', $data);
    }

    protected function createHarga($kode, $id, $harga, $ppn, $hargaPPN) {
        HargaBarang::create([
            'id_barang' => $kode,
            'id_harga' => $id,
            'harga' => str_replace(".", "", $harga),
            'ppn' => str_replace(".", "", $ppn),
            'harga_ppn' => str_replace(".", "", $hargaPPN)
        ]);
    }

    protected function updateHarga($kode, $id, $harga, $ppn, $hargaPPN) {
        $updateHarga = HargaBarang::where('id_barang', $kode)->where('id_harga', $id)->first();
        $updateHarga->{'harga'} = str_replace(".", "", $harga);
        $updateHarga->{'ppn'} = str_replace(".", "", $ppn);
        $updateHarga->{'harga_ppn'} = str_replace(".", "", $hargaPPN);
        $updateHarga->save();
    }

    protected function createStok($kode, $id, $stok) {
        StokBarang::create([
            'id_barang' => $kode,
            'id_gudang' => $id,
            'status' => 'T',
            'stok' => $stok,
        ]);
    }

    protected function updateStok($kode, $id, $stok) {
        $updateStok = StokBarang::where('id_barang', $kode)->where('id_gudang', $id)->first();
        $updateStok->{'stok'} = $stok;
        $updateStok->save();
    }
}
