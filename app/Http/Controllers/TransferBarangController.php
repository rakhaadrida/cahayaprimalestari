<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gudang;
use App\Barang;
use App\StokBarang;
use App\TransferBarang;
use App\DetilTB;
use App\TempDetilTB;
use Carbon\Carbon;

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

        $items = TempDetilTB::with(['barang', 'gudangAsal', 'gudangTujuan'])->where('id_tb', $newcode)->orderBy('created_at', 'asc')->get();
        $itemsRow = TempDetilTB::where('id_tb', $newcode)->count();

        $tanggal = Carbon::now()->toDateString();
        $tanggal = $this->formatTanggal($tanggal, 'd-m-Y');

        $data = [
            'barang' => $barang,
            'gudang' => $gudang,
            'stok' => $stok,
            'newcode' => $newcode,
            'tanggal' => $tanggal,
            'items' => $items,
            'itemsRow' => $itemsRow
        ];

        return view('pages.pembelian.transferBarang', $data);
    }

    public function formatTanggal($tanggal, $format) {
        $formatTanggal = Carbon::parse($tanggal)->format($format);
        return $formatTanggal;
    }

    public function create(Request $request, $id) {
        TempDetilTB::create([
            'id_tb' => $id,
            'id_barang' => $request->kodeBarang,
            'id_asal' => $request->kodeAsal,
            'id_tujuan' => $request->kodeTujuan,
            'stok_asal' => $request->qtyAsal,
            'stok_tujuan' => $request->qtyTujuan,
            'qty' => $request->qty
        ]);

        return redirect()->route('tb');
    }

    public function process(Request $request, $id) {
        $tanggal = $request->tanggal;
        $tanggal = $this->formatTanggal($tanggal, 'Y-m-d');
        
        TransferBarang::create([
            'id' => $id,
            'tgl_tb' => $tanggal
        ]);

        $tempDetil = TempDetilTB::where('id_tb', $id)->get();
        foreach($tempDetil as $td) {
            DetilTB::create([
                'id_tb' => $td->id_tb,
                'id_barang' => $td->id_barang,
                'id_asal' => $td->id_asal,
                'id_tujuan' => $td->id_tujuan,
                'qty' => $td->qty
            ]);

            $updateStok = StokBarang::where('id_barang', $td->id_barang)
                            ->where('id_gudang', $td->id_asal)->first();
            $updateStok->{'stok'} -= $td->qty;
            $updateStok->save();

            $updateStok = StokBarang::where('id_barang', $td->id_barang)
                            ->where('id_gudang', $td->id_tujuan)->first();
            $updateStok->{'stok'} += $td->qty;
            $updateStok->save();

            $deleteTemp = TempDetilTB::where('id_tb', $id)->where('id_barang', $td->id_barang)->where('id_asal', $td->id_asal)->where('id_tujuan', $td->id_tujuan)->delete();
        }

        return redirect()->route('tb');
    }

    public function remove($id, $barang, $asal, $tujuan) {
        $tempDetil = TempDetilTB::where('id_tb', $id)->where('id_barang', $barang)->where('id_asal', $asal)->where('id_tujuan', $tujuan)->delete();

        return redirect()->route('tb');
    }
}
