@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Data Barang {{ $item->first()->nama }}</h1>
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
                  value="{{ $item->first()->id }}" >
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-2 col-form-label text-bold">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold"
                  value="{{ $item->first()->nama }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="kategori" class="col-2 col-form-label text-bold">Kategori</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{ $item->first()->namaJenis }}" >
                </div>
              </div>
              <div class="form-group row">
                <label for="kategori" class="col-2 col-form-label text-bold">Sub Kategori</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{ $item->first()->namaSub }}" >
                </div>
              </div>
              <div class="form-group row">
                <label for="satuan" class="col-2 col-form-label text-bold">Ukuran / Satuan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{$item->first()->ukuran}}  {{$item->first()->satuan}}" >
                </div>
              </div>
              <hr>
              @if((Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'SUPER') || (Auth::user()->roles == 'AR'))
                @foreach($harga as $h)
                  <div class="form-group row">
                    <label for="ukuran" class="col-2 col-form-label text-bold">{{ $h->nama }}</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2">
                      @foreach($hargaBarang as $hb)
                        @if(($hb->id_harga == $h->id) && ($hb->id_barang == $item->first()->id))
                          <input type="text" readonly class="form-control col-form-label-sm text-dark text-bold" value="{{ number_format($hb->harga_ppn, 0, "", ".") }}" >
                          @break
                        @endif
                      @endforeach
                    </div>
                  </div>
                @endforeach
                <hr>
                @foreach($gudang as $g)
                  @php
                    if($g->tipe == 'RETUR') {
                      $stok = \App\Utilities\Helper::getStokGudangRetur($item->first()->id, $g->id);
                    } else {
                      $stok = \App\Utilities\Helper::getStokGudangBiasa($item->first()->id, $g->id);
                    }
                  @endphp
                  <div class="form-group row">
                    <label for="ukuran" class="col-2 col-form-label text-bold">{{ $g->nama }}</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2">
                      <input type="text" readonly class="form-control-plaintext col-form-label-sm text-dark text-bold" value="{{ $stok->count() != 0 ? $stok[0]->stok : '' }} @if($stok->count() != 0) @if($item->first()->satuan == "Pcs / Dus") Pcs @elseif($item->first()->satuan == "Set") Set @elseif($item->first()->satuan == "Meter / Rol") Rol @else Meter @endif @endif" >
                    </div>
                  </div>
                @endforeach
                <hr>
              @endif
              <div class="form-row justify-content-center">
                @if((Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'SUPER'))
                  <div class="col-2">
                    <a href="{{ route('barang.edit', $item->first()->id) }}" class="btn btn-outline-primary btn-block text-bold">Ubah</a>
                  </div>
                  <div class="col-2">
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary btn-block text-bold">Kembali</a>
                  </div>
                @else
                  <div class="col-2">
                    <a href="{{ route('stok-office') }}" class="btn btn-outline-secondary btn-block text-bold">Kembali</a>
                  </div>
                @endif
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

@push('addon-script')
<script type="text/javascript">
</script>
@endpush
