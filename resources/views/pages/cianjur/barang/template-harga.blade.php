<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Template Import Harga Barang</h2>
      <h2></h2>
    </center>
    <br>
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <th>No</th>
          <th>Kode_barang</th>
          <th>Nama_barang</th>
          <th>Harga_saat_ini</th>
          <th>Harga_baru</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $key => $item)
          <tr class="text-dark">
            <td align="center">{{ ++$key }}</td>
            <td align="center">{{ $item->id }}</td>
            <td>{{ $item->nama }}</td>
            <td align="right">{{ $item->harga }}</td>
            <td align="right"></td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <br>
  </body>
</html>
