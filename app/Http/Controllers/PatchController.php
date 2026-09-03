<?php

namespace App\Http\Controllers;

use App\Exports\CancelledOrderExport;
use App\Models\AccReceivable;
use App\Models\BarangMasuk;
use App\Models\DetilSO;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use App\Models\SalesOrder;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PatchController extends Controller
{
    public function cancelOrder() {
        try {
            $lastcode = NeedApproval::max('id');
            $lastnumber = (int) substr($lastcode, 3, 4);
            $lastnumber++;
            $newcode = 'APP'.sprintf('%04s', $lastnumber);

            $id = 'IV26071202';

            $items = NeedApproval::query()
                ->with(['need_appdetil'])
                ->where('id_dokumen', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            NeedApproval::create([
                'id' => $newcode,
                'tanggal' => Carbon::now()->toDateString(),
                'status' => 'PENDING_BATAL',
                'keterangan' => 'Kurang 1 barang',
                'id_dokumen' => $id,
                'tipe' => 'Faktur',
                'id_user' => 8
            ]);

            if(($items->count() != 0) && ($items->first()->need_appdetil->count() != 0)) {
                $detil = NeedAppDetil::where('id_app', $items[0]->need_appdetil[0]->id_app)->get();
            } else {
                $detil = DetilSO::with(['so'])->where('id_so', $id)->get();
            }

            foreach($detil as $item) {
                $updateStok = StokBarang::where('id_barang', $item->id_barang)
                            ->where('id_gudang', $item->id_gudang)->first();

                $updateStok->{'stok'} += $item->qty;
                $updateStok->save();
            }

            return 'Cancel Order Successful';
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'message' => 'An error occurred while cancel data'
            ]);
        }   
    }    

    public function cancelledOrder() {
        $date = Carbon::now()->toDateString();
        $fileDate = Carbon::parse($date)->format('d-M');

        return Excel::download(new CancelledOrderExport(), 'Faktur-Batal-'.$fileDate.'.xlsx');
    }

    public function restoreOrder() {
        try {
            $items = SalesOrder::query()
                ->whereIn('id', ['IV26071140'])
                ->get();

            foreach($items as $item) {
                $item->status = 'CETAK';
                $item->save();
            }

            return 'Restore Order Successful';
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'message' => 'An error occurred while cancel data'
            ]);
        }   
    } 

    public function removeReceivable() {
        try {
            $items = AccReceivable::query()
                ->whereIn('id_so', ['IV26070000'])
                ->get();

            foreach($items as $item) {
                $item->delete();
            }

            return 'Remove Receivable Successful';
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->back()->withInput()->withErrors([
                'message' => 'An error occurred while cancel data'
            ]);
        }   
    } 

    public function indexPrintReceipt() {
        $items = BarangMasuk::query()
            ->whereIn('id_supplier', ['SUP04', 'SUP35', 'SUP36'])
            ->where('tanggal', '>=', '2023-01-01')
            ->where('tanggal', '<=', '2023-12-31')
            ->where('status', '!=', 'BATAL')
            ->orderBy('tanggal', 'asc')
            ->get();

        $data = [
            'items' => $items,
        ];

        return view('pages.patch.print-receipt.index', $data);
    }

    public function printReceipt(Request $request) {
        $items = BarangMasuk::query()
            ->whereIn('id_supplier', ['SUP04', 'SUP35', 'SUP36'])
            ->where('tanggal', '>=', '2023-01-01')
            ->where('tanggal', '<=', '2023-12-31')
            ->where('status', '!=', 'BATAL')
            ->whereBetween('id', [$request->kodeAwal, $request->kodeAkhir])
            ->orderBy('tanggal', 'asc')
            ->get();

        foreach($items as $i) {
            $item = BarangMasuk::where('id', $i->id)->get();
            $tabel = ceil($item->first()->detilbm->count() / 34);

            if($tabel > 1) {
                for($j = 1; $j < $tabel; $j++) {
                    $newItem = collect([
                        'id' => $item->first()->id.'Z',
                        'id_faktur' => $item->first()->id_faktur,
                        'tanggal' => $item->first()->tanggal,
                        'total' => $items->first()->total,
                        'id_supplier' => $items->first()->id_supplier,
                        'id_user' => $items->first()->id_user,
                    ]);

                    $items->push($newItem);
                }
            }
        }   

        $items = $items->sortBy(function ($product, $key) {
                    return $product['tanggal'].$product['id'];
                });
        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu,
            'awal' => $request->kodeAwal,
            'akhir' => $request->kodeAkhir
        ];

        return view('pages.patch.print-receipt.print', $data);
    } 

    public function updatePrintReceipt($start, $end) {
        $items = BarangMasuk::query()
            ->whereIn('id_supplier', ['SUP04', 'SUP35', 'SUP36'])
            ->where('tanggal', '>=', '2023-01-01')
            ->where('tanggal', '<=', '2023-12-31')
            ->where('status', '!=', 'BATAL')
            ->whereBetween('id', [$start, $end])
            ->orderBy('tanggal', 'asc')
            ->get();

        foreach($items as $item) {
            $item->status = 'CETAK';
            $item->save();
        }

        return redirect()->route('index-print-receipt');
    }
}
