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
      <h1 class="h3 mb-0 text-gray-800 menu-title">Account Receivable</h1>
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
                <div class="form-group row" style="margin-top: -10px">
                  <label for="bulan" class="col-2 col-form-label text-right text-bold">Nama Bulan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan" autocomplete="off" autofocus>
                  </div>
                  <label for="status" class="col-auto col-form-label text-right text-bold">Status</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <select class="form-control form-control-sm mt-1" tabindex="2" name="status">
                      <option value="ALL" selected>ALL</option>
                      <option value="LUNAS">LUNAS</option>
                      <option value="BELUM LUNAS">BELUM LUNAS</option>
                    </select>
                  </div>
                  {{-- <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="status" id="status">
                  </div> --}}
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-right text-bold">Dari Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="3" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" placeholder="DD-MM-YYYY" autocomplete="off">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ml-3"> s / d </label>
                  <div class="col-2">
                    <input type="text" tabindex="4" class="form-control datepicker form-control-sm text-bold mt-1 ml-1" name="tglAkhir" id="tglAkhir" placeholder="DD-MM-YYYY" autocomplete="off">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="5" formaction="{{ route('ar-show') }}" formmethod="GET" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: 180px">
                    <button type="submit" tabindex="5" formaction="{{ route('ar-cetak-now') }}" formmethod="POST" formtarget="_blank" id="btn-cari" class="btn btn-danger btn-sm btn-block text-bold">Print</button>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="5" formaction="{{ route('ar-excel-now') }}" formmethod="POST" id="btn-cari" class="btn btn-success btn-sm btn-block text-bold">Excel</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              {{-- @if(Auth::user()->roles != 'OFFICE02') --}}
                <!-- Button Submit dan Reset -->
                {{-- <div class="form-row justify-content-center" @if($ar->count() != 0) style="margin-bottom: -18px" @else style="margin-bottom: 18px" @endif>
                  <div class="col-1">
                    <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ar-process') }}" formmethod="POST">Submit</button>
                  </div>
                  <div class="col-1">
                    <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                  </div>
                </div> --}}
                <!-- End Button Submit dan Reset -->
              {{-- @endif --}}

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
                  @php $i = 1; $tab = 5;
                    if((Auth::user()->roles == 'SUPER') || (Auth::user()->roles == 'AR'))
                      $items = $ar;
                    elseif(Auth::user()->roles == 'OFFICE02')
                      $items = $arOffice
                  @endphp
                  @php 
                    $total = App\Models\DetilAR::select(DB::raw('sum(cicil) as totCicil'))
                              ->where('id_ar', $arLast->first()->id)->get();
                    $retur = App\Models\AR_Retur::selectRaw('sum(total) as total')
                            ->where('id_ar', $arLast->first()->id)->get();
                  @endphp
                  <tr class="text-dark">
                    <td align="center" class="align-middle">{{ $i }}</td>
                    <td class="align-middle">{{ $arLast->first()->so->customer->nama }}</td>
                    <td class="align-middle">{{ $arLast->first()->so->customer->sales->nama }}</td>
                    <td align="center" class="align-middle">{{ $arLast->first()->so->kategori }}</td>
                    <td align="center" class="align-middle"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('trans-detail', $arLast->first()->id_so) }}" formmethod="POST" formtarget="_blank" class="btn btn-sm btn-link text-bold">{{ $arLast->first()->id_so }}</button></td>
                    <td align="center" class="align-middle">
                      {{ \Carbon\Carbon::parse($arLast->first()->so->tgl_so)->format('d-M-y') }}
                    </td>
                    <td align="center" class="align-middle">
                      {{ \Carbon\Carbon::parse($arLast->first()->so->tgl_so)->add($arLast->first()->so->tempo, 'days')
                        ->format('d-M-y') }}
                    </td>
                    <td align="right" class="align-middle">
                      {{ number_format($arLast->first()->so->total, 0, "", ",") }}
                    </td>
                    <td class="text-right align-middle">
                      {{ $total[0]->totCicil != null ? number_format($total[0]->totCicil, 0, "", ",") : 0}}
                      {{-- <input type="text" name="cic{{$arLast->first()->id_so}}" id="cicil" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right cicil" @if($total[0]->totCicil != null) value="{{ number_format($total[0]->totCicil, 0, "", ",") }}" @endif > --}}
                    </td>
                    <td class="text-right align-middle">
                      <input type="hidden" value="{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '' }}">
                      <a href="{{ route('ar-retur-create', $arLast->first()->id_so) }}" tabindex="{{ $tab += 2 }}" class="btn btn-link btn-sm text-bold text-right btnRetur" style="font-size: 13px; width: 100%; padding-right: 0px; padding-top: 5px">{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}</a>
                    </td>
                    <td align="right" class="align-middle">{{ number_format($arLast->first()->so->total - $total[0]->totCicil - $retur[0]->total, 0, "", ",") }}</td>
                    <td align="center" class="align-middle text-bold" @if(($arLast->first()->keterangan != null) && ($arLast->first()->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>
                      <a href="{{ route('ar-cicil-create', $arLast->first()->id_so) }}" tabindex="{{ $tab += 3 }}" class="btn btn-link btn-sm text-bold btnDetail" style="font-size: 13px">{{$arLast->first()->keterangan}}</a>
                    </td>
                  </tr>  
                  @php $i++; @endphp
                  @forelse($items as $a)
                    @php 
                      $total = App\Models\DetilAR::select(DB::raw('sum(cicil) as totCicil'))
                                ->where('id_ar', $a->id)->get();
                      $retur = App\Models\AR_Retur::selectRaw('sum(total) as total')
                              ->where('id_ar', $a->id)->get();
                    @endphp
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="align-middle">{{ $a->so->customer != NULL ? $a->so->customer->nama : $a->so->id_customer }}</td>
                      <td class="align-middle">{{ $a->so->customer != NULL ? $a->so->customer->sales->nama : $a->so->id_customer }}</td>
                      <td align="center" class="align-middle">{{ $a->so->kategori }}</td>
                      <td align="center" class="align-middle"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('trans-detail', $a->id_so) }}" formmethod="POST" formtarget="_blank" class="btn btn-sm btn-link text-bold">{{ $a->id_so }}</button></td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->so->tgl_so)->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->so->tgl_so)->add($a->so->tempo, 'days')
                          ->format('d-M-y') }}
                      </td>
                      <td align="right" class="align-middle">
                        {{ number_format($a->so->total, 0, "", ",") }}
                      </td>
                      <td class="align-middle text-right">
                        {{ $total[0]->totCicil != null ? number_format($total[0]->totCicil, 0, "", ",") : 0 }}
                        {{-- <input type="text" name="cic{{$a->id_so}}" id="cicil" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right cicil" @if($total[0]->totCicil != null) value="{{ number_format($total[0]->totCicil, 0, "", ",") }}" @endif > --}}
                      </td>
                      <td class="text-right align-middle">
                        <input type="hidden" value="{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '' }}">
                        {{-- <a href="#Retur{{ $a->id_so }}" tabindex="{{ $tab += 2 }}" class="btn btn-link btn-sm text-bold text-right btnRetur" data-toggle="modal" style="font-size: 13px; width: 100%; padding-right: 0px; padding-top: 5px">{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}</a> --}}
                        <a href="{{ route('ar-retur-create', $a->id_so) }}" tabindex="{{ $tab += 2 }}" class="btn btn-link btn-sm text-bold text-right btnRetur" style="font-size: 13px; width: 100%; padding-right: 0px; padding-top: 5px">{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}</a>
                      </td>
                      <td align="right" class="align-middle">{{ number_format($a->so->total - $total[0]->totCicil - $retur[0]->total, 0, "", ",") }}</td>
                      <td align="center" class="align-middle text-bold" @if(($a->keterangan != null) && ($a->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>
                        {{-- <a href="#Detail{{ $a->id_so }}" tabindex="{{ $tab += 3 }}" class="btn btn-link btn-sm text-bold btnDetail" data-toggle="modal" style="font-size: 13px">{{$a->keterangan}}</a> --}}
                        <a href="{{ route('ar-cicil-create', $a->id_so) }}" tabindex="{{ $tab += 3 }}" class="btn btn-link btn-sm text-bold btnDetail" style="font-size: 13px">{{$a->keterangan}}</a>
                      </td>
                    </tr>     
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan=12 class="text-center text-bold h4 p-2"><i>Tidak ada daftar account receivable</i></td>
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
// const kodeSO = document.getElementById("kodeSO");
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

      if((value == 7) || (value == 10)) {
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

      if((value == 7) || (value == 10)) {
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

/** Input nominal comma separator **/
/* for(let i = 0; i < cicil.length; i++) {
  // cicil[i].addEventListener("keyup", function(e) {
  //   $(this).val(function(index, value) {
  //     return value
  //     .replace(/\D/g, "")
  //     .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  //     ;
  //   });
  // })

  cicil[i].addEventListener("focus", function(e) {
    cicil[i].value = cicil[i].value.replace(/\,/g, "");
  })

  cicil[i].addEventListener("focusout", function(e) {
    cicil[i].value = addCommas(cicil[i].value);
  })

  cicil[i].addEventListener("change", function(e) {
    var arrKode = kodeSO.value.split(',');
    var kode = cicil[i].name.substr(-7);

    if(arrKode[0] != "") {
      kodeSO.value = kodeSO.value.concat(`,${kode}`);
    }
    else {
      kodeSO.value = kode;
    }
  })

  // retur[i].addEventListener("keyup", function(e) {
  //   $(this).val(function(index, value) {
  //     return value
  //     .replace(/\D/g, "")
  //     .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  //     ;
  //   });
  // })

  retur[i].addEventListener("focus", function(e) {
    retur[i].value = retur[i].value.replace(/\,/g, "");
  })

  retur[i].addEventListener("focusout", function(e) {
    retur[i].value = addCommas(retur[i].value);
  })

  retur[i].addEventListener("change", function(e) {
    var arrKode = kodeSO.value.split(',');
    var kode = retur[i].name.substr(-7);

    if(arrKode[0] != "") {
      kodeSO.value = kodeSO.value.concat(`,${kode}`);
    }
    else {
      kodeSO.value = kode;
    }
  })
} */

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