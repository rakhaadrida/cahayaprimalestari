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
            <form action="" method="" id="formTB">
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
                      <input type="text" tabindex="1" class="form-control datepicker col-form-label-sm text-bold" name="tanggal" id="tanggal" value="{{ $tanggal }}" autocomplete="off" required>
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
                  @php $tab = 1 @endphp
                  @for($i=1; $i<=5; $i++)
                    <tr class="text-dark text-bold" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" tabindex="{{ $tab++ }}" name="kodeBarang[]" id="kodeBarang" class="form-control form-control-sm text-dark text-bold kodeBarang"
                        value="{{ old('kodeBarang[]') }}" @if($i == 1) required autofocus @endif >
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 2 }}" name="namaBarang[]" id="namaBarang" class="form-control form-control-sm text-dark text-bold namaBarang"
                        value="{{ old('namaBarang[]') }}" @if($i == 1) required @endif>
                      </td>
                      <td> 
                        <input type="text" tabindex="{{ $tab += 3 }}" name="gdgAsal[]" id="gdgAsal" class="form-control form-control-sm text-dark text-bold gdgAsal" value="{{ old('gdgAsal[]') }}">
                        <input type="hidden" name="kodeAsal[]" class="kodeAsal">
                        <input type="hidden" name="statusAsal[]" class="statusAsal">
                      </td>
                      <td> 
                        <input type="text" name="stokAsal[]" id="stokAsal" readonly class="form-control-plaintext form-control-sm text-dark text-bold text-center stokAsal" value="{{ old('stokAsal[]') }}">
                      </td>
                      <td> 
                        <input type="text" tabindex="{{ $tab += 4 }}" name="gdgTujuan[]" id="gdgTujuan" class="form-control form-control-sm text-dark text-bold gdgTujuan" value="{{ old('gdgTujuan[]') }}">
                        <input type="hidden" name="kodeTujuan[]" class="kodeTujuan">
                        <input type="hidden" name="statusTujuan[]" class="statusTujuan">
                      </td>
                      <td> 
                        <input type="text" name="stokTujuan[]" id="stokTujuan" readonly class="form-control-plaintext form-control-sm text-dark text-bold text-center stokTujuan" value="{{ old('stokTujuan[]') }}">
                      </td>
                      <td> 
                        <input type="text" tabindex="{{ $tab += 5 }}" name="qtyTransfer[]" id="qtyTransfer" class="form-control form-control-sm text-bold text-dark text-center qtyTransfer" value="{{ old('qtyTransfer[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
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
                  <button type="submit" tabindex="{{ $tab++ }}" id="submitTB" formaction="{{ route('tb-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
                </div>
                <div class="col-2">
                  <button type="reset" tabindex="{{ $tab++ }}" id="resetTB" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
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

const formTB = document.getElementById("formTB");
const tanggal = document.getElementById('tanggal');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const gdgAsal = document.querySelectorAll(".gdgAsal");
const kodeAsal = document.querySelectorAll(".kodeAsal");
const statusAsal = document.querySelectorAll(".statusAsal");
const stokAsal = document.querySelectorAll(".stokAsal");
const gdgTujuan = document.querySelectorAll(".gdgTujuan");
const kodeTujuan = document.querySelectorAll(".kodeTujuan");
const statusTujuan = document.querySelectorAll(".statusTujuan");
const stokTujuan = document.querySelectorAll(".stokTujuan");
const qtyTransfer = document.querySelectorAll(".qtyTransfer");
const hapusBaris = document.querySelectorAll(".icRemove");
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');
var tab = '{{ $tab }}';

newRow.addEventListener('click', displayRow);
tanggal.addEventListener("keyup", formatTanggal);
formTB.addEventListener("keypress", checkEnter);

