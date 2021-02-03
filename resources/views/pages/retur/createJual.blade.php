@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Retur Penjualan</h1>
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
            <form action="" method="" id="formRJ">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold">Nomor Retur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" readonly class="form-control form-control-sm text-bold" name="kode" id="kodeSO" value="{{ $newcode }}">
                      </div>
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal Retur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="1" class="form-control datepicker form-control-sm text-bold" name="tanggal" id="tglRetur" value="{{ $tanggal }}" autocomplete="off" required autofocus>
                      </div>
                    </div>  
                  </div>
                </div>
                <div class="form-group row ">
                  <label for="nama" class="col-2 col-form-label text-bold">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" tabindex="2" class="form-control form-control-sm text-bold" name="namaCustomer" id="namaCustomer" required>
                  </div>
                  <div class="col-2 mt-1">
                    <input type="text" readonly class="form-control form-control-sm text-bold" name="kodeCustomer" id="kodeCustomer">
                  </div>
                </div>
                <input type="hidden" name="jumBaris" id="jumBaris" value="5">
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil BM-->
              <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
              </span>
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <td style="width: 50px">No</td>
                  <td style="width: 150px">Kode Barang</td>
                  <td >Nama Barang</td>
                  <td style="width: 150px">Qty Retur</td>
                  <td style="width: 50px">Hapus</td>
                </thead>
                <tbody id="tablePO">
                  @php $tab = 2 @endphp
                  @for($i=1; $i<=5; $i++)
                    <tr class="text-bold text-dark" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" tabindex="{{ $tab++ }}" name="kodeBarang[]" id="kodeBarang" class="form-control form-control-sm text-bold text-dark kodeBarang" value="{{ old('kodeBarang[]') }}" @if($i == 1) required @endif >
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 2 }}" name="namaBarang[]" id="namaBarang" class="form-control form-control-sm text-bold text-dark namaBarang" value="{{ old('namaBarang[]') }}" @if($i == 1) required @endif>
                      </td>
                      <td> 
                        <input type="text" tabindex="{{ $tab += 3 }}" name="qty[]" id="qty" class="form-control form-control-sm text-bold text-dark text-center qty" value="{{ old('qty[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" @if($i == 1) required @endif>
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                    </tr>
                  @endfor
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" tabindex="{{ $tab++ }}"  id="submitRJ" onclick="return checkRequired(event)" class="btn btn-success btn-block text-bold">Submit</button>
                  {{-- formaction="{{ route('ret-process-jual', $newcode) }}" formmethod="POST" --}}
                </div>
                <div class="col-2">
                  <button type="reset" tabindex="{{ $tab += 2 }}" id="resetRJ" class="btn btn-outline-danger btn-block text-bold">Reset All </button> 
                </div>
                <div class="col-2">
                  <a href="{{ url()->previous() }}" tabindex="{{ $tab += 3 }}" id="backRJ" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                </div>
              </div>
              <!-- End Button Submit dan Reset -->

              <div class="modal" id="modalNotif" tabindex="-1" role="dialog" aria-labelledby="modalNotif" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title text-bold">Notifikasi Barang</h4>
                    </div>
                    <div class="modal-body text-dark">
                      <h5>Terdapat <b>Kode Barang</b> yang sama. Silahkan <b>Jumlahkan Qty pada Kode Barang yang Sama </b>atau ubah kode barang.</h5>
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

const formRJ = document.getElementById("formRJ");
const tglRetur = document.getElementById("tglRetur");
const namaCustomer = document.getElementById("namaCustomer");
const kodeCustomer = document.getElementById("kodeCustomer");
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const hapusBaris = document.querySelectorAll(".icRemove");
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');
var tab = '{{ $tab }}';

tglRetur.addEventListener("keyup", formatTanggal);
namaCustomer.addEventListener("keyup", displayCust);
namaCustomer.addEventListener("blur", displayCust);
newRow.addEventListener("click", displayRow);
formRJ.addEventListener("keypress", checkEnter);

