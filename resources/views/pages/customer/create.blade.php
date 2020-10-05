@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Tambah Data Customer</h1>
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
            <form action="{{ route('customer.store') }}" method="POST">
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-1 col-form-label text-bold">Kode </label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode"
                  value="{{ $newcode }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-1 col-form-label text-bold">Nama</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-6">
                  <input type="text" class="form-control col-form-label-sm" name="nama" placeholder="Nama Customer" value="{{ old('nama') }}" required>
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label for="alamat" class="col-1 col-form-label text-bold">Alamat</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-7">
                  <textarea name="alamat" class="form-control col-form-label-sm" rows="2" required>
                    {{ old('alamat') }}
                  </textarea>
                </div>
              </div>
              <div class="form-group row">
                <label for="telepon" class="col-1 col-form-label text-bold">Telepon</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="telepon" placeholder="021-xxxxx" value="{{ old('telepon') }}" required>
                </div>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="contact_person" placeholder="Contact Person" 
                  value="{{ old('contact_person') }}" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="npwp" class="col-1 col-form-label text-bold">NPWP</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="npwp" placeholder="Nomor NPWP" value="{{ old('npwp') }}">
                </div>
              </div>
              <hr>
              <div class="form-group row">
                <label for="limit" class="col-1 col-form-label text-bold">Limit</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="limit" placeholder="Limit" value="{{ old('limit') }}">
                </div>
              </div>
              <div class="form-group row">
                <label for="sales_cover" class="col-1 text-bold">Sales Cover</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm mt-1" name="namaSales" id="sales" placeholder="Sales Cover" value="{{ old('sales_cover') }}" required>
                  <input type="hidden" name="id_sales" id="kodeSales">
                </div>
              </div>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold">Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
              </div>
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
const namaSales = document.getElementById("sales");
const kodeSales = document.getElementById("kodeSales");

namaSales.addEventListener("change", displayKode);

function displayKode(e) {
  @foreach($sales as $s)
    if('{{ $s->nama }}' == e.target.value) {
      kodeSales.value = '{{ $s->id }}';
    }
  @endforeach
}

/** Autocomplete Input Text **/
$(function() {
  var sales = [];
  @foreach($sales as $s)
    sales.push('{{ $s->nama }}');
  @endforeach

  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Barang --*/
  $(namaSales).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(sales, extractLast(request.term)));
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

  /*-- Autocomplete Input Supplier --*/
  $(namaSupplier).on("keydown", function(event) {
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
});
</script>
@endpush