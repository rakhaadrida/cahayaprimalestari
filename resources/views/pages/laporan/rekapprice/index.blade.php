@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Price List</h1>
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

              <div class="row justify-content-center" style="margin-bottom: 15px">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <button type="submit" formaction="{{ route('price-pdf') }}" formmethod="POST" formtarget="_blank" class="btn btn-primary btn-block text-bold">Download PDF</>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <button type="submit" formaction="{{ route('price-excel') }}" formmethod="POST"  class="btn btn-danger btn-block text-bold">Download Excel</>
                </div>
              </div>
              <hr>

              <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner">
                  @foreach($jenis as $item)
                  <div class="carousel-item @if($item->id == $jenis[0]->id) active @endif"/>
                    <div class="container" style="margin-bottom: 0px">
                      <div class="row justify-content-center">
                        <h4 class="text-bold text-dark">Price List {{ $item->nama }}</h4>
                      </div>
                      <div class="row justify-content-center" style="margin-top: -5px">
                        <h6 class="text-dark ">Waktu : {{ \Carbon\Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss') }}</h6>
                      </div>
                    </div>

                    <!-- Tabel Data Detil BM-->
                    <table class="table table-sm table-bordered table-responsive-sm table-hover">
                      <thead class="text-center text-dark text-bold">
                        <td style="width: 50px" class="align-middle">No</td>
                        <td style="width: 110px">Kode Barang</td>
                        <td class="align-middle">Nama Barang</td>
                        <td style="width: 130px" class="align-middle">Price</td>
                        <td style="width: 60px;" class="align-middle">%</td>
                        <td style="width: 130px;" class="align-middle">Net</td>
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
                            <td colspan="6" align="center">
                              <button type="button" class="btn btn-link btn-sm text-dark text-bold" data-toggle="collapse" data-target="#collapseSub{{$s->id}}" aria-expanded="false" aria-controls="collapseSub{{$s->id}}" style="padding: 0; font-size: 15px; width: 100%">{{ $s->nama }}</button>
                            </td>
                          </tr>
                          @foreach($barang as $b)
                            @php
                              $harga = \App\Models\HargaBarang::where('id_barang', $b->id)
                                  ->where('id_harga', 'HRG01')->get();
                            @endphp
                            <tr class="text-dark text-bold collapse show" id="collapseSub{{$s->id}}">
                              <td align="center">{{ $i }}</td>
                              <td align="center">{{ $b->id }}</td>
                              <td>{{ $b->nama }}</td>
                              <td align="right">{{ $harga->count() != 0 ? number_format($harga[0]->harga_ppn, 0, "", ".")  : '' }}</td>
                              <td align="right">1,1</td>
                              <td align="right">{{ $harga->count() != 0 ? number_format($harga[0]->harga, 0, "", ".") : '' }}</td>
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