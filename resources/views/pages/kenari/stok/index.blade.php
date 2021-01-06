@extends('pages.kenari.stok.show')
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
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-bold text-dark">
            <tr align="center">
              <th>No</th>
              <th>Nama</th>
              <th style="width: 110px">Stok Kenari</th>
              <th style="width: 110px">Stok Lain</th>
              <th style="width: 70px">Detail</th>
            </tr>
          </thead>
          <tbody>
            @php $j = 1; @endphp
            @forelse($items as $item)
              <tr class="text-dark">
                <td class="align-middle" align="center" style="width: 10px">{{ $j }}</td>
                <td class="align-middle">{{ $item->nama }}</td>
                @php
                  $stokKenari = App\Models\StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                                ->selectRaw('sum(stok) as stok')->where('tipe', 'KENARI')
                                ->where('id_barang', $item->id)->get(); 
                  $stokLain = App\Models\StokBarang::join('gudang', 'gudang.id', 'stok.id_gudang')
                                ->selectRaw('sum(stok) as stok')->where('tipe', '!=', 'KENARI')
                                ->where('id_barang', $item->id)->where('status', 'T')->get();
                @endphp
                <td class="align-middle" align="center" style="width: 45px">{{ $stokKenari->count() != 0 ? $stokKenari[0]->stok : '' }}</td>
                <td class="align-middle" align="center" style="width: 45px">{{ $stokLain->count() != 0 ? $stokLain[0]->stok : '' }}</td>
                <td align="center" style="width: 15px">
                  <a href="#DetailBarang{{ $item->id }}" class="btn btn-sm btn-success" data-toggle="modal">
                    <i class="fas fa-fw fa-eye"></i>
                  </a>
                </td>
              </tr>
              @php $j++; @endphp
            @empty
              <tr>
                <td colspan="8" class="text-center text-bold h4 p-2">Tidak Ada Data Barang</td>
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