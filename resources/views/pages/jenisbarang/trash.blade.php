@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Data Jenis Barang Tidak Terpakai</h1>
    <a href="{{ route('jenis.index') }}" class="btn btn-sm btn-outline-primary shadow-sm">
      Kembali ke Data Jenis Barang
    </a>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
          <a href="{{ route('jb-restoreAll') }}" class="btn btn-sm btn-primary shadow-sm mb-3">Kembalikan Semua Data</a>
          <a href="{{ route('jb-hapusAll') }}" class="btn btn-sm btn-outline-danger shadow-sm mb-3 ml-2">Hapus Permanen Semua Data</a>
          <thead class="text-center text-bold text-dark">
            <tr align="center">
              <th style="width: 80px">No</th>
              <th style="width: 580px">Nama</th>
              <th>Kembalikan</th>
              <th>Hapus Permanen</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @forelse ($items as $item)
              <tr class="text-dark">
                <td class="align-middle" align="center">{{ $i }}</td>
                <td class="align-middle">{{ $item->nama }}</td>
                <td class="align-middle" align="center">
                  <a href="{{ route('jb-restore', $item->id) }}" class="btn btn-success btn-sm"><i class="fas fa-fw fa-undo"></i></a>
                </td>
                <td class="align-middle" align="center">
                  <a href="{{ route('jb-hapus', $item->id) }}" class="btn btn-danger btn-sm"><i class="fas fa-fw fa-eraser"></i></a>
                </td>
              </tr>
              @php $i++; @endphp
            @empty
              <tr>
                <td colspan="4" class="text-center">Tidak Ada Data</td>
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