<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet">
  </head>
  <body>
    <center>
      <h5 class="text-bold text-dark">FAKTUR PENJUALAN</h5>
      <h5 class="text-bold text-dark" style="margin-top: -10px">(CASH)</h5>
    </center>
    <center>
      <div class="subtitle-cetak-so-one">
        <span class="text-right">Nomor</span>
        <span>:</span>
        <span class="text-bold">{{ $items[0]->id_so }}</span>
      </div>
      <div class="subtitle-cetak-so-second">
        <span class="text-right">Tanggal</span>
        <span>:</span>
        <span class="text-bold">{{ \Carbon\Carbon::parse($items[0]->so->tgl_so)->format('d-m-Y') }}</span>
      </div>
    </center>
    <div class="float-right customer-cetak-so">
      <span>Kepada Yth :</span>
      <span>{{ $items[0]->so->id_customer }}</span>
    </div>
    <br>
    <br>

    <table class="table table-sm table-responsive-sm table-hover table-cetak">
      <thead class="text-center text-bold text-dark" style="border: dotted">
        <tr>
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
        <tr>
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
    <table class="table table-sm table-striped table-responsive-sm table-hover table-cetak" style="margin-top: -10px">
      <thead class="text-center text-dark text-bold">
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
      <tbody id="tablePO">
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

    
  </body>
</html>
