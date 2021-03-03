<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Data AR</h2>
      <h5 class="waktu-cetak">Tanggal : {{$awal}} s/d {{$akhir}}</h5>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr>
          <th>No</th>
          <th>Sales</th>
          <th>Customer</th>
          <th>Kategori</th>
          <th>No. Faktur</th>
          <th>Tgl. Faktur</th>
          <th>Tempo</th>
          <th>Total</th>
          <th>Cicil</th>
          <th>Retur</th>
          <th>Kurang Bayar</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; @endphp
        @foreach($items as $item)
          @php 
            $total = App\Models\DetilAR::select(DB::raw('sum(cicil) as totCicil'))
                      ->where('id_ar', $item->id)->get();
            $retur = App\Models\AR_Retur::selectRaw('sum(total) as total')
                    ->where('id_ar', $item->id)->get();
          @endphp
          <tr class="text-dark">
            <td align="center">{{ $i }}</td>
            <td align="center">{{ $item->so->customer->sales->nama }}</td>
            <td>{{ $item->so->customer->nama }}</td>
            <td align="center">{{ $item->so->kategori }}</td>
            <td align="center">{{ $item->id_so }}</td>
            <td align="center">{{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-M-y') }}</td>
            <td align="center">
              {{ \Carbon\Carbon::parse($item->so->tgl_so)->add($item->so->tempo, 'days')
                ->format('d-M-y') }}
            </td>
            <td align="right">{{ $item->so->total }}</td>
            <td align="right">{{ $total->first()->totCicil }}</td>
            <td align="right">{{ $retur->first()->total }}</td>
            <td align="right">{{ $item->so->total - $total->first()->totCicil - $retur->first()->total }}</td>
            <td>{{ $item->keterangan }}</td>
          </tr> 
          @php $i++ @endphp
        @endforeach
        @if($itemsEx != NULL)
          @foreach($itemsEx as $item)
            @php 
              $total = App\Models\DetilAR::select(DB::raw('sum(cicil) as totCicil'))
                        ->where('id_ar', $item->id)->get();
              $retur = App\Models\AR_Retur::selectRaw('sum(total) as total')
                      ->where('id_ar', $item->id)->get();
            @endphp
            <tr class="text-dark">
              <td align="center" class="align-middle">{{ $i }}</td>
              <td class="align-middle text-center">{{ $item->so->customer->sales->nama }}</td>
              <td class="align-middle">{{ $item->so->customer->nama }}</td>
              <td align="center" class="align-middle">{{ $item->so->kategori }}</td>
              <td align="center" class="align-middle">{{ $item->id_so }}</td>
              <td align="center" class="align-middle">
                {{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-M-y') }}
              </td>
              <td align="center" class="align-middle">
                {{ \Carbon\Carbon::parse($item->so->tgl_so)->add($item->so->tempo, 'days')
                  ->format('d-M-y') }}
              </td>
              <td align="right">{{ $item->so->total }}</td>
              <td align="right">{{ $total->first()->totCicil }}</td>
              <td align="right">{{ $retur->first()->total }}</td>
              <td align="right">{{ $item->so->total - $total->first()->totCicil - $retur->first()->total }}</td>
              <td>{{ $item->keterangan }}</td>
            </tr> 
            @php $i++ @endphp
          @endforeach
        @endif
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
