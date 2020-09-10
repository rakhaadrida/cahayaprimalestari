@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Penerimaan Barang</h1>
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
              <!-- Inputan Data Id, Tanggal, Supplier BM -->
               <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor BM</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control col-form-label-sm text-bold" name="kode" value="{{ $newcode }}" readonly>
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal BM</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control col-form-label-sm text-bold" name="tanggal" value="{{ $tanggal }}" >
                      </div>
                    </div>   
                  </div>
                </div>
                <div class="form-group row">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" name="namaSupplier" id="namaSupplier" placeholder=" Masukkan Nama Supplier" class="form-control col-form-label-sm text-bold" 
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->supplier->nama }}" readonly
                      @endif
                    />
                    <input type="hidden" name="kodeSupplier" id="kodeSupplier" 
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->id_supplier }}"
                      @endif
                    />
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->
              
              <!-- Inputan Detil BM -->
              <div class="form-row">
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Kode</label>
                  <input type="text" name="kodeBarang" id="kodeBarang" placeholder="Kd Brg" class="form-control form-control-sm text-bold">
                </div>
                <div class="form-group col-sm-3">
                  <label for="kode" class="col-form-label text-bold ">Nama Barang</label>
                  <input type="text" name="namaBarang" id="namaBarang" placeholder="Nama Barang" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Harga</label>
                  <input type="text" name="harga" id="harga" placeholder="Harga Satuan" class="form-control form-control-sm text-bold" readonly>
                </div>
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Qty</label>
                  <input type="text" name="pcs" id="qty" placeholder="Pcs" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Keterangan</label>
                  <input type="text" name="ket" id="ket" placeholder="Keterangan Barang" class="form-control form-control-sm">
                </div>
                <div class="form-group col-auto">
                  <label for="" class="col-form-label text-bold " ></label>
                  <button type="submit" formaction="{{ route('bm-create', $newcode) }}" formmethod="POST" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>          
              <hr>
              <!-- End Inputan Detil BM -->

              <!-- Tabel Data Detil BM-->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold">
                  <td>No</td>
                  <td style="width: 100px">Kode Barang</td>
                  <td>Nama Barang</td>
                  <td style="width: 160px">Harga</td>
                  <td style="width: 80px">Qty (Pcs)</td>
                  <td>Jumlah Harga</td>
                  <td>Keterangan</td>
                  <td>Edit</td>
                  <td>Delete</td>
                </thead>
                <tbody>
                  @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td align="center">{{ $item->barang->id }}</td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="right" class="autoharga">{{ $item->harga }}</td>
                        <td align="right" class="editable{{$i}}" id="editableQty{{$i}}">
                          {{ $item->qty }}
                        </td>
                        <td align="right" class="autototal">{{ $item->qty * $item->harga }}</td>
                        <td align="center">{{ $item->keterangan }}</td>
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
                          <a href="{{ route('bm-remove', ['bm' => $item->id_bm, 'barang' => $item->id_barang]) }}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=9 class="text-center text-bold h4 p-2"><i>Silahkan Input Detil Barang Masuk</i></td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('bm-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
const supplier = document.getElementById("namaSupplier");
const kodeSupp = document.getElementById("kodeSupplier");
const kodeBrg = document.getElementById("kodeBarang");
const namaBrg = document.getElementById("namaBarang");
const harga = document.getElementById("harga");

supplier.addEventListener('change', displaySupp);
kodeBrg.addEventListener('change', displayAll);
namaBrg.addEventListener('change', displayAll);

/** Tampil Id Supp **/
function displaySupp(e) {
  @foreach($supplier as $s)
    if('{{ $s->nama }}' == e.target.value) {
      kodeSupp.value = '{{ $s->id }}';
    }
  @endforeach
}

/** Tampil Data Barang **/
function displayAll(e) {
  @foreach($barang as $br)
    if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
      kodeBrg.value = '{{ $br->id }}';
      namaBrg.value = '{{ $br->nama }}';
    }
  @endforeach
  @foreach($harga as $hb)
    if(('{{ $hb->id_barang }}' == kodeBrg.value) && ('{{ $hb->id_harga }}' == 1)) {
      harga.value = '{{ $hb->harga }}';
    }
  @endforeach
}

/** Autocomplete Input Text **/
$(function() {
  var kode = [];
  var nama = [];
  @foreach($barang as $b)
    kode.push('{{ $b->id }}');
    nama.push('{{ $b->nama }}');
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

  /*-- Autocomplete Input Nama Barang --*/
  $(namaBarang).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(nama, extractLast(request.term)));
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

  /*-- Autocomplete Input Kode Barang --*/
  $(kodeBarang).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kode, extractLast(request.term)));
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

/* Tampil Data tanpa Refresh
$('#btn-cari').click(function(e) {
  e.preventDefault();
  $.ajax({
    url: '/barangmasuk/process',
    type: 'post',
    data: {kode: kode.value},
    dataType: 'json',
    success: function(data) {
      $.each(data, function() {
        $.each(this, function(index, value) {
          supplier.value = value.id;
          console.log(value);
        });
        
      });
    },
  })
})
*/

</script>
@endpush