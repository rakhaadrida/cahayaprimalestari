@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Retur Tagihan Faktur {{ $item->first()->id_so }}</h1>
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
            <form action="" method="" id="returAR">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier BM -->
               <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="1" readonly class="form-control form-control-sm text-bold text-dark" name="kodeSO" value="{{ $item->first()->id_so }}" >
                        <input type="hidden" name="kode" value="{{ $item->first()->id }}">
                        <input type="hidden" name="kodeRet" value="{{ $retur->count() != 0 ? $retur->first()->id : '' }}">
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="2" readonly class="form-control datepicker form-control-sm text-bold text-dark" name="tanggal" value="{{ \Carbon\Carbon::parse($item->first()->so->tgl_so)->format('d-M-y') }}">
                      </div>
                    </div>
                  </div>
                  <div class="col" style="margin-left: -360px">
                    <div class="form-group row subtotal-po">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Sub Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right text-dark" name="subtotal" id="subtotal" value={{$total->count() != 0 ? number_format($total->first()->total, 0, "", ".") : ''}}>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" tabindex="5" name="namaCust" readonly class="form-control form-control-sm text-bold text-dark" value="{{ $item->first()->so->customer->nama }}" />
                    <input type="hidden" name="kodeCustomer" value="{{ $item->first()->so->id_customer }}">
                  </div>
                  <input type="hidden" name="jumBaris" id="jumBaris" value="3">
                  <input type="hidden" name="jumAwal" id="jumAwal" value="{{ $retur->count() }}">
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->

              <!-- Tabel Data Detil BM-->
              @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                  Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
                </span>
              @endif
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <tr class="text-center">
                    <th rowspan="2" class="align-middle" style="width: 30px">No</th>
                    <th rowspan="2" class="align-middle"style="width: 90px">Kode</th>
                    <th rowspan="2" class="align-middle">Nama Barang</th>
                    <th rowspan="2" class="align-middle"style="width: 110px">Tgl. Retur</th>
                    <th rowspan="2" class="align-middle"style="width: 60px">Qty</th>
                    <th rowspan="2" class="align-middle"style="width: 60px">Tipe Harga</th>
                    <th rowspan="2" class="align-middle"style="width: 90px">Harga</th>
                    <th rowspan="2" class="align-middle"style="width: 100px">Jumlah</th>
                    <th colspan="2">Diskon</th>
                    <th rowspan="2" class="align-middle"style="width: 110px">Total</th>
                    <th rowspan="2" class="align-middle"style="width: 50px">Hapus</th>
                  </tr>
                  <tr class="text-center">
                    <th style="width: 90px">%</th>
                    <th style="width: 110px">Rupiah</th>
                  </tr>
                </thead>
                <tbody id="tablePO" class="table-ar">
                  @php $i = 1; $totalQty = 0; $totalRet = 0; @endphp
                  @foreach($retur as $d)
                    <tr class="table-modal-first-row text-dark retur-ar" id="{{ $i-1 }}">
                      <td class="text-center align-middle">{{ $i }}</td>
                      <td class="text-center">
                        <input type="text" readonly class="form-control form-control-sm text-bold text-center text-dark kodeBarang" name="kodeDetil[]" required value="{{ $d->id_barang }}">
                      </td>
                      <td>
                         <input type="text" readonly class="form-control form-control-sm text-bold text-dark namaBarang" name="namaDetil[]" value="{{ $d->barang->nama }}">
                        </td>
                      <td class="text-center">
                        <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglRetur" name="tglDetil[]" autocomplete="off" required value="{{ \Carbon\Carbon::parse($d->tgl_retur)->format('d-m-Y') }}">
                      </td>
                      <td class="text-right">
                        <input type="text" class="form-control form-control-sm text-bold text-dark text-right qty" name="qtyDetil[]" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" required value="{{ $d->qty }}">
                      </td>
                      @php $tipe = App\Models\HargaBarang::where('id_barang', $d->id_barang)->where('harga_ppn', $d->harga)->get(); @endphp
                      <td class="text-right">
                        <input type="text" class="form-control form-control-sm text-bold text-dark text-center tipe" name="tipeDetil[]" autocomplete="off" required value="{{ $tipe->first()->hargaBarang->tipe }}">
                      </td>
                      {{-- @php $kurang -= $d->cicil; @endphp --}}
                      <td class="text-right">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" name="hargaDetil[]" value="{{ number_format($d->harga, 0, "", ".") }}">
                      </td>
                      <td class="text-right">
                        <input type="text" name="jumlahDetil[]" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ number_format($d->qty * $d->harga, 0, "", ".") }}">
                      </td>
                      <td class="text-right">
                        <input type="text" name="diskonDetil[]" class="form-control form-control-sm text-bold text-dark text-right diskon" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9, tanda +, dan tanda koma" autocomplete="off" value="{{ $d->diskon }}">
                      </td>
                      <td class="text-right">
                        <input type="text" name="diskonRpDetil[]" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right diskonRp" value="{{ number_format($d->diskonRp, 0, "", ".") }}">
                      </td>
                      <td class="text-right">
                        <input type="text" name="nettoDetil[]" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto" value="{{ number_format(($d->qty * $d->harga) - $d->diskonRp, 0, "", ".") }}">
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemoveDetil">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                    </tr>
                    @php $i++; $totalQty += $d->qty; $totalRet += (($d->qty * $d->harga) - $d->diskonRp) @endphp
                  @endforeach
                  @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                    @for($j = $i; $j <= $i+2; $j++)
                      <tr class="text-dark" id="{{ $j }}">
                        <td class="text-center align-middle">{{ $j }}</td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark kodeBarang" name="kodeBarang{{$item->first()->id}}[]" id="kodeBarang{{$item->first()->id}}" >
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark namaBarang" name="namaBarang{{$item->first()->id}}[]" id="namaBarang{{$item->first()->id}}">
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglRetur" name="tglRetur{{$item->first()->id}}[]" id="tglRetur{{$item->first()->id}}" placeholder="DD-MM-YYYY" autocomplete="off" >
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark text-right qty" name="qty{{$item->first()->id}}[]" id="qty{{$item->first()->id}}" onkeypress="return angkaSaja(event, {{$j - $retur->count()}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" >
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark text-center tipe" name="tipe{{$item->first()->id}}[]" id="tipe{{$item->first()->id}}" autocomplete="off" >
                        </td>
                        <td class="align-middle">
                          <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" name="harga{{$item->first()->id}}[]" id="harga{{$item->first()->id}}">
                        </td>
                        <td class="text-right align-middle">
                          <input type="text" name="jumlah{{$item->first()->id}}[]" id="jumlah{{$item->first()->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah">
                        </td>
                        <td class="align-middle" style="width: 90px">
                          <input type="text" name="diskon{{$item->first()->id}}[]" id="diskon{{$item->first()->id}}" class="form-control form-control-sm text-bold text-dark text-right diskon" onkeypress="return angkaPlus(event, {{$j - $retur->count()}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9, tanda +, dan tanda koma" autocomplete="off">
                        </td>
                        <td class="text-right align-middle" style="width: 110px">
                          <input type="text" name="diskonRp{{$item->first()->id}}[]" id="diskonRp{{$item->first()->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right diskonRp">
                        </td>
                        <td class="text-right align-middle">
                          <input type="text" name="netto{{$item->first()->id}}[]" id="netto{{$item->first()->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto">
                        </td>
                        <td align="center" class="align-middle">
                          <a href="#" class="icRemove">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                    @endfor
                  @endif
                </tbody>
                <tfoot>
                  <tr class="text-right text-bold text-dark" style="font-size: 16px">
                    <td colspan="4" class="align-middle text-center ">Total</td>
                    <td>{{ number_format($totalQty, 0, "", ".") }}</td>
                    <td colspan="5"></td>
                    <td>{{ number_format($totalRet, 0, "", ".") }}</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ar-retur') }}" formmethod="POST">Submit</button>
                  </div>
                  <div class="col-2">
                    <button type="reset" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                  </div>
                  <div class="col-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                  </div>
                </div>
              @endif
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

const returAR = document.getElementById("returAR");
const subtotal = document.getElementById('subtotal');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const tglRetur = document.querySelectorAll('.tglRetur');
const qty = document.querySelectorAll('.qty');
const tipe = document.querySelectorAll(".tipe");
const harga = document.querySelectorAll('.harga');
const jumlah = document.querySelectorAll('.jumlah');
const diskon = document.querySelectorAll('.diskon');
const diskonRp = document.querySelectorAll('.diskonRp');
const netto = document.querySelectorAll('.netto');
const total = document.querySelectorAll('.total');
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');
const jumAwal = document.getElementById('jumAwal');
const hapusBaris = document.querySelectorAll(".icRemoveDetil");
const hapusBiasa = document.querySelectorAll(".icRemove");
const retur = '{{ $retur->count() }}';

newRow.addEventListener('click', displayRow);
returAR.addEventListener("keypress", checkEnter);

function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
}

function displayRow(e) {
  const lastRow = $(tablePO).find('tr:last').attr("id");
  const lastNo = $(tablePO).find('tr:last td:first-child').text();
  // var newNum = +lastRow + 1;
  var newNum = +jumAwal.value + +jumBaris.value + 1;
  var newNo = +lastNo + 1;
  const newTr = `
    <tr class="text-dark" id="${newNum}">
      <td align="center" class="align-middle">${newNo}</td>
      <td class="align-middle">
        <input type="text" name="kodeBarang{{$item->first()->id}}[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark kdBrgRow">
      </td>
      <td class="align-middle">
        <input type="text" name="namaBarang{{$item->first()->id}}[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark nmBrgRow">
      </td>
      <td class="align-middle">
        <input type="text" class="form-control datepickerRow form-control-sm text-bold text-dark text-center tglReturRow" name="tglRetur{{$item->first()->id}}[]" id="tglReturRow${newNum}" placeholder="DD-MM-YYYY" autocomplete="off">
      </td>
      <td class="align-middle">
        <input type="text" class="form-control form-control-sm text-bold text-dark text-right qtyRow" name="qty{{$item->first()->id}}[]" id="qtyRow${newNum}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
      </td>
      <td class="align-middle">
        <input type="text" class="form-control form-control-sm text-bold text-dark text-center tipeRow" name="tipe{{$item->first()->id}}[]" id="tipeRow${newNum}" autocomplete="off">
      </td>
      <td class="align-middle">
        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right hargaRow" name="harga{{$item->first()->id}}[]" id="hargaRow${newNum}">
      </td>
      <td class="text-right align-middle">
        <input type="text" name="jumlah{{$item->first()->id}}[]" id="jumlahRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlahRow">
      </td>
      <td class="align-middle" style="width: 90px">
        <input type="text" name="diskon{{$item->first()->id}}[]" id="diskonRow${newNum}" class="form-control form-control-sm text-bold text-dark text-right diskonRow" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9, tanda +, dan tanda koma" autocomplete="off">
      </td>
      <td class="text-right align-middle" style="width: 110px">
        <input type="text" name="diskonRp{{$item->first()->id}}[]" id="diskonRpRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right diskonRpRow">
      </td>
      <td class="text-right align-middle">
        <input type="text" name="netto{{$item->first()->id}}[]" id="nettoRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right nettoRow">
      </td>
      <td align="center" class="align-middle">
        <a href="#" class="icRemoveRow" id="icRemoveRow${newNum}">
          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
        </a>
      </td>
    </tr>
  `;

  $(tablePO).append(newTr);
  jumBaris.value = +jumBaris.value + 1;
  const newRow = document.getElementById(newNum);
  const brgRow = document.getElementById("nmBrgRow"+newNum);
  const kodeRow = document.getElementById("kdBrgRow"+newNum);
  const tglReturRow = document.getElementById("tglReturRow"+newNum);
  const qtyRow = document.getElementById("qtyRow"+newNum);
  const tipeRow = document.getElementById("tipeRow"+newNum);
  const hargaRow = document.getElementById("hargaRow"+newNum);
  const jumlahRow = document.getElementById("jumlahRow"+newNum);
  const diskonRow = document.getElementById("diskonRow"+newNum);
  const diskonRpRow = document.getElementById("diskonRpRow"+newNum);
  const nettoRow = document.getElementById("nettoRow"+newNum);
  const hapusRow = document.getElementById("icRemoveRow"+newNum);
  kodeRow.focus();
  // document.getElementById("submitRT"+'{{$item->first()->id}}').tabIndex = tab++;

  /** Tampil Harga **/
  kodeRow.addEventListener("keyup", displayHargaRow);
  brgRow.addEventListener("keyup", displayHargaRow);
  kodeRow.addEventListener("blur", displayHargaRow);
  brgRow.addEventListener("blur", displayHargaRow);

  $('.datepickerRow').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    language: 'id',
  });

  function displayHargaRow(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qtyRow.removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeRow.value = '{{ $br->id }}';
        brgRow.value = '{{ $br->nama }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeRow.value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipeRow.value = '{{ $hb->hargaBarang->tipe }}';
        hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
      }
    @endforeach

    qtyRow.value = '';
  }

  tglReturRow.addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4)
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);

    tglReturRow.value = value;
  });

  qtyRow.addEventListener("keyup", function(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlahRow.value.replace(/\./g, ""));
      jumlahRow.value = "";
      diskonRpRow.value = "";
      nettoRow.value = "";
    }
    else {
      netPast = +nettoRow.value.replace(/\./g, "");
      jumlahRow.value = addCommas(e.target.value * hargaRow.value.replace(/\./g, ""));
      if(diskonRow.value != "") {
        var angkaDiskon = hitungDiskon(diskonRow.value)
        diskonRpRow.value = addCommas((angkaDiskon * jumlahRow.value.replace(/\./g, "") / 100).toFixed(0));
      }

      nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
      checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    }
  });

  tipeRow.addEventListener("keyup", displayTipeRow);
  tipeRow.addEventListener("blur", displayTipeRow);

  function displayTipeRow(e) {
      if(e.target.value == "") {
        subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
        hargaRow.value = "";
        jumlahRow.value = "";
        diskonRpRow.value = "";
        nettoRow.value = "";
      }

      @foreach($harga as $hb)
        if(('{{ $hb->id_barang }}' == kodeRow.value) && ('{{ $hb->hargaBarang->tipe }}' == e.target.value)) {
            hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
            jumlahRow.value = addCommas(+hargaRow.value.replace(/\./g, "") * qtyRow.value);

            netPast = +nettoRow.value.replace(/\./g, "");
            if(diskonRow.value != "") {
                var angkaDiskon = hitungDiskon(diskonRow.value)
                diskonRpRow.value = addCommas((angkaDiskon * jumlahRow.value.replace(/\./g,"") / 100).toFixed(0));
            }

            nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
            checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
        }
      @endforeach
    }

    diskonRow.addEventListener("keyup", displayDiskonRow);
    diskonRow.addEventListener("blur", displayDiskonRow);

  function displayDiskonRow(e) {
    if(e.target.value == "") {
      netPast = nettoRow.value.replace(/\./g, "");
      nettoRow.value = addCommas(+nettoRow.value.replace(/\./g, "") + +diskonRpRow.value.replace(/\./g, ""));
      checkSubtotal(netPast, nettoRow.value.replace(/\./g, ""));
      diskonRpRow.value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      netPast = +nettoRow.value.replace(/\./g, "");
      diskonRpRow.value = addCommas((angkaDiskon * jumlahRow.value.replace(/\./g, "") / 100).toFixed(0));
      nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
      checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    }
  }

  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    if(qtyRow.value != "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
    }

    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $(tablePO).find('tr:last').attr("id");
    console.log(lastNum);
    var numRow;
    if(+curNum < +lastNum) {
      $(newRow).remove();
      var j = curNum;
      var selisih = +lastNum - +curNum;
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
    if(jumBaris.value > 3)
      document.getElementById("kodeRow"+numRow).focus();
    else
      kodeBarang[2 + +jumAwal.value].focus();
  })

  /** Autocomplete Nama  Barang **/
  $(function() {
    var idBarang = [];
    var nmBarang = [];
    @foreach($barang as $b)
      idBarang.push('{{ $b->id }}');
      nmBarang.push('{{ $b->nama }}');
    @endforeach

    var tipeHrg = '{{ implode(",", $hrg) }}';
    tipeHrg = tipeHrg.split(',');

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

    $(tipeRow).on("keydown", function(event) {
          if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
              event.preventDefault();
          }
      })
    .autocomplete({
              minLength: 0,
              source: function(request, response) {
                  // delegate back to autocomplete, but extract the last term
                  response($.ui.autocomplete.filter(tipeHrg, extractLast(request.term)));
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

for(let i = retur; i < kodeBarang.length; i++) {
  brgNama[i].addEventListener("keyup", displayHarga) ;
  kodeBarang[i].addEventListener("keyup", displayHarga);
  brgNama[i].addEventListener("blur", displayHarga) ;
  kodeBarang[i].addEventListener("blur", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipe[i].value = '{{ $hb->hargaBarang->tipe }}';
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
      }
    @endforeach

    qty[i].value = '';
  }
}

for(let i = 0; i < tglRetur.length; i++) {
  tglRetur[i].addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4)
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);

    tglRetur[i].value = value;
  });
}

