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
          width: 100%;
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
        padding-bottom: 0.3rem;
        padding-left: 0.1rem;
        padding-right: 0.1rem;
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
          border: dotted;
          border-bottom: none;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          padding-top: 5px;
          padding-bottom: 10px;
          margin-top: -30px;
          margin-left: 30px;
          margin-right: 30px;
      }

      .subtitle-cetak-so-one {
          margin-top: -5px;
          font-size: 12px;
      }

      .subtitle-cetak-so-second {
          margin-left: -2.5px;
          font-size: 12px;
      }

      .sub-title {
        font-family: 'Courier New', Courier, monospace;
      }

      .logo-cetak-so img {
        width: 132px;
        height: 50px;
        margin-top: 5px;
        margin-left: 35px;
      }

      .customer-cetak-so {
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        width: 220px;
        margin-top: 0px;
        margin-right: 30px;
      }

      .kode-cetak-so {
        font-size: 11px;
      }

      .nama-cetak-so {
          margin-top: 10px;
          font-size: 14px;
      }

      .alamat-cetak-so {
        margin-bottom: -10px;
        line-height: 20px;
      }

      .telepon-cetak-so {
          /* margin-bottom: -15px; */
          line-height: 18px;
      }

      .table-info-cetak-so {
          margin-top: -25px;
          font-size: 11px;
          border: dotted;
          border-spacing: 0px;
          margin-left: 29px;
          margin-right: 29px;
      }

      .th-info-cetak-so {
        font-family: 'Courier New', Courier, monospace;
        line-height: 5px;
      }

      .tr-info-cetak-so td{
        line-height: 10px;
      }

      .table-cetak {
          font-size: 11px;
          height: 230px;
          margin-left: 31px;
          margin-right: 34.5px;
          margin-top: -13px;
      }

      .th-detail-cetak-so {
          line-height: 5px;
          border: dotted;
      }

      .tr-detail-cetak-so {
          line-height: 10px;
      }

      .footer-cetak-so {
          display: inline-block;
          border: dotted;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          border-bottom-left-radius: 10px;
          border-bottom-right-radius: 10px;
          margin-bottom: -50px;
          margin-left: 30px;
          margin-right: 30px;
      }

      .table-footer {
          margin-left: -15px;
      }

      .ttd-penerima {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        padding-left: -5px;
        margin-bottom: 15px;
        margin-top: 0px;
      }

      .info_bayar {
        font-family: 'Courier New', Courier, monospace;
        color: black;
        margin-top: -15px;
        margin-left: 5px;
        margin-right: 30px;
        font-size: 12px;
        line-height: 18px;
      }

      .ttd-gudang {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        margin-top: -5px;
        margin-left: 2px;
        line-height: 14px;
      }

      .ttd-mengetahui {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        margin-top: -12px;
        line-height: 10px;
      }

      .tgl-ttd {
        font-size: 10px;
      }

      .total-faktur {
        font-family: Arial, Helvetica, sans-serif;
          margin-top: 1px;
          margin-left: 5px;
          font-size: 10px;
      }

      .tabel-total-faktur {
          line-height: 12px;
          margin-bottom: 3px;
      }

      .title-total {
          width: 168px;
      }

      .angka-total {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 12px;
      }

      .angka-total-akhir {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 13px;
      }

      .waktu-cetak-so {
        margin-left: 30px;
        margin-right: 30px;
        margin-top: 80px;
      }

      .waktu-cetak {
        font-size: 10px !important;
        margin-left: -15px;
        margin-right: 10px;
      }

      .cetak-ke {
        font-size: 10px !important;
      }

      /* @media print {
        @page {
          size: 24.2cm 13.8cm;
        }
      } */
    </style>
  </head>
  <body>
    @foreach($items as $item)
      <div class="cetak-all-container" style="margin-bottom: -55px; @if($items[$items->count()-1]->id != $item->id) page-break-after: always; @endif " >
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
          <img src="backend/img/Logo_CPL.jpg" alt="">
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
              <td style="border: dotted; width: 110px">No. Order</td>
              <td style="border: dotted; width: 110px">Tgl. Order</td>
              <td style="border: dotted; width: 110px">Kredit Term</td>
              <td style="border: dotted; width: 110px">Jatuh Tempo</td>
              <td style="border: dotted; width: 180px">Sales</td>
              <td style="border: dotted">Route</td>
            </tr>
          </thead>
          <tbody class="text-bold">
            <tr class="tr-info-cetak-so">
              <td align="center" style="border: dotted">{{ $item->id }}</td>
              <td align="center" style="border: dotted">
                {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y') }}
              </td>
              <td align="center" style="border: dotted">0 Hari</td>
              <td align="center" style="border: dotted">
                {{ \Carbon\Carbon::parse($item->tgl_so)->add($item->tempo, 'days')->format('d-M-y') }}
              </td>
              <td align="center" style="border: dotted">{{ $item->customer->sales->nama }}</td>
              <td align="center" style="border: dotted">{{ Auth::user()->name }}</td>
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
        <table class="table table-sm table-responsive-sm table-hover table-cetak">
          <thead class="text-center text-bold th-detail-cetak-so">
            <tr>
              <td style="width: 10px">No</td>
              <td style="width: 50px">Kode</td>
              <td>Nama Barang</td>
              <td colspan="2"><span style="margin-left: 10px !important">Qty</span> </td>
              <td style="width: 30px">UOM</td>
              <td style="width: 55px">Harga</td>
              <td style="width: 70px">Rupiah</td>
              <td colspan="2">Diskon</td>
              <td style="width: 80px">Netto Rp</td>
            </tr>
          </thead>
          <tbody class="tr-detail-cetak-so">
            @php $i = 1; @endphp
            @foreach($itemsDet as $itemDet)
              <tr >
                <td rowspan="2" align="center">{{ $i }}</td>
                <td rowspan="2">{{ $itemDet->id_barang }}</td>
                <td rowspan="2">{{ $itemDet->barang->nama }}</td>
                <td rowspan="2" align="right" style="width: 50px">{{ $itemDet->qty }}</td>
                @if($itemDet->barang->satuan == "Pcs / Dus")
                  <td rowspan="2" align="center" style="width: 50px">
                    {{ $itemDet->qty / $itemDet->barang->ukuran }} Dus
                  </td>
                @else
                  <td rowspan="2" align="center" style="width: 80px">
                    {{ $itemDet->qty * $itemDet->barang->ukuran }} Mtr
                  </td>
                @endif
                <td rowspan="2" align="center">
                  @if($itemDet->barang->satuan == "Pcs / Dus") PCS @else ROL @endif
                </td>
                <td rowspan="2" align="right">{{ number_format($itemDet->harga, 0, "", ".") }}</td>
                <td rowspan="2" align="right">{{ number_format($itemDet->qty * $itemDet->harga, 0, "", ".") }}</td>
                @php 
                  $diskon = 100;
                  $arrDiskon = explode("+", $itemDet->diskon);
                  for($j = 0; $j < sizeof($arrDiskon); $j++) {
                    $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                  } 
                  $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                @endphp
                <td style="width: 70px; border-bottom: none !important" align="right">
                  {{ $itemDet->diskon }} 
                </td>
                <td rowspan="2" style="width: 60px" align="right">
                  {{ number_format($itemDet->diskonRp, 0, "", ".") }}
                </td>
                <td rowspan="2" align="right">
                  {{ number_format((($itemDet->qty * $itemDet->harga) - $itemDet->diskonRp), 0, "", ".") }}</td>
              </tr>
              <tr class="">
                <td style="width: 70px; border-top: none !important; margin-top: -8px !important;" align="right">({{ $diskon }}%)</td>
              </tr>
              @php $i++ @endphp
            @endforeach
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
                <td style="border-right: dotted; width: 87px"> 
                  <div class="ttd-penerima text-center">
                    <span>Penerima,</span>
                    <br><br><br>
                    <span class="form-ttd">(___________)</span>
                  </div>
                </td>
                <td style="border-right: dotted; width: 253px">
                  <div class="info_bayar">
                    <span>Pembayaran Giro / Transfer</span>
                    <br>
                    <span>Rekening Bank BCA</span>
                    <br>
                    <span>a/n Irianti Irawan 0911276444</span>
                    {{-- <span>Waktu Cetak : </span>
                    <br>
                    <span>{{ $today }} - {{ $waktu }}</span>
                    <br>
                    <span>Cetak ke : {{ $cetak }}</span> --}}
                  </div>
                </td>
                <td style="width: 85px">
                  <div class="ttd-gudang">
                    <center><span class="nama-gudang">Gudang,</span></center>
                    <br><br>
                    <span class="form-ttd">(___________)</span>
                  </div>
                </td>
                <td style="border-right: dotted; width: 80px">
                  <div class="ttd-mengetahui">
                    <span class="tgl-ttd">
                      {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y')}}
                    </span>
                    <span>Mengetahui,</span> 
                    <br><br><br><br>
                    <span class="form-ttd">(__________)</span>
                  </div>
                </td>
                <td>
                  <div class="total-faktur">
                    <table class="tabel-total-faktur">
                      <tr>
                        <td class="title-total text-bold">Jumlah</td>
                        <td class="text-right angka-total">{{ number_format($item->total, 0, "", ".") }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Disc Faktur</td>
                        <td class="text-right angka-total">0</td>
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
                        <td colspan="2" style="height: 2px"></td>
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
