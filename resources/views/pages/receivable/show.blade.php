@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
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
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan" value="{{ $bulan }}">
                  </div>
                  <label for="status" class="col-auto col-form-label text-right text-bold">Status</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="status" id="status" value="{{ $status }}">
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-right text-bold">Dari Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" value="{{ $tglAwal }}">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ml-3">s / d</label>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1 ml-1" name="tglAkhir" value="{{ $tglAkhir }}">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('ar-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center" @if($so->count() != 0) style="margin-bottom: -18px" @else style="margin-bottom: 18px" @endif>
                <div class="col-1">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ar-process') }}" formmethod="POST">Submit</button>
                </div>
                <div class="col-1">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
              </div>
              <!-- End Button Submit dan Reset -->

              <!-- Tabel Data Detil AR -->
              <input type="hidden" id="kodeSO" name="kodeSO">
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($so->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
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
                  @php $i = 1 @endphp
                  @forelse($so as $s)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="align-middle">{{ $s->customer->nama }}</td>
                      <td class="align-middle">{{ $s->customer->sales->nama }}</td>
                      <td align="center" class="align-middle">{{ $s->kategori }}</td>
                      <td align="center" class="align-middle">{{ $s->id }}</td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($s->tgl_so)->format('d-M-y') }}
                      </td>
                      <td align="center" class="align-middle">
                        {{ \Carbon\Carbon::parse($s->tgl_so)->add($s->tempo, 'days')
                          ->format('d-M-y') }}
                      </td>
                      <td align="right" class="align-middle">
                        {{ number_format($s->total, 0, "", ",") }}
                      </td>
                      <td class="align-middle">
                        <input type="text" name="cic{{$s->id}}" id="cicil" class="form-control form-control-sm text-bold text-dark text-right cicil" @if($s->ar != null) value="{{ number_format($s->ar->cicil, 0, "", ",") }}" @endif>
                      </td>
                      <td class="align-middle">
                        <input type="text" name="ret{{$s->id}}" id="retur" class="form-control form-control-sm text-bold text-dark text-right retur" 
                        @if($s->ar != null) value="{{ number_format($s->ar->retur, 0, "", ",") }}" @endif>
                      </td>
                      <td align="right" class="align-middle">{{ number_format($s->total, 0, "", ",") }}</td>
                      <td align="center" class="align-middle text-bold" @if(($s->ar != null) && ($s->ar->keterangan == "LUNAS")) style="background-color: lightgreen" @else style="background-color: lightpink" @endif>
                        @if($s->ar != null) {{$s->ar->keterangan}} @else BELUM LUNAS @endif
                      </td>
                    </tr>
                    @php $i++ @endphp
                  @empty
                    <tr>
                      <td colspan=12 class="text-center text-bold h4 p-2"><i>Tidak ada daftar account receivable</i></td>
                    </tr>
                  @endforelse
                </tbody>
              </table>

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center" @if($so->count() != 0) style="margin-top: -18px" @endif>
                <div class="col-1">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ar-process') }}" formmethod="POST">Submit</button>
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
const cicil = document.querySelectorAll(".cicil");
const retur = document.querySelectorAll(".retur");
const kodeSO = document.getElementById("kodeSO");

/** Sort Datatable **/
$('#dataTable').dataTable( {
  "columnDefs": [
    { "orderable": false, "targets": [0, 3] }
  ],
  "aaSorting" : []
});

/** Input nominal comma separator **/
for(let i = 0; i < cicil.length; i++) {
  cicil[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value
      .replace(/\D/g, "")
      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
      ;
    });
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

  retur[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value
      .replace(/\D/g, "")
      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
      ;
    });
  })
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

  /*-- Autocomplete Input Barang --*/
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