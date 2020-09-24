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
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold" name="kode" value="{{ $items[0]->id_so }}">
                      </div>
                    </div>  
                  </div>
                  <div class="col" style="margin-left: -380px">
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right text-dark">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="tglSO" 
                        value="{{ $items[0]->so->tgl_so }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaCust" class="col-5 col-form-label text-bold text-right text-dark">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaCust"
                        value="{{ $items[0]->so->customer->nama }}">
                        <input type="hidden" name="kodeCust" 
                        value="{{ $items[0]->so->id_customer }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSales" class="col-5 col-form-label text-bold text-right text-dark">Nama Sales</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaSales"
                        value="{{ $items[0]->so->customer->sales->nama }}">
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
                    <input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm mt-1">
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
                  <td style="width: 30px">No</td>
                  <td style="width: 90px">Kode</td>
                  <td style="width: 260px">Nama Barang</td>
                  <td style="width: 60px">Qty</td>
                  <td>Harga</td>
                  <td>Jumlah</td>
                  <td colspan="2">Diskon</td>
                  <td style="width: 120px">Netto (Rp)</td>
                  <td style="width: 40px">Hapus</td>
                </thead>
                <tbody id="tablePO">
                  @php 
                    $i = 1; $subtotal = 0;
                  @endphp
                  @foreach($items as $item)
                    <tr class="text-bold" id="{{ $i }}">
                      <td align="center" class="no">{{ $i }}</td>
                      <td>
                        <input type="text" name="kodeBarang[]" class="form-control form-control-sm text-bold kodeBarang" value="{{ $item->id_barang }}">
                      </td>
                      <td>
                        <input type="text" name="namaBarang[]" class="form-control form-control-sm text-bold namaBarang" value="{{ $item->barang->nama }}">
                      </td>
                      <td> 
                        <input type="text" name="qty[]" class="form-control form-control-sm text-bold qty" value="{{ $item->qty }}">
                      </td>
                      <td align="right">
                        <input type="text" name="harga[]" class="form-control form-control-sm text-bold harga" value="{{ $item->harga }}" readonly>
                      </td>
                      <td align="right" class="total">{{ $item->qty * $item->harga }}</td>
                      <td align="right" style="width: 90px">
                        <input type="text" name="diskon[]" class="form-control form-control-sm text-bold text-right diskon" 
                        value="{{ $item->diskon }}" >
                      </td>
                      <td align="right" style="width: 140px" class="diskonRp">
                        {{ (($item->qty * $item->harga) * $item->diskon) / 100 }}
                      </td>
                      <td align="right" class="netto">
                        {{ ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100) }}
                      </td>
                      <td align="center">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                      @php $subtotal += ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100); 
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

              <div class="form-group row justify-content-end subtotal-so">
                <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2 mr-1">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ $subtotal }}" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2 mr-1">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ $subtotal * 10 / 100 }}" />
                </div>
              </div>
              <div class="form-group row justify-content-end grandtotal-so">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2 mr-1">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" 
                  value="{{ $subtotal + ($subtotal * 10 / 100) }}" />
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
const no = document.querySelectorAll('.no');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const harga = document.querySelectorAll(".harga");
const total = document.querySelectorAll(".total");
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
  brgNama[i].addEventListener("change", function (e) {
    @foreach($barang as $br)
      if('{{ $br->nama }}' == e.target.value) {
        kodeBarang[i].value = '{{ $br->id }}';
      }
    @endforeach
    displayHarga(kodeBarang[i].value);
  });

  kodeBarang[i].addEventListener("change", function (e) {
    @foreach($barang as $br)
      if('{{ $br->id }}' == e.target.value) {
        brgNama[i].value = '{{ $br->nama }}';
      }
    @endforeach
    displayHarga(e.target.value);
  });

  function displayHarga(kode) {
    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kode) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        harga[i].value = '{{ $hb->harga }}';
        var nettoAwal = netto[i].innerHTML;
        total[i].innerHTML = '{{ $hb->harga }}' * +qty[i].value;
        diskonRp[i].innerHTML = (+total[i].innerHTML * +diskon[i].value) / 100;
        netto[i].innerHTML = +total[i].innerHTML - +diskonRp[i].innerHTML;
        if(netto[i].innerHTML > +nettoAwal) 
          subtotal.value = +subtotal.value + (+netto[i].innerHTML - +nettoAwal);
        else
          subtotal.value = +subtotal.value - (+nettoAwal - +netto[i].innerHTML);
        total_ppn(subtotal.value);
      }
    @endforeach
  }
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("change", function (e) {
    total[i].innerHTML = e.target.value * harga[i].value;
    var nettoAwal = netto[i].innerHTML;
    diskonRp[i].innerHTML = (+total[i].innerHTML * +diskon[i].value) / 100;
    netto[i].innerHTML = +total[i].innerHTML - +diskonRp[i].innerHTML;
    if(netto[i].innerHTML > +nettoAwal) 
      subtotal.value = +subtotal.value + (+netto[i].innerHTML - +nettoAwal);
    else
      subtotal.value = +subtotal.value - (+nettoAwal - +netto[i].innerHTML);
    total_ppn(subtotal.value);
  });
}

/** Hitung PPN Dan Total **/
function total_ppn(sub) {
  ppn.value = sub * 10 / 100;
  grandtotal.value = +sub + +ppn.value;
}

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    if(qty[i].value != "") {
      subtotal.value = +subtotal.value - +netto[i].innerHTML;
      total_ppn(subtotal.value);
    }
    const newRow = document.getElementById(i+1);
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $(tablePO).find('tr:last').attr("id");
    $(newRow).remove();
    if(curNum < lastNum) {
      for(let i = curNum; i < lastNum; i++) {
        $(tablePO).find('tr:nth-child('+i+') td:first-child').html(i);
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