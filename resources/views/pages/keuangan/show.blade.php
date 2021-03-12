@extends('pages.keuangan.detail')
@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Laporan Keuangan</h1>
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

  <div class="row" style="overflow-x:auto;">
    <div class="card-body">
      <div class="table-responsive">
        <div class="card show">
          <div class="card-body">
            <form action="" method="">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">
                <div class="form-group row justify-content-center">
                  <label for="tahun" class="col-auto col-form-label text-bold">Tahun</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-1">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="tahun" id="tahun" value="{{$tahun}}" required>
                  </div>
                  <label for="bulan" class="col-auto col-form-label text-bold ">Bulan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" id="bulan" name="bulan" value="{{$bulan}}" autofocus required>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('lap-keu-show', ['tah' => $tahun, 'mo' => $month]) }}" formmethod="GET" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                  <div class="col-auto mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="6" formaction="{{ route('lap-keu') }}" formmethod="GET" class="btn btn-outline-danger btn-sm btn-block text-bold">Reset Filter</button>
                  </div>
                </div>   
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <div class="container">
                <div class="row justify-content-center">
                  <h4 class="text-bold text-dark">Laporan Keuangan {{$tahun}}</h4>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h5 class="text-bold text-dark">Bulan : {{$bulan}}</h5>
                </div>
                {{-- <div class="row justify-content-end" style="margin-top: -55px">
                  <div class="col-2">
                    <button type="submit" formaction="{{ route('lap-keu-show') }}" formmethod="POST"  class="btn btn-success btn-block text-bold">Download Excel</>
                  </div>
                </div> --}}
              </div>

              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($items->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
                <thead class="text-center text-bold text-dark">
                  <tr style="font-size: 14px">
                    <th style="width: 10px" class="align-middle">NO</th>
                    <th style="width: 100px" class="align-middle">SALES</th>
                    <th style="width: 60px" class="align-middle">DETAIL</th>
                    @foreach($jenis as $j)
                      <th style="width: 70px" class="align-middle">{{$j->nama}}</th>
                    @endforeach
                    <th style="width: 40px" class="align-middle">Grand Total</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php 
                    $no = 1; $subRevenue = 0; $subHPP = 0; $subRetur = 0; $subLaba = 0;
                    if(Auth::user()->roles == 'OFFICE02')
                      $sales = $salesOff;
                  @endphp
                  @forelse($sales as $s)
                    <tr class="text-dark">
                      <td align="center" class="align-middle"
                        @if(Auth::user()->roles == 'SUPER') rowspan="4" 
                        @else rowspan="3" 
                        @endif  
                        @if($no % 2 == 0) style="background-color: white" @endif>
                          {{ $no }}
                      </td>
                      <td class="align-middle"
                        @if(Auth::user()->roles == 'SUPER') rowspan="4" 
                        @else rowspan="3" 
                        @endif  
                        @if($no % 2 == 0) style="background-color: white" @endif>
                          {{ $s->nama }}
                      </td>
                      <td align="center" style="background-color: #f0ededda !important">Revenue</td>
                      @php $total = 0; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle" style="background-color: #f0ededda !important">
                        @foreach($items as $i)
                          @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                              {{ number_format($i->total, 0, "", ",") }}
                            @php $total += $i->total @endphp
                          @endif
                        @endforeach
                        </td>
                      @endforeach
                      <td align="right" class="align-middle" style="background-color: #f0ededda !important">
                        {{ number_format($total, 0, "", ",") }}
                      </td>
                      @php $subRevenue += $total @endphp
                    </tr>
                    @if(Auth::user()->roles == 'SUPER')
                    <tr class="text-dark" style="background-color: white">
                      <td align="center">HPP</td>
                      @php $hpp = 0; $kode = $s->id; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle">
                        {{-- @foreach($items as $i)
                          @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id)) --}}
                            <a href="#Detail{{ $j->id }}{{ $s->id }}" class="btn btn-link btn-sm text-bold" data-toggle="modal" style="font-size: 14px; padding: 0px 0px;">
                              @if($j->$kode != 0)
                                {{ number_format($j->$kode, 0, "", ",") }}
                              @endif
                            </a>
                            @php $hpp += $j->$kode @endphp
                          {{-- @endif
                        @endforeach --}}
                        </td>
                      @endforeach
                      <td align="right" class="align-middle">
                        {{ number_format($hpp, 0, "", ",") }}
                      </td>
                      @php $subHPP += $hpp; @endphp
                    </tr>
                    @endif
                    <tr class="text-dark">
                      <td align="center">Retur</td>
                      @php $ret = 0; $arrRet = []; $k = 0; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle">
                          @foreach($retur as $r)
                            @if(($r->id_sales == $s->id) && ($r->id_kategori == $j->id))
                              {{ number_format($r->total, 0, "", ",") }}
                              @php $ret += $r->total; $arrRet[$k] = $r->total; @endphp
                              @break
                            @else
                              @php $arrRet[$k] = 0; @endphp
                            @endif
                          @endforeach
                          @if($retur->count() == 0)
                            @php $arrRet[$k] = 0; @endphp
                          @endif
                          @php $k++ @endphp
                        </td>
                      @endforeach
                      <td align="right" class="align-middle">
                        {{ number_format($ret, 0, "", ",") }}
                      </td>
                      @php $subRetur += $ret; @endphp
                    </tr>
                    <tr class="text-dark text-bold" style="background-color: yellow">
                      @if(Auth::user()->roles == 'SUPER')
                        <td align="center">Laba</td>
                      @else
                        <td align="center">Total</td>
                      @endif
                      @php $laba = 0; $k = 0; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle">
                        @foreach($items as $i)
                          @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                            @if(Auth::user()->roles == 'SUPER')
                              {{ number_format($i->total - $j->$kode - $arrRet[$k], 0, "", ",") }}
                              @php $laba += ($i->total - $j->$kode - $arrRet[$k]) @endphp
                            @else
                              {{ number_format($i->total - $arrRet[$k], 0, "", ",") }}
                              @php $laba += ($i->total - $arrRet[$k]) @endphp
                            @endif
                          @endif
                        @endforeach
                        @php $k++ @endphp
                        </td>
                      @endforeach
                      <td align="right" class="align-middle">
                        {{ number_format($laba, 0, "", ",") }}
                      </td>
                      @php $subLaba += $laba; @endphp
                    </tr>
                    @php $no++ @endphp
                  @empty
                    <tr>
                      <td colspan=12 class="text-center text-bold h4 p-2"><i>Belum Ada Laporan Keuangan</i></td>
                    </tr>
                  @endforelse
                  @if(Auth::user()->roles == 'SUPER')
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark" style="letter-spacing: 0.8px">Total Revenue</td>
                      <td align="right" class="text-bold">{{ number_format($subRevenue, 0, "", ".") }}</td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark" style="letter-spacing: 0.8px">Total HPP</td>
                      <td align="right" class="text-bold">{{ number_format($subHPP, 0, "", ".") }}</td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark" style="letter-spacing: 0.8px">Total Retur</td>
                      <td align="right" class="text-bold">{{ number_format($subRetur, 0, "", ".") }}</td>
                    </tr>
                    <tr class="text-bold" style="font-size: 15px; color: black">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold bg-warning" style="letter-spacing: 0.8px">Total Laba</td>
                      <td align="right" class="text-bold bg-warning" id="laba">{{ number_format($subLaba, 0, "", ".") }}</td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark align-middle" style="letter-spacing: 0.8px">Pendapatan Lain-Lain</td>
                      <td>
                        <input type="text" name="pendapatan" class="form-control form-control-sm text-bold text-dark text-right" onkeypress="return angkaSaja(event, 'pendapatan')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" style="font-size: 15px" id="pendapatan" value="{{ $keu->count() != 0 ? number_format($keu->first()->pendapatan, 0, "", ".") : '' }}">
                      </td>
                    </tr>
                    <tr class="text-bold" style="font-size: 15px; color: black">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold bg-success" style="letter-spacing: 0.8px">Total Laba & Pendapatan</td>
                      <td align="right" class="text-bold bg-success" id="totLaba">{{ number_format($keu->count() != 0 ? $subLaba + $keu->first()->pendapatan : $subLaba, 0, "", ".") }}</td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark align-middle" style="letter-spacing: 0.8px">Beban Gaji</td>
                      <td align="right" class="text-bold">
                        <input type="text" name="bebanGaji" class="form-control form-control-sm text-bold text-dark text-right" onkeypress="return angkaSaja(event, 'gaji')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" style="font-size: 15px" id="gaji" value="{{ $keu->count() != 0 ? number_format($keu->first()->beban_gaji, 0, "", ".") : '' }}">
                      </td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark align-middle" style="letter-spacing: 0.8px">Beban Penjualan</td>
                      <td align="right" class="text-bold">
                        <input type="text" name="bebanJual" class="form-control form-control-sm text-bold text-dark text-right" onkeypress="return angkaSaja(event, 'jual')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" style="font-size: 15px" id="jual" value="{{ $keu->count() != 0 ? number_format($keu->first()->beban_jual, 0, "", ".") : '' }}">
                      </td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark align-middle" style="letter-spacing: 0.8px">Beban Lain-Lain</td>
                      <td align="right" class="text-bold">
                        <input type="text" name="bebanLain" class="form-control form-control-sm text-bold text-dark text-right" onkeypress="return angkaSaja(event, 'lain')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" style="font-size: 15px" id="lain" value="{{ $keu->count() != 0 ? number_format($keu->first()->beban_lain, 0, "", ".") : '' }}">
                      </td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-dark align-middle" style="letter-spacing: 0.8px">Petty Cash</td>
                      <td align="right" class="text-bold">
                        <input type="text" name="pettyCash" class="form-control form-control-sm text-bold text-dark text-right" onkeypress="return angkaSaja(event, 'petty')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" style="font-size: 15px" id="petty" value="{{ $keu->count() != 0 ? number_format($keu->first()->petty_cash, 0, "", ".") : '' }}">
                      </td>
                    </tr>
                    <tr class="text-dark text-bold" style="font-size: 15px">
                      <td colspan="{{ $jenis->count() + 3 }}" align="right" class="text-bold text-white bg-primary" style="letter-spacing: 0.8px">Grand Total</td>
                      <td align="right" class="text-bold text-white bg-primary" id="subLaba">{{ number_format($keu->count() != 0 ? $subLaba + $keu->first()->pendapatan - $keu->first()->beban_gaji - $keu->first()->beban_jual - $keu->first()->beban_lain - $keu->first()->petty_cash : $subLaba, 0, "", ".") }}</td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center" style="margin-top: 30px">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold" onclick="return checkRequired(event)" id="submitKeu" >Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold" >Reset</button>
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
const tahun = document.getElementById('tahun');
const bulan = document.getElementById('bulan');
const laba = document.getElementById('laba');
const pendapatan = document.getElementById('pendapatan');
const totLaba = document.getElementById('totLaba');
const gaji = document.getElementById('gaji');
const jual = document.getElementById('jual');
const lain = document.getElementById('lain');
const petty = document.getElementById('petty');
const subLaba = document.getElementById('subLaba');