function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;     
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
}

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
    <tr class="text-dark text-bold" id="${newNum}">
      <td align="center" class="align-middle">${newNo}</td>
      <td>
        <input type="text" tabindex="${tab++}" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-dark text-bold kdBrgRow">
      </td>
      <td>
        <input type="text" tabindex="${tab += 2}" name="namaBarang[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-dark text-bold nmBrgRow">
      </td>
      <td> 
        <input type="text" tabindex="${tab += 3}" name="gdgAsal[]" id="gdgAsal${newNum}" class="form-control form-control-sm text-dark text-bold gdgAsalRow" >
        <input type="hidden" name="kodeAsal[]" id="kodeAsal${newNum}" class="kodeAsalRow">
        <input type="hidden" name="statusAsal[]" id="statusAsal${newNum}" class="statusAsalRow">
      </td>
      <td> 
        <input type="text" name="stokAsal[]" id="stokAsal${newNum}" readonly class="form-control-plaintext form-control-sm text-dark text-bold text-center stokAsalRow">
      </td>
      <td> 
        <input type="text" tabindex="${tab += 4}" name="gdgTujuan[]" id="gdgTujuan${newNum}" class="form-control form-control-sm text-dark text-bold gdgTujuanRow" >
        <input type="hidden" name="kodeTujuan[]" id="kodeTujuan${newNum}" class="kodeTujuanRow">
        <input type="hidden" name="statusTujuan[]" id="statusTujuan${newNum}" class="statusTujuanRow">
      </td>
      <td> 
        <input type="text" name="stokTujuan[]" id="stokTujuan${newNum}" readonly class="form-control-plaintext form-control-sm text-dark text-bold text-center stokTujuanRow">
      </td>
      <td> 
        <input type="text" tabindex="${tab += 5}" name="qtyTransfer[]" id="qtyTransfer${newNum}" class="form-control form-control-sm text-dark text-bold text-center qtyTransferRow" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
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
  const statusAsalRow = document.getElementById("statusAsal"+newNum);
  const stokAsalRow = document.getElementById("stokAsal"+newNum);
  const gdgTujuanRow = document.getElementById("gdgTujuan"+newNum);
  const kodeTujuanRow = document.getElementById("kodeTujuan"+newNum);
  const statusTujuanRow = document.getElementById("statusTujuan"+newNum);
  const stokTujuanRow = document.getElementById("stokTujuan"+newNum);
  const qtyTransferRow = document.getElementById("qtyTransfer"+newNum);
  const hapusRow = document.getElementById("icRemoveRow"+newNum);
  kodeRow.focus();
  document.getElementById("submitTB").tabIndex = tab++;
  document.getElementById("resetTB").tabIndex = tab++;

  /** Tampil Harga **/
  kodeRow.addEventListener("keyup", displayBarangRow);
  brgRow.addEventListener("keyup", displayBarangRow);
  kodeRow.addEventListener("blur", displayBarangRow);
  brgRow.addEventListener("blur", displayBarangRow);

  function displayBarangRow(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      gdgAsalRow.removeAttribute('required');
      gdgTujuanRow.removeAttribute('required');
      qtyTransferRow.removeAttribute('required');
    } 

    @foreach($barang as $br)
      if(('{{ $br->id }}' == e.target.value) || ('{{ $br->nama }}' == e.target.value)) {
        kodeRow.value = '{{ $br->id }}';
        brgRow.value = '{{ $br->nama }}';
        gdgAsalRow.setAttribute('required', true);
        gdgTujuanRow.setAttribute('required', true);
        qtyTransferRow.setAttribute('required', true);
      }
    @endforeach
  }

  gdgAsalRow.addEventListener("keyup", displayAsalRow);
  gdgAsalRow.addEventListener("blur", displayAsalRow);

  function displayAsalRow(e) {
    if(e.target.value == "") {
      kodeAsalRow.value = "";
      stokAsalRow.value = "";
    }

    @foreach($gudang as $g)
      @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeAsalRow.value = '{{ $g->id }}';
        statusAsalRow.value = 'T';
      } 
      if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Bagus')) {
        kodeAsalRow.value = '{{ $g->id }}';
        statusAsalRow.value = 'T';
      } else if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Jelek')) {
        kodeAsalRow.value = '{{ $g->id }}';
        statusAsalRow.value = 'F';
      }
    @endforeach
    @endforeach
    displayStokRow(kodeAsalRow.value, statusAsalRow.value, stokAsalRow);
  }

  gdgTujuanRow.addEventListener("keyup", displayTujuanRow);
  gdgTujuanRow.addEventListener("blur", displayTujuanRow);

  function displayTujuanRow(e) {
    if(e.target.value == "") {
      kodeTujuanRow.value = "";
      stokTujuanRow.value = "";
    }

    @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeTujuanRow.value = '{{ $g->id }}';
        statusTujuanRow.value = 'T';
      } 
      if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Bagus')) {
        kodeTujuanRow.value = '{{ $g->id }}';
        statusTujuanRow.value = 'T';
      } else if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Jelek')) {
        kodeTujuanRow.value = '{{ $g->id }}';
        statusTujuanRow.value = 'F';
      }
    @endforeach
    displayStokRow(kodeTujuanRow.value, statusTujuanRow.value, stokTujuanRow);
  }

  function displayStokRow(kode, status, stok) {
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeRow.value) && ('{{ $s->id_gudang }}' == kode) && ('{{ $s->status }}' == status)) {
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
    var numRow;
    if(+curNum < +lastNum) {
      $(newRow).remove();
      for(let i = +curNum; i < +lastNum; i++) {
        $(tablePO).find('tr:nth-child('+i+') td:first-child').html(i);
      }
      numRow = lastNum;
    }
    else if(+curNum == +lastNum) {
      $(newRow).remove();
      numRow = +curNum - 1;
    }
    jumBaris.value -= 1;
    if(jumBaris.value > 5)
      document.getElementById("kdBrgRow"+numRow).focus();
    else
      kodeBarang[4].focus();
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
      if('{{ $g->tipe }}' == 'RETUR') {
        nmGudang.push('Retur Bagus');
        nmGudang.push('Retur Jelek');
      } else {
        nmGudang.push('{{ $g->nama }}');
      }
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
  brgNama[i].addEventListener("keyup", displayBarang);
  kodeBarang[i].addEventListener("keyup", displayBarang);
  brgNama[i].addEventListener("blur", displayBarang);
  kodeBarang[i].addEventListener("blur", displayBarang);

  function displayBarang(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      gdgAsal[i].removeAttribute('required');
      gdgTujuan[i].removeAttribute('required');
      qtyTransfer[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->id }}' == e.target.value) || ('{{ $br->nama }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
        gdgAsal[i].setAttribute('required', true);
        gdgTujuan[i].setAttribute('required', true);
        qtyTransfer[i].setAttribute('required', true);
      }
    @endforeach

    gdgAsal[i].value = '';
    kodeAsal[i].value = '';
    statusAsal[i].value = '';
    stokAsal[i].value = '';
    gdgTujuan[i].value = '';
    kodeTujuan[i].value = '';
    statusTujuan[i].value = '';
    stokTujuan[i].value = '';
  }
}

