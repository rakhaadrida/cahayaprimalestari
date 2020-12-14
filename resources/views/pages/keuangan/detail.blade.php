@foreach($items as $i)
{{-- @foreach($jenis as $j) --}}
  <div class="modal modalGudang" id="Detail{{ $i->id_kategori }}{{ $i->id_sales }}" tabindex="-1" role="dialog" aria-labelledby="{{$i->id_kategori}}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
          </button>
          <h5 class="modal-title text-bold"><i class="fa fa-money-bill-alt fa-fw"></i> 
            @foreach($jenis as $j)
              @if($j->id == $i->id_kategori)
                Detail HPP Kategori <strong>{{ $j->nama }}</strong> dengan Sales <strong>{{ $i->nama }}</strong>
              @endif
            @endforeach
          </h5>
        </div>
        <div class="modal-body text-dark">
          <table class="table table-responsive table-bordered table-striped table-sm" style="font-size: 14px">
            <thead class="text-center text-bold text-dark">
              <tr class="text-center bg-gradient-danger text-white">
                <th class="align-middle" style="width: 60px">No</th>
                <th class="align-middle" style="width: 340px">Nama Barang</th>
                <th class="align-middle" style="width: 65px">Qty SO</th>
                <th class="align-middle" style="width: 95px">Harga</th>
                <th class="align-middle" style="width: 115px">Total</th>
                <th colspan="2" class="align-middle" style="width: 120px">Diskon</th>
                <th class="align-middle" style="width: 115px">Nominal Diskon</th>
                <th class="align-middle" style="width: 120px">HPP</th>
              </tr>
            </thead>
            <tbody>
              @php 
                $j = 1; $totalQty = 0; $totalHpp = 0; $subtotal = 0; $totalDiskon = 0;
              @endphp
              @foreach($hppPerKat as $h)
                @if(($h->get('id_sales') == $i->id_sales) && ($h->get('id_kat') == $i->id_kategori))
                  <tr class="table-modal-first-row text-dark text-bold">
                    <td class="text-center">{{ $j }}</td>
                    <td>{{ $h->get('nama') }}</td>
                    <td class="text-right">{{ $h->get('qty') }}</td>
                    <td class="text-right">{{ number_format($h->get('harga'), 0, "", ".") }}</td>
                    @php 
                      $total = $h->get('qty') * $h->get('harga');
                      $diskon = $total * $h->get('disPersen') / 100;
                    @endphp
                    <td class="text-right">{{ number_format($total, 0, "",".") }}</td>
                    <td class="text-right" style="width: 100px" class="text-center">{{ $h->get('diskon') }}</td>
                    <td style="width: 95px" class="text-right">{{ number_format($h->get('disPersen'), 2, ",", "") }} %</td>
                    <td class="text-right">{{ number_format($diskon, 0, "", ".") }}</td>
                    @php $totalQty += $h->get('qty'); @endphp
                    <td class="text-right">{{ number_format($h->get('hpp'), 0, "", ".") }}</td>
                  </tr>
                  @php $j++; $totalHpp += $h->get('hpp'); $subtotal += $total; 
                    $totalDiskon += $diskon; @endphp
                @endif
              @endforeach
              <tr class="bg-gradient-danger text-white">
                <td colspan="2" class="text-center text-bold">Total</td>
                <td class="text-right text-bold">{{ number_format($totalQty, 0, "", ".") }}</td>
                <td></td>
                <td class="text-right text-bold">{{ number_format($subtotal, 0, "", ".") }}</td>
                <td colspan="2"></td>
                <td class="text-right text-bold">{{ number_format($totalDiskon, 0, "", ".") }}</td>
                <td class="text-right text-bold">{{ number_format($totalHpp, 0, "", ".") }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endforeach