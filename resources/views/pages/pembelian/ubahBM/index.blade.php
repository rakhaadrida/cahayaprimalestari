@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Ubah Barang Masuk</h1>
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
                <div class="form-group row">
                  <label for="kode" class="col-2 col-form-label text-bold">Nomor BM</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="id" id="kode">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold">Nama Supplier</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" id="namaSupplier" name="nama">
                    <input type="hidden" name="kode" id="kodeSupplier">
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Tanggal Awal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Tanggal Akhir</label>
                  <span class="col-form-label text-bold ml-2">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAkhir" >
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('bm-show') }}" formmethod="GET" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
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
<script type="text/javascript">
const namaSupp = document.getElementById('namaSupplier');
const kodeSupp = document.getElementById('kodeSupplier');
const kodeBM = document.getElementById('kode');

/** Call Fungsi Setelah Inputan Terisi **/
namaSupp.addEventListener("change", displayKode);

function displayKode(e) {
  @foreach($supplier as $s)
    if('{{ $s->nama }}' == e.target.value) {
      kodeSupp.value = '{{ $s->id }}';
    }
  @endforeach
}

/** Autocomplete Input Text **/
$(function() {
  var supplier = [];
  var kodeMasuk = [];
  @foreach($supplier as $s)
    supplier.push('{{ $s->nama }}');
  @endforeach
  @foreach($bm as $b)
    kodeMasuk.push('{{ $b->id }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Customer --*/
  $(namaSupp).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(supplier, extractLast(request.term)));
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

  /*-- Autocomplete Input Kode SO --*/
  $(kodeBM).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kodeMasuk, extractLast(request.term)));
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