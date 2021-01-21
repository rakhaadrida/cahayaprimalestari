<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    {{-- <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet"> --}}
    <style>
      body {
          margin: 0;
          width: 816px;
          height: 520px;
          font-family: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
          font-size: 1rem;
          font-weight: 400;
          line-height: 1.5;
          color: #131313;
          text-align: left;
          background-color: #fff;
          margin-left: 35px;
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

      .float-left {
          float: left !important;
      }

      table {
          border-collapse: collapse;
      }

      .table {
          width: 116%;
          margin-bottom: 1rem;
          color: #858796;
      }

      .table th,
      .table td {
          padding: 2rem;
          vertical-align: top;
      }

      /* .table thead th {
          vertical-align: bottom;
          border-bottom: 1px solid #afbbc5;
      } */

      .table tbody+tbody {
          border-top: 1px solid #afbbc5;
      }

      .table-sm th {
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
        padding-left: 0.3rem;
        padding-right: 0.3rem;
      }

      .table-sm td {
        padding-top: 0.4rem;
        padding-bottom: 0.8rem;
        padding-left: 0.1rem;
        padding-right: 0.1rem;
      }

      .table tbody tr:last-child td {
        border-bottom: solid black;
        border-width: 1px;
        padding-bottom: 0.3rem;
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

      .header-cetak-so {
        display: inline-block;
        padding-top: 5px;
        padding-bottom: 10px;
        margin-top: -5px;
        margin-left: 13px;
        margin-right: 30px;
      }

      .title-header {
        font-size: 31px;
        font-family: Arial, Helvetica, sans-serif;
        margin-top: 65px;
        margin-left: 85px;
      }

      .subtitle-cetak-so {
        font-family: Arial, Helvetica, sans-serif;
        margin-top: 17px;
        margin-left: -50px;
        font-size: 17px;
      }

      .subtitle-second {
        margin-top: -3px;
      }

      .detail-cetak-so {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 17px;
        width: 195px;
        margin-top: -95px;
        margin-left: 693px;
        line-height: 20px;
      }

      .detail-second {
        margin-left: -24px;
      }

      .detail-third {
        margin-left: 8.5px;
      }

      .detail-fourth {
        margin-left: -15.5px;
      }

      .logo-cetak-so img {
        width: 170px;
        height: 65px;
        margin-top: -177px;
        margin-left: -30px;
      }

      .logo-address {
        margin-top: -109px;
        margin-left: -22.5px !important;
        font-size: 14px;
        font-family: 'Courier New', Courier, monospace;
      }

      .address-line-two {
        margin-top: -8px !important;
      }

      .customer-cetak-so {
        font-family: 'Courier New', Courier, monospace;
        font-size: 15px;
        width: 350px;
        margin-top: -175px;
        margin-right: -95px;
        line-height: 16px;
      }

      .waktu-second {
        margin-left: 36px;
      }

      .waktu-third {
        margin-left: 54px;
      }

      .page-number {
        float: right;
        margin-top: -23px;
        margin-right: -105px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
      }

      .table-cetak {
        font-size: 18px;
        /* height: 200px; */
        margin-left: -25px;
        margin-right: 21px;
        margin-top: -0px;
      }

      .th-detail-cetak-so {
        line-height: 20px;
        border: solid;
        border-width: 1.25px;
        border-left: none;
        border-right: none;
        color: black !important;
        font-family: 'Courier New', Courier, monospace;
        font-size: 21px;
      }

      .tr-detail-cetak-so {
        line-height: 13px;
        color: black !important;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
      }

      .footer-cetak-so {
        
        margin-top: -13px;
        margin-bottom: -50px;
        margin-left: 90px;
        margin-right: 30px;
      }

      .table-footer {
          margin-left: -88px;
          font-size: 18px;
      }

      /* @media print {
        @page {
          size: 21.59cm 13.9cm;
        }
      } */
    </style>
  </head>
  <body>
     @foreach($items as $item)
      <div class="cetak-all-container" @if($items[$items->count()-1]->id != $item->id) style="page-break-after: always" @endif>
        <div class="container-fluid header-cetak-so">
          <div class="title-header text-center">
            <h3 class="text-bold">Goods Receipt Note</h3>
          </div>
          <div class="subtitle-cetak-so">
            <span class="text-right">Supplier</span>
            <span>:</span>
            <span>{{ $item->supplier->nama }}</span>
          </div>
          <div class="subtitle-cetak-so subtitle-second">
            <span class="text-right">We had accepted these following item(s) :</span>
          </div>
        </div>
        <div class="float-left logo-cetak-so">
          <img src="{{ url('backend/img/Logo_CPL.jpg') }}" alt="">
          <h6 class="logo-address">JL KRAMAT PULO GUNDUL</h6>
          <h6 class="logo-address address-line-two">KRAMAT SENTIONG - JAKPUS</h6>
        </div>
        <div class="float-right customer-cetak-so">
          <span class="text-right text-bold">Tanggal Cetak</span>
          <span>:</span>
          <span>{{ $today }}</span>
          <br>
          <span class="waktu-second text-right text-bold">Jam Cetak</span>
          <span>:</span>
          <span>{{ $waktu }}</span>
          <br>
          <span class="waktu-third text-right text-bold">Pemakai</span>
          <span>:</span>
          <span>{{ Auth::user()->name }}</span>
        </div>
        <div class="detail-cetak-so">
          <span class="text-right">GRN Date</span>
          <span>:</span>
          <span>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}</span>
          <br>
          <span class="detail-second text-right">GRN Number</span>
          <span>:</span>
          <span>{{ $item->id }}</span>
          <br>
          <span class="detail-third text-right">DO. Date</span>
          <span>:</span>
          <span>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}</span>
          <br>
          <span class="detail-fourth text-right">DO. Number</span>
          <span>:</span>
          <span>{{ $item->id_faktur }}</span>
        </div>
        <br>
        
        @php 
        $itemsDet = \App\Models\DetilBM::with(['barang'])
                          ->select('id_barang', 'diskon')
                          ->selectRaw('avg(harga) as harga, sum(qty) as qty')
                          ->where('id_bm', $item->id)
                          ->groupBy('id_barang', 'diskon')
                          ->get();
        @endphp
        <!-- Tabel Data Detil BM-->
        <span class="page-number text-right">Page  :   1</span>
        <table class="table table-sm table-responsive-sm table-cetak">
          <thead class="text-center text-bold th-detail-cetak-so">
            <tr>
              <th style="width: 5px">No.</th>
              <th style="width: 30px">Kode</th>
              <th style="width: 330px">Nama Barang</th>
              {{-- <th colspan="3"><span style="margin-left: 10px !important">Quantity</span> </th> --}}
              <th><span style="margin-left: 10px !important">Quantity</span> </th>
              <th style="width: 250px">Description</th>
            </tr>
          </thead>
          <tbody class="tr-detail-cetak-so">
            @php $i = 1; @endphp
            @foreach($itemsDet as $itemDet)
              <tr>
                <td align="center">{{ $i }}</td>
                <td align="center">{{ $itemDet->id_barang }}</td>
                <td>{{ $itemDet->barang->nama }}</td>
                <td align="center" style="width: 50px">{{ $itemDet->qty }} @if($itemDet->barang->satuan == "Pcs / Dus") Pcs @elseif($itemDet->barang->satuan == "Set") Set @else Rol @endif</td>
                {{-- <td align="center" style="width: 1px">/</td>
                <td style="width: 50px">
                  {{ $itemDet->qty / $itemDet->barang->ukuran }} @if($itemDet->barang->satuan == "Pcs / Dus") Dus @else Rol @endif
                </td> --}}
                <td></td>
              </tr>
              @php $i++ @endphp
            @endforeach
          </tbody>
        </table>
        
        <div class="container-fluid footer-cetak-so">
          <table class="table-footer">
            <thead>
              <tr>
                <td align="center" style="width: 200px">KEPALA GUDANG</td>
                <td align="center" style="width: 280px">ADMIN SALES</td>
                <td align="center" style="width: 160px">GUDANG</td>
              </tr>
              <tr>
                <td colspan="3" style="height: 45px"></td>
              </tr>
              <tr>
                <td align="center">( AGUNG NUGRAHA )</td>
                <td align="center">( ERNA )</td>
                <td align="center">( TORO )</td>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    @endforeach
  </body>
</html>
