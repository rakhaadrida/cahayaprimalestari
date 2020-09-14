@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Transfer Barang</h1>
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
                <div class="col-12">
                  <div class="form-group row">
                    <label for="kode" class="col-2 col-form-label text-bold ">Nomor TB</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2">
                      <input type="text" class="form-control col-form-label-sm text-bold" name="kode" value="{{ $newcode }}" readonly>
                    </div>
                    <label for="nama" class="col-auto col-form-label text-bold ">Tanggal TB</label>
                    <span class="col-form-label text-bold ml-3">:</span>
                    <div class="col-2">
                      <input type="text" class="form-control col-form-label-sm text-bold" name="tanggal" value="{{ $tanggal }}" >
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="kode" class="col-2 col-form-label text-bold ">Kode Barang</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2">
                      <input type="text" class="form-control col-form-label-sm text-bold" name="kodeBarang" id="kodeBarang"
                        @if($itemsRow != 0) 
                          value="{{ $items[$itemsRow-1]->barang->id }}"
                        @endif
                      >
                    </div>
                    <label for="nama" class="col-auto col-form-label text-bold ">Nama Barang</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-4">
                      <input type="text" class="form-control col-form-label-sm text-bold" name="namaBarang" id="namaBarang"
                        @if($itemsRow != 0) 
                          value="{{ $items[$itemsRow-1]->barang->nama }}"
                        @endif
                      >
                    </div>
                  </div>   
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->
              
              <!-- Inputan Detil BM -->
              <div class="form-row">
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Gudang Asal</label>
                  <input type="text" name="gudangAsal" id="gudangAsal" placeholder="Nama Gudang" class="form-control form-control-sm"
                    @if($itemsRow != 0) 
                      value="{{ $items[$itemsRow-1]->gudangAsal->nama }}"
                    @endif
                  >
                  <input type="hidden" name="kodeAsal" id="kodeAsal">
                </div>
                @php $stokSisa = 0 @endphp
                @if($itemsRow != 0) 
                  @foreach($stok as $s)
                    @if(($s->id_gudang == $items[$itemsRow-1]->id_asal) && ($s->id_barang == $items[$itemsRow-1]->id_barang))
                      @php $stokSisa = $s->stok; @endphp
                    @endif
                  @endforeach
                @endif
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Stok Asal</label>
                  <input type="text" name="qtyAsal" id="qtyAsal" placeholder="Qty" class="form-control form-control-sm" readonly
                    @if($itemsRow != 0) 
                      @foreach($items as $item)
                        @if(($item->id_asal == $items[$itemsRow-1]->id_asal) && ($item->id_barang == $items[$itemsRow-1]->id_barang))
                          value="{{ $stokSisa -= $item->qty }}"
                        @endif
                      @endforeach
                    @endif
                  >
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Gudang Tujuan</label>
                  <input type="text" name="gudangTujuan" id="gudangTujuan" placeholder="Nama Gudang" class="form-control form-control-sm">
                  <input type="hidden" name="kodeTujuan" id="kodeTujuan">
                </div>
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Stok</label>
                  <input type="text" name="qtyTujuan" id="qtyTujuan" placeholder="Qty" class="form-control form-control-sm" readonly>
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Jumlah Transfer</label>
                  <input type="text" name="qty" id="qty" placeholder="Qty Barang Transfer" class="form-control form-control-sm">
                </div>
                <div class="form-group col-auto">
                  <label for="" class="col-form-label text-bold " ></label>
                  <button type="submit" formaction="{{ route('tb-create', $newcode) }}" formmethod="POST" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>          
              <hr>
              <!-- End Inputan Detil BM -->

              <!-- Tabel Data Detil BM-->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold">
                  <td style="width: 40px">No</td>
                  <td style="width: 90px">Kode Barang</td>
                  <td>Nama Barang</td>
                  <td style="width: 150px">Gudang Asal</td>
                  <td style="width: 70px">Stok Asal</td>
                  <td style="width: 150px">Gudang Tujuan</td>
                  <td style="width: 70px">Stok Tujuan</td>
                  <td style="width: 90px">Qty Transfer</td>
                  <td style="width: 50px">Edit</td>
                  <td style="width: 50px">Delete</td>
                </thead>
                <tbody>
                  @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td align="center">{{ $item->barang->id }}</td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="right">{{ $item->gudangAsal->nama }}</td>
                        <td align="right">{{ $item->stok_asal }}</td>
                        <td align="right">{{ $item->gudangTujuan->nama }}</td>
                        <td align="right">{{ $item->stok_tujuan }}</td>
                        <td align="right">{{ $item->qty }}</td>
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
                          <a href="{{ route('tb-remove', ['id' => $item->id_tb, 'barang' => $item->id_barang, 'asal' => $item->id_asal, 'tujuan' => $item->id_tujuan]) }}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=10 class="text-center text-bold h4 p-2"><i>Silahkan Input Detil Transfer Barang</i></td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('tb-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
const kodeBrg = document.getElementById("kodeBarang");
const namaBrg = document.getElementById("namaBarang");
const gudangAsal = document.getElementById("gudangAsal");
const kodeAsal = document.getElementById("kodeAsal");
const gudangTujuan = document.getElementById("gudangTujuan");
const kodeTujuan = document.getElementById("kodeTujuan");
const qtyAsal = document.getElementById("qtyAsal");
const qtyTujuan = document.getElementById("qtyTujuan");

kodeBrg.addEventListener('change', displayAll);
namaBrg.addEventListener('change', displayAll);
gudangAsal.addEventListener('change', displayAsal);
gudangTujuan.addEventListener('change', displayTujuan);

/** Tampil Data Barang **/
function displayAll(e) {
  @foreach($barang as $br)
    if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
      kodeBrg.value = '{{ $br->id }}';
      namaBrg.value = '{{ $br->nama }}';
    }
  @endforeach
}

function displayAsal(e) {
  @foreach($stok as $s)
    if(('{{ $s->gudang->nama }}' == e.target.value) && ('{{ $s->id_barang }}' == kodeBrg.value)) {
      qtyAsal.value = '{{ $s->stok }}';
      kodeAsal.value = '{{ $s->id_gudang }}';
    }
  @endforeach
  if('{{ $itemsRow != 0 }}') {
    @foreach($items as $item)
      if(('{{ $item->id_asal }}' == kodeAsal.value) && ('{{ $item->id_barang }}' == kodeBrg.value)) {
        qtyAsal.value -= '{{ $item->qty }}';
      }
    @endforeach
  }
}

function displayTujuan(e) {
  @foreach($stok as $s)
    if(('{{ $s->gudang->nama }}' == e.target.value) && ('{{ $s->id_barang }}' == kodeBrg.value)) {
      qtyTujuan.value = '{{ $s->stok }}';
      kodeTujuan.value = '{{ $s->id_gudang }}';
    }
  @endforeach
}

/** Autocomplete Input Text **/
$(function() {
  var barangKode = [];
  var barangNama = [];
  var gudang = [];
  @foreach($barang as $b)
    barangKode.push('{{ $b->id }}');
    barangNama.push('{{ $b->nama }}');
  @endforeach
  @foreach($gudang as $g)
    gudang.push('{{ $g->nama }}');
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

  /*-- Autocomplete Input Gudang Asal --*/
  $(gudangAsal).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(gudang, extractLast(request.term)));
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

  /*-- Autocomplete Input Gudang Tujuan --*/
  $(gudangTujuan).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(gudang, extractLast(request.term)));
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