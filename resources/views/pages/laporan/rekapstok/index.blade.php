@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Rekap Stok Barang</h1>
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
                  <label for="tanggal" class="col-auto col-form-label text-bold">Tanggal Rekap</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tanggal" id="tanggal" placeholder="DD-MM-YYYY" autofocus>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('rs-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>  

              <div class="row justify-content-center" style="margin-bottom: 15px">
                <div class="col-2">
                  <a href="{{ url('/rekap/cetak') }}" class="btn btn-primary btn-block text-bold btnprnt">Print</a>
                </div>
                <div class="col-2">
                  <button type="submit" formaction="{{ route('rs-pdf') }}" formmethod="POST" formtarget="_blank" class="btn btn-success btn-block text-bold">Download PDF</>
                </div>
                <div class="col-2">
                  <button type="submit" formaction="{{ route('rs-excel') }}" formmethod="POST"  class="btn btn-danger btn-block text-bold">Download Excel</>
                </div>
              </div>
              <hr>

              <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner">
                  @foreach($jenis as $item)
                  <div class="carousel-item @if($item->id == $jenis[0]->id) active @endif"/>
                    <div class="container" style="margin-bottom: 0px">
                      <div class="row justify-content-center">
                        <h4 class="text-bold text-dark">Rekap Stok {{ $item->nama }}</h4>
                      </div>
                      <div class="row justify-content-center" style="margin-top: -5px">
                        <h6 class="text-dark ">Waktu : {{ \Carbon\Carbon::now('+07:00')->format('d F Y, H:i:s') }}</h6>
                      </div>
                    </div>

                    <!-- Tabel Data Detil BM-->
                    <table class="table table-sm table-bordered table-responsive-sm table-hover">
                      <thead class="text-center text-dark text-bold">
                        <td style="width: 40px" class="align-middle">No</td>
                        <td style="width: 80px">Kode Barang</td>
                        <td class="align-middle">Nama Barang</td>
                        <td style="width: 110px; background-color: yellow" class="align-middle">Total Stok</td>
                        @foreach($gudang as $g)
                          <td style="width: 110px" class="align-middle">{{ $g->nama }}</td>
                        @endforeach
                      </thead>
                      <tbody id="tablePO">
                        @php $i = 1; 
                            $sub = \App\Models\Subjenis::where('id_kategori', $item->id)->get();
                        @endphp
                        @foreach($sub as $s)
                          @php
                            $barang = \App\Models\Barang::where('id_sub', $s->id)->get();
                          @endphp
                          <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                            <td colspan="7" align="center">
                              <button type="button" class="btn btn-link btn-sm text-dark text-bold" data-toggle="collapse" data-target="#collapseSub{{$s->id}}" aria-expanded="false" aria-controls="collapseSub{{$s->id}}" style="padding: 0; font-size: 15px; width: 100%">{{ $s->nama }}</button>
                            </td>
                          </tr>
                          @foreach($barang as $b)
                            @php
                              $stok = \App\Models\StokBarang::with(['barang'])
                                  ->select('id_barang', DB::raw('sum(stok) as total'))
                                  ->where('id_barang', $b->id)->groupBy('id_barang')->get();
                            @endphp
                            <tr class="text-dark text-bold collapse show" id="collapseSub{{$s->id}}">
                              <td align="center">{{ $i }}</td>
                              <td align="center">{{ $b->id }}</td>
                              <td>{{ $b->nama }}</td>
                              @if($stok->count() != 0)
                                <td align="right" style="background-color: yellow">{{$stok[0]->total}}</td>
                              @else
                                <td>0</td>
                              @endif
                              @foreach($gudang as $g)
                                @php
                                  $stokGd = \App\Models\StokBarang::where('id_barang', $b->id)
                                          ->where('id_gudang', $g->id)->get();
                                @endphp
                                @if(($stokGd->count() != 0) && ($stokGd[0]->stok != 0))
                                  <td align="right">{{$stokGd[0]->stok}}</td>
                                @else
                                  <td></td>
                                @endif
                              @endforeach
                            </tr>
                            @php $i++; @endphp
                          @endforeach
                      @endforeach
                      </tbody>
                    </table>
                    <!-- End Tabel Data Detil PO -->
                  </div>
                  @endforeach
                </div>
                @if(($jenis->count() > 0) && ($jenis->count() != 1))
                  <a class="carousel-control-prev" href="#so-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next " href="#so-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                @endif
              </div>
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

const tanggal = document.getElementById('tanggal');

tanggal.addEventListener("keyup", formatTanggal);

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