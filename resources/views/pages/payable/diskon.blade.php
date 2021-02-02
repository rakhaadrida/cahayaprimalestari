@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Account Payable</h1>
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
              {{-- <div class="container so-container"> 
                <div class="form-group row" style="margin-top: -10px">
                  <label for="bulan" class="col-2 col-form-label text-right text-bold">Nama Bulan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="bulan" id="bulan">
                  </div>
                  <label for="status" class="col-auto col-form-label text-right text-bold">Status</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="status" id="status">
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-right text-bold">Dari Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ml-3"> s / d </label>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1 ml-1" name="tglAkhir" >
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('ap-home') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr> --}}
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              @php $subtotal = 0; @endphp
              @foreach($items as $item)
                @if($item->id == $items[0]->id)
                  <div class="container so-update-container text-dark" style="margin-top: -10px; margin-bottom: -15px">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row">
                          <label for="kode" class="col-2 form-control-sm text-bold  text-right mt-1">Nomor Faktur</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2">
                            <input type="text" name="kode" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->id_faktur }}" >
                          </div>
                        </div>
                      </div> 
                      <div class="col" style="margin-left: -520px">
                        <div class="form-group row">
                          <label for="tanggal" class="col-4 form-control-sm text-bold text-right mt-1">Nama Supplier</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-7">
                            <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->supplier->nama }} ({{ $item->id_supplier }})" >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row customer-detail">
                          <label for="tanggal" class="col-2 form-control-sm text-bold text-right mt-1">Nomor BM</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2">
                            <input type="text" name="BM{{$item->id}}" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->id }}" >
                          </div>
                        </div>
                      </div> 
                      <div class="col" style="margin-left: -520px">
                        <div class="form-group row customer-detail">
                          <label for="tanggal" class="col-4 form-control-sm text-bold mt-1 text-right">Status</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-3">
                            <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->status }}" >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row customer-detail">
                          <label for="tanggal" class="col-2 form-control-sm text-bold text-right mt-1">Tanggal BM</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2">
                            <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}" >
                          </div>
                        </div>
                      </div>
                      <div class="col" style="margin-left: -520px">
                        <div class="form-group row customer-detail">
                          <label for="tempo" class="col-4 form-control-sm text-bold mt-1 text-right">Tempo</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2">
                            <input type="text" tabindex="1" class="form-control form-control-sm text-bold text-dark mt-1" name="tempo" id="tempo" onkeypress="return angkaSaja(event, 'TEMPO')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" value="{{ $item->tempo != null ? $item->tempo : '' }}" autofocus>
                          </div>
                          <span class="col-form-label text-bold"> Hari</span>
                        </div>
                      </div>
                    </div>
                  </div>
                @else
                  <div class="container so-update-container text-dark" style="margin-top: -10px; margin-bottom: -15px">
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row">
                          <label for="kode" class="col-2 form-control-sm text-bold  text-right mt-1">Nomor BM</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2">
                            <input type="text" name="BM{{$item->id}}" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->id }}" >
                          </div>
                        </div>
                      </div> 
                      <div class="col" style="margin-left: -520px">
                        <div class="form-group row">
                          <label for="tanggal" class="col-4 form-control-sm text-bold text-right mt-1">Status</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-7">
                            <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->status }}" >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group row customer-detail">
                          <label for="tanggal" class="col-2 form-control-sm text-bold text-right mt-1">Tanggal BM</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2">
                            <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}" >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif

                <!-- Tabel Data Detil PO -->
                <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                  <thead class="text-center text-bold text-dark">
                    <tr>
                      <td rowspan="2" style="width: 30px" class="align-middle">No</td>
                      <td rowspan="2" style="width: 90px" class="align-middle">Kode Barang</td>
                      <td rowspan="2" @if(Auth::user()->roles == 'ADMIN') style="width: 320px" @endif class="align-middle">Nama Barang</td>
                      <td rowspan="2" style="width: 55px" class="align-middle">Qty</td>
                      <td rowspan="2" style="width: 100px" class="align-middle">Harga</td>
                      <td rowspan="2" style="width: 130px" class="align-middle">Jumlah</td>
                      <td colspan="2">Diskon</td>
                      <td rowspan="2" style="width: 130px" class="align-middle">HPP</td>
                    </tr>
                    <tr>
                      <td style="width: 95px">%</td>
                      <td style="width: 130px">Rupiah</td>
                    </tr>
                  </thead>
                  <tbody>
                    @php $i = 1; $tab = 1; @endphp
                    @foreach($item->detilbm as $detil)
                      <tr class="text-dark text-bold">
                        <td align="center" class="align-middle">{{ $i }}</td>
                        <td align="center" class="align-middle">{{ $detil->id_barang }} </td>
                        <td class="align-middle">{{ $detil->barang->nama }}</td>
                        <td align="right" class="align-middle">{{ $detil->qty }}</td>
                        <td align="right" class="align-middle">
                          {{ number_format($detil->harga, 0, "", ".") }}
                        </td>
                        <td align="right" class="align-middle">
                          <input type="text" name="jumlah[]" id="jumlah" class="form-control form-control-sm text-bold text-dark text-right jumlah" value="{{ number_format(($detil->qty * $detil->harga), 0, "", ".") }}">
                        </td>
                        <td align="right">
                          <input type="text" tabindex="{{ $tab++ }}" name="dis{{$item->id}}{{$detil->id_barang}}" id="diskon" class="form-control form-control-sm text-bold text-dark text-right diskon" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9, tanda +, dan koma" autocomplete="off" @if($detil->diskon != null) value="{{ str_replace(".", ",", $detil->diskon) }}" @endif required>
                          <input type="hidden" name="diskon{{$item->id}}{{$detil->id_barang}}" class="disAngka" @if($detil->diskon != null) value="{{ $detil->disPersen }}" @endif>
                        </td>
                        @if($detil->diskon != null)
                          @php 
                            $diskon = 100;
                            $detil->diskon = str_replace(",", ".", $detil->diskon);
                            $arrDiskon = explode("+", $detil->diskon);
                            for($j = 0; $j < sizeof($arrDiskon); $j++) {
                              $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                            } 
                            $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                          @endphp
                        @endif
                        <td align="right">
                          <input type="text" name="disRp{{$detil->id_barang}}" id="diskonRp" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right diskonRp" @if($detil->diskon != null) 
                          value="{{ number_format((($detil->qty * $detil->harga) * str_replace(",", ".", $diskon)) / 100, 0, "", ".") }}" 
                          {{-- value="{{ $diskon }}" --}}
                          @endif>
                        </td>
                        <td align="right">
                          <input type="text" name="hpp{{$item->id}}{{$detil->id_barang}}" id="netto" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto" @if($detil->diskon != null) 
                          value="{{ number_format(($detil->qty * $detil->harga) - 
                          ((($detil->qty * $detil->harga) * str_replace(",", ".", $diskon)) / 100), 0, "", ".") }}" @endif>
                        </td>
                        @if($detil->diskon != null)
                          @php 
                            $subtotal += ($detil->qty * $detil->harga) - 
                            ((($detil->qty * $detil->harga) * str_replace(",", ".", $diskon)) / 100);
                          @endphp
                        @endif
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  </tbody>
                </table>

                @if($item->id == $items[$items->count() - 1]->id)
                  <div class="form-group row justify-content-end subtotal-so">
                    <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2 mr-1">
                      <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal, 0, "", ".") }}" />
                    </div>
                  </div>
                  <div class="form-group row justify-content-end total-so">
                    <label for="ppn" class="col-2 col-form-label text-bold text-right text-dark">Potongan Lain-lain</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2 mr-1">
                      <input type="text" name="potongan" id="potongan" class="form-control col-form-label-sm text-bold text-dark text-right mt-1 potongan" placeholder="Input Potongan" onkeypress="return angkaSaja(event, 'OKE')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9"  autocomplete="off" value="{{ number_format($potongan[0]->potongan, 0, "", ".") }}" />
                    </div>
                  </div>
                  <div class="form-group row justify-content-end total-so">
                    <label for="ppn" class="col-3 col-form-label text-bold text-right text-dark">Total Sebelum PPN</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2 mr-1">
                      <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal - $items[0]->potongan, 0, "", ".") }}" />
                    </div>
                  </div>
                  <div class="form-group row justify-content-end total-so">
                    <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2 mr-1">
                      <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="0" />
                    </div>
                  </div>
                  <div class="form-group row justify-content-end grandtotal-so">
                    <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                    <span class="col-form-label text-bold">:</span>
                    <div class="col-2 mr-1">
                      <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" value="{{ number_format($subtotal - $items[0]->potongan, 0, "", ".") }}" />
                      {{-- value="{{number_format($subtotal + ($subtotal * 10 / 100),0,"",".")}}" --}}
                    </div>
                  </div>
                @endif
                <hr>
                <!-- End Tabel Data Detil PO -->

                @if($item->id == $items[$items->count() - 1]->id)
                  <!-- Button Submit dan Reset -->
                  <div class="form-row justify-content-center">
                    <div class="col-2">
                      <button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('ap-process') }}" formmethod="POST" class="btn btn-success btn-block text-bold">Simpan</button>
                    </div>
                    <div class="col-2">
                      <a href="{{ url()->previous() }}" tabindex="{{$tab += 2 }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                    </div>
                  </div>
                  {{-- <div class="form-row justify-content-center">
                    <div class="col-2">
                      <button type="submit" formaction="{{ route('ap') }}" formmethod="GET" class="btn btn-outline-primary btn-block text-bold">Kembali</button>
                    </div>
                  </div> --}}
                  <!-- End Button Submit dan Reset -->
                @endif
              @endforeach
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
const tempo = document.getElementById("tempo");
const jumlah = document.querySelectorAll(".jumlah");
const diskon = document.querySelectorAll(".diskon");
const disAngka = document.querySelectorAll(".disAngka");
const diskonRp = document.querySelectorAll(".diskonRp");
const netto = document.querySelectorAll(".netto");
const subtotal = document.getElementById('subtotal');
const potongan = document.getElementById('potongan');
const totalNotPPN = document.getElementById('totalNotPPN');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');

