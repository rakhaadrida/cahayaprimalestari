@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Data Customer {{ $item->nama }}</h1>
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
                <label for="kode">Kode</label>
                <input type="text" class="form-control" name="kode" value="{{ $item->id }}">
              </div>
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" name="nama" value="{{ $item->nama }}">
              </div>
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" class="form-control" rows="3">
                  {{ $item->alamat }}
                </textarea>   
              </div>
              <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="text" class="form-control" name="telepon" value="{{ $item->telepon }}">
              </div>
              <div class="form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" class="form-control" name="contact_person" placeholder="Contact Person" value="{{ $item->contact_person }}">
              </div>
              <div class="form-group">
                <label for="tempo">Tempo</label>
                <input type="text" class="form-control" name="tempo" placeholder="Tempo" 
                  value="{{ $item->tempo }}">
              </div>
              <div class="form-group">
                <label for="limit">Limit</label>
                <input type="text" class="form-control" name="limit" placeholder="Limit" 
                  value="{{ $item->limit }}">
              </div>
              <div class="form-group">
                <label for="sales_cover">Sales Cover</label>
                <input type="text" class="form-control" name="sales_cover" placeholder="Sales Cover" 
                  value="{{ $item->sales_cover }}">
              </div>
              <button type="submit" class="btn btn-primary btn-block">Update</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</div>
<!-- /.container-fluid -->
@endsection