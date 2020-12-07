@extends('pages.retur.kirimJual')
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
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Retur Penjualan</h1>
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
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan" autofocus>
                  </div>
                  <label for="status" class="col-auto col-form-label text-right text-bold">Status</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <select class="form-control form-control-sm mt-1" name="status">
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
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" placeholder="DD-MM-YYYY">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ml-3"> s / d </label>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1 ml-1" name="tglAkhir" id="tglAkhir" placeholder="DD-MM-YYYY">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('ap-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Tabel Data Detil AR -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($retur->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <th style="width: 30px" class="align-middle">No</th>
                    <th style="width: 60px" class="align-middle">No. Retur</th>
                    <th style="width: 60px" class="align-middle">Tgl. Retur</th>
                    <th class="align-middle">Customer</th>
                    <th style="width: 70px" class="align-middle">No. Faktur</th>
                    <th style="width: 60px" class="align-middle">Qty Faktur</th>
                    <th style="width: 40px" class="align-middle">Qty Retur</th>
                    <th style="width: 50px" class="align-middle">Qty Kirim</th>
                    <th style="width: 50px" class="align-middle">Qty Tidak Retur</th>
                    <th style="width: 50px" class="align-middle">Qty Kurang</th>
                    <th style="width: 70px" class="align-middle">Status</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php $i = 1 @endphp
                  @forelse($retur as $r)
                    @php 
                      $qtyFaktur = App\Models\DetilSO::selectRaw('sum(qty) as total')
                                ->where('id_so', $r->id_faktur)->get();
                      $qtyRetur = App\Models\DetilRetur::selectRaw('sum(qty) as total')
                                ->where('id_retur', $r->id)->get();
                      $qtyProses = App\Models\DetilRJ::selectRaw('sum(qty_kirim) as totalKirim,
                                sum(qty_batal) as totalBatal')
                                ->where('id_retur', $r->id)->get();
                    @endphp
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="align-middle text-center">{{ $r->id }}</td>
                      <td class="align-middle text-center">
                        {{ \Carbon\Carbon::parse($r->tanggal)->format('d-M-y') }}
                      </td>
                      <td class="align-middle">{{ $r->so->customer->nama }}</td>
                      <td class="align-middle text-center">{{ $r->id_faktur }}</td>
                      <td class="align-middle text-center">{{ $qtyFaktur[0]->total }}</td>
                      <td class="align-middle text-right">{{ $qtyRetur[0]->total }}</td>
                      <td class="align-middle text-right">{{ $qtyProses[0]->totalKirim }}</td>
                      <td class="align-middle text-right">{{ $qtyProses[0]->totalBatal }}</td>
                      <td class="align-middle text-right">{{ $qtyRetur[0]->total - ($qtyProses[0]->totalBatal + $qtyProses[0]->totalKirim) }}</td>
                      <td align="center" class="align-middle text-bold" @if($r->status != "INPUT") style="background-color: lightgreen" @else style="background-color: lightpink" @endif>
                        <a href="#Detail{{ $r->id }}" class="btn btn-link btn-sm text-bold btnDetail" data-toggle="modal" style="font-size: 13px">{{$r->status}}
                        </a>
                      </td>
                    </tr>
                    @php $i++ @endphp
                  @empty
                    <tr>
                      <td colspan="9" class="text-center text-bold h4 p-2"><i>Tidak ada daftar account receivable</i></td>
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

/** Input nominal comma separator **/
for(let i = 0; i < transfer.length; i++) {
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
}

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