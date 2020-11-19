@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Data Sub Jenis Barang</h1>
    <div class="justify-content-end">
      <a href="{{ route('subjenis.create') }}" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i>  Tambah Sub Jenis Barang
      </a>
      <span class="vertical-hr mr-2 ml-1"></span>
      <a href="{{ route('sub-trash') }}" class="btn btn-sm btn-outline-danger shadow-sm">
        <i class="fas fa-trash-alt fa-sm text-dark-50 mr-1"></i>  Data Tak Terpakai
      </a>
    </div>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-center text-bold text-dark">
            <tr align="center">
              <th style="width: 80px">No</th>
              <th style="width: 500px">Nama</th>
              <th style="width: 160px">Kategori</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @forelse ($items as $item)
              <tr class="text-dark">
                <td class="align-middle" align="center">{{ $i }}</td>
                <td class="align-middle">{{ $item->nama }}</td>
                <td class="text-center align-middle">@if($item->id_kategori != '') {{ $item->jenis->nama }} @endif</td>
                <td align="center">
                  <a href="{{ route('subjenis.edit', $item->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-fw fa-edit"></i>
                  </a>
                </td>
                <td align="center">
                  <form action="{{ route('subjenis.destroy', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('delete')
                    <button class="btn btn-sm btn-danger">
                      <i class="fas fa-fw fa-trash"></i>
                    </button>  
                  </form>
                </td>
              </tr>
              @php $i++; @endphp
            @empty
              <tr>
                <td colspan="5" class="text-center">Tidak Ada Data</td>
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