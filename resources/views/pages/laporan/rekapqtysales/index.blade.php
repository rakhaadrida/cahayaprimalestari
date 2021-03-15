@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Rekap Qty Penjualan</h1>
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
                  <label for="tanggal" class="col-auto col-form-label text-bold">Nama Bulan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan" autocomplete="off" autofocus>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold">Sales</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="sales" id="sales" autocomplete="off" autofocus>
                    <input type="hidden" name="kode" id="kode">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold">Kategori</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="kategori" id="kategori" autocomplete="off" autofocus>
                    <input type="hidden" name="jenis" id="jenis">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('qs-show') }}" formmethod="POST" id="btn-cari" class="btn btn-success btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div> 
              <hr> 

              {{-- <div class="row justify-content-center" style="margin-bottom: 15px">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <button type="submit" formaction="{{ route('rs-pdf') }}" formmethod="POST" formtarget="_blank" class="btn btn-primary btn-block text-bold">Download PDF</>
                </div>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <button type="submit" formaction="{{ route('rs-excel') }}" formmethod="POST"  class="btn btn-danger btn-block text-bold">Download Excel</>
                </div>
              </div>
              <hr> --}}

              {{-- <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner"> --}}
                  {{-- @foreach($jenis as $item) --}}
                  {{-- <div class="carousel-item @if($item->id == $jenis[0]->id) active @endif"/> --}}
                    <div class="container" style="margin-bottom: 0px">
                      <div class="row justify-content-center">
                        <h4 class="text-bold text-dark">Rekap Qty Penjualan Bulan {{ $bulan }} {{ $year }}</h4>
                      </div>
                      <div class="row justify-content-center" style="margin-top: -5px">
                        <h6 class="text-dark ">Waktu : {{ \Carbon\Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss') }}</h6>
                      </div>
                    </div>

                    <!-- Tabel Data Detil BM-->
                    <table class="table table-sm table-bordered table-responsive-sm table-hover">
                      <thead class="text-center text-dark text-bold">
                        <td style="width: 40px" class="align-middle">No</td>
                        <td class="align-middle">Customer</td>
                        <td class="align-middle">Nama Barang</td>
                        <td class="align-middle" style="width: 130px">Kategori</td>
                        <td style="width: 90px; background-color: yellow" class="align-middle">Qty</td>
                      </thead>
                      <tbody id="tablePO">
                        @php $i = 1; 
                          $sales = \App\Models\Sales::All();
                        @endphp
                        @foreach($sales as $s)
                          @php
                            $total = 0; $cekQty = 0;
                            $customer = \App\Models\Customer::where('id_sales', $s->id)->get();
                          @endphp
                          <tr class="text-dark text-bold" style="background-color: rgb(255, 221, 181)">
                            <td colspan="5" align="center">
                              <button type="button" class="btn btn-link btn-sm text-dark text-bold" data-toggle="collapse" data-target="#collapseSub{{$s->id}}" aria-expanded="false" aria-controls="collapseSub{{$s->id}}" style="padding: 0; font-size: 15px; width: 100%">{{ $s->nama }}</button>
                            </td>
                          </tr>
                          @foreach($customer as $c)
                            @php
                              $qty = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                                    ->join('customer', 'customer.id', 'so.id_customer')
                                    ->select('id_barang')->selectRaw('sum(qty) as qty')
                                    // ->select('id_barang', 'id_customer','id_so')->selectRaw('sum(qty) as qty')
                                    ->where('id_customer', $c->id)->whereYear('tgl_so', $tahun)
                                    // ->where('so.id_sales', $s->id)->whereYear('tgl_so', $tahun)
                                    ->whereMonth('tgl_so', $month)
                                    ->groupBy('id_barang')->orderBy('nama')->get();
                                    // ->groupBy('id_customer', 'id_barang')->orderBy('nama')->get();
                              $cekQty += $qty->count();
                            @endphp
                            @foreach($qty as $q)
                              <tr class="text-dark text-bold collapse show" id="collapseSub{{$s->id}}">
                                <td align="center">{{ $i }}</td>
                                <td>{{ $c->nama }}</td>
                                {{-- <td>{{ $q->so->customer->nama }}</td> --}}
                                <td>{{ $q->barang->nama }}</td>
                                <td align="center">{{ $q->barang->jenis->nama }}</td>
                                <td align="right" style="width: 120px; background-color: yellow">{{ $q->qty }}</td>
                              </tr>
                              @php $i++; $total += $q->qty; @endphp
                            @endforeach
                          @endforeach
                          @if($cekQty != 0)
                            <tr class="text-white text-bold bg-primary collapse show" id="collapseSub{{$s->id}}">
                              <td align="right" colspan="4">Total Qty Penjualan</td>
                              <td align="right">{{ $total }}</td>
                            </tr>
                          @endif
                        @endforeach
                      </tbody>
                    </table>
                    <!-- End Tabel Data Detil PO -->
                  {{-- </div> --}}
                  {{-- @endforeach --}}
                {{-- </div>
                @if(($jenis->count() > 0) && ($jenis->count() != 1))
                  <a class="carousel-control-prev" href="#so-carousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next " href="#so-carousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                @endif --}}
              {{-- </div> --}}
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
const sales = document.getElementById('sales');
const kode = document.getElementById('kode');
const kategori = document.getElementById('kategori');
const jenis = document.getElementById('jenis');

// tanggal.addEventListener("keyup", formatTanggal);
sales.addEventListener("blur", displayKode);
sales.addEventListener("keyup", displayKode);
kategori.addEventListener("blur", displayJenis);
kategori.addEventListener("keyup", displayJenis);

function displayKode(e) {
  if(e.target.value == '') {
    kode.value = '';
  }

  @foreach($sales as $s)
    if(e.target.value == '{{ $s->nama }}') {
      kode.value = '{{ $s->id }}';
    }
  @endforeach
}

function displayJenis(e) {
  if(e.target.value == '') {
    jenis.value = '';
  }

  @foreach($jenis as $j)
    if(e.target.value == '{{ $j->nama }}') {
      jenis.value = '{{ $j->id }}';
    }
  @endforeach
}

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

/** Autocomplete Input Text **/
$(function() {
  var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
              'September', 'Oktober', 'November', 'Desember'];
  var nama = [];
  var jenis = [];

  @foreach($sales as $s)
    nama.push('{{ $s->nama }}');
  @endforeach

  @foreach($jenis as $j)
    jenis.push('{{ $j->nama }}');
  @endforeach

  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Bulan --*/
  $("#bulan").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(bulan, extractLast(request.term)));
    },
    focus: function() {
      // prevent value inserted on focus
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      // remove the current input
      terms.pop();
      // add the selected item
      terms.push(ui.item.value);
      // add placeholder to get the comma-and-space at the end
      terms.push("");
      this.value = terms.join("");
      return false;
    }
  });

  /*-- Autocomplete Input Sales --*/
  $("#sales").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(nama, extractLast(request.term)));
    },
    focus: function() {
      // prevent value inserted on focus
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      // remove the current input
      terms.pop();
      // add the selected item
      terms.push(ui.item.value);
      // add placeholder to get the comma-and-space at the end
      terms.push("");
      this.value = terms.join("");
      return false;
    }
  });

  /*-- Autocomplete Input Jenis --*/
  $("#kategori").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(jenis, extractLast(request.term)));
    },
    focus: function() {
      // prevent value inserted on focus
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      // remove the current input
      terms.pop();
      // add the selected item
      terms.push(ui.item.value);
      // add placeholder to get the comma-and-space at the end
      terms.push("");
      this.value = terms.join("");
      return false;
    }
  });
});
</script>
@endpush