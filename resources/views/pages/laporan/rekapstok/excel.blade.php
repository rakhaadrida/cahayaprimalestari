<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Rekap Stok Barang</h2>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td>No</td>
          {{-- <td>Kode Barang</td> --}}
          <td >Nama Barang</td>
          <td>Total Stok</td>
          @foreach($gudang as $g)
            <td>{{ $g->nama }}</td>
          @endforeach
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($jenis as $j)
          <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
            <td colspan="6" align="center">{{ $j->nama }}</td>
          </tr>
          @foreach($stok as $s)
            @if($s->barang->id_kategori == $j->id)
              <tr class="text-dark ">
                <td align="center">{{ $i }}</td>
                {{-- <td>{{ $s->id_barang }}</td> --}}
                <td>{{ $s->barang->nama }}</td>
                <td align="right" style="background-color: yellow">{{ $s->total }}</td>
                @php
                  $stokGd = \App\Models\StokBarang::where('id_barang', $s->id_barang)->get();
                @endphp
                @foreach($stokGd as $sg)
                  <td align="right">{{ $sg->stok }}</td>
                @endforeach
              </tr>
              @php $i++ @endphp
            @endif
          @endforeach
        @endforeach
      </tbody>
    </table>
  </body>
</html>
