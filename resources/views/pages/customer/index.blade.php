@extends('pages.customer.show')
@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Data Customer</h1>
    <div class="justify-content-end">
      <a href="{{ route('customer.create') }}" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i>  Tambah Customer
      </a>
      <span class="vertical-hr mr-2 ml-1"></span>
      <a href="{{ route('cus-trash') }}" class="btn btn-sm btn-outline-danger shadow-sm">
        <i class="fas fa-trash-alt fa-sm text-dark-50 mr-1"></i>  Data Tak Terpakai
      </a>
      <span class="vertical-hr mr-2 ml-1"></span>
      <a href="{{ route('cus-excel') }}" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-file-excel fa-sm text-dark-50 mr-1"></i>  Download Excel
      </a>
      {{-- <button type="submit" formaction="{{ route('cus-excel') }}" formmethod="POST" class="btn btn-sm btn-success shadow-sm"><i class="fas fa-file-excel fa-sm text-dark-50 mr-1"></i>Download Excel</button> --}}
    </div>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-center text-bold text-dark">
            <tr align="center">
              <th style="width: 20px">No</th>
              <th>Nama</th>
              <th style="width: 340px">Alamat</th>
              <th style="width: 110px">Telepon</th>
              <th style="width: 65px">Sales</th>
              <th>Detail</th>
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
                <td class="align-middle">{{ $item->alamat }}</td>
                <td class="align-middle">{{ $item->telepon }}</td>
                <td class="align-middle">
                  @if($item->id_sales != '') {{ $item->namaSales }} @endif
                </td>
                <td class="align-middle" align="center">
                  <a href="#DetailCustomer{{ $item->id }}" class="btn btn-success btn-sm" data-toggle="modal">
                    <i class="fas fa-fw fa-eye"></i>
                  </a>
                </td>
                <td class="align-middle" align="center">
                  <a href="{{ route('customer.edit', $item->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-fw fa-edit"></i>
                  </a>
                </td>
                <td class="align-middle" align="center">
                  <form action="{{ route('customer.destroy', $item->id) }}" method="POST" class="d-inline">
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
