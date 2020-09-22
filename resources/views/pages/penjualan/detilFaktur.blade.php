@extends('layouts.admin')

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
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="kode" id="kode" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="namaCustomer" id="namaCustomer" >
                    <input type="hidden" name="kodeCustomer" id="kodeCustomer">
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Tanggal Awal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Tanggal Akhir</label>
                  <span class="col-form-label text-bold ml-3">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAkhir" >
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('so-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner">
                  @foreach($items as $item)
                  <div class="carousel-item @if($item->id == $items[$itemsRow-1]->id) active
                    @endif "
                  />
                    <div class="container so-container">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row">
                            <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor SO</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold"
                              @if($itemsRow != 0)
                                value="{{ $item->id }}"
                              @endif
                              >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row">
                            <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Customer</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-7">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
                              @if($itemsRow != 0)
                                value="{{ $item->customer->nama }} ({{ $item->id_customer }})"
                              @endif
                              >
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
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold"
                              @if($itemsRow != 0)
                                value="{{ \Carbon\Carbon::parse($item->tgl_so)->format('d-m-Y') }}"
                              @endif
                              >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Sales</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-4">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
                              @if($itemsRow != 0)
                                value="{{ $item->customer->sales->nama }}"
                              @endif
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Status</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold"
                              @if($itemsRow != 0)
                                value="{{ $item->status }}"
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
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
                              @if($itemsRow != 0)
                                value="{{ \Carbon\Carbon::parse($item->tgl_so)->add($item->tempo, 'days')->format('d-m-Y') }}"
                              @endif
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    {{-- <table class="table table-sm table-responsive-sm table-hover" style="width: 40%">
                      <thead class="text-center text-bold text-dark" style="border: dotted">
                        <td style="border: dotted">Jatuh Tempo</td>
                        <td>Sales</td>
                      </thead>
                      <tbody class="text-bold">
                        <td align="center" style="border: dotted">
                          @if($itemsRow != 0) {{ $item->tempo }} @endif
                        </td>
                        <td align="center"> 
                          @if($itemsRow != 0) {{ $item->customer->sales->nama }} @endif
                        </td>
                      </tbody>
                    </table> --}}

                    <!-- Tabel Data Detil PO -->
                    <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                      <thead class="text-center text-bold text-dark">
                        <td style="width: 30px">No</td>
                        <td style="width: 80px">Kode</td>
                        <td>Nama Barang</td>
                        <td style="width: 50px">Qty</td>
                        <td>Harga</td>
                        <td>Jumlah</td>
                        <td style="width: 80px">Diskon(%)</td>
                        <td style="width: 110px">Diskon(Rp)</td>
                        <td style="width: 120px">Netto (Rp)</td>
                      </thead>
                      <tbody>
                        @if($itemsRow != 0)
                          @php 
                            $i = 1; $subtotal = 0;
                            $itemsDetail = \App\DetilSO::with(['so', 'barang'])->where('id_so',
                              $item->id)->get();
                          @endphp
                          @foreach($itemsDetail as $itemDet)
                            <tr class="text-bold">
                              <td align="center">{{ $i }}</td>
                              <td align="center">{{ $itemDet->id_barang }} </td>
                              <td>{{ $itemDet->barang->nama }}</td>
                              <td align="right">{{ $itemDet->qty }}</td>
                              <td align="right">{{ $itemDet->harga }}</td>
                              <td align="right">{{ $itemDet->qty * $itemDet->harga }}</td>
                              <td align="right">{{ $itemDet->diskon }} %</td>
                              <td align="right">
                                {{ (($itemDet->qty * $itemDet->harga) * $itemDet->diskon) / 100 }}
                              </td>
                              <td align="right">
                                {{ ($itemDet->qty * $itemDet->harga) - 
                                ((($itemDet->qty * $itemDet->harga) * $itemDet->diskon) / 100) }}
                              </td>
                              @php $subtotal += ($itemDet->qty * $itemDet->harga) - 
                                ((($itemDet->qty * $itemDet->harga) * $itemDet->diskon) / 100); 
                              @endphp
                            </tr>
                            @php $i++; @endphp
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
                        <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right"
                        @if($itemsRow != 0) 
                          value="{{ $subtotal }}"
                        @endif
                        />
                      </div>
                    </div>
                    <div class="form-group row justify-content-end total-so">
                      <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" 
                        @if($itemsRow != 0) 
                          value="{{ $subtotal * 10 / 100 }}"
                        @endif
                        />
                      </div>
                    </div>
                    <div class="form-group row justify-content-end total-so">
                      <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" 
                        @if($itemsRow != 0) 
                          value="{{ $subtotal + ($subtotal * 10 / 100) }}"
                        @endif
                        />
                      </div>
                    </div>
                    <hr>
                    <!-- End Tabel Data Detil PO -->

                    <!-- Button Submit dan Reset -->
                    <div class="form-row justify-content-center">
                      <div class="col-2">
                        <a href="" data-toggle="modal" data-target="#{{$item->id}}" class="btn btn-danger btn-block text-bold"> Ganti Status
                        </a>
                        {{-- <button type="submit" formaction="" formmethod="POST" class="btn btn-danger btn-block text-bold">Ganti Status /> --}}
                      </div>
                      <div class="col-2">
                        <button type="reset" class="btn btn-info btn-block text-bold">Ubah Isi Faktur</button>
                      </div>
                    </div>
                    <!-- End Button Submit dan Reset -->
                  </div>
                  <div class="modal" id="{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$item->id}}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" class="h2 text-bold">&times;</span>
                          </button>
                          <h4 class="modal-title">Ubah Status Faktur {{$item->id}}</h4>
                        </div>
                        <div class="modal-body">
                          <form>
                            <div class="form-group row">
                              <label for="kode" class="col-2 col-form-label text-bold">Status</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-2">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="BATAL">
                                <input type="hidden" name="id_so" value="{{$item->id}}">
                              </div>
                            </div>
                            <div class="form-group subtotal-so">
                              <label for="keterangan" class="col-form-label">Keterangan</label>
                              <input type="text" class="form-control" name="keterangan">
                            </div>
                            <hr>
                            <div class="form-row justify-content-center">
                              <div class="col-3">
                                <button type="submit" class="btn btn-success btn-block text-bold">Simpan</button>
                              </div>
                              <div class="col-3">
                                <button button type="button" class="btn btn-outline-secondary btn-block text-bold" data-dismiss="modal">Batal</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                @if(($itemsRow > 0) && ($itemsRow != 1))
                  <a class="carousel-control-prev" href="#so-carousel" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                  {{-- @if($item->id != $items[$itemsRow-1]->id) --}}
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
<script type="text/javascript">
const namaCust = document.getElementById('namaCustomer');
const kodeCust = document.getElementById('kodeCustomer');
const kodeSO = document.getElementById('kode');

/** Call Fungsi Setelah Inputan Terisi **/
namaCust.addEventListener("change", displayKode);

function displayKode(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
    }
  @endforeach
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