<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">REKAP FAKTUR BATAL</h2>
      <h5 class="waktu-cetak">Tanggal : {{ $startDate }} s/d {{ $endDate }}</h5>
      <h5 class="waktu-cetak">Waktu Cetak : {{ $exportDate }}</h5>
    </center>
    <br>
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td>No</td>
          <td>Tgl Faktur</td>
          <td>No Faktur</td>
          <td>Tgl Approval</td>
          <td>Customer</td>
          <td>Total</td>
          <td>Status</td>
          <td>Keterangan</td>
        </tr>
      </thead>
      <tbody id="tablePO">
        @foreach($items as $index => $item)
          <tr class="text-dark ">
            <td align="center">{{ ++$index }}</td>
            <td align="center">{{ $beginDate->diffInDays($item->so_date) }}</td>
            <td align="center">{{ $item->id }}</td>
            <td align="center">{{ $beginDate->diffInDays($item->approval_date) }}</td>
            <td>{{ $item->customer_name }}</td>
            <td align="right">{{ $item->subtotal }}</td>
            <td align="center">{{ $item->status }}</td>
            <td>{{ $item->description }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <br>
    <h4>Copyright &copy; {{$since}} @if($year->year != $since) - {{$year->year}} @endif | rakhaadrida</h4>
  </body>
</html>
