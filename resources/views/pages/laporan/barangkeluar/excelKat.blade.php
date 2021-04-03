<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Barang Keluar {{ $nama }}</h2>
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
            <th>No. Faktur</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Diskon</th>
            <th>Nominal Diskon</th>
            <th>Netto</th>
          </tr>
        </thead>
        <tbody>
          @php 
            $j = 1; $totalQty = 0; $totalDiskon = 0;
            $barang = \App\Models\DetilSO::join('barang', 'barang.id', 'detilso.id_barang')
                      ->join('so', 'so.id', 'detilso.id_so')
                      ->select('id_so as id', 'detilso.*')
                      ->selectRaw('sum(qty) as qty, sum(diskonRp) as diskonRp')
                      ->where('id_kategori', $id)
                      ->whereNotIn('so.status', ['BATAL', 'LIMIT'])
                      ->whereBetween('tgl_so', [$tglAwal, $tglAkhir])
                      ->groupBy('id_so', 'id_barang')->orderBy('id_barang')->orderBy('id_so')->get();
          @endphp
          @foreach($barang as $i)
            <tr>
              <td align="center">{{ $j }}</td>
              <td>{{ $i->barang->nama }}</td>
              <td align="center">{{ $i->id }}</td>
              <td align="right">{{ $i->qty }}</td>
              <td align="right">{{ $i->harga }}</td>
              <td align="right">{{ $i->qty * $i->harga }}</td>
              <td align="right">{{ $i->diskon }}</td>
              <td align="right">{{ $i->diskonRp }}</td>
              <td align="right">{{ $i->qty * $i->harga - $i->diskonRp }}</td>
            </tr>
            @php $j++; @endphp
          @endforeach
          <tr>
            <td colspan="3">Total</td>
            <td align="right"></td>
            <td></td>
            <td align="right"></td>
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
