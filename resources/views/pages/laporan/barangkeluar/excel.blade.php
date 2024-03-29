<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">REKAP BARANG KELUAR</h2>
      <h3 class="text-bold text-dark">Tanggal : {{ $tanggal }}</h3>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>

    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td>No</td>
          <td>Bulan</td>
          <td>Tgl Faktur</td>
          <td>No Faktur</td>
          <td>Sales</td>
          <td>Customer</td>
          <td>Nama Barang</td>
          <td>Gudang</td>
          <td>Qty</td>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($items as $item)
          @php
            $tanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $item->tgl_so);
          @endphp
          <tr class="text-dark ">
            <td align="center">{{ $i }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($item->tgl_so)->isoFormat('MMMM') }}</td>
            <td align="center">{{ $awal->diffInDays($tanggal) }}</td>
            <td align="center">{{ $item->id_so }}</td>
            <td align="center">{{ $item->namaSales }}</td>
            <td>{{ $item->namaCustomer }}</td>
            <td>{{ $item->namaBarang }}</td>
            <td align="center">{{ $item->namaGudang }}</td>
            <td align="right">{{ $item->qty }}</td>
          </tr>
          @php $i++ @endphp
        @endforeach
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
