@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
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
                    <input type="text" class="form-control datepicke form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" placeholder="DD-MM-YYYY" required>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s/d</label>
                  <div class="col-2">
                    <input type="text" class="form-control datepicke form-control-sm text-bold mt-1" name="tglAkhir" id="tglAkhir" placeholder="DD-MM-YYYY" required>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" id="btn-cari" formaction="{{ route('trans-show') }}" 
                    formmethod="GET" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
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