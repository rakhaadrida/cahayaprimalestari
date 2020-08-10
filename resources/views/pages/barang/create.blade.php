@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Tambah Data Barang</h1>
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
            <form action="{{ route('barang.store') }}" method="POST">
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold">Kode </label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode" 
                  value="{{ $newcode }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-1 col-form-label text-bold">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <input type="text" class="form-control col-form-label-sm" name="nama" placeholder="Nama Barang" value="{{ old('nama') }}">
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label for="ukuran" class="col-1 col-form-label text-bold">Ukuran</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="ukuran" placeholder="Ukuran Pack" value="{{ old('ukuran') }}">
                </div>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="ukuran" placeholder="Ukuran Satuan" value="{{ old('ukuran') }}">
                </div>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="ukuran" placeholder="Ukuran Kapasitas" value="{{ old('ukuran') }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-1"></label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm" name="ukuran" placeholder="Volume per Satuan" value="{{ old('ukuran') }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="isi" class="col-1 col-form-label text-bold">Isi</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="isi" placeholder="Isi Per Pack" 
                  value="{{ old('isi') }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="isi" class="col-1 text-bold">Daya Muat</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm mt-1" name="kapasitas" placeholder="Kubik Per Pack" value="{{ old('isi') }}">
                </div>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm mt-1" name="kapasitas" placeholder="Tonase Per Pack" value="{{ old('isi') }}">
                </div>
              </div>
              <hr>
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