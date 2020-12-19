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
          <form action="" method="POST">
            @csrf
            <input type="hidden" name="kode" value="{{ $a->id_bm }}">
            <table class="table table-responsive table-bordered table-striped table-md" style="font-size: 16px">
              <thead class="text-center text-bold text-dark">
                <tr class="text-center">
                  <th style="width: 60px">No</th>
                  <th style="width: 160px">Tgl. Bayar</th>
                  <th style="width: 160px">Jumlah Bayar</th>
                  <th style="width: 160px">Kurang Bayar</th>
                </tr>
              </thead>
              <tbody class="table-ar">
                @php 
                  $i = 1; $total = 0;
                  $totalBM = App\Models\BarangMasuk::select(DB::raw('sum(total) as totBM'))
                          ->where('id_faktur', $a->id_bm)->get();
                  $detilap = App\Models\DetilAP::where('id_ap', $a->id)->get();
                  $retur = App\Models\AP_Retur::selectRaw('sum(total) as total')
                          ->where('id_ap', $a->id)->get();
                  $kurang = $totalBM[0]->totBM - $retur[0]->total;
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
                    @php $i++; $total += $d->transfer; @endphp
                  @endif
                @endforeach
                @if($a->keterangan == 'BELUM LUNAS')
                  <input type="hidden" name="kurangAwal" class="kurangAwal" value="{{ $kurang }}">
                  <tr class="text-dark">
                    <td class="text-center align-middle">{{ $i }}</td>
                    <td class="text-center align-middle">
                      <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl{{$a->id_bm}}" id="tglBayar{{$a->id_bm}}" placeholder="DD-MM-YYYY">
                    </td>
                    <td class="text-right align-middle">
                      <input type="text" name="bayar{{$a->id_bm}}" id="bayar{{$a->id_bm}}" class="form-control form-control-sm text-bold text-dark text-right bayarModal">
                    </td>
                    <td class="text-right align-middle">
                      <input type="text" name="kurang{{$a->id_bm}}" id="kurang{{$a->id_bm}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurang">
                    </td>
                  </tr>
                @endif
                <tr>
                  <td colspan="2" class="text-center text-bold text-dark">Total</td>
                  <td class="text-right text-bold text-dark">{{ number_format($total, 0, "", ".") }}</td>
                  <td class="text-right text-bold text-dark">{{ number_format($kurang, 0, "", ".") }}</td>
                </tr>
              </tbody>
            </table>

            @if($a->keterangan == 'BELUM LUNAS')
              <div class="form-row justify-content-center">
                <div class="col-3">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ap-transfer') }}" formmethod="POST">Submit</button>
                  {{-- id="btn{{$a->id_so}}"   onclick="return checkEditable({{$a->id_so}})--}}
                </div>
                <div class="col-3">
                  <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-block text-bold">Batal</button>
                </div>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
@endforeach

<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
$.fn.datepicker.dates['id'] = {
  days:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],
  daysShort:["Mgu","Sen","Sel","Rab","Kam","Jum","Sab"],
  daysMin:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
  months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
  monthsShort:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"],
  today:"Hari Ini",
  clear:"Kosongkan"
};

$('.datepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true,
  language: 'id',
});

const tglBayar = document.querySelectorAll('.tglBayar');
const bayarModal = document.querySelectorAll('.bayarModal');
const kurang = document.querySelectorAll('.kurang');
const kurangAwal = document.querySelectorAll('.kurangAwal');

for(let i = 0; i < tglBayar.length; i++) {
  tglBayar[i].addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4) 
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
    
    tglBayar[i].value = value;
  });
}

for(let i = 0; i < bayarModal.length; i++) {
  bayarModal[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
  });

  bayarModal[i].addEventListener("change", function(e) {
    kurang[i].value = addCommas(kurangAwal[i].value.replace(/\./g, "") - e.target.value.replace(/\,/g, ""));
  });
}

/** Add Thousand Separators **/
function addCommas(nStr) {
	nStr += '';
	x = nStr.split(',');
	x1 = x[0];
	x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
}

</script>