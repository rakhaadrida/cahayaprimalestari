<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Kartu Stok (Good Stock)</h2>
      <h3 class="text-dark">
        Kode Barang : {{$itemsBRG->first()->id}}
      </h3>
      <h5 class="waktu-cetak">Tanggal : {{\Carbon\Carbon::parse($awal)->format('d-M-y')}} s/d {{\Carbon\Carbon::parse($akhir)->format('d-M-y')}}</h5>
    </center>
    <br>

    <h5>Kode Barang : {{ $itemsBRG->first()->id }} - {{ $itemsBRG->first()->nama }}</h5>
    <h5>Ukuran : {{ $itemsBRG->first()->ukuran }}  {{ $itemsBRG->first()->satuan }} </h5>

    <!-- Tabel Data Detil PO -->
    <table class="table table-sm table-bordered table-striped table-responsive-sm">
      <thead class="text-center text-bold text-dark">
        <tr>
          <td rowspan="3">No</td>
          <td rowspan="3">Tanggal</td>
          <td rowspan="3">Tipe Transaksi</td>
          <td rowspan="3">Nomor Transaksi</td>
          <td rowspan="3">Keterangan</td>
          <td colspan="4">Pemasukan</td>
          <td colspan="{{ $gudang->count() + 2 }}">Pengeluaran</td>
          <td rowspan="3">Pemakai</td>
        </tr>
        <tr>
          <td rowspan="2">
            @if($itemsBRG->first()->satuan == "Pcs / Dus") Pcs @else Meter @endif
          </td>
          <td rowspan="2">
            @if($itemsBRG->first()->satuan == "Pcs / Dus") Pcs @else Meter @endif TB
          </td>
          <td rowspan="2">Gudang</td>
          <td rowspan="2">Rupiah</td>
          <td rowspan="2">
            @if($itemsBRG->first()->satuan == "Pcs / Dus") Pcs @else Meter @endif
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
        @if($items->count() != 0)
          <tr>
            <td colspan="5" class="text-bold text-dark text-center">Stok Awal</td>
            <td class="text-bold text-dark text-right">{{ $stokAwal }}</td>
            <td colspan="{{ $gudang->count() + 6 }}"></td>
          </tr>
          @php 
            $i = 1; $totalBM = 0; $totalSO = 0;
          @endphp
          @foreach($items as $it)
            <tr class="text-bold">
              <td align="center">{{ $i }}</td>
              <td align="center">{{ \Carbon\Carbon::parse($it->tanggal)->format('d-M-y') }}</td>
              <td>
                @if(substr($it->id, 0, 2) == 'BM')Barang Masuk @elseif((substr($it->id, 0, 2) == 'TB'))Transfer @else Penjualan @endif
              </td>
              <td>{{ $it->id }}</td>
              @php
                $nama = ''; $namaGud = ''; $total = ''; $user = '';
                if(substr($it->id, 0, 2) == 'BM') {
                  $namaBM = \App\Models\BarangMasuk::where('id', $it->id)->get();
                  $nama = $namaBM->first()->supplier->nama;
                  $namaGud = $namaBM->first()->gudang->nama;
                  $total = $namaBM->first()->total;
                  $user = $namaBM->first()->user->name;
                }
                elseif((substr($it->id, 0, 2) == 'TB')) {
                  $namaTB = \App\Models\DetilTB::where('id_tb', $it->id)
                            ->where('id_barang', $itemsBRG->first()->id)->get();
                  $nama = $namaTB->first()->gudangAsal->nama;
                  $namaGud = $namaTB->first()->gudangTuju->nama;
                  $user = $namaTB->first()->tb->user->name;
                }
                else {
                  $namaSO = \App\Models\SalesOrder::where('id', $it->id)->get();
                  $nama = $namaSO->first()->customer->nama;
                  $total = $namaSO->first()->total;
                  $user = $namaSO->first()->user->name;
                } 
              @endphp
              <td>{{ $nama }}</td>
              <td align="right">{{ substr($it->id, 0, 2) == 'BM' ? $it->qty : '' }}</td>
              <td align="right">{{ substr($it->id, 0, 2) == 'TB' ? $it->qty : '' }}</td>
              <td>{{ $namaGud }}</td>
              <td align="right">{{ substr($it->id, 0, 2) == 'BM' ? number_format($total, 0, "", ".") : '' }}</td>
              <td align="right">{{ substr($it->id, 0, 2) == 'IN' ? $it->qty : '' }}</td>
              @foreach($gudang as $g)
                @php
                  $itemGud = \App\Models\DetilSO::where('id_so', $it->id)
                            ->where('id_barang', $it->id_barang)
                            ->where('id_gudang', $g->id)->get();
                @endphp
                @if($itemGud->count() != 0)
                  <td align="right">{{ $itemGud->first()->qty }}</td>
                @else
                  <td></td>
                @endif
              @endforeach
              <td align="right">{{ ((substr($it->id, 0, 2) != 'BM') && (substr($it->id, 0, 2) != 'TB')) ? $total : '' }}</td>
              <td align="center">{{ $user }} - {{ \Carbon\Carbon::parse($it->updated_at)->format('H:i:s') }}</td>
              @php
                if(substr($it->id, 0, 2) == 'BM') 
                  $totalBM += $it->qty; 
                elseif(substr($it->id, 0, 2) == 'IN')
                  $totalSO += $it->qty;
              @endphp
            </tr>
            @php $i++; @endphp
          @endforeach
          <tr>
            <td colspan="5" class="text-bold text-dark text-center">Total</td>
            <td class="text-bold text-dark text-right">
              {{ $stokAwal + $totalBM }}
            </td>
            <td colspan="3"></td>
            <td class="text-bold text-dark text-right">{{ $totalSO }}</td>
            <td colspan="{{ $gudang->count() + 2 }}"></td>
          </tr>
          <tr style="background-color: yellow">
            <td colspan="5" class="text-bold text-dark text-center">Stok Akhir</td>
            <td class="text-bold text-dark text-right">{{ $stok[0]->total }}</td>
            <td colspan="{{ $gudang->count() + 6 }}"></td>
          </tr>
        @else 
          <tr>
            <td colspan="{{ $gudang->count() + 12 }}" class="text-center text-bold h4 p-2"><i>Tidak ada transaksi untuk kode dan tanggal tersebut</i></td>
          </tr>
        @endif
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
