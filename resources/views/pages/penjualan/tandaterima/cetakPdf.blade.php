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

      .align-middle {
        vertical-align: middle !important;
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
        padding-top: 0.5rem;
        padding-bottom: 0.4rem;
        padding-left: 0.2rem;
        padding-right: 0.2rem;
      }

      .table tbody tr:last-child td {
        border-bottom: solid black;
        border-width: 1px;
        padding-bottom: 0.4rem;
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
        margin-top: 45px;
        margin-left: 13px;
        margin-right: 30px;
      }

      .title-header {
        font-size: 31px;
        font-family: Arial, Helvetica, sans-serif;
        margin-top: 25px;
        margin-left: -15px;
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
        width: 200px;
        margin-top: -65px;
        margin-left: 693px;
        line-height: 20px;
      }

      .detail-second {
        margin-left: -17px;
      }

      .detail-second-number {
        margin-left: -25px;
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
        margin-top: -137px;
        margin-left: -30px;
      }

      .logo-address {
        margin-top: -69px;
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
        margin-top: -135px;
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
        margin-top: -13px;
        margin-right: -105px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 16px;
      }

      .table-cetak {
        font-size: 18px;
        /* height: 200px; */
        margin-left: -25px;
        margin-right: 21px;
        margin-top: 10px;
      }

      .th-detail-cetak-so th {
        line-height: 20px;
        border: solid;
        border-width: 1.25px;
        color: black !important;
        font-family: 'Courier New', Courier, monospace;
        font-size: 21px;
      }

      .tr-detail-cetak-so td {
        line-height: 13px;
        border: solid;
        border-width: 1.25px;
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
    <div class="cetak-all-container">
      <div class="container-fluid header-cetak-so">
        <div class="title-header text-center">
          <h3 class="text-bold">Tanda Terima @if(substr($items[0]->id, 0, 3) == 'RET') Retur @else Faktur @endif</h3>
        </div>
        {{-- <div class="subtitle-cetak-so">
          <span class="text-right">Supplier</span>
          <span>:</span>
          <span>{{ $items[0]->supplier->nama }}</span>
        </div>
        <div class="subtitle-cetak-so subtitle-second">
          <span class="text-right">We had accepted these following item(s) :</span>
        </div> --}}
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
        <span class="text-right">No. TTF</span>
        <span>:</span>
        <span>{{ $newcode }}</span>
        <br>
        <span class="detail-second text-right">No. Faktur</span>
        <span>:</span>
        
        @if($items->count() == 1)
          <span>{{ $items[0]->id }}</span>
        @else
          <br>
          <span class="detail-second-number">{{ $items[0]->id }} - {{ $items->last()->id }}</span>
        @endif
        {{-- <br>
        <span class="detail-third text-right">DO. Date</span>
        <span>:</span>
        <span>{{ \Carbon\Carbon::parse($items[0]->tanggal)->format('d-M-y') }}</span>
        <br>
        <span class="detail-fourth text-right">DO. Number</span>
        <span>:</span>
        <span>{{ $items[0]->id_faktur }}</span> --}}
      </div>
      <br>
      
      @php 
      // $itemsDet = \App\Models\DetilBM::with(['barang'])
      //                   ->select('id_barang', 'diskon')
      //                   ->selectRaw('avg(harga) as harga, sum(qty) as qty')
      //                   ->where('id_bm', $items[0]->id)
      //                   ->groupBy('id_barang', 'diskon')
      //                   ->get();
      @endphp
      <!-- Tabel Data Detil BM-->
      <span class="page-number text-right">Page  :   1</span>
      <table class="table table-sm table-responsive-sm table-cetak">
        <thead class="text-center text-bold th-detail-cetak-so">
          <tr>
            <th rowspan="2" class="align-middle" style="width: 5px">No</th>
            <th rowspan="2" class="align-middle" style="width: 80px">No. Faktur</th>
            <th rowspan="2" class="align-middle" style="width: 100px">Tgl. Faktur</th>
            <th rowspan="2" class="align-middle" style="width: 360px">Customer</th>
            <th colspan="3">Ttd</th>
          </tr>
          <tr>
            <th style="width: 20px">Gudang</th>
            <th style="width: 20px">Pengiriman</th>
            <th style="width: 20px">Accounting</th>
          </tr>
        </thead>
        <tbody class="tr-detail-cetak-so">
          @php $i = 1; @endphp
          @foreach($items as $item)
            <tr>
              <td align="center">{{ $i }}</td>
              <td align="center">{{ $item->id }}</td>
              <td align="center">{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y') }}</td>
              @if(substr($items[0]->id, 0, 3) == 'RET')
                <td>{{ $item->so->customer->nama }}</td>
              @else
                <td>{{ $item->customer->nama }}</td>
              @endif
              <td></td>
              <td></td>
              <td></td>
            </tr>
            @php $i++ @endphp
          @endforeach
        </tbody>
      </table>
      
    </div>
  </body>
</html>
