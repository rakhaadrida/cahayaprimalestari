<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TandaTerima;
use App\Models\SalesOrder;
use App\Models\DetilSO;
use Carbon\Carbon;
use PDF;

class TandaTerimaController extends Controller
{
    public function index() {
        // $so = DetilSO::join('so', 'so.id', 'detilso.id_so')
        //     ->select('id_so', 'tgl_so', 'id_barang', 'id_gudang', 'qty', 'detilso.diskon as diskon', 'diskonRp')
        //     ->whereNotIn('id_gudang', ['GDG01', 'GDG06'])
        //     ->where('tgl_so', '>', '2021-03-02')->whereNotIn('status', ['BATAL', 'LIMIT'])
        //     ->orderBy('tgl_so', 'desc')->get();

        // return response()->json($so);

        // foreach($so as $s) {
        //     $item = DetilSO::where('id_so', $s->id_so)->where('id_barang', $s->id_barang)
        //             ->where('id_gudang', $s->id_gudang)->first();
        //     $item->{'diskonRp'} = 0;
        //     $item->save();
        // }

        $items = TandaTerima::groupBy('id')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.penjualan.tandaterima.index', $data);
    }

    public function show(Request $request) {
        $tglAwal = Carbon::parse($request->tglAwal)->format('Y-m-d');
        $tglAkhir = Carbon::parse($request->tglAkhir)->format('Y-m-d');

        $items = TandaTerima::whereBetween('tanggal', [$tglAwal, $tglAkhir])
                ->groupBy('id')->get();
        $data = [
            'items' => $items
        ];

        return view('pages.penjualan.tandaterima.index', $data);
    }

    public function detail(Request $request, $id) {
        $items = TandaTerima::groupBy('id')->get();
        $data = [
            'items' => $items,
            'kode' => $id
        ];

        return view('pages.penjualan.tandaterima.detail', $data);
    }

    public function indexCetak($status, $awal, $akhir) {
        $ttr = TandaTerima::select('id_so')->get()->pluck('id_so')->toArray();
        // var_dump($ttr);
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')
                ->whereNotIn('so.id', $ttr)->where('status', 'CETAK')->get();
        // return response()->json($items);

        $data = [
            'items' => $items,
            'status' => $status,
            'awal' => $awal,
            'akhir' => $akhir
        ];

        return view('pages.penjualan.tandaterima.indexCetak', $data);
    }

    public function process(Request $request) {
        $data = [
            'awal' => $request->kodeAwal,
            'akhir' => $request->kodeAkhir,
            'status' => 'true'
        ];

        return redirect()->route('ttr-index-cetak', $data);
    }

    public function cetak($awal, $akhir) {
        $items = SalesOrder::join('users', 'users.id', 'so.id_user')
                ->select('so.id as id', 'so.*')->where('roles', '!=', 'KENARI')
                ->where('status', 'CETAK')->whereBetween('so.id', [$awal, $akhir])->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = TandaTerima::selectRaw('max(id) as id')->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 3);
        $lastnumber++;
        $newcode = 'TTR'.$tahun.$bulan.sprintf('%03s', $lastnumber);

        $today = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $waktu = Carbon::now();
        $waktu = Carbon::parse($waktu)->format('H:i:s');

        $data = [
            'items' => $items,
            'newcode' => $newcode,
            'today' => $today,
            'waktu' => $waktu
        ];

        return view('pages.penjualan.tandaterima.cetakPdf', $data);

        // $paper = array(0,0,612,394);
        // $pdf = PDF::loadview('pages.penjualan.tandaterima.cetak', $data)->setPaper($paper);
        // ob_end_clean();
        // return $pdf->stream('cetak-ttr.pdf');
    }

    public function update($awal, $akhir) {
        $items = SalesOrder::whereBetween('id', [$awal, $akhir])->get();

        $waktu = Carbon::now('+07:00');
        $bulan = $waktu->format('m');
        $month = $waktu->month;
        $tahun = substr($waktu->year, -2);

        $lastcode = TandaTerima::selectRaw('max(id) as id')->whereMonth('tanggal', $month)->get();
        $lastnumber = (int) substr($lastcode[0]->id, 7, 3);
        $lastnumber++;
        $newcode = 'TTR'.$tahun.$bulan.sprintf('%03s', $lastnumber);

        foreach($items as $item) {
            TandaTerima::create([
                'id' => $newcode,
                'id_so' => $item->id,
                'tanggal' => Carbon::now()->toDateString(),
                'id_user' => Auth::user()->id
            ]);
        }

        $data = [
            'status' => 'false',
            'awal' => 0,
            'akhir' => 0
        ];

        return redirect()->route('ttr-index-cetak', $data);
    }
}
