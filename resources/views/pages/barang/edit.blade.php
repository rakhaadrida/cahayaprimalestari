@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Barang {{ $item->nama }}</h1>
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
            <form action="{{ route('barang.update', $item->id )}}" method="POST">
              @method('PUT')
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold">Kode</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="kode" 
                  value="{{ $item->id }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-1 col-form-label text-bold">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <input type="text" class="form-control col-form-label-sm" name="nama" 
                  value="{{ $item->nama }}" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="kategori" class="col-1 col-form-label text-bold">Kategori</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kategori" placeholder="Kategori Barang" id="kategori" value="@if($item->id_kategori != '') {{ $item->jenis->nama }} @endif" required>
                </div>
                <input type="hidden" name="kodeJenis" id="kodeJenis" 
                value="@if($item->id_kategori != '') {{$item->id_kategori}} @endif">
              </div>
              <hr>
              <div class="form-group row">
                <label for="satuan" class="col-1 col-form-label text-bold">Satuan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-6">
                  <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="satuan" 
                    value="Pcs / Dus" @if($item->satuan == "Pcs / Dus") checked @endif required >
                    <label class="form-check-label font-weight-normal" for="satuan1">Pcs / Dus</label>
                  </div>
                  <div class="form-check form-check-inline ml-4">
                    <input class="form-check-input" type="radio" name="satuan" 
                    value="Meter / Rol" @if($item->satuan == "Meter / Rol") checked @endif>
                    <label class="form-check-label font-weight-normal" for="satuan2">Meter / Rol</label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="ukuran" class="col-1 col-form-label text-bold">Ukuran</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="ukuran" value="{{ number_format($item->ukuran, 0, "", ".") }}" id="ukuran" onkeypress="return angkaSaja(event)" id="ukuran" data-toogle="tooltip" data-placement="top" title="Hanya input angka 0-9" required>
                </div>
                <span class="col-form-label text-bold" id="labelUkuran">{{ $item->satuan }}</span>
              </div>
              <hr>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold">Update</button>
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
const kategori = document.getElementById('kategori');
const kodeJenis = document.getElementById('kodeJenis');
const ukuran = document.getElementById('ukuran');
const labelUkuran = document.getElementById('labelUkuran');
const radios = document.querySelectorAll('input[type=radio][name="satuan"]');

Array.prototype.forEach.call(radios, function(radio) {
   radio.addEventListener('change', displayUkuran);
});

kategori.addEventListener("keydown", function(e) {
  @foreach($jenis as $j)
    if('{{ $j->nama }}' == e.target.value) {
      kodeJenis.value = '{{ $j->id }}';
    }
  @endforeach
});

/** Tampil Label Satuan Ukuran **/
function displayUkuran(e) {
  labelUkuran.textContent = e.target.value;
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      $(ukuran).tooltip('show');

    return false;
  }
  return true;
}

/** Autocomplete Input Text **/
$(function() {
  var jenis = [];
  @foreach($jenis as $s)
    jenis.push('{{ $s->nama }}');
  @endforeach

  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Barang --*/
  $(kategori).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(jenis, extractLast(request.term)));
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