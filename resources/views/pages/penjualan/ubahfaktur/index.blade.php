@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
    <h1 class="h3 mb-0 text-gray-800 menu-title">
      @if(Auth::user()->roles == 'FINANCE') Cek Faktur @else Ubah Faktur @endif
    </h1>
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
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">
                <div class="form-group row">
                  <label for="kode" class="col-2 col-form-label text-bold">Nomor SO</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="id" id="kode" autofocus>
                  </div>
                  {{-- @if(Auth::user()->roles != 'FINANCE') --}}
                    <label for="tanggal" class="col-auto col-form-label text-bold ">Nama Customer</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-4">
                      <input type="text" class="form-control form-control-sm text-bold mt-1" id="namaCustomer" name="nama">
                      <input type="hidden" name="kode" id="kodeCustomer">
                    </div>
                  {{-- @endif --}}
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Tanggal Awal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" placeholder="DD-MM-YYYY">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Tanggal Akhir</label>
                  <span class="col-form-label text-bold ml-3">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAkhir" id="tglAkhir" placeholder="DD-MM-YYYY">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('so-show') }}" formmethod="GET" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.container-fluid -->
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

const namaCust = document.getElementById('namaCustomer');
const kodeCust = document.getElementById('kodeCustomer');
const tglAwal = document.getElementById('tglAwal');
const tglAkhir = document.getElementById('tglAkhir');
const kodeSO = document.getElementById('kode');

tglAwal.addEventListener("keyup", formatTanggal);
tglAkhir.addEventListener("keyup", formatTanggal);
namaCust.addEventListener("keyup", displayKode);

/** Call Fungsi Setelah Inputan Terisi **/
function formatTanggal(e) {
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if(value.length > 2 && value.length <= 4) 
    value = value.slice(0,2) + "-" + value.slice(2);
  else if(value.length > 4 && value.length <= 8)
    value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
  
  if(e.target.id == 'tglAwal')
    tglAwal.value = value;
  else
    tglAkhir.value = value;
}

function displayKode(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
    }
    else if(e.target.value == '') {
      kodeCust.value = '';
    }
  @endforeach
}

/** Autocomplete Input Text **/
$(function() {
  var customer = [];
  var kodeFaktur = [];
  @foreach($customer as $c)
    customer.push('{{ $c->nama }}');
  @endforeach
  @foreach($so as $s)
    kodeFaktur.push('{{ $s->id }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Customer --*/
  $(namaCust).on("keydown", function(event) {
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

  /*-- Autocomplete Input Kode SO --*/
  $(kodeSO).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kodeFaktur, extractLast(request.term)));
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