<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Kartu Stok (Good Stock)</h2>
      <h3 class="text-dark">
        Kode Barang : {{$rowBM[0]->barang->id}}
      </h3>
      <h5 class="waktu-cetak">Tanggal : {{\Carbon\Carbon::parse($awal)->format('d-m-Y')}} s/d {{\Carbon\Carbon::parse($akhir)->format('d-m-Y')}}</h5>
    </center>
    <br>

    <h5>Kode Barang : {{ $rowBM[0]->barang->id }} - {{ $rowBM[0]->barang->nama }}</h5>
    <h5>Ukuran : {{ $rowBM[0]->barang->ukuran }}  {{ $rowBM[0]->barang->satuan }} </h5>

    <!-- Tabel Data Detil PO -->
    <table class="table table-sm table-bordered table-striped table-responsive-sm">
      <thead class="text-center text-bold text-dark">
        <tr>
          <td rowspan="2">No</td>
          <td rowspan="2">Tanggal</td>
          <td rowspan="2">Tipe Transaksi</td>
          <td rowspan="2">Nomor Transaksi</td>
          <td rowspan="2">Keterangan</td>
          <td colspan="3">Pemasukan</td>
          <td colspan="3">Pengeluaran</td>
          <td rowspan="2">Pemakai</td>
          <td rowspan="2">Waktu</td>
        </tr>
        <tr>
          <td>Pack</td>
          <td>Pcs</td>
          <td>Rupiah</td>
          <td>Pack</td>
          <td>Pcs</td>
          <td>Rupiah</td>
        </tr>
      </thead>
      <tbody>
        @if(($rowBM->count() != 0) || ($rowSO->count() != 0))
          <tr>
            <td colspan="5" class="text-bold text-dark text-center">Stok Awal</td>
            <td class="text-bold text-dark text-right">{{ $stokAwal }}</td>
            <td colspan="7"></td>
          </tr>
          @php 
            $i = 1; $totalBM = 0; $totalSO = 0;
          @endphp
          @foreach($rowBM as $ib)
            <tr class="text-bold">
              <td align="center">{{ $i }}</td>
              <td align="center">
                {{ \Carbon\Carbon::parse($ib->bm->tanggal)->format('d-m-Y') }} 
              </td>
              <td>Barang Masuk</td>
              <td>{{ $ib->bm->id }}</td>
              <td>{{ $ib->bm->supplier->nama }}</td>
              <td align="right">{{ $ib->qty }}</td>
              <td align="right"></td>
              <td align="right">
                {{ number_format($ib->qty * $ib->harga, 0, "", ",") }}
              </td>
              <td align="right"></td>
              <td align="right"></td>
              <td align="right"></td>
              <td align="right"></td>
              <td align="left">{{ \Carbon\Carbon::parse($ib->created_at)->format('d-m-Y H:i:s') }}</td>
              @php $totalBM += $ib->qty @endphp
            </tr>
            @php $i++; @endphp
          @endforeach
          @foreach($rowSO as $is)
            <tr class="text-bold">
              <td align="center">{{ $i }}</td>
              <td align="center">{{ \Carbon\Carbon::parse($is->so->tgl_so)->format('d-m-Y') }} </td>
              <td>Penjualan Barang</td>
              <td>{{ $is->so->id }}</td>
              <td>{{ $is->so->customer->nama }}</td>
              <td align="right"></td>
              <td align="right"></td>
              <td align="right"></td>
              <td align="right">{{ $is->qty }}</td>
              <td align="right"></td>
              <td align="right">
                {{ number_format($is->qty * $is->harga, 0, "", ",") }}
              </td>
              <td align="right"></td>
              <td align="left">{{ \Carbon\Carbon::parse($is->created_at)->format('d-m-Y H:i:s') }}</td>
              @php $totalSO += $is->qty @endphp
            </tr>
            @php $i++; @endphp
          @endforeach
          <tr>
            <td colspan="5" class="text-bold text-dark text-center">Total</td>
            <td class="text-bold text-dark text-right">
              {{ $stokAwal + $totalBM }}
            </td>
            <td colspan="2"></td>
            <td class="text-bold text-dark text-right">{{ $totalSO }}</td>
            <td colspan="4"></td>
          </tr>
          <tr style="background-color: yellow">
            <td colspan="5" class="text-bold text-dark text-center">Stok Akhir</td>
            <td class="text-bold text-dark text-right">{{ $stok[0]->total }}</td>
            <td colspan="7"></td>
          </tr>
        @else 
          <tr>
            <td colspan="12" class="text-center text-bold h4 p-2"><i>Tidak ada transaksi untuk kode dan tanggal tersebut</i></td>
          </tr>
        @endif
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
