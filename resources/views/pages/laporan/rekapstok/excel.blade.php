<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">REKAP STOK {{$nama}}</h2>
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
            <td colspan="6" align="center">{{ $s->nama }}</td>
          </tr>
          @foreach($barang as $b)
            @php
              $stok = \App\Models\StokBarang::with(['barang'])->select('id_barang', 
                        DB::raw('sum(stok) as total'))->where('id_barang', $b->id)
                        ->groupBy('id_barang')->get();
            @endphp
            <tr class="text-dark ">
              <td align="center">{{ $i }}</td>
              <td>{{ $b->nama }}</td>
              @if($stok->count() != 0)
                <td align="right" style="background-color: yellow">{{$stok[0]->total}}</td>
              @else
                <td>0</td>
              @endif
              @foreach($gudang as $g)
                @php
                  $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                          ->where('id_gudang', $g->id)->get();
                @endphp
                @if(($stokGd->count() != 0) && ($stokGd[0]->stok != 0))
                  <td align="right">{{$stokGd[0]->stok}}</td>
                @else
                  <td></td>
                @endif
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
