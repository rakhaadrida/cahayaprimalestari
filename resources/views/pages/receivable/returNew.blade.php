@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Retur Tagihan Faktur {{ $item->first()->id_so }}</h1>
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
            <form action="" method="">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier BM -->
               <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="1" readonly class="form-control form-control-sm text-bold text-dark" name="kodeSO" value="{{ $item->first()->id_so }}" >
                        <input type="hidden" name="kode" value="{{ $item->first()->id }}">
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="2" readonly class="form-control datepicker form-control-sm text-bold text-dark" name="tanggal" value="{{ $item->first()->so->tgl_so }}">
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -360px">
                    <div class="form-group row subtotal-po">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Sub Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right text-dark" name="subtotal" id="subtotal" value={{$retur->first()->total != 0 ? number_format($retur->first()->total, 0, "", ".") : ''}}>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" tabindex="5" name="namaCust" readonly class="form-control form-control-sm text-bold text-dark" value="{{ $item->first()->so->customer->nama }}" />
                  </div>
                  <input type="hidden" name="jumBaris" id="jumBaris" value="5">
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->

              <!-- Tabel Data Detil BM-->
              @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                  Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
                </span>
              @endif
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <tr class="text-center">
                    <th rowspan="2" class="align-middle" style="width: 30px">No</th>
                    <th rowspan="2" class="align-middle"style="width: 100px">Kode</th>
                    <th rowspan="2" class="align-middle">Nama Barang</th>
                    <th rowspan="2" class="align-middle"style="width: 110px">Tgl. Retur</th>
                    <th rowspan="2" class="align-middle"style="width: 60px">Qty</th>
                    <th rowspan="2" class="align-middle"style="width: 90px">Harga</th>
                    <th rowspan="2" class="align-middle"style="width: 100px">Jumlah</th>
                    <th colspan="2">Diskon</th>
                    <th rowspan="2" class="align-middle"style="width: 110px">Total</th>
                  </tr>
                  <tr class="text-center">
                    <th style="width: 90px">%</th>
                    <th style="width: 110px">Rupiah</th>
                  </tr>
                </thead>
                <tbody id="tablePO" class="table-ar">
                  @php $i = 1; $totalQty = 0; $totalRet = 0; @endphp
                  @foreach($retur as $d)
                    <tr class="table-modal-first-row text-dark" >
                      <td class="text-center" >{{ $i }}</td>
                      <td class="text-center">{{ $d->id_barang }}</td>
                      <td>{{ $d->barang->nama }}</td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($d->tgl_retur)->format('d-M-y') }}</td>
                      <td class="text-right">{{ $d->qty }}</td>
                      {{-- @php $kurang -= $d->cicil; @endphp --}}
                      <td class="text-right">{{ number_format($d->harga, 0, "", ".") }}</td>
                      <td class="text-right">{{ number_format($d->qty * $d->harga, 0, "", ".") }}</td>
                      <td class="text-right">{{ $d->diskon }}</td>
                      <td class="text-right">{{ number_format($d->diskonRp, 0, "", ".") }}</td>
                      <td class="text-right">{{ number_format(($d->qty * $d->harga) - $d->diskonRp, 0, "", ".") }}</td>
                    </tr>
                    @php $i++; $totalQty += $d->qty; $totalRet += (($d->qty * $d->harga) - $d->diskonRp) @endphp
                  @endforeach
                  @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                    @for($j = $i; $j <= $i+4; $j++)
                      <tr class="text-dark" id="{{ $j }}">
                        <td class="text-center align-middle">{{ $j }}</td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark kodeBarang" name="kodeBarang{{$item->first()->id}}[]" id="kodeBarang{{$item->first()->id}}">
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark namaBarang" name="namaBarang{{$item->first()->id}}[]" id="namaBarang{{$item->first()->id}}">
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglRetur" name="tglRetur{{$item->first()->id}}[]" id="tglRetur{{$item->first()->id}}" placeholder="DD-MM-YYYY" autocomplete="off">
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark text-right qty" name="qty{{$item->first()->id}}[]" id="qty{{$item->first()->id}}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                        </td>
                        <td class="align-middle">
                          <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" name="harga{{$item->first()->id}}[]" id="harga{{$item->first()->id}}">
                        </td>
                        <td class="text-right align-middle">
                          <input type="text" name="jumlah{{$item->first()->id}}[]" id="jumlah{{$item->first()->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah">
                        </td>
                        <td class="align-middle" style="width: 90px">
                          <input type="text" name="diskon{{$item->first()->id}}[]" id="diskon{{$item->first()->id}}" class="form-control form-control-sm text-bold text-dark text-right diskon" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9, tanda +, dan tanda koma" autocomplete="off">
                        </td>
                        <td class="text-right align-middle" style="width: 110px">
                          <input type="text" name="diskonRp{{$item->first()->id}}[]" id="diskonRp{{$item->first()->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right diskonRp">
                        </td>
                        <td class="text-right align-middle">
                          <input type="text" name="netto{{$item->first()->id}}[]" id="netto{{$item->first()->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto">
                        </td>
                      </tr>
                    @endfor
                  @endif 
                </tbody>
                <tfoot>
                  <tr class="text-right text-bold text-dark">
                    <td colspan="4" class="align-middle text-center ">Total</td>
                    <td>{{ number_format($totalQty, 0, "", ".") }}</td>
                    <td colspan="4"></td>
                    <td>{{ number_format($totalRet, 0, "", ".") }}</td>
                  </tr>
                </tfoot>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ar-retur') }}" formmethod="POST">Submit</button>
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

