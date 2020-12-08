@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Data Stok Barang Retur</h1>
    {{-- <div class="justify-content-end">
      <a href="{{ route('ret-index-jual') }}" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i>  Input Retur Penjualan
      </a>
      <span class="vertical-hr mr-2 ml-1"></span>
      <a href="{{ route('ret-index-beli') }}" class="btn btn-sm btn-outline-danger shadow-sm">
        <i class="fas fa-trash-alt fa-sm text-dark-50 mr-1"></i>  Input Retur Pembelian
      </a>
    </div> --}}
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-bold text-dark">
            <tr align="center">
              <th rowspan="2" class="align-middle" style="width: 80px">No</th>
              <th rowspan="2"class="align-middle">Nama</th>
              {{-- @foreach($gudang as $g)
                <th rowspan="2" style="width: 80px">{{ $g->nama }}</th>
              @endforeach --}}
              <th colspan="2">Stok</th>
              <th rowspan="2" class="align-middle" style="width: 180px">Total</th>
            </tr>
            <tr align="center">
              <th style="width: 160px">Bagus</th>
              <th style="width: 160px">Jelek</th>
            </tr>
          </thead>
          <tbody>
            @php $i=0; $j=1; @endphp
            @forelse($items as $item)
              <tr class="text-dark">
                <td class="align-middle" align="center" style="width: 10px">{{ $j }}</td>
                <td class="align-middle">{{ $item->barang->nama }}</td>
                @php
                  $bagus = \App\Models\StokBarang::where('id_barang', $item->id_barang)
                          ->where('id_gudang', $item->id_gudang)
                          ->where('status', 'T')->get();
                  $jelek = \App\Models\StokBarang::where('id_barang', $item->id_barang)
                          ->where('id_gudang', $item->id_gudang)
                          ->where('status', 'F')->get(); 
                  $total = \App\Models\StokBarang::selectRaw('sum(stok) as total')
                          ->where('id_barang', $item->id_barang)
                          ->where('id_gudang', $item->id_gudang)->get(); 
                @endphp
                <td class="align-middle text-center">{{ $bagus->count() != 0 ? $bagus[0]->stok : '' }}</td>
                <td class="align-middle text-center">{{ $jelek->count() != 0 ? $jelek[0]->stok : '' }}</td>
                <td class="align-middle text-center">{{ $total->count() != 0 ? $total[0]->total : '' }}</td>
              </tr>
              @php $j++; @endphp
            @empty
              <tr>
                <td colspan="6" class="text-center">Tidak Ada Data</td>
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
@endpush