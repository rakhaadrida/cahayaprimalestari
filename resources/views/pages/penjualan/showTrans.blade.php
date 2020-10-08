@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Transaksi Harian</h1>
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
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">  
                <div class="form-group row justify-content-center" >
                  <label for="kode" class="col-auto col-form-label text-bold">Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" value="{{ $tglAwal }}">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s/d</label>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAkhir" value="{{ $tglAkhir }}">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="" formmethod="" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Tabel Detil Transaksi Harian -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="text-center text-bold text-dark">
                  <th style="width: 20px" class="align-middle">No</th>
                  <th style="width: 70px" class="align-middle">Nomor Faktur</th>
                  <th style="width: 80px" class="align-middle">Tgl Faktur</th>
                  <th class="align-middle">Customer</th>
                  <th style="width: 80px" class="align-middle">Total</th>
                  <th style="width: 80px" class="align-middle">Kategori</th>
                  <th class="align-middle">Tempo</th>
                  <th style="width: 120px" class="align-middle">Status</th>
                </thead>
                <tbody>
                  @php $i=1; @endphp
                  @forelse ($items as $item)
                    <tr>
                      <td align="center">{{ $i }}</td>
                      <td class="text-center"><button type="submit" formaction="{{ route('trans-detail', $item->id) }}" formmethod="POST" class="btn btn-link text-bold">{{ $item->id }}</button></td>
                      <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-m-Y')  }}</td>
                      <td>{{ $item->customer->nama }}</td>
                      <td class="text-right">{{ number_format($item->total, 0, "", ",") }}</td>
                      <td class="text-center">{{ $item->kategori }}</td>
                      <td class="text-center">{{ $item->tempo }} Hari</td>
                      <td>{{ $item->status }}</td>
                    </tr>
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan="8" class="text-center">Tidak Ada Data Transaksi pada Tanggal Ini</td>
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