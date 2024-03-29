@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Stok {{ $barang->nama }}</h1>
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
            <form action="{{ route('storeStok')}}" method="POST">
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-2 col-form-label text-bold">Kode Barang</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="kode"
                  value="{{ $barang->id }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-2 col-form-label text-bold">Nama Barang</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="nama"
                  value="{{ $barang->nama }}" readonly>
                </div>
              </div>
              <hr>
              @php $i=0; @endphp
              @foreach($gudang as $g)
                <div class="form-group row">
                  <label for="harga" class="col-2 col-form-label text-bold">
                    {{ $g->nama }}</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-1">
                    <input type="text" class="form-control col-form-label-sm text-right"
                    name="stok[]" required @if($g->id == $gudang[0]->id) autofocus @endif
                      @foreach($items as $item)
                        @if($item->id_gudang == $g->id)
                          value="{{ $item->stok }}"
                          @break
                        @endif
                      @endforeach
                      @if(Auth::user()->roles != 'SUPER') readonly @endif
                    />
                  </div>
                  <span class="col-form-label text-bold">
                    @if($barang->satuan == "Pcs / Dus") Pcs @elseif($barang->satuan == "Set") Set @elseif($barang->satuan == "Meter / Rol") Rol @else Meter @endif
                  </span>
                </div>
                @php $i++; @endphp
              @endforeach
              <hr>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold">Update</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                </div>
                <div class="col-2">
                  <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
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
