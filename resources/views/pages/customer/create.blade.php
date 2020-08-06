@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Tambah Data Customer</h1>
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
            <form action="{{ route('customer.store') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="kode">Kode </label>
                <input type="text" class="form-control" name="kode" placeholder="Kode Customer" 
                  value="{{ $newcode }}" readonly>
              </div>
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" name="nama" placeholder="Nama Customer" 
                  value="{{ old('nama') }}">
              </div>
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3">
                  {{ old('alamat') }}
                </textarea>
              </div>
              <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="text" class="form-control" name="telepon" placeholder="Nomor Telepon Customer" 
                  value="{{ old('telepon') }}">
              </div>
              <div class="form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" class="form-control" name="contact_person" placeholder="Contact Person" 
                  value="{{ old('contact_person') }}">
              </div>
              <div class="form-group">
                <label for="tempo">Tempo</label>
                <input type="text" class="form-control" name="tempo" placeholder="Tempo" 
                  value="{{ old('tempo') }}">
              </div>
              <div class="form-group">
                <label for="limit">Limit</label>
                <input type="text" class="form-control" name="limit" placeholder="Limit" 
                  value="{{ old('limit') }}">
              </div>
              <div class="form-group">
                <label for="sales_cover">Sales Cover</label>
                <input type="text" class="form-control" name="sales_cover" placeholder="Sales Cover" 
                  value="{{ old('sales_cover') }}">
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