@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Sales Order</h1>
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
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="kode" value="{{ $newcode }}" readonly>
                      </div>
                      <label for="tanggal" class="col-2 col-form-label text-bold text-right">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="tanggal" value="{{ $tanggal }}" readonly>
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -350px; margin-right:-40px">
                    <div class="form-group row subtotal-po">
                      <label for="tempo" class="col-6 col-form-label text-bold text-right">Jatuh Tempo</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="tempo"
                          @if($itemsRow != 0) 
                            value="{{ $items[$itemsRow - 1]->tempo }}"
                          @endif
                        >
                      </div>
                      <span class="col-form-label text-bold input-right">hari</span>
                    </div>
                    {{-- <div class="form-group row total-po">
                      <label for="waktuTagih" class="col-6 col-form-label text-bold">Waktu Penagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="waktuTagih">
                      </div>
                      <span class="col-form-label text-bold">hari</span>
                    </div>
                    <div class="form-group row total-po">
                      <label for="diskonFaktur" class="col-6 col-form-label text-bold">Diskon Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="diskonFaktur" id="diskonFaktur"
                          @if($itemsRow != 0) 
                            value="{{ $items[0]->diskon_faktur }}"
                          @else 
                            value="0"
                          @endif
                        />
                      </div>
                      <span class="col-form-label text-bold">%</span>
                    </div> --}}
                    <div class="form-group row total-po">
                      <label for="pkp" class="col-6 col-form-label text-bold text-right">PKP</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3 pkp-check">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="radio" name="pkp"  value="1"
                            @if($itemsRow != 0) 
                              @if($items[$itemsRow - 1]->pkp == 1)
                                checked
                              @endif
                            @endif
                          >
                          <label class="form-check-label text-bold" for="pkp1">Ya</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="pkp"  value="0"
                            @if($itemsRow != 0) 
                              @if($items[$itemsRow - 1]->pkp == 0)
                                checked
                              @endif
                            @endif
                          >
                          <label class="form-check-label text-bold" for="pkp2">Tidak</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row customer-row">
                  <label for="customer" class="col-2 col-form-label text-bold">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-3">
                    <input type="text" name="namaCustomer" id="namaCustomer" placeholder="Nama Customer" class="form-control form-control-sm mt-1" required />
                    <input type="hidden" name="kodeCustomer" id="idCustomer">
                  </div>
                  <label for="customer" class="col-1 col-form-label text-bold">NPWP</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" name="npwp" id="npwp" class="form-control form-control-sm mt-1" readonly />
                  </div>
                </div>
                <div class="form-group row sales-row">
                  <label for="alamat" class="col-2 col-form-label text-bold">Nama Sales</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" name="namaSales" id="namaSales" placeholder="Nama Sales" class="form-control form-control-sm mt-1" readonly />
                    <input type="hidden" name="kodeSales" id="idSales" 
                      {{-- @if($itemsRow != 0) 
                        value="{{ $items[0]->id_supplier }}"
                      @endif --}}
                    />
                  </div>
                  <label for="kat" class="col-2 col-form-label text-bold text-right">Kategori</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-3">
                    <div class="form-check mt-2">
                      <input class="form-check-input" type="radio" name="kategori"  value="Cash" required>
                      <label class="form-check-label text-bold" for="kat1">Cash</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="kategori"  value="Prime">
                      <label class="form-check-label text-bold" for="kat2">Prime</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="kategori"  value="Extrana">
                      <label class="form-check-label text-bold" for="kat3">Extrana</label>
                    </div>
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -60px">
                  <label for="tglKirim" class="col-2 col-form-label text-bold">Tanggal Kirim</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" name="tanggalKirim" placeholder="DD-MM-YYYY" class="form-control form-control-sm mt-1" required />
                    <input type="hidden" name="jumBaris" id="jumBaris" value="5">
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
            
              {{-- <div class="form-group row">
                <label for="keterangan" class="col-1 col-form-label text-bold">Keterangan</label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm ml-1" name="keterangan" placeholder="Keterangan" 
                    value="{{ old('keterangan') }}">
                </div>
              </div> --}}

              <!-- Inputan Detil PO -->
              {{-- <div class="form-row">
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Kode</label>
                  <input type="text" name="kodeBarang" id="kodeBarang" placeholder="Kd Brg" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-3">
                  <label for="kode" class="col-form-label text-bold ">Nama Barang</label>
                  <input type="text" name="namaBarang" id="namaBarang" placeholder="Nama Barang" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Pcs</label>
                  <input type="text" name="pcs" id="qty" placeholder="Qty (Pcs)" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Diskon</label>
                  <input type="text" name="diskon" id="diskon" placeholder="Diskon" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Harga</label>
                  <input type="text" name="harga" id="harga" placeholder="Harga Satuan" class="form-control form-control-sm text-bold" readonly>
                  <input type="hidden" name="kodeHarga" id="idHarga" />
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Jumlah</label>
                  <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah Harga" class="form-control form-control-sm text-bold" readonly>
                </div>
                <div class="form-group col-auto">
                  <label for="" class="col-form-label text-bold " ></label>
                  <button type="submit" formaction="{{ route('so-create', $newcode) }}" formmethod="POST" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>          
              <hr> --}}
              <!-- End Inputan Detil PO -->
              
              <!-- Tabel Data Detil PO -->
              <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
              </span>
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" >
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <td rowspan="2" style="width: 30px" class="align-middle">No</td>
                    <td rowspan="2" style="width: 90px" class="align-middle">Kode Barang</td>
                    <td rowspan="2" class="align-middle">Nama Barang</td>
                    <td rowspan="2" style="width: 55px" class="align-middle">Qty</td>
                    <td rowspan="2" style="width: 90px" class="align-middle">Harga</td>
                    <td rowspan="2" style="width: 110px" class="align-middle">Jumlah</td>
                    <td colspan="2">Diskon</td>
                    <td rowspan="2" style="width: 120px" class="align-middle">Netto (Rp)</td>
                    <td rowspan="2" style="width: 50px" class="align-middle">Hapus</td>
                  </tr>
                  <tr>
                    <td>%</td>
                    <td>Rupiah</td>
                  </tr>
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
                        <input type="text" name="qty[]" id="qty" class="form-control form-control-sm text-bold text-dark text-right qty" 
                        value="{{ old('qty[]') }}">
                        <input type="hidden" name="kodeGudang[]" class="kodeGudang">
                        <input type="hidden" name="qtyGudang[]" class="qtyGudang">
                      </td>
                      <td>
                        <input type="text" name="harga[]" id="harga" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" value="{{ old('harga[]') }}">
                      </td>
                      <td>
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ old('jumlah[]') }}" >
                      </td>
                      <td style="width: 90px">
                        <input type="text" name="diskon[]" id="diskon" class="form-control form-control-sm text-bold text-right text-dark diskon" 
                        value="{{ old('diskon[]') }}" >
                      </td>
                      <td style="width: 100px">
                        <input type="text" name="diskonRp[]" id="diskonRp" class="form-control form-control-sm text-bold text-right text-dark diskonRp" 
                        value="{{ old('diskonRp[]') }}" >
                      </td>
                      <td>
                        <input type="text" name="netto[]" id="netto" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto" value="{{ old('netto[]') }}" >
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                    </tr>
                    </div>
                    <div class="modal modalGudang" id="{{$i-1}}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="h2 text-bold">&times;</span>
                            </button>
                            <h4 class="modal-title">Pilih Gudang</h4>
                          </div>
                          <div class="modal-body">
                            <p>Qty order melebihi stok pada gudang <strong>{{ $stok[0]->gudang->nama }}</strong>. Pilih gudang lainnya untuk memenuhi qty order.</p>
                            <input type="hidden" id="kodeModal{{$i-1}}" value="{{$i-1}}">
                            <div class="form-group row" style="margin-top: -10px">
                              <label for="kode" class="col-4 col-form-label text-bold">Qty Order</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold" id="qtyOrder"></span>
                            </div>
                            <div class="form-group row" style="margin-top: -30px">
                              <label for="kode" class="col-4 col-form-label text-bold">Stok {{ $gudang[0]->nama }}</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold" id="stokJohar"></span>
                            </div>
                            <div class="form-group row" style="margin-top: -20px">
                              <label for="kode" class="col-4 col-form-label text-bold">Sisa Qty Order</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold" id="sisaQty"></span>
                            </div>
                            <label for="pilih" style="margin-bottom: -5px">Pilih Gudang Tambahan</label>
                            @foreach($gudang as $g)
                              @if($g->id != "GDG01")
                                <div class="row">
                                  <label for="kode" class="col-7 col-form-label text-bold">{{ $g->nama }} <span class="col-form-label text-bold stokGudang"></span></label>
                                  <input type="hidden" class="kodeGud" value="{{$g->id}}">
                                  <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-success btn-block text-bold mt-1 btnPilih">Pilih</button>
                                  </div>
                                </div>
                              @endif
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  @endfor

                  <!-- Tabel Tampil Detil SO (Bukan Diinput di Tabel) -->
                  {{-- @if($itemsRow != 0)
                    @php $i = 1; $subtotal = 0; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td align="center">{{ $item->barang->id }} </td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="right" class="editable{{$i}}" id="editableQty{{$i}}">
                          {{ $item->qty }}
                        </td>
                        <td align="right" class="autoharga">{{ $item->harga }}</td>
                        <td align="right" class="autoharga">{{ $item->harga * $item->qty }}</td>
                        <td align="right" class="autodiskon">{{ $item->diskon }} %</td>
                        @php 
                          $total = $item->qty * $item->harga;
                          $besarDiskon = $item->diskon * $total / 100;
                          $total -= $besarDiskon;
                          $subtotal += $total;
                        @endphp
                        <td align="right" class="autodiskon">{{ $besarDiskon }}</td>
                        <td align="right" class="autototal">
                          {{ $total }}
                          <input type="hidden" id="totalBarang" value="{{ $total }}">
                        </td>
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
                          <a href="{{ route('so-remove', ['id' => $item->id_so, 'barang' => $item->id_barang]) }}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=11 class="text-center text-bold h4 p-2"><i>Belum ada Detail SO</i></td>
                    </tr>
                  @endif  --}}

                </tbody>
              </table>
              {{-- <div class="form-group row justify-content-end subtotal-so">
                <label for="subTotal" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger" 
                  @if($itemsRow != 0) 
                    value="{{ $subtotal }}"
                  @endif
                  />
                </div>
              </div> --}}
              @if($itemsRow != 0) 
                @php
                  $diskonFaktur = ($items[0]->diskon_faktur * $subtotal) / 100;
                  $totalNotPPN = $subtotal - $diskonFaktur;
                  $ppn = $totalNotPPN * 10 / 100;
                  $grandtotal = $totalNotPPN + $ppn;
                @endphp
              @endif
              {{-- <div class="form-group row justify-content-end total-so">
                <label for="diskonFaktur" class="col-2 col-form-label text-bold text-right text-dark">Diskon Faktur</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" name="angkaDF" id="angkaDF" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger"
                  @if($itemsRow != 0) 
                    value="{{ $diskonFaktur }}"
                  @endif
                  />
                </div>
              </div> --}}
              <div class="form-group row justify-content-end subtotal-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" />
                  {{-- @if($itemsRow != 0) 
                    value="{{ $totalNotPPN }}"
                  @endif --}}
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" 
                  @if($itemsRow != 0) 
                    value="{{ $ppn }}"
                  @endif
                  />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-right" 
                  @if($itemsRow != 0) 
                    value="{{ $grandtotal }}"
                  @endif
                  />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('so-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
<!-- /.container-fluid -->
@endsection

@push('addon-script')
<script type="text/javascript">
const namaCust = document.getElementById('namaCustomer');
const namaSales = document.getElementById('namaSales');
const npwp = document.getElementById('npwp');
const kodeCust = document.getElementById('idCustomer');
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const kodeGudang = document.querySelectorAll(".kodeGudang");
const qtyGudang = document.querySelectorAll(".qtyGudang");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const diskon = document.querySelectorAll(".diskon");
const diskonRp = document.querySelectorAll(".diskonRp");
const netto = document.querySelectorAll(".netto");
const hapusBaris = document.querySelectorAll(".icRemove");
const subTotal = document.getElementById('subtotal');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');
const teksJohar = document.getElementById('stokJohar');
const qtyOrder = document.getElementById('qtyOrder');
const sisaQty = document.getElementById('sisaQty');
const stokGudang = document.querySelectorAll('.stokGudang');
const kodeGud = document.querySelectorAll(".kodeGud");
const btnPilih = document.querySelectorAll(".btnPilih");
const modalGudang = document.querySelectorAll(".modalGudang");
var netPast;

/** Call Fungsi Setelah Inputan Terisi **/
namaCust.addEventListener('change', displayCust);
newRow.addEventListener("click", displayRow);

/** Tampil Id Supplier **/
function displayCust(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
      namaSales.value = '{{ $c->sales->nama }}';
      npwp.value = '{{ $c->npwp }}';
    }
  @endforeach
}

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
        <input type="text" name="harga[]" id="hargaRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-right hargaRow" placeholder="Harga Satuan" >
      </td>
      <td>
        <input type="text" name="jumlah[]" id="jumlahRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-right jumlahRow" placeholder="Total Harga" >
      </td>
      <td style="width: 60px">
        <input type="text" name="diskon[]" id="diskonRow${newNum}" class="form-control form-control-sm text-bold text-right text-dark diskonRow" 
        value="{{ old('diskon[]') }}" >
      </td>
      <td style="width: 120px">
        <input type="text" name="diskonRp[]" id="diskonRpRow${newNum}" class="form-control form-control-sm text-bold text-right text-dark diskonRpRow" 
        value="{{ old('diskonRp[]') }}" >
      </td>
      <td>
        <input type="text" name="netto[]" id="nettoRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right nettoRow" value="{{ old('netto[]') }}" >
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
  const diskonRow = document.getElementById("diskonRow"+newNum);
  const diskonRpRow = document.getElementById("diskonRpRow"+newNum);
  const nettoRow = document.getElementById("nettoRow"+newNum);
  const hapusRow = document.getElementById("icRemoveRow"+newNum);

  /** Tampil Harga **/
  brgRow.addEventListener("change", displayHargaRow); 
  kodeRow.addEventListener("change", displayHargaRow);
  
  function displayHargaRow(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(".", "") - +jumlahRow.value.replace(".", ""));
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
        hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
        qtyRow.setAttribute('required', true);
      }
    @endforeach
  }   

  /** Tampil Jumlah **/
  qtyRow.addEventListener("change", function (e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      jumlahRow.value = "";
      nettoRow.value = "";
    }
    else {
      netPast = +nettoRow.value.replace(/\./g, "");
      jumlahRow.value = addCommas(e.target.value * hargaRow.value.replace(/\./g, ""));
      if(diskonRow.value != "") {
        diskonRpRow.value = addCommas(diskonRow.value * jumlahRow.value.replace(/\./g, "") / 100);
      }

      nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
      checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    }
    total_ppn(subtotal.value.replace(/\./g, ""));
  });

  /** Tampil Diskon Rp **/
  diskonRow.addEventListener("change", function (e) {
    if(e.target.value == "") {
      netPast = nettoRow.value.replace(/\./g, "");
      nettoRow.value = addCommas(+nettoRow.value.replace(/\./g, "") + +diskonRpRow.value.replace(/\./g, ""))
      checkSubtotal(netPast, nettoRow.value.replace(/\./g, ""));
      diskonRpRow.value = "";
    }
    else {
      netPast = +nettoRow.value.replace(/\./g, "")
      diskonRpRow.value = addCommas(e.target.value * jumlahRow.value.replace(/\./g, "") / 100);
      nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""))
      checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    }
    total_ppn(subtotal.value.replace(/\./g, ""));
  });
  
  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    if(qtyRow.value != "") {
       subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      total_ppn(subtotal.value.replace(/\./g, ""));
    }
    
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

