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
      <h1 class="h3 mb-0 text-gray-800 menu-title">Komisi Sales Fadil</h1>
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
                  <label for="status" class="col-auto col-form-label text-right text-bold">Kategori</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <select class="form-control form-control-sm mt-1" tabindex="2" name="kategori">
                      <option value="ALL" selected>ALL</option>
                      <option value="EXTRANA">EXTRANA</option>
                      <option value="PRIME">PRIME</option>
                    </select>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="5" formaction="{{ route('komisi-show') }}" formmethod="GET" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                  <div class="col-auto mt-1">
                    <button type="submit" tabindex="5" formaction="{{ route('komisi-excel') }}" formmethod="POST" id="btn-cari" class="btn btn-success btn-sm btn-block text-bold">Download Excel</button>
                  </div>
                  <div class="col-auto mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="5" formaction="{{ route('komisi-upload') }}" formmethod="GET" id="btn-cari" class="btn btn-danger btn-sm btn-block text-bold">Upload File Komisi</button>
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <div class="container" style="margin-bottom: -25px">
                <div class="row justify-content-center">
                  <h5 class="text-bold text-dark">
                    {{-- Periode : {{$bulanLast}} s / d {{$bulanNow}} --}}
                    Bulan : {{$bulanNow}}
                  </h5>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h5 class="text-dark">
                    {{-- Tanggal : {{ \Carbon\Carbon::parse($lastMonth)->format('d-M-y') }} s / d {{ \Carbon\Carbon::parse($monthNow)->format('d-M-y') }} --}}
                    Tanggal : 1 - {{ \Carbon\Carbon::parse($monthNow)->isoFormat('DD MMMM YYYY') }}
                  </h5>
                </div>
              </div>
              <br>

              <!-- Tabel Data Detil AR -->
              {{-- <input type="hidden" id="kodeSO" name="kodeSO"> --}}
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($ar->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <th style="width: 30px" class="align-middle">No</th>
                    <th style="width: 170px" class="align-middle">Customer</th>
                    <th style="width: 60px" class="align-middle">Sales</th>
                    <th style="width: 40px" class="align-middle">Kategori</th>
                    <th style="width: 80px" class="align-middle">No. Faktur</th>
                    <th style="width: 60px" class="align-middle">Tgl. Faktur</th>
                    <th style="width: 60px" class="align-middle">Tempo</th>
                    <th style="width: 70px" class="align-middle">Total</th>
                    <th style="width: 75px" class="align-middle">Cicil</th>
                    <th style="width: 60px" class="align-middle">Retur</th>
                    <th style="width: 70px" class="align-middle">Kurang Bayar</th>
                    <th style="width: 60px" class="align-middle">Keterangan</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php $i = 1; $tab = 5; @endphp
                  @forelse($ar as $a)
                    @php 
                      $total = App\Models\DetilAR::selectRaw('sum(cicil) as totCicil')
                                ->where('id_ar', $a->id)->get();
                      $retur = App\Models\AR_Retur::selectRaw('sum(total) as total')
                              ->where('id_ar', $a->id)->get();
                    @endphp
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="align-middle">{{ $a->so->customer != NULL ? $a->so->customer->nama : $a->so->id_customer }}</td>
                      {{-- <td class="align-middle">{{ $a->so->customer != NULL ? $a->so->customer->sales->nama : $a->so->id_customer }}</td> --}}
                      <td class="align-middle">{{ $a->so != NULL ? $a->so->sales->nama : $a->so->id_customer }}</td>
                      <td align="center" class="align-middle">{{ $a->so->kategori }}</td>
                      <td align="center" class="align-middle"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('trans-detail', $a->id_so) }}" formmethod="POST" formtarget="_blank" class="btn btn-sm btn-link text-bold">{{ $a->id_so }}</button></td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->so->tgl_so)->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->so->tgl_so)->add($a->so->tempo, 'days')->format('d-M-y') }}
                      </td>
                      <td align="right" class="align-middle">
                        {{ number_format($a->so->total, 0, "", ",") }}
                      </td>
                      <td class="align-middle text-right">
                        {{ $total[0]->totCicil != null ? number_format($total[0]->totCicil, 0, "", ",") : 0 }}
                      </td>
                      <td class="text-right align-middle">
                        <input type="hidden" value="{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '' }}">
                        <a href="{{ route('ar-retur-create', $a->id_so) }}" tabindex="{{ $tab += 2 }}" class="btn btn-link btn-sm text-bold text-right btnRetur" style="font-size: 13px; width: 100%; padding-right: 0px; padding-top: 5px">{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}</a>
                      </td>
                      <td align="right" class="align-middle">{{ number_format($a->so->total - $total[0]->totCicil - $retur[0]->total, 0, "", ",") }}</td>
                      <td align="center" class="align-middle text-bold" @if(($a->keterangan != null) && ($a->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>
                        <a href="{{ route('ar-cicil-create', $a->id_so) }}" tabindex="{{ $tab += 3 }}" class="btn btn-link btn-sm text-bold btnDetail" style="font-size: 13px">{{$a->keterangan}}</a>
                      </td>
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
                    <td colspan="7" class="text-center">Total</td>
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

    $.each([7, 8, 9, 10], function(index, value) {

      if((value == 7) || (value == 8) || (value == 10)) {
        var column = api
          .column(value, {
              page: 'current'
          })
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
      }
      else {
        var column = api
          .column(value, {
              page: 'current'
          })
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal($(b).val());
          }, 0 );
      }

      if((value == 7) || (value == 8) || (value == 10)) {
        var column_total = api
          .column(value)
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal(b);
          }, 0 );
      }
      else {
        var column_total = api
          .column(value)
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal($(b).val());
          }, 0 );
      }

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
  var status = ['LUNAS', 'BELUM LUNAS'];

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

  /*-- Autocomplete Input Status --*/
  $("#status").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(status, extractLast(request.term)));
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