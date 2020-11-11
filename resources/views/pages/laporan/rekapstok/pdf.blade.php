<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet">
  </head>
  <body>
    <center>
      <h5 class="text-bold text-dark">Rekap Stok Barang</h5>
      <h6 class="text-dark kode-cetak-stok">
        Dari Kode {{$stok[0]->id_barang}} s/d {{ $stok[$stok->count() - 1]->id_barang}}
      </h6>
      <p class="waktu-cetak">Waktu Cetak : {{$waktu}}</p>
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td style="width: 30px" class="align-middle">No</td>
          {{-- <td style="width: 70px" class="align-middle">Kode Barang</td> --}}
          <td class="align-middle" class="align-middle">Nama Barang</td>
          <td style="width: 70px; background-color: yellow" class="align-middle">Total Stok</td>
          @foreach($gudang as $g)
            <td style="width: 70px" class="align-middle">{{ $g->nama }}</td>
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