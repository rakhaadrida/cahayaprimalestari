<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GudangRequest;
use App\Models\Gudang;
use App\Models\StokBarang;
use App\Models\SalesOrder;
use App\Models\AccReceivable;
use App\Models\Customer;
use App\Models\Sales;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GudangExport;
use Carbon\Carbon;

class GudangController extends Controller
{
    public function index()
    {   
        $items = Gudang::All();
        $data = [
            'items' => $items
        ];

        $newSales = ['Budi-02', 'Ega-02', 'Hendra-02', 'Yardi-02'];
        $newId = [];
        for($i = 0; $i < sizeof($newSales); $i++) {
            $lastcode = Sales::selectRaw('max(id) as id')->get();
            $lastnumber = (int) substr($lastcode[0]->id, 3, 2);
            $lastnumber++;
            $newcode = 'SLS'.sprintf('%02s', $lastnumber);
            $newId[$i] = $newcode;

            Sales::create([
                'id' => $newcode,
                'nama' => $newSales[$i]
            ]);
        }

        $budi = 'ABADI COOL,AGUNG JAYA 1,AGUNG LISTRIK,ANEKA ELEKTRONIK (BSD),BAHAN BANGUNGAN JAYARAYA,BARU JAYA 99 SERANG,BINTANG TERANG (L.A),TB. BOJONG BANGUNAN,DARWIN ELEKTRIK,TOKO DUA SAUDARA,ELECTRO,FIKA JAYA,INDAH ELEKTRIK,MATAHARI,MEGAH JAYA SERANG,PT. MENTARI TIMUR SENTOSA,MURA JAYA,NS TRADING,SAMUDERA CIBUBUR,SENTRAL LISTRIK (CILINCING),SETIA BUDI,SINAR PRATAMA,SINAR SAKTI,SIS ELEKTRIK,SUMBER CAHAYA LESTARI,SUPER BANGUNAN KARAWANG,SURYA AGUNG ELEKTRIK,SURYA JAYA MAS,USAHA E,WILIAM JAYA CIBUBUR';
        $arrBudi = explode(",", $budi);

        $items = Customer::whereIn('nama', $arrBudi)->get();
        foreach($items as $i) {
            $i->id_sales = $newId[0];
            $i->save();
        }

        $ega = 'TOKO BARU,JAYA ABADI,RIZKY ELECTRIK';
        $arrEga = explode(",", $ega);

        $items = Customer::whereIn('nama', $arrEga)->get();
        foreach($items as $i) {
            $i->id_sales = $newId[1];
            $i->save();
        }

        $hendra = 'ARTHA JAYA SUKSES,TOKO BAHAGIA TEKNIK,CAHYO ELECTRIC,GUDANG ELEKTRIK,TOKO HARAPAN BARU,JAYA AGUNG,JUANDA ELEKTRONIK,LOTUS,MITRA ELEKTRONIK,NASA ELEKTRIK';
        $arrHendra = explode(",", $hendra);

        $items = Customer::whereIn('nama', $arrHendra)->get();
        foreach($items as $i) {
            $i->id_sales = $newId[2];
            $i->save();
        }

        $yardi = 'AKSA REMPOA,AKUR JAYA,ARHENT ELETRIC,ARIES ELEKTRIK,TOKO ARINDA PONDOK AREN,BAROKAH ELEKTRIK,TB CAHAYA BELAWA,BENUA BARU,BENUA BARU,BERKAH (PASAR SANTA),BERKAH JAYA,BINTANG PRIMA ELEKTRIK,CAHAYA,CAHAYA ABADI (CIPAYUNG),TOKO CAHAYA ABADI CAMAN,TOKO CAHAYA BINTANG,CAHAYA ELEKTRIK (PONDOK PETIR),TOKO CAHAYA GINTUNG,CAHAYA RESTU CIKARET,CENTRAL ELEKTRONIK,TK. CHAMPION ELECTRIK,CHANDRA JAYA,CHASA JAYA ELEKTRIK,TOKO LISTRIK CIREMAI,DANIEL ELEKTRIK 2,DELTA ELEKTRIK,DIVI JAYA ELEKTRIK,TOKO DMS ONE,DMS ELEKTRIK,ERLANGGA / ALFA BAHAR,FADJAR LISTRIK,TOKO FAJAR BARU ELEKTRIK,TB FAJAR INDAH ABADI,FAMILY ELEKTRIK,GEMINI ELECTRICAL,GRIYA BARU,HARAPAN MURNI CAKUNG,TOKO INDAH MULIA,INDO CAHAYA PARUNG,INTI JAYA ELEKTRIK,JAFAR,JAYA AUDIO TEKNIK,TB JAYA LESTARI,JAYA MAKMUR MERUYA,TB KARYA ABADI KALIBATA,KEVINDO,MAHKOTA CEMERLANG,MELINDA,MILENIUM ELEKTRIK,MITRA SANJAYA,MULIA JAYA (CINERE),MULTI JAYA ELEKTRIK,MULTI REZEKI TEKNIK,NASIONAL UTAMA,OMEGA ELECTRIK,PANJI ELEKTRIK,PESONA ELEKTRONIK CIPUTAT,PRIMA ELEKTRIK,PRIMA JAYA,TB PUTRA JAYA CINANGKA,PUTRA TANJUNG ELEKTRIK,RAJA GIANT LISTRIK,RAM RAF ELEKTRIK,TOKO SABAR (PS. SANTA ),SAHABAT ELEKTRIK SERUA,SAHABAT ELEKTRIK CIRACAS,SEDERHANA,SINAR ABADI BOJONGSARI,SINAR AGUNG PAMULANG,SINAR ALAM,SINAR COMET 3,SINAR ELEKTRIK (MAMPANG),SINAR JAYA,TOKO SINAR JAYA (SANTA),SINAR JAYA ( CIKOKO),SINAR KLATEN MULIA,SINAR KOMODO,SINAR LESTARI GANDUL,SINAR LESTARI PAMULANG 2,SINAR RAYA JOMBANG,SINAR SAKTI ELEKTRIK,SINAR SURYA,SINAR TERANG (SERPONG),SINAR WAHANA AMPERA,STAR ELEKTRIK,SUARA BARU,TOKO SUKA HATI,SUMBER ALAM 5,SUMBER CAHAYA NUSANTARA,SUMBER MULIA SUKSES JOMBANG,TARUNA JAYA,TAWANG ELECTRIK,TOKO BANGUNAN 99,TB MUTIARA,TEGAR JAYA SAWANGAN,TEGUH ELEKTRIK,TERANG DUNIA DEPOK,TERANG INDAH,TERANG MAKMUR CILANDAK,TETAP JAYA ELEKTRIK,URIP JAYA,TOKO UU TEKNIK,VIOLEN JAYA ELECTRONIC,WAHANA KALIMULYA DEPOK,WENDY ELEKTRIK,WIJAYA CIBUBUR,YAYE ELEKTRIK';
        $arrYardi = explode(",", $yardi);

        $items = Customer::whereIn('nama', $arrYardi)->get();
        foreach($items as $i) {
            $i->id_sales = $newId[3];
            $i->save();
        }

        $items = Customer::whereIn('id', ['CUS0636', 'CUS0970'])->get();
        foreach($items as $i) {
            $i->id_sales = $newId[3];
            $i->save();
        }

        $items = SalesOrder::whereIn('id', ['IN21000256P', 'IN21000228P'])->get();
        foreach($items as $i) {
            $i->kategori = 'Prime T';
            $i->tempo = '30';
            $i->save();
        }

        $item = SalesOrder::where('id', 'IV21002723')->first();
        $item->{'total'} = 6494066;
        $item->save();

        $items = AccReceivable::whereIn('id_so', ['IV21002323', 'IV21002197', 'IV21002157', 'IV21002059', 'IV21002034', 
                'IV21002031', 'IV21002029', 'IV21002023', 'IV21001994', 'IV21002307', 'IV21002326', 'IV21002259',
                'IV21002503', 'IV21002249', 'IV21002049', 'IV21002139', 'IV21002605', 'IN21000092P'])->get();
        foreach($items as $i) {
            $i->keterangan = 'LUNAS';
            $i->save();
        }

        return view('pages.gudang.index', $data);
    }

