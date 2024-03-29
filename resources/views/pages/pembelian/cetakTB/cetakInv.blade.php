{{-- <!DOCTYPE html> --}}
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {{-- <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet"> --}}
    <style>
      body {
          /* margin: 0; */
          width: 914.7px;
          height: 520px;
          /* width: 8.54in;
          height: 5.43in; */
          font-family: "Calibri", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
          font-size: 1.2rem;
          font-weight: 700;
          line-height: 1.5;
          color: black;
          text-align: left;
          background-color: #fff;
      }

      /* @font-face {
        font-family: "epson1regular";
        font-weight: 900;
        color: black;
        src: url('{{ public_path('backend/fonts/epson1regular.ttf') }}');
      } */

      @font-face {
        font-family: "epson1";
        font-weight: 900;
        color: black;
        src: url('{{ public_path('backend/fonts/epson1.woff') }}');
      }

      /* @font-face {
        font-family: "bpdots";
        font-weight: 800;
        color: black;
        src: url('{{ public_path('backend/fonts/BPdots.otf') }}');
      } */

      @font-face {
        font-family: "Dotrice";
        font-weight: 800;
        color: black;
        src: url('{{ public_path('backend/fonts/Dotrice.ttf') }}');
      } 

      @font-face {
        font-family: "Dotrice Bold";
        font-weight: 800;
        color: black;
        src: url('{{ public_path('backend/fonts/Dotrice-Bold.otf') }}');
      } 

      @font-face {
        font-family: "buenard";
        font-weight: 800;
        color: black;
        src: url('{{ public_path('backend/fonts/Buenard-Regular.ttf') }}');
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
          width: 87.29%;
          padding-right: 0.75rem;
          padding-left: 0.75rem;
          /* margin-right: auto; */
          margin-left: auto;
          color: black;
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
          width: 90.1%;
          margin-bottom: 1rem;
          color: black;
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
          color: black;
          border: 1.4px dotted;
          border-bottom: none;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          border-left: 1px solid black;
          border-right: 1px solid black;
          padding-top: 5px;
          padding-bottom: 10px;
          /* margin-top: 0px; */
          /* margin-left: 35px;
          margin-right: 30px; */
          /* margin-top: 0;     // margin minimum
          margin-left: 0;
          margin-right: 0; */
          margin-left: 0;
          /* margin-top: 0; */
          /* margin-left: 20px; */
          margin-right: 0;
      }

      .title-header {
        /* font-family: "Dotrice"; */
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        /* font-family: "Fake Receipt", Times, serif; */
        /* margin-left: 10px !important; */
      }

      .subtitle-cetak-so-one {
        font-family: 'Courier New', Courier, monospace;
        color: black;
        /* font-family: "Dotrice"; */
        margin-top: -5px;
        /* margin-left: 8px; */
        font-size: 15px;
        font-weight: normal;
      }

      .subtitle-cetak-so-second {
        font-family: 'Courier New', Courier, monospace;
        color: black;
        /* font-family: "Dotrice"; */
        margin-left: -9px;
        margin-top: -2px;
        font-size: 15px;
        font-weight: normal;
        letter-spacing: 1px;
      }

      .sub-title {
        font-family: 'Calibri', Helvetica, sans-serif;
        /* font-family: 'Courier New', Courier, monospace; */
        /* font-family: 'Consolas', Helvetica, sans-serif; */
      }

      .logo-cetak-so {
        margin-top: -99px;
        /* margin-left: 20px; */
      }
 
      .logo-cetak-so img {
        /* width: 135px; */
        /* height: 50px; */
        width: 148px;
        height: 45px;
        margin-bottom: -2.5px;
        /* margin-top: -99px; */
        /* margin-left: 5px; // Margin Minimum */
        /* margin-left: 20px; */
      }

      .telpon-logo {
        font-family: "Rockwell", Helvetica, sans-serif;
        font-size: 10.5px;
        font-weight: 900;
        line-height: 0px;
        margin-left: 3px;
      }

      .customer-cetak-so {
        /* font-family: 'Courier New', Courier, monospace; */
        color: black;
        font-weight: 500;
        /* font-family: 'Consolas', Helvetica, sans-serif; */
        font-size: 16px;
        width: 275px;
        margin-top: -110px;
        margin-right: 75px;
      }

      .kode-cetak-so {
        font-size: 12px;
      }

      .nama-cetak-so {
        /* font-family: "Dotrice"; */
        color: black;
        margin-top: 8px;
        margin-bottom: 5px;
        font-size: 16px;
      }

      .alamat-cetak-so {
        color: black;
        font-size: 15px;
        margin-bottom: -10px;
        line-height: 14px;
      }

      .telepon-cetak-so {
        /* margin-bottom: -15px; */
        color: black;
        line-height: 18px;
      }

      .table-info-cetak-so {
        margin-top: -30px;
        font-size: 15px;
        border-spacing: 0px;
        /* margin-left: 0; // margin Minimum */
        margin-right: 30px;
        /* margin-left: 20px; */
      }

      .table-info-cetak-so td {
        border: 1px dotted;
      }

      .table-info-cetak-so thead td {
        padding-bottom: 0.25rem !important;
      }

      .th-info-cetak-so {
        font-family: 'Courier New', Courier, sans-serif;
        line-height: 6px;
      }

      .tr-info-cetak-so td{
        /* font-family: 'Consolas', sans-serif; */
        /* font-family: 'Consolas', Helvetica, sans-serif; */
        line-height: 9px;
      }

      .table-cetak {
        font-size: 16px;
        width: 90.1% !important;
        /* height: 225px; */
        height: 52.5% !important;
        /* margin-left: 0; // Margin minimum */
        /* margin-left: 20px; */
        margin-right: 34.5px;
        margin-top: -13px;
        /* border: 1px solid black; */
        /* margin-bottom: 40px !important; */
      }

      .table-cetak thead td {
        padding-top: 0.3rem !important;
        padding-bottom: 0.4rem !important;
      }

      .th-detail-cetak-so td {
        line-height: 6px;
        border-top: 1px dotted;
        border-bottom: 1px dotted;
        /* font-family: monospace, sans-serif; */
      }

      .tr-detail-cetak-so {
          /* color: black; */
          line-height: 7px;
          /* font-family: 'saxMono', sans-serif; */
          /* font-family: 'Consolas', Helvetica, sans-serif; */
      }

      .tr-detail-cetak-so td {
          border-bottom: none;
          border-top: none;
      }

      tr.baris-so {
        /* height: 15px !important; */
        height: 21px !important;
      }

      .table-cetak tbody td:empty {
        border-left: 0;
        border-right: 0;
        border-top: 0;
        /* border-bottom: 1px solid black; */
      }

      .footer-cetak-so {
          /* display: inline-block; */
          border: 1px dotted;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          border-bottom-left-radius: 10px;
          border-bottom-right-radius: 10px;
          border-left: 1px solid black;
          border-right: 1px solid black;
          margin-bottom: -40px;
          margin-left: 0; 
          /* margin-left: 20px; */
          margin-right: 30px;
          margin-top: -10px;
      }

      .table-footer {
          margin-left: -15px;
          width: 920px;
          margin-right: -50px;
      }

      .ttd-penerima {
        /* font-family: "Dotrice-Bold"; */
        /* font-family: Arial, Helvetica, sans-serif; */
        font-size: 15px;
        padding-left: 5px;
        margin-bottom: 12px;
        margin-top: 5px;
      }

      .info_bayar {
        /* font-family: 'Courier New', Courier, monospace; */
        color: black;
        margin-top: -7px;
        margin-left: 5px;
        margin-right: 30px;
        font-size: 15px;
        line-height: 18px;
      }

      .ttd-gudang {
        /* font-family: Arial, Helvetica, sans-serif; */
        font-size: 15px;
        margin-top: -5px;
        margin-left: 1px;
        margin-bottom: -4px;
        line-height: 14px;
      }

      .ttd-mengetahui {
        /* font-family: Arial, Helvetica, sans-serif; */
        font-size: 15px;
        margin-top: -10px;
        margin-bottom: -5px;
        line-height: 10px;
      }

      .tgl-ttd {
        font-size: 13px;
        line-height: 3px;
        padding-left: 0.4rem; 
        padding-bottom: 0.01rem;
      }

      .total-faktur {
        /* font-family: "dotricebold"; */
        /* font-family: Arial, Helvetica, sans-serif; */
        margin-top: 0px;
        margin-left: 5px;
        /* border: solid black; */
        font-size: 14px;
      }

      .tabel-total-faktur {
        line-height: 15px;
        margin-bottom: 3px;
      }

      .title-total {
        /* width: 145px; */
        font-size: 14px;
      }

      .angka-total {
        width: 180px;
        /* font-family: "epson1"; */
        /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; */
        font-size: 16px;
        padding-right: 0.01rem !important;
      }

      .angka-total-akhir {
        width: 145px;
        /* font-family: "epson1"; */
        /* font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; */
        font-size: 17px;
      }

      .waktu-cetak-so {
        font-weight: 700;
        margin-left: 30px;
        /* margin-right: 100px; // Margin minimum */
        margin-right: 100px; /* Margin custom */ 
        margin-top: 33px;
        /* border: solid black; */
      }

      .waktu-cetak {
        font-size: 12px !important;
        margin-left: -15px;
        margin-right: 10px;
      }

      .cetak-ke {
        font-size: 12px !important;
      }

      @media print {
        @page {
          /* size: 24.2cm 13.8cm; */
          width: 21.8cm;
          height: 13.8cm;
          /* margin: -10cm -10cm 0cm 0cm; */
          margin-top: 0.4002cm;
          margin-left: 0.281cm;
          margin-bottom: 0.144cm;
          /* margin-bottom: 0.4826cm;  0.19in */
          margin-right: 1.27cm;
        }

        body {
          margin: 0;
          /* margin: 0.13in 0.13in 0.5in 0.15in; */
          zoom: 1.37;
        }

        /* img {
          display: none;
        } */
      }
    </style>
  </head>
  <body>
    @php $i = 1; $no = 1; $kode = []; $det = 0; $urut = 0; $stat = 0; $kur = 0; $total = 0; @endphp
    @foreach($items as $item)
      @if(($items->first()->id != ($det <= 12 ? $item->id : $items[$urut-$kur]->id)) && ($det <= 12)) 
        @php $i = 1; $no = 1; $kur = 0; $kode = []; $total = 0; @endphp
      @endif
      <div class="cetak-all-container" style="margin-bottom: -55px; page-break-after: always;">
        <div class="container-fluid header-cetak-so">
          <div class="title-header text-center">
            <h5 class="text-bold ">TRANSFER BARANG</h5>
            <h5 class="text-bold " style="margin-top: -10px">
              (TRANSFER)
            </h5>
          </div>
          <div class="subtitle-cetak-so-one text-center">
            <span class="text-right sub-title">Nomor</span>
            <span>:</span>
            <span class="text-bold">{{ $det <= 12 ? $item->id : $items[$urut-$kur]->id }}</span>
          </div>
          <div class="subtitle-cetak-so-second text-center">
            <span class="text-right sub-title">Tanggal</span>
            <span>:</span>
            <span class="text-bold">{{ \Carbon\Carbon::parse(($det <= 12 ? $item->tgl_tb : $items[$urut-$kur]->tgl_tb))->format('d-M-y') }}</span>
          </div>
        </div>
        <div class="float-left logo-cetak-so">
          <img src="{{ url('backend/img/Logo_CPL_Only.jpg') }}" alt="">
          <br>
          <span class="telpon-logo">Office : 021 - 428 78 662</span>
        </div>
        <div class="float-right customer-cetak-so">
          <span class="kode-cetak-so"></span>
          <br>
          <span class="nama-cetak-so"></span>
          <br>
          <span class="alamat-cetak-so text-wrap"></span>
          <br>
        </div>
        <br>
        <br>

        <table class="table table-sm table-responsive-sm table-hover table-info-cetak-so">
          <thead class="text-center" style="font-weight: 600">
            <tr class="th-info-cetak-so">
              <td style="width: 120px">No. Order</td>
              <td style="width: 130px">Tgl. Order</td>
              <td style="width: 110px">Kredit Term</td>
              <td style="width: 130px">Jatuh Tempo</td>
              <td style="width: 180px">Sales</td>
              <td>Route</td>
            </tr>
          </thead>
          <tbody>
            <tr class="tr-info-cetak-so">
              <td align="center">{{ $det <= 12 ? $item->id : $items[$urut-$kur]->id }}</td>
              <td align="center">
                {{ \Carbon\Carbon::parse(($det <= 12 ? $item->tgl_tb : $items[$urut-$kur]->tgl_tb))->format('d-M-y') }}
              </td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center">{{ Auth::user()->name }}</td>
            </tr>
          </tbody>
        </table>
        
        @php 
        $stat = $det;
        if($det <= 12)
          $tb = $item->id;
        else
          $tb = $items[$urut-$kur]->id;

          // , sum(diskonRp) as diskonRp
        $itemsDet = \App\Models\DetilTB::where('id_tb', $tb)->whereNotIn('id_barang', $kode)->get();
        $det = $itemsDet->count();
        @endphp
        <!-- Tabel Data Detil BM-->
        <table class="table table-sm table-responsive-sm table-cetak" style="page-break-inside: auto">
          <thead class="text-center th-detail-cetak-so">
            <tr>
              <td style="width: 10px; border-left: 1px dotted">No</td>
              <td style="width: 340px">Nama Barang</td>
              <td style="width: 80px;">Dari Gudang</td>
              <td style="width: 75px">Qty</td>
              <td style="width: 80px; border-right: 1px dotted">Tujuan</td>
            </tr>
          </thead>
          <tbody class="tr-detail-cetak-so">
            @php $cek = 0; @endphp
            @foreach($itemsDet as $itemDet)
              <tr class="baris-so">
                <td align="center">{{ $no }}</td>
                <td>{{ $itemDet->barang->nama }}</td>
                <td align="center">{{ $itemDet->gudangAsal->nama }}</td>
                @if($itemDet->barang->satuan == "Pcs / Dus")
                  <td align="center">{{ $itemDet->qty }} PCS</td>
                @elseif($itemDet->barang->satuan == "Set")
                  <td align="center">{{ $itemDet->qty }} SET</td>
                @elseif($itemDet->barang->satuan == "Meter / Rol")
                  <td align="center">{{ $itemDet->qty }} ROL</td>
                @else
                  <td align="center">{{ $itemDet->qty }} MTR</td>
                @endif
                <td align="center">{{ $itemDet->gudangTuju->nama }}</td>
              </tr>
              @php $no++; array_push($kode, $itemDet->id_barang); $total += $itemDet->qty; @endphp
              @if($no > (12 * $i))
                @php $cek = 1; @endphp
                @break
              @endif
            @endforeach
            @if($itemsDet->count() < 12)
              <tr class="text-center">
                <td colspan="8"></td>
              </tr>
            @endif
          </tbody>
        </table>
        
        <div class="container-fluid footer-cetak-so">
          <table class="table-footer">
            <thead>
              <tr>
                <td style="border-right: 1px dotted; width: 90px"> 
                  <div class="ttd-penerima">
                    <table style="font-size: 15px !important">
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
                </td>
                <td style="border-right: 1px dotted; width: 273px">
                  <div class="info_bayar">
                    <span>Pembayaran Giro / Transfer</span>
                    <br>
                    <span>Rekening Bank BCA</span>
                    <br>
                    <span>a/n Indah Ramadhon 5790416491</span>
                  </div>
                </td>
                <td style="width: 90px">
                  <div class="ttd-gudang">
                    <table style="font-size: 15px !important">
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
                  </div>
                </td>
                <td style="border-right: 1px dotted; width: 88px">
                  <div class="ttd-mengetahui">
                    <table style="font-size: 15px !important">
                      <tr>
                        <td class="tgl-ttd">{{ \Carbon\Carbon::parse(($stat <= 12 ? $item->tgl_tb : $items[$urut-$kur]->tgl_tb))->format('d-M-y')}}</td>
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
                  </div>
                </td>
                <td>
                  <div class="total-faktur">
                    <table class="tabel-total-faktur">
                      <tr>
                        <td class="title-total text-bold">Jumlah Pcs</td>
                        <td class="text-right angka-total">{{ $itemsDet->count() <= 12 ? number_format($total, 0, "", ".") : '' }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Disc Faktur</td>
                        <td class="text-right angka-total">{{ $itemsDet->count() <= 12 ? '' : 'Bersambung' }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Nilai Netto</td>
                        <td class="text-right angka-total" @if($itemsDet->count() > 12) style="letter-spacing: 0.7px;" @endif>{{ $itemsDet->count() <= 12 ? '' : 'ke halaman' }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">PPN</td>
                        <td class="text-right angka-total">{{ $itemsDet->count() <= 12 ? '' : 'berikutnya...' }}</td>
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
                        <td class="text-right angka-total-akhir"></td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </thead>
          </table>
        </div>

        @php $i++; $urut++; $kur++; @endphp
        <div class="float-right waktu-cetak-so">
          <span class="waktu-cetak">Waktu Cetak : {{ $today }} {{ $waktu }}</span>
          <span class="cetak-ke">Cetak ke: 1</span>
        </div>
      </div>
    @endforeach

    <script type="text/javascript">
      window.onafterprint = function() {
        window.location = "{{ route('cetak-tb-update', ['awal' => $awal, 'akhir' => $akhir]) }}";
      }

      window.print();
    </script>
  </body>
  
</html>