/** Tampil Harga Barang **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("change", displayHarga) ;
  kodeBarang[i].addEventListener("change", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
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
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        qty[i].setAttribute('required', true);
      }
    @endforeach
  }

  kodeGudang[i].value = 'GDG01';
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("change", function (e) {
    var stokJohar = 0;
    var stokLain = [];
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->id_gudang }}' == 'GDG01')) {
        stokJohar = '{{ $s->stok }}';
      }
      else if('{{ $s->id_barang }}' == kodeBarang[i].value){
        stokLain.push('{{ $s->stok }}');
      }
    @endforeach

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      netto[i].value = "";
    }
    else {
      if(+e.target.value > stokJohar) {
        $('#'+i).modal("show");
        teksJohar.textContent = stokJohar;
        qtyOrder.textContent = e.target.value;
        sisaQty.textContent = +e.target.value - +stokJohar;
        for(let i = 0; i < stokGudang.length; i++) {
          stokGudang[i].textContent = `(Stok : ${stokLain[i]})`;
        }
        qtyGudang[i].value = stokJohar;
      }
      else {
        qtyGudang[i].value = e.target.value;
      }

      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        diskonRp[i].value = addCommas(diskon[i].value * jumlah[i].value.replace(/\./g, "") / 100);
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    grandtotal.value = subtotal.value;
  });
}  

/** Tampil Diskon Rupiah Otomatis **/
for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("change", function (e) {
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
    // total_ppn(subtotal.value.replace(/\./g, ""));
    grandtotal.value = subtotal.value;
  });
}

