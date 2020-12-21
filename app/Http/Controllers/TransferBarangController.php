<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\TransferBarang;
use App\Models\DetilTB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransferBarangController extends Controller
{
    public function index() {
        $barang = Barang::All();
        $gudang = Gudang::All();
        $stok = StokBarang::with(['gudang'])->get();

        $lastcode = TransferBarang::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'TB'.sprintf('%04s', $lastnumber);

        // $items = TempDetilTB::with(['barang', 'gudangAsal', 'gudangTujuan'])->where('id_tb', $newcode)->orderBy('created_at', 'asc')->get();
        // $itemsRow = TempDetilTB::where('id_tb', $newcode)->count();

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'barang' => $barang,
            'gudang' => $gudang,
            'stok' => $stok,
            'newcode' => $newcode,
            'tanggal' => $tanggal
            // 'items' => $items,
            // 'itemsRow' => $itemsRow
        ];

        return view('pages.pembelian.transferbarang.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function process(Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;
        
        TransferBarang::create([
            'id' => $id,
            'tgl_tb' => $tanggal,
            'id_user' => Auth::user()->id
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                DetilTB::create([
                    'id_tb' => $request->id,
                    'id_barang' => $request->kodeBarang[$i],
                    'id_asal' => $request->kodeAsal[$i],
                    'id_tujuan' => $request->kodeTujuan[$i],
                    'qty' => $request->qtyTransfer[$i]
                ]);

                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $request->kodeAsal[$i])->first();
                $updateStok->{'stok'} -= $request->qtyTransfer[$i];
                $updateStok->save();

                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $request->kodeTujuan[$i])->first();
                $updateStok->{'stok'} += $request->qtyTransfer[$i];
                $updateStok->save();

                // $deleteTemp = TempDetilTB::where('id_tb', $id)->where('id_barang', $request->kodeBarang[$i])->where('id_asal', $request->kodeAsal[$i])->where('id_tujuan', $request->kodeTujuan[$i])->delete();
            }
        }

        return redirect()->route('tb');
    }

    public function indexTab() {
        $items = TransferBarang::orderBy('id', 'desc')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.pembelian.transferbarang.indexTab', $data);
    }

    public function detail(Request $request, $id) {
        $items = TransferBarang::orderBy('id', 'desc')->get();
        $data = [
            'items' => $items,
            'kode' => $id
        ];

        return view('pages.pembelian.transferbarang.detail', $data);
    }
}
