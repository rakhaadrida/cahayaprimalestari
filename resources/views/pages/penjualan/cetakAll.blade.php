<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="backend/css/sb-admin-2.css" rel="stylesheet">
    <link href="backend/css/main.css" rel="stylesheet">
  </head>
  <body>
    @foreach($items as $item)
      <div class="cetak-all-container" @if($items[$items->count()-1]->id != $item->id) style="page-break-after: always" @endif>
        <div class="container-fluid header-cetak-so">
          <div class="title-header text-center">
            <h5 class="text-bold text-dark">FAKTUR PENJUALAN</h5>
            <h5 class="text-bold text-dark" style="margin-top: -10px">(CASH)</h5>
          </div>
          <div class="subtitle-cetak-so-one text-center">
            <span class="text-right">Nomor</span>
            <span>:</span>
            <span class="text-bold">{{ $item->id }}</span>
          </div>
          <div class="subtitle-cetak-so-second text-center">
            <span class="text-right">Tanggal</span>
            <span>:</span>
            <span class="text-bold">{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-m-Y') }}</span>
          </div>
        </div>
        <div class="float-right customer-cetak-so">
          <span class="kode-cetak-so">Kepada Yth :</span>
          <span>{{ $item->id_customer }}</span>
          <br>
          <span class="nama-cetak-so">{{ $item->customer->nama }}</span>
          <br>
          <span class="alamat-cetak-so text-wrap">{{ $item->customer->alamat }}</span>
          <br>
          <span>{{ $item->customer->telepon }}</span>
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
                {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-m-Y') }}
              </td>
              <td align="center" style="border: dotted">0 Hari</td>
              <td align="center" style="border: dotted">
                {{ \Carbon\Carbon::parse($item->tgl_so)->add($item->tempo, 'days')->format('d-m-Y') }}
              </td>
              <td align="center" style="border: dotted">{{ $item->customer->sales->nama }}</td>
              <td align="center" style="border: dotted">{{ Auth::user()->name }}</td>
            </tr>
          </tbody>
        </table>
        
        @php $itemsDet = \App\Models\DetilSO::with(['barang'])->where('id_so', $item->id)->get();
        @endphp
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
            @foreach($itemsDet as $itemDet)
              <tr class="text-dark ">
                <td align="center">{{ $i }}</td>
                <td>{{ $itemDet->id_barang }}</td>
                <td>{{ $itemDet->barang->nama }}</td>
                <td align="right">{{ $itemDet->qty }}</td>
                <td align="right">{{ number_format($itemDet->harga, 0, "", ".") }}</td>
                <td align="right">{{ number_format($itemDet->qty * $itemDet->harga, 0, "", ".") }}</td>
                @php 
                  $diskon = 100;
                  $arrDiskon = explode("+", $itemDet->diskon);
                  for($j = 0; $j < sizeof($arrDiskon); $j++) {
                    $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                  } 
                  $diskon = number_format((($diskon - 100) * -1), 2, ".", "");
                @endphp
                <td style="width: 50px" align="right">{{ str_replace(".", ",", $diskon) }} %</td>
                <td style="width: 80px" align="right">{{ number_format(($itemDet->qty * $itemDet->harga) * $diskon / 100, 0, "", ".") }}</td>
                <td align="right">{{ number_format((($itemDet->qty * $itemDet->harga) - (($itemDet->qty * $itemDet->harga) * $diskon / 100)), 0, "", ".") }}</td>
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
                      {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-m-Y')}}
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
                        <td class="title-total">Jumlah</td>
                        <td class="text-right angka-total">{{ number_format($item->total, 0, "", ".") }}</td>
                      </tr>
                      <tr>
                        <td class="title-total">Disc Faktur</td>
                        <td class="text-right angka-total">0</td>
                      </tr>
                      <tr>
                        <td class="title-total">Nilai Netto</td>
                        <td class="text-right angka-total">{{ number_format($item->total, 0, "", ".") }}</td>
                      </tr>
                      <tr>
                        <td class="title-total">PPN</td>
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
                        <td class="title-total">Nilai Tagihan</td>
                        <td class="text-right angka-total">{{ number_format($item->total, 0, "", ".") }}</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    @endforeach
  </body>
</html>
