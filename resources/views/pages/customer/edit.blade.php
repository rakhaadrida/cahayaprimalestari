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
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold">Kode</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode" value="{{ $item->id }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-1 col-form-label text-bold">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-6">
                  <input type="text" class="form-control col-form-label-sm" name="nama" 
                  value="{{ $item->nama }}">
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label for="alamat" class="col-1 col-form-label text-bold">Alamat</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-7">
                  <textarea name="alamat" class="form-control col-form-label-sm" rows="3">
                    {{ $item->alamat }}
                  </textarea>  
                </div> 
              </div>
              <div class="form-group row">
                <label for="telepon" class="col-1 col-form-label text-bold">Telepon</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="telepon" value="{{ $item->telepon }}">
                </div>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="contact_person" value="{{ $item->contact_person }}">
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label for="tempo" class="col-1 col-form-label text-bold">Tempo</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="tempo" 
                  value="{{ $item->tempo }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="limit" class="col-1 col-form-label text-bold">Limit</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="limit" 
                  value="{{ $item->limit }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="sales_cover" class="col-1 text-bold">Sales Cover</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm mt-1" name="sales_cover" value="{{ $item->sales_cover }}">
                </div>
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