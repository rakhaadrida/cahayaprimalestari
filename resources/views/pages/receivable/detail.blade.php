@foreach($ar as $a)
  <div class="modal modalGudang" id="Detail{{ $a->id_so }}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
          </button>
          <h5 class="modal-title text-bold"><i class="fa fa-money-bill-alt fa-fw"></i> Detail Cicil Faktur <strong>{{ $a->id_so }}</strong></h5>
        </div>
        <div class="modal-body text-dark">
          <table class="table table-responsive table-bordered table-striped table-md" style="font-size: 16px">
            <thead class="text-center text-bold text-dark">
              <tr class="text-center">
                <th style="width: 60px">No</th>
                <th style="width: 120px">Tgl. Bayar</th>
                <th style="width: 160px">Jumlah Cicil</th>
                <th style="width: 160px">Kurang Bayar</th>
              </tr>
            </thead>
            <tbody>
              @php 
                $i = 1; $total = 0; $kurang = $a->so->total - $a->retur;
                $detilar = App\Models\DetilAR::where('id_ar', $a->id)->get();
              @endphp
              @foreach($detilar as $d)
                <tr class="table-modal-first-row text-dark">
                  <td class="text-center">{{ $i }}</td>
                  <td class="text-center">{{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d-M-y') }}</td>
                  <td class="text-right">{{ number_format($d->cicil, 0, "", ".") }}</td>
                  @php $kurang -= $d->cicil; @endphp
                  <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>
                </tr>
                @php $i++; $total += $d->cicil; @endphp
              @endforeach
              <tr>
                <td colspan="2" class="text-center text-bold text-dark">Total</td>
                <td class="text-right text-bold text-dark">{{ number_format($total, 0, "", ".") }}</td>
                <td class="text-right text-bold text-dark">{{ number_format($kurang, 0, "", ".") }}</td>
              </tr>
            </tbody>
          </table>
          {{-- @php $j = 1; @endphp
          @foreach($detilar as $d)
            <div class="form-group row" style="margin-top: -10px">
              <label for="kode" class="col-auto col-form-label text-bold">{{ $j }}</label>
              <span class="col-auto col-form-label">{{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d-M-y') }}</span>
              <span class="col-form-label qtyOrder">{{ number_format($d->cicil, 0, "", ".") }}</span>
            </div>
            @php $j++; @endphp
          @endforeach --}}
        </div>
      </div>
    </div>
  </div>
@endforeach