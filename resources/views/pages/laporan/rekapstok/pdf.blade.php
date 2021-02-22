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
          color: black;
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
          color: black !important;
      }

      /* .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      } */
      
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
          font-size: 8px;
      }
      
      .table {
          width: 100%;
          margin-bottom: 1rem;
          color: black;
          /* table-layout: fixed; */
      }

      .table th,
      .table td {
          padding: 0.75rem;
          vertical-align: top;
          border-top: 1px solid black;
      }

      .table thead th {
          vertical-align: bottom;
          border-bottom: 1px solid black;
      }

      .table tbody+tbody {
          border-top: 1px solid black;
      }

      .table-sm th,
      .table-sm td {
          padding-top: 0.3rem;
          padding-bottom: 0.3rem;
          padding-left: 0.15rem;
          padding-right: 0.15rem;
      }

      .table-bordered {
          border: 1px solid black;
      }

      .table-bordered th,
      .table-bordered td {
          border: 1px solid black;
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

      /* #container {
        column-count: 2 !important;
        -moz-column-count: 2 !important;
        -webkit-column-count: 2 !important;
        
        width: 200px;
        height: 200px;
      } */

      .table-cetak {
        left: 0%;
        width: 50%;
        font-size: 8.5px;
        margin-left: -15px;
        margin-right: 3px;
        margin-top: -28px;
        position: absolute;
        /* border: 0.5px solid #292a2b !important; */
        border-width: thin !important;
      }

      .table-cetak th,
      .table-cetak td {
        padding-top: 0rem !important;
        padding-bottom: 0.1rem !important;
        border: 0.5px solid black !important;
        border-width: thin !important;
        /* overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; */
      }

      .table-cetak-right {
        left: 50%;
        width: 50%;
        font-size: 8.5px;
        margin-left: 3px;
        margin-right: -15px;
        /* margin-top: -0px; */
        position: absolute;
        /* border: 0.5px solid #292a2b !important; */
        border-width: thin !important;
      }

      .table-cetak-right th,
      .table-cetak-right td {
        padding-top: 0rem !important;
        padding-bottom: 0.1rem !important;
        border: 0.5px solid black !important;
        border-width: thin !important;
      }
    </style>
  </head>
  <body>
    @foreach($jenis as $item)
      @php
        $kodeJen = explode(",", $item->id);
      @endphp
      <div class="cetak-rekap-all" @if($jenis[$jenis->count()-1]->id != $item->id) style="page-break-after: always" @endif>
        <center>
          <div class="title-rekap-all">
            <h5 class="text-bold text-dark title-rekap">REKAP STOK {{$item->nama}}</h5>
            <h6 class="text-dark waktu-cetak">Waktu Cetak : {{$waktu}}</h6>
          </div>
        </center>
        <br>
        
        <div id="container">
          <!-- Tabel Data Detil BM-->
          <table class="table table-sm table-bordered table-cetak">
            <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
              <tr>
                <td style="width: 5px" class="align-middle">No</td>
                <td class="align-middle">Nama Barang</td>
                <td style="width: 25px; background-color: yellow" class="align-middle">Total</td>
                @foreach($gudang as $g)
                  <td style="width: 18px" class="align-middle">{{ substr($g->nama, 0, 3) }}</td>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @php 
                $i = 1; $baris = 1; $kode = []; $status = 0; $kodeBrg = [];
                $sub = \App\Models\Subjenis::whereIn('id_kategori', $kodeJen)->get();
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
                  @if($baris <= 67)
                    @if($baris != 67)
                      <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                        <td colspan="{{ $gudang->count() + 3 }}" align="center">{{ $s->nama }}</td>
                      </tr>
                      @php $kodeSub = $s->id; @endphp
                    @endif
                    @php 
                      if(($baris + $barang->count()) <= 67)
                        array_push($kode, $s->id); 
                      $baris++; 
                    @endphp
                    @foreach($barang as $b)
                      @if($baris <= 67)
                        @php
                          $stok = \App\Models\StokBarang::with(['barang'])->select('id_barang', 
                                    DB::raw('sum(stok) as total'))->where('id_barang', $b->id)
                                    ->groupBy('id_barang')->get();
                        @endphp
                        <tr class="text-dark ">
                          <td align="center">{{ $i }}</td>
                          <td class="text-truncate">{{ $b->nama }}</td>
                          <td align="right" style="background-color: yellow">{{ $stok->count() != 0 ? $stok[0]->total : ''}}</td>
                          @foreach($gudang as $g)
                            @php
                              if($g->tipe != 'RETUR') {
                                $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                                          ->where('id_gudang', $g->id)->get();
                              } else {
                                $stokGd = \App\Models\StokBarang::selectRaw('sum(stok) as
                                          stok')->where('id_barang', $b->id)
                                          ->where('id_gudang', $g->id)->get();
                              }
                            @endphp
                            <td align="right">{{ (($stokGd->count() != 0) && ($stokGd[0]->stok != 0)) ? $stokGd[0]->stok : '' }}</td>
                          @endforeach
                        </tr>
                        @php $i++; $baris++; array_push($kodeBrg, $b->id); @endphp
                      @else
                        @php $status = 1; @endphp
                        @break
                      @endif
                    @endforeach
                  @else
                    @php $status = 1; @endphp
                  @endif
                @endif
              @endforeach
            </tbody>
          </table>

          @if($status == 1)
            <table class="table table-sm table-bordered table-cetak-right">
              <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
                <tr>
                  <td style="width: 5px" class="align-middle">No</td>
                  <td class="align-middle">Nama Barang</td>
                  <td style="width: 25px; background-color: yellow" class="align-middle">Total</td>
                  @foreach($gudang as $g)
                    <td style="width: 18px" class="align-middle">{{ substr($g->nama, 0, 3) }}</td>
                  @endforeach
                </tr>
              </thead>
              <tbody id="tablePO">
                @php $j = $i; $status = 0;
                    $sub = \App\Models\Subjenis::whereIn('id_kategori', $kodeJen)
                          ->whereNotIn('id', $kode)->get();
                @endphp
                @if($baris <= 134)
                  @foreach($sub as $s)
                  @if($status != 1)
                    @php
                      // if($s->id != 'SUB31') {
                        $barang = \App\Models\Barang::where('id_sub', $s->id)
                                  ->whereNotIn('id', $kodeBrg)->get();
                      // } else {
                      //   $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
                      // }
                    @endphp 
                    @if($baris <= 134)
                      @if(($s->id != $kodeSub) && ($baris != 134))
                        <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                          <td colspan="{{ $gudang->count() + 3 }}" align="center">{{ $s->nama }}</td>
                        </tr>
                        @php $kodeSub = $s->id; $baris++; @endphp
                      @endif
                      @php 
                        if(($baris + $barang->count()) <= 134)
                          array_push($kode, $s->id); 
                      @endphp
                      @foreach($barang as $b)
                        @if($baris <= 134)
                          @php
                            $stok = \App\Models\StokBarang::with(['barang'])->select('id_barang', 
                                      DB::raw('sum(stok) as total'))->where('id_barang', $b->id)
                                      ->groupBy('id_barang')->get();
                          @endphp
                          <tr class="text-dark ">
                            <td align="center">{{ $j }}</td>
                            <td>{{ $b->nama }}</td>
                            <td align="right" style="background-color: yellow">{{ $stok->count() != 0 ? $stok[0]->total : ''}}</td>
                            @foreach($gudang as $sg)
                              @php
                                if($sg->tipe != 'RETUR') {
                                  $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                                            ->where('id_gudang', $sg->id)->get();
                                } else {
                                  $stokGd = \App\Models\StokBarang::selectRaw('sum(stok) as
                                            stok')->where('id_barang', $b->id)
                                            ->where('id_gudang', $sg->id)->get();
                                }
                              @endphp
                              <td align="right">{{ (($stokGd->count() != 0) && ($stokGd[0]->stok != 0)) ? $stokGd[0]->stok : '' }}</td>
                            @endforeach
                          </tr>
                          @php $j++; $baris++; array_push($kodeBrg, $b->id); @endphp
                        @else
                          @php $status = 1; @endphp
                          @break
                        @endif
                      @endforeach
                    @else
                      @php $status = 1; @endphp
                    @endif
                  @endif
                @endforeach
                @endif
              </tbody>
            </table>
          @endif

          @if($status == 1)
            <table class="table table-sm table-bordered table-cetak" style="page-break-before: always">
              <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
                <tr>
                  <td style="width: 5px" class="align-middle">No</td>
                  <td class="align-middle" class="align-middle">Nama Barang</td>
                  <td style="width: 25px; background-color: yellow" class="align-middle">Total</td>
                  @foreach($gudang as $g)
                    <td style="width: 18px" class="align-middle">{{ substr($g->nama, 0, 3) }}</td>
                  @endforeach
                </tr>
              </thead>
              <tbody id="tablePO">
                @php $status = 0;
                    $sub = \App\Models\Subjenis::whereIn('id_kategori', $kodeJen)
                          ->whereNotIn('id', $kode)->get();
                @endphp
                @if($baris <= 201)
                  @foreach($sub as $s)
                  @if($status != 1)
                    @php
                      $barang = \App\Models\Barang::where('id_sub', $s->id)
                                ->whereNotIn('id', $kodeBrg)->get();
                    @endphp 
                    @if($baris <= 201)
                      @if(($s->id != $kodeSub) && ($baris != 201))
                        <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                          <td colspan="{{ $gudang->count() + 3 }}" align="center">{{ $s->nama }}</td>
                        </tr>
                        @php $kodeSub = $s->id; $baris++; @endphp
                      @endif
                      @php 
                        if(($baris + $barang->count()) <= 201)
                          array_push($kode, $s->id); 
                      @endphp
                      @foreach($barang as $b)
                        @if($baris <= 201)
                          @php
                            $stok = \App\Models\StokBarang::with(['barang'])->select('id_barang', 
                                      DB::raw('sum(stok) as total'))->where('id_barang', $b->id)
                                      ->groupBy('id_barang')->get();
                          @endphp
                          <tr class="text-dark ">
                            <td align="center">{{ $j }}</td>
                            <td>{{ $b->nama }}</td>
                            <td align="right" style="background-color: yellow">{{ $stok->count() != 0 ? $stok[0]->total : ''}}</td>
                            @foreach($gudang as $sg)
                              @php
                                if($sg->tipe != 'RETUR') {
                                  $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                                            ->where('id_gudang', $sg->id)->get();
                                } else {
                                  $stokGd = \App\Models\StokBarang::selectRaw('sum(stok) as
                                            stok')->where('id_barang', $b->id)
                                            ->where('id_gudang', $sg->id)->get();
                                }
                              @endphp
                              <td align="right">{{ (($stokGd->count() != 0) && ($stokGd[0]->stok != 0)) ? $stokGd[0]->stok : '' }}</td>
                            @endforeach
                          </tr>
                          @php $j++; $baris++; array_push($kodeBrg, $b->id); @endphp
                        @else
                          @php $status = 1; @endphp
                          @break
                        @endif
                      @endforeach
                    @else
                      @php $status = 1; @endphp
                    @endif
                  @endif
                @endforeach
                @endif
              </tbody>
            </table>
          @endif

          @if($status == 1)
            <table class="table table-sm table-bordered table-cetak-right">
              <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
                <tr>
                  <td style="width: 5px" class="align-middle">No</td>
                  <td class="align-middle" class="align-middle">Nama Barang</td>
                  <td style="width: 25px; background-color: yellow" class="align-middle">Total</td>
                  @foreach($gudang as $g)
                    <td style="width: 18px" class="align-middle">{{ substr($g->nama, 0, 3) }}</td>
                  @endforeach
                </tr>
              </thead>
              <tbody id="tablePO">
                @php $status = 0;
                    $sub = \App\Models\Subjenis::whereIn('id_kategori', $kodeJen)
                          ->whereNotIn('id', $kode)->get();
                @endphp
                @if($baris <= 268)
                  @foreach($sub as $s)
                  @if($status != 1)
                    @php
                      $barang = \App\Models\Barang::where('id_sub', $s->id)
                                ->whereNotIn('id', $kodeBrg)->get();
                    @endphp 
                    @if($baris <= 268)
                      @if(($s->id != $kodeSub) && ($baris != 268))
                        <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                          <td colspan="{{ $gudang->count() + 3 }}" align="center">{{ $s->nama }}</td>
                        </tr>
                      @endif
                      @php $baris++; @endphp
                      @foreach($barang as $b)
                        @if($baris <= 268)
                          @php
                            $stok = \App\Models\StokBarang::with(['barang'])->select('id_barang', 
                                      DB::raw('sum(stok) as total'))->where('id_barang', $b->id)
                                      ->groupBy('id_barang')->get();
                          @endphp
                          <tr class="text-dark ">
                            <td align="center">{{ $j }}</td>
                            <td>{{ $b->nama }}</td>
                            <td align="right" style="background-color: yellow">{{ $stok->count() != 0 ? $stok[0]->total : ''}}</td>
                            @foreach($gudang as $sg)
                              @php
                                if($sg->tipe != 'RETUR') {
                                  $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                                            ->where('id_gudang', $sg->id)->get();
                                } else {
                                  $stokGd = \App\Models\StokBarang::selectRaw('sum(stok) as
                                            stok')->where('id_barang', $b->id)
                                            ->where('id_gudang', $sg->id)->get();
                                }
                              @endphp
                              <td align="right">{{ (($stokGd->count() != 0) && ($stokGd[0]->stok != 0)) ? $stokGd[0]->stok : '' }}</td>
                            @endforeach
                          </tr>
                          @php $j++; $baris++; array_push($kodeBrg, $b->id); @endphp
                        @else
                          @break
                        @endif
                      @endforeach
                    @else
                      @php $status = 1; @endphp
                    @endif
                  @endif
                @endforeach
                @endif
              </tbody>
            </table>
          @endif
        </div>
      </div>
    @endforeach
  </body>
</html>