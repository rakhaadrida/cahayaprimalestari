<?php

namespace App\Http\Controllers;

use App\Exports\CancelledOrderExport;
use App\Models\AccReceivable;
use App\Models\DetilSO;
use App\Models\NeedApproval;
use App\Models\NeedAppDetil;
use App\Models\SalesOrder;
use App\Models\StokBarang;
use Carbon\Carbon;
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
}
