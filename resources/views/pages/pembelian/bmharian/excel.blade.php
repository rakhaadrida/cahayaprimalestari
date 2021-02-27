<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Barang Masuk Harian</h2>
      <h5 class="waktu-cetak">Tanggal : {{$awal}} s/d {{$akhir}}</h5>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <th>No</th>
          <th>Supplier</th>
          <th>Gudang</th>
          <th>No. Faktur</th>
          <th>Tanggal</th>
          <th>Tempo</th>
          <th>Total</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($items as $item)
          <tr class="text-dark">
            <td align="center">{{ $i }}</td>
            <td>{{ $item->supplier->nama }}</td>
            <td align="center">{{ $item->gudang->nama }}</td>
            <td align="center">{{ $item->id_faktur }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}</td>
            <td align="center">
              {{ \Carbon\Carbon::parse($item->tanggal)->add($item->tempo, 'days')
                ->format('d-M-y') }}
            </td>
            <td align="right">{{ number_format($item->total, 0, "", ",") }}</td>
            <td></td>
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
