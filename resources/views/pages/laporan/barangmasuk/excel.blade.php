<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">REKAP BARANG MASUK</h2>
      <h3 class="text-bold text-dark">Tanggal : {{ $tanggal }}</h3>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td>No</td>
          {{-- <td>Kode Barang</td> --}}
          <td>Supplier</td>
          <td>Nama Barang</td>
          <td>Gudang</td>
          <td>Qty</td>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($items as $item)
          <tr class="text-dark ">
            <td align="center">{{ $i }}</td>
            {{-- <td align="center">{{ $item->id_barang }}</td> --}}
            <td>{{ $item->bm->supplier->nama }}</td>
            <td>{{ $item->barang->nama }}</td>
            <td align="center">{{ $item->bm->gudang->nama }}</td>
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
