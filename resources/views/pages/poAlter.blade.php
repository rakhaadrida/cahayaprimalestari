@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Purchase Order</h1>
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
              <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor PO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control col-form-label-sm text-bold" name="kode" value="{{ $newcode }}" readonly>
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal PO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control col-form-label-sm text-bold" name="tanggal" value="{{ $tanggal }}" readonly>
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -320px">
                    <div class="form-group row subtotal-po">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Sub Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="subtotal" id="subtotal">
                      </div>
                    </div>
                    <div class="form-group row total-po">
                      <label for="ppn" class="col-5 col-form-label text-bold ">PPN</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="ppn" id="ppn">
                      </div>
                    </div>
                    <div class="form-group row total-po">
                      <label for="grandtotal" class="col-5 col-form-label text-bold ">Grand Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" class="form-control col-form-label-sm text-bold grand-total" name="grandtotal" id="grandtotal" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row supplier-row">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" name="namaSupplier" id="namaSupplier" placeholder="Nama Supplier" class="form-control form-control-sm"
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->supplier->nama }}" readonly
                      @endif
                    />
                    <input type="hidden" name="kodeSupplier" id="idSupplier" 
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->id_supplier }}"
                      @endif
                    />
                  </div>
                </div>
              </div>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              {{-- <div class="form-group row">
                <label for="keterangan" class="col-1 col-form-label text-bold">Keterangan</label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm ml-1" name="keterangan" placeholder="Keterangan" 
                    value="{{ old('keterangan') }}">
                </div>
              </div> --}}
              <hr>

              <!-- Inputan Detil PO -->
              {{-- <div class="form-row">
                <div class="form-group col-sm-3">
                  <label for="kode" class="col-form-label text-bold ">Nama Barang</label>
                  <input type="text" name="namaBarang" id="namaBarang" placeholder="Nama Barang" class="form-control form-control-sm">
                  <input type="hidden" name="kodeBarang" id="idBarang" />
                </div>
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
                  <button type="submit" formaction="{{ route('po-create', $newcode) }}" formmethod="POST" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>           
              <hr> --}}
              <!-- End Inputan Detil PO -->
              
              <!-- Tabel Data Detil PO -->
                <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                  Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
                </span>
                <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                  <thead class="text-center text-bold text-dark">
                    <td style="width: 40px">No</td>
                    <td style="width: 380px">Nama Barang</td>
                    <td style="width: 80px">Qty (Pcs)</td>
                    <td>Harga</td>
                    <td>Jumlah</td>
                    {{-- <td>Ubah</td> --}}
                    <td style="width: 50px">Hapus</td> 
                  </thead>
                  <tbody>
                    @for($i=1; $i<=5; $i++)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td >
                          <input type="text" name="namaBarang[]" id="namaBarang" placeholder="Masukkan Nama" class="form-control form-control-sm text-bold namaBarang">
                          <input type="hidden" name="kodeBarang[]" class="kodeBarang">
                        </td>
                        <td> 
                          <input type="text" name="qty[]" id="qty" class="form-control form-control-sm text-bold qty" placeholder="Qty PO">
                        </td>
                        <td>
                          <input type="text" name="harga[]" id="harga" class="form-control form-control-sm text-bold text-right harga" placeholder="Harga Satuan" readonly >
                        </td>
                        <td>
                          <input type="text" name="jumlah[]" id="jumlah" class="form-control form-control-sm text-bold text-right jumlah" placeholder="Total Harga" readonly>
                        </td>
                        <td align="center">
                          <a href="#" class="icRemove">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                    @endfor
                    {{-- <tr class="hide text-bold">
                      <td align="center">{{ $i }}</td>
                      <td>
                        <input type="text" name="namaBarang[]" id="namaBarang" placeholder="Masukkan Nama" class="form-control form-control-sm text-bold namaBarang">
                        <input type="hidden" name="kodeBarang[]" class="kodeBarang">
                      </td>
                      <td> 
                        <input type="text" name="qty[]" id="qty" class="form-control form-control-sm text-bold qty" placeholder="Qty PO">
                      </td>
                      <td>
                        <input type="text" name="harga[]" id="harga" class="form-control form-control-sm text-bold text-right harga" placeholder="Harga Satuan" readonly >
                      </td>
                      <td>
                        <input type="text" name="jumlah[]" id="jumlah" class="form-control form-control-sm text-bold text-right jumlah" placeholder="Total Harga" readonly>
                      </td>
                      <td align="center">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                    </tr> --}}
                    {{-- @if($itemsRow != 0)
                      @php $i = 1; @endphp
                      @foreach($items as $item)
                        <tr class="text-bold">
                          <td align="center">{{ $i }}</td>
                          <td class="editable{{$i}}" id="editableBarang{{$i}}">
                            {{ $item->barang->nama }}
                          </td>
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
                        <td colspan=7 class="text-center text-bold h4 p-2"><i>Belum ada Detail PO</i></td>
                      </tr>
                    @endif  --}}
                  </tbody>
                </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('po-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
const namaSup = document.getElementById('namaSupplier');
const kodeSup = document.getElementById('idSupplier');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const subtotal = document.getElementById('subtotal');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
const hapusBaris = document.querySelectorAll(".icRemove");
const newRow = document.getElementsByClassName('table-add')[0];

/** Call Fungsi Setelah Inputan Terisi **/
namaSup.addEventListener('change', displaySupp);
newRow.addEventListener("click", displayRow);

function displayRow(e) {
  const clone = $(tablePO).find('tbody tr').last().clone(true).removeClass('hide');
  $(tablePO).append(clone);
}

/** Tampil Id Supplier **/
function displaySupp(e) {
  var idSupp;
  @foreach($supplier as $s)
    if('{{ $s->nama }}' == e.target.value) {
      idSupp = '{{ $s->id }}';
    }
  @endforeach
  
  kodeSup.value = idSupp;
}

/** Tampil Harga Barang **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("change", function (e) {
    @foreach($barang as $br)
      if('{{ $br->nama }}' == e.target.value) {
        kodeBarang[i].value = '{{ $br->id }}';
      }
    @endforeach
    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        harga[i].value = '{{ $hb->harga }}';
        jumlah[i].value = '{{ $hb->harga }}';
      }
    @endforeach
  });
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("change", function (e) {
    jumlah[i].value = e.target.value * harga[i].value;
    subtotal.value = +subtotal.value + +jumlah[i].value;
  });
} 

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    subtotal.value = +subtotal.value - +jumlah[i].value;
    jumlah[i].value = jumlah[i+1].value;
    harga[i].value = harga[i+1].value;
    qty[i].value = qty[i+1].value;
    brgNama[i].value = brgNama[i+1].value;
    jumlah[i+1].value = "";
    harga[i+1].value = "";
    qty[i+1].value = "";
    brgNama[i+1].value = "";
  });
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

/** Autocomplete Input Text **/
$(function() {
  var barang = [];
  @foreach($barang as $b)
    barang.push('{{ $b->nama }}');
  @endforeach

  var supplier = [];
  @foreach($supplier as $s)
    supplier.push('{{ $s->nama }}');
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
  }

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