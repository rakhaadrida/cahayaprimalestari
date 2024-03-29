@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Ubah Faktur</h1>
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
              <div class="container so-container">
                <div class="form-group row">
                  <label for="kode" class="col-2 col-form-label text-bold">Nomor SO</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="id" id="kode" value="{{ $id }}" autofocus>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" tabindex="2" class="form-control form-control-sm text-bold mt-1" name="nama" id="namaCustomer" value="{{ $nama }}" >
                    <input type="hidden" name="kode" id="kodeCustomer" value="{{ $kode }}">
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Tanggal Awal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="3" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" value="{{ $tglAwal }}" autocomplete="off">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Tanggal Akhir</label>
                  <span class="col-form-label text-bold ml-3">:</span>
                  <div class="col-2">
                    <input type="text" tabindex="4" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAkhir" id="tglAkhir" value="{{ $tglAkhir }}" autocomplete="off">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" tabindex="5" formaction="{{ route('so-show-kenari') }}" formmethod="GET" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner">
                  @foreach($items as $item)
                  <div class="carousel-item @if($item->id == $items[$items->count()-1]->id) active
                    @endif "
                  />
                    <div class="container so-update-container text-dark">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row">
                            <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor SO</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $items->count() != 0 ? $item->id : '' }}" >
                            </div>
                          </div>
                        </div>
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row">
                            <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Customer</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-7">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $items->count() != 0 ? $item->customer->nama : '' }}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal SO</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $items->count() != 0 ? \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y') : '' }}">
                            </div>
                          </div>
                        </div>
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Sales</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-4">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $items->count() != 0 ? $item->customer->sales->nama : '' }}" >
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Status</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-3">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark"
                              @if($item->need_approval->count() != 0)
                                value="{{ $item->need_approval->last()->status }}"
                                @php $status = $item->need_approval->last()->status; @endphp
                              @else
                                value="{{ $item->status }}"
                                @php $status = $item->status; @endphp
                              @endif
                              >
                            </div>
                          </div>
                        </div>
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Jatuh Tempo</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-4">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $items->count() != 0 ? \Carbon\Carbon::parse($item->tgl_so)->add($item->tempo, 'days')->format('d-M-y') : '' }}" >
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Tabel Data Detil PO -->
                    <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                      <thead class="text-center text-bold text-dark">
                        <td style="width: 30px">No</td>
                        <td style="width: 80px">Kode</td>
                        <td>Nama Barang</td>
                        <td style="width: 50px">Qty</td>
                        <td>Harga</td>
                        <td>Jumlah</td>
                        <td style="width: 100px">Diskon(%)</td>
                        <td style="width: 110px">Diskon(Rp)</td>
                        <td style="width: 120px">Netto (Rp)</td>
                      </thead>
                      <tbody>
                        @if($items->count() != 0)
                          @php
                            $i = 1; $subtotal = 0;
                            if(($item->need_approval->count() != 0) && ($item->need_approval->last()->status == 'PENDING_UPDATE')) {
                              $itemsDetail = \App\Models\NeedAppDetil::with(['barang'])
                                        ->select('id_barang', 'diskon')
                                        ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                        ->where('id_app', $item->need_approval->last()->id)
                                        ->groupBy('id_barang', 'diskon')
                                        ->get();
                            } else {
                              $itemsDetail = \App\Models\DetilSO::with(['barang'])
                                        ->select('id_barang', 'diskon')
                                        ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                        ->where('id_so', $item->id)
                                        ->groupBy('id_barang', 'diskon')
                                        ->get();
                            }
                          @endphp
                          @foreach($itemsDetail as $itemDet)
                            <tr class="text-dark">
                              <td align="center">{{ $i }}</td>
                              <td align="center">{{ $itemDet->id_barang }} </td>
                              <td>{{ $itemDet->barang->nama }}</td>
                              <td align="right">{{ $itemDet->qty }}</td>
                              <td align="right">
                                {{ number_format($itemDet->harga, 0, "", ".") }}
                              </td>
                              <td align="right">
                                {{number_format(($itemDet->qty * $itemDet->harga), 0, "", ".")}}
                              </td>
                              <td align="right">{{ $itemDet->diskon }}</td>
                              @php
                                $diskon = 100;
                                $itemDet->diskon = ($itemDet->diskon != NULL ? str_replace(",", ".", $itemDet->diskon) : 0);
                                $arrDiskon = explode("+", $itemDet->diskon);
                                for($j = 0; $j < sizeof($arrDiskon); $j++) {
                                  $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                                }
                                $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                              @endphp
                              <td align="right">
                                {{ number_format((($itemDet->qty * $itemDet->harga) * str_replace(",", ".", $diskon)) / 100, 0, "", ".") }}
                              </td>
                              <td align="right">
                                {{ number_format(($itemDet->qty * $itemDet->harga) -
                                ((($itemDet->qty * $itemDet->harga) * str_replace(",", ".", $diskon)) / 100), 0, "", ".") }}
                              </td>
                            </tr>
                            @php $i++; $subtotal += (($itemDet->qty * $itemDet->harga) -
                                ((($itemDet->qty * $itemDet->harga) * str_replace(",", ".", $diskon)) / 100)); @endphp
                          @endforeach
                        @else
                          <tr>
                            <td colspan="9" class="text-center text-bold h4 p-2"><i>Belum ada Detail SO</i></td>
                          </tr>
                        @endif
                      </tbody>
                    </table>

                    <div class="form-group row justify-content-end subtotal-so">
                      <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal, 0, "", ".") }}"
                        />
                      </div>
                    </div>
                    <div class="form-group row justify-content-end total-so">
                      <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Diskon Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="diskonFaktur" id="diskonFaktur" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($item->diskon, 0, "", ".") }}" />
                      </div>
                    </div>
                    <div class="form-group row justify-content-end total-so">
                      <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Total Sebelum PPN</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal - $item->diskon, 0, "", ".") }}" />
                      </div>
                    </div>
                    <div class="form-group row justify-content-end total-so">
                      <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="0"
                        />
                      </div>
                    </div>
                    <div class="form-group row justify-content-end grandtotal-so">
                      <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" value=" {{number_format($subtotal - $item->diskon, 0, "", ".") }}"
                        />
                      </div>
                    </div>
                    <hr>
                    <!-- End Tabel Data Detil PO -->

                    @if((Auth::user()->roles != 'AR') && (($status != 'BATAL') && ($status != 'PENDING_BATAL') && ($status != 'LIMIT')))
                      <!-- Button Submit dan Reset -->
                      <div class="form-row justify-content-center">
                        <div class="col-2">
                          <a href="" tabindex="6" data-toggle="modal" data-target="#{{$item->id}}" class="btn btn-danger btn-block text-bold">Batal</a>
                        </div>
                        <div class="col-2">
                          <button type="submit" tabindex="7" formaction="{{ route('so-edit-kenari', $item->id) }}" formmethod="POST" class="btn btn-info btn-block text-bold">Ubah</button>
                        </div>
                      </div>
                      <!-- End Button Submit dan Reset -->
                    @endif
                  </div>

                  <!-- Modal Ganti Status -->
                  <div class="modal" id="{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$item->id}}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" class="h2 text-bold">&times;</span>
                          </button>
                          <h4 class="modal-title">Ubah Status Faktur <b>{{$item->id}}</b></h4>
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
                              <input type="text" class="form-control" name="ket{{$item->id}}"
                              id="ket{{$item->id}}" data-toogle="tooltip" data-placement="bottom" title="Form keterangan harus diisi">
                            </div>
                            <hr>
                            <div class="form-row justify-content-center">
                              <div class="col-3">
                                <button type="submit" class="btn btn-success btn-block text-bold" id="btn{{$item->id}}" onclick="return checkEditable({{$item->id}})">Simpan</button>
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
                  @endforeach
                </div>
                @if(($items->count() > 0) && ($items->count() != 1))
                  <a class="carousel-control-prev" href="#so-carousel" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                  {{-- @if($item->id != $items[$items->count()-1]->id) --}}
                    <a class="carousel-control-next " href="#so-carousel" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  {{-- @endif --}}
                @endif
              </div>

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

