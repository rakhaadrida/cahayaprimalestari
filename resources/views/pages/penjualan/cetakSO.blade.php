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
          margin-left: 7px;
          font-size: 12px;
      }

      .customer-cetak-so {
          font-size: 12px;
          width: 220px;
          margin-top: -25px;
          margin-right: 30px;
      }

      .nama-cetak-so {
          margin-top: 10px;
          margin-bottom: 10px;
          font-size: 14px;
      }

      .alamat-cetak-so {
          margin-bottom: -10px;
          line-height: 15px;
      }

      .telepon-cetak-so {
          /* margin-bottom: -15px; */
          line-height: 13px;
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
          line-height: 5px;
      }

      .tr-info-cetak-so {
          line-height: 10px;
      }

      .table-cetak {
          font-size: 11px;
          height: 200px;
          margin-left: 31px;
          margin-right: 31px;
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
          font-size: 12px;
          padding-left: -5px;
          margin-bottom: 15px;
      }

      .info_bayar {
          margin-top: -20px;
          margin-left: 5px;
          margin-right: 30px;
          font-size: 12px;
          line-height: 24px;
      }

      .ttd-gudang {
          font-size: 12px;
          margin-top: 1px;
          margin-left: 2px;
      }

      .ttd-mengetahui {
          font-size: 12px;
          margin-top: -7px;
          line-height: 13px;
      }

      .tgl-ttd {
          font-size: 11px;
      }

      .total-faktur {
          margin-top: -3px;
          margin-left: 5px;
          font-size: 12px;
      }

      .tabel-total-faktur {
          line-height: 16px;
      }

      .title-total {
          width: 170px;
      }

      .angka-total {
          font-size: 14px;
      }
    </style>
  </head>
  <body>
    <div class="container-fluid header-cetak-so">
      <div class="title-header text-center">
        <h5 class="text-bold text-dark">FAKTUR PENJUALAN</h5>
        <h5 class="text-bold text-dark" style="margin-top: -10px">(CASH)</h5>
      </div>
      <div class="subtitle-cetak-so-one text-center">
        <span class="text-right">Nomor</span>
        <span>:</span>
        <span class="text-bold">{{ $items[0]->id_so }}</span>
      </div>
      <div class="subtitle-cetak-so-second text-center">
        <span class="text-right">Tanggal</span>
        <span>:</span>
        <span class="text-bold">{{ \Carbon\Carbon::parse($items[0]->so->tgl_so)->format('d-m-Y') }}</span>
      </div>
    </div>
    <div class="float-right customer-cetak-so">
      <span class="kode-cetak-so">Kepada Yth :</span>
      <span>{{ $items[0]->so->id_customer }}</span>
      <br>
      <span class="nama-cetak-so">{{ $items[0]->so->customer->nama }}</span>
      <br>
      <span class="alamat-cetak-so text-wrap">{{ $items[0]->so->customer->alamat }}</span>
      <br>
      <span class="telepon-cetak-so">{{ $items[0]->so->customer->telepon }}</span>
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
        @foreach($items as $item)
        <tr class="tr-info-cetak-so">
          <td align="center" style="border: dotted">{{ $item->id_so }}</td>
          <td align="center" style="border: dotted">
            {{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-m-Y') }}
          </td>
          <td align="center" style="border: dotted">0 Hari</td>
          <td align="center" style="border: dotted">
            {{ \Carbon\Carbon::parse($item->so->tgl_so)->add($item->so->tempo, 'days')->format('d-m-Y') }}
          </td>
          <td align="center" style="border: dotted">{{ $item->so->customer->sales->nama }}</td>
          <td align="center" style="border: dotted">Admin</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    
    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-responsive-sm table-hover table-cetak">
      <thead class="text-center text-bold th-detail-cetak-so">
        <tr>
          <td style="width: 30px">No</td>
          <td style="width: 70px">Kode</td>
          <td>Nama Barang</td>
          <td style="width: 50px">Qty</td>
          <td style="width: 80px">Harga</td>
          <td style="width: 80px">Rupiah</td>
          <td colspan="2">Diskon</td>
          <td style="width: 80px">Netto Rp</td>
        </tr>
      </thead>
      <tbody class="tr-detail-cetak-so">
        @php $i = 1; @endphp
        @foreach($items as $item)
          <tr class="text-dark ">
            <td align="center">{{ $i }}</td>
            <td>{{ $item->id_barang }}</td>
            <td>{{ $item->barang->nama }}</td>
            <td align="right">{{ $item->qty }}</td>
            <td align="right">{{ number_format($item->harga, 0, "", ".") }}</td>
            <td align="right">{{ number_format($item->qty * $item->harga, 0, "", ".") }}</td>
            <td style="width: 40px" align="right">{{ $item->diskon}} %</td>
            <td style="width: 80px" align="right">{{ number_format(($item->qty * $item->harga) * $item->diskon / 100, 0, "", ".") }}</td>
            <td align="right">{{ number_format((($item->qty * $item->harga) - (($item->qty * $item->harga) * $item->diskon / 100)), 0, "", ".") }}</td>
          </tr>
          @php $i++ @endphp
        @endforeach
      </tbody>
    </table>
    
    <div class="container-fluid footer-cetak-so">
      <table class="table-footer">
        <thead>
          <tr>
            <td style="border-right: dotted; width: 87px"> 
              <div class="ttd-penerima text-center">
                <span>Penerima,</span>
                <br><br><br><br>
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
              </div>
            </td>
            <td style="width: 85px">
              <div class="ttd-gudang">
                <center><span>Gudang,</span></center>
                <br><br>
                <span class="form-ttd">(___________)</span>
              </div>
            </td>
            <td style="border-right: dotted; width: 80px">
              <div class="ttd-mengetahui">
                <span class="tgl-ttd">
                  {{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-m-Y')}}
                </span>
                <br>
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
                    <td class="text-right angka-total">{{ number_format($items[0]->so->total, 0, "", ".") }}</td>
                  </tr>
                  <tr>
                    <td class="title-total text-bold">Disc Faktur</td>
                    <td class="text-right angka-total">0</td>
                  </tr>
                  <tr>
                    <td class="title-total text-bold">Nilai Netto</td>
                    <td class="text-right angka-total">{{ number_format($items[0]->so->total, 0, "", ".") }}</td>
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
                    <td class="title-total"></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td class="title-total text-bold">Nilai Tagihan</td>
                    <td class="text-right angka-total">{{ number_format($items[0]->so->total, 0, "", ".") }}</td>
                  </tr>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
        </thead>
      </table>
    </div>
  </body>
</html>
