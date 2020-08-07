@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Customer {{ $item->nama }}</h1>
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
            <form action="{{ route('customer.update', $item->id )}}" method="POST">
              @method('PUT')
              @csrf
              <div class="form-group">
                <label for="kode" class="text-bold">Kode</label>
                <input type="text" class="form-control" name="kode" value="{{ $item->id }}" readonly>
              </div>
              <div class="form-group">
                <label for="nama" class="text-bold">Nama</label>
                <input type="text" class="form-control" name="nama" value="{{ $item->nama }}">
              </div>
              <div class="form-group">
                <label for="alamat" class="text-bold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3">
                  {{ $item->alamat }}
                </textarea>   
              </div>
              <div class="form-group">
                <label for="telepon" class="text-bold">Telepon</label>
                <input type="text" class="form-control" name="telepon" value="{{ $item->telepon }}">
              </div>
              <div class="form-group">
                <label for="contact_person" class="text-bold">Contact Person</label>
                <input type="text" class="form-control" name="contact_person" placeholder="Contact Person" value="{{ $item->contact_person }}">
              </div>
              <div class="form-group">
                <label for="tempo" class="text-bold">Tempo</label>
                <input type="text" class="form-control" name="tempo" placeholder="Tempo" 
                  value="{{ $item->tempo }}">
              </div>
              <div class="form-group">
                <label for="limit" class="text-bold">Limit</label>
                <input type="text" class="form-control" name="limit" placeholder="Limit" 
                  value="{{ $item->limit }}">
              </div>
              <div class="form-group">
                <label for="sales_cover" class="text-bold">Sales Cover</label>
                <input type="text" class="form-control" name="sales_cover" placeholder="Sales Cover" 
                  value="{{ $item->sales_cover }}">
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