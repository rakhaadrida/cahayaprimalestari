@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Transfer Barang</h1>
  </div>
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <div class="card show">
          <div class="card-body">
            <form action="" method="">
              @csrf
              <!-- Tabel Detil Transaksi Harian -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="text-center text-bold text-dark">
                  <th style="width: 60px" class="align-middle">No</th>
                  <th style="width: 140px" class="align-middle">Nomor TB</th>
                  <th style="width: 180px" class="align-middle">Tgl TB</th>
                  <th style="width: 160px" class="align-middle">User</th>
                </thead>
                <tbody>
                  @php $i = 1; $tab = 0; @endphp
                  @forelse ($items as $item)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="text-center"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('tb-detail', $item->id) }}" formmethod="POST" class="btn btn-sm btn-link text-bold">{{ $item->id }}</button></td>
                      <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->tgl_tb)->format('d-M-y')  }}</td>
                      <td class="text-center align-middle">{{ $item->user->name }}</td>
                    </tr>
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-bold text-dark h4 py-2">Tidak Ada Data Transfer Barang</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <!-- End Tabel Detil Transaksi Harian -->
              
            </form>
          </div>
        </div>
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