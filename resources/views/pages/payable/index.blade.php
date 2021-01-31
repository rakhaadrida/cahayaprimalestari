{{-- @extends('pages.payable.detail')
@extends('pages.payable.retur') --}}
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
      <h1 class="h3 mb-0 text-gray-800 menu-title">Account Payable</h1>
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
                    <button type="submit" tabindex="5" formaction="{{ route('ap-show') }}" formmethod="GET" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Tabel Data Detil AR -->
              <input type="hidden" id="kodeBM" name="kodeBM">
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($ap->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <th style="width: 30px" class="align-middle">No</th>
                    <th style="width: 300px" class="align-middle">Supplier</th>
                    <th style="width: 50px" class="align-middle">No. Faktur</th>
                    <th style="width: 65px" class="align-middle">Tgl. BM</th>
                    <th style="width: 55px" class="align-middle">Tempo</th>
                    <th style="width: 60px" class="align-middle">Discount</th>
                    <th style="width: 75px" class="align-middle">Total</th>
                    <th style="width: 70px" class="align-middle">Transfer</th>
                    <th style="width: 70px" class="align-middle">Retur</th>
                    <th style="width: 70px" class="align-middle">Kurang Bayar</th>
                    <th style="width: 60px" class="align-middle">Keterangan</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php $i = 1; $tab = 5 @endphp
                  @php 
                    if($apLast->count() != 0) {
                      $totalBM = App\Models\BarangMasuk::select(DB::raw('sum(total) as totBM'))
                                ->where('id_faktur', $apLast->first()->id_bm)->where('status', '!=', 'BATAL')->get();
                      $potBM = App\Models\BarangMasuk::select(DB::raw('sum(potongan) as potongan'))
                              ->where('id_faktur', $apLast->first()->id_bm)->get();
                      $total = App\Models\DetilAP::select(DB::raw('sum(transfer) as 
                              totTransfer'))->where('id_ap', $apLast->first()->id)->get();
                      $retur = App\Models\AP_Retur::selectRaw('sum(total) as total')
                              ->where('id_ap', $apLast->first()->id)->get();
                    }
                  @endphp
                  @if($apLast->count() != 0)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="align-middle">{{ $apLast->first()->bm[0]->supplier->nama }}</td>
                      <td align="center" class="align-middle"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('ap-detail', $apLast->first()->id_bm) }}" formmethod="POST" formtarget="_blank" class="btn btn-link btn-sm text-bold">{{ $apLast->first()->id_bm }}</button></td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($apLast->first()->bm[0]->tanggal)->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($apLast->first()->bm[0]->tanggal)->add($apLast->first()->bm[0]->tempo, 'days')->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle" @if($apLast->first()->bm->last()->diskon != 'F') style="background-color: lightgreen" @endif>
                        {{ $apLast->first()->bm->last()->diskon == 'T' ? 'INPUT' : 'KOSONG' }}
                      </td>
                      <td align="right" class="align-middle">
                        {{ $apLast->first()->bm->last()->diskon == 'T' ? number_format($totalBM[0]->totBM - $potBM[0]->potongan, 0, "", ",") : '' }}
                      </td>
                      <td class="align-middle">
                        <input type="text" name="tr{{$apLast->first()->id_bm}}" id="transfer" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right transfer" @if($total[0]->totTransfer != null) value="{{ number_format($total[0]->totTransfer, 0, "", ",") }}" @endif>
                      </td>
                      <td class="align-middle">
                        <input type="hidden" value="{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}">
                        <a href="{{ route('ap-retur-create', $apLast->first()->id_bm) }}" tabindex="{{ $tab += 2 }}" class="btn btn-link btn-sm text-bold text-right btnRetur" style="font-size: 13px; width: 100%; padding-right: 0px; padding-top: 5px">{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}</a>
                      </td>
                      <td align="right" class="align-middle">
                        {{ $apLast->first()->bm->last()->diskon == 'T' ? number_format($totalBM[0]->totBM - $total[0]->totTransfer - $retur[0]->total - $potBM[0]->potongan, 0, "", ",") : '' }}
                      </td>
                      <td align="center" class="align-middle text-bold" @if(($apLast->first()->keterangan != null) && ($apLast->first()->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>
                        <a href="{{ route('ap-transfer-create', $apLast->first()->id_bm) }}" tabindex="{{ $tab += 3 }}" class="btn btn-link btn-sm text-bold btnDetail" style="font-size: 13px">{{$apLast->first()->keterangan}}</a>
                      </td>
                    </tr> 
                  @endif
                  @php $i++; @endphp
                  @forelse($ap as $a)
                    @php 
                      $totalBM = App\Models\BarangMasuk::select(DB::raw('sum(total) as totBM'))
                              ->where('id_faktur', $a->id_bm)->where('status', '!=', 'BATAL')->get();
                      $potBM = App\Models\BarangMasuk::select(DB::raw('sum(potongan) as potongan'))
                              ->where('id_faktur', $a->id_bm)->get();
                      $total = App\Models\DetilAP::select(DB::raw('sum(transfer) as 
                              totTransfer'))->where('id_ap', $a->id)->get();
                      $retur = App\Models\AP_Retur::selectRaw('sum(total) as total')
                              ->where('id_ap', $a->id)->get();
                    @endphp
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="align-middle">{{ $a->bm[0]->supplier->nama }}</td>
                      <td align="center" class="align-middle"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('ap-detail', $a->id_bm) }}" formmethod="POST" formtarget="_blank" class="btn btn-link btn-sm text-bold">{{ $a->id_bm }}</button></td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->bm[0]->tanggal)->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->bm[0]->tanggal)->add($a->bm[0]->tempo, 'days')
                          ->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle" @if($a->bm->last()->diskon != 'F') style="background-color: lightgreen" @endif>
                        {{ $a->bm->last()->diskon == 'T' ? 'INPUT' : 'KOSONG' }}
                      </td>
                      <td align="right" class="align-middle">
                        {{ $a->bm->last()->diskon == 'T' ? number_format($totalBM[0]->totBM - $potBM[0]->potongan, 0, "", ",") : '' }}
                      </td>
                      <td class="align-middle">
                        <input type="text" name="tr{{$a->id_bm}}" id="transfer" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right transfer" @if($total[0]->totTransfer != null) value="{{ number_format($total[0]->totTransfer, 0, "", ",") }}" @endif>
                      </td>
                      <td class="align-middle">
                        <input type="hidden" value="{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}">
                        {{-- <a href="#Retur{{ $a->id_bm }}" tabindex="{{ $tab += 2 }}" class="btn btn-link btn-sm text-bold text-right btnRetur" data-toggle="modal" style="font-size: 13px; width: 100%; padding-right: 0px; padding-top: 5px">{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}</a> --}}
                        <a href="{{ route('ap-retur-create', $a->id_bm) }}" tabindex="{{ $tab += 2 }}" class="btn btn-link btn-sm text-bold text-right btnRetur" style="font-size: 13px; width: 100%; padding-right: 0px; padding-top: 5px">{{ $retur[0]->total != null ? number_format($retur[0]->total, 0, "", ",") : '0' }}</a>
                      </td>
                      <td align="right" class="align-middle">
                        {{ $a->bm->last()->diskon == 'T' ? number_format($totalBM[0]->totBM - $total[0]->totTransfer - $retur[0]->total - $potBM[0]->potongan, 0, "", ",") : '' }}
                      </td>
                      <td align="center" class="align-middle text-bold" @if(($a->keterangan != null) && ($a->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>
                        {{-- <a href="#Detail{{ $a->id_bm }}" tabindex="{{ $tab += 3 }}" class="btn btn-link btn-sm text-bold btnDetail" data-toggle="modal" style="font-size: 13px">{{$a->keterangan}}</a> --}}
                        <a href="{{ route('ap-transfer-create', $a->id_bm) }}" tabindex="{{ $tab += 3 }}" class="btn btn-link btn-sm text-bold btnDetail" style="font-size: 13px">{{$a->keterangan}}</a>
                      </td>
                    </tr>
                    @php $i++ @endphp
                  @empty
                    <tr>
                      <td colspan=12 class="text-center text-bold h4 p-2"><i>Tidak ada daftar Account Payable</i></td>
                    </tr>
                  @endforelse
                </tbody>
                <tfoot>
                  <tr class="text-right text-bold text-dark" style="background-color: lightgrey; font-size: 14px">
                    <td colspan="6" class="text-center">Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>

              <!-- Button Submit dan Reset -->
              {{-- <div class="form-row justify-content-center" @if($ap->count() != 0) style="margin-top: -18px" @endif>
                <div class="col-1">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ap-transfer') }}" formmethod="POST">Submit</button>
                </div>
                <div class="col-1">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
              </div> --}}
              <!-- End Button Submit dan Reset -->
              
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

const transfer = document.querySelectorAll(".transfer");
const kodeBM = document.getElementById("kodeBM");
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
  "columnDefs": [
    { "orderable": false, "targets": [0, 5] }
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

    $.each([6, 7, 8, 9], function(index, value) {

      if((value == 6) || (value == 9)) {
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

      if((value == 6) || (value == 9)) {
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
/* for(let i = 0; i < transfer.length; i++) {
  // transfer[i].addEventListener("keyup", function(e) {
  //   $(this).val(function(index, value) {
  //     return value
  //     .replace(/\D/g, "")
  //     .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  //     ;
  //   });
  // })

  transfer[i].addEventListener("focus", function(e) {
    transfer[i].value = transfer[i].value.replace(/\,/g, "");
  })

  transfer[i].addEventListener("focusout", function(e) {
    transfer[i].value = addCommas(transfer[i].value);
  })

  transfer[i].addEventListener("change", function(e) {
    var arrKode = kodeBM.value.split(',');
    var kode = transfer[i].name.substr(-6);

    if(arrKode[0] != "") {
      kodeBM.value = kodeBM.value.concat(`,${kode}`);
    }
    else {
      kodeBM.value = kode;
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