const namaCust = document.getElementById('namaCustomer');
const kodeCust = document.getElementById('kodeCustomer');
const tglAwal = document.getElementById('tglAwal');
const tglAkhir = document.getElementById('tglAkhir');
const kodeSO = document.getElementById('kode');

/** Call Fungsi Setelah Inputan Terisi **/
namaCust.addEventListener("keyup", displayKode);
namaCust.addEventListener("blur", displayKode);
tglAwal.addEventListener("keyup", formatTanggal);
tglAkhir.addEventListener("keyup", formatTanggal);

function displayKode(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
    }
    else if(e.target.value == '') {
      kodeCust.value = '';
    }
  @endforeach
}

function formatTanggal(e) {
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if(value.length > 2 && value.length <= 4)
    value = value.slice(0,2) + "-" + value.slice(2);
  else if(value.length > 4 && value.length <= 8)
    value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);

  if(e.target.id == 'tglAwal')
    tglAwal.value = value;
  else
    tglAkhir.value = value;
}

function checkEditable(kode) {
  const ket = document.getElementById("ket"+kode.id);
  if(ket.value == "") {
    $(ket).tooltip('show');
    return false;
  }
  else {
    @foreach($items as $item)
      if('{{ $item->id }}' == kode.id) {
        document.getElementById("btn"+kode.id).formMethod = "POST";
        document.getElementById("btn"+kode.id).formAction = "{{ route('so-status-kenari', $item->id) }}";
      }
    @endforeach
  }
}

/** Autocomplete Input Text **/
$(function() {
  var customer = [];
  var kodeFaktur = [];
  @foreach($customer as $c)
    customer.push('{{ $c->nama }}');
  @endforeach
  @foreach($so as $s)
    kodeFaktur.push('{{ $s->id }}');
  @endforeach

  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Customer --*/
  $(namaCust).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(customer, extractLast(request.term)));
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

  /*-- Autocomplete Input Kode SO --*/
  $(kodeSO).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kodeFaktur, extractLast(request.term)));
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
