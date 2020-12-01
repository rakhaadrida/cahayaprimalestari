@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

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
                        <input type="text" class="form-control form-control-sm text-bold" name="kode" id="kode" value="" required>
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal BM</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" class="form-control datepicker form-control-sm text-bold" name="tanggal" id="tanggal" value="{{ $tanggal }}" required>
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -320px">
                    <div class="form-group row subtotal-po">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Sub Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right" name="subtotal" id="subtotal">
                      </div>
                    </div>
                    <div class="form-group row" style="margin-top: -25px">
                      <label for="ppn" class="col-5 col-form-label text-bold ">PPN</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right" name="ppn" id="ppn">
                      </div>
                    </div>
                    <div class="form-group row" style="margin-top: -25px">
                      <label for="grandtotal" class="col-5 col-form-label text-bold ">Grand Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext text-bold text-right text-danger" name="grandtotal" id="grandtotal">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row subtotal-so" style="margin-top: -68px">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Gudang</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" name="namaGudang" id="namaGudang" class="form-control form-control-sm text-bold" required autofocus>
                    <input type="hidden" name="kodeGudang" id="kodeGudang"> 
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" name="namaSupplier" id="namaSupplier"  class="form-control form-control-sm text-bold" required
                      {{-- @if($itemsRow != 0) 
                        value="{{ $items[$itemsRow - 1]->supplier->nama }}" readonly
                      @endif --}}
                    />
                    <input type="hidden" name="kodeSupplier" id="kodeSupplier" 
                      {{-- @if($itemsRow != 0) 
                        value="{{ $items[$itemsRow - 1]->id_supplier }}"
                      @endif --}}
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
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <td style="width: 30px" class="align-middle">No</td>
                    <td style="width: 120px" class="align-middle">Kode Barang</td>
                    <td @if(Auth::user()->roles == 'ADMIN') style="width: 320px" @endif class="align-middle">Nama Barang</td>
                    <td style="width: 85px" class="align-middle">Qty</td>
                    <td style="width: 120px" class="align-middle">Harga</td>
                    <td style="width: 140px" class="align-middle">Jumlah</td>
                    {{-- @if(Auth::user()->roles == 'SUPER')
                      <td colspan="2">Diskon</td>
                      <td rowspan="2" style="width: 120px" class="align-middle">Netto (Rp)</td>
                    @endif --}}
                    <td style="width: 50px" class="align-middle">Hapus</td>
                  </tr>
                  {{-- @if(Auth::user()->roles == 'SUPER')
                    <tr>
                      <td>%</td>
                      <td>Rupiah</td>
                    </tr>
                  @endif --}}
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
                        <input type="text" name="qty[]" id="qty" class="form-control form-control-sm text-bold text-dark text-right qty" value="{{ old('qty[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
                      </td>
                      <td>
                        <input type="text" name="harga[]" id="harga" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" value="{{ old('harga[]') }}">
                      </td>
                      <td>
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ old('jumlah[]') }}" >
                      </td>
                      {{-- @if(Auth::user()->roles == 'SUPER')
                        <td style="width: 90px">
                          <input type="text" name="diskon[]" id="diskon" class="form-control form-control-sm text-bold text-right text-dark diskon" 
                          value="{{ old('diskon[]') }}" >
                        </td>
                        <td style="width: 100px">
                          <input type="text" name="diskonRp[]" id="diskonRp" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark diskonRp" 
                          value="{{ old('diskonRp[]') }}" >
                        </td>
                        <td>
                          <input type="text" name="netto[]" id="netto" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto" value="{{ old('netto[]') }}" >
                        </td>
                      @endif --}}
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
                  <button type="submit" onclick="return checkRequired(event)" id="submitBM"  class="btn btn-success btn-block text-bold">Submit</button>
                  {{-- id="submitBM" onclick="return checkEditable()" 
                  formaction="{{ route('bm-process', $newcode) }}" formmethod="POST"--}}
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-danger btn-block text-bold">Reset All </button> 
                  {{-- formaction="{{ route('bm-reset', $newcode) }}" formmethod="GET" --}}
                </div>
              </div>
              <!-- End Button Submit dan Reset -->

              <!-- Modal Konfirmasi Cetak atau Input -->
              <div class="modal" id="modalKonfirm" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title">Konfirmasi Barang Masuk <b>{{$newcode}}</b></h4>
                    </div>
                    <div class="modal-body">
                      <p>Data Barang Masuk <strong>{{$newcode}}</strong> akan disimpan. Silahkan pilih cetak atau input barang masuk lagi.</p>
                      <hr>
                      <div class="form-row justify-content-center">
                        <div class="col-3">
                          <button type="submit" formaction="{{ route('bm-process', ['id' => $newcode, 'status' => 'CETAK']) }}" formmethod="POST" class="btn btn-success btn-block text-bold btnCetak">Cetak</button>
                        </div>
                        <div class="col-3">
                          <button type="submit" formaction="{{ route('bm-process', ['id' => $newcode, 'status' => 'INPUT']) }}" formmethod="POST" class="btn btn-outline-secondary btn-block text-bold">Input Lagi</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Modal Konfirmasi -->

              @if($status == 'true')
                <!-- Tampilan Cetak -->
                <iframe src="{{url('barangmasuk/cetak/'.$lastcode)}}" id="frameCetak" frameborder="0" hidden></iframe>
              @endif

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
<script src="{{ url('backend/vendor/jquery/jquery.printPageSO.js') }}"></script>
<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
@if($status == 'true')
  $(document).ready(function() {
    $("#frameCetak").printPage();
  });
@endif

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

