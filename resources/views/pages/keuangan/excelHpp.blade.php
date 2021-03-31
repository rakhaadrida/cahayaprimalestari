<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Data HPP {{ $nama }} Bulan {{ $bul }}</h2>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    {{-- @foreach($kategori as $k) --}}
      <table class="table table-sm table-bordered table-cetak">
        <thead>
          <tr style="font-size: 14px">
            <th>No</th>
            {{-- <th>Nama Barang {{ $k->barang->jenis->nama }}</th> --}}
            <th>Nama Barang</th>
            <th>Qty Faktur</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Diskon</th>
            <th>Nominal Diskon</th>
            <th>Harga Pokok</th>
          </tr>
        </thead>
        <tbody>
          @php 
            $j = 1; $totalQty = 0; $totalHpp = 0; $subtotal = 0; $totalDiskon = 0;
            $barang = \App\Models\DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                      ->join('so', 'so.id', 'detilso.id_so')
                      ->select('id_so as id', 'detilso.*')->selectRaw('sum(qty) as qty')
                      // ->where('id_sales', $id)->where('qty', '!=', 0)
                      // ->where('id_kategori', $k->id_kategori)
                      ->where('id_kategori', $id)->where('qty', '!=', 0)
                      ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                      ->whereYear('so.tgl_so', $tah)
                      ->whereMonth('so.tgl_so', $bulan)
                      // ->groupBy('id_barang', 'harga', 'diskon')
                      ->groupBy('id_barang', 'harga')
                      ->get();
          @endphp
          @foreach($barang as $i)
            <tr>
              <td align="center">{{ $j }}</td>
              <td>{{ $i->barang->nama }}</td>
              <td align="right">{{ $i->qty }}</td>
              <td align="right">{{ $i->harga }}</td>
              @php 
                $total = $i->qty * $i->harga;
              @endphp
              <td align="right">{{ $total }}</td>
              <td align="right"></td>
              <td align="right"></td>
              @php $totalQty += $i->qty; @endphp
              <td align="right"></td>
            </tr>
            @php $j++; $subtotal += $total; @endphp
          @endforeach
          <tr>
            <td colspan="2">Total</td>
            <td align="right">{{ $totalQty }}</td>
            <td></td>
            <td align="right">{{ $subtotal }}</td>
            <td></td>
            <td align="right"></td>
            <td align="right"></td>
          </tr>
        </tbody>
      </table>
      <br>
    {{-- @endforeach --}}
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