const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const tglRetur = document.querySelectorAll('.tglRetur');
const qty = document.querySelectorAll('.qty');
const harga = document.querySelectorAll('.harga');
const jumlah = document.querySelectorAll('.jumlah');
const diskon = document.querySelectorAll('.diskon');
const diskonRp = document.querySelectorAll('.diskonRp');
const netto = document.querySelectorAll('.netto');
const total = document.querySelectorAll('.total');

for(let i = 0; i < kodeBarang.length; i++) {
  brgNama[i].addEventListener("keyup", displayHarga) ;
  kodeBarang[i].addEventListener("keyup", displayHarga);
  brgNama[i].addEventListener("blur", displayHarga) ;
  kodeBarang[i].addEventListener("blur", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
      }
    @endforeach

    qty[i].value = '';
  }
}

for(let i = 0; i < tglRetur.length; i++) {
  tglRetur[i].addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4) 
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
    
    tglRetur[i].value = value;
  });
}

for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("keyup", function(e) {
    if(e.target.value == "") {
      // total[i].value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      diskonRp[i].value = "";
      netto[i].value = "";
    }
    else {  
      // netPast = +jumlah[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas(angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100);
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      // checkSubtotal(netPast, +jumlah[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
  });
}

for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      // netPast = jumlah[i].value.replace(/\./g, "");
      netto[i].value = addCommas(+netto[i].value.replace(/\./g, "") + +diskonRp[i].value.replace(/\./g, ""))
      // checkSubtotal(netPast, jumlah[i].value.replace(/\./g, ""));
      diskonRp[i].value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      console.log(angkaDiskon);
      netPast = +netto[i].value.replace(/\./g, "");
      diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""))
      // checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    // totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    // grandtotal.value = totalNotPPN.value;
  });
}

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  angka = angka.replace(/\,/g, ".");
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon =  ((totDiskon - 100) * -1).toFixed(2);
  return totDiskon;
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

/** Inputan hanya bisa angka dan plus **/
function angkaPlus(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && charCode != 43 && charCode != 44  && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= diskon.length; i++) {
      if(inputan == i)
        $(diskon[inputan-1]).tooltip('show');
    }
    return false;
  }
  return true;
}

/* function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
    totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
    totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") + (+Now - +Past));
  }
} */

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

/** Autocomplete Input Kode PO **/
$(function() {
  var kode = [];
  var nama = [];
  @foreach($barang as $b)
    kode.push('{{ $b->id }}');
    nama.push('{{ $b->nama }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Nama Barang --*/
  $(brgNama).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(nama, extractLast(request.term)));
    },
    focus: function() {
      // prevent value inserted on focus
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      // remove the current input
      terms.pop();
      // add the selected item
      terms.push(ui.item.value);
      // add placeholder to get the comma-and-space at the end
      terms.push("");
      this.value = terms.join("");
      return false;
    }
  });

  /*-- Autocomplete Input Kode Barang --*/
  $(kodeBarang).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kode, extractLast(request.term)));
    },
    focus: function() {
      // prevent value inserted on focus
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      // remove the current input
      terms.pop();
      // add the selected item
      terms.push(ui.item.value);
      // add placeholder to get the comma-and-space at the end
      terms.push("");
      this.value = terms.join("");
      return false;
    }
  });
});

</script>
@endpush