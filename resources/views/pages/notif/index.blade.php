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
      <h1 class="h3 mb-0 text-gray-800 menu-title">Notifikasi</h1>
      <a href="{{ route('notif-read-all') }}" class="btn btn-sm btn-success shadow-sm mb-3">Tandai Semua Sudah Dibaca</a>
  </div>

  <div class="row">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
          <thead class="text-center text-bold text-dark">
            <tr align="center">
              <th class="align-middle" style="width: 30px">No</th>
              <th class="align-middle" style="width: 70px">No. Dokumen</th>
              <th class="align-middle" style="width: 80px">Tgl Approve</th>
              <th class="align-middle">Customer / Supplier</th>
              <th class="align-middle" style="width: 70px">Status</th>
              <th class="align-middle">Keterangan</th>
              <th class="align-middle" style="width: 50px">Detail</th>
              <th class="align-middle" style="width: 130px">Action</th>
            </tr>
          </thead>
          <tbody>
            @php $i=1; @endphp
            @forelse($items as $item)
              @if((($item->so != NULL) && ($item->so->user->roles != 'KENARI')) || (($item->bm != NULL) && ($item->bm->user->roles != 'KENARI')))
                <tr class="text-dark">
                  <td class="align-middle" align="center">{{ $i }}</td>
                  <td class="align-middle" align="center">{{ $item->id_dokumen }}</td>
                  <td class="align-middle" align="center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}</td>
                  <td class="align-middle">
                    @if($item->tipe == 'Faktur')
                      {{ $item->so->customer->nama }}
                    @else
                      {{ $item->bm->supplier->nama }}
                    @endif
                  </td>
                  <td class="align-middle" align="center">{{ $item->status }}</td>
                  <td class="align-middle">{{ $item->keterangan }}</td>
                  <td class="align-middle" align="center">
                    <a href="{{ route('notif-show', $item->id) }}" class="btn btn-success btn-sm">
                      <i class="fas fa-fw fa-eye"></i>
                    </a>
                  </td>
                  <td class="align-middle" align="center">
                    @if(($item->tipe == 'Faktur') && (($item->status == 'UPDATE') || ($item->status == 'APPROVE_LIMIT')))
                      <a href="{{ route('cetak-faktur', ['status' => 'false', 'awal' => '0', 'akhir' => '0']) }}" class="btn btn-primary btn-sm" style="width: 150px">Cetak</a>
                    @else
                      <a href="{{ route('notif-read', $item->id) }}" class="btn btn-info btn-sm">Tandai Sudah Dibaca</a>
                    @endif
                  </td>
                </tr>
                @php $i++; @endphp
              @endif
            @empty
              <tr>
                <td colspan="8" class="text-center text-bold h4 p-2">Tidak Ada Data Notifikasi</td>
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