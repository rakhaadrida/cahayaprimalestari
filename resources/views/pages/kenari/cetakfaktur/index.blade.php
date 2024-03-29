@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Cetak Faktur</h1>
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
                <div class="form-group row justify-content-center" >
                  <label for="kode" class="col-auto col-form-label text-bold">Nomor Faktur</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm mt-1 kodeAwal" name="kodeAwal" id="kodeAwal" placeholder="Kode Awal" data-toogle="tooltip" data-placement="top" title="Kolom ini harus diisi" autofocus>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s / d</label>
                  <div class="col-2">
                    <input type="text" tabindex="2" class="form-control form-control-sm mt-1 kodeAkhir" name="kodeAkhir" id="kodeAkhir" placeholder="Kode Akhir" data-toogle="tooltip" data-placement="top" title="Kolom ini harus diisi">
                  </div>
                  <div class="col-2 mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="3" id="btnCetak" class="btn btn-success btn-sm btn-block text-bold btnCetak" onclick="return checkRequired(event)" >Cetak</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Tabel Detil Transaksi Harian -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="text-center text-bold text-dark">
                  <th style="width: 20px" class="align-middle">No</th>
                  <th style="width: 70px" class="align-middle">Nomor Faktur</th>
                  <th style="width: 80px" class="align-middle">Tgl Faktur</th>
                  <th class="align-middle">Customer</th>
                  <th style="width: 80px" class="align-middle">Total</th>
                  <th style="width: 80px" class="align-middle">Kategori</th>
                  <th style="width: 70px" class="align-middle">Tempo</th>
                  <th style="width: 80px" class="align-middle">Status</th>
                </thead>
                <tbody>
                  @php $i=1; $tab = 3; @endphp
                  @forelse ($items as $item)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="text-center"><button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('trans-detail', $item->id) }}" formmethod="POST" class="btn btn-sm btn-link text-bold">{{ $item->id }}</button></td>
                      <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y')  }}</td>
                      <td class="align-middle">{{ $item->customer->nama }}</td>
                      <td class="text-right align-middle">{{ number_format($item->total, 0, "", ",") }}</td>
                      <td class="text-center align-middle">{{ $item->kategori }}</td>
                      <td class="text-center align-middle">{{ $item->tempo }} Hari</td>
                      <td class="text-center align-middle">{{ $item->status }}</td>
                    </tr>
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan="8" class="text-center">Tidak Ada Data Transaksi pada Tanggal Ini</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <!-- End Tabel Detil Transaksi Harian --> 
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- @if($status == "true")
  <!-- Tampilan Cetak -->
  <iframe src="{{url('kenari/cetak/'.$awal.'/'.$akhir)}}" id="frameCetak" name="frameCetak" frameborder="0" hidden></iframe>
@endif --}}
<!-- /.container-fluid -->
@endsection

@push('addon-script')
<script src="{{ url('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('backend/js/demo/datatables-demo.js') }}"></script>
<script type="text/javascript">
$('#dataTable').dataTable({
  "pageLength": 25
});

const kodeAwal = document.getElementById('kodeAwal');
const kodeAkhir = document.getElementById('kodeAkhir');
const btnCetak = document.getElementById('btnCetak');

/* @if($status == "true")
  const printFrame = document.getElementById("frameCetak").contentWindow;
  
  printFrame.window.onafterprint = function(e) {
    window.location = "{{ route('cetak-update-kenari', ['awal' => $awal, 'akhir' => $akhir]) }}";
  }

  printFrame.window.print();

@endif */

function checkRequired(e) {
  if((kodeAwal.value == '') || (kodeAkhir.value == '')) {
    $(kodeAwal).tooltip('show');
    $(kodeAkhir).tooltip('show');
    return false;
  } else {
    document.getElementById('btnCetak').formMethod = "POST";
    document.getElementById('btnCetak').formAction = "{{ route('cetak-process-kenari') }}";
  }
}

/** Autocomplete Input Text **/
$(function() {
  var kodeSO = [];
  @foreach($items as $i)
    kodeSO.push('{{ $i->id }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Kode SO --*/
  $(kodeAwal).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kodeSO, extractLast(request.term)));
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

  $(kodeAkhir).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kodeSO, extractLast(request.term)));
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