/** Tampil Kode Gudang Tambahan **/
for(let i = 0; i < btnPilih.length; i++) {
  btnPilih[i].addEventListener("click", function (e) {
    for(let j = 0; j < modalGudang.length; j++) {
      if(j == document.getElementById('kodeModal'+j).value) {
        var k = j;
        break;
      }
    }
    var arrKode = kodeGudang[k].value.split(',');
    if(arrKode.length > 1) {
      arrKode[1] = kodeGud[i].value;
      kodeGudang[k].value = arrKode[0].concat(`, ${arrKode[1]}`);
    }
    else {
      kodeGudang[k].value = kodeGudang[k].value.concat(`,${kodeGud[i].value}`);
    }
    qtyGudang[k].value = qtyGudang[k].value.concat(`,${sisaQty.textContent}`);
    $('#'+k).modal("hide");
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
  ppn.value = addCommas(Math.floor(sub * 10 / 100));
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

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    if(qty[i].value != "") {
       subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      total_ppn(subtotal.value.replace(/\./g, ""));
    }

    netto[i].value = netto[i+1].value;
    diskonRp[i].value = diskonRp[i+1].value;
    diskon[i].value = diskon[i+1].value;
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
  var barangKode = [];
  var barangNama = [];
  @foreach($barang as $b)
    barangKode.push('{{ $b->id }}');
    barangNama.push('{{ $b->nama }}');
  @endforeach

  var customer = [];
  @foreach($customer as $c)
    customer.push('{{ $c->nama }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

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
});

</script>
@endpush