for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("keyup", function(e) {
    if(e.target.value == "") {
      // total[i].value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      diskon[i].value = "";
      diskonRp[i].value = "";
      netto[i].value = "";
    }
    else {
      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
  });
}

for(let i = 0; i < tipe.length; i++) {
    tipe[i].addEventListener("keyup", displayTipe);
    tipe[i].addEventListener("blur", displayTipe);

    function displayTipe(e) {
        if(e.target.value == "") {
            subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
            harga[i].value = "";
            jumlah[i].value = "";
            diskonRp[i].value = "";
            netto[i].value = "";
        }

        @foreach($harga as $hb)
        if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->hargaBarang->tipe }}' == e.target.value)) {
            harga[i].value = addCommas('{{ $hb->harga_ppn }}');
            jumlah[i].value = addCommas(+harga[i].value.replace(/\./g, "") * qty[i].value);

            netPast = +netto[i].value.replace(/\./g, "");
            if(diskon[i].value != "") {
                var angkaDiskon = hitungDiskon(diskon[i].value)
                diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g,"") / 100).toFixed(0));
            }

            netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
            checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
        }
        @endforeach
    }
}

for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("keyup", displayDiskon);
  diskon[i].addEventListener("blur", displayDiskon);

  function displayDiskon(e) {
    if(e.target.value == "") {
      netPast = netto[i].value.replace(/\./g, "");
      netto[i].value = addCommas(+netto[i].value.replace(/\./g, "") + +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, netto[i].value.replace(/\./g, ""));
      diskonRp[i].value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      netPast = +netto[i].value.replace(/\./g, "");
      diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    // totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    // grandtotal.value = totalNotPPN.value;
  }
}

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    const delRow = document.getElementById(i);
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $('tbody tr:last').prev().prev().prev().find('td:first-child').text();

    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
    $(delRow).remove();
    for(let j = +curNum; j < (+lastNum + +jumBaris.value); j++) {
      $(tablePO).find('tr:nth-child('+j+') td:first-child').html(j);
    }

    jumAwal.value -= 1;
  });
}

