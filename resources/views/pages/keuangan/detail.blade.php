@foreach($items as $i)
  <div class="modal modalGudang" id="Detail{{ $i->id_kategori }}{{ $i->id_sales }}" tabindex="-1" role="dialog" aria-labelledby="{{$i->id_kategori}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
          </button>
          <h5 class="modal-title text-bold"><i class="fa fa-money-bill-alt fa-fw"></i> 
            @foreach($jenis as $j)
              @if($j->id == $i->id_kategori)
                Detail HPP Kategori <strong>{{ $j->nama }}</strong>
              @endif
            @endforeach
          </h5>
        </div>
        <div class="modal-body text-dark">
          <table class="table table-responsive table-bordered table-striped table-md" style="font-size: 14px">
            <thead class="text-center text-bold text-dark">
              <tr class="text-center">
                <th class="align-middle" style="width: 40px">No</th>
                <th class="align-middle" style="width: 240px">Nama Barang</th>
                <th class="align-middle" style="width: 110px">Harga</th>
                <th class="align-middle" style="width: 100px">Rata-rata Diskon</th>
                <th class="align-middle" style="width: 110px">Harga Diskon</th>
                <th class="align-middle" style="width: 80px">Qty SO</th>
                <th class="align-middle" style="width: 130px">HPP</th>
              </tr>
            </thead>
            <tbody>
              @php 
                $j = 1; $totalQty = 0; $totalHpp = 0;
                $qty = App\Models\DetilSO::join('barang', 'barang.id', '=', 'detilso.id_barang')
                    ->join('so', 'so.id', '=', 'detilso.id_so')
                    ->join('customer', 'customer.id', '=', 'so.id_customer')
                    ->select('id_barang', DB::raw('sum(qty) as qtyItems')) 
                    ->where('barang.id_kategori', $i->id_kategori)
                    ->where('id_sales', $i->id_sales)
                    ->whereYear('so.tgl_so', $tahun)
                    ->whereMonth('so.tgl_so', $month)
                    ->groupBy('id_barang')
                    ->get();

                $hpp = App\Models\DetilBM::join('barangmasuk', 'barangmasuk.id', '=', 
                    'detilbm.id_bm')
                    ->join('barang', 'barang.id', '=', 'detilbm.id_barang')
                    ->select('id_barang', DB::raw('avg(harga) as avgHarga'),
                    DB::raw('avg(disPersen) as avgDisPersen')) 
                    ->where('barang.id_kategori', $i->id_kategori)
                    ->where('diskon', '!=', NULL)
                    ->whereYear('barangmasuk.tanggal', $tahun)
                    ->whereMonth('barangmasuk.tanggal', $month)
                    ->groupBy('id_barang')
                    ->get();

                foreach($qty as $q) {
                  foreach($hpp as $h) {
                    if($q->id_barang == $h->id_barang) {
                      $q['avgDis'] = number_format($h->avgDisPersen, 2, ".", "");
                      $q['hrg'] = number_format($h->avgHarga, 0, "", "");
                      $q['disHpp'] = number_format(($h->avgHarga * $h->avgDisPersen) / 100, 0, "", "");
                      $q['hrgHpp'] = number_format($h->avgHarga - $q['disHpp'], 0, "", "");
                      $q['totHpp'] = $q['hrgHpp'] * $q->qtyItems;
                    }
                  }
                }
              @endphp
              @foreach($qty as $q)
                <tr class="table-modal-first-row text-dark">
                  <td class="text-center">{{ $j }}</td>
                  <td>{{ $q->barang->nama }}</td>
                  <td class="text-right">{{ number_format($q->hrg, 0, "", ".") }}</td>
                  <td class="text-right">{{ number_format($q->avgDis, 2, ",", "") }}</td>
                  @php 
                    $hrgDiskon = number_format(($q->hrg * $q->avgDis) / 100, 0, "", "");
                    $hrgDiskon = number_format($q->hrg - $hrgDiskon, 0, "", "");
                  @endphp
                  <td class="text-right">{{ number_format($hrgDiskon, 0, "", ".") }}</td>
                  @foreach($hpp as $h) 
                    @if($q->id_barang == $h->id_barang)
                      <td class="text-right">{{ $q->qtyItems }}</td>
                      @php $totalQty += $q->qtyItems; @endphp
                    @endif
                  @endforeach
                  <td class="text-right">{{ number_format($q->totHpp, 0, "", ".") }}</td>
                </tr>
                @php $j++; $totalHpp += $q->totHpp; @endphp
              @endforeach
              <tr>
                <td colspan="5" class="text-center text-bold text-dark">Total</td>
                <td class="text-right text-bold text-dark">{{ number_format($totalQty, 0, "", ".") }}</td>
                <td class="text-right text-bold text-dark">{{ number_format($totalHpp, 0, "", ".") }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endforeach