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
            @endphp
            <tr class="text-dark ">
              <td align="center">{{ $i }}</td>
              <td>{{ $b->nama }}</td>
               <td align="right">{{ $stok->count() != 0 ? $stok[0]->total : ''}}</td>
              @foreach($gudang as $g)
                @php
                  if($g->tipe != 'RETUR') {
                    $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                              ->where('id_gudang', $g->id)->get();
                    $tambahGd = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                                ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                                ->where('id_gudang', $g->id)->whereBetween('tgl_so', [$awal, $kemarin])
                                ->get();
                  } else {
                    $stokGd = \App\Models\StokBarang::selectRaw('sum(stok) as
                              stok')->where('id_barang', $b->id)
                              ->where('id_gudang', $g->id)->get();
                    $tambahGd = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                              ->selectRaw('sum(qty) as qty')->where('id_barang', $b->id)
                              ->where('id_gudang', $g->id)->whereBetween('tgl_so', [$awal, $kemarin])
                              ->get();
                  }
                @endphp
                <td align="right">{{ (($stokGd->count() != 0) && ($stokGd[0]->stok != 0) && ($tambahGd->count() != 0)) ? $stokGd[0]->stok + $tambahGd->first()->qty : '' }}</td>
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
