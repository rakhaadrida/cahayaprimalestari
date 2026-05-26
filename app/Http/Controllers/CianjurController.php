<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\HargaBarang;
use App\Models\Faktur;
use App\Models\FakturItem;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CianjurController extends Controller
{
    public function so() {
        $barang = Barang::All();
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
}
