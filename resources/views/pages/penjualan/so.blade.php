@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Sales Order</h1>
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
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="kode" value="{{ $newcode }}" readonly>
                      </div>
                      <label for="tanggal" class="col-auto col-form-label text-bold ">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="tanggal" value="{{ $tanggal }}" readonly>
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -320px">
                    <div class="form-group row subtotal-po">
                      <label for="tempo" class="col-6 col-form-label text-bold">Jatuh Tempo</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="tempo"
                          @if($itemsRow != 0) 
                            value="{{ $items[$itemsRow - 1]->tempo }}"
                          @endif
                        >
                      </div>
                      <span class="col-form-label text-bold input-right">hari</span>
                    </div>
                    {{-- <div class="form-group row total-po">
                      <label for="waktuTagih" class="col-6 col-form-label text-bold">Waktu Penagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="waktuTagih">
                      </div>
                      <span class="col-form-label text-bold">hari</span>
                    </div>
                    <div class="form-group row total-po">
                      <label for="diskonFaktur" class="col-6 col-form-label text-bold">Diskon Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="diskonFaktur" id="diskonFaktur"
                          @if($itemsRow != 0) 
                            value="{{ $items[0]->diskon_faktur }}"
                          @else 
                            value="0"
                          @endif
                        />
                      </div>
                      <span class="col-form-label text-bold">%</span>
                    </div> --}}
                    <div class="form-group row total-po">
                      <label for="pkp" class="col-6 col-form-label text-bold">PKP</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3 pkp-check">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="radio" name="pkp"  value="1"
                            @if($itemsRow != 0) 
                              @if($items[$itemsRow - 1]->pkp == 1)
                                checked
                              @endif
                            @endif
                          >
                          <label class="form-check-label text-bold" for="pkp1">Ya</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="pkp"  value="0"
                            @if($itemsRow != 0) 
                              @if($items[$itemsRow - 1]->pkp == 0)
                                checked
                              @endif
                            @endif
                          >
                          <label class="form-check-label text-bold" for="pkp2">Tidak</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row customer-row">
                  <label for="customer" class="col-2 col-form-label text-bold">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-3">
                    <input type="text" name="namaCustomer" id="namaCustomer" placeholder="Nama Customer" class="form-control form-control-sm mt-1"
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->customer->nama }}" readonly
                      @endif
                    />
                    <input type="hidden" name="kodeCustomer" id="idCustomer" 
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->id_customer }}"
                      @endif
                    />
                  </div>
                </div>
                <div class="form-group row sales-row">
                  <label for="alamat" class="col-2 col-form-label text-bold">Nama Sales</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-3">
                    <input type="text" name="namaSales" id="namaSales" placeholder="Nama Sales" class="form-control form-control-sm mt-1" readonly
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->customer->sales->nama }}"
                      @endif
                    />
                    <input type="hidden" name="kodeSales" id="idSales" 
                      {{-- @if($itemsRow != 0) 
                        value="{{ $items[0]->id_supplier }}"
                      @endif --}}
                    />
                  </div>
                </div>
                <div class="form-group row sales-row">
                  <label for="tglKirim" class="col-2 col-form-label text-bold">Tanggal Kirim</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" name="tanggalKirim" placeholder="DD-MM-YYYY" class="form-control form-control-sm mt-1"
                      @if($itemsRow != 0) 
                        value="{{ $items[$itemsRow - 1]->tgl_kirim }}"
                      @endif
                    />
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
            
              {{-- <div class="form-group row">
                <label for="keterangan" class="col-1 col-form-label text-bold">Keterangan</label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm ml-1" name="keterangan" placeholder="Keterangan" 
                    value="{{ old('keterangan') }}">
                </div>
              </div> --}}

              <!-- Inputan Detil PO -->
              <div class="form-row">
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Kode</label>
                  <input type="text" name="kodeBarang" id="kodeBarang" placeholder="Kd Brg" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-3">
                  <label for="kode" class="col-form-label text-bold ">Nama Barang</label>
                  <input type="text" name="namaBarang" id="namaBarang" placeholder="Nama Barang" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Pcs</label>
                  <input type="text" name="pcs" id="qty" placeholder="Qty (Pcs)" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Diskon</label>
                  <input type="text" name="diskon" id="diskon" placeholder="Diskon" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Harga</label>
                  <input type="text" name="harga" id="harga" placeholder="Harga Satuan" class="form-control form-control-sm text-bold" readonly>
                  <input type="hidden" name="kodeHarga" id="idHarga" />
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Jumlah</label>
                  <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah Harga" class="form-control form-control-sm text-bold" readonly>
                </div>
                <div class="form-group col-auto">
                  <label for="" class="col-form-label text-bold " ></label>
                  <button type="submit" formaction="{{ route('so-create', $newcode) }}" formmethod="POST" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>          
              <hr>
              <!-- End Inputan Detil PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold text-dark">
                  <td style="width: 30px">No</td>
                  <td style="width: 80px">Kode</td>
                  <td>Nama Barang</td>
                  <td style="width: 50px">Qty</td>
                  <td>Harga</td>
                  <td>Jumlah</td>
                  <td style="width: 80px">Diskon(%)</td>
                  <td style="width: 110px">Diskon(Rp)</td>
                  <td style="width: 120px">Netto (Rp)</td>
                  <td style="width: 50px">Ubah</td>
                  <td style="width: 50px">Hapus</td>
                </thead>
                <tbody>
                  @if($itemsRow != 0)
                    @php $i = 1; $subtotal = 0; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td align="center">{{ $item->barang->id }} </td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="right" class="editable{{$i}}" id="editableQty{{$i}}">
                          {{ $item->qty }}
                        </td>
                        <td align="right" class="autoharga">{{ $item->harga }}</td>
                        <td align="right" class="autoharga">{{ $item->harga * $item->qty }}</td>
                        <td align="right" class="autodiskon">{{ $item->diskon }} %</td>
                        @php 
                          $total = $item->qty * $item->harga;
                          $besarDiskon = $item->diskon * $total / 100;
                          $total -= $besarDiskon;
                          $subtotal += $total;
                        @endphp
                        <td align="right" class="autodiskon">{{ $besarDiskon }}</td>
                        <td align="right" class="autototal">
                          {{ $total }}
                          <input type="hidden" id="totalBarang" value="{{ $total }}">
                        </td>
                        <td align="center">
                          <a href="" id="editButton{{$i}}" 
                          onclick="return displayEditable({{$i}})">
                            <i class="fas fa-fw fa-edit fa-lg ic-edit mt-1"></i>
                          </a>
                          <a href="" id="updateButton{{$i}}" class="ic-update" 
                          onclick="return processEditable({{$i}})">
                            <i class="fas fa-fw fa-save fa-lg mt-1"></i>
                          </a>
                        </td>
                        <td align="center">
                          <a href="{{ route('so-remove', ['id' => $item->id_so, 'barang' => $item->id_barang]) }}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=10 class="text-center">Belum ada Detail SO</td>
                    </tr>
                  @endif 
                </tbody>
              </table>
              {{-- <div class="form-group row justify-content-end subtotal-so">
                <label for="subTotal" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger" 
                  @if($itemsRow != 0) 
                    value="{{ $subtotal }}"
                  @endif
                  />
                </div>
              </div> --}}
              @if($itemsRow != 0) 
                @php
                  $diskonFaktur = ($items[0]->diskon_faktur * $subtotal) / 100;
                  $totalNotPPN = $subtotal - $diskonFaktur;
                  $ppn = $totalNotPPN * 10 / 100;
                  $grandtotal = $totalNotPPN + $ppn;
                @endphp
              @endif
              {{-- <div class="form-group row justify-content-end total-so">
                <label for="diskonFaktur" class="col-2 col-form-label text-bold text-right text-dark">Diskon Faktur</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" name="angkaDF" id="angkaDF" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger"
                  @if($itemsRow != 0) 
                    value="{{ $diskonFaktur }}"
                  @endif
                  />
                </div>
              </div> --}}
              <div class="form-group row justify-content-end subtotal-so">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger"
                  @if($itemsRow != 0) 
                    value="{{ $totalNotPPN }}"
                  @endif
                  />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger" 
                  @if($itemsRow != 0) 
                    value="{{ $ppn }}"
                  @endif
                  />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg" 
                  @if($itemsRow != 0) 
                    value="{{ $grandtotal }}"
                  @endif
                  />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('so-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
<!-- /.container-fluid -->
@endsection

@push('addon-script')
<script type="text/javascript">
const namaCust = document.getElementById('namaCustomer');
const namaSales = document.getElementById('namaSales');
const kodeCust = document.getElementById('idCustomer');
const namaBrg = document.getElementById('namaBarang');
const kodeBrg = document.getElementById('kodeBarang');
const harga = document.getElementById('harga');
const kodeHarga = document.getElementById('idHarga');
const jumlah = document.getElementById('jumlah');
const qty = document.getElementById('qty');
const diskon = document.getElementById('diskon');
// const diskonFaktur = document.getElementById('diskonFaktur');
const angkaDF = document.getElementById('angkaDF');
const subTotal = document.getElementById('subtotal');
const totalNotPpn = document.getElementById('totalNotPPN');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');

/** Call Fungsi Setelah Inputan Terisi **/
namaCust.addEventListener('change', displayCust);
namaBrg.addEventListener('change', displayHarga);
kodeBrg.addEventListener('change', displayHarga);
qty.addEventListener('change', displayJumlah);
diskon.addEventListener('change', displayTotal);
// diskonFaktur.addEventListener('change', displayDiskon);

/** Tampil Id Supplier **/
function displayCust(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
      namaSales.value = '{{ $c->sales->nama }}';
    }
  @endforeach
}

