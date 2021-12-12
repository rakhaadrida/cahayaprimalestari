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
            $total = \App\Utilities\Helper::getReceivableTotal($item->id);
            $retur = \App\Utilities\Helper::getReceivableRetur($item->id);
            $tanggal = \App\Utilities\Helper::getReceivableDate($item->tgl_so);
            $temp = \App\Utilities\Helper::getReceivableTempo($item->tgl_so, $item->tempo);
            $tempo = \App\Utilities\Helper::getReceivableDate($temp);
          @endphp
          <tr class="text-dark">
            <td align="center">{{ $i }}</td>
            <td align="center">{{ $item->namaSales }}</td>
            <td>{{ $item->namaCustomer }}</td>
            <td align="center">{{ $item->kategori }}</td>
            <td align="center">{{ $item->id_so }}</td>
            <td align="center">{{ $aw->diffInDays($tanggal) }}</td>
            <td align="center">{{ $aw->diffInDays($tempo) }}</td>
            <td align="right">{{ $item->total }}</td>
            <td align="right">{{ $total->first()->totCicil }}</td>
            <td align="right">{{ $retur->first()->total }}</td>
            <td align="right">{{ $item->total - $total->first()->totCicil - $retur->first()->total }}</td>
            <td>{{ $item->keterangan }}</td>
          </tr>
          @php $i++ @endphp
        @endforeach
        @if($itemsEx != NULL)
          @foreach($itemsEx as $item)
            @php
                $total = \App\Utilities\Helper::getReceivableTotal($item->id);
                $retur = \App\Utilities\Helper::getReceivableRetur($item->id);
                $tanggal = \App\Utilities\Helper::getReceivableDate($item->tgl_so);
                $temp = \App\Utilities\Helper::getReceivableTempo($item->tgl_so, $item->tempo);
                $tempo = \App\Utilities\Helper::getReceivableDate($temp);
            @endphp
            <tr class="text-dark">
              <td align="center" class="align-middle">{{ $i }}</td>
              <td align="center" class="align-middle text-center">{{ $item->namaSales }}</td>
              <td class="align-middle">{{ $item->namaCustomer }}</td>
              <td align="center" class="align-middle">{{ $item->kategori }}</td>
              <td align="center" class="align-middle">{{ $item->id_so }}</td>
              <td align="center">{{ $aw->diffInDays($tanggal) }}</td>
              <td align="center">{{ $aw->diffInDays($tempo) }}</td>
              <td align="right">{{ $item->total }}</td>
              <td align="right">{{ $total->first()->totCicil }}</td>
              <td align="right">{{ $retur->first()->total }}</td>
              <td align="right">{{ $item->total - $total->first()->totCicil - $retur->first()->total }}</td>
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