pendapatan.addEventListener("keyup", getTotal);
gaji.addEventListener("keyup", getTotal);
jual.addEventListener("keyup", getTotal);
lain.addEventListener("keyup", getTotal);
petty.addEventListener("keyup", getTotal);

function getTotal(e) {
  $(this).val(function(index, value) {
    return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  });

  totLaba.textContent = addCommas(+laba.textContent.replace(/\./g, "") + +pendapatan.value.replace(/\./g, ""));
  subLaba.textContent = displayTotal(laba.textContent, pendapatan.value, gaji.value, jual.value, lain.value, petty.value);
}

function displayTotal(awal, dapat, bg, bj, bl, pc) {
  total = addCommas(+awal.replace(/\./g, "") + +dapat.replace(/\./g, "") - +bg.replace(/\./g, "") - +bj.replace(/\./g, "") - +bl.replace(/\./g, "") - +pc.replace(/\./g, ""));
  return total;
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    if(inputan == 'pendapatan')
      $(pendapatan).tooltip('show');
    if(inputan == 'gaji')
      $(gaji).tooltip('show');
    if(inputan == 'lain')
      $(lain).tooltip('show');
    if(inputan == 'jual')
      $(jual).tooltip('show');
    if(inputan == 'petty')
      $(petty).tooltip('show');
    return false;
  }
  return true;
}

/** Add Thousand Separators **/
function addCommas(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
}

function checkRequired(e) {
  tahun.removeAttribute('required');
  bulan.removeAttribute('required');

  document.getElementById("submitKeu").formMethod = "POST";
  document.getElementById("submitKeu").formAction = "{{ route('lap-keu-store-show', ['tahun' => $tahun, 'bulan' => $month]) }}";
}

$(function() {
  $("[autofocus]").on("focus", function() {
    if (this.setSelectionRange) {
      var len = this.value.length * 2;
      this.setSelectionRange(len, len);
    } else {
      this.value = this.value;
    }
    this.scrollTop = 999999;
  }).focus();
});

/** Autocomplete Input Text **/
$(function() {
  var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
              'September', 'Oktober', 'November', 'Desember'];
  var yearNow = new Date();
  yearNow = yearNow.getFullYear();
  var tahun = [`${yearNow}`, `${yearNow-1}`, `${yearNow-2}`];
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Customer --*/
  $("#bulan").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(bulan, extractLast(request.term)));
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

  /*-- Autocomplete Input Customer --*/
  $("#tahun").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(tahun, extractLast(request.term)));
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