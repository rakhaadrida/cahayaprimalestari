@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Program Prime</h1>
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
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan" value="{{ $bul }}" autocomplete="off" autofocus>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold">Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="customer" id="customer" value="{{ $cust }}" autocomplete="off" autofocus>
                    <input type="hidden" name="kode" id="kode" value="{{ $id }}">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('prime-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                  <div class="col-auto mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="6" formaction="{{ route('prime') }}" formmethod="GET" class="btn btn-outline-danger btn-sm btn-block text-bold">Reset Filter</button>
                  </div>
                  <div class="col-auto mt-1">
                    <button type="submit" tabindex="5" formaction="{{ route('prime-excel-filter') }}" formmethod="POST" id="btn-cari" class="btn btn-success btn-sm btn-block text-bold">Download Excel</button>
                  </div>
                </div>  
              </div> 
              <hr> 

              <div class="container" style="margin-bottom: 0px">
                <div class="row justify-content-center">
                  <h4 class="text-bold text-dark">Program Prime Bulan {{ $bul != '' ? $bul : $bulanNow }} {{ $year }}</h4>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h6 class="text-dark ">Waktu : {{ \Carbon\Carbon::now('+07:00')->isoFormat('dddd, D MMMM Y, HH:mm:ss') }}</h6>
                </div>
              </div>

              <!-- Tabel Data Detil BM-->
              <table class="table table-sm table-bordered table-responsive-sm table-hover">
                <thead class="text-center text-dark text-bold">
                  <td style="width: 40px" class="align-middle">No</td>
                  <td style="width: 90px" class="align-middle">Sales</td>
                  <td class="align-middle">Customer</td>
                  <td class="align-middle">Nama Barang</td>
                  <td class="align-middle" style="width: 130px">Kategori</td>
                  <td style="width: 90px; background-color: yellow" class="align-middle">Qty</td>
                </thead>
                <tbody id="tablePO">
                  @php $i = 1; $subtotal = 0; @endphp
                  @foreach($sales as $s)
                    @php
                      $total = 0; $cekQty = 0;
                      if($cust == '')
                        $customer = \App\Models\Customer::where('id_sales', $s->id)->get();
                    @endphp
                    {{-- @foreach($customer as $c) --}}
                      @php
                        $qty = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                              ->join('customer', 'customer.id', 'so.id_customer')
                              ->join('barang', 'barang.id', 'detilso.id_barang')
                              ->select('id_barang', 'so.id_sales', 'id_so')->selectRaw('sum(qty) as qty')
                              // ->select('id_barang', 'id_customer','id_so')->selectRaw('sum(qty) as qty')
                              ->where('id_kategori', 'KAT08')
                              ->whereNotIn('status', ['BATAL', 'LIMIT'])
                              // ->where('id_customer', $c->id)->whereYear('tgl_so', $tahun)
                              ->where('so.id_sales', $s->id)->whereYear('tgl_so', $tahun)
                              ->whereIn(DB::raw('MONTH(tgl_so)'), $month)
                              // ->groupBy('id_barang')->orderBy('customer.nama')->get();
                              ->groupBy('id_customer', 'id_barang')->orderBy('customer.nama')->get();
                        $cekQty += $qty->count();
                      @endphp
                      @foreach($qty as $q)
                        <tr class="text-dark text-bold">
                          <td align="center">{{ $i }}</td>
                          <td>{{ $q->so->sales->nama }}</td>
                          {{-- <td>{{ $c->nama }}</td> --}}
                          <td>{{ $q->so->customer->nama }}</td>
                          <td>{{ $q->barang->nama }}</td>
                          <td align="center">{{ $q->barang->jenis->nama }}</td>
                          <td align="right" style="width: 120px; background-color: yellow">{{ $q->qty }}</td>
                        </tr>
                        @php $i++; $total += $q->qty; @endphp
                      @endforeach
                    {{-- @endforeach --}}
                    @if($cekQty != 0)
                      <tr class="text-white text-bold bg-primary">
                        <td align="right" colspan="5">Total Qty Penjualan</td>
                        <td align="right">{{ $total }}</td>
                      </tr>
                    @endif
                    @php $subtotal += $total; @endphp
                  @endforeach
                  <tr class="text-white text-bold bg-danger">
                    <td align="right" colspan="5">Grand Total Qty Penjualan</td>
                    <td align="right">{{ number_format($subtotal, 0, "", ".") }}</td>
                  </tr>
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

const tanggal = document.getElementById('tanggal');
const customer = document.getElementById('customer');
const kode = document.getElementById('kode');

// tanggal.addEventListener("keyup", formatTanggal);
customer.addEventListener("blur", displayKode);
customer.addEventListener("keyup", displayKode);

function displayKode(e) {
  if(e.target.value == '') {
    kode.value = '';
  }

  @foreach($customerAll as $c)
    if(e.target.value == '{{ $c->nama }}') {
      kode.value = '{{ $c->id }}';
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

  @foreach($customerAll as $c)
    nama.push('{{ $c->nama }}');
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
  $("#customer").on("keydown", function(event) {
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