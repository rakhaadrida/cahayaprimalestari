@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Data Barang Tidak Terpakai</h1>
    <a href="{{ route('barang-cianjur') }}" class="btn btn-sm btn-outline-primary shadow-sm">Kembali ke Data Barang</a>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
          <a href="{{ route('restore-barang-cianjur', 0) }}" class="btn btn-sm btn-primary shadow-sm mb-3">Kembalikan Semua Data</a>
          <a href="{{ route('destroy-barang-cianjur', 0) }}" class="btn btn-sm btn-outline-danger shadow-sm mb-3 ml-2">Hapus Permanen Semua Data</a>
          <thead class="text-bold text-dark">
            <tr align="center">
              <th style="width: 40px">No</th>
              <th>Nama</th>
              <th style="width: 90px">Stok</th>
              <th style="width: 70px">Detail</th>
              <th style="width: 80px">Kembalikan</th>
              <th style="width: 140px">Hapus Permanen</th>
            </tr>
          </thead>
          <tbody>
            @php $i=0; $j=1; @endphp
            @forelse($items as $key => $item)
              <tr class="text-dark">
                <td class="align-middle" align="center" style="width: 10px">{{ ++$key }}</td>
                <td class="align-middle">{{ $item->nama }}</td>
                <td class="align-middle" align="center" style="width: 45px">{{ $mapStockByProduct[$item->id] ?? '' }}</td>
                <td align="center" style="width: 15px">
                  <a href="{{ route('show-barang-cianjur', $item->id) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-fw fa-eye"></i>
                  </a>
                </td>
                <td class="align-middle" align="center">
                  <a href="{{ route('restore-barang-cianjur', $item->id) }}" class="btn btn-success btn-sm"><i class="fas fa-fw fa-undo"></i></a>
                </td>
                <td class="align-middle" align="center">
                  <a href="{{ route('destroy-barang-cianjur', $item->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-fw fa-eraser"></i></a>
                </td>
              </tr>
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
@endsection

@push('addon-script')
  <script src="{{ url('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ url('backend/js/demo/datatables-demo.js') }}"></script>
@endpush