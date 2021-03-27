@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Penjualan Extrana</h1>
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
                <div class="form-group row justify-content-center" style="margin-top: -10px">
                  <label for="status" class="col-auto col-form-label text-right text-bold">Nama Bulan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan" autocomplete="off" value="{{ $bul != NULL ? $bul : '' }}" autofocus>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="5" formaction="{{ route('extrana-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                  <div class="col-auto mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="6" formaction="{{ route('extrana') }}" formmethod="GET" class="btn btn-outline-danger btn-sm btn-block text-bold">Reset Filter</button>
                  </div>
                  <div class="col-auto mt-1">
                    <button type="submit" tabindex="5" formaction="{{ route('komisi-excel') }}" formmethod="POST" id="btn-cari" class="btn btn-success btn-sm btn-block text-bold">Download Excel</button>
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <div class="container" style="margin-bottom: -15px">
                <div class="row justify-content-center">
                  <h4 class="text-bold text-dark">Penjualan Extrana Bulan {{ $bulan }} {{ $tahun }}</h4>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h6 class="text-dark ">Waktu : {{ $waktu }}</h6>
                </div>
              </div>
              <br>

              <!-- Tabel Data Detil AR -->
              {{-- <input type="hidden" id="kodeSO" name="kodeSO"> --}}
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($items->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <th style="width: 30px" class="align-middle">No</th>
                    <th style="width: 60px" class="align-middle">Sales</th>
                    <th class="align-middle">Customer</th>
                    {{-- <th style="width: 40px" class="align-middle">Kategori</th> --}}
                    {{-- <th style="width: 80px" class="align-middle">No. Faktur</th>
                    <th style="width: 60px" class="align-middle">Tgl. Faktur</th> --}}
                    <th class="align-middle">Barang</th>
                    <th style="width: 30px" class="align-middle">Qty</th>
                    <th style="width: 70px" class="align-middle">Harga</th>
                    <th style="width: 70px" class="align-middle">Total</th>
                    <th style="width: 70px" class="align-middle">Diskon</th>
                    <th style="width: 70px" class="align-middle">Netto</th>
                    {{-- <th style="width: 60px" class="align-middle">Keterangan</th> --}}
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php $i = 1; @endphp
                  @forelse($items as $a)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td align="center" class="align-middle">{{ $a->sales }}</td>
                      <td class="align-middle">{{ $a->cust }}</td>
                      {{-- <td align="center" class="align-middle">{{ $a->id }}</td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->tgl_so)->format('d-M-y') }}
                      </td> --}}
                      <td class="align-middle">{{ $a->barang->nama }}</td>
                      <td align="right" class="align-middle">{{ number_format($a->qty, 0, "", ",") }}</td>
                      <td align="right" class="align-middle">{{ number_format($a->harga, 0, "", ",") }}</td>
                      <td align="right" class="align-middle">{{ number_format($a->qty * $a->harga, 0, "", ",") }}</td>
                      <td align="right" class="align-middle">{{ number_format($a->diskonRp, 0, "", ",") }}</td>
                      <td align="right" class="align-middle">{{ number_format($a->qty * $a->harga - $a->diskonRp, 0, "", ",") }}</td>
                      {{-- <td align="center" class="align-middle text-bold" @if(($a->keterangan != null) && ($a->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>{{$a->keterangan}}</td> --}}
                    </tr>     
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan=12 class="text-center text-bold h4 p-2"><i>Tidak ada Data Komisi Sales untuk Periode ini</i></td>
                    </tr>
                  @endforelse
                </tbody>
                <tfoot>
                  <tr class="text-right text-bold text-dark" style="background-color: lightgrey; font-size: 14px">
                    <td colspan="4" class="text-center">Total</td>
                    {{-- <td colspan="6" class="text-center">Total</td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>              
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

const cicil = document.querySelectorAll(".cicil");
const retur = document.querySelectorAll(".retur");

/** Sort Datatable **/
$('#dataTable').dataTable( {
  "pageLength": 100,
  "columnDefs": [
    { "orderable": false, "targets": [0, 3] }
  ],
  "aaSorting" : [],
  "footerCallback": function ( row, data, start, end, display ) {
    var api = this.api(), data;

    // Remove the formatting to get integer data for summation
    var intVal = function ( i ) {
      return typeof i === 'string' ?
        i.replace(/[\$,]/g, '')*1 :
        typeof i === 'number' ?
            i : 0;
    };

    $.each([4, 5, 6, 7, 8], function(index, value) {
    // $.each([6, 7, 8, 9, 10], function(index, value) {

      var column = api
        .column(value, {
            page: 'current'
        })
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );

      var column_total = api
        .column(value)
        .data()
        .reduce( function (a, b) {
          return intVal(a) + intVal(b);
        }, 0 );

      // Update footer
      $(api.column(value).footer()).html(addCommas(column));
    }); 
  }
});

/** Add Thousand Separators **/
function addCommas(nStr) {
	nStr += '';
	x = nStr.split(',');
	x1 = x[0];
	x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

/** Autocomplete Input Text **/
$(function() {
  var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
              'September', 'Oktober', 'November', 'Desember'];

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
});

</script>
@endpush