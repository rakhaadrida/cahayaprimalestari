{{-- <!DOCTYPE html> --}}
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <style>
      body {
          width: 914.7px;
          height: 520px;
          font-family: Arial, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
          font-size: 1.2rem;
          font-weight: 700;
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
          font-size: 1.15rem;
      }

      .container-fluid {
          width: 87.29%;
          padding-right: 0.75rem;
          padding-left: 0.75rem;
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
          margin-left: 0;
          margin-right: 0;
      }

      .title-header {
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
      }

      .subtitle-cetak-so-one {
        font-family: 'Courier New', Courier, monospace;
        color: black;
        margin-top: -5px;
        font-size: 15px;
        font-weight: normal;
      }

      .subtitle-cetak-so-second {
        font-family: 'Courier New', Courier, monospace;
        color: black;
        margin-left: -9px;
        margin-top: -2px;
        font-size: 15px;
        font-weight: normal;
        letter-spacing: 1px;
      }

      .sub-title {
        font-family: 'Calibri', Helvetica, sans-serif;
      }

      .logo-cetak-so {
          display: inline-block;
          color: black;
          border: 1.4px dotted;
          border-bottom: none;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          border-left: 1px solid black;
          border-right: 1px solid black;
          margin-right: 0;
      }

      .logo-cetak-so h2 {
          text-decoration: underline black;
      }

      .logo-cetak-so h3 {
        margin-top: -8px;
          font-size: 20px;
      }

      .logo-cetak-so h4 {
          margin-top: -8px;
      }

      .logo-cetak-so span {
          font-weight: normal;
          font-size: 17px;
      }

      .logo-cetak-so .subtitle-one {
          color: black;
          font-size: 15px;
          font-weight: normal;
      }

      .logo-cetak-so .subtitle-one .sub-title-dot {
          margin-left: 36px;
      }

      .logo-cetak-so .subtitle-second {
          color: black;
          margin-top: -3px;
          margin-bottom: 5px;
          font-size: 15px;
          font-weight: normal;
      }

      .customer-cetak-so {
        color: black;
        font-weight: 500;
        font-size: 17px;
        width: 410px;
        margin-top: -130px;
        margin-right: 95px;
          margin-bottom: -27px;
      }

      .kode-cetak-so {
        font-size: 12px;
      }

      .customer-cetak-so h4 {
        color: black;
      }

      .customer-cetak-so h5 {
        color: black;
          margin-top: -8px;
          font-size: 0.75rem;
          height: 19px;
      }

      .customer-cetak-so .subtitle-one {
          color: black;
          font-weight: normal;
          margin-top: 18px;
      }

      .customer-cetak-so .subtitle-second {
          color: black;
          margin-top: -7px;
          font-weight: normal;
      }

      .customer-cetak-so .subtitle-second .sub-title-dot {
          margin-left: 43px;
      }

      .table-info-cetak-so td {
        border: 1px dotted;
      }

      .table-info-cetak-so thead td {
        padding-bottom: 0.25rem !important;
      }

      .tr-info-cetak-so td{
        line-height: 9px;
      }

      .table-cetak {
        font-size: 14px;
        width: 90.1% !important;
        height: 52.5% !important;
        margin-right: 34.5px;
        margin-top: -13px;
      }

      .table-cetak thead td {
        padding-top: 0.3rem !important;
        padding-bottom: 0.4rem !important;
      }

      .th-detail-cetak-so td {
        line-height: 6px;
        border-top: 1px dotted;
        border-bottom: 1px dotted;
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
          border: 1px dotted;
          border-top-left-radius: 10px;
          border-top-right-radius: 10px;
          border-bottom-left-radius: 10px;
          border-bottom-right-radius: 10px;
          border-left: 1px solid black;
          border-right: 1px solid black;
          margin-bottom: -40px;
          margin-left: 0;
          margin-right: 30px;
          margin-top: -15px;
      }

      .table-footer {
          margin-left: -15px;
          width: 920px;
          margin-right: -50px;
          margin-bottom: 6px;
      }

      .ttd-penerima {
        font-size: 18px;
        padding-left: 25px;
        margin-bottom: 12px;
        /*margin-top: -20px;*/
          margin-top: 10px;
      }

      .ttd-gudang {
        font-size: 18px;
        /*margin-top: -35px;*/
          margin-top: -5px;
        margin-left: 35px;
        margin-bottom: -4px;
        line-height: 17px;
      }

      .ttd-mengetahui {
        font-size: 18px;
        /*margin-top: -37px;*/
          margin-top: -7px;
        margin-left: 45px;
        margin-bottom: -5px;
        line-height: 10px;
      }

      .total-faktur {
        margin-top: 0px;
        margin-left: 5px;
        font-size: 14px;
      }

      .tabel-total-faktur {
        line-height: 15px;
        margin-bottom: 3px;
          margin-top: 10px;
      }

      .title-total {
        font-size: 14px;
      }

      .angka-total {
        width: 175px;
        font-size: 15px;
        padding-right: 0.01rem !important;
      }

      .angka-total-akhir {
        width: 145px;
        font-size: 16px;
      }

      .waktu-cetak-so {
        font-weight: 500;
        margin-left: 30px;
        margin-top: 15px;
      }

      .waktu-cetak {
        font-size: 14px !important;
        margin-left: 115px;
      }

      .waktu-cetak-so-second {
          font-weight: 600;
          margin-left: 30px;
          margin-top: -5px;
      }

      .cetak-ke {
        margin-left: 117px;
        font-size: 14px !important;
      }

      @media print {
        @page {
          width: 21.8cm;
          height: 13.8cm;
          margin-top: 0.4002cm;
          margin-left: 0.281cm;
          margin-bottom: 0.144cm;
          margin-right: 1.27cm;
        }

        body {
          margin: 0;
          zoom: 1.37;
        }
      }
    </style>
  </head>
  <body>
    @php $i = 1; $no = 1; $kode = []; $subtotal = 0; @endphp
    @foreach($items as $item)
      <div class="cetak-all-container" style="margin-bottom: -55px; page-break-after: always;">

        <div class="container-fluid float-left logo-cetak-so">
          <h2 class="text-bold">INVOICE</h2>
          <h3 class="text-bold">MITRA ELEKTRIK</h3>
          <h4>Jakarta Pusat</h4>

            <div class="subtitle-one">
                <span class="sub-title">Tanggal</span>
                <span class="sub-title-dot">:</span>
                <span class="text-bold">{{ \Carbon\Carbon::parse($items->first()->tgl_so)->format('d-M-y') }}</span>
            </div>
            <div class="subtitle-second">
                <span class="sub-title">Jatuh Tempo</span>
                <span>:</span>
                <span class="text-bold">{{ \Carbon\Carbon::parse($items->first()->tgl_so)->add($items->first()->tempo, 'days')->format('d-M-y') }}</span>
            </div>
        </div>
        <div class="float-right customer-cetak-so">
          <span class="kode-cetak-so">Kepada Yth :</span>
          <br>
          <h4 class="text-bold">{{ $items->first()->customer->nama }}</h4>
          <h5 class="text-wrap">{{ substr($items->first()->customer->alamat, 0, 96) }}</h5>

            <div class="subtitle-one">
                <span class="sub-title">No. Invoice</span>
                <span>:</span>
                <span>{{ $items->first()->id }}</span>
            </div>
            <div class="subtitle-second">
                <span class="sub-title">Sales</span>
                <span class="sub-title-dot">:</span>
                <span>{{ $items->first()->sales->nama }}</span>
            </div>
          <br>
        </div>
        <br>
        <br>

        @php
        // sum(diskonRp) as diskonRp
        $itemsDet = \App\Models\DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                          ->select('id_barang', 'nama', 'satuan', 'diskon', 'diskonRp')
                          ->selectRaw('avg(harga) as harga, sum(qty) as qty')
                          ->where('id_so', $items->first()->id)
                          ->whereNotIn('id_barang', $kode)
                          ->groupBy('id_barang', 'diskon')
                          ->get();
        @endphp
        <!-- Tabel Data Detil BM-->
        <table class="table table-sm table-responsive-sm table-cetak" style="page-break-inside: auto">
          <thead class="text-center th-detail-cetak-so">
            <tr>
              <td style="width: 10px; border-left: 1px dotted">No</td>
              <td style="width: 340px">Nama Barang</td>
              <td style="width: 75px">Qty</td>
              {{-- <td style="width: 65px" class="text-right">Qty</td> --}}
              {{-- <td style="width: 80px"></td> --}}
              <td style="width: 50px">Harga</td>
              <td style="width: 90px">Total</td>
              <td colspan="2">Diskon</td>
              <td style="width: 80px; border-right: 1px dotted">Netto Rp</td>
            </tr>
          </thead>
          <tbody class="tr-detail-cetak-so">
            @php $cek = 0; @endphp
            @foreach($itemsDet as $itemDet)
              <tr class="baris-so">
                <td align="center">{{ $no }}</td>
                <td>{{ $itemDet->nama }}</td>
                @if($itemDet->satuan == "Pcs / Dus")
                  <td align="center">{{ $itemDet->qty }} PCS</td>
                @elseif($itemDet->satuan == "Set")
                  <td align="center">{{ $itemDet->qty }} SET</td>
                @elseif($itemDet->satuan == "Meter / Rol")
                  <td align="center">{{ $itemDet->qty }} ROL</td>
                @else
                  <td align="center">{{ $itemDet->qty }} MTR</td>
                @endif
                <td align="right">{{ number_format($itemDet->harga, 0, "", ".") }}</td>
                <td align="right">{{ number_format($itemDet->qty * $itemDet->harga, 0, "", ".") }}</td>
                @php
                  $diskon = 100;
                  $itemDet->diskon = str_replace(",", ".", $itemDet->diskon);
                  $arrDiskon = explode("+", $itemDet->diskon);
                  for($j = 0; $j < sizeof($arrDiskon); $j++) {
                    $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                  }
                  $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                @endphp
                <td style="width: 55px; font-size: 14.5px" align="right">
                  {{ $diskon }}%
                </td>
                <td style="width: 65px" align="right">
                  {{ number_format($itemDet->diskonRp, 0, "", ".") }}
                </td>
                <td align="right">
                  {{ number_format((($itemDet->qty * $itemDet->harga) - $itemDet->diskonRp), 0, "", ".") }}</td>
              </tr>
              @php $subtotal += ($itemDet->qty * $itemDet->harga) - $itemDet->diskonRp; $no++; array_push($kode, $itemDet->id_barang); @endphp
              @if($no > (12 * $i))
                @php $cek = 1; @endphp
                @break
              @endif
            @endforeach
            @if($itemsDet->count() < 12)
              <tr class="text-center">
                <td colspan="8"></td>
                {{-- <td></td>
                <td colspan="2"></td> --}}
              </tr>
            @endif
          </tbody>
        </table>

        @php
          $cetak = 1;
          if($items->first()->status != 'INPUT') {
            $ubah = App\Models\Approval::where('id_dokumen', $items->first()->id)->count();
            $cetak += $ubah;
          }
        @endphp

        <div class="container-fluid footer-cetak-so">
          <table class="table-footer">
            <thead>
              <tr>
                <td style="width: 170px">
                  <div class="ttd-penerima">
                    <table style="font-size: 15px !important;">
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
                <td style="width: 170px">
                  <div class="ttd-gudang">
                    <table style="font-size: 15px !important">
                      <tr>
                        <td class="text-center">Gudang,</td>
                      </tr>
                      <tr>
                        <td style="height: 37px"></td>
                      </tr>
                      <tr>
                        <td class="text-center">(___________)</td>
                      </tr>
                    </table>
                  </div>
                </td>
                <td style="width: 205px">
                  <div class="ttd-mengetahui">
                    <table style="font-size: 15px !important">
                      <tr>
                        <td class="text-center">Mengetahui,</td>
                      </tr>
                      <tr>
                        <td style="height: 37px"></td>
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
                        <td class="title-total text-bold">Jumlah</td>
                        {{-- <td class="text-right angka-total">{{ $itemsDet->count() <= 12 ? number_format($items->first()->total + $items->first()->diskon, 0, "", ".") : '' }}</td> --}}
                        <td class="text-right angka-total">{{ $itemsDet->count() <= 12 ? number_format($subtotal + $items->first()->diskon, 0, "", ".") : '' }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Disc Faktur</td>
                        <td class="text-right angka-total">{{ $itemsDet->count() <= 12 ? number_format($items->first()->diskon, 0, "", ".") : 'Bersambung' }}</td>
                      </tr>
                      <tr>
                        <td class="title-total text-bold">Nilai Netto</td>
                        {{-- <td class="text-right angka-total" @if($itemsDet->count() > 12) style="letter-spacing: 0.7px;" @endif>{{ $itemsDet->count() <= 12 ? number_format($items->first()->total, 0, "", ".") : 'ke halaman' }}</td> --}}
                        <td class="text-right angka-total" @if($itemsDet->count() > 12) style="letter-spacing: 0.7px;" @endif>{{ $itemsDet->count() <= 12 ? number_format($subtotal, 0, "", ".") : 'ke halaman' }}</td>
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
                        {{-- <td class="text-right angka-total-akhir">{{ $itemsDet->count() <= 12 ? number_format($items->first()->total, 0, "", ".") : '' }}</td> --}}
                        <td class="text-right angka-total-akhir">{{ $itemsDet->count() <= 12 ? number_format($subtotal, 0, "", ".") : '' }}</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </thead>
          </table>
        </div>
      </div>

{{--      <div class="waktu-cetak-so">--}}
{{--          <span class="waktu-cetak">Pembayaran ke Rekening Bank BCA</span>--}}
{{--      </div>--}}
{{--      <div class="waktu-cetak-so-second">--}}
{{--          <span class="cetak-ke">a/n Indah Ramadhon 5790416491</span>--}}
{{--      </div>--}}
    @endforeach

    <script type="text/javascript">
      window.onafterprint = function() {
        window.location = "{{ route('so-after-print', $id) }}";
      }

      window.print();
    </script>
  </body>

</html>
