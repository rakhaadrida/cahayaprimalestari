@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Data Barang</h1>
    <div class="justify-content-end">
      <a href="{{ route('create-barang-cianjur') }}" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i>  Tambah Barang
      </a>
      <span class="vertical-hr mr-2 ml-1"></span>
      <a href="{{ route('deleted-barang-cianjur') }}" class="btn btn-sm btn-outline-danger shadow-sm">
        <i class="fas fa-trash-alt fa-sm text-dark-50 mr-1"></i>  Data Tak Terpakai
      </a>
      <span class="vertical-hr mr-2 ml-1"></span>
      <a href="{{ route('excel-barang-cianjur') }}" class="btn btn-sm btn-success shadow-sm">
        <i class="fas fa-file-excel fa-sm text-dark-50 mr-1"></i>  Download Excel
      </a>
    </div>
  </div>
  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-bold text-dark">
            <tr align="center">
              <th>No</th>
              <th>Nama</th>
              <th style="width: 90px">Stok</th>
              <th style="width: 70px">Detail</th>
              {{-- <th style="width: 70px">Harga</th> --}}
              {{-- <th style="width: 70px">Stok</th> --}}
              <th style="width: 70px">Ubah</th>
              <th style="width: 70px">Hapus</th>
            </tr>
          </thead>
          <tbody>
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
                {{-- <td align="center" style="width: 15px">
                  <a href="{{ route('create-harga-barang-cianjur', $item->id) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-fw fa-money-bill-alt"></i>
                  </a>
                </td>
                <td align="center" style="width: 15px">
                  <a href="{{ route('create-stok-barang-cianjur', $item->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-fw fa-warehouse"></i>
                  </a>
                </td> --}}
                <td align="center" style="width: 15px">
                  <a href="{{ route('edit-barang-cianjur', $item->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-fw fa-edit"></i>
                  </a>
                </td>
                <td align="center" style="width: 20px">
                  <form action="{{ route('delete-barang-cianjur', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('delete')
                    <button class="btn btn-sm btn-danger">
                      <i class="fas fa-fw fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-bold h4 p-2">Tidak Ada Data</td>
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

  <script type="text/javascript">
    $('#dataTable').dataTable({
      "pageLength": 100,
      "columnDefs": [
        { 
            orderable: false, 
            targets: [3, 4, 5]
        }
      ]
    });
  </script>
@endpush