for(let i = 0; i < hapusBiasa.length; i++) {
  hapusBiasa[i].addEventListener("click", function (e) {
    if(qty[+i + +jumAwal.value].value != "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[+i + +jumAwal.value].value.replace(/\./g, ""));
    }

    for(let j = (+i + +jumAwal.value); j < (+hapusBiasa.length + +jumAwal.value); j++) {
      if(j+1 != (+hapusBiasa.length + +jumAwal.value)) {
        netto[j].value = netto[j+1].value;
        diskonRp[j].value = diskonRp[j+1].value;
        diskon[j].value = diskon[j+1].value;
        jumlah[j].value = jumlah[j+1].value;
        harga[j].value = harga[j+1].value;
        qty[j].value = qty[j+1].value;
        tglRetur[j].value = tglRetur[j+1].value;
        brgNama[j].value = brgNama[j+1].value;
        kodeBarang[j].value = kodeBarang[j+1].value;
      } else {
        netto[j].value = '';
        diskonRp[j].value = '';
        diskon[j].value = '';
        jumlah[j].value = '';
        harga[j].value = '';
        qty[j].value = '';
        tglRetur[j].value = '';
        brgNama[j].value = '';
        kodeBarang[j].value = '';
      }
    }

    // $(this).parents('tr').next().find('input').val('');
    for(let j = 0; j < kodeBarang.length; j++) {
      if(kodeBarang[j].value == '') {
        kodeBarang[j].focus();
        break;
      }
    }
  });
}

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  angka = angka.replace(/\,/g, ".");
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon =  ((totDiskon - 100) * -1);
  return totDiskon;
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

/** Inputan hanya bisa angka dan plus **/
function angkaPlus(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && charCode != 43 && charCode != 44  && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= diskon.length; i++) {
      if(inputan == i)
        $(diskon[inputan-1]).tooltip('show');
    }
    return false;
  }
  return true;
}

function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
    // totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
    // totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") + (+Now - +Past));
  }
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

/** Autocomplete Input Kode PO **/
$(function() {
  var kode = [];
  var nama = [];
  @foreach($barang as $b)
    kode.push('{{ $b->id }}');
    nama.push('{{ $b->nama }}');
  @endforeach

  var tipeHarga = '{{ implode(",", $hrg) }}';
  tipeHarga = tipeHarga.split(',');

  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Nama Barang --*/
  $(brgNama).on("keydown", function(event) {
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

  $(tipe).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
    }
  })
    .autocomplete({
        minLength: 0,
        source: function(request, response) {
            // delegate back to autocomplete, but extract the last term
            response($.ui.autocomplete.filter(tipeHarga, extractLast(request.term)));
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
