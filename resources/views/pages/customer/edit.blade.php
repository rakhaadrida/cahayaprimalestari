@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Customer {{ $item->nama }}</h1>
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
            <form action="{{ route('customer.update', $item->id )}}" method="POST">
              @method('PUT')
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold">Kode</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode" value="{{ $item->id }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-1 col-form-label text-bold">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-6">
                  <input type="text" class="form-control col-form-label-sm" name="nama" 
                  value="{{ $item->nama }}" required>
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label for="alamat" class="col-1 col-form-label text-bold">Alamat</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-7">
                  <textarea name="alamat" class="form-control col-form-label-sm" rows="2" required>{{$item->alamat}}</textarea>  
                </div> 
              </div>
              <div class="form-group row">
                <label for="telepon" class="col-1 col-form-label text-bold">Telepon</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="telepon" value="{{ $item->telepon }}" onkeypress="return angkaSaja(event, telepon)" id="telepon" data-toogle="tooltip" data-placement="right" title="Hanya input angka 0-9" required>
                </div>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="contact_person" value="{{ $item->contact_person }}" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="npwp" class="col-1 col-form-label text-bold">NPWP</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="npwp" 
                  onkeypress="return angkaSaja(event, npwp)" id="npwp" data-toogle="tooltip" data-placement="right" title="Hanya input angka 0-9"
                  value ="@if($item->npwp != ""){{ $item->npwp }}@else-@endif">
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label for="limit" class="col-1 col-form-label text-bold">Limit</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="limit" 
                  value="{{ number_format($item->limit, 0, "", ".") }}" onkeypress="return angkaSaja(event, limit)" id="limit" data-toogle="tooltip" data-placement="right" title="Hanya input angka 0-9" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="sales_cover" class="col-1 col-form-label text-bold" style="margin-top: -7px">Sales Cover</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm mt-1" name="namaSales" id="sales" value="@if($item->id_sales != '') {{ $item->sales->nama }} @endif" required>
                  <input type="hidden" name="id_sales" id="kodeSales" 
                  value="@if($item->id_sales != '') {{ $item->id_sales }} @endif">
                </div>
              </div>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold">Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
              </div>
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
<script type="text/javascript">
const namaSales = document.getElementById("sales");
const kodeSales = document.getElementById("kodeSales");
const telepon = document.getElementById("telepon");
const npwp = document.getElementById("npwp");
const limit = document.getElementById("limit");

namaSales.addEventListener('keyup', displayKode);
telepon.addEventListener("keyup", formatPhone);
limit.addEventListener("keyup", formatNominal);

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    if(inputan.id == "telepon")
      $(telepon).tooltip('show');
    else if(inputan.id == "npwp")
      $(npwp).tooltip('show');
    else if(inputan.id == "limit")
      $(limit).tooltip('show');

    return false;
  }
  return true;
}

/** Input telepon strip separator **/
function formatPhone(e){
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if((kode == "021") || (kode == "022") || (kode == "061") || (kode == "024") || (kode == "031")) {
    if(value.length > 3 && value.length <= 6) 
      value = value.slice(0,3) + "-" + value.slice(3);
    else if(value.length > 6 && value.length <= 9)
      value = value.slice(0,3) + "-" + value.slice(3,6) + "-" + value.slice(6);
    else if(value.length > 9)
      value = value.slice(0,3) + "-" + value.slice(3,6) + "-" + value.slice(6,9) + "-" + value.slice(9);
  }
  else
    if(value.length > 4 && value.length <= 8) 
      value = value.slice(0,4) + "-" + value.slice(4);
    else if(value.length > 8 && value.length <= 12)
      value = value.slice(0,4) + "-" + value.slice(4,8) + "-" + value.slice(8);
    else if(value.length > 12)
      value = value.slice(0,4) + "-" + value.slice(4,8) + "-" + value.slice(8,12) + "-" + value.slice(12);
  
  telepon.value = value;
}

/** Input telepon strip separator **/
function formatNominal(e){
  $(this).val(function(index, value) {
    return value
    .replace(/\D/g, "")
    .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    ;
  });
}

/** Display Kode Sales **/
function displayKode(e) {
  @foreach($sales as $s)
    if('{{ $s->nama }}' == e.target.value) {
      kodeSales.value = '{{ $s->id }}';
    }
    else if(e.target.value == '') {
      kodeSales.value = '';
    }
  @endforeach
}

/** Autocomplete Input Text **/
$(function() {
  var sales = [];
  @foreach($sales as $s)
    sales.push('{{ $s->nama }}');
  @endforeach

  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Barang --*/
  $(namaSales).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(sales, extractLast(request.term)));
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

  /*-- Autocomplete Input Supplier --*/
  $(namaSupplier).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(supplier, extractLast(request.term)));
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