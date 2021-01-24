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
      <h1 class="h3 mb-0 text-gray-800 menu-title">Histori Approval</h1>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-center text-bold text-dark">
            <tr align="center">
              <th>No</th>
              <th>No. Transaksi</th>
              <th>Tanggal Approval</th>
              <th>Customer / Supplier</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @forelse($items as $item)
              <tr class="text-dark">
                <td class="align-middle" align="center">{{ $i }}</td>
                <td class="align-middle" align="center">{{ $item->id_dokumen }}</td>
                <td class="align-middle" align="center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}</td>
                @if($item->tipe == 'Faktur') 
                  <td class="align-middle" align="center">{{ $item->so->customer->nama }}</td>
                @elseif($item->tipe == 'Dokumen') 
                  <td class="align-middle" align="center">{{ $item->bm->supplier->nama }}</td>
                @elseif($item->tipe == 'RJ') 
                  <td class="align-middle" align="center">{{ $item->rj->customer->nama }}</td>
                @elseif($item->tipe == 'RB') 
                  <td class="align-middle" align="center">{{ $item->rb->supplier->nama }}</td>
                @endif
                <td class="align-middle" align="center">{{ $item->status }}</td>
                <td class="align-middle">{{ $item->keterangan }}</td>
                <td class="align-middle" align="center">
                  <a href="{{ route('app-detail', $item->id_dokumen) }}" class="btn btn-success btn-sm"><i class="fas fa-fw fa-eye"></i></a>
                </td>
              </tr>
              @php $i++; @endphp
            @empty
              <tr>
                <td colspan="7" class="text-center">Tidak Ada Histori Approval</td>
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