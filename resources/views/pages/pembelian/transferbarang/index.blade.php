@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

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
                    <label for="kode" class="col-auto col-form-label text-bold ">Nomor TB</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2">
                      <input type="text" class="form-control col-form-label-sm text-bold" name="kode" value="{{ $newcode }}" readonly>
                    </div>
                    <label for="nama" class="col-auto col-form-label text-bold ">Tanggal TB</label>
                    <span class="col-form-label text-bold ml-3">:</span>
                    <div class="col-2">
                      <input type="text" class="form-control datepicker col-form-label-sm text-bold" name="tanggal" id="tanggal" value="{{ $tanggal }}" required>
                    </div>
                    <input type="hidden" name="jumBaris" id="jumBaris" value="5">
                  </div> 
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->
              
              <!-- Inputan Detil BM -->
              {{-- <div class="form-row">
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
              <hr> --}}
              <!-- End Inputan Detil BM -->

              <!-- Tabel Data Detil BM-->
              <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
              </span>
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <td class="align-middle" style="width: 40px">No</td>
                  <td style="width: 90px">Kode Barang</td>
                  <td class="align-middle">Nama Barang</td>
                  <td class="align-middle" style="width: 160px">Gudang Asal</td>
                  <td style="width: 60px">Stok Asal</td>
                  <td class="align-middle" style="width: 160px">Gudang Tujuan</td>
                  <td style="width: 60px">Stok Tujuan</td>
                  <td style="width: 70px">Qty Transfer</td>
                  <td style="width: 50px">Delete</td>
                </thead>
                <tbody id="tablePO">
                  @for($i=1; $i<=5; $i++)
                    <tr class="text-dark" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" name="kodeBarang[]" id="kodeBarang" class="form-control form-control-sm text-dark kodeBarang"
                        value="{{ old('kodeBarang[]') }}" @if($i == 1) required @endif >
                      </td>
                      <td>
                        <input type="text" name="namaBarang[]" id="namaBarang" class="form-control form-control-sm text-dark namaBarang"
                        value="{{ old('namaBarang[]') }}" @if($i == 1) required @endif>
                      </td>
                      <td> 
                        <input type="text" name="gdgAsal[]" id="gdgAsal" class="form-control form-control-sm text-dark gdgAsal" value="{{ old('gdgAsal[]') }}">
                        <input type="hidden" name="kodeAsal[]" class="kodeAsal">
                      </td>
                      <td> 
                        <input type="text" name="stokAsal[]" id="stokAsal" readonly class="form-control-plaintext form-control-sm text-dark text-center stokAsal" value="{{ old('stokAsal[]') }}">
                      </td>
                      <td> 
                        <input type="text" name="gdgTujuan[]" id="gdgTujuan" class="form-control form-control-sm text-dark gdgTujuan" value="{{ old('gdgTujuan[]') }}">
                        <input type="hidden" name="kodeTujuan[]" class="kodeTujuan">
                      </td>
                      <td> 
                        <input type="text" name="stokTujuan[]" id="stokTujuan" readonly class="form-control-plaintext form-control-sm text-dark text-center stokTujuan" value="{{ old('stokTujuan[]') }}">
                      </td>
                      <td> 
                        <input type="text" name="qtyTransfer[]" id="qtyTransfer" class="form-control form-control-sm text-dark text-center qtyTransfer" value="{{ old('qtyTransfer[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
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
                  @endif --}}

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
<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
$.fn.datepicker.dates['id'] = {
  days:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],
  daysShort:["Mgu","Sen","Sel","Rab","Kam","Jum","Sab"],
  daysMin:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
  months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
  monthsShort:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"],
  today:"Hari Ini",
  clear:"Kosongkan"
};

$('.datepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true,
  language: 'id',
});

const tanggal = document.getElementById('tanggal');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const gdgAsal = document.querySelectorAll(".gdgAsal");
const kodeAsal = document.querySelectorAll(".kodeAsal");
const stokAsal = document.querySelectorAll(".stokAsal");
const gdgTujuan = document.querySelectorAll(".gdgTujuan");
const kodeTujuan = document.querySelectorAll(".kodeTujuan");
const stokTujuan = document.querySelectorAll(".stokTujuan");
const qtyTransfer = document.querySelectorAll(".qtyTransfer");
const hapusBaris = document.querySelectorAll(".icRemove");
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');

