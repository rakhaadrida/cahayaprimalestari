@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Data Harga {{ $barang->nama }}</h1>
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
            <form action="{{ route('storeHarga')}}" method="POST">
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-auto col-form-label text-bold">Kode Barang</label>
                <span class="col-form-label text-bold ml-1">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="kode" value="{{ $barang->id }}" readonly>
                </div>
              </div>
              <div class="form-group row">
                <label for="nama" class="col-auto col-form-label text-bold">Nama Barang</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="nama" value="{{ $barang->nama }}" readonly>
                </div>
              </div>
              <hr>
              @php $i = 0; $tab = 1; @endphp
              @foreach($harga as $h)
                <div class="form-row">
                  <div class="form-group col-2">
                    <label for="harga" class="col-form-label text-bold">Price List</label>
                    <input type="text" tabindex="0" class="form-control col-form-label-sm harga" id="harga" name="harga[]" readonly
                      @foreach($items as $item)
                        @if($item->id_harga == $h->id)
                          value="{{ number_format($item->harga, 0, "", ".") }}" 
                          @break
                        @endif
                      @endforeach
                    />
                  </div>
                  <div class="form-group col-2 ml-2">
                    <label for="ppn" class="col-form-label text-bold">PPN</label>
                    <input type="text" tabindex="0" class="form-control col-form-label-sm ppn" id="ppn" name="ppn[]" readonly
                      @foreach($items as $item)
                        @if($item->id_harga == $h->id)
                          value="{{ number_format($item->ppn, 0, "", ".") }}" 
                          @break
                        @endif
                      @endforeach
                    />
                  </div>
                  <div class="form-group col-2 ml-2">
                    <label for="hargaPPN" class="col-form-label text-bold">{{ $h->nama }}</label>
                    <input type="text" tabindex="{{ $tab++ }}" class="form-control col-form-label-sm hargaPPN" id="hargaPPN" name="hargaPPN[]" onkeypress="return angkaSaja(event)" data-toogle="tooltip" data-placement="right" title="Hanya input angka 0-9" required @if($i == 0) autofocus @endif
                      @foreach($items as $item)
                        @if($item->id_harga == $h->id)
                          value="{{ number_format($item->harga_ppn, 0, "", ".") }}" 
                          @break
                        @endif
                      @endforeach
                    />
                  </div>
                </div>
                @php $i++; @endphp
              @endforeach
              <hr>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" tabindex="{{ $tab++ }}" class="btn btn-success btn-block text-bold">Update</button>
                </div>
                <div class="col-2">
                  <button type="reset" tabindex="{{ $tab+= 2 }}" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                </div>
                <div class="col-2">
                  <a href="{{ url()->previous() }}" tabindex="{{ $tab+= 3 }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
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
const harga = document.querySelectorAll(".harga");
const ppn = document.querySelectorAll(".ppn");
const hargaPPN = document.querySelectorAll(".hargaPPN");

/** Tampil Pricelist dan PPN **/
for(let i = 0; i < harga.length; i++) {
  hargaPPN[i].addEventListener('keyup', function(e) {
    console.log(e.target.value.replace(/\./g, ""));
    harga[i].value = addCommas(Math.floor(+e.target.value.replace(/\./g, "") / 1.1));
    ppn[i].value = addCommas(Math.floor(+e.target.value.replace(/\./g, "") - harga[i].value.replace(/\./g, "")));
  });

  hargaPPN[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value
      .replace(/\D/g, "")
      .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      ;
    });
  });
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    $(hargaPPN).tooltip('show');

    return false;
  }
  return true;
}

/** Add Thousand Separators **/
function addCommas(nStr) {
	nStr += '';
	x = nStr.split(',');
	x1 = x[0];
	x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
}

$(function() {
  $("[autofocus]").on("focus", function() {
    if (this.setSelectionRange) {
      var len = this.value.length * 2;
      this.setSelectionRange(len, len);
    } else {
      this.value = this.value;
    }
    this.scrollTop = 999999;
  }).focus();
});

</script>
@endpush