function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;     
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
}

/** Add New Table Line **/
function displayRow(e) {
  const lastRow = $(tablePO).find('tr:last').attr("id");
  const lastNo = $(tablePO).find('tr:last td:first-child').text();
  var newNum = +lastRow + 1;
  var newNo = +lastNo + 1;
  const newTr = `
    <tr class="text-bold text-dark" id="${newNum}">
      <td align="center" class="align-middle">${newNo}</td>
      <td>
        <input type="text" tabindex="${tab++}" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark kdBrgRow">
      </td>
      <td>
        <input type="text" tabindex="${tab += 2}" name="namaBarang[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark nmBrgRow">
      </td>
      <td> 
        <input type="text" tabindex="${tab += 3}" name="qty[]" id="qtyRow${newNum}" class="form-control form-control-sm text-bold text-dark text-center qtyRow" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
      </td>
      <td align="center" class="align-middle">
        <a href="#" class="icRemoveRow" id="icRemoveRow${newNum}">
          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
        </a>
      </td>
    </tr>
  `; 

  $(tablePO).append(newTr);
  jumBaris.value = newNum;
  const newRow = document.getElementById(newNum);
  const brgRow = document.getElementById("nmBrgRow"+newNum);
  const kodeRow = document.getElementById("kdBrgRow"+newNum);
  const qtyRow = document.getElementById("qtyRow"+newNum);
  const hapusRow = document.getElementById("icRemoveRow"+newNum);
  kodeRow.focus();
  document.getElementById("submitRJ").tabIndex = tab++;
  document.getElementById("resetRJ").tabIndex = tab++;
  document.getElementById("backRJ").tabIndex = tab++;

  /** Tampil Harga **/
  kodeRow.addEventListener("keyup", displayBarangRow);
  brgRow.addEventListener("keyup", displayBarangRow);
  kodeRow.addEventListener("blur", displayBarangRow);
  brgRow.addEventListener("blur", displayBarangRow);

  function displayBarangRow(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qtyRow.removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->id }}' == e.target.value) || ('{{ $br->nama }}' == e.target.value)) {
        kodeRow.value = '{{ $br->id }}';
        brgRow.value = '{{ $br->nama }}';
      }
    @endforeach
  }

  /** Inputan hanya bisa angka **/
  qtyRow.addEventListener("keypress", function (e, evt) {
    evt = (evt) ? evt : window.event;
    var charCodeRow = (evt.which) ? evt.which : evt.keyCode;
    if (charCodeRow > 31 && (charCodeRow < 48 || charCodeRow > 57)) {
      $(qtyRow).tooltip('show');
      
      e.preventDefault();
    }
    
    return true;
  });
  
  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $(tablePO).find('tr:last').attr("id");
    var numRow;

    if(+curNum < +lastNum) {
      $(newRow).remove();
      for(let i = +curNum; i < +lastNum; i++) {
        $(tablePO).find('tr:nth-child('+i+') td:first-child').html(i);
      }
      numRow = lastNum;
    }
    else if(+curNum == +lastNum) {
      $(newRow).remove();
      numRow = +curNum - 1;
    }
    jumBaris.value -= 1;
    if(jumBaris.value > 5)
      document.getElementById("kdBrgRow"+numRow).focus();
    else
      kodeBarang[4].focus();
  });

  /** Autocomplete Nama  Barang **/
  $(function() {
    var idBarang = [];
    var nmBarang = [];
    @foreach($barang as $b)
      idBarang.push('{{ $b->id }}');
      nmBarang.push('{{ $b->nama }}');
    @endforeach
      
    function split(val) {
      return val.split(/,\s/);
    }

    function extractLast(term) {
      return split(term).pop();
    }

    $(kodeRow).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        response($.ui.autocomplete.filter(idBarang, extractLast(request.term)));
      },
      focus: function() {
        return false;
      },
      select: function(event, ui) {
        var terms = split(this.value);
        terms.pop();
        terms.push(ui.item.value);
        terms.push("");
        this.value = terms.join("");
        return false;
      }
    });
    
    $(brgRow).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        response($.ui.autocomplete.filter(nmBarang, extractLast(request.term)));
      },
      focus: function() {
        return false;
      },
      select: function(event, ui) {
        var terms = split(this.value);
        terms.pop();
        terms.push(ui.item.value);
        terms.push("");
        this.value = terms.join("");
        return false;
      }
    });
  }); 
}

