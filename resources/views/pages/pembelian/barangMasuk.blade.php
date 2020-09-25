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
                      <div class="col-2 mt-1">
                        <input type="text" class="form-control form-control-sm text-bold" name="kode" value="{{ $newcode }}" readonly>
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal BM</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" class="form-control form-control-sm text-bold" name="tanggal" value="{{ $tanggal }}" >
                      </div>
                    </div>   
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Gudang</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" name="namaGudang" id="namaGudang" class="form-control form-control-sm text-bold" value="Johar Baru" readonly
                    />
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" name="namaSupplier" id="namaSupplier" placeholder=" Masukkan Nama Supplier" class="form-control form-control-sm text-bold" required
                      @if($itemsRow != 0) 
                        value="{{ $items[$itemsRow - 1]->supplier->nama }}" readonly
                      @endif
                    />
                    <input type="hidden" name="kodeSupplier" id="kodeSupplier" 
                      @if($itemsRow != 0) 
                        value="{{ $items[$itemsRow - 1]->id_supplier }}"
                      @endif
                    />
                  </div>
                  <input type="hidden" name="jumBaris" id="jumBaris" value="5">

                  <!-- Button Reset Supplier -->
                  {{-- @if($itemsRow != 0)
                    <div class="col-auto mt-1" style="margin-left: -15px">
                      <button type="submit" onclick="return resetSupplier()" 
                      id="resetSupp" class="btn btn-info btn-sm btn-block text-bold form-control form-control-sm">Reset</button>
                    </div>
                  @endif --}}

                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->
              
              <!-- Inputan Detil BM -->
              {{-- <div class="form-row">
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
              <hr> --}}
              <!-- End Inputan Detil BM -->

              <!-- Tabel Data Detil BM-->
              <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
              </span>
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold">
                  <td style="width: 40px">No</td>
                  <td style="width: 100px">Kode Barang</td>
                  <td>Nama Barang</td>
                  <td style="width: 80px">Qty (Pcs)</td>
                  <td style="width: 90px">Harga</td>
                  <td style="width: 110px">Jumlah Harga</td>
                  <td style="width: 250px">Keterangan</td>
                  <td style="width: 50px" id="deleteHead">Delete</td>
                </thead>
                <tbody id="tablePO">
                  @for($i=1; $i<=5; $i++)
                    <tr class="text-bold text-dark" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" name="kodeBarang[]" id="kodeBarang" class="form-control form-control-sm text-bold text-dark kodeBarang"
                        value="{{ old('kodeBarang[]') }}" @if($i == 1) required @endif >
                      </td>
                      <td>
                        <input type="text" name="namaBarang[]" id="namaBarang" class="form-control form-control-sm text-bold text-dark namaBarang"
                        value="{{ old('namaBarang[]') }}" @if($i == 1) required @endif>
                      </td>
                      <td> 
                        <input type="text" name="qty[]" id="qty" class="form-control form-control-sm text-bold text-dark qty" value="{{ old('qty[]') }}">
                      </td>
                      <td>
                        <input type="text" name="harga[]" id="harga" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" value="{{ old('harga[]') }}">
                      </td>
                      <td>
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ old('jumlah[]') }}" >
                      </td>
                      <td>
                        <input type="text" name="keterangan[]" id="keterangan" class="form-control form-control-sm text-bold text-dark keterangan" value="{{ old('keterangan[]') }}" >
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                    </tr>
                  @endfor

                  <!-- Tabel Tampil Detil BM (Bukan Diinput di Tabel) -->
                  {{-- @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold barisBM">
                        <td align="center">{{ $i }}</td>
                        <td align="center">{{ $item->barang->id }}</td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="right">{{ $item->harga }}</td>
                        <td align="right" class="editQty{{$i}}" id="editableQty{{$i}}">
                          {{ $item->qty }}
                        </td>
                        <td align="right">{{ $item->qty * $item->harga }}</td>
                        <td align="center" class="editKet{{$i}}" id="editableKet{{$i}}">
                          {{ $item->keterangan }}
                        </td>
                        <td align="center">
                          <a href="" id="editButton{{$i}}" 
                          onclick="return displayEditable({{$i}})">
                            <i class="fas fa-fw fa-edit fa-lg ic-edit mt-1"></i>
                          </a>
                          <button type="submit" formaction="{{ route('bm-update', ['bm' => $item->id_bm, 'barang' => $item->id_barang, 'id' => $i]) }}" formmethod="POST"
                          id="updateButton{{$i}}" class=" btn btn-md ic-update">
                            <i class="fas fa-fw fa-save fa-lg mt-1"></i>
                          </button>
                        </td>
                        <td align="center">
                          <a href="{{ route('bm-remove', ['bm' => $item->id_bm, 'barang' => $item->id_barang]) }}" id="removeButton{{$i}}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                          <a href="" id="cancelButton{{$i}}" class="ic-cancel" 
                          onclick="return cancelEditable({{$i}})">
                            <i class="fas fa-fw fa-history fa-lg mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=9 class="text-center text-bold h4 p-2"><i>Silahkan Input Detil Barang Masuk</i></td>
                    </tr>
                  @endif --}}

                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('bm-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</button>
                  {{-- id="submitBM" onclick="return checkEditable()" --}}
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-danger btn-block text-bold">Reset All </button> 
                  {{-- formaction="{{ route('bm-reset', $newcode) }}" formmethod="GET" --}}
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
const namaSup = document.getElementById('namaSupplier');
const kodeSup = document.getElementById('kodeSupplier');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const keterangan = document.querySelectorAll(".keterangan");
const hapusBaris = document.querySelectorAll(".icRemove");
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');

namaSup.addEventListener('change', displaySupp);
newRow.addEventListener('click', displayRow);

/** Add New Table Line **/
function displayRow(e) {
  const lastRow = $(tablePO).find('tr:last').attr("id");
  const lastNo = $(tablePO).find('tr:last td:first-child').text();
  var newNum = +lastRow + 1;
  var newNo = +lastNo + 1;
  const newTr = `
    <tr class="text-bold" id="${newNum}">
      <td align="center" class="align-middle">${newNo}</td>
      <td>
        <input type="text" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold kdBrgRow">
      </td>
      <td>
        <input type="text" name="namaBarang[]" id="nmBrgRow${newNum}" placeholder="Masukkan Nama" class="form-control form-control-sm text-bold nmBrgRow">
      </td>
      <td> 
        <input type="text" name="qty[]" id="qtyRow${newNum}" class="form-control form-control-sm text-bold qtyRow" placeholder="Qty PO">
      </td>
      <td>
        <input type="text" name="harga[]" id="hargaRow${newNum}" class="form-control form-control-sm text-bold text-right hargaRow" placeholder="Harga Satuan" readonly >
      </td>
      <td>
        <input type="text" name="jumlah[]" id="jumlahRow${newNum}" class="form-control form-control-sm text-bold text-right jumlahRow" placeholder="Total Harga" readonly>
      </td>
      <td>
        <input type="text" name="keterangan[]" id="keteranganRow${newNum}" class="form-control form-control-sm text-bold keteranganRow" placeholder="Keterangan">
      </td>
      <td align="center" class="align-middle">
        <a href="#" class="icRemoveRow" id="icRemoveRow${newNum}">
          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
        </a>
      </td>
    </tr>
  `; 

  $(tablePO).append(newTr);
  jumBaris.value = newNum;
  const newRow = document.getElementById(newNum);
  const brgRow = document.getElementById("nmBrgRow"+newNum);
  const kodeRow = document.getElementById("kdBrgRow"+newNum);
  const qtyRow = document.getElementById("qtyRow"+newNum);
  const hargaRow = document.getElementById("hargaRow"+newNum);
  const jumlahRow = document.getElementById("jumlahRow"+newNum);
  const keteranganRow = document.getElementById("keteranganRow"+newNum);
  const hapusRow = document.getElementById("icRemoveRow"+newNum);

  /** Tampil Harga **/
  brgRow.addEventListener("change", function (e) {   
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qtyRow.removeAttribute('required');
    } 

    @foreach($barang as $br)
      if('{{ $br->nama }}' == e.target.value) {
        kodeRow.value = '{{ $br->id }}';
      }
    @endforeach
    displayHargaRow(kodeRow.value);
  });

  kodeRow.addEventListener("change", function (e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qtyRow.removeAttribute('required');
    }

    @foreach($barang as $br)
      if('{{ $br->id }}' == e.target.value) {
        brgRow.value = '{{ $br->nama }}';
      }
    @endforeach
    displayHargaRow(e.target.value);
  });

  function displayHargaRow(kode) {
    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kode) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        hargaRow.value = addCommas('{{ $hb->harga }}');
        qtyRow.setAttribute('required', true);
      }
    @endforeach
  }

  /** Tampil Jumlah **/
  qtyRow.addEventListener("change", function (e) {
    if(e.target.value == "") {
      jumlahRow.value = "";
    }
    else {
      jumlahRow.value = addCommas(e.target.value * hargaRow.value.replace(/\./g, ""));
    }
  });
  
  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $(tablePO).find('tr:last').attr("id");
    if(curNum < lastNum) {
      $(newRow).remove();
      for(let i = curNum; i < lastNum; i++) {
        $(tablePO).find('tr:nth-child('+i+') td:first-child').html(i);
      }
    }
    else if(curNum == lastNum) {
      $(newRow).remove();
    }
    jumBaris.value -= 1;
  });

  /** Autocomplete Nama  Barang **/
  $(function() {
    var idBarang = [];
    var nmBarang = [];
    @foreach($barang as $b)
      idBarang.push('{{ $b->id }}');
      nmBarang.push('{{ $b->nama }}');
    @endforeach
      
    function split(val) {
      return val.split(/,\s/);
    }

    function extractLast(term) {
      return split(term).pop();
    }

    $(kodeRow).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        response($.ui.autocomplete.filter(idBarang, extractLast(request.term)));
      },
      focus: function() {
        return false;
      },
      select: function(event, ui) {
        var terms = split(this.value);
        terms.pop();
        terms.push(ui.item.value);
        terms.push("");
        this.value = terms.join("");
        return false;
      }
    });
    
    $(brgRow).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        response($.ui.autocomplete.filter(nmBarang, extractLast(request.term)));
      },
      focus: function() {
        return false;
      },
      select: function(event, ui) {
        var terms = split(this.value);
        terms.pop();
        terms.push(ui.item.value);
        terms.push("");
        this.value = terms.join("");
        return false;
      }
    });
  }); 
}