newRow.addEventListener('click', displayRow);
tanggal.addEventListener("keyup", formatTanggal);

function formatTanggal(e) {
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if(value.length > 2 && value.length <= 4) 
    value = value.slice(0,2) + "-" + value.slice(2);
  else if(value.length > 4 && value.length <= 8)
    value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
  
  tanggal.value = value;
}

/** Add New Table Line **/
function displayRow(e) {
  const lastRow = $(tablePO).find('tr:last').attr("id");
  const lastNo = $(tablePO).find('tr:last td:first-child').text();
  var newNum = +lastRow + 1;
  var newNo = +lastNo + 1;
  const newTr = `
    <tr class="text-bold text-dark" id="${newNum}">
      <td align="center" class="align-middle">${newNo}</td>
      <td>
        <input type="text" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold kdBrgRow">
      </td>
      <td>
        <input type="text" name="namaBarang[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-bold nmBrgRow">
      </td>
      <td> 
        <input type="text" name="gdgAsal[]" id="gdgAsal${newNum}" class="form-control form-control-sm text-bold text-dark gdgAsalRow" >
        <input type="hidden" name="kodeAsal[]" id="kodeAsal${newNum}" class="kodeAsalRow">
      </td>
      <td> 
        <input type="text" name="stokAsal[]" id="stokAsal${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark stokAsalRow">
      </td>
      <td> 
        <input type="text" name="gdgTujuan[]" id="gdgTujuan${newNum}" class="form-control form-control-sm text-bold text-dark gdgTujuanRow" >
        <input type="hidden" name="kodeTujuan[]" id="kodeTujuan${newNum}" class="kodeTujuanRow">
      </td>
      <td> 
        <input type="text" name="stokTujuan[]" id="stokTujuan${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark stokTujuanRow">
      </td>
      <td> 
        <input type="text" name="qtyTransfer[]" id="qtyTransfer${newNum}" class="form-control form-control-sm text-bold text-dark qtyTransferRow" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
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
  const gdgAsalRow = document.getElementById("gdgAsal"+newNum);
  const kodeAsalRow = document.getElementById("kodeAsal"+newNum);
  const stokAsalRow = document.getElementById("stokAsal"+newNum);
  const gdgTujuanRow = document.getElementById("gdgTujuan"+newNum);
  const kodeTujuanRow = document.getElementById("kodeTujuan"+newNum);
  const stokTujuanRow = document.getElementById("stokTujuan"+newNum);
  const qtyTransferRow = document.getElementById("qtyTransfer"+newNum);
  const hapusRow = document.getElementById("icRemoveRow"+newNum);

  /** Tampil Harga **/
  brgRow.addEventListener("keydown", function (e) {   
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      gdgAsalRow.removeAttribute('required');
      gdgTujuanRow.removeAttribute('required');
    } 

    @foreach($barang as $br)
      if('{{ $br->nama }}' == e.target.value) {
        kodeRow.value = '{{ $br->id }}';
        gdgAsalRow.setAttribute('required', true);
        gdgTujuanRow.setAttribute('required', true);
      }
    @endforeach
  });

  kodeRow.addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      gdgAsalRow.removeAttribute('required');
      gdgTujuanRow.removeAttribute('required');
    }

    @foreach($barang as $br)
      if('{{ $br->id }}' == e.target.value) {
        brgRow.value = '{{ $br->nama }}';
        gdgAsalRow.setAttribute('required', true);
        gdgTujuanRow.setAttribute('required', true);
      }
    @endforeach
  });

  gdgAsalRow.addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      kodeAsalRow.value = "";
      stokAsalRow.value = "";
    }

    @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeAsalRow.value = '{{ $g->id }}';
      }
    @endforeach
    displayStokRow(kodeAsalRow.value, stokAsalRow);
  });

  gdgTujuanRow.addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      kodeTujuanRow.value = "";
      stokTujuanRow.value = "";
    }

    @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeTujuanRow.value = '{{ $g->id }}';
      }
    @endforeach
    displayStokRow(kodeTujuanRow.value, stokTujuanRow);
  });

  function displayStokRow(kode, stok) {
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeRow.value) && ('{{ $s->id_gudang }}' == kode)) {
        stok.value = '{{ $s->stok }}';
      }
    @endforeach
  }

  /** Inputan hanya bisa angka **/
  qtyTransferRow.addEventListener("keypress", function (e, evt) {
    evt = (evt) ? evt : window.event;
    var charCodeRow = (evt.which) ? evt.which : evt.keyCode;
    if (charCodeRow > 31 && (charCodeRow < 48 || charCodeRow > 57)) {
      $(qtyTransferRow).tooltip('show');
      
      e.preventDefault();
    }
    
    return true;
  });
  
  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $(tablePO).find('tr:last').attr("id");
    if(+curNum < +lastNum) {
      $(newRow).remove();
      for(let i = +curNum; i < +lastNum; i++) {
        $(tablePO).find('tr:nth-child('+i+') td:first-child').html(i);
      }
    }
    else if(+curNum == +lastNum) {
      $(newRow).remove();
    }
    jumBaris.value -= 1;
  });

  /** Autocomplete Nama  Barang **/
  $(function() {
    var idBarang = [];
    var nmBarang = [];
    var nmGudang = [];
    @foreach($barang as $b)
      idBarang.push('{{ $b->id }}');
      nmBarang.push('{{ $b->nama }}');
    @endforeach
    @foreach($gudang as $g)
      nmGudang.push('{{ $g->nama }}');
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

    $(gdgAsalRow).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(nmGudang, extractLast(request.term)));
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

    $(gdgTujuanRow).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(nmGudang, extractLast(request.term)));
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
}

/** Tampil Harga Barang **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      gdgAsal[i].removeAttribute('required');
      gdgTujuan[i].removeAttribute('required');
      qtyTransfer[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if('{{ $br->nama }}' == e.target.value) {
        kodeBarang[i].value = '{{ $br->id }}';
        gdgAsal[i].setAttribute('required', true);
        gdgTujuan[i].setAttribute('required', true);
        qtyTransfer[i].setAttribute('required', true);
      }
    @endforeach
  });

  kodeBarang[i].addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      gdgAsal[i].removeAttribute('required');
      gdgTujuan[i].removeAttribute('required');
      qtyTransfer[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if('{{ $br->id }}' == e.target.value) {
        brgNama[i].value = '{{ $br->nama }}';
        gdgAsal[i].setAttribute('required', true);
        gdgTujuan[i].setAttribute('required', true);
        qtyTransfer[i].setAttribute('required', true);
      }
    @endforeach
  });
}

/** Tampil Stok Gudang **/
for(let i = 0; i < gdgAsal.length; i++) {
  gdgAsal[i].addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      kodeAsal[i].value = "";
      stokAsal[i].value = "";
    }

    @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeAsal[i].value = '{{ $g->id }}';
      }
    @endforeach
    displayStok(kodeAsal[i].value, stokAsal[i]);
  });

  gdgTujuan[i].addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      kodeTujuan[i].value = "";
      stokTujuan[i].value = "";
    }

    @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeTujuan[i].value = '{{ $g->id }}';
      }
    @endforeach
    displayStok(kodeTujuan[i].value, stokTujuan[i]);
  });

  function displayStok(kode, stok) {
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->id_gudang }}' == kode)) {
        stok.value = '{{ $s->stok }}';
      }
    @endforeach
  }
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  console.log(charCode);
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= qtyTransfer.length; i++) {
      if(inputan == i)
        $(qtyTransfer[inputan-1]).tooltip('show');
    }

    return false;
  }
  return true;
}

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    qtyTransfer[i].value = qtyTransfer[i+1].value;
    stokTujuan[i].value = stokTujuan[i+1].value;
    gdgTujuan[i].value = gdgTujuan[i+1].value;
    stokAsal[i].value = stokAsal[i+1].value;
    gdgAsal[i].value = gdgAsal[i+1].value;
    brgNama[i].value = brgNama[i+1].value;
    kodeBarang[i].value = kodeBarang[i+1].value;
    if(kodeBarang[i+1].value == "") {
      gdgAsal[i].removeAttribute('required');
      gdgTujuan[i].removeAttribute('required');
      qtyTransfer[i].removeAttribute('required');
    }
    else {
      gdgAsal[i+1].removeAttribute('required');
      gdgTujuan[i+1].removeAttribute('required');
      qtyTransfer[i].removeAttribute('required');
    }
    $(this).parents('tr').next().find('input').val('');
  });
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
  $(gdgAsal).on("keydown", function(event) {
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
  $(gdgTujuan).on("keydown", function(event) {
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

</script>
@endpush