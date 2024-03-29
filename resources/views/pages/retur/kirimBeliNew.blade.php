@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Terima Retur {{ $item->first()->id}}</h1>
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
            <form action="" method="" id="kirimRB">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier BM -->
               <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor Retur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="1" readonly class="form-control form-control-sm text-bold text-dark" name="kode" value="{{ $item->first()->id }}" >
                      </div>
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal Retur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="2" readonly class="form-control datepicker form-control-sm text-bold text-dark" name="tanggal" value="{{ \Carbon\Carbon::parse($item->first()->tanggal)->format('d-M-y') }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" tabindex="5" name="namaCust" readonly class="form-control form-control-sm text-bold text-dark" value="{{ $item->first()->supplier->nama }}" />
                    <input type="hidden" name="kodeCustomer" value="{{ $item->first()->id_supplier }}">
                  </div>
                  <input type="hidden" name="jumRB" id="jumRB" value="{{ $retur->count() }}">
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->

              <!-- Tabel Data Detil BM-->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <tr class="text-center">
                    <th class="align-middle" style="width: 40px">No</th>
                    <th class="align-middle" style="width: 90px">Kode Barang</th>
                    <th class="align-middle" style="width: 335px">Nama Barang</th>
                    <th class="align-middle" style="width: 60px">Qty Retur</th>
{{--                    <th class="align-middle" style="width: 100px">Tgl. Terima</th>--}}
{{--                    <th class="align-middle" style="width: 70px">Qty Terima</th>--}}
{{--                    <th class="align-middle" style="width: 70px">Qty Ditolak</th>--}}
{{--                    <th class="align-middle" style="width: 70px">Potong Tagihan</th>--}}
{{--                    <th class="align-middle" style="width: 50px">Qty Kurang</th>--}}
                    <th class="align-middle"style="width: 50px">Hapus</th>
                  </tr>
                </thead>
                <tbody id="tablePO" class="table-ar">
                  @php $i = 1; $totalTerima = 0; $totalBatal = 0; $totalPotong = 0; $kurang = 0; $totalDRT = 0; @endphp
                  @foreach($retur as $d)
                    @php
                      $totalTerima = 0; $totalBatal = 0; $totalPotong = 0; $kode = $item->first()->id; $no = 0;
                      $returTerima = App\Models\DetilRT::join('returterima', 'returterima.id',
                                    'detilrt.id_terima')->where('id_retur', $item->first()->id)
                                    ->where('id_barang', $d->id_barang)->get();
                      $kurang = $d->qty_retur;
                    @endphp
                    @if($returTerima->count() != 0)
                      @foreach($returTerima as $dr)
                        <tr class="table-modal-first-row text-dark text-bold" id="{{ $i-1 }}">
                          <td class="text-center align-middle">{{ $i }}</td>
                          <td class="text-center align-middle">
                            <input type="hidden" name="kodeTerima" value="{{ $dr->id_terima }}">
                            <input type="text" class="form-control-plaintext form-control-sm text-bold text-dark text-center kodeDetil" name="kodeDetil[]" readonly value="{{ $dr->id_barang }}">
                            {{-- @if((($no == 0) && ($dr->id_barang == $d->id_barang)) || (($no > 0) && ($dr->id_barang != $returTerima[$no-1]->id_barang))) --}}
                            @if(($dr->id_barang == $d->id_barang) && ($dr->qty_terima + $dr->qty_batal + $dr->potong == $d->qty_retur))
                              <input type="hidden" name="kodeBarang[]" value="{{ $dr->id_barang }}">
                              <input type="hidden" name="qty[]" value="{{ $d->qty_retur }}">
                              <input type="hidden" name="terima[]" value="">
                              <input type="hidden" name="batal[]" value="">
                              <input type="hidden" name="tgl[]" value="">
                            @endif
                          </td>
                          <td class="align-middle">{{ $dr->barang->nama }}</td>
                          <td class="text-center align-middle">
                            <input type="text" name="qtyDetil[]" id="qtyDetil{{$dr->id_barang}}" class="form-control-plaintext form-control-sm text-bold text-dark text-center qtyDetil" onkeypress="return angkaSaja(event)" autocomplete="off" value ="{{ $d->qty_retur }}" readonly>
                          </td>
{{--                          <td class="text-center">--}}
{{--                            <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglDetil" name="tglDetil[]" id="tglDetil{{$dr->id_barang}}" autocomplete="off" @if($dr->tanggal != '') value ="{{ \Carbon\Carbon::parse($dr->tanggal)->format('d-m-Y') }}" @endif>--}}
{{--                          </td>--}}
{{--                          <td class="text-right">--}}
{{--                            <input type="text" name="terimaDetil[]" id="terimaDetil{{$dr->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right terimaDetil" onkeypress="return angkaSaja(event)" autocomplete="off" @if($dr->qty_terima != 0) value ="{{ $dr->qty_terima }}" @endif>--}}
{{--                          </td>--}}
{{--                          <td class="text-right">--}}
{{--                            <input type="text" name="batalDetil[]" id="batalDetil{{$dr->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right batalDetil" onkeypress="return angkaSaja(event)" autocomplete="off" @if($dr->qty_batal != 0) value ="{{ $dr->qty_batal }}" @endif>--}}
{{--                          </td>--}}
{{--                          <td class="text-right align-middle">{{ $dr->potong != 0 ? $dr->potong : '' }}</td>--}}
                          @php $kurang -= ($dr->qty_terima + $dr->qty_batal + $dr->potong); @endphp
{{--                          <td class="text-right align-middle">{{ number_format($kurang, 0, "", ".") }}</td>--}}
                          <td align="center" class="align-middle">
                            <a href="#" class="icRemove">
                              <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                            </a>
                          </td>
                        </tr>
                        @php $i++; $totalTerima += $dr->qty_terima; $totalBatal += $dr->qty_batal;
                              $totalPotong += $dr->potong; $no++; @endphp
                      @endforeach
                      @php $totalDRT += $returTerima->count(); @endphp
                    @endif
                    @if(($retur->first()->returBeli->status != 'LENGKAP') && ($d->qty_retur != $totalTerima + $totalBatal + $totalPotong))
                      <tr class="text-dark text-bold" id="{{ $i-1 }}">
                        <td class="text-center align-middle">{{ $i }}</td>
                        <td class="text-center align-middle">
                          <input type="hidden" name="kurangAwal" class="kurangAwal" value="{{ $kurang }}">
                          <input type="text" class="form-control form-control-sm text-bold text-dark text-center kodeBarang" name="kodeBarang[]" required value="{{ $d->id_barang }}">
                        </td>
                        <td class="align-middle">
                          <input type="text" class="form-control form-control-sm text-bold text-dark namaBarang" name="namaBarang[]" required value="{{ $d->barang->nama }}">
                        </td>
                        <td class="align-middle text-center">
                          <input type="text" name="qty[]" id="qty{{$d->id_barang}}" class="form-control form-control-sm text-bold text-dark text-center qty" onkeypress="return angkaSaja(event)" autocomplete="off" value ="{{ $d->qty_retur }}" >
                        </td>
{{--                        <td class="text-center align-middle">--}}
{{--                          <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl[]" id="tglBayar{{$d->id_barang}}" placeholder="DD-MM-YYYY" autocomplete="off">--}}
{{--                        </td>--}}
{{--                        <td class="text-right align-middle">--}}
{{--                          <input type="text" name="terima[]" id="bayar{{$d->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right kirimModal" onkeypress="return angkaSaja(event)" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">--}}
{{--                        </td>--}}
{{--                        <td class="text-right align-middle">--}}
{{--                          <input type="text" name="batal[]" id="batal{{$d->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right batalModal" onkeypress="return angkaSaja(event)" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">--}}
{{--                        </td>--}}
{{--                        <td class="text-right align-middle"></td>--}}
{{--                        <td class="text-right align-middle">--}}
{{--                          <input type="text" name="kurang[]" id="kurang{{$d->id_barang}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurang">--}}
{{--                        </td>--}}
                        <td align="center" class="align-middle">
                          <a href="#" class="icRemove" >
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1" @if($returTerima->count() != 0) hidden @endif></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endif

                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="text-right text-bold text-dark" style="font-size: 16px">
                    <td colspan="3" class="text-center">Total</td>
                    <td class="text-center">{{ number_format($retur->sum('qty_retur'), 0, "", ".") }}</td>
{{--                    <td class="text-right">{{ number_format($totalTerima, 0, "", ".") }}</td>--}}
{{--                    <td class="text-right">{{ number_format($totalBatal, 0, "", ".") }}</td>--}}
{{--                    <td class="text-right">{{ number_format($totalPotong, 0, "", ".") }}</td>--}}
{{--                    <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>--}}
                    <td></td>
                  </tr>
                </tfoot>
              </table>
              <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $totalDRT + $retur->count() }}">
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              @if($item->first()->status == 'INPUT')
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <button type="submit" class="btn btn-success btn-block text-bold" id="submitRB" onclick="return checkLimit(event)">Update</button>
                  </div>
                  <div class="col-2">
                    <button type="submit" class="btn btn-outline-dark btn-block text-bold" formaction="{{ route('retur-selesai-beli', $item->first()->id) }}" formmethod="GET">Selesai Retur</button>
{{--                    <button type="submit" class="btn btn-success btn-block text-bold" id="submitRB" onclick="return checkLimit(event)">Submit</button>--}}
                    {{-- formaction="{{ route('retur-beli-process') }}" formmethod="POST" --}}
                  </div>
                  <div class="col-2">
                    <a href="{{ route('retur-potong-beli', $item->first()->id) }}" id="backRJ" class="btn btn-outline-primary btn-block text-bold">Potong Tagihan</a>
                  </div>
                  <div class="col-2">
                    {{-- <button type="submit" class="btn btn-outline-danger btn-block text-bold" formaction="{{ route('retur-beli-batal', $item->first()->id) }}" formmethod="POST">Batal Retur</button> --}}
                    <a href="" data-toggle="modal" data-target="#batalRetur" class="btn btn-outline-danger btn-block text-bold">Batal Retur</a>
                  </div>
                  <div class="col-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-block text-bold">Kembali</a>
                  </div>
                </div>
              @else
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                  </div>
                </div>
              @endif
              <!-- End Button Submit dan Reset -->

              <div class="modal" id="batalRetur" tabindex="-1" role="dialog" aria-labelledby="batalRetur" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title">Batal Retur Supplier <b>{{$item->first()->id}}</b></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                          <label for="kode" class="col-2 col-form-label text-bold">Status</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2">
                            <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="statusUbah" value="BATAL">
                          </div>
                        </div>
                        <div class="form-group subtotal-so">
                          <label for="keterangan" class="col-form-label">Keterangan</label>
                          <input type="text" class="form-control" name="keterangan"
                          id="keterangan" data-toogle="tooltip" data-placement="bottom" title="Form keterangan harus diisi">
                        </div>
                        <hr>
                        <div class="form-row justify-content-center">
                          <div class="col-3">
                            <button type="submit" class="btn btn-success btn-block text-bold" id="btn" onclick="return checkEditable()">Simpan</button>
                            {{-- formaction="{{ route('so-status', $item->id) }}" formmethod="POST" --}}
                          </div>
                          <div class="col-3">
                            <button button type="button" class="btn btn-outline-secondary btn-block text-bold" data-dismiss="modal">Batal</button>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal" id="modalNotif" tabindex="-1" role="dialog" aria-labelledby="modalNotif" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title text-bold">Notifikasi Qty Terima dan Ditolak</h4>
                    </div>
                    <div class="modal-body text-dark">
                      <h5><b>Jumlah Qty Terima + Ditolak</b> tidak bisa melebihi <b>Jumlah Qty Retur</b>. Silahkan ubah jumlah Qty Terima atau Ditolak yang berwarna <b>Merah</b></h5>
                    </div>
                  </div>
                </div>
              </div>
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

const qtyDetil = document.querySelectorAll('.qtyDetil');
const terimaDetil = document.querySelectorAll('.terimaDetil');
const batalDetil = document.querySelectorAll('.batalDetil');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const kirimRB = document.getElementById("kirimRB");
const qty = document.querySelectorAll('.qty');
const tglBayar = document.querySelectorAll('.tglBayar');
const kirimModal = document.querySelectorAll('.kirimModal');
const batalModal = document.querySelectorAll('.batalModal');
const kurang = document.querySelectorAll('.kurang');
const kurangAwal = document.querySelectorAll('.kurangAwal');
// const hapusBaris = document.querySelectorAll(".icRemoveDetil");
const hapusBaris = document.querySelectorAll(".icRemove");
const btnCetak = document.querySelectorAll('.btnCetak');
const kodeRB = document.getElementById('angka');

kirimRB.addEventListener("keypress", checkEnter);

function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
}

for(let i = 0; i < kodeBarang.length; i++) {
  brgNama[i].addEventListener("keyup", displayNama) ;
  kodeBarang[i].addEventListener("keyup", displayNama);
  brgNama[i].addEventListener("blur", displayNama) ;
  kodeBarang[i].addEventListener("blur", displayNama);

  function displayNama(e) {
    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
      }
    @endforeach
  }
}

for(let i = 0; i < tglBayar.length; i++) {
  tglBayar[i].addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4)
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);

    tglBayar[i].value = value;
  });
}

