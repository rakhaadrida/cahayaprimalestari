<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Data Penjualan Extrana</h2>
      <h3 class="waktu-cetak">Bulan : {{$bulan}}</h3>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>

    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td>No</td>
          <td>Sales</td>
          <td>Customer</td>
          <td>No Faktur</td>
          <td>Tgl Faktur</td>
          <td>Nama Barang</td>
          <td>Qty</td>
          <td>Harga</td>
          <td>Total</td>
          <td>Diskon</td>
          <td>Netto</td>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($items as $item)
          @php
            $tanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $item->so->tgl_so);
          @endphp
          <tr class="text-dark">
            <td align="center">{{ $i }}</td>
            <td align="center">{{ $item->sales }}</td>
            <td>{{ $item->cust }}</td>
            <td align="center">{{ $item->id_so }}</td>
            {{-- <td align="center">{{ \Carbon\Carbon::parse($item->so->tgl_so)->isoFormat('DD-MMM-YY') }}</td> --}}
            <td align="center">{{ $awal->diffInDays($tanggal) }}</td>
            <td>{{ $item->barang->nama }}</td>
            <td align="right">{{ $item->qty }}</td>
            <td align="right">{{ $item->harga }}</td>
            <td align="right">{{ $item->qty * $item->harga }}</td>
            <td align="right">{{ $item->diskonRp }}</td>
            <td align="right">{{ $item->qty * $item->harga - $item->diskonRp }}</td>
          </tr>
          @php $i++ @endphp
        @endforeach
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun != $sejak) - {{$tahun}} @endif | rakhaadrida</h4>
  </body>
</html>
