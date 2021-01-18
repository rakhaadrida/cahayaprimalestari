@php $j = 0; @endphp
@foreach($retur as $r)
  <div class="modal modalGudang" id="Detail{{ $r->id }}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
          </button>
          <h5 class="modal-title text-bold"><i class="fa fa-money-bill-alt fa-fw"></i> Detail Terima Retur <strong>{{ $r->id }}</strong></h5>
        </div>
        <div class="modal-body text-dark">
          <form action="" method="POST" id="kirimRB">
            @csrf
            <input type="hidden" name="kode" id="kode" value="{{ $r->id }}">
            <input type="hidden" name="angka" id="angka" value="{{ $j }}">
            @if($r->status == 'INPUT')
              <div class="form-row justify-content-center mb-3">
                <div class="col-3">
                  <a href="{{ route('retur-potong-beli', $r->id) }}" id="backRJ" class="btn btn-outline-primary btn-block text-bold">Potong Tagihan</a>
                </div>
              </div>
            @endif
            @php 
              $detail = \App\Models\DetilRB::where('id_retur', $r->id)->get();
            @endphp
            @foreach($detail as $d)
              <table class="table table-responsive table-bordered table-striped table-sm" style="font-size: 16px">
                <thead class="text-center text-bold text-dark">
                  <tr class="text-center bg-gradient-danger text-white">
                    <th class="align-middle" style="width: 40px">No</th>
                    <th class="align-middle" style="width: 90px">Kode Barang</th>
                    <th class="align-middle" style="width: 325px">Nama Barang</th>
                    <th class="align-middle" style="width: 60px">Qty Retur</th>
                    <th class="align-middle" style="width: 100px">Tgl. Terima</th>
                    <th class="align-middle" style="width: 70px">Qty Terima</th>
                    <th class="align-middle" style="width: 70px">Qty Ditolak</th>
                    <th class="align-middle" style="width: 70px">Potong Tagihan</th>
                    <th class="align-middle" style="width: 70px">Qty Kurang</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php 
                    $i = 1; $totalTerima = 0; $totalBatal = 0; $totalPotong = 0; $kode = $r->id;
                    $returTerima = App\Models\DetilRT::join('returterima', 'returterima.id',
                                  'detilrt.id_terima')->where('id_retur', $r->id)
                                  ->where('id_barang', $d->id_barang)->get();
                    $kurang = $d->qty_retur;
                  @endphp
                  @if($returTerima->count() != 0)
                    @foreach($returTerima as $dr)
                      <tr class="table-modal-first-row text-dark text-bold">
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="text-center align-middle">{{ $dr->id_barang }}</td>
                        <td class="align-middle">{{ $dr->barang->nama }}</td>
                        <td class="text-center">{{ number_format($d->qty_retur, 0, "", ".") }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($dr->tanggal)->format('d-M-y') }}</td>
                        <td class="text-right">{{ number_format($dr->qty_terima, 0, "", ".") }}</td>
                        <td class="text-right">{{ number_format($dr->qty_batal, 0, "", ".") }}</td>
                        <td class="text-right">{{ number_format($dr->potong, 0, "", ".") }}</td>
                        @php $kurang -= ($dr->qty_terima + $dr->qty_batal + $dr->potong); @endphp
                        <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>
                      </tr>
                      @php $i++; $totalTerima += $dr->qty_terima; $totalBatal += $dr->qty_batal; 
                            $totalPotong += $dr->potong; @endphp
                    @endforeach
                  @endif
                  @if($d->qty_retur != $totalTerima + $totalBatal + $totalPotong)
                    <input type="hidden" name="kurangAwal" class="kurangAwal" value="{{ $kurang }}">
                    <tr class="text-dark text-bold">
                      <td class="text-center align-middle">{{ $i }}</td>
                      <td class="text-center align-middle">{{ $d->id_barang }}</td>
                      <td class="align-middle">{{ $d->barang->nama }}</td>
                      <td class="align-middle text-center">{{ $d->qty_retur }}</td>
                      <td class="text-center align-middle">
                        <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl{{$r->id}}{{$d->id_barang}}" id="tglBayar{{$d->id_barang}}" placeholder="DD-MM-YYYY" autocomplete="off">
                      </td>
                      <td class="text-right align-middle">
                        <input type="text" name="terima{{$r->id}}{{$d->id_barang}}" id="bayar{{$d->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right kirimModal" onkeypress="return angkaSaja(event)" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                      </td>
                      <td class="text-right align-middle">
                        <input type="text" name="batal{{$r->id}}{{$d->id_barang}}" id="batal{{$d->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right batalModal" onkeypress="return angkaSaja(event)" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                      </td>
                      <td class="text-right align-middle"></td>
                      <td class="text-right align-middle">
                        <input type="text" name="kurang{{$d->id_barang}}" id="kurang{{$d->id_barang}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurang">
                      </td>
                    </tr>
                  @endif
                  <tr class="bg-gradient-danger text-white text-bold">
                    <td colspan="6" class="text-center">Total</td>
                    <td class="text-right">{{ number_format($totalTerima, 0, "", ".") }}</td>
                    <td class="text-right">{{ number_format($totalBatal, 0, "", ".") }}</td>
                    <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>
                  </tr>
                </tbody>
              </table>
              @if($d->id_barang != $detail->last()->id_barang)
                <br>
              @endif
            @endforeach
            <hr>

            @if($r->status == 'INPUT')
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('retur-beli-process') }}" formmethod="POST">Submit</button>
                </div>
                <div class="col-2">
                  <button type="submit" class="btn btn-outline-danger btn-block text-bold" formaction="{{ route('retur-beli-batal', $r->id) }}" formmethod="POST">Batal Retur</button>
                </div>
                <div class="col-2">
                  <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-block text-bold">Kembali</button>
                </div>
              </div>
            {{-- @elseif($r->status == 'LENGKAP')
              <div class="form-row justify-content-center">
                <div class="col-3">
                  <button type="button" id="btnCetak" class="btn btn-primary btn-block text-bold btnCetak">Cetak</button>
                </div>
              </div> --}}

              {{-- <iframe src="{{url('retur/pembelian/cetak/'.$r->id)}}" id="frameCetak{{$j}}" frameborder="0" hidden></iframe> --}}
              {{-- @php $j++; @endphp --}}
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
  @php $j++ @endphp
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

