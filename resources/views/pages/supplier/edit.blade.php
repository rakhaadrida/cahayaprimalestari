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
              <div class="form-group">
                <label for="kode" class="text-bold">Kode</label>
                <input type="text" class="form-control col-form-label-sm" name="kode" value="{{ $item->id }}" readonly>
              </div>
              <div class="form-group">
                <label for="nama" class="text-bold">Nama</label>
                <input type="text" class="form-control col-form-label-sm" name="nama" value="{{ $item->nama }}" required>
              </div>
              <div class="form-group">
                <label for="alamat" class="text-bold">Alamat</label>
                <textarea name="alamat" class="form-control col-form-label-sm" required>{{$item->alamat}}</textarea>   
              </div>
              <div class="form-group">
                <label for="telepon" class="text-bold">Telepon</label>
                <input type="text" class="form-control col-form-label-sm" name="telepon" value="{{ $item->telepon }}" required>
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