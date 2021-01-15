{{-- <!DOCTYPE html> --}}
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {{-- <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet"> --}}
    <style>
      body {
          margin: 0;
          width: 914.7px;
          height: 520px;
          /* width: 8.54in;
          height: 5.43in; */
          font-family: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
          font-size: 1rem;
          font-weight: 900;
          line-height: 1.5;
          color: #252525;
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
          font-size: 1.15rem;
      }

      .container-fluid {
          width: 80.5%;
          padding-right: 0.75rem;
          padding-left: 0.75rem;
          /* margin-right: auto; */
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
          width: 83.6%;
          margin-bottom: 1rem;
          color:#252525;
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
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
        padding-left: 0.15rem;
        padding-right: 0.15rem;
      }

      /* .table-sm th {
        padding-top: 0.4rem;
        padding-bottom: 0.4rem;
        padding-left: 0.3rem;
        padding-right: 0.3rem;
      }

      .table-sm td {
        padding-top: 0.4rem;
        padding-bottom: 0.3rem;
        padding-left: 0.1rem;
        padding-right: 0.1rem;
      } */

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
          border: 1.9px dotted;
          border-bottom: none;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          padding-top: 5px;
          padding-bottom: 10px;
          margin-top: 0px;
          margin-left: 35px;
          margin-right: 30px;
      }

      .title-header {
        font-family: Arial, Helvetica, sans-serif;
        /* font-family: "Fake Receipt", Times, serif; */
        margin-left: 10px !important;
      }

      .subtitle-cetak-so-one {
        margin-top: -5px;
        margin-left: 15px;
        font-size: 12px;
      }

      .subtitle-cetak-so-second {
          margin-left: -18px;
          margin-top: -2px;
          font-size: 12px;
      }

      .sub-title {
        /* font-family: 'Courier New', Courier, monospace; */
        font-family: 'Consolas', Helvetica, sans-serif;
      }

      .logo-cetak-so img {
        width: 135px;
        height: 50px;
        margin-top: -95px;
        margin-left: 40px;
      }

      .customer-cetak-so {
        font-family: 'Consolas', Helvetica, sans-serif;
        font-size: 12px;
        width: 205px;
        margin-top: -95px;
        margin-right: 125px;
      }

      .kode-cetak-so {
        font-size: 11px;
      }

      .nama-cetak-so {
          margin-top: 10px;
          margin-bottom: 5px;
          font-size: 14px;
      }

      .alamat-cetak-so {
        margin-bottom: -10px;
        line-height: 15px;
      }

      .telepon-cetak-so {
          /* margin-bottom: -15px; */
          line-height: 18px;
      }

      .table-info-cetak-so {
          margin-top: -60px;
          font-size: 11px;
          border-spacing: 0px;
          margin-left: 35px;
          margin-right: 30px;
      }

      .table-info-cetak-so td {
        border: 1.9px dotted;
      }

      .table-info-cetak-so thead td {
        padding-bottom: 0.25rem !important;
      }

      .th-info-cetak-so {
        font-family: 'Courier New', Courier, monospace;
        line-height: 5px;
      }

      .tr-info-cetak-so td{
        font-family: 'Consolas', sans-serif;
        /* font-family: 'Consolas', Helvetica, sans-serif; */
        line-height: 8px;
      }

      .table-cetak {
        font-size: 12px;
        width: 84.18% !important;
        height: 227px;
        margin-left: 35px;
        margin-right: 34.5px;
        margin-top: -14px;
      }

      .table-cetak thead td {
        padding-top: 0.3rem !important;
        padding-bottom: 0.3rem !important;
      }

      .th-detail-cetak-so {
        line-height: 6px;
        border: 1.9px dotted;
        font-family: monospace, sans-serif;
      }

      .tr-detail-cetak-so {
          line-height: 7px;
          /* font-family: 'saxMono', sans-serif; */
          /* font-family: 'Consolas', Helvetica, sans-serif; */
      }

      tr.baris-so {
        height: 15px !important;
      }

      .table-cetak td:empty {
        border-left: 0;
        border-right: 0;
        border-top: 0;
      }

      .footer-cetak-so {
          display: inline-block;
          border: 1.9px dotted;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          border-bottom-left-radius: 10px;
          border-bottom-right-radius: 10px;
          margin-bottom: -40px;
          margin-left: 35px;
          margin-right: 30px;
      }

      .table-footer {
          margin-left: -15px;
          /* border: solid black; */
          width: 920px;
          margin-right: -50px;
      }

      .ttd-penerima {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        padding-left: 5px;
        margin-bottom: 12px;
        margin-top: 5px;
      }

      .info_bayar {
        font-family: 'Courier New', Courier, monospace;
        color: black;
        margin-top: -7px;
        margin-left: 5px;
        margin-right: 30px;
        font-size: 12px;
        line-height: 18px;
      }

      .ttd-gudang {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        margin-top: -5px;
        margin-left: 1px;
        margin-bottom: -4px;
        line-height: 14px;
      }

      .ttd-mengetahui {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        margin-top: -10px;
        margin-bottom: -5px;
        line-height: 10px;
      }

      .tgl-ttd {
        font-size: 11px;
        line-height: 3px;
        padding-left: 0.4rem; 
        padding-bottom: 0.01rem;
      }

      .total-faktur {
        font-family: Arial, Helvetica, sans-serif;
        margin-top: -2px;
        margin-left: 5px;
        /* border: solid black; */
        font-size: 12px;
      }

      .tabel-total-faktur {
        line-height: 13px;
        margin-bottom: 3px;
      }

      .title-total {
        /* width: 145px; */
        font-size: 11px;
      }

      .angka-total {
        width: 145px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 13px;
        padding-right: 0.01rem !important;
      }

      .angka-total-akhir {
        width: 145px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
      }

      .waktu-cetak-so {
        margin-left: 30px;
        margin-right: 120px;
        margin-top: 32px;
      }

      .waktu-cetak {
        font-size: 9px !important;
        margin-left: -15px;
        margin-right: 10px;
      }

      .cetak-ke {
        font-size: 9px !important;
      }

      @media print {
        @page {
          /* size: 24.2cm 13.8cm; */
          width: 21.8cm;
          height: 13.8cm;
        }

        body {
          margin: 0;
          zoom: 1.37;
        }
      }
    </style>
  </head>
  <body>
    @foreach($items as $item)
      <div class="cetak-all-container" style="margin-bottom: -55px">
        <div class="container-fluid header-cetak-so">
          <div class="title-header text-center">
            <h5 class="text-bold ">FAKTUR PENJUALAN</h5>
            <h5 class="text-bold " style="margin-top: -10px">
              (@if($item->kategori == "Cash") CASH @else TEMPO @endif)
            </h5>
          </div>
          <div class="subtitle-cetak-so-one text-center">
            <span class="text-right sub-title">Nomor</span>
            <span>:</span>
            <span class="text-bold">{{ $item->id }}</span>
          </div>
          <div class="subtitle-cetak-so-second text-center">
            <span class="text-right sub-title">Tanggal</span>
            <span>:</span>
            <span class="text-bold">{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y') }}</span>
          </div>
        </div>
        <div class="float-left logo-cetak-so">
          <img src="{{ url('backend/img/Logo_CPL.jpg') }}" alt="">
        </div>
        <div class="float-right customer-cetak-so">
          <span class="kode-cetak-so">Kepada Yth :</span>
          <span>{{ $item->id_customer }}</span>
          <br>
          <span class="nama-cetak-so">{{ $item->customer->nama }}</span>
          <br>
          <span class="alamat-cetak-so text-wrap">{{ $item->customer->alamat }}</span>
          <br>
          <span class="telepon-cetak-so">{{ $item->customer->telepon }}</span>
        </div>
        <br>
        <br>

        <table class="table table-sm table-responsive-sm table-hover table-info-cetak-so">
          <thead class="text-center text-bold">
            <tr class="th-info-cetak-so">
              <td style="width: 110px">No. Order</td>
              <td style="width: 110px">Tgl. Order</td>
              <td style="width: 110px">Kredit Term</td>
              <td style="width: 110px">Jatuh Tempo</td>
              <td style="width: 180px">Sales</td>
              <td>Route</td>
            </tr>
          </thead>
          <tbody class="text-bold">
            <tr class="tr-info-cetak-so">
              <td align="center">{{ $item->id }}</td>
              <td align="center">
                {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y') }}
              </td>
              <td align="center">0 Hari</td>
              <td align="center">
                {{ \Carbon\Carbon::parse($item->tgl_so)->add($item->tempo, 'days')->format('d-M-y') }}
              </td>
              <td align="center">{{ $item->customer->sales->nama }}</td>
              <td align="center">{{ Auth::user()->name }}</td>
            </tr>
          </tbody>
        </table>
        
        @php 
        $itemsDet = \App\Models\DetilSO::with(['barang'])
                          ->select('id_barang', 'diskon')
                          ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                          ->where('id_so', $item->id)
                          ->groupBy('id_barang', 'diskon')
                          ->get();
        @endphp
        <!-- Tabel Data Detil BM-->
        <table class="table table-sm table-responsive-sm table-cetak" style="page-break-inside: auto">
          <thead class="text-center text-bold th-detail-cetak-so">
            <tr>
              <td style="width: 10px">No</td>
              <td style="width: 285px">Nama Barang</td>
              <td style="width: 65px" class="text-right">Qty</td>
              <td style="width: 80px"></td>
              <td style="width: 60px">Harga</td>
              <td style="width: 70px">Total</td>
              <td colspan="2">Diskon</td>
              <td style="width: 75px; border-right: 1.9px dotted">Netto Rp</td>
            </tr>
          </thead>
          <tbody class="tr-detail-cetak-so">
            @php $i = 1; @endphp
            @foreach($itemsDet as $itemDet)
              <tr class="baris-so">
                <td align="center">{{ $i }}</td>
                <td>{{ $itemDet->barang->nama }}</td>
                @if($itemDet->barang->satuan == "Pcs / Dus")
                  <td colspan="2" align="center"><span style="margin-left: -15px !important">{{ $itemDet->qty }} PCS</span></td>
                @elseif($itemDet->barang->satuan == "Meter / Rol")
                  <td align="center">{{ $itemDet->qty }} ROL</td>
                  <td >{{ number_format($itemDet->qty * $itemDet->barang->ukuran, 0, "", ".") }} MTR</td>
                @else
                  <td colspan="2" align="center"><span style="margin-left: -15px !important">{{ $itemDet->qty }} MTR</span></td>
                @endif
                <td align="right">{{ number_format($itemDet->harga, 0, "", ".") }}</td>
                <td align="right">{{ number_format($itemDet->qty * $itemDet->harga, 0, "", ".") }}</td>
                @php 
                  $diskon = 100;
                  $arrDiskon = explode("+", $itemDet->diskon);
                  for($j = 0; $j < sizeof($arrDiskon); $j++) {
                    $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                  } 
                  $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                @endphp
                <td style="width: 130px" align="right">
                  {{ str_replace(".", ",", $itemDet->diskon) }} ({{ $diskon }}%)
                </td>
                <td style="width: 65px" align="right">
                  {{ number_format($itemDet->diskonRp, 0, "", ".") }}
                </td>
                <td align="right">
                  {{ number_format((($itemDet->qty * $itemDet->harga) - $itemDet->diskonRp), 0, "", ".") }}</td>
              </tr>
              @php $i++ @endphp
            @endforeach
            @if($i < 10)
              <tr class="text-center">
                <td colspan="8"></td>
                <td></td>
                <td colspan="2"></td>
              </tr>
            @endif
          </tbody>
        </table>
        
        @php 
          $cetak = 1;
          if($item->status != 'INPUT') {
            $ubah = App\Models\Approval::where('id_dokumen', $item->id)->count();
            $cetak += $ubah; 
          }
        @endphp

        <div class="container-fluid footer-cetak-so">
          <table class="table-footer">
            <thead>
              <tr>
                <td style="border-right: 1.9px dotted; width: 87px"> 
                  <div class="ttd-penerima">
                    <table style="font-size: 12px !important">
                      <tr>
                        <td class="text-center">Penerima,</td>
                      </tr>
                      <tr>
                        <td style="height: 38px"></td>
                      </tr>
                      <tr>
                        <td class="text-center">(__________)</td>
                      </tr>
                    </table>
                  </div>
                  {{-- <div class="ttd-penerima text-center">
                    <span>Penerima,</span>
                    <br><br><br>
                    <span class="form-ttd">(___________)</span>
                  </div> --}}
                </td>
                <td style="border-right: 1.9px dotted; width: 253px">
                  <div class="info_bayar">
                    <span>Pembayaran Giro / Transfer</span>
                    <br>
                    <span>Rekening Bank BCA</span>
                    <br>
                    <span>a/n Indah Ramadhon 5790416491</span>
                    {{-- <span>Waktu Cetak : </span>
                    <br>
                    <span>{{ $today }} - {{ $waktu }}</span>
                    <br>
                    <span>Cetak ke : 1</span> --}}
                  </div>
                </td>
                <td style="width: 90px">
                  <div class="ttd-gudang">
                    <table style="font-size: 12px !important">
                      <tr>
                        <td class="text-center">Gudang,</td>
                      </tr>
                      <tr>
                        <td style="height: 30px"></td>
                      </tr>
                      <tr>
                        <td class="text-center">(___________)</td>
                      </tr>
                    </table>
                    {{-- <center><span class="nama-gudang">Gudang,</span></center>
                    <br><br>
                    <span class="form-ttd">(___________)</span> --}}
                  </div>
                </td>
                <td style="border-right: 1.9px dotted; width: 85px">
                  <div class="ttd-mengetahui">
                    {{-- <span class="tgl-ttd">
                      {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y')}}
                    </span> --}}
                    <table style="font-size: 12px !important">
                      <tr>
                        <td class="tgl-ttd">{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y')}}</td>
                      </tr>
                      <tr>
                        <td class="text-center">Mengetahui,</td>
                      </tr>
                      <tr>
                        <td style="height: 30px"></td>
                      </tr>
                      <tr>
                        <td class="text-center">(__________)</td>
                      </tr>
                    </table>
                    {{-- <span class="tgl-ttd">
                      {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y')}}
                    </span>
                    <span>Mengetahui,</span> 
                    <br><br><br><br>
                    <span class="form-ttd">(__________)</span>  --}}
                  </div>
                </td>
                <td>
                  <div class="total-faktur">
                    <table class="tabel-total-faktur">
                      <tr>
                        <td class="title-total text-bold">Jumlah</td>
                        <td class="text-right angka-total">{{ number_format($item->total + $item->diskon, 0, "", ".") }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Disc Faktur</td>
                        <td class="text-right angka-total">{{ number_format($item->diskon, 0, "", ".") }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Nilai Netto</td>
                        <td class="text-right angka-total">{{ number_format($item->total, 0, "", ".") }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">PPN</td>
                        <td class="text-right angka-total"></td>
                      </tr>
                      <tr>
                        <td class="title-total"></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td colspan="2" style="height: 3px"></td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Nilai Tagihan</td>
                        <td class="text-right angka-total-akhir">{{ number_format($item->total, 0, "", ".") }}</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </thead>
          </table>
        </div>
        <div class="float-right waktu-cetak-so">
          <span class="waktu-cetak">Waktu Cetak : {{ $today }} {{ $waktu }}</span>
          <span class="cetak-ke">Cetak ke: {{ $cetak }}</span>
        </div>
      </div>
    @endforeach
  </body>
  
</html>