    public function create()
    {
        $lastcode = Gudang::withTrashed()->max('id');
        $lastnumber = (int) substr($lastcode, 3, 2);
        $lastnumber++;
        $newcode = 'GDG'.sprintf("%02s", $lastnumber);

        $data = [
            'newcode' => $newcode
        ];
        
        return view('pages.gudang.create', $data);
    }

    public function store(GudangRequest $request)
    {
        if($request->retur == '')
            $request->retur = 'F';

        Gudang::create([
            'id' => $request->kode,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'tipe' => $request->tipe
        ]);

        return redirect()->route('gudang.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = Gudang::findOrFail($id);
        $data = [
            'item' => $item
        ];

        return view('pages.gudang.edit', $data);
    }

    public function update(GudangRequest $request, $id)
    {
        $data = $request->all();
        $item = Gudang::findOrFail($id);
        $item->update($data);

        return redirect()->route('gudang.index');
    }

    public function destroy($id)
    {
        $item = Gudang::findOrFail($id);
        $item->delete();

        $item = StokBarang::where('id_gudang', $id);
        $item->delete();

        return redirect()->route('gudang.index');
    }

    public function trash() {
        $items = Gudang::onlyTrashed()->get();
        $data = [
            'items' => $items
        ];

        return view('pages.gudang.trash', $data);
    }

    public function restore($id) {
        $item = Gudang::onlyTrashed()->where('id', $id);
        $item->restore();

        $item = StokBarang::onlyTrashed()->where('id_gudang', $id);
        $item->restore();

        return redirect()->back();
    }

    public function restoreAll() {
        $items = Gudang::onlyTrashed();
        $items->restore();

        $item = StokBarang::onlyTrashed();
        $item->restore();

        return redirect()->back();
    }

    public function hapus($id) {
        $item = Gudang::onlyTrashed()->where('id', $id);
        $item->forceDelete();

        $item = StokBarang::onlyTrashed()->where('id_gudang', $id);
        $item->forceDelete();

        return redirect()->back();
    }

    public function hapusAll() {
        $items = Gudang::onlyTrashed();
        $items->forceDelete();

        $item = StokBarang::onlyTrashed();
        $item->forceDelete();

        return redirect()->back();
    }

    public function excel() {
        $tanggal = Carbon::now()->toDateString();
        $tglFile = Carbon::parse($tanggal)->format('d-M');

        return Excel::download(new GudangExport(), 'Master Gudang-'.$tglFile.'.xlsx');
    }
}
