@extends('pages.barang.show')
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
      <a href="{{ route('barang.create') }}" class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i>  Tambah Barang
      </a>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr align="center">
              <th>No</th>
              <th>Nama</th>
              @foreach($gudang as $g)
                <th>{{ $g->nama }}</th>
              @endforeach
              <th>Detail</th>
              <th>Harga</th>
              <th>Stok</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
          </thead>
          <tbody>
            @php $i=0; $j=1; @endphp
            @forelse($items as $item)
              <tr>
                <td align="center" style="width: 10px">{{ $j }}</td>
                <td>{{ $item->nama }}</td>
                @foreach($gudang as $g)
                  @if(($stok->count() > 0) && ($i < $stok->count()))
                    @if(($stok[$i]->id_gudang == $g->id) && ($stok[$i]->id_barang == $item->id))
                      <td align="center" style="width: 45px">{{ $stok[$i]->stok }}</td>
                      @php $i++; @endphp
                    @else
                      <td></td>  
                    @endif
                  @else
                    <td></td>
                  @endif
                @endforeach
                <td align="center" style="width: 15px">
                  <a href="#DetailBarang{{ $item->id }}" class="btn btn-sm btn-success" data-toggle="modal">
                    <i class="fas fa-fw fa-eye"></i>
                  </a>
                </td>
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
              </tr>
              @php $j++; @endphp
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