potongan.addEventListener('keyup', formatNominal);
potongan.addEventListener('keyup', displayTotal);

/** Tampil Diskon Rupiah Otomatis **/
for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      netPast = netto[i].value.replace(/\./g, "");
      netto[i].value = "";
      checkSubtotal(netPast, netto[i].value.replace(/\./g, ""));
      diskonRp[i].value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      netPast = +netto[i].value.replace(/\./g, "");
      disAngka[i].value = angkaDiskon;
      diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +potongan.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
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
  totDiskon = ((totDiskon - 100) * -1).toFixed(2);
  return totDiskon;
}

/** Add Nominal Separators **/
function formatNominal(e){
  $(this).val(function(index, value) {
    return value
    .replace(/\D/g, "")
    .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    ;
  });
}

function displayTotal(e) {
  totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +e.target.value.replace(/\./g, ""));
  grandtotal.value = totalNotPPN.value;
}

/** Check Jumlah Netto onChange **/
function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
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

/** Inputan hanya bisa angka **/
function angkaSaja(evt, teks) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    if(teks == 'TEMPO')
      $(tempo).tooltip('show');
    else
      $(potongan).tooltip('show');

    return false;
  }
  return true;
}

/** Inputan hanya bisa angka dan plus **/
function angkaPlus(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && charCode != 43 && charCode != 44 && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= diskon.length; i++) {
      if(inputan == i)
        $(diskon[inputan-1]).tooltip('show');
    }
    return false;
  }
  return true;
}

</script>
@endpush