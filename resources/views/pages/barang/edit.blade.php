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
              <hr>
              <div class="form-group row">
                <label for="satuan" class="col-1 col-form-label text-bold">Satuan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-6">
                  <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="satuan" 
                    value="Pcs / Pack" @if($item->satuan == "Pcs / Pack") checked @endif required >
                    <label class="form-check-label font-weight-normal" for="satuan1">Pcs / Pack</label>
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
const ukuran = document.getElementById('ukuran');
const labelUkuran = document.getElementById('labelUkuran');
const radios = document.querySelectorAll('input[type=radio][name="satuan"]');

Array.prototype.forEach.call(radios, function(radio) {
   radio.addEventListener('change', displayUkuran);
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

</script>
@endpush