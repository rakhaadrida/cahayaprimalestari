@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Cetak Barang Masuk</h1>
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
                  <label for="kode" class="col-auto col-form-label text-bold">Nomor BM</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm mt-1 kodeAwal" name="kodeAwal" id="kodeAwal" placeholder="Kode Awal" data-toogle="tooltip" data-placement="top" title="Kolom ini harus diisi" autofocus>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s / d</label>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm mt-1 kodeAkhir" name="kodeAkhir" id="kodeAkhir" placeholder="Kode Akhir" data-toogle="tooltip" data-placement="top" title="Kolom ini harus diisi">
                  </div>
                  <div class="col-2 mt-1" style="margin-left: -10px">
                    <button type="submit" id="btnCetak" class="btn btn-success btn-sm btn-block text-bold btnCetak" formaction="{{ route('cetak-bm-process') }}" formmethod="POST">Cetak</button>
                    {{-- formaction="{{ route('cetak-process') }}" formmethod="POST" 
                    onclick="return checkRequired(event)"--}}
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Tabel Detil Transaksi Harian -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="text-center text-bold text-dark">
                  <th style="width: 20px" class="align-middle">No</th>
                  <th style="width: 70px" class="align-middle">Nomor BM</th>
                  <th style="width: 80px" class="align-middle">Tgl BM</th>
                  <th class="align-middle">Supplier</th>
                  <th style="width: 130px" class="align-middle">Gudang</th>
                  <th style="width: 100px" class="align-middle">Total</th>
                  <th style="width: 80px" class="align-middle">Status</th>
                </thead>
                <tbody>
                  @php $i=1; @endphp
                  @forelse ($items as $item)
                    <tr class="text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td class="text-center"><button type="submit" formaction="{{ route('cetak-bm-detail', $item->id) }}" formmethod="POST" class="btn btn-sm btn-link text-bold">{{ $item->id }}</button></td>
                      <td class="text-center align-middle">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y')  }}</td>
                      <td class="align-middle">{{ $item->supplier->nama }}</td>
                      <td class="text-center align-middle">{{ $item->gudang->nama }}</td>
                      <td class="text-right align-middle">{{ number_format($item->total, 0, "", ",") }}</td>
                      <td class="text-center align-middle">{{ $item->status }}</td>
                    </tr>
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan="7" class="text-center">Tidak Ada Data Barang Masuk pada Tanggal Ini</td>
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
@if($status == "true")
  <!-- Tampilan Cetak -->
  <iframe src="{{url('cetak-bm-all/'.$awal.'/'.$akhir)}}" id="frameCetak" name="frameCetak" frameborder="0" hidden></iframe>
@endif
<!-- /.container-fluid -->
@endsection

@push('addon-script')
<script src="{{ url('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('backend/js/demo/datatables-demo.js') }}"></script>
{{-- <script src="{{ url('backend/vendor/jquery/jquery.printPageSO.js') }}"></script> --}}
<script type="text/javascript">
const kodeAwal = document.getElementById('kodeAwal');
const kodeAkhir = document.getElementById('kodeAkhir');
const btnCetak = document.getElementById('btnCetak');

@if($status == "true")
  const printFrame = document.getElementById("frameCetak").contentWindow;
  // const kodeAwal = document.querySelectorAll(".kodeAwal");
  // const kodeAkhir = document.querySelectorAll(".kodeAkhir");

  /** Cara 1 **/
  // const printFrame = document.createElement('iframe');
  // printFrame.src = "{{url('cetak/'.$awal.'/'.$akhir)}}";
  // printFrame.id = 'print_pdf';
  // printFrame.name = 'print_pdf';

  // printFrame.onload = () => {
  //   printFrame.contentWindow.onafterprint = function(e) {
  //     alert('ok');
  //   }
    // printFrame.focus();
    // printFrame.print();
  // };

  // document.body.appendChild(printFrame);
  // window.frames['print_pdf'].focus();
  // window.frames['print_pdf'].print();
  /** End Cara 1 **/


  /** Cara 2 **/
  printFrame.window.onafterprint = function(e) {
    // alert('ok');
    // window.location = "{{ route('cetak-bm-update', ['awal' => $awal, 'akhir' => $akhir]) }}";
  }

  
  // window.frames["frameCetak"].window.print();
  
  printFrame.window.print();
  // window.print();
  /** End Cara 2 **/


  /** Cara 3 **/
  /* var afterPrint = function(e) {
    // clearTimeout(timer);
    // window.focus();
    kodeAwal[0].focus();
    window.onfocus = function() {
      window.location = "{{ route('cetak-update', ['awal' => $awal, 'akhir' => $akhir]) }}";
    }
    // kodeAwal[0].removeAttribute("required");
    // kodeAkhir[0].removeAttribute("required");
    // document.getElementById("updateCetak").click();
  } */
  
  // if(printFrame.contentWindow.onafterprint) { 
  //   $(printFrame).contentWindow.on("afterprint", afterPrint);  
  // }
  // else { 
  //   // var timer = setTimeout(afterPrint, 5000);
  //   setTimeout(afterPrint, 0);
  // }
  /** End Cara 3 **/
@endif

function checkRequired(e) {
  if((kodeAwal.value == '') || (kodeAkhir.value == '')) {
    $(kodeAwal).tooltip('show');
    $(kodeAkhir).tooltip('show');
    return false;
  } else {
    document.getElementById('btnCetak').formMethod = "POST";
    document.getElementById('btnCetak').formAction = "{{ route('cetak-process') }}";
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