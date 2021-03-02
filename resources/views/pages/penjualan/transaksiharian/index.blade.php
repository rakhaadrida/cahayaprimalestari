@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
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
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" placeholder="DD-MM-YYYY" autocomplete="off" autofocus>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s/d</label>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAkhir" id="tglAkhir" placeholder="DD-MM-YYYY" autocomplete="off">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" id="btn-cari" formaction="{{ route('trans-show') }}" 
                    formmethod="GET" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                  <div class="col-auto mt-1" style="margin-left: 30px">
                    <button type="submit" tabindex="5" formaction="{{ route('ar-cetak-now', 'All') }}" formmethod="POST" formtarget="_blank" id="btn-cari" class="btn btn-danger btn-sm btn-block text-bold ">Print All</button>
                  </div>
                  <div class="col-auto mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="5" formaction="{{ route('ar-cetak-now', 'Prime') }}" formmethod="POST" formtarget="_blank" id="btn-cari" class="btn btn-success btn-sm btn-block text-bold ">Print Prime</button>
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
                  <th style="width: 100px" class="align-middle">Status</th>
                  <th style="width: 60px" class="align-middle">User</th>
                </thead>
                <tbody>
                  @php $i=1; $tab = 3; @endphp
                  @forelse ($items as $item)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="text-center"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('trans-detail', $item->id) }}" formmethod="POST" class="btn btn-link btn-sm text-bold">{{ $item->id }}</button></td>
                      <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y')  }}</td>
                      <td class="align-middle">{{ $item->customer->nama }}</td>
                      <td class="text-right align-middle">{{ number_format($item->total, 0, "", ",") }}</td>
                      <td class="text-center align-middle">{{ $item->kategori }}</td>
                      <td class="text-center align-middle">{{ $item->tempo }} Hari</td>
                      <td class="text-center align-middle">{{ $item->status }}</td>
                      <td class="text-center align-middle">{{ $item->user->name }}</td>
                    </tr>
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan="9" class="text-center text-bold text-dark h4 py-2">Tidak Ada Data Transaksi pada Tanggal Ini</td>
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
<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
$.fn.datepicker.dates['id'] = {
  days:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],
  daysShort:["Mgu","Sen","Sel","Rab","Kam","Jum","Sab"],
  daysMin:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
  months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
  monthsShort:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"],
  today:"Hari Ini",
  clear:"Kosongkan"
};

$('.datepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true,
  language: 'id',
});

const tglAwal = document.getElementById('tglAwal');
const tglAkhir = document.getElementById('tglAkhir');

tglAwal.addEventListener("keyup", formatTanggal);
tglAkhir.addEventListener("keyup", formatTanggal);

function formatTanggal(e) {
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if(value.length > 2 && value.length <= 4) 
    value = value.slice(0,2) + "-" + value.slice(2);
  else if(value.length > 4 && value.length <= 8)
    value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
  
  if(e.target.id == 'tglAwal')
    tglAwal.value = value;
  else
    tglAkhir.value = value;
}
</script>
@endpush