/** Tampil Harga Otomatis **/
function displayHarga(e) {
  @foreach($barang as $br)
    if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
      kodeBrg.value = '{{ $br->id }}';
      namaBrg.value = '{{ $br->nama }}';
    }
  @endforeach
  @foreach($harga as $hb)
    if(('{{ $hb->id_barang }}' == kodeBrg.value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
      harga.value = '{{ $hb->harga_ppn }}';
      kodeHarga.value = '{{ $hb->id_harga }}';
      jumlah.value = harga.value;
    }
  @endforeach  
}

/** Tampil Jumlah Harga Otomatis **/
function displayJumlah(e) {
  jumlah.value = e.target.value * harga.value;
} 

function displayTotal(e) {
  let totalHarga = qty.value * harga.value;
  let besarDiskon = (e.target.value * totalHarga) / 100;
  jumlah.value = totalHarga - besarDiskon;
} 

// function displayDiskon(e) {
//   angkaDF.value = (e.target.value * subTotal.value) / 100;
//   totalNotPpn.value = subTotal.value - angkaDF.value;
//   ppn.value = totalNotPpn.value * 10 / 100;
//   grandtotal.value = +totalNotPpn.value + +ppn.value;
// } 

/** Tampil Table Column Editable **/
function displayEditable(no) {
  document.getElementById("editButton"+no).style.display = "none";
  document.getElementById("updateButton"+no).style.display = "block";
  let row = document.querySelectorAll(".editable"+no);

  row.forEach(function(e) {
    e.contentEditable = true;
    e.style.backgroundColor = "lightgrey";
    e.style.color = "black";
  })

  return false;
}

/* function processEditable(no) {
  let editableBarang = document.getElementById("editableBarang"+no);
  let editableQty = document.getElementById("editableQty"+no);
  const itemsEdit = [];

  itemsEdit.push({
    barang: editableBarang.value;
    qty: editableQty.value;
  })  
  
  $.ajax({
    url: '/po/update',
    type: 'post',
    data: itemsEdit
  });
} */

/** Autocomplete Input Text **/
$(function() {
  var barangKode = [];
  var barangNama = [];
  @foreach($barang as $b)
    barangKode.push('{{ $b->id }}');
    barangNama.push('{{ $b->nama }}');
  @endforeach

  var customer = [];
  @foreach($customer as $c)
    customer.push('{{ $c->nama }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Kode Barang --*/
  $(kodeBrg).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(barangKode, extractLast(request.term)));
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

  /*-- Autocomplete Input Nama Barang --*/
  $(namaBrg).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(barangNama, extractLast(request.term)));
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

  /*-- Autocomplete Input Customer --*/
  $(namaCust).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(customer, extractLast(request.term)));
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