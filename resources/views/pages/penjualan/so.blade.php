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
                      {{-- <div class="col-1"></div> --}}
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
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="tempo">
                      </div>
                      <span class="col-form-label text-bold input-right">hari</span>
                    </div>
                    <div class="form-group row total-po">
                      <label for="waktuTagih" class="col-6 col-form-label text-bold">Waktu Penagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="waktuTagih">
                      </div>
                      <span class="col-form-label text-bold">hari</span>
                    </div>
                    <div class="form-group row total-po">
                      <label for="grandtotal" class="col-6 col-form-label text-bold">Diskon Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="diskon">
                      </div>
                      <span class="col-form-label text-bold">%</span>
                    </div>
                    <div class="form-group row total-po">
                      <label for="pkp" class="col-6 col-form-label text-bold">PKP</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3 pkp-check">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="radio" name="pkp"  value="1">
                          <label class="form-check-label text-bold" for="pkp1">Ya</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="pkp"  value="0">
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
                        value="{{ $items[0]->supplier->nama }}" readonly
                      @endif
                    />
                    <input type="hidden" name="kodeCustomer" id="idCustomer" 
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->id_supplier }}"
                      @endif
                    />
                  </div>
                </div>
                <div class="form-group row sales-row">
                  <label for="alamat" class="col-2 col-form-label text-bold">Nama Sales</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-3">
                    <input type="text" name="namaSales" id="namaSales" placeholder="Nama Sales" class="form-control form-control-sm mt-1"
                      {{-- @if($itemsRow != 0) 
                        value="{{ $items[0]->supplier->nama }}" readonly
                      @endif --}}
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
                    <input type="date" name="tanggalKirim" placeholder="DD-MM-YYYY" class="form-control form-control-sm mt-1"/>
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
                <div class="form-group col-sm-3">
                  <label for="kode" class="col-form-label text-bold ">Nama Barang</label>
                  <input type="text" name="namaBarang" id="namaBarang" placeholder="Nama Barang" class="form-control form-control-sm">
                  <input type="hidden" name="kodeBarang" id="idBarang" />
                </div>
                {{-- <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Pack</label>
                  <input type="text" name="pack" placeholder="Qty(Pack)" class="form-control form-control-sm">
                </div> --}}
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Pcs</label>
                  <input type="text" name="pcs" id="qty" placeholder="Qty (Pcs)" class="form-control form-control-sm">
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
                  <button type="submit" formaction="" formmethod="POST" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>          
              <hr>
              <!-- End Inputan Detil PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold">
                  <td>No</td>
                  <td>Nama Barang</td>
                  {{-- <td>Pack</td> --}}
                  <td>Qty (Pcs)</td>
                  <td>Harga</td>
                  <td>Diskon</td>
                  <td>Jumlah</td>
                  <td>Ubah</td>
                  <td>Hapus</td>
                </thead>
                <tbody>
                  @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td class="editable{{$i}}" id="editableBarang{{$i}}">
                          {{ $item->barang->nama }}
                        </td>
                        {{-- <td></td> --}}
                        <td align="right" class="editable{{$i}}" id="editableQty{{$i}}">
                          {{ $item->qty }}
                        </td>
                        <td align="right" class="autoharga">{{ $item->harga }}</td>
                        <td align="right" class="autototal">{{ $item->qty * $item->harga }}</td>
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
                          <a href="{{ route('po-remove', ['po' => $item->id_po, 'barang' => $item->id_barang]) }}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=8 class="text-center">Belum ada Detail SO</td>
                    </tr>
                  @endif 
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
const kodeCust = document.getElementById('idCustomer');
const namaBrg = document.getElementById('namaBarang');
const kodeBarang = document.getElementById('idBarang');
const harga = document.getElementById('harga');
const kodeHarga = document.getElementById('idHarga');
const jumlah = document.getElementById('jumlah');
const qty = document.getElementById('qty');

/** Call Fungsi Setelah Inputan Terisi **/
namaCust.addEventListener('change', displayCust);
namaBrg.addEventListener('change', displayHarga);
qty.addEventListener('change', displayJumlah);

/** Tampil Id Supplier **/
function displayCust(e) {
  var idCust;
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      idCust = '{{ $c->id }}';
    }
  @endforeach
  
  kodeCust.value = idCust;
}

/** Tampil Harga Otomatis **/
function displayHarga(e) {
  var idBarang;
  @foreach($barang as $br)
    if('{{ $br->nama }}' == e.target.value) {
      idBarang = '{{ $br->id }}';
    }
  @endforeach
  @foreach($harga as $hb)
    if(('{{ $hb->id_barang }}' == idBarang) && ('{{ $hb->id_harga }}' == 3)) {
      var hargaBeli = '{{ $hb->harga }}';
      var idHarga = '{{ $hb->id_harga }}';
    }
  @endforeach
  
  kodeBarang.value = idBarang;
  kodeHarga.value = idHarga;
  harga.value = hargaBeli;
  jumlah.value = hargaBeli;
}

/** Tampil Jumlah Harga Otomatis **/
function displayJumlah(e) {
  jumlah.value = e.target.value * harga.value;
} 

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
  var barang = [];
  @foreach($barang as $b)
    barang.push('{{ $b->nama }}');
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

  /*-- Autocomplete Input Barang --*/
  $(namaBrg).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(barang, extractLast(request.term)));
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
  $(namaCustomer).on("keydown", function(event) {
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