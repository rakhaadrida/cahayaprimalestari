<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">PRICE LIST {{$nama}}</h2>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <td>No</td>
          <td>Nama Barang</td>
          <td>Price</td>
          <td>%</td>
          <td>Net</td>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($sub as $s)
          @php
            $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
          @endphp 
          <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
            <td colspan="5" align="center">{{ $s->nama }}</td>
          </tr>
          @foreach($barang as $b)
            @php
              $harga = \App\Models\HargaBarang::where('id_barang', $b->id)
                      ->where('id_harga', 'HRG01')->get();
            @endphp
            <tr class="text-dark ">
              <td align="center">{{ $i }}</td>
              <td>{{ $b->nama }}</td>
              <td align="right">{{ $harga->count() != 0 ? $harga[0]->harga_ppn : '' }}</td>
              <td align="right">1,1</td>
              <td align="right">{{ $harga->count() != 0 ? $harga[0]->harga : '' }}</td>
            </tr>
            @php $i++ @endphp
          @endforeach
        @endforeach
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
