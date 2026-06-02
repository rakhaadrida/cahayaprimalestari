@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding-top: 5px;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
  @endif
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
      <span class="vertical-hr mr-2 ml-1"></span>
      <button class="btn btn-sm btn-outline-success shadow-sm dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-file-excel fa-sm text-dark-50 mr-1"></i>  Import Data Barang
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <button class="dropdown-item" data-toggle="modal" data-target="#importHargaModal">Import Harga</button>
        <button class="dropdown-item" data-toggle="modal" data-target="#importStokModal">Import Stok</button>
      </div>
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

      <div class="modal fade" id="importHargaModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="h2 text-bold">&times;</span>
              </button>
              <h4 class="modal-title">Import Harga Barang</h4>
            </div>
            <div class="modal-body">
              <p>Silakan download template terlebih dahulu agar format excel sesuai sistem.</p>
              <a href="{{ route('excel-harga-cianjur') }}" class="btn btn-success btn-sm mb-1">Download Template</a>
              <hr>
              <form action="{{ route('import-harga-cianjur') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label>Upload File Excel</label>
                  <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input mb-1" accept=".xls,.xlsx" required>
                    <label class="custom-file-label" for="excelFile">
                        Pilih file
                    </label>
                    <ul class="small text-muted">
                      <li>Format: .xlsx</li>
                      <li>Maksimal 5MB</li>
                      <li>Jangan ubah nama kolom template</li>
                    </ul>
                  </div>
                </div>
                <button type="submit" id="btnImport" class="btn btn-sm btn-primary mb-2">Import Harga</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="importStokModal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="h2 text-bold">&times;</span>
              </button>
              <h4 class="modal-title">Import Stok Barang</h4>
            </div>
            <div class="modal-body">
              <p>Silakan download template terlebih dahulu agar format excel sesuai sistem.</p>
              <a href="{{ route('excel-stok-cianjur') }}" class="btn btn-success btn-sm mb-1">Download Template</a>
              <hr>
              <form action="{{ route('import-stok-cianjur') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label>Upload File Excel</label>
                  <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input mb-1" accept=".xls,.xlsx" required>
                    <label class="custom-file-label" for="excelFile">
                        Pilih file
                    </label>
                    <ul class="small text-muted">
                      <li>Format: .xlsx</li>
                      <li>Maksimal 5MB</li>
                      <li>Jangan ubah nama kolom template</li>
                    </ul>
                  </div>
                </div>
                <button type="submit" id="btnImport" class="btn btn-sm btn-primary mb-2">Import Stok</button>
              </form>
            </div>
          </div>
        </div>
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

    $('.custom-file-input').on('change', function () {
      let fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    $('form').submit(function() {
      $('#btnImport').prop('disabled', true).text('Processing...');
    });
  </script>
@endpush
