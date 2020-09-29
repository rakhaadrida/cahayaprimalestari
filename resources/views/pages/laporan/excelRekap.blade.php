<html>
  <body>
    <center>
      <h3 class="text-bold text-dark">Rekap Stok Barang</h3>
      <h4 class="text-dark">
        Dari Kode {{$stok[0]->id_barang}} s/d {{ $stok[$stok->count() - 1]->id_barang}}
      </h4>
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover table-cetak">
      <thead class="text-center text-dark text-bold">
        <tr>
          <td>No</td>
          <td>Kode Barang</td>
          <td >Nama Barang</td>
          <td>Total Stok</td>
          @foreach($gudang as $g)
            <td>{{ $g->nama }}</td>
          @endforeach
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($stok as $s)
          <tr class="text-dark">
            <td align="center">{{ $i }}</td>
            <td>{{ $s->id_barang }}</td>
            <td>{{ $s->barang->nama }}</td>
            <td align="right" style="background-color: yellow">{{ $s->total }}</td>
            @php
              $stokGd = \App\StokBarang::where('id_barang', $s->id_barang)->get();
            @endphp
            @foreach($stokGd as $sg)
              <td align="right">{{ $sg->stok }}</td>
            @endforeach
          </tr>
          @php $i++ @endphp
        @endforeach
      </tbody>
    </table>
  </body>
</html>
