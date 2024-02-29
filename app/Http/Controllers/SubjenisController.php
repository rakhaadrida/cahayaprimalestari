<?php

namespace App\Http\Controllers;

use App\Exports\SubjenisExport;
use App\Models\Barang;
use App\Models\JenisBarang;
use App\Models\Subjenis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SubjenisController extends Controller
{
    public function index()
    {
        $items = Subjenis::query()
            ->select('subjenis.*', 'jenisbarang.nama AS namaJenis')
            ->join('jenisbarang', 'jenisbarang.id', 'subjenis.id_kategori')
            ->get();

        $data = [
            'items' => $items
        ];

        return view('pages.subjenis.index', $data);
    }

    public function create()
    {
        $lastcode = Subjenis::withTrashed()->whereRaw('CHAR_LENGTH(id) > 5')->max('id');
        $lastnumber = (int) substr($lastcode, 3, 3);
        $lastnumber++;
        $newcode = 'SUB'.sprintf("%03s", $lastnumber);
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
        $item = Subjenis::query()
            ->select('subjenis.*', 'jenisbarang.nama AS namaJenis')
            ->join('jenisbarang', 'jenisbarang.id', 'subjenis.id_kategori')
            ->findOrFail($id);
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
        $items = Subjenis::query()
            ->select('subjenis.*', 'jenisbarang.nama AS namaJenis')
            ->join('jenisbarang', 'jenisbarang.id', 'subjenis.id_kategori')
            ->onlyTrashed()
            ->get();

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
