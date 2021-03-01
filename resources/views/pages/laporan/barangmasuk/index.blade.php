@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Rekap Barang Masuk</h1>
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

              <div class="container so-container">  
                <div class="form-group row justify-content-center" style="margin-top: -10px">
                  <label for="tanggal" class="col-auto col-form-label text-bold">Tanggal Awal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" placeholder="DD-MM-YYYY" value="{{ $tglAwal != NULL ? $tglAwal : ''}}" autocomplete="off" autofocus>
                  </div>
                  <span class="col-form-label text-bold">s / d</span>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAkhir" id="tglAkhir" placeholder="DD-MM-YYYY" value="{{ $tglAkhir != NULL ? $tglAkhir : ''}}" autocomplete="off" autofocus>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('bmk-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                  <div class="col-auto mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="6" formaction="{{ route('bmk') }}" formmethod="GET" class="btn btn-outline-danger btn-sm btn-block text-bold">Reset Filter</button>
                  </div>
                </div>  
              </div>  

              <div class="row justify-content-center" style="margin-bottom: 15px">
                {{-- <div class="col-2">
                  <a href="{{ url('/rekap/cetak') }}" class="btn btn-primary btn-block text-bold btnprnt">Print</a>
                </div> --}}
                {{-- <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <button type="submit" formaction="{{ route('rs-pdf') }}" formmethod="POST" formtarget="_blank" class="btn btn-primary btn-block text-bold">Download PDF</>
                </div> --}}
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <button type="submit" formaction="{{ route('bmk-excel') }}" formmethod="POST"  class="btn btn-danger btn-block text-bold">Download Excel</>
                </div>
              </div>
              <hr>

              <div class="container" style="margin-bottom: 0px">
                <div class="row justify-content-center">
                  <h4 class="text-bold text-dark">Rekap Barang Masuk {{ $tanggal }}</h4>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h6 class="text-dark ">Waktu : {{$waktu}}</h6>
                </div>
              </div>

              <!-- Tabel Data Detil BM-->
              <table class="table table-sm table-bordered table-responsive-sm table-hover">
                <thead class="text-center text-dark text-bold">
                  <td style="width: 60px" class="align-middle">No</td>
                  <td style="width: 130px">Kode Barang</td>
                  <td class="align-middle">Nama Barang</td>
                  <td style="width: 200px" class="align-middle">Nama Gudang</td>
                  <td style="width: 150px; background-color: yellow" class="align-middle">Qty</td>
                </thead>
                <tbody id="tablePO">
                  @php $i = 1; @endphp
                  @forelse($items as $item)
                    <tr class="text-dark text-bold">
                      <td align="center">{{ $i }}</td>
                      <td align="center">{{ $item->id_barang }}</td>
                      <td>{{ $item->barang->nama }}</td>
                      <td align="center">{{ $item->bm->gudang->nama }}</td>
                      <td align="right" style="background-color: yellow">{{ $item->qty }}</td>
                    </tr>
                    @php $i++; @endphp
                  @empty
                  @endforelse
                </tbody>
              </table>
              <!-- End Tabel Data Detil PO -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
<script src="{{ url('backend/vendor/jquery/jquery.printPage.js') }}"></script>
<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('.btnprnt').printPage();
});

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
  
  tanggal.value = value;
}
</script>
@endpush