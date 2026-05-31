@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Data Barang {{ $item->nama }}</h1>
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
            <form action="" method="">
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-2 col-form-label text-bold">Kode</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold"
                  value="{{ $item->id }}" >
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-2 col-form-label text-bold">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold"
                  value="{{ $item->nama }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="kategori" class="col-2 col-form-label text-bold">Kategori</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{ $item->namaJenis }}" >
                </div>
              </div>
              <div class="form-group row">
                <label for="kategori" class="col-2 col-form-label text-bold">Sub Kategori</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{ $item->namaSub }}" >
                </div>
              </div>
              <div class="form-group row">
                <label for="satuan" class="col-2 col-form-label text-bold">Ukuran / Satuan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{$item->ukuran}}  {{$item->satuan}}" >
                </div>
              </div>
              <div class="form-group row">
                <label for="ukuran" class="col-2 col-form-label text-bold">Stok</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control-plaintext col-form-label-sm text-dark text-bold" value="{{ $stok ? $stok->stok : '' }} @if($stok) @if($item->satuan == "Pcs / Dus") Pcs @elseif($item->satuan == "Set") Set @elseif($item->satuan == "Meter / Rol") Rol @else Meter @endif @endif" >
                </div>
              </div>
              <hr>
              @foreach($harga as $h)
                <div class="form-group row">
                  <label for="ukuran" class="col-2 col-form-label text-bold">{{ $h->nama }}</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    @foreach($hargaBarang as $hb)
                      @if(($hb->id_harga == $h->id) && ($hb->id_barang == $item->id))
                        <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{ number_format($hb->harga_ppn, 0, "", ".") }}" >
                        @break
                      @endif
                    @endforeach
                  </div>
                </div>
              @endforeach
              <hr>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <a href="{{ route('edit-barang-cianjur', $item->id) }}" class="btn btn-outline-primary btn-block text-bold">Ubah</a>
                </div>
                <div class="col-2">
                  <a href="{{ route('barang-cianjur') }}" class="btn btn-outline-secondary btn-block text-bold">Kembali</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
<script type="text/javascript">
</script>
@endpush
