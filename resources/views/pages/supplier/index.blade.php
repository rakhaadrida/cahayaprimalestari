@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Supplier</h1>
      <a href="{{ route('supplier.create') }}" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i>  Tambah Supplier
      </a>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-center text-bold text-dark">
            <tr align="center">
              <th>No</th>
              <th>Nama</th>
              <th>Alamat</th>
              <th>Telepon</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @forelse ($items as $item)
              <tr class="text-dark">
                <td align="center" class="align-middle">{{ $i }}</td>
                <td class="align-middle">{{ $item->nama }}</td>
                <td class="align-middle">{{ $item->alamat }}</td>
                <td class="align-middle">{{ $item->telepon }}</td>
                <td class="align-middle" align="center">
                  <a href="{{ route('supplier.edit', $item->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-fw fa-edit"></i>
                  </a>
                </td>
                <td class="align-middle" align="center">
                  <form action="{{ route('supplier.destroy', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger btn-sm">
                      <i class="fas fa-fw fa-trash"></i>
                    </button>  
                  </form>
                </td>
              </tr>
              @php $i++; @endphp
            @empty
              <tr>
                <td colspan="8" class="text-center">Tidak Ada Data</td>
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