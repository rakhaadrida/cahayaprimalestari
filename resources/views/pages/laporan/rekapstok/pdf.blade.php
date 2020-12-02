<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    {{-- <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet"> --}}
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
          color: #292a2b !important;
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

      .kode-cetak-stok {
          margin-top: -8px;
      }

      .title-rekap {
        margin-top: -30px;
      }

      .waktu-cetak {
        font-size: 12px !important;
        margin-top: -10px !important;
      }

      .table-cetak {
        left: 0%;
        width: 50%;
        font-size: 8.5px;
        margin-left: -15px;
        margin-right: 3px;
        margin-top: -28px;
        position: absolute;
        border: 0.5px solid #292a2b !important;
        border-width: thin !important;
      }

      .table-cetak th,
      .table-cetak td {
        padding-top: 0rem !important;
        padding-bottom: 0.1rem !important;
        border: 0.5px solid #292a2b !important;
        border-width: thin !important;
      }

      .table-cetak-right {
        left: 50%;
        width: 50%;
        font-size: 8.5px;
        margin-left: 3px;
        margin-right: -15px;
        margin-top: -0px;
        position: absolute;
        border: 0.5px solid #292a2b !important;
        border-width: thin !important;
      }

      .table-cetak-right th,
      .table-cetak-right td {
        padding-top: 0rem !important;
        padding-bottom: 0.1rem !important;
        border: 0.5px solid #292a2b !important;
        border-width: thin !important;
      }
    </style>
  </head>
  <body>
    @foreach($jenis as $item)
      <div class="cetak-rekap-all" @if($jenis[$jenis->count()-1]->id != $item->id) style="page-break-after: always" @endif>
        <center>
          <div class="title-rekap-all">
            <h5 class="text-bold text-dark title-rekap">REKAP STOK {{$item->nama}}</h5>
            <h6 class="text-dark waktu-cetak">Waktu Cetak : {{$waktu}}</h6>
          </div>
        </center>
        <br>
        
        <div class="row rekap-split">
          <!-- Tabel Data Detil BM-->
          <table class="table table-sm table-bordered table-cetak">
            <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
              <tr>
                <td style="width: 8px" class="align-middle">No</td>
                <td class="align-middle" class="align-middle">Nama Barang</td>
                <td style="width: 25px; background-color: yellow" class="align-middle">Total</td>
                @foreach($gudang as $g)
                  <td style="width: 20px" class="align-middle">{{ $g->nama }}</td>
                @endforeach
              </tr>
            </thead>
            <tbody id="tablePO">
              @php 
                $i = 1; $baris = 1; $kode = []; $status = 0; $kodeBrg;
                $sub = \App\Models\Subjenis::where('id_kategori', $item->id)->get();
              @endphp
              {{-- @for($j = 1; $j <= 66; $j++)
                @if($baris <= 66)
                  <tr class="text-dark ">
                    <td align="center">{{ $j }}</td>
                    <td>Phillips</td>
                    <td>300</td>
                    <td>100</td>
                    <td>100</td>
                    <td>100</td>
                  </tr>
                  @php $baris++ @endphp
                @endif
              @endfor --}}
              @foreach($sub as $s)
                @if($status != 1)
                  @php
                    $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
                  @endphp 
                  @if($baris <= 66)
                    <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                      <td colspan="6" align="center">{{ $s->nama }}</td>
                    </tr>
                    @php $baris++; array_push($kode, $s->id); @endphp
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
                          <td align="right" style="background-color: yellow">0</td>
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
                      @php $i++; $baris++; $kodeBrg = $b->id; @endphp
                    @endforeach
                  @else
                    @php $status = 1; @endphp
                  @endif
                @endif
              @endforeach
            </tbody>
          </table>
          <table class="table table-sm table-bordered table-cetak-right">
            <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
              <tr>
                <td style="width: 8px" class="align-middle">No</td>
                <td class="align-middle" class="align-middle">Nama Barang</td>
                <td style="width: 25px; background-color: yellow" class="align-middle">Total</td>
                @foreach($gudang as $g)
                  <td style="width: 20px" class="align-middle">{{ $g->nama }}</td>
                @endforeach
              </tr>
            </thead>
            <tbody id="tablePO">
              @php $j = $i; $status = 0;
                  $sub = \App\Models\Subjenis::where('id_kategori', $item->id)
                        ->whereNotIn('id', $kode)->get();
              @endphp
              @if($baris <= 132)
                @foreach($sub as $s)
                @if($status != 1)
                  @php
                    $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
                  @endphp 
                  @if($baris <= 132)
                    <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                      <td colspan="6" align="center">{{ $s->nama }}</td>
                    </tr>
                    @php $baris++; @endphp
                    @foreach($barang as $b)
                      @php
                        $stok = \App\Models\StokBarang::with(['barang'])->select('id_barang', 
                                  DB::raw('sum(stok) as total'))->where('id_barang', $b->id)
                                  ->groupBy('id_barang')->get();
                      @endphp
                      <tr class="text-dark ">
                        <td align="center">{{ $j }}</td>
                        <td>{{ $b->nama }}</td>
                        @if($stok->count() != 0)
                          <td align="right" style="background-color: yellow">{{$stok[0]->total}}</td>
                        @else
                          <td align="right" style="background-color: yellow">0</td>
                        @endif
                        @foreach($gudang as $sg)
                          @php
                            $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                                    ->where('id_gudang', $sg->id)->get();
                          @endphp
                          @if(($stokGd->count() != 0) && ($stokGd[0]->stok != 0))
                            <td align="right">{{$stokGd[0]->stok}}</td>
                          @else
                            <td></td>
                          @endif
                        @endforeach
                      </tr>
                      @php $j++; $baris++; @endphp
                    @endforeach
                  @else
                    @php $status = 1; @endphp
                  @endif
                @endif
              @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    @endforeach
  </body>
</html>