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
    public function index($status) {
        $barang = Barang::All();
        $gudang = Gudang::All();
        $stok = StokBarang::with(['gudang'])->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = TransferBarang::selectRaw('max(id) as id')->whereMonth('tgl_tb', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'TB'.$tahun.$bulan.sprintf('%04s', $lastnumber);

        // $items = TempDetilTB::with(['barang', 'gudangAsal', 'gudangTujuan'])->where('id_tb', $newcode)->orderBy('created_at', 'asc')->get();
        // $itemsRow = TempDetilTB::where('id_tb', $newcode)->count();

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');
        $lastTB = TransferBarang::where('created_at', '!=', NULL)->latest()->take(1)->get();

        $data = [
            'barang' => $barang,
            'gudang' => $gudang,
            'stok' => $stok,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'lastTB' => $lastTB,
            'status' => $status
            // 'items' => $items,
            // 'itemsRow' => $itemsRow
        ];

        return view('pages.pembelian.transferbarang.index', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function process(Request $request, $id, $status) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        $jumlah = $request->jumBaris;

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = TransferBarang::selectRaw('max(id) as id')->whereMonth('tgl_tb', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 6, 4);
        $lastnumber++;
        $newcode = 'TB'.$tahun.$bulan.sprintf('%04s', $lastnumber);
        
        TransferBarang::create([
            'id' => $newcode,
            'tgl_tb' => $tanggal,
            'status' => $status,
            'id_user' => Auth::user()->id
        ]);

        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                DetilTB::create([
                    'id_tb' => $newcode,
                    'id_barang' => $request->kodeBarang[$i],
                    'id_asal' => $request->kodeAsal[$i],
                    'id_tujuan' => $request->kodeTujuan[$i],
                    'qty' => $request->qtyTransfer[$i]
                ]);

                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $request->kodeAsal[$i])
                                ->where('status', $request->statusAsal[$i])->first();
                $updateStok->{'stok'} -= $request->qtyTransfer[$i];
                $updateStok->save();

                $updateStok = StokBarang::where('id_barang', $request->kodeBarang[$i])
                                ->where('id_gudang', $request->kodeTujuan[$i])
                                ->where('status', $request->statusTujuan[$i])->first();
                
                if($updateStok == NULL) {
                    StokBarang::create([
                        'id_barang' => $request->kodeBarang[$i],
                        'id_gudang' => $request->kodeTujuan[$i],
                        'status' => $request->statusTujuan[$i],
                        'stok' => $request->qtyTransfer[$i]
                    ]);
                } else {
                    $updateStok->{'stok'} += $request->qtyTransfer[$i];
                    $updateStok->save();
                }
                
                // $deleteTemp = TempDetilTB::where('id_tb', $id)->where('id_barang', $request->kodeBarang[$i])->where('id_asal', $request->kodeAsal[$i])->where('id_tujuan', $request->kodeTujuan[$i])->delete();
            }
        }

        if($status != 'CETAK')
            $cetak = 'false';
        else {
            $cetak = 'true';
        }

        return redirect()->route('tb', $cetak);
    }

    public function cetak(Request $request, $id) {
        $items = TransferBarang::where('id', $id)->get();
        $tabel = ceil($items->first()->detiltb->count() / 12);

        if($tabel > 1) {
            for($i = 1; $i < $tabel; $i++) {
                $item = collect([
                    'id' => $items->first()->id,
                    'tgl_tb' => $items->first()->tgl_tb,
                    'status' => $items->first()->status,
                    'id_user' => $items->first()->id_user,
                ]);

                $items->push($item);
            }
        }

        $items = $items->values();

        $today = Carbon::now()->isoFormat('dddd, D MMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.pembelian.transferbarang.cetakInv', $data);
    }

    public function afterPrint($id) {
        $item = TransferBarang::where('id', $id)->first();
        $item->{'status'} = 'CETAK';
        $item->save();

        $data = [
            'status' => 'false'
        ];

        return redirect()->route('tb', $data);
    }

    public function indexTab() {
        $items = TransferBarang::orderBy('id', 'desc')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.pembelian.transferbarang.indexTab', $data);
    }

    public function detail(Request $request, $id) {
        $items = TransferBarang::All();
        $data = [
            'items' => $items,
            'kode' => $id
        ];

        return view('pages.pembelian.transferbarang.detail', $data);
    }    
}
