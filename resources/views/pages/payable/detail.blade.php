@foreach($ap as $a)
  <div class="modal modalGudang" id="Detail{{ $a->id_bm }}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
          </button>
          <h5 class="modal-title text-bold"><i class="fa fa-money-bill-alt fa-fw"></i> Detail Bayar Payable <strong>{{ $a->id_bm }}</strong></h5>
        </div>
        <div class="modal-body text-dark">
          <table class="table table-responsive table-bordered table-striped table-md" style="font-size: 16px">
            <thead class="text-center text-bold text-dark">
              <tr class="text-center">
                <th style="width: 60px">No</th>
                <th style="width: 120px">Tgl. Bayar</th>
                <th style="width: 160px">Jumlah Bayar</th>
                <th style="width: 160px">Kurang Bayar</th>
              </tr>
            </thead>
            <tbody>
              @php 
                $i = 1; $total = 0; $kurang = $a->bm->total;
                $detilap = App\Models\DetilAP::where('id_ap', $a->id)->get();
              @endphp
              @foreach($detilap as $d)
                @if($d->transfer != 0)
                  <tr class="table-modal-first-row text-dark">
                    <td class="text-center">{{ $i }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d-M-y') }}</td>
                    <td class="text-right">{{ number_format($d->transfer, 0, "", ".") }}</td>
                    @php $kurang -= $d->transfer; @endphp
                    <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>
                  </tr>
                @endif
                @php $i++; $total += $d->transfer; @endphp
              @endforeach
              <tr>
                <td colspan="2" class="text-center text-bold text-dark">Total</td>
                <td class="text-right text-bold text-dark">{{ number_format($total, 0, "", ".") }}</td>
                <td class="text-right text-bold text-dark">{{ number_format($kurang, 0, "", ".") }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endforeach