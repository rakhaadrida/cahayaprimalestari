@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Tambah Data Barang</h1>
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
                <label for="kode">Kode </label>
                <input type="text" class="form-control" name="kode" placeholder="Kode Barang" 
                  value="{{ old('nama') }}">
              </div>
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Barang" 
                  value="{{ old('nama') }}">
              </div>
              <div class="form-group">
                <label for="alamat">Ukuran</label>
                <input type="text" class="form-control" name="ukuran" placeholder="Ukuran Barang" 
                  value="{{ old('ukuran') }}">
              </div>
              <div class="form-group">
                <label for="telepon">Isi</label>
                <input type="text" class="form-control" name="isi" placeholder="Isi Barang" 
                  value="{{ old('isi') }}">
              </div>
              <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>
<!-- /.container-fluid -->
@endsection