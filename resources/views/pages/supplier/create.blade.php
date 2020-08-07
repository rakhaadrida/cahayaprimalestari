@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Tambah Data Supplier</h1>
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
            <form action="{{ route('supplier.store') }}" method="POST">
              @csrf
              {{-- <div class="row">
                <div class="form-group col-2">
                  <label for="kode" class="text-bold">Kode </label>
                  <input type="text" class="form-control col-form-label-sm" name="kode" placeholder="Kode Supplier" 
                    value="{{ $newcode }}" readonly>
                </div>
                <div class="form-group col-6">
                  <label for="nama" class="text-bold">Nama</label>
                  <input type="text" class="form-control col-form-label-sm" name="nama" placeholder="Nama Supplier" 
                    value="{{ old('nama') }}">
                </div>
              </div>   --}}
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold">Kode :</label>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode" placeholder="Kode Supplier" 
                  value="{{ $newcode }}" readonly>
                </div>
                <label for="nama" class="col-form-label text-bold ml-5">Nama :</label>
                <div class="col-6">
                  <input type="text" class="form-control col-form-label-sm" name="nama" placeholder="Nama Supplier" 
                  value="{{ old('nama') }}">
                </div>
              </div>
              <hr>  
              <div class="row">
                <div class="form-group col-7">
                  <label for="alamat" class="text-bold">Alamat</label>
                  <input type="text" class="form-control col-form-label-sm" name="alamat" placeholder="Alamat" 
                    value="{{ old('alamat') }}">
                </div>
                <div class="form-group col-3">
                  <label for="kecamatan" class="text-bold">Kecamatan</label>
                  <input type="text" class="form-control col-form-label-sm" name="kecamatan" placeholder="Kecamatan" 
                    value="{{ old('kecamatan') }}">
                </div>
              </div> 
              <div class="row">
                <div class="form-group col-3">
                  <label for="kota" class="text-bold">Kota</label>
                  <input type="text" class="form-control col-form-label-sm" name="kota" placeholder="Kota" 
                    value="{{ old('kota') }}">
                </div>
                <div class="form-group col-3">
                  <label for="propinsi" class="text-bold">Propinsi</label>
                  <input type="text" class="form-control col-form-label-sm" name="propinsi" placeholder="Propinsi" 
                    value="{{ old('propinsi') }}">
                </div>
                <div class="form-group col-2">
                  <label for="kode-pos" class="text-bold">Kode Pos</label>
                  <input type="text" class="form-control col-form-label-sm" name="kode-pos" placeholder="Kode Pos" 
                    value="{{ old('kode-pos') }}">
                </div>
              </div> 
              <div class="row">
                <div class="form-group col-3">
                  <label for="telepon" class="text-bold">Telepon</label>
                  <input type="text" class="form-control col-form-label-sm" name="telepon" placeholder="Nomor Telepon Supplier" 
                    value="{{ old('telepon') }}">
                </div>
              </div>
              <hr>  
              <div class="form-group col-3">
                <label for="telepon" class="text-bold">Telepon</label>
                <input type="text" class="form-control col-form-label-sm" name="telepon" placeholder="Nomor Telepon Supplier" 
                  value="{{ old('telepon') }}">
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