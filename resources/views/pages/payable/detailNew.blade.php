@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Progress Transfer Tagihan Faktur {{ $item->first()->id_bm }}</h1>
  </div>
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <div class="card show">
          <div class="card-body">
            <form action="" method="" id="cicilAP">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier BM -->
               <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="1" readonly class="form-control form-control-sm text-bold text-dark" name="kodeSO" value="{{ $item->first()->id_bm }}" >
                        <input type="hidden" name="kode" value="{{ $item->first()->id_bm }}">
                        <input type="hidden" name="kodeAP" value="{{ $item->first()->id }}">
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="2" readonly class="form-control datepicker form-control-sm text-bold text-dark" name="tanggal" value="{{ \Carbon\Carbon::parse($item->first()->bm->first()->tanggal)->format('d-M-y') }}">
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -360px">
                    <div class="form-group row subtotal-po">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Total Tagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right text-dark" name="subtotal" id="subtotal" value={{ number_format($totalBM->first()->totBM - $potBM->first()->potongan, 0, "", ".") }}>
                      </div>
                    </div>
                    <div class="form-group row" style="margin-top: -25px">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Total Retur</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right text-dark" name="retur" id="retur" value={{ number_format($retur->first()->total, 0, "", ".") }}>
                      </div>
                    </div>
                    <div class="form-group row" style="margin-top: -25px">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Kurang Bayar</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right text-dark" name="kurangBayar" id="kurangBayar" value={{ number_format($totalBM->first()->totBM - $retur->first()->total - $potBM->first()->potongan, 0, "", ".") }}>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -60px">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" tabindex="5" name="namaCust" readonly class="form-control form-control-sm text-bold text-dark" value="{{ $item->first()->bm->first()->supplier->nama }}" />
                  </div>
                  <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $detilap->count() }}">
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->

              <!-- Tabel Data Detil BM-->
              {{-- @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                  Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
                </span>
              @endif --}}
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <tr class="text-center">
                    <th style="width: 60px">No</th>
                    <th style="width: 160px">Tgl. Bayar</th>
                    <th style="width: 160px">Jumlah Cicil</th>
                    <th style="width: 160px">Kurang Bayar</th>
                    <th style="width: 60px">Hapus</th>
                  </tr>
                </thead>
                <tbody id="tablePO" class="table-ar">
                  @php $i = 1; $total = 0; $kurang = $totalBM->first()->totBM - $retur->first()->total - $potBM->first()->potongan; @endphp
                  @foreach($detilap as $d)
                    @if($d->transfer != 0)
                      {{-- <tr class="table-modal-first-row text-dark">
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d-M-y') }}</td>
                        <td class="text-right">{{ number_format($d->transfer, 0, "", ".") }}</td>
                        @php $kurang -= $d->transfer; @endphp
                        <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>
                      </tr> --}}
                      <tr class="table-modal-first-row text-dark" id="{{$i-1}}" style="font-size: 16px !important">
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="text-center">
                          <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglDetil" name="tgldetil[]" autocomplete="off" value="{{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d-m-Y') }}" style="font-size: 16px">
                        </td>
                        <td class="text-right">
                          <input type="text" name="bayardetil[]" class="form-control form-control-sm text-bold text-dark text-right bayarDetil" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" value="{{ number_format($d->transfer, 0, "", ".") }}" style="font-size: 16px">
                          <input type="hidden" name="bayarAwal" class="bayarAwal" value="{{ $d->transfer }}">
                        </td>
                        @php $kurang -= $d->transfer; @endphp
                        <td class="text-right align-middle text-bold">
                          <input type="text" name="kurangdetil[]" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurangDetil" style="font-size: 16px" value="{{ number_format($kurang, 0, "", ".") }}" style="font-size: 16px">
                        </td>
                        <td align="center" class="align-middle">
                          <a href="#" class="icRemoveDetil">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; $total += $d->transfer; @endphp
                    @endif
                  @endforeach
                  @if($item->first()->keterangan == 'BELUM LUNAS')
                    <input type="hidden" name="kurangAwal" class="kurangAwal" value="{{ $kurang }}">
                    <tr class="text-dark">
                      <td class="text-center align-middle">{{ $i }}</td>
                      <td class="text-center align-middle">
                        <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl" id="tglBayar{{$item->first()->id_bm}}" placeholder="DD-MM-YYYY" autocomplete="off" style="font-size: 16px">
                      </td>
                      <td class="text-right align-middle">
                        <input type="text" name="bayar" id="bayar{{$item->first()->id_bm}}" class="form-control form-control-sm text-bold text-dark text-right bayarModal" autocomplete="off" style="font-size: 16px">
                        <input type="hidden" name="modalAwal" class="modalAwal" value="0">
                      </td>
                      <td class="text-right align-middle">
                        <input type="text" name="kurang" id="kurang{{$item->first()->id_bm}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurang" style="font-size: 16px">
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                    </tr>
                  @endif
                  <tr style="font-size: 16px !important">
                    <td colspan="2" class="align-middle text-center text-bold text-dark" >Total</td>
                    <td class="text-right text-bold text-dark">
                      <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right totalAkhir" id="totalAkhir" style="font-size: 16px" value="{{ number_format($total, 0, "", ".") }}">
                    </td>
                    <td class="text-right text-bold text-dark">
                      <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurangAkhir" name="kurangAkhir" id="kurangAkhir" style="font-size: 16px" value="{{ number_format($kurang, 0, "", ".") }}">
                    </td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <button type="submit" class="btn btn-success btn-block text-bold" id="submitAP" onclick="return checkLimit(event)">Submit</button>
                    {{-- formaction="{{ route('ap-transfer') }}" formmethod="POST" --}}
                  </div>
                  <div class="col-2">
                    <button type="reset" data-dismiss="modal" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                  </div>
                  <div class="col-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                  </div>
                </div>
              @endif
              <!-- End Button Submit dan Reset -->

              <div class="modal" id="modalNotif" tabindex="-1" role="dialog" aria-labelledby="modalNotif" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title text-bold">Notifikasi Jumlah Cicil</h4>
                    </div>
                    <div class="modal-body text-dark">
                      <h5><b>Jumlah Cicil</b> tidak bisa melebihi <b>Jumlah Kurang Bayar</b>. Total Kurang Bayar untuk faktur <b>{{ $item->first()->id_bm }}</b> adalah <b>{{ number_format($kurang, 0, "", ".") }}</b></h5>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
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

const cicilAP = document.getElementById("cicilAP");
const tglBayar = document.querySelectorAll('.tglBayar');
const bayarModal = document.querySelectorAll('.bayarModal');
const modalAwal = document.querySelectorAll('.modalAwal');
const bayarDetil = document.querySelectorAll('.bayarDetil');
const bayarAwal = document.querySelectorAll('.bayarAwal');
const kurang = document.querySelectorAll('.kurang');
const kurangDetil = document.querySelectorAll('.kurangDetil');
const kurangAwal = document.querySelectorAll('.kurangAwal');
const hapusBaris = document.querySelectorAll(".icRemoveDetil");
const hapusBiasa = document.querySelectorAll(".icRemove");
const totalAkhir = document.getElementById('totalAkhir');
const kurangAkhir = document.getElementById('kurangAkhir');
const subtotal = document.getElementById('subtotal');

// cicilAP.addEventListener("keypress", checkEnter);

/* function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;     
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
} */

for(let i = 0; i < bayarDetil.length; i++) {
  bayarDetil[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
  });

  bayarDetil[i].addEventListener("blur", function(e) {
    if(+e.target.value.replace(/\./g, "") > +bayarAwal[i].value) {
      kurangDetil[i].value = addCommas(kurangDetil[i].value.replace(/\./g, "") - (e.target.value.replace(/\./g, "") - +bayarAwal[i].value));
      totalAkhir.value = addCommas(+totalAkhir.value.replace(/\./g, "") + (+e.target.value.replace(/\./g, "") - +bayarAwal[i].value));
      kurangAkhir.value = addCommas(kurangAkhir.value.replace(/\./g, "") - (e.target.value.replace(/\./g, "") - +bayarAwal[i].value));
    } else {
      kurangDetil[i].value = addCommas(+kurangDetil[i].value.replace(/\./g, "") + (+bayarAwal[i].value - +e.target.value.replace(/\./g, "")));
      totalAkhir.value = addCommas(totalAkhir.value.replace(/\./g, "") - (+bayarAwal[i].value - +e.target.value.replace(/\./g, "")));
      kurangAkhir.value = addCommas(+kurangAkhir.value.replace(/\./g, "") + (+bayarAwal[i].value - +e.target.value.replace(/\./g, "")));
    }

    bayarAwal[i].value = e.target.value.replace(/\./g, "");
    for(let j = i+1; j < bayarDetil.length; j++) {
      kurangDetil[j].value = addCommas(+kurangDetil[j-1].value.replace(/\./g, "") - +bayarAwal[j].value);
    }
  });
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

for(let i = 0; i < bayarModal.length; i++) {
  bayarModal[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

    if (e.keyCode === 13) {
      displayTotalAkhir(e);
    }
  });

  bayarModal[i].addEventListener("blur", displayTotalAkhir) 

  function displayTotalAkhir(e) {
    if(+e.target.value.replace(/\./g, "") > +modalAwal[i].value) {
      if(kurangDetil.length != 0) 
        kurang[i].value = addCommas(kurangDetil[kurangDetil.length - 1].value.replace(/\./g, "") - (e.target.value.replace(/\./g, "") - +modalAwal[i].value));
      else
        kurang[i].value = addCommas(kurangAkhir.value.replace(/\./g, "") - (e.target.value.replace(/\./g, "") - +modalAwal[i].value));

      totalAkhir.value = addCommas(+totalAkhir.value.replace(/\./g, "") + (+e.target.value.replace(/\./g, "") - +modalAwal[i].value));
      kurangAkhir.value = addCommas(kurangAkhir.value.replace(/\./g, "") - (e.target.value.replace(/\./g, "") - +modalAwal[i].value));
    } else {
      if(kurangDetil.length != 0) 
        kurang[i].value = addCommas(+kurangDetil[kurangDetil.length - 1].value.replace(/\./g, "") + (+modalAwal[i].value - +e.target.value.replace(/\./g, "")));
      else
        kurang[i].value = addCommas(+kurangAkhir.value.replace(/\./g, "") + (+modalAwal[i].value - +e.target.value.replace(/\./g, "")));

      totalAkhir.value = addCommas(totalAkhir.value.replace(/\./g, "") - (+modalAwal[i].value - +e.target.value.replace(/\./g, "")));
      kurangAkhir.value = addCommas(+kurangAkhir.value.replace(/\./g, "") + (+modalAwal[i].value - +e.target.value.replace(/\./g, "")));
    }

    if(+e.target.value.replace(/\./g, "") > +kurangAkhir.value.replace(/\./g, "")) {
      modalAwal[i].value = '0';
      kurangAkhir.value = addCommas(+kurangAkhir.value.replace(/\./g, "") + +bayarModal[0].value.replace(/\./g, ""));
      totalAkhir.value = addCommas(+totalAkhir.value.replace(/\./g, "") - +bayarModal[0].value.replace(/\./g, ""));
    } else {
      modalAwal[i].value = e.target.value.replace(/\./g, "");
    }
  }
}

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    kurangAkhir.value = addCommas(+kurangAkhir.value.replace(/\./g, "") + +bayarDetil[i].value.replace(/\./g, ""));
    totalAkhir.value = addCommas(+totalAkhir.value.replace(/\./g, "") - +bayarDetil[i].value.replace(/\./g, ""));

    for(let j = i+1; j < hapusBaris.length; j++) {
      kurangDetil[j].value = addCommas(+kurangDetil[j].value.replace(/\./g, "") + +bayarDetil[i].value.replace(/\./g, ""));
    }

    if(kurang[0].value != '')
      kurang[0].value = addCommas(+kurang[0].value.replace(/\./g, "") + +bayarDetil[i].value.replace(/\./g, ""));

    const delRow = document.getElementById(i);
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $('table tr:last').prev().prev().prev().find('td:first-child').text();

    $(delRow).remove();
    for(let j = +curNum; j < +lastNum; j++) {
      $(tablePO).find('tr:nth-child('+j+') td:first-child').html(j);
    }

    const no = $('table tr:last').prev().find('td:first-child').text();
    $('table tr:last').prev().find('td:first-child').html(no-1);

    jumBaris.value -= 1;
  });
}

for(let i = 0; i < hapusBiasa.length; i++) {
  hapusBiasa[i].addEventListener("click", function (e) {
    kurangAkhir.value = addCommas(+kurangAkhir.value.replace(/\./g, "") + +bayarModal[i].value.replace(/\./g, ""));
    totalAkhir.value = addCommas(+totalAkhir.value.replace(/\./g, "") - +bayarModal[i].value.replace(/\./g, ""));

    $(this).parents('tr').find('input').val('');
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

function checkLimit(e) {
  if((+bayarModal[0].value.replace(/\./g, "") > '{{ $kurang }}') && (+bayarModal[0].value.replace(/\./g, "") != 0)) {
    document.getElementById("submitAP").dataset.toggle = "modal";
    document.getElementById("submitAP").dataset.target = "#modalNotif";
    return false;
  } else {
    document.getElementById("submitAP").removeAttribute('data-toggle');
    document.getElementById("submitAP").removeAttribute('data-target');

    document.getElementById("submitAP").formMethod = "POST";
    document.getElementById("submitAP").formAction = "{{ route('ap-transfer') }}";
  }
}

</script>
@endpush