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
          <form action="" method="POST">
            @csrf
            <input type="hidden" name="kode" value="{{ $a->id_so }}">
            <table class="table table-responsive-md table-bordered table-striped table-md" style="font-size: 16px">
              <thead class="text-center text-bold text-dark">
                <tr class="text-center">
                  <th style="width: 60px">No</th>
                  <th style="width: 160px">Tgl. Bayar</th>
                  <th style="width: 160px">Jumlah Cicil</th>
                  <th style="width: 160px">Kurang Bayar</th>
                </tr>
              </thead>
              <tbody class="table-ar">
                @php 
                  $retur = App\Models\AR_Retur::selectRaw('sum(total) as total')
                          ->where('id_ar', $a->id)->get();
                  $i = 1; $total = 0; $kurang = $a->so->total - $retur[0]->total;
                  $detilar = App\Models\DetilAR::where('id_ar', $a->id)->get();
                @endphp
                @foreach($detilar as $d)
                  @if($d->cicil != 0)
                    <tr class="table-modal-first-row text-dark">
                      <td class="text-center">{{ $i }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d-M-y') }}</td>
                      <td class="text-right">{{ number_format($d->cicil, 0, "", ".") }}</td>
                      @php $kurang -= $d->cicil; @endphp
                      <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>
                    </tr>
                  @endif
                  @php $i++; $total += $d->cicil; @endphp
                @endforeach
                @if(($a->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                  <input type="hidden" name="kurangAwal" class="kurangAwal" value="{{ $kurang }}">
                  <tr class="text-dark">
                    <td class="text-center align-middle">{{ $i }}</td>
                    <td class="text-center align-middle">
                      <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl{{$a->id_so}}" id="tglBayar{{$a->id_so}}" placeholder="DD-MM-YYYY" autocomplete="off">
                    </td>
                    <td class="text-right align-middle">
                      <input type="text" name="cicil{{$a->id_so}}" id="cicil{{$a->id_so}}" class="form-control form-control-sm text-bold text-dark text-right cicilModal" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                    </td>
                    <td class="text-right align-middle">
                      <input type="text" name="kurang{{$a->id_so}}" id="kurang{{$a->id_so}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurang">
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

            @if(($a->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
              <div class="form-row justify-content-center">
                <div class="col-3">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ar-process') }}" formmethod="POST">Submit</button>
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
const cicilModal = document.querySelectorAll('.cicilModal');
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

for(let i = 0; i < cicilModal.length; i++) {
  cicilModal[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
  });

  cicilModal[i].addEventListener("blur", function(e) {
    kurang[i].value = addCommas(kurangAwal[i].value.replace(/\./g, "") - e.target.value.replace(/\,/g, ""));
  });
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= qty.length; i++) {
      if(inputan == i)
        $(qty[inputan-1]).tooltip('show');
    }
    return false;
  }
  return true;
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

/* function checkEditable(kodeSO) {
  const tglByr = document.getElementById("tglBayar"+kodeSO.id);
  const cicilByr = document.getElementById("cicil"+kodeSO.id);
  if((tglByr.value == "") || (cicilByr.value == "")) {
    return false;
  }
  else {
    @foreach($ar as $a)
      if('{{ $a->id_so }}' == kodeSO.id) {
        document.getElementById("btn"+kodeSO.id).formMethod = "POST";
        document.getElementById("btn"+kodeSO.id).formAction = '{{ route('ar-process') }}';
      }
    @endforeach
  }
} */

</script>