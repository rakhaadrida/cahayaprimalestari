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
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold ">Kode</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode" placeholder="Kode Supplier" 
                  value="{{ $newcode }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-1 col-form-label text-bold ">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-6">
                  <input type="text" class="form-control col-form-label-sm" name="nama" placeholder="Nama Supplier" 
                  value="{{ old('nama') }}">
                </div>
              </div>
              <hr>  
              <div class="form-group row">
                <label for="alamat" class="col-1 col-form-label text-bold ">Alamat</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-8">
                  <input type="text" class="form-control col-form-label-sm" name="alamat" placeholder="Nama Jalan, Pertokoan, Nomor Toko" 
                    value="{{ old('alamat') }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-1"></label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm ml-1" name="kecamatan" placeholder="Kecamatan" 
                    value="{{ old('kecamatan') }}">
                </div>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kota" placeholder="Kota" 
                    value="{{ old('kota') }}">
                </div>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm" name="propinsi" placeholder="Propinsi" 
                    value="{{ old('propinsi') }}">
                </div>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode-pos" placeholder="Kode Pos" 
                    value="{{ old('kode-pos') }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="telepon" class="col-1 col-form-label text-bold">Telepon</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="telepon" placeholder="021-xxxxx" 
                    value="{{ old('telepon') }}">
                </div>
              </div>
              <hr>
              {{-- <hr>  
              <div class="form-group col-3">
                <label for="telepon" class="text-bold">Telepon</label>
                <input type="text" class="form-control col-form-label-sm" name="telepon" placeholder="Nomor Telepon Supplier" 
                  value="{{ old('telepon') }}">
              </div> --}}
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