for(let i = 0; i < kirimModal.length; i++) {
  kirimModal[i].addEventListener("change", function(e) {
    if(kurang[i].value == '')
      kurang[i].value = kurangAwal[i].value - e.target.value;
    else
      kurang[i].value -= e.target.value;
  });

  kirimModal[i].addEventListener("blur", function(e) {
    if(e.target.value == '')
      tglBayar[i].removeAttribute('required');
    else
      tglBayar[i].setAttribute('required', true);
  });
}

for(let i = 0; i < batalModal.length; i++) {
  batalModal[i].addEventListener("change", function(e) {
    if(kurang[i].value == '')
      kurang[i].value = kurangAwal[i].value - e.target.value;
    else
      kurang[i].value -= e.target.value;
  });
}

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    const delRow = document.getElementById(i);
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $('tbody tr:last').find('td:first-child').text();

    $(delRow).remove();
    for(let j = +curNum; j < +lastNum; j++) {
      $(tablePO).find('tr:nth-child('+j+') td:first-child').html(j);
    }

    jumBaris.value -= 1;
  });
}

for(let i = 0; i < btnCetak.length; i++) {
  btnCetak[i].addEventListener("click", function(e) {
    const printFrame = document.getElementById("frameCetak"+i).contentWindow;

    printFrame.window.onafterprint = function(e) {
      alert('ok');
    }

    printFrame.window.print();
    // window.print();
  });
}

