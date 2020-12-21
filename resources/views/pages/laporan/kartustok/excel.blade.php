<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Kartu Stok (Good Stock)</h2>
      <h3 class="text-dark">
        Kode Barang : {{$rowBM[0]->barang->id}}
      </h3>
      <h5 class="waktu-cetak">Tanggal : {{\Carbon\Carbon::parse($awal)->format('d-M-y')}} s/d {{\Carbon\Carbon::parse($akhir)->format('d-M-y')}}</h5>
    </center>
    <br>

    <h5>Kode Barang : {{ $rowBM[0]->barang->id }} - {{ $rowBM[0]->barang->nama }}</h5>
    <h5>Ukuran : {{ $rowBM[0]->barang->ukuran }}  {{ $rowBM[0]->barang->satuan }} </h5>

    <!-- Tabel Data Detil PO -->
    <table class="table table-sm table-bordered table-striped table-responsive-sm">
      <thead class="text-center text-bold text-dark">
        <tr>
          <td rowspan="3">No</td>
          <td rowspan="3">Tanggal</td>
          <td rowspan="3">Tipe Transaksi</td>
          <td rowspan="3">Nomor Transaksi</td>
          <td rowspan="3">Keterangan</td>
          <td colspan="3">Pemasukan</td>
          <td colspan="{{ $gudang->count() + 2 }}">Pengeluaran</td>
          <td rowspan="3">Pemakai</td>
        </tr>
        <tr>
          <td rowspan="2">
            @if($rowBM[0]->barang->satuan == "Pcs / Dus") Pcs @else Meter @endif
          </td>
          <td rowspan="2">Gudang</td>
          <td rowspan="2">Rupiah</td>
          <td rowspan="2">
            @if($rowBM[0]->barang->satuan == "Pcs / Dus") Pcs @else Meter @endif
          </td>
          <td colspan="{{ $gudang->count() }}">Dari Gudang</td>
          <td rowspan="2">Rupiah</td>
        </tr>
        <tr>
          @foreach($gudang as $g)
            <td>{{ substr($g->nama, 0, 3) }}</td>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @if(($rowBM->count() != 0) || ($rowSO->count() != 0))
          <tr>
            <td colspan="5" class="text-bold text-dark text-center">Stok Awal</td>
            <td class="text-bold text-dark text-right">{{ $stokAwal }}</td>
            <td colspan="{{ $gudang->count() + 5 }}"></td>
          </tr>
          @php 
            $i = 1; $totalBM = 0; $totalSO = 0;
          @endphp
          @foreach($rowBM as $ib)
            <tr class="text-bold">
              <td align="center">{{ $i }}</td>
              <td align="center">
                {{ \Carbon\Carbon::parse($ib->bm->tanggal)->format('d-M-y') }} 
              </td>
              <td>Barang Masuk</td>
              <td>{{ $ib->bm->id }}</td>
              <td>{{ $ib->bm->supplier->nama }}</td>
              <td align="right">{{ $ib->qty }}</td>
              <td>{{ $ib->bm->gudang->nama }}</td>
              <td align="right">
                {{ number_format($ib->bm->total, 0, "", ",") }}
              </td>
              <td align="right"></td>
              @foreach($gudang as $g)
                <td></td>
              @endforeach
              <td align="right"></td>
              <td align="left">{{ $ib->bm->user->name }} - {{ \Carbon\Carbon::parse($ib->bm->updated_at)->format('H:i:s') }}</td>
              @php $totalBM += $ib->qty @endphp
            </tr>
            @php $i++; @endphp
          @endforeach
          @foreach($rowSO as $is)
            <tr class="text-bold">
              <td align="center">{{ $i }}</td>
              <td align="center">{{ \Carbon\Carbon::parse($is->so->tgl_so)->format('d-M-y') }} </td>
              <td>Penjualan Barang</td>
              <td>{{ $is->so->id }}</td>
              <td>{{ $is->so->customer->nama }}</td>
              <td align="right"></td>
              <td align="right"></td>
              <td align="right"></td>
              <td align="right">{{ $is->qty }}</td>
              @foreach($gudang as $g)
                @php
                  $itemGud = \App\Models\DetilSO::where('id_so', $is->so->id)
                            ->where('id_barang', $is->id_barang)
                            ->where('id_gudang', $g->id)->get();
                @endphp
                @if($itemGud->count() != 0)
                  <td align="right">{{ $itemGud[0]->qty }}
                  </td>
                @else
                  <td></td>
                @endif
              @endforeach
              <td align="right">
                {{ number_format($is->so->total - $is->so->diskon, 0, "", ",") }}
              </td>
              <td align="left">{{ $is->so->user->name }} - {{ \Carbon\Carbon::parse($is->so->updated_at)->format('H:i:s') }}</td>
              @php $totalSO += $is->qty @endphp
            </tr>
            @php $i++; @endphp
          @endforeach
          @foreach($rowTB as $it)
            <tr class="text-bold">
              <td align="center">{{ $i }}</td>
              <td align="center">
                {{ \Carbon\Carbon::parse($it->tb->tgl_tb)->format('d-M-y') }} 
              </td>
              <td>Transfer Barang</td>
              <td>{{ $it->tb->id }}</td>
              <td>{{ $it->gudangAsal->nama }}</td>
              <td align="right">{{ $it->qty }}</td>
              <td>{{ $it->gudangTuju->nama }}</td>
              <td align="right"></td>
              <td align="right"></td>
              @foreach($gudang as $g)
                <td></td>
              @endforeach
              <td align="right"></td>
              <td align="left">{{ $it->tb->user->name }} - {{ \Carbon\Carbon::parse($it->tb->updated_at)->format('H:i:s') }}</td>
              @php $totalBM += $it->qty @endphp
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
            <td colspan="{{ $gudang->count() + 2 }}"></td>
          </tr>
          <tr style="background-color: yellow">
            <td colspan="5" class="text-bold text-dark text-center">Stok Akhir</td>
            <td class="text-bold text-dark text-right">{{ $stok[0]->total }}</td>
            <td colspan="{{ $gudang->count() + 5 }}"></td>
          </tr>
        @else 
          <tr>
            <td colspan="15" class="text-center text-bold h4 p-2"><i>Tidak ada transaksi untuk kode dan tanggal tersebut</i></td>
          </tr>
        @endif
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
