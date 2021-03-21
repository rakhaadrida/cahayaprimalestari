<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Program Prime {{ $bulanNow == 'KOSONG' ? $customer->first()->nama : $bulanNow}}</h2>
      <h5 class="waktu-cetak">Tanggal : 1 - {{ \Carbon\Carbon::parse($date)->isoFormat('DD MMMM YYYY') }}</h5>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-responsive-sm table-hover">
      <thead class="text-center text-dark text-bold">
        <tr>
          <th>No</th>
          <th>Sales</th>
          <th>Customer</th>
          <th>Nama Barang</th>
          <th>Kategori</th>
          <th>Qty</th>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php $i = 1; $subtotal = 0; @endphp
        @foreach($sales as $s)
          @php
            $total = 0; $cekQty = 0;
            if($cust == 'KOSONG')
              $customer = \App\Models\SalesOrder::select('id_customer as id', 'id_sales')
                          ->where('id_sales', $s->id)->groupBy('id_customer')->get();
          @endphp
          @foreach($customer as $c)
            @php
              $qty = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                    ->join('customer', 'customer.id', 'so.id_customer')
                    ->join('barang', 'barang.id', 'detilso.id_barang')
                    // ->select('id_barang')->selectRaw('sum(qty) as qty')
                    ->select('id_barang', 'id_customer','id_so')->selectRaw('sum(qty) as qty')
                    ->whereNotIn('status', ['BATAL', 'LIMIT'])
                    ->where('id_kategori', 'KAT08')
                    ->where('id_customer', $c->id)
                    ->where('so.id_sales', $s->id)->whereYear('tgl_so', $tahun)
                    ->whereIn(DB::raw('MONTH(tgl_so)'), $month)
                    // ->groupBy('id_barang')->orderBy('customer.nama')->get();
                    ->groupBy('id_customer', 'id_barang')->orderBy('customer.nama')->get();
              $cekQty += $qty->count();
            @endphp
            @foreach($qty as $q)
              <tr class="text-dark text-bold">
                <td align="center">{{ $i }}</td>
                <td>{{ $q->so->sales->nama }}</td>
                {{-- <td>{{ $c->nama }}</td> --}}
                <td>{{ $q->so->customer->nama }}</td>
                <td>{{ $q->barang->nama }}</td>
                <td align="center">{{ $q->barang->jenis->nama }}</td>
                <td align="right">{{ $q->qty }}</td>
              </tr>
              @php $i++; $total += $q->qty; @endphp
            @endforeach
          @endforeach
          @if($cekQty != 0)
            <tr class="text-white text-bold bg-primary">
              <td colspan="5" align="right">Total Qty Penjualan {{ $s->nama }}</td>
              <td align="right">{{ $total }}</td>
            </tr>
          @endif
          @php $subtotal += $total; @endphp
        @endforeach
        <tr class="text-white text-bold bg-danger">
          <td colspan="5" align="right" >Grand Total Qty Penjualan</td>
          <td align="right">{{ $subtotal }}</td>
        </tr>
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun != $sejak) - {{$tahun}} @endif | rakhaadrida</h4>
  </body>
</html>
