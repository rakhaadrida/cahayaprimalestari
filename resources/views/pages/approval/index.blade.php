@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" 
  rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Approval</h1>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr align="center">
              <th>No</th>
              <th>No. Transaksi</th>
              <th>Tanggal</th>
              <th>Customer</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @forelse ($items as $item)
              <tr>
                <td align="center">{{ $i }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->tgl_so }}</td>
                <td>{{ $item->customer->nama }}</td>
                <td>{{ $item->status }}</td>
                @php $ket = \App\Models\NeedApproval::where('id_so', $item->id)->first(); @endphp
                <td>{{ $ket['keterangan'] }}</td>
                <td align="center">
                  <a href="{{ route('app-show', $item->id) }}" class="btn btn-success">
                    <i class="fas fa-fw fa-eye"></i>
                  </a>
                </td>
              </tr>
              @php $i++; @endphp
            @empty
              <tr>
                <td colspan="7" class="text-center">Tidak Ada Data Approval</td>
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
    
  </script>
@endpush