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
              @endphp
              @foreach($qty as $q)
                @if(($q->id_sales == $i->id_sales) && ($q->id_kategori == $i->id_kategori))
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
                    <td class="text-right">{{ $q->qtyItems }}</td>
                    @php $totalQty += $q->qtyItems; @endphp
                    <td class="text-right">{{ number_format($q->totHpp, 0, "", ".") }}</td>
                  </tr>
                  @php $j++; $totalHpp += $q->totHpp; @endphp
                @endif
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