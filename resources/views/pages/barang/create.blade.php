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
              <div class="form-group">
                <label for="kode" class="text-bold">Kode </label>
                <input type="text" class="form-control" name="kode" placeholder="Kode Barang" 
                  value="{{ $newcode }}" readonly>
              </div>
              <div class="form-group">
                <label for="nama" class="text-bold">Nama</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Barang" 
                  value="{{ old('nama') }}">
              </div>
              <div class="form-group">
                <label for="ukuran" class="text-bold">Ukuran</label>
                <input type="text" class="form-control" name="ukuran" placeholder="Ukuran Barang" 
                  value="{{ old('ukuran') }}">
              </div>
              <div class="form-group">
                <label for="isi" class="text-bold">Isi</label>
                <input type="text" class="form-control" name="isi" placeholder="Isi Barang" 
                  value="{{ old('isi') }}">
              </div>
              <div class="form-row">
                <div class="col">
                  <button type="submit" class="btn btn-success btn-block text-bold">Submit</button>
                </div>
                <div class="col">
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