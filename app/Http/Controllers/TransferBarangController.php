<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gudang;
use App\Models\Barang;
use App\Models\StokBarang;
use App\Models\TransferBarang;
use App\Models\DetilTB;
use App\Models\NeedApproval;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Input;

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
        ];

        return view('pages.pembelian.transferbarang.index', $data);
    }

    /* public function cekStok(Request $request) {
        $gudang = Gudang::where('nama', $request->name)->get();

        $item = StokBarang::where('id_barang', $request->barang)->where('id_gudang', $gudang->first()->id)
                ->where('status', $request->status)->get();
        $data = [
            'stok' => $item->first()->stok,
            'kode' => $item->first()->id_gudang
        ];

        return response()->json($data);
    } */

    public function cekStok(Request $request) {
        $jumlah = $request->jumBrs;
        $cek = $jumlah; $qtyAsal = [];
        for($i = 0; $i < $jumlah; $i++) {
            if($request->kodeBarang[$i] != "") {
                $stokAsal = StokBarang::where('id_barang', $request->kodeBarang[$i])
                        ->where('id_gudang', $request->kodeAsal[$i])
                        ->where('status', $request->statusAsal[$i])->get();
                array_push($qtyAsal, $stokAsal->first()->stok);
                
                if($request->qtyTransfer[$i] > $stokAsal->first()->stok) {
                    $cek = 1;
                }
            }
        }

        $data = [
            'cek' => $cek,
            'qtyAsal' => $qtyAsal
        ];

        return response()->json($data);
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

        // return redirect()->back()->withInput($request->all());

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
            }
        }

        if($status != 'CETAK')
            $cetak = 'false';
        else {
            $cetak = 'true';
        }

        if($status != 'CETAK')
            return redirect()->route('tb', 'false');
        else
            return redirect()->route('tb-cetak', $newcode);
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
            'waktu' => $waktu,
            'id' => $id
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
        $item = TransferBarang::where('id', $id)->get();
        $items = TransferBarang::whereBetween('tgl_tb', [Carbon::parse($item->first()->tgl_tb)->subDays(3), 
                Carbon::parse($item->first()->tgl_tb)->addDays(3)])->get();

        $data = [
            'items' => $items,
            'kode' => $id
        ];

        return view('pages.pembelian.transferbarang.detail', $data);
    }
    
    public function status(Request $request, $id) {
        $lastcode = NeedApproval::max('id');
        $lastnumber = (int) substr($lastcode, 3, 4);
        $lastnumber++;
        $newcode = 'APP'.sprintf('%04s', $lastnumber);

        NeedApproval::create([
            'id' => $newcode,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'PENDING_BATAL',
            'keterangan' => $request->input("ket".$id),
            'id_dokumen' => $id,
            'tipe' => 'Transfer',
            'id_user' => Auth::user()->id
        ]);

        $detil = DetilTB::where('id_tb', $id)->get();
        
        foreach($detil as $item) {
            $updateStokKurang = StokBarang::where('id_barang', $item->id_barang)
                    ->where('id_gudang', $item->id_tujuan)->first();

            $updateStokKurang->{'stok'} -= $item->qty;
            $updateStokKurang->save();

            $updateStokTambah = StokBarang::where('id_barang', $item->id_barang)
                    ->where('id_gudang', $item->id_asal)->first();

            $updateStokTambah->{'stok'} += $item->qty;
            $updateStokTambah->save();
        }

        return redirect()->route('tb-index');
    }
}