/** Tampil Stok Gudang **/
for(let i = 0; i < gdgAsal.length; i++) {
  gdgAsal[i].addEventListener("keyup", displayAsal);
  gdgAsal[i].addEventListener("blur", displayAsal);

  function displayAsal(e) {
    if(e.target.value == "") {
      kodeAsal[i].value = "";
      statusAsal[i].value = "";
      stokAsal[i].value = "";
    }

    @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeAsal[i].value = '{{ $g->id }}';
        statusAsal[i].value = 'T';
      } 
      if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Bagus')) {
        kodeAsal[i].value = '{{ $g->id }}';
        statusAsal[i].value = 'T';
      } else if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Jelek')) {
        kodeAsal[i].value = '{{ $g->id }}';
        statusAsal[i].value = 'F';
      }
    @endforeach
    displayStok(kodeAsal[i].value, statusAsal[i].value, stokAsal[i]);
  }

  gdgTujuan[i].addEventListener("keyup", displayTujuan);
  gdgTujuan[i].addEventListener("blur", displayTujuan);

  function displayTujuan(e) {
    if(e.target.value == "") {
      kodeTujuan[i].value = "";
      statusTujuan[i].value = "";
      stokTujuan[i].value = "";
    }

    @foreach($gudang as $g)
      if('{{ $g->nama }}' == e.target.value) {
        kodeTujuan[i].value = '{{ $g->id }}';
        statusTujuan[i].value = 'T';
      }
      if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Bagus')) {
        kodeTujuan[i].value = '{{ $g->id }}';
        statusTujuan[i].value = 'T';
      } else if(('{{ $g->tipe }}' == 'RETUR') && (e.target.value == 'Retur Jelek')) {
        kodeTujuan[i].value = '{{ $g->id }}';
        statusTujuan[i].value = 'F';
      }
    @endforeach
    displayStok(kodeTujuan[i].value, statusTujuan[i].value, stokTujuan[i]);
  }

  function displayStok(kode, status, stok) {
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->id_gudang }}' == kode) && ('{{ $s->status }}' == status)) {
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
    kodeBarang[i].focus();
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
    if('{{ $g->tipe }}' == 'RETUR') {
      gudang.push('Retur Bagus');
      gudang.push('Retur Jelek');
    } else {
      gudang.push('{{ $g->nama }}');
    }
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