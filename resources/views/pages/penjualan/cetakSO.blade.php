<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet">
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
      <span>{{ $items[0]->so->customer->telepon }}</span>
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
    <table class="table table-sm table-responsive-sm table-hover table-cetak" style="margin-top: -10px">
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
      <table>
        <thead>
          <tr>
            <td>
              <div class="ttd-penerima">
                <span>Penerima,</span>
                <br><br><br><br>
                <span class="form-ttd">(_________)</span>
              </div>
            </td>
            <td>
              <div class="info_bayar">
                <span>Pembayaran Giro / Transfer</span>
                <br>
                <span>Rekening Bank BCA</span>
                <br>
                <span>a/n Irianti Irawan 0912227644</span>
              </div>
            </td>
            <td>
              <div class="ttd-gudang">
                <span>Gudang,</span>
                <br><br><br>
                <span class="form-ttd">(_________)</span>
              </div>
            </td>
            <td>
              <div class="ttd-mengetahui">
                <span class="tgl-ttd">
                  {{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-m-Y')}}
                </span>
                <br>
                <span>Mengetahui,</span>
                <br><br><br>
                <span class="form-ttd">(_________)</span>
              </div>
            </td>
            <td>
              <div class="total-faktur">
                <table class="tabel-total-faktur">
                  <tr>
                    <td style="width: 180px">Jumlah</td>
                    <td class="text-right angka-total">
                      {{ number_format($items[0]->so->total, 0, "", ".") }}
                    </td>
                  </tr>
                  <tr>
                    <td style="width: 180px">Disc Faktur</td>
                    <td class="text-right angka-total">0</td>
                  </tr>
                  <tr>
                    <td style="width: 180px">Nilai Netto</td>
                    <td class="text-right angka-total">
                      {{ number_format($items[0]->so->total, 0, "", ".") }}
                    </td>
                  </tr>
                  <tr>
                    <td style="width: 180px">PPN</td>
                    <td class="text-right angka-total"></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td style="width: 180px">Nilai Tagihan</td>
                    <td class="text-right angka-total">
                      {{ number_format($items[0]->so->total, 0, "", ".") }}
                    </td>
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
