@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Data Barang</h1>
    @if((Auth::user()->roles != 'OFFICE02') && (Auth::user()->roles != 'GUDANG'))
      <div class="justify-content-end">
        <a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary shadow-sm">
          <i class="fas fa-plus fa-sm text-white-50 mr-1"></i>  Tambah Barang
        </a>
        <span class="vertical-hr mr-2 ml-1"></span>
        <a href="{{ route('barang-trash') }}" class="btn btn-sm btn-outline-danger shadow-sm">
          <i class="fas fa-trash-alt fa-sm text-dark-50 mr-1"></i>  Data Tak Terpakai
        </a>
      </div>
    @endif
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-bold text-dark">
            <tr align="center">
              <th>No</th>
              <th>Nama</th>
              @if((Auth::user()->roles != 'OFFICE02') && (Auth::user()->roles != 'AR'))
                @foreach($gudang as $g)
                  <th style="width: 40px">{{ substr($g->nama, 0, 3) }}</th>
                @endforeach
              @else
                <th style="width: 140px">Stok</th>
              @endif
              <th>Detail</th>
              @if((Auth::user()->roles != 'OFFICE02') && (Auth::user()->roles != 'GUDANG') && (Auth::user()->roles != 'AR'))
                <th>Harga</th>
                <th>Stok</th>
                <th>Ubah</th>
                <th>Hapus</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @php $i = 0; $j = 1; 
              if(Auth::user()->roles != 'OFFICE02') 
                $items = $itemsBrg;
              else
                $items = $itemsBrgOff;
            @endphp
            @forelse($items as $item)
              <tr class="text-dark">
                <td class="align-middle" align="center" style="width: 10px">{{ $j }}</td>
                <td class="align-middle">{{ $item->nama }}</td>
                @if((Auth::user()->roles != 'OFFICE02') && (Auth::user()->roles != 'AR'))
                  @foreach($gudang as $g)
                    @php
                      if($g->tipe == 'RETUR') {
                        $stok = App\Models\StokBarang::selectRaw('sum(stok) as stok')
                                ->where('id_barang', $item->id)
                                ->where('id_gudang', $g->id)->get();   
                      } else {
                        $stok = App\Models\StokBarang::where('id_barang', $item->id)
                                ->where('id_gudang', $g->id)->get();  
                      }
                    @endphp
                    <td class="align-middle" align="center" style="width: 45px">{{ $stok->count() != 0 ? $stok[0]->stok : '' }}</td>
                  @endforeach
                @else
                  @php 
                    $stok = App\Models\StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                            ->selectRaw('sum(stok) as stok')
                            ->where('id_barang', $item->id)
                            ->where('tipe', '!=', 'RETUR')->get();
                  @endphp
                  <td class="align-middle" align="center" style="width: 45px">{{ $stok->count() != 0 ? $stok[0]->stok : '' }}</td>
                @endif
                <td align="center" style="width: 15px">
                  {{-- <a href="#DetailBarang{{ $item->id }}" class="btn btn-sm btn-success" data-toggle="modal">
                    <i class="fas fa-fw fa-eye"></i>
                  </a> --}}
                  <a href="{{ route('detailBarang', $item->id) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-fw fa-eye"></i>
                  </a>
                </td>
                @if((Auth::user()->roles != 'OFFICE02') && (Auth::user()->roles != 'GUDANG') && (Auth::user()->roles != 'AR'))
                  <td align="center" style="width: 15px">
                    <a href="{{ route('hargaBarang', $item->id) }}" class="btn btn-sm btn-warning">
                      <i class="fas fa-fw fa-money-bill-alt"></i>
                    </a>
                  </td>
                  <td align="center" style="width: 15px">
                    <a href="{{ route('stokBarang', $item->id) }}" class="btn btn-sm btn-primary">
                      <i class="fas fa-fw fa-warehouse"></i>
                    </a>
                  </td>
                  <td align="center" style="width: 15px">
                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-info">
                      <i class="fas fa-fw fa-edit"></i>
                    </a>
                  </td>
                  <td align="center" style="width: 20px">
                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('delete')
                      <button class="btn btn-sm btn-danger">
                        <i class="fas fa-fw fa-trash"></i>
                      </button>  
                    </form>
                  </td>
                @endif
              </tr>
              @php $j++; @endphp
            @empty
              <tr>
                <td colspan="8" class="text-center text-bold h4 p-2">Tidak Ada Data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
</div>
<!-- /.container-fluid -->
@endsection

@push('addon-script')
  <script src="{{ url('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ url('backend/js/demo/datatables-demo.js') }}"></script>

  <script type="text/javascript">
    $('#dataTable').dataTable({
      "pageLength": 100
    });
  </script>
@endpush