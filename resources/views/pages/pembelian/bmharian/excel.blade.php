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
          <th>Tanggal</th>
          <th>No. Faktur</th>
          <th>Item Barang</th>
          <th>Qty</th>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($items as $item)
          @php
            $j = 1;
            $detil = \App\Models\DetilBM::where('id_bm', $item->id)->get();
          @endphp
          @foreach($detil as $d)
            <tr class="text-dark">
              <td align="center">{{ $j == 1 ? $i : '' }}</td>
              <td>{{ $j == 1 ? $item->supplier->nama : '' }}</td>
              <td align="center">{{ $j == 1 ? $item->gudang->nama : '' }}</td>
              <td align="center">{{ $j == 1 ? \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') : '' }}</td>
              <td align="center">{{ $j == 1 ? $item->id_faktur : '' }}</td>
              <td>{{ $d->barang->nama }}</td>
              <td align="right">{{ $d->qty }}</td>
            </tr> 
            @php $j++; @endphp
          @endforeach
          @php $i++ @endphp
        @endforeach
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