/** Tampil Id Supp **/
function displaySupp(e) {
  @foreach($supplier as $s)
    if('{{ $s->nama }}' == e.target.value) {
      kodeSup.value = '{{ $s->id }}';
    }
  @endforeach
}

/** Tampil Harga Barang **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("change", function (e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if('{{ $br->nama }}' == e.target.value) {
        kodeBarang[i].value = '{{ $br->id }}';
      }
    @endforeach
    displayHarga(kodeBarang[i].value);
  });

  kodeBarang[i].addEventListener("change", function (e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

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
        harga[i].value = addCommas('{{ $hb->harga }}');
        qty[i].setAttribute('required', true);
      }
    @endforeach
  }
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("change", function (e) {
    if(e.target.value == "") {
      jumlah[i].value = "";
    }
    else {
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
    }
  });
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
    keterangan[i].value = keterangan[i+1].value;
    jumlah[i].value = jumlah[i+1].value;
    harga[i].value = harga[i+1].value;
    qty[i].value = qty[i+1].value;
    brgNama[i].value = brgNama[i+1].value;
    kodeBarang[i].value = kodeBarang[i+1].value;
    if(kodeBarang[i+1].value == "")
      qty[i].removeAttribute('required');
    else
      qty[i+1].removeAttribute('required');
    $(this).parents('tr').next().find('input').val('');
  });
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

</script>
@endpush