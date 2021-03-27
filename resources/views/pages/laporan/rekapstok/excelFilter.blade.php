<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">REKAP STOK {{$nama}}</h2>
      <h3 class="text-bold text-dark">Tanggal Rekap: {{$tglRekap}}</h3>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td>No</td>
          <td>Nama Barang</td>
          <td>Total Stok</td>
          @foreach($gudang as $g)
            <td>{{ $g->nama }}</td>
          @endforeach
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($sub as $s)
          @php
            $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
          @endphp 
          <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
            <td colspan="{{ $gudang->count() + 3 }}" align="center">{{ $s->nama }}</td>
          </tr>
          @foreach($barang as $b)
            @php
              $stok = \App\Models\StokBarang::with(['barang'])->select('id_barang', 
                        DB::raw('sum(stok) as total'))->where('id_barang', $b->id)
                        ->groupBy('id_barang')->get();
              $tambah = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                        ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                        ->whereBetween('tgl_so', [$awal, $kemarin])->get();
              $kurang = \App\Models\DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                        ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                        ->whereBetween('tanggal', [$awal, $kemarin])->get();
              $kurangRet = \App\Models\DetilRJ::join('returjual', 'returjual.id', 'detilrj.id_retur')
                          ->selectRaw('sum(qty_retur - qty_kirim - potong) as qty')
                          ->where('status', 'INPUT')->where('id_barang', $b->id)
                          ->whereBetween('tanggal', [$awal, $kemarin])->get();
              $detiltr = \App\Models\DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                          ->join('returbeli', 'returbeli.id', 'returterima.id_retur')
                          ->selectRaw('sum(qty_terima + qty_batal + potong) as qty')
                          ->where('status', 'INPUT')->where('id_barang', $b->id)
                          ->whereBetween('returbeli.tanggal', [$awal, $kemarin])
                          ->get();
              $tambahRet = \App\Models\DetilRB::join('returbeli', 'returbeli.id', 'detilrb.id_retur')
                          ->selectRaw('sum(qty_retur) as retur')
                          ->where('status', 'INPUT')->where('id_barang', $b->id)
                          ->whereBetween('tanggal', [$awal, $kemarin])
                          ->get();
              if($tambahRet->count() != 0)
                $tambahRet[0]['qty'] = $tambahRet->first()->retur - $detiltr->first()->qty;
            @endphp
            <tr class="text-dark ">
              <td align="center">{{ $i }}</td>
              <td>{{ $b->nama }}</td>
               <td align="right">{{ $stok->count() != 0 ? $stok[0]->total + $tambah->first()->qty + $tambahRet->first()->qty - $kurang->first()->qty - $kurangRet->first()->qty : ''}}</td>
              @foreach($gudang as $g)
                @php
                  if($g->tipe != 'RETUR') {
                    $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                              ->where('id_gudang', $g->id)->get();
                    $tambahGd = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                                ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                                ->where('id_gudang', $g->id)->whereBetween('tgl_so', [$awal, $kemarin])
                                ->get();
                    $kurangGd = \App\Models\DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                                ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                                ->where('id_gudang', $g->id)->whereBetween('tanggal', [$awal, $kemarin])
                                ->get();
                    $tambahTb = \App\Models\DetilTB::join('transferbarang', 'transferbarang.id', 
                                'detiltb.id_tb')
                                ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                                ->where('id_asal', $g->id)->whereBetween('tgl_tb', 
                                [$awal, $kemarin])->get();
                    $kurangTb = \App\Models\DetilTB::join('transferbarang', 'transferbarang.id', 
                                'detiltb.id_tb')
                                ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                                ->where('id_tujuan', $g->id)->whereBetween('tgl_tb', 
                                [$awal, $kemarin])->get();
                  } else {
                    $stokGd = \App\Models\StokBarang::selectRaw('sum(stok) as
                              stok')->where('id_barang', $b->id)
                              ->where('id_gudang', $g->id)->get();
                    $detilRT = \App\Models\DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                              ->join('returbeli', 'returbeli.id', 'returterima.id_retur')
                              ->selectRaw('sum(qty_terima + qty_batal + potong) as qty')
                              ->where('status', 'INPUT')->where('id_barang', $b->id)
                              ->whereBetween('returbeli.tanggal', [$awal, $kemarin])
                              ->get();
                    $tambahGd = \App\Models\DetilRB::join('returbeli', 'returbeli.id', 'detilrb.id_retur')
                                ->selectRaw('sum(qty_retur) as retur')
                                ->where('status', 'INPUT')->where('id_barang', $b->id)
                                ->whereBetween('tanggal', [$awal, $kemarin])
                                ->get();
                    if($tambahGd->count() != 0)
                      $tambahGd[0]['qty'] = $tambahGd->first()->retur - $detilRT->first()->qty; 

                    $kurangGd = \App\Models\DetilRJ::join('returjual', 'returjual.id', 'detilrj.id_retur')
                                ->selectRaw('sum(qty_retur - qty_kirim - potong) as qty')
                                ->where('status', 'INPUT')->where('id_barang', $b->id)
                                ->whereBetween('tanggal', [$awal, $kemarin])->get();
                    $tambahTb = \App\Models\DetilTB::join('transferbarang', 'transferbarang.id', 
                                'detiltb.id_tb')
                                ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                                ->where('id_asal', $g->id)->whereBetween('tgl_tb', 
                                [$awal, $kemarin])->get();
                    $kurangTb = \App\Models\DetilTB::join('transferbarang', 'transferbarang.id', 
                                'detiltb.id_tb')
                                ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                                ->where('id_tujuan', $g->id)->whereBetween('tgl_tb', 
                                [$awal, $kemarin])->get();
                  }
                @endphp
                <td align="right">{{ (($stokGd->count() != 0) && (($tambahGd->count() != 0) || ($kurangGd->count() != 0) || ($tambahTb->count() != 0) || ($kurangTb->count() != 0))) ? (($stokGd[0]->stok + $tambahGd->first()->qty + $tambahTb->first()->qty - $kurangGd->first()->qty - $kurangTb->first()->qty) != 0 ? $stokGd[0]->stok + $tambahGd->first()->qty + $tambahTb->first()->qty - $kurangGd->first()->qty - $kurangTb->first()->qty : '') : '' }}</td>
              @endforeach
            </tr>
            @php $i++ @endphp
          @endforeach
        @endforeach
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