function formatTanggal(e) {
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if(value.length > 2 && value.length <= 4) 
    value = value.slice(0,2) + "-" + value.slice(2);
  else if(value.length > 4 && value.length <= 8)
    value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
  
  tglRetur.value = value;
}

/** Tampil Id Supp **/
function displayCust(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      kodeCustomer.value = '{{ $c->id }}';
    }
    else if(e.target.value == '') {
      kodeCustomer.value = '';
    }
  @endforeach
}

/** Tampil Harga Barang **/
for(let i = 0; i < brgNama.length; i++) {
  kodeBarang[i].addEventListener("keyup", displayBarang);
  brgNama[i].addEventListener("keyup", displayBarang);
  kodeBarang[i].addEventListener("blur", displayBarang);
  brgNama[i].addEventListener("blur", displayBarang);

  function displayBarang(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->id }}' == e.target.value) || ('{{ $br->nama }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
        qty[i].setAttribute('required', 'true');
      }
    @endforeach
  }
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

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    for(let j = i; j < hapusBaris.length; j++) {
      if(j+1 != hapusBaris.length) {
        qty[j].value = qty[j+1].value;
        brgNama[j].value = brgNama[j+1].value;
        kodeBarang[j].value = kodeBarang[j+1].value;
        if(kodeBarang[j+1].value == "")
          qty[j].removeAttribute('required');
        else
          qty[j+1].removeAttribute('required');
      } else {
        qty[j].value = '';
        brgNama[j].value = '';
        kodeBarang[j].value = '';
      }
    }

    // $(this).parents('tr').next().find('input').val('');
    for(let j = 0; j < kodeBarang.length; j++) {
      if(kodeBarang[j].value == '') {
        kodeBarang[j].focus();
        break;
      }
    }
  });
}

function checkRequired(e) {
  const kdRow = document.querySelectorAll('.kdBrgRow');
  document.getElementById("submitRJ").removeAttribute('data-toggle');
  document.getElementById("submitRJ").removeAttribute('data-target');
  cek = 0;
  var kode = [];
  for(let i = 0; i < (jumBaris.value - kdRow.length); i++) {
    if(kodeBarang[i].value != '') {
      kode.push(kodeBarang[i].value);
    }
  }

  for(let i = 0; i < kdRow.length; i++) {
    if(kdRow[i].value != '') {
      kode.push(kdRow[i].value);
    }
  }

  cek = new Set(kode).size !== kode.length;

  if(cek === true) {
    document.getElementById("submitRJ").dataset.toggle = "modal";
    document.getElementById("submitRJ").dataset.target = "#modalNotif";
    return false;
  } 
  else {
    document.getElementById("submitRJ").formMethod = "POST";
    document.getElementById("submitRJ").formAction = "{{ route('ret-process-jual', $newcode) }}";
  }
}

/** Autocomplete Input Kode PO **/
$(function() {
  var customer = [];
  @foreach($customer as $c)
    customer.push('{{ $c->nama }}');
  @endforeach

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

  /*-- Autocomplete Input Barang --*/
  $(namaCustomer).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(customer, extractLast(request.term)));
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

  /*-- Autocomplete Input Nama Barang --*/
  $(namaBarang).on("keydown", function(event) {
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