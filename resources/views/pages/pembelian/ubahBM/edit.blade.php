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
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold text-dark">Nomor BM</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold" name="kode" value="{{ $items[0]->id_bm }}">
                      </div>
                    </div>  
                  </div>
                  <div class="col" style="margin-left: -380px">
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right text-dark">Tanggal BM</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="tglBM" 
                        value="{{ \Carbon\Carbon::parse($items[0]->bm->tanggal)->format('d-M-y') }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSupp" class="col-5 col-form-label text-bold text-right text-dark">Nama Supplier</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaSupp"
                        value="{{ $items[0]->bm->supplier->nama }}">
                        <input type="hidden" name="kodeSupp" 
                        value="{{ $items[0]->bm->id_supplier }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSales" class="col-5 col-form-label text-bold text-right text-dark">Nama Gudang</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaGudang"
                        value="{{ $items[0]->bm->gudang->nama }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row so-update-left">
                  <label for="nama" class="col-2 col-form-label text-bold text-dark">Tanggal Update</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" readonly class="form-control-plaintext form-control-sm text-bold" name="tanggal" value="{{ $tanggal }}">
                  </div>
                </div>
                <div class="form-group row so-update-input">
                  <label for="alamat" class="col-2 col-form-label text-bold text-dark">Keterangan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-5">
                    <input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm mt-1" required>
                    <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $items->count() }}">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="nama" value="{{ $nama }}">
                    <input type="hidden" name="tglAwal" value="{{ $tglAwal }}">
                    <input type="hidden" name="tglAkhir" value="{{ $tglAkhir }}">
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" >
                <thead class="text-center text-bold text-dark">
                  <td style="width: 50px">No</td>
                  <td style="width: 100px">Kode</td>
                  <td>Nama Barang</td>
                  <td style="width: 80px">Qty</td>
                  <td>Harga</td>
                  <td>Jumlah</td>
                  <td>Hapus</td>
                </thead>
                <tbody id="tablePO">
                  @php 
                    $i = 1; $subtotal = 0;
                  @endphp
                  @foreach($items as $item)
                    <tr class="text-bold" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" name="kodeBarang[]" class="form-control form-control-sm text-bold kodeBarang" value="{{ $item->id_barang }}" required>
                      </td>
                      <td>
                        <input type="text" name="namaBarang[]" class="form-control form-control-sm text-bold namaBarang" value="{{ $item->barang->nama }}" required>
                      </td>
                      <td> 
                        <input type="text" name="qty[]" class="form-control form-control-sm text-bold qty" value="{{ $item->qty }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" required>
                      </td>
                      <td align="right">
                        <input type="text" name="harga[]" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" value="{{ number_format($item->harga, 0, "", ".") }}" readonly>
                      </td>
                      <td align="right">
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ number_format($item->qty * $item->harga, 0, "", ".") }}" >
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                      @php $subtotal += $item->qty * $item->harga; @endphp
                    </tr>
                    @php $i++; @endphp
                  @endforeach
                </tbody>
              </table>

              <div class="form-group row justify-content-end subtotal-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal, 0, "", ".") }}" >
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" 
                  value="0" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-right" 
                  value="{{ number_format($subtotal, 0, "", ".") }}"
                  />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('bm-update') }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
                </div>
                <div class="col-2">
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
@endsection

@push('addon-script')
<script type="text/javascript">
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const hapusBaris = document.querySelectorAll(".icRemove");
const subtotal = document.getElementById('subtotal');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
const jumBaris = document.getElementById('jumBaris');

/** Tampil Nama dan Kode Barang Otomatis **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("change", displayHarga) ;
  kodeBarang[i].addEventListener("change", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        qty[i].setAttribute('required', true);
      }
    @endforeach
  }
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("change", function (e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
    }
    else {
      netPast = +jumlah[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));

      checkSubtotal(netPast, +jumlah[i].value.replace(/\./g, ""));
    }
    total_ppn(subtotal.value.replace(/\./g, ""));
  });
} 

/** Check Jumlah Netto onChange **/
function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
  }
}

/** Hitung PPN Dan Total **/
function total_ppn(sub) {
  ppn.value = 0;
  grandtotal.value = addCommas(+sub + +ppn.value.replace(/\./g, ""));
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {   
    for(let i = 1; i <= qty.length; i++) {
      if(inputan == i)
        $(qty[inputan-1]).tooltip('show');
    }

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

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    if(qty[i].value != "") {
       subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      total_ppn(subtotal.value.replace(/\./g, ""));
    }

    for(let j = i; j < hapusBaris.length; j++) {
      if(j == hapusBaris.length - 1) {
        $(tablePO).find('tr:last-child').remove();  
      }
      else {
        jumlah[j].value = jumlah[j+1].value;
        harga[j].value = harga[j+1].value;
        qty[j].value = qty[j+1].value;
        brgNama[j].value = brgNama[j+1].value;
        kodeBarang[j].value = kodeBarang[j+1].value;
        if(kodeBarang[j+1].value == "")
          qty[j].removeAttribute('required');
        else
          qty[j+1].removeAttribute('required');
      }     
    }
    jumBaris.value -= 1; 
  });
}

/** Autocomplete Input Text **/
$(function() {
  var kodeBrg = [];
  var namaBrg = [];
  @foreach($barang as $b)
    kodeBrg.push('{{ $b->id }}');
    namaBrg.push('{{ $b->nama }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Barang --*/
  for(let i = 0; i < brgNama.length; i++) {
    $(brgNama[i]).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(namaBrg, extractLast(request.term)));
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
  }

  for(let i = 0; i < kodeBarang.length; i++) {
    $(kodeBarang[i]).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(kodeBrg, extractLast(request.term)));
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
  }
});


</script>
@endpush