const kirimRB = document.getElementById("kirimRB");
const tglBayar = document.querySelectorAll('.tglBayar');
const kirimModal = document.querySelectorAll('.kirimModal');
const batalModal = document.querySelectorAll('.batalModal');
const kurang = document.querySelectorAll('.kurang');
const kurangAwal = document.querySelectorAll('.kurangAwal');
const btnCetak = document.querySelectorAll('.btnCetak');
const kodeRB = document.getElementById('angka');

kirimRB.addEventListener("keypress", checkEnter);

function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;     
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
}

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

for(let i = 0; i < kirimModal.length; i++) {
  kirimModal[i].addEventListener("change", function(e) {
    if(kurang[i].value == '')
      kurang[i].value = kurangAwal[i].value - e.target.value;
    else
      kurang[i].value -= e.target.value;
  });
}

for(let i = 0; i < batalModal.length; i++) {
  batalModal[i].addEventListener("change", function(e) {
    if(kurang[i].value == '')
      kurang[i].value = kurangAwal[i].value - e.target.value;
    else
      kurang[i].value -= e.target.value;
  });
}

for(let i = 0; i < btnCetak.length; i++) {
  btnCetak[i].addEventListener("click", function(e) {
    const printFrame = document.getElementById("frameCetak"+i).contentWindow;

    printFrame.window.onafterprint = function(e) {
      alert('ok');
    }

    printFrame.window.print();
    // window.print();
  });
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, nomor, inputan, jenis) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
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

</script>