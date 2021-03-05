<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Data Master Barang</h2>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>Kategori</th>
          <th>Sub Kategori</th>
          <th>Satuan</th>
          <th>Ukuran</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($items as $item)
          <tr class="text-dark">
            <td align="center">{{ $i }}</td>
            <td align="center">{{ $item->id }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->jenis->nama }}</td>
            <td>{{ $item->subjenis->nama }}</td>
            <td align="left">{{ $item->satuan }}</td>
            <td align="right">{{ $item->ukuran }}</td>
            <td align="center">{{ $item->deleted_at == NULL ? 'Aktif' : 'Tidak Aktif' }}</td>
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