const kode = document.getElementById('kode');
const namaSup = document.getElementById('namaSupplier');
const kodeSup = document.getElementById('kodeSupplier');
const tanggal = document.getElementById('tanggal');
const gudang = document.getElementById('namaGudang');
const kodeGud = document.getElementById('kodeGudang');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const diskon = document.querySelectorAll(".diskon");
const diskonRp = document.querySelectorAll(".diskonRp");
const netto = document.querySelectorAll(".netto");
const subtotal = document.getElementById('subtotal');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
const hapusBaris = document.querySelectorAll(".icRemove");
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');
var netPast;
// const keterangan = document.querySelectorAll(".keterangan");

tanggal.addEventListener("keyup", formatTanggal);
gudang.addEventListener("keyup", displayGud);
namaSup.addEventListener("keyup", displaySupp);
namaSup.addEventListener("focusout", focusTabel);
newRow.addEventListener('click', displayRow);

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
        <input type="text" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark kdBrgRow">
      </td>
      <td>
        <input type="text" name="namaBarang[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark nmBrgRow">
      </td>
      <td> 
        <input type="text" name="qty[]" id="qtyRow${newNum}" class="form-control form-control-sm text-bold text-dark qtyRow" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
      </td>
      <td>
        <input type="text" name="harga[]" id="hargaRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right hargaRow">
      </td>
      <td>
        <input type="text" name="jumlah[]" id="jumlahRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlahRow">
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
  const hapusRow = document.getElementById("icRemoveRow"+newNum);

  /** Tampil Harga **/
  brgRow.addEventListener("keyup", function (e) {   
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

  kodeRow.addEventListener("keyup", function (e) {
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
        hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
        qtyRow.setAttribute('required', true);
      }
    @endforeach
  }

  /** Inputan hanya bisa angka **/
  qtyRow.addEventListener("keypress", function (e, evt) {
    evt = (evt) ? evt : window.event;
    var charCodeRow = (evt.which) ? evt.which : evt.keyCode;
    if (charCodeRow > 31 && (charCodeRow < 48 || charCodeRow > 57)) {
      $(qtyRow).tooltip('show');
      
      e.preventDefault();
    }
    
    return true;
  });

  /** Tampil Jumlah **/
  qtyRow.addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlahRow.value.replace(/\./g, ""));
      jumlahRow.value = "";
    }
    else {  
      netPast = +jumlahRow.value.replace(/\./g, "");
      jumlahRow.value = addCommas(e.target.value * hargaRow.value.replace(/\./g, ""));
      checkSubtotal(netPast, +jumlahRow.value.replace(/\./g, ""));
    }
    total_ppn(subtotal.value.replace(/\./g, ""));
  });
  
  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $(tablePO).find('tr:last').attr("id");
    if(qtyRow.value != "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlahRow.value.replace(/\./g, ""));
      total_ppn(subtotal.value.replace(/\./g, ""));
    }

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

/** Tampil Id Supp **/
function displayGud(e) {
  @foreach($gudang as $g)
    if('{{ $g->nama }}' == e.target.value) {
      kodeGud.value = '{{ $g->id }}';
    }
    else if(e.target.value == '') {
      kodeGud.value = '';
    }
  @endforeach
}

/** Tampil Id Supp **/
function displaySupp(e) {
  @foreach($supplier as $s)
    if('{{ $s->nama }}' == e.target.value) {
      kodeSup.value = '{{ $s->id }}';
    }
    else if(e.target.value == '') {
      kodeSup.value = '';
    }
  @endforeach
}

function focusTabel(e) {
  kodeBarang[0].focus();
}

/** Tampil Harga Barang **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
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

  kodeBarang[i].addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
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
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        qty[i].setAttribute('required', true);
      }
    @endforeach
  }
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
    }
    else {  
      netPast = +jumlah[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +jumlah[i].value.replace(/\./g, ""));
    }
    total_ppn(subtotal.value.replace(/\./g, ""));
  });

  // qty[i].addEventListener("focusout", focusKode);
  
  // function focusKode(e) {
  //   kodeBarang[i+1].focus();
  // }
} 

/** Tampil Diskon Rupiah Otomatis **/
for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("keydown", function (e) {
    if(e.target.value == "") {
      netPast = netto[i].value.replace(/\./g, "");
      netto[i].value = addCommas(+netto[i].value.replace(/\./g, "") + +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, netto[i].value.replace(/\./g, ""));
      diskonRp[i].value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      netPast = +netto[i].value.replace(/\./g, "");
      diskonRp[i].value = addCommas(angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100);
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    total_ppn(subtotal.value.replace(/\./g, ""));
  });
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

/** Check Jumlah Netto onChange **/
function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
  }
}

/** Hitung PPN Dan Total **/
function total_ppn(sub) {
  // ppn.value = addCommas(Math.floor(sub * 10 / 100));
  grandtotal.value = addCommas(+sub + +ppn.value.replace(/\./g, ""));
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

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    if(qty[i].value != "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      total_ppn(subtotal.value.replace(/\./g, ""));
    }

    for(let j = i; j < hapusBaris.length; j++) {
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
    $(this).parents('tr').next().find('input').val('');
  });
}

function checkRequired(e) {
  if((kode.value == "") || (tanggal.value == "") || 
  (namaSup.value == "") || (gudang.value == "")) {
    e.stopPropagation();
  }
  else {
    document.getElementById("submitBM").dataset.toggle = "modal";
    document.getElementById("submitBM").dataset.target = "#modalKonfirm";
    return false;
  }
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

  var nmGudang = [];
  @foreach($gudang as $g)
    nmGudang.push('{{ $g->nama }}');
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

  /*-- Autocomplete Input Gudang --*/
  $(gudang).on("keydown", function(event) {
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

</script>
@endpush