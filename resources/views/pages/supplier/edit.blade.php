@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Supplier {{ $item->nama }}</h1>
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
            <form action="{{ route('supplier.update', $item->id )}}" method="POST">
              @method('PUT')
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold ">Kode</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="kode" 
                  value="{{ $item->id }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-1 col-form-label text-bold ">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-6">
                  <input type="text" class="form-control col-form-label-sm" name="nama" value="{{ $item->nama }}" autocomplete="off" required autofocus>
                </div>
              </div>
              <hr>  
              <div class="form-group row">
                <label for="alamat" class="col-1 col-form-label text-bold ">Alamat</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-8">
                  <textarea class="form-control col-form-label-sm" name="alamat" required>{{ $item->alamat }}</textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="telepon" class="col-1 col-form-label text-bold">Telepon</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="telepon" value="{{ $item->telepon }}" onkeypress="return angkaSaja(event)" id="telepon" autocomplete="off" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="npwp" class="col-1 col-form-label text-bold">NPWP</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="npwp" 
                  value ="@if($item->npwp != ""){{ $item->npwp }}@else-@endif"
                  onkeypress="return angkaSaja(event)" autocomplete="off" >
                </div>
              </div>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold">Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                </div>
                <div class="col-2">
                  <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
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
const telepon = document.getElementById("telepon");

telepon.addEventListener("keyup", formatPhone);

function angkaSaja(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
  }
  return true;
}

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

$(function() {
  $("[autofocus]").on("focus", function() {
    if (this.setSelectionRange) {
      var len = this.value.length * 2;
      this.setSelectionRange(len, len);
    } else {
      this.value = this.value;
    }
    this.scrollTop = 999999;
  }).focus();
});
</script>
@endpush