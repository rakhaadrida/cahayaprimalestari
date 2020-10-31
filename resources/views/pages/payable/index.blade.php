@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan">
                  </div>
                  <label for="status" class="col-auto col-form-label text-right text-bold">Status</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="status" id="status">
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-right text-bold">Dari Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ml-3"> s / d </label>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1 ml-1" name="tglAkhir" >
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('ap-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center" @if($ap->count() != 0) style="margin-bottom: -18px" @else style="margin-bottom: 18px" @endif>
                <div class="col-1">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ap-transfer') }}" formmethod="POST">Submit</button>
                </div>
                <div class="col-1">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
              </div>
              <!-- End Button Submit dan Reset -->

              <!-- Tabel Data Detil AR -->
              <input type="hidden" id="kodeBM" name="kodeBM">
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($ap->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <th style="width: 30px" class="align-middle">No</th>
                    <th style="width: 170px" class="align-middle">Supplier</th>
                    <th style="width: 60px" class="align-middle">No. BM</th>
                    <th style="width: 75px" class="align-middle">Tgl. BM</th>
                    <th style="width: 60px" class="align-middle">Discount</th>
                    <th style="width: 70px" class="align-middle">HPP</th>
                    <th style="width: 75px" class="align-middle">Total</th>
                    <th style="width: 70px" class="align-middle">Transfer</th>
                    <th style="width: 70px" class="align-middle">Kurang Bayar</th>
                    <th style="width: 60px" class="align-middle">Keterangan</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php $i = 1 @endphp
                  @forelse($ap as $a)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="align-middle">{{ $a->bm->supplier->nama }}</td>
                      <td align="center" class="align-middle"><button type="submit" formaction="{{ route('ap-detail', $a->id_bm) }}" formmethod="POST" class="btn btn-link btn-sm text-bold">{{ $a->id_bm }}</button></td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($a->bm->tanggal)->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle" @if($a->bm->detilbm[0]->diskon != '') style="background-color: lightgreen" @endif>
                        @if($a->bm->detilbm[0]->diskon != '') INPUT @else KOSONG @endif
                      </td>
                      <td align="center" class="align-middle" @if($a->bm->detilbm[0]->diskon != '') style="background-color: lightgreen" @endif>
                        @if($a->bm->detilbm[0]->diskon != '') INPUT @else KOSONG @endif
                      </td>
                      <td align="right" class="align-middle">
                        @if($a->bm->detilbm[0]->diskon != '') {{ number_format($a->bm->total, 0, "", ",") }} @endif
                      </td>
                      <td align="right" class="align-middle">
                        <input type="text" name="tr{{$a->id_bm}}" id="transfer" class="form-control form-control-sm text-bold text-dark text-right transfer" @if($a->transfer != null) value="{{ number_format($a->transfer, 0, "", ",") }}" @endif>
                      </td>
                      <td align="right" class="align-middle">{{ number_format($a->bm->total - $a->transfer, 0, "", ",") }}</td>
                      <td align="center" class="align-middle text-bold align-middle" @if(($a->keterangan != null) && ($a->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>{{$a->keterangan}}</td>
                    </tr>
                    @php $i++ @endphp
                  @empty
                    <tr>
                      <td colspan=12 class="text-center text-bold h4 p-2"><i>Tidak ada daftar account receivable</i></td>
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
                  </tr>
                </tfoot>
              </table>

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center" @if($ap->count() != 0) style="margin-top: -18px" @endif>
                <div class="col-1">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ap-transfer') }}" formmethod="POST">Submit</button>
                </div>
                <div class="col-1">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
              </div>
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

<script>
const transfer = document.querySelectorAll(".transfer");
const kodeBM = document.getElementById("kodeBM");

/** Sort Datatable **/
$('#dataTable').dataTable( {
  "columnDefs": [
    { "orderable": false, "targets": [0, 4, 5] }
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

    $.each([6, 7, 8], function(index, value) {

      if((value == 6) || (value == 8)) {
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

      if((value == 6) || (value == 8)) {
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
for(let i = 0; i < transfer.length; i++) {
  transfer[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value
      .replace(/\D/g, "")
      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
      ;
    });
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