<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    {{-- <link href="{{ url('backend/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ url('backend/css/main.css') }}" rel="stylesheet"> --}}
    <style>
      body {
          margin: 0;
          font-family: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
          font-size: 1rem;
          font-weight: 400;
          line-height: 1.5;
          color: #858796;
          text-align: left;
          background-color: #fff;
      }

      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
          margin-top: 0;
          margin-bottom: 0.5rem;
      }

      h1,
      h2,
      h3,
      h4,
      h5,
      h6,
      .h1,
      .h2,
      .h3,
      .h4,
      .h5,
      .h6 {
          margin-bottom: 0.5rem;
          font-weight: 400;
          line-height: 1.2;
      }

      h5,
      .h5 {
          font-size: 1.25rem;
      }

      .container-fluid {
          width: 100%;
          padding-right: 0.75rem;
          padding-left: 0.75rem;
          margin-right: auto;
          margin-left: auto;
      }

      .text-center {
          text-align: center !important;
      }

      .text-bold {
        font-weight: bold
      }

      .text-dark {
          color: #5a5c69 !important;
      }
      
      .text-right {
          text-align: right !important;
      }

      .text-wrap {
          white-space: normal !important;
      }

      .float-right {
          float: right !important;
      }

      .align-middle {
          vertical-align: middle !important;
      }

      table {
          border-collapse: collapse;
      }

      .table {
          width: 100%;
          margin-bottom: 1rem;
          color: #858796;
      }

      .table th,
      .table td {
          padding: 0.75rem;
          vertical-align: top;
          border-top: 1px solid #afbbc5;
      }

      .table thead th {
          vertical-align: bottom;
          border-bottom: 1px solid #afbbc5;
      }

      .table tbody+tbody {
          border-top: 1px solid #afbbc5;
      }

      .table-sm th,
      .table-sm td {
          padding: 0.3rem;
      }

      .table-bordered {
          border: 1px solid #afbbc5;
      }

      .table-bordered th,
      .table-bordered td {
          border: 1px solid #afbbc5;
      }

      .table-bordered thead th,
      .table-bordered thead td {
          border-bottom-width: 2px;
      }

      .table-striped tbody tr:nth-of-type(odd) {
          background-color: rgba(0, 0, 0, 0.05);
      }

      .table-hover tbody tr:hover {
          color: #858796;
          background-color: rgba(59, 57, 57, 0.075);
      }

      .kode-cetak-stok {
          margin-top: -8px;
      }

      .waktu-cetak {
          font-size: 12px;
          margin-top: -8px;
      }

      .table-cetak {
          font-size: 11px;
          height: 200px;
          margin-left: 31px;
          margin-right: 31px;
          margin-top: -13px;
      }
    </style>
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
    <table class="table table-sm table-bordered table-striped table-responsive-sm table-cetak" style="border: none">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td style="width: 30px" class="align-middle">No</td>
          {{-- <td style="width: 90px" class="align-middle">Kode Barang</td> --}}
          <td style="width: 130px" class="align-middle">Nama Barang</td>
          <td style="width: 40px" class="align-middle total-stok">Total Stok</td>
          @foreach($gudang as $g)
            <td style="width: 40px" class="align-middle">{{ $g->nama }}</td>
          @endforeach
        </tr>
      </thead>
      <tbody>
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
                <td align="right" style="background-color: yellow; width: 40px">{{ $s->total }}</td>
                @php
                  $stokGd = \App\Models\StokBarang::where('id_barang', $s->id_barang)->get();
                @endphp
                @foreach($stokGd as $sg)
                  <td align="right" style="width: 40px">{{ $sg->stok }}</td>
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