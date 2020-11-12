@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Ubah Faktur</h1>
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
                      <label for="kode" class="col-2 col-form-label text-bold text-dark">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark" name="kode" value="{{ $items[0]->id_so }}">
                      </div>
                    </div>  
                  </div>
                  <div class="col" style="margin-left: -380px">
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right text-dark">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="tglSO" 
                        value="{{ \Carbon\Carbon::parse($items[0]->so->tgl_so)->format('d-M-y') }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaCust" class="col-5 col-form-label text-bold text-right text-dark">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="namaCust"
                        value="{{ $items[0]->so->customer->nama }}">
                        <input type="hidden" name="kodeCust" 
                        value="{{ $items[0]->so->id_customer }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSales" class="col-5 col-form-label text-bold text-right text-dark">Nama Sales</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="namaSales"
                        value="{{ $items[0]->so->customer->sales->nama }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row so-update-left">
                  <label for="nama" class="col-2 col-form-label text-bold text-dark">Tanggal Update</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark" name="tanggal" value="{{ $tanggal }}">
                  </div>
                </div>
                <div class="form-group row so-update-input">
                  <label for="alamat" class="col-2 col-form-label text-bold text-dark">Keterangan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-5">
                    <input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm mt-1 text-dark" required>
                    <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $itemsRow }}">
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
                  <tr>
                    <td style="width: 30px">No</td>
                    <td style="width: 80px">Kode</td>
                    <td>Nama Barang</td>
                    <td style="width: 55px">Qty</td>
                    @foreach($gudang as $g)
                      <td style="width: 55px">{{ substr($g->nama, 0, 3) }}</td>
                    @endforeach
                    <td style="width: 80px">Harga</td>
                    <td style="width: 100px">Jumlah</td>
                    <td style="width: 100px">Diskon(%)</td>
                    <td style="width: 90px">Diskon(Rp)</td>
                    <td style="width: 100px">Netto (Rp)</td>
                    <td>Hapus</td>
                  </tr>
                </thead>
                <tbody id="tablePO">
                  @php 
                    $i = 1; $subtotal = 0;
                    $itemsDetail = \App\Models\DetilSO::with(['barang'])
                                    ->select('id_barang', 'diskon')
                                    ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                    ->where('id_so', $items[0]->id_so)
                                    ->groupBy('id_barang', 'diskon')
                                    ->get();
                  @endphp
                  @foreach($itemsDetail as $item)
                    <tr class="text-dark" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" name="kodeBarang[]" class="form-control form-control-sm text-bold text-dark kodeBarang" value="{{ $item->id_barang }}" required>
                      </td>
                      <td>
                        <input type="text" name="namaBarang[]" class="form-control form-control-sm text-bold text-dark namaBarang" value="{{ $item->barang->nama }}" required>
                      </td>
                      <td> 
                        <input type="text" name="qty[]" class="form-control form-control-sm text-bold text-dark text-right qty" value="{{ $item->qty }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" required>
                      </td>
                      @foreach($gudang as $g)
                        @php
                          $itemGud = \App\Models\DetilSO::where('id_so', $items[0]->id_so)
                                    ->where('id_barang', $item->id_barang)
                                    ->where('id_gudang', $g->id)->get();
                        @endphp
                        @if($itemGud->count() != 0)
                          <td align="right" class="text-dark text-bold align-middle">
                            {{ $itemGud[0]->qty }}
                          </td>
                        @else
                          <td></td>
                        @endif
                      @endforeach
                      <td align="right">
                        <input type="text" name="harga[]" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" value="{{ number_format($item->harga, 0, "", ".") }}" readonly>
                      </td>
                      <td align="right">
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ number_format($item->qty * $item->harga, 0, "", ".") }}" >
                      </td>
                      <td align="right" style="width: 60px">
                        <input type="text" name="diskon[]" class="form-control form-control-sm text-bold text-right diskon" value="{{ $item->diskon }}" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9 dan tanda +" required>
                      </td>
                      @php 
                        $diskon = 100;
                        $arrDiskon = explode("+", $item->diskon);
                        for($j = 0; $j < sizeof($arrDiskon); $j++) {
                          $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                        } 
                        $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                      @endphp
                      <td align="right" style="width: 120px" >
                        <input type="text" name="diskonRp[]" id="diskonRp" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark diskonRp" 
                        value="{{ number_format((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100, 0, "", ".") }}" >
                      </td>
                      <td align="right">
                        <input type="text" name="netto[]" id="netto" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto" value="{{ number_format(($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100), 0, "", ".") }}" >
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                      @php $subtotal += ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100); 
                      @endphp
                    </tr>
                    {{-- <tr class="text-bold">
                      <td align="center">{{ $i }}</td>
                      <td align="center">{{ $item->id_barang }} </td>
                      <td>{{ $item->barang->nama }}</td>
                      <td align="right">{{ $item->qty }}</td>
                      <td align="right">{{ $item->harga }}</td>
                      <td align="right">{{ $item->qty * $item->harga }}</td>
                      <td align="right">{{ $item->diskon }} %</td>
                      <td align="right">
                        {{ (($item->qty * $item->harga) * $item->diskon) / 100 }}
                      </td>
                      <td align="right">
                        {{ ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100) }}
                      </td>
                      @php $subtotal += ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100); 
                      @endphp
                    </tr> --}}
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
                  <button type="submit" formaction="{{ route('so-update') }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
const diskon = document.querySelectorAll(".diskon");
const diskonRp = document.querySelectorAll(".diskonRp");
const netto = document.querySelectorAll(".netto");
const hapusBaris = document.querySelectorAll(".icRemove");
const subtotal = document.getElementById('subtotal');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
const jumBaris = document.getElementById('jumBaris');

/** Tampil Nama dan Kode Barang Otomatis **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("keydown", displayHarga) ;
  kodeBarang[i].addEventListener("keydown", displayHarga);

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
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      netto[i].value = "";
    }
    else {
      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas(angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100);
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    grandtotal.value = subtotal.value;
  });
} 

/** Tampil Diskon Rupiah Otomatis **/
for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("change", function (e) {
    if(e.target.value == "") {
      netPast = netto[i].value.replace(/\./g, "");
      netto[i].value = addCommas(+netto[i].value.replace(/\./g, "") + +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, netto[i].value.replace(/\./g, ""));
      diskonRp[i].value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      netPast = +netto[i].value.replace(/\./g, "")
      diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    grandtotal.value = subtotal.value;
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

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon = ((totDiskon - 100) * -1).toFixed(2);
  return totDiskon;
}

/** Hitung PPN Dan Total **/
function total_ppn(sub) {
  ppn.value = addCommas(sub * 10 / 100);
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

/** Inputan hanya bisa angka dan plus **/
function angkaPlus(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && charCode != 43  && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= diskon.length; i++) {
      if(inputan == i)
        $(diskon[inputan-1]).tooltip('show');
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
       subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      total_ppn(subtotal.value.replace(/\./g, ""));
    }

    for(let j = i; j < hapusBaris.length; j++) {
      if(j == hapusBaris.length - 1) {
        $(tablePO).find('tr:last-child').remove();  
      }
      else {
        netto[j].value = netto[j+1].value;
        diskonRp[j].value = diskonRp[j+1].value;
        diskon[j].value = diskon[j+1].value;
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