function checkLimit(e) {
  document.getElementById("submitRB").removeAttribute('data-toggle');
  document.getElementById("submitRB").removeAttribute('data-target');
  var cek = 0; var urut = []; var urutDetil = [];
  for(let i = 0; i < kirimModal.length; i++) {
    if((+kirimModal[i].value + +batalModal[i].value) > +qty[i].value) {
      cek = 1;
      urut.push(i);
      // kirimModal[i].style.borderColor = "red";
      // kirimModal[i].style.borderWidth = "2px";
    }
  }

  for(let i = 0; i < terimaDetil.length; i++) {
    if((+terimaDetil[i].value + +batalDetil[i].value) > +qtyDetil[i].value) {
      cek = 1;
      urutDetil.push(i);
      // kirimModal[i].style.borderColor = "red";
      // kirimModal[i].style.borderWidth = "2px";
    }
  }

  if(cek == 1) {
    document.getElementById("submitRB").dataset.toggle = "modal";
    document.getElementById("submitRB").dataset.target = "#modalNotif";
    for(let i = 0; i < urut.length; i++) {
      $(kirimModal[urut[i]]).closest('td').css("border-color", "red");
      $(kirimModal[urut[i]]).closest('td').css("border-width", "3px");
      $(batalModal[urut[i]]).closest('td').css("border-color", "red");
      $(batalModal[urut[i]]).closest('td').css("border-width", "3px");
    }
    for(let i = 0; i < urutDetil.length; i++) {
      $(terimaDetil[urutDetil[i]]).closest('td').css("border-color", "red");
      $(terimaDetil[urutDetil[i]]).closest('td').css("border-width", "3px");
      $(batalDetil[urutDetil[i]]).closest('td').css("border-color", "red");
      $(batalDetil[urutDetil[i]]).closest('td').css("border-width", "3px");
    }
    return false;
  }
  else {
    document.getElementById("submitRB").formMethod = "POST";
    document.getElementById("submitRB").formAction = "{{ route('retur-beli-process') }}";
  }
}

function checkEditable() {
  const ket = document.getElementById("keterangan");
  if(ket.value == "") {
    $(ket).tooltip('show');
    return false;
  }
  else {
    document.getElementById("btn").formMethod = "POST";
    document.getElementById("btn").formAction = '{{ route('retur-beli-batal', $item->first()->id) }}';
  }
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, nomor, inputan, jenis) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
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
});

</script>
@endpush
