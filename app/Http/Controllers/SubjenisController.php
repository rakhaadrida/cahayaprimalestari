<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subjenis;
use App\Models\JenisBarang;
use App\Models\Barang;
use App\Models\DetilBM;
use App\Models\DetilSO;
use App\Models\StokBarang;
use App\Models\AccPayable;
use App\Models\BarangMasuk;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SubjenisExport;
use Carbon\Carbon;

class SubjenisController extends Controller
{
    public function index()
    {
        $items = Subjenis::All();
        $data = [
            'items' => $items
        ];

        /* $barang = Barang::All();
        $kode = 1;
        foreach($barang as $b) {
            $totBM = DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                    ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                    ->where('status', '!=', 'BATAL')->get();
            $totSO = DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])->get();
            $stok = StokBarang::selectRaw('sum(stok) as stok')->where('id_barang', $b->id)
                    ->where('id_gudang', '!=', 'GDG05')->get();
            $id = 'BA2012'.sprintf('%04s', $kode);
            $ap = 'AP2012'.sprintf('%04s', $kode);

            if($totBM->first()->qty < $totSO->first()->qty) {
                $item = DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                        ->select('id_bm as id', 'detilbm.*')->where('status', '!=', 'BATAL')
                        ->where('id_barang', $b->id)->orderBy('tanggal')->get();
                
                if($item->count() != 0) {
                    BarangMasuk::create([
                        'id' => $id,
                        'id_faktur' => $id,
                        'tanggal' => '2020-12-31',
                        'total' => 0,
                        'potongan' => 0,
                        'id_gudang' => 'GDG01',
                        'id_supplier' => 'SUP08',
                        'tempo' => 0,
                        'status' => 'CETAK',
                        'diskon' => 'T',
                        'id_user' => '1'
                    ]);

                    DetilBM::create([
                        'id_bm' => $id,
                        'id_barang' => $b->id,
                        'harga' => $item->first()->harga,
                        'qty' => $totSO->first()->qty - $totBM->first()->qty + $stok->first()->stok,
                        'diskon' => $item->first()->diskon,
                        'disPersen' => $item->first()->disPersen
                    ]);

                    AccPayable::create([
                        'id' => $ap,
                        'id_bm' => $id,
                        'keterangan' => 'LUNAS'
                    ]);

                    $kode++;
                }
            } else {
                if(($totBM->first()->qty - $totSO->first()->qty) < $stok->first()->stok) {
                    $item = DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                            ->select('id_bm as id', 'detilbm.*')->where('status', '!=', 'BATAL')
                            ->where('id_barang', $b->id)->orderBy('tanggal')->get();
                    
                    if($item->count() != 0) {
                        BarangMasuk::create([
                            'id' => $id,
                            'id_faktur' => $id,
                            'tanggal' => '2020-12-31',
                            'total' => 0,
                            'potongan' => 0,
                            'id_gudang' => 'GDG01',
                            'id_supplier' => 'SUP08',
                            'tempo' => 0,
                            'status' => 'CETAK',
                            'diskon' => 'T',
                            'id_user' => '1'
                        ]);

                        DetilBM::create([
                            'id_bm' => $id,
                            'id_barang' => $b->id,
                            'harga' => $item->first()->harga,
                            'qty' => $stok->first()->stok - ($totBM->first()->qty - $totSO->first()->qty),
                            'diskon' => $item->first()->diskon,
                            'disPersen' => $item->first()->disPersen
                        ]);

                        AccPayable::create([
                            'id' => $ap,
                            'id_bm' => $id,
                            'keterangan' => 'LUNAS'
                        ]);

                        $kode++;
                    }
                }
            }
        } */

        return view('pages.subjenis.index', $data);
    }

    public function create()
    {
        $lastcode = Subjenis::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'SUB'.sprintf("%02s", $lastnumber);
        $jenis = JenisBarang::All();

        $data = [
            'newcode' => $newcode,
            'jenis' => $jenis
        ];
        
        return view('pages.subjenis.create', $data);
    }

    public function store(Request $request)
    {
        Subjenis::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'id_kategori' => $request->kodeJenis,
            'limit' => $request->limit
        ]);

        return redirect()->route('subjenis.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Subjenis::findOrFail($id);
        $jenis = JenisBarang::All();

        $data = [
            'item' => $item,
            'jenis' => $jenis
        ];

        return view('pages.subjenis.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $item = Subjenis::where('id', $id)->first();
        $item->{'nama'} = $request->nama;
        $item->{'id_kategori'} = $request->kodeJenis;
        $item->{'limit'} = $request->limit;
        $item->save();

        return redirect()->route('subjenis.index');
    }

    public function destroy($id)
    {
        $item = Subjenis::findOrFail($id);
        $item->delete();

        $item = Barang::where('id_sub', $id)->get();
        foreach($item as $i) {
            $i->id_sub = '';
            $i->save();
        }

        return redirect()->route('subjenis.index');
    }

    public function trash() {
        $items = Subjenis::onlyTrashed()->get();

        $data = [
            'items' => $items,
        ];

        return view('pages.subjenis.trash', $data);
    }

    public function restore($id) {
        $item = Subjenis::onlyTrashed()->where('id', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Subjenis::onlyTrashed();
        $items->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Subjenis::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Subjenis::onlyTrashed();
        $items->forceDelete();

        return redirect()->back();
    }

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new SubjenisExport(), 'Master Subjenis-'.$tglFile.'.xlsx');
    }
}
