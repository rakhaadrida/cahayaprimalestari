@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

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
              <div class="container so-container" style="margin-bottom: -20px">
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
                </div>
                <div class="form-group row" style="margin-top: -18px">
                  <label for="customer" class="col-2 col-form-label text-bold">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-3">
                    <input type="text" name="namaCustomer" id="namaCustomer" placeholder="Nama Customer" class="form-control form-control-sm mt-1" required autofocus/>
                    <input type="hidden" name="kodeCustomer" id="idCustomer">
                    <input type="hidden" name="limit" id="limit">
                    <input type="hidden" name="piutang" id="piutang">
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
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row" style="margin-top: -18px">
                      <label for="tglKirim" class="col-2 col-form-label text-bold">Tanggal Kirim</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" name="tanggalKirim" id="tanggalKirim" placeholder="DD-MM-YYYY" class="form-control datepicker form-control-sm mt-1" required />
                        <input type="hidden" name="jumBaris" id="jumBaris" value="5">
                      </div>
                      <label for="kat" class="col-2 col-form-label text-bold text-right" style="margin-top: -35px">Kategori</label>
                      <span class="col-form-label text-bold" style="margin-top: -35px">:</span>
                      <div class="col-3" style="margin-top: -35px">
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="radio" name="kategori"  value="Cash" id="kategori" required>
                          <label class="form-check-label text-bold text-dark" for="kat1">Cash</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="kategori"  value="Prime">
                          <label class="form-check-label text-bold text-dark" for="kat2">Prime</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="kategori"  value="Extrana">
                          <label class="form-check-label text-bold text-dark" for="kat3">Extrana</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col" style="margin-left: -350px; margin-right:-40px; margin-top: -120px">
                    <div class="form-group row subtotal-po">
                      <label for="tempo" class="col-6 col-form-label text-bold text-right">Jatuh Tempo</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control form-control-sm text-bold mt-1" name="tempo" id="tempo" onkeypress="return angkaSaja(event, 'tempo')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" readonly >
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
                          <input class="form-check-input" type="radio" name="pkp" id="pkp" value="1" >
                          <label class="form-check-label text-bold text-dark" for="pkp1">Ya</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="pkp" id="pkp" value="0" >
                          <label class="form-check-label text-bold text-dark" for="pkp2">Tidak</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
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
                    <td colspan="2" style="width: 130px" class="align-middle">Qty</td>
                    <td rowspan="2" style="width: 50px" class="align-middle">Tipe Harga</td>
                    <td rowspan="2" style="width: 100px" class="align-middle">Harga</td>
                    <td rowspan="2" style="width: 110px" class="align-middle">Jumlah</td>
                    <td colspan="2">Diskon</td>
                    <td rowspan="2" style="width: 120px" class="align-middle">Netto (Rp)</td>
                    <td rowspan="2" style="width: 50px" class="align-middle">Hapus</td>
                  </tr>
                  <tr>
                    <td style="width: 70px" id="pcs"></td>
                    <td style="width: 60px" id="satuanUkuran"></td>
                    <td style="width: 100px">%</td>
                    <td style="width: 110px">Rupiah</td>
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
                        value="{{ old('qty[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
                        <input type="hidden" name="kodeGudang[]" class="kodeGudang">
                        <input type="hidden" name="qtyGudang[]" class="qtyGudang">
                      </td>
                      <td>
                        <input type="text" name="satuan[]" id="satuan" class="form-control form-control-sm text-bold text-dark text-right satuan" 
                        value="{{ old('satuan[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
                      </td>
                      <td>
                        <input type="text" name="tipe[]" id="tipe" class="form-control form-control-sm text-bold text-dark text-center tipe" 
                        value="{{ old('tipe[]') }}">
                      </td>
                      <td>
                        <input type="text" name="harga[]" id="harga" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" value="{{ old('harga[]') }}">
                      </td>
                      <td>
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ old('jumlah[]') }}" >
                      </td>
                      <td>
                        <input type="text" name="diskon[]" id="diskon" class="form-control form-control-sm text-bold text-right text-dark diskon" 
                        value="{{ old('diskon[]') }}" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9 dan tanda +">
                      </td>
                      <td>
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
                    <div class="modal modalGudang" id="{{$i-1}}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="h2 text-bold">&times;</span>
                            </button>
                            <h4 class="modal-title text-bold">Pilih Gudang</h4>
                          </div>
                          <div class="modal-body text-dark">
                            <p>Qty order melebihi stok pada gudang <strong>{{ $stok[0]->gudang->nama }}</strong>. Pilih gudang lainnya untuk memenuhi qty order.</p>
                            <input type="hidden" id="kodeModal{{$i-1}}" value="{{$i-1}}">
                            <div class="form-group row" style="margin-top: -10px">
                              <label for="kode" class="col-4 col-form-label text-bold">Qty Order</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold qtyOrder"></span>
                              <span class="col-form-label text-bold qtySatuan"></span>
                              <span class="col-form-label text-bold qtyOrderUkuran"></span>
                              <span class="col-form-label text-bold qtyUkuran"></span>
                            </div>
                            <div class="form-group row" style="margin-top: -30px">
                              <label for="kode" class="col-4 col-form-label text-bold">Stok {{ $gudang[0]->nama }}</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold stokJohar"></span>
                              <span class="col-form-label text-bold stokSatuan"></span>
                              <span class="col-form-label text-bold stokJoharUkuran"></span>
                              <span class="col-form-label text-bold stokUkuran"></span>
                            </div>
                            <div class="form-group row" style="margin-top: -20px">
                              <label for="kode" class="col-4 col-form-label text-bold">Sisa Qty Order</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold sisaQty"></span>
                              <span class="col-form-label text-bold sisaSatuan"></span>
                              <span class="col-form-label text-bold sisaQtyUkuran"></span>
                              <span class="col-form-label text-bold sisaUkuran"></span>
                            </div>
                            <label for="pilih" style="margin-bottom: -5px">Pilih Gudang Tambahan</label>
                            @foreach($gudang as $g)
                              @if($g->id != "GDG01")
                                <div class="row">
                                <label for="kode" class="col-8 col-form-label text-bold">{{ $g->nama }} (Stok : <span class="col-form-label text-bold stokGudang{{$i-1}}"></span><span class="col-form-label text-bold gudangSatuan{{$i-1}}"></span><span class="col-form-label text-bold stokGudangUkuran{{$i-1}}"></span><span class="col-form-label text-bold gudangUkuran{{$i-1}}"></span>)</label>
                                  <input type="hidden" class="kodeGud{{$i-1}}" value="{{$g->id}}">
                                  <div class="col-3">
                                    <button type="button" class="btn btn-sm btn-success btn-block text-bold mt-1 btnPilih{{$i-1}}">Pilih</button>
                                  </div>
                                </div>
                              @endif
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="modal" id="notif{{$i-1}}" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" class="h2 text-bold">&times;</span>
                            </button>
                            <h4 class="modal-title text-bold">Notifikasi Stok Barang</h4>
                          </div>
                          <div class="modal-body text-dark">
                            <h5>Qty input tidak bisa melebihi total stok. Total stok untuk barang <span class="col-form-label text-bold nmbrg"></span> adalah <span class="col-form-label text-bold totalstok"></span> atau <span class="col-form-label text-bold totalsatuan"></span></h5>
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
              {{-- @if($itemsRow != 0) 
                @php
                  $diskonFaktur = ($items[0]->diskon_faktur * $subtotal) / 100;
                  $totalNotPPN = $subtotal - $diskonFaktur;
                  $ppn = $totalNotPPN * 10 / 100;
                  $grandtotal = $totalNotPPN + $ppn;
                @endphp
              @endif --}}
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
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext form-control-sm text-bold text-secondary text-right mt-1" />
                  {{-- @if($itemsRow != 0) 
                    value="{{ $totalNotPPN }}"
                  @endif --}}
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Diskon Faktur</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="diskonFaktur" id="diskonFaktur" class="form-control form-control-sm text-bold text-dark text-right mt-1 diskon-faktur" placeholder="Input Diskon" onkeypress="return angkaSaja(event, 'OKE')" />
                  {{-- @if($itemsRow != 0) 
                    value="{{ $totalNotPPN }}"
                  @endif --}}
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Total Sebelum PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext form-control-sm text-bold text-secondary text-right mt-1" />
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
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext form-control-sm text-bold text-danger text-right" 
                  {{-- @if($itemsRow != 0) 
                    value="{{ $ppn }}"
                  @endif --}}
                  />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext form-control-sm text-bold text-danger text-right mt-1" 
                  {{-- @if($itemsRow != 0) 
                    value="{{ $grandtotal }}"
                  @endif --}}
                  />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold"
                  onclick="return checkRequired(event)" id="submitSO" >Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
              </div>
              <!-- End Button Submit dan Reset -->

              <!-- Modal Konfirmasi Limit -->
              <div class="modal" id="modalLimit" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title">Konfirmasi Limit <b><span class="col-form-label text-bold" id="limitNama"></span></b></h4>
                    </div>
                    <div class="modal-body">
                      <p>Nilai Total SO untuk customer <strong><span class="col-form-label text-bold" id="limitTitle"></span></strong> melebihi limit senilai <span class="col-form-label text-bold" id="limitAngka"></span>.</p>
                      <hr>
                      <div class="form-group row" style="margin-top: -10px">
                        <label for="kode" class="col-4 col-form-label text-bold">Total Kredit</label>
                        <span class="col-auto col-form-label text-bold">:</span>
                        <span class="col-3 col-form-label text-bold text-right" id="totalKredit"></span>
                      </div>
                      <div class="form-group row" style="margin-top: -30px">
                        <label for="kode" class="col-4 col-form-label text-bold">Total SO</label>
                        <span class="col-auto col-form-label text-bold">:</span>
                        <span class="col-3 col-form-label text-bold text-right" id="totalSO"></span>
                      </div>
                      <div class="form-group row" style="margin-top: -20px">
                        <label for="kode" class="col-4 col-form-label text-bold">Total Tagihan</label>
                        <span class="col-auto col-form-label text-bold">:</span>
                        <span class="col-3 col-form-label text-bold text-right" id="totalTagihan"></span>
                      </div>
                      <hr>
                      <p>Silahkan pilih untuk simpan atau batal.</p>
                      <div class="form-row justify-content-center">
                        <div class="col-3">
                          {{-- <a href="{{ url('/so/process/'.$newcode.'/CETAK') }}" class="btn btn-success btn-block text-bold btnCetak">Cetak</a> --}}
                          <button type="submit" formaction="{{ route('so-process', ['id' => $newcode, 'status' => 'LIMIT']) }}" formmethod="POST" class="btn btn-success btn-block text-bold btnCetak">Simpan</button>
                        </div>
                        <div class="col-3">
                          <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-block text-bold">Batal</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Modal Konfirmasi -->

              <!-- Modal Konfirmasi Cetak atau Input -->
              <div class="modal" id="modalKonfirm" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title">Konfirmasi Faktur <b>{{$newcode}}</b></h4>
                    </div>
                    <div class="modal-body">
                      <p>Faktur <strong>{{$newcode}}</strong> akan disimpan. Silahkan pilih cetak atau input faktur lagi.</p>
                      <hr>
                      <div class="form-row justify-content-center">
                        <div class="col-3">
                          {{-- <a href="{{ url('/so/process/'.$newcode.'/CETAK') }}" class="btn btn-success btn-block text-bold btnCetak">Cetak</a> --}}
                          <button type="submit" formaction="{{ route('so-process', ['id' => $newcode, 'status' => 'CETAK']) }}" formmethod="POST" class="btn btn-success btn-block text-bold btnCetak">Cetak</button>
                        </div>
                        <div class="col-3">
                          <button type="submit" formaction="{{ route('so-process', ['id' => $newcode, 'status' => 'INPUT']) }}" formmethod="POST" class="btn btn-outline-secondary btn-block text-bold">Input Lagi</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Modal Konfirmasi -->

              @if($status == 'true')
                <!-- Tampilan Cetak -->
                <iframe src="{{url('so/cetak/'.$lastcode)}}" id="frameCetak" frameborder="0" hidden></iframe>
                <iframe src="{{url('so/cetak-ttr/'.$lastcode)}}" id="frameTTR" frameborder="0" hidden></iframe>
              @endif

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
<script src="{{ url('backend/vendor/jquery/jquery.printPageSO.js') }}"></script>
<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">

@if($status == 'true')
  // $(document).ready(function() {
  //   $("#frameCetak").printPage();
  //   $("#frameTTR").printPage();
  // });

  const printFrame = document.getElementById("frameCetak").contentWindow;
  const printTTR = document.getElementById("frameTTR").contentWindow;

  printFrame.window.onafterprint = function(e) {
    alert('ok');
  }

  printTTR.window.print();
  printFrame.window.print();
  window.print();
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

const namaCust = document.getElementById('namaCustomer');
const kodeCust = document.getElementById('idCustomer');
const limit = document.getElementById('limit');
const piutang = document.getElementById('piutang');
const namaSales = document.getElementById('namaSales');
const npwp = document.getElementById('npwp');
const tempo = document.getElementById('tempo');
const tanggalKirim = document.getElementById('tanggalKirim');
const radios = document.querySelectorAll('input[type=radio][name="kategori"]');
const kategori = document.getElementById('kategori');
const pkp = document.getElementById('pkp');
const pcs = document.getElementById("pcs");
const satuanUkuran = document.getElementById("satuanUkuran");
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const satuan = document.querySelectorAll(".satuan");
const kodeGudang = document.querySelectorAll(".kodeGudang");
const qtyGudang = document.querySelectorAll(".qtyGudang");
const tipe = document.querySelectorAll(".tipe");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const diskon = document.querySelectorAll(".diskon");
const diskonRp = document.querySelectorAll(".diskonRp");
const netto = document.querySelectorAll(".netto");
const hapusBaris = document.querySelectorAll(".icRemove");
const subTotal = document.getElementById('subtotal');
const diskonFaktur = document.getElementById('diskonFaktur');
const totalNotPPN = document.getElementById('totalNotPPN');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
const newRow = document.getElementsByClassName('table-add')[0];
const jumBaris = document.getElementById('jumBaris');
const teksJohar = document.querySelectorAll('.stokJohar');
const teksSatuan = document.querySelectorAll('.stokSatuan');
const teksJoharUkuran = document.querySelectorAll('.stokJoharUkuran');
const teksUkuran = document.querySelectorAll('.stokUkuran');
const qtyOrder = document.querySelectorAll('.qtyOrder');
const qtySatuan = document.querySelectorAll('.qtySatuan');
const qtyOrderUkuran = document.querySelectorAll('.qtyOrderUkuran');
const qtyUkuran = document.querySelectorAll('.qtyUkuran');
const sisaQty = document.querySelectorAll('.sisaQty');
const sisaSatuan = document.querySelectorAll('.sisaSatuan');
const sisaQtyUkuran = document.querySelectorAll('.sisaQtyUkuran');
const sisaUkuran = document.querySelectorAll('.sisaUkuran');
const modalGudang = document.querySelectorAll(".modalGudang");
const totalstok = document.querySelectorAll(".totalstok");
const totalsatuan = document.querySelectorAll(".totalsatuan");
const nmbrg = document.querySelectorAll(".nmbrg");
const totalKredit = document.getElementById('totalKredit');
const totalSO = document.getElementById('totalSO');
const totalTagihan = document.getElementById('totalTagihan');
const limitTitle = document.getElementById('limitTitle');
const limitNama = document.getElementById('limitNama');
const limitAngka = document.getElementById('limitAngka');
var netPast; var ukuran;
var kodeModal;
var totPast;
var sisa; var stokJohar; var stokLain; var totStok;

/** Call Fungsi Setelah Inputan Terisi **/
namaCust.addEventListener('keyup', displayCust);
namaCust.addEventListener('focusout', focusTanggal);
tanggalKirim.addEventListener("keyup", formatTanggal);
newRow.addEventListener("click", displayRow);
diskonFaktur.addEventListener('keyup', formatNominal);
diskonFaktur.addEventListener('keyup', displayTotal);

Array.prototype.forEach.call(radios, function(radio) {
   radio.addEventListener('change', displayTempo);
});

/** Tampil Id Supplier **/
function displayCust(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }}' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
      limit.value = '{{ $c->limit }}';
      namaSales.value = '{{ $c->sales->nama }}';
      npwp.value = '{{ $c->npwp }}';

      @foreach($totalKredit as $t)
        if('{{ $t->id_customer }}' == kodeCust.value) {
          piutang.value = '{{ $t->total }}';
        }
      @endforeach
    }
    else if(e.target.value == '') {
      kodeCust.value = '';
      limit.value = '';
      namaSales.value = '';
      npwp.value = '';
    }
  @endforeach
}

function focusTanggal(e) {
  tanggalKirim.focus();
}

function formatTanggal(e) {
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if(value.length > 2 && value.length <= 4) 
    value = value.slice(0,2) + "-" + value.slice(2);
  else if(value.length > 4 && value.length <= 8)
    value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
  
  tanggalKirim.value = value;
}

function focusKode(e) {
  kodeBarang[0].focus();
}

/** Tampil Input Tempo **/
function displayTempo(e) {
  if((radios[1].checked) || (radios[2].checked)) {
    tempo.removeAttribute('readonly');
    tempo.setAttribute('required', 'true');
  }
  else if(radios[0].checked) {
    tempo.setAttribute('readonly', 'true');
    tempo.removeAttribute('required');
  }
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
        <input type="text" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark kdBrgRow">
      </td>
      <td>
        <input type="text" name="namaBarang[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark nmBrgRow">
      </td>
      <td>
        <input type="text" name="qty[]" id="qtyRow${newNum}" class="form-control form-control-sm text-bold text-dark text-right qtyRow" value="{{ old('qty[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
        <input type="hidden" name="kodeGudang[]" class="kodeGudangRow" id="kodeGudangRow${newNum}">
        <input type="hidden" name="qtyGudang[]" class="qtyGudangRow" id="qtyGudangRow${newNum}">
      <td>
        <input type="text" name="satuan[]" id="satuanRow${newNum}" class="form-control form-control-sm text-bold text-dark text-right satuanRow" 
        value="{{ old('satuan[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
      </td>
      <td>
        <input type="text" name="tipe[]" id="tipeRow${newNum}" class="form-control form-control-sm text-bold text-dark text-center tipeRow" 
        value="{{ old('tipe[]') }}">
      </td>
      <td>
        <input type="text" name="harga[]" id="hargaRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right hargaRow">
      </td>
      <td>
        <input type="text" name="jumlah[]" id="jumlahRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlahRow">
      </td>
      <td style="width: 60px">
        <input type="text" name="diskon[]" id="diskonRow${newNum}" class="form-control form-control-sm text-bold text-right text-dark diskonRow" 
        value="{{ old('diskon[]') }}" onkeypress="return angkaPlus(event, ${newNum})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9 dan tanda +">
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

  const newModal = `
    <div class="modal modalGudangRow" id="gud${newNum}" tabindex="-1" role="dialog" aria-labelledby="${newNum}" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="h2 text-bold">&times;</span>
            </button>
            <h4 class="modal-title text-bold">Pilih Gudang</h4>
          </div>
          <div class="modal-body text-dark">
            <p>Qty order melebihi stok pada gudang <strong>{{ $stok[0]->gudang->nama }}</strong>. Pilih gudang lainnya untuk memenuhi qty order.</p>
            <input type="hidden" id="kodeModal${newNum}" value="${newNum}">
            <div class="form-group row" style="margin-top: -10px">
              <label for="kode" class="col-4 col-form-label text-bold">Qty Order</label>
              <span class="col-auto col-form-label text-bold">:</span>
              <span id="qtyOrder${newNum}" class="col-form-label text-bold"></span>
              <span id="qtySatuan${newNum}" class="col-form-label text-bold"></span>
              <span id="qtyOrderUkuran${newNum}" class="col-form-label text-bold"></span>
              <span id="qtyUkuran${newNum}" class="col-form-label text-bold qtyUkuran"></span>
            </div>
            <div class="form-group row" style="margin-top: -30px">
              <label for="kode" class="col-4 col-form-label text-bold">Stok {{ $gudang[0]->nama }}</label>
              <span class="col-auto col-form-label text-bold">:</span>
              <span id="stokJohar${newNum}" class="col-form-label text-bold"></span>
              <span id="stokSatuan${newNum}" class="col-form-label text-bold"></span>
              <span id="stokJoharUkuran${newNum}" class="col-form-label text-bold"></span>
              <span id="stokUkuran${newNum}" class="col-form-label text-bold"></span>
            </div>
            <div class="form-group row" style="margin-top: -20px">
              <label for="kode" class="col-4 col-form-label text-bold">Sisa Qty Order</label>
              <span class="col-auto col-form-label text-bold">:</span>
              <span class="col-form-label text-bold" id="sisaQty${newNum}"></span>
              <span class="col-form-label text-bold" id="sisaSatuan${newNum}"></span>
              <span class="col-form-label text-bold" id="sisaQtyUkuran${newNum}"></span>
              <span class="col-form-label text-bold" id="sisaUkuran${newNum}"></span>
            </div>
            <label for="pilih" style="margin-bottom: -5px">Pilih Gudang Tambahan</label>
            @foreach($gudang as $g)
              @if($g->id != "GDG01")
                <div class="row">
                <label for="kode" class="col-8 col-form-label text-bold">{{ $g->nama }} (Stok : <span class="col-form-label text-bold stokGudangRow${newNum}"></span>)</label>
                  <input type="hidden" class="kodeGudRow${newNum}" value="{{$g->id}}">
                  <div class="col-3">
                    <button type="button" class="btn btn-sm btn-success btn-block text-bold mt-1 btnPilihRow${newNum}">Pilih</button>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="modal" id="notif${newNum}" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
            </button>
            <h4 class="modal-title text-bold">Notifikasi Stok Barang</h4>
          </div>
          <div class="modal-body text-dark">
            <h5>Qty input tidak bisa melebihi total stok. Total stok untuk barang <span class="col-form-label text-bold nmbrgRow" id="nmbrgRow${newNum}"></span> adalah <span class="col-form-label text-bold totalstokRow" id="totalstokRow${newNum}"></span> atau <span class="col-form-label text-bold totalsatuanRow" id="totalsatuanRow${newNum}"></span></h5>
          </div>
        </div>
      </div>
    </div>
  `;

  $(tablePO).append(newTr);
  $(tablePO).append(newModal);
  jumBaris.value = newNum;
  const newRow = document.getElementById(newNum);
  const newMod = document.getElementById("gud"+newNum);
  const newModNotif = document.getElementById("notif"+newNum);
  const brgRow = document.getElementById("nmBrgRow"+newNum);
  const kodeRow = document.getElementById("kdBrgRow"+newNum);
  const qtyRow = document.getElementById("qtyRow"+newNum);
  const satuanRow = document.getElementById("satuanRow"+newNum);
  const kodeGudangRow = document.getElementById("kodeGudangRow"+newNum);
  const qtyGudangRow = document.getElementById("qtyGudangRow"+newNum);
  const tipeRow = document.getElementById("tipeRow"+newNum);
  const hargaRow = document.getElementById("hargaRow"+newNum);
  const jumlahRow = document.getElementById("jumlahRow"+newNum);
  const diskonRow = document.getElementById("diskonRow"+newNum);
  const diskonRpRow = document.getElementById("diskonRpRow"+newNum);
  const nettoRow = document.getElementById("nettoRow"+newNum);
  const hapusRow = document.getElementById("icRemoveRow"+newNum);
  const teksJoharRow = document.getElementById("stokJohar"+newNum);
  const teksSatuanRow = document.getElementById("stokSatuan"+newNum);
  const teksJoharUkuranRow = document.getElementById("stokJoharUkuran"+newNum);
  const teksUkuranRow = document.getElementById("stokUkuran"+newNum);
  const qtyOrderRow = document.getElementById("qtyOrder"+newNum);
  const qtySatuanRow = document.getElementById("qtySatuan"+newNum);
  const qtyOrderUkuranRow = document.getElementById("qtyOrderUkuran"+newNum);
  const qtyUkuranRow = document.getElementById("qtyUkuran"+newNum);
  const sisaQtyRow = document.getElementById("sisaQty"+newNum);
  const sisaSatuanRow = document.getElementById("sisaSatuan"+newNum);
  const sisaQtyUkuranRow = document.getElementById("sisaQtyUkuran"+newNum);
  const sisaUkuranRow = document.getElementById("sisaUkuran"+newNum);
  const totalstokRow = document.getElementById("totalstokRow"+newNum);
  const totalsatuanRow = document.getElementById("totalsatuanRow"+newNum);
  const nmbrgRow = document.getElementById("nmbrgRow"+newNum);
  var ukuranRow;

  /** Tampil Harga **/
  brgRow.addEventListener("keyup", displayHargaRow); 
  kodeRow.addEventListener("keyup", displayHargaRow);
  
  function displayHargaRow(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      $(this).parents('tr').find('input').val('');
      qtyRow.removeAttribute('required');
    } 

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeRow.value = '{{ $br->id }}';
        brgRow.value = '{{ $br->nama }}';
      }
    @endforeach

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeRow.value = '{{ $br->id }}';
        brgRow.value = '{{ $br->nama }}';
        satuanUkuran.innerHTML = '{{ substr($br->satuan, -3) }}';
        if(satuanUkuran.innerHTML == 'Dus')
          pcs.innerHTML = 'Pcs';
        else
          pcs.innerHTML = 'Meter';
        ukuranRow = '{{ $br->ukuran }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeRow.value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipeRow.value = '{{ $hb->hargaBarang->tipe }}';
        hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
        qtyRow.setAttribute('required', true);
      }
    @endforeach

    kodeGudangRow.value = 'GDG01';
    qtyRow.value = '';
    satuanRow.value = '';
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
  qtyRow.addEventListener("change", displayQtyRow);
  satuanRow.addEventListener("change", displayQtyRow);

  function displayQtyRow(e) {
    stokJohar = 0;
    stokLain = [];
    totStok = 0;
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeRow.value) && ('{{ $s->id_gudang }}' == 'GDG01')) {
        stokJohar = '{{ $s->stok }}';
        totStok = +totStok + +stokJohar;
      }
      else if('{{ $s->id_barang }}' == kodeRow.value){
        stokLain.push('{{ $s->stok }}');
        totStok = +totStok + +'{{ $s->stok }}';
      }
    @endforeach

    hitungQtyRow(e.target.id, e.target.value);

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      jumlahRow.value = "";
      nettoRow.value = "";
      kodeGudangRow.value = "GDG01";
      qtyGudangRow.value = "";
      qtyRow.value = "";
      satuanRow.value = "";
    }
    else if(((e.target.id == `qtyRow${newNum}`) && (+e.target.value > totStok)) || ((e.target.id == `satuanRow${newNum}`) && (+e.target.value * +ukuranRow) > totStok)) {
      $('#notif'+newNum).modal("show");
      nmbrgRow.textContent = brgRow.value;
      totalstokRow.textContent = `${totStok} ${pcs.innerHTML}`;
      totalsatuanRow.textContent = `${totStok / ukuranRow} ${satuanUkuran.innerHTML}`;

      qtyRow.value = "";
      satuanRow.value = "";
      jumlahRow.value = "";
      nettoRow.value = "";

      return false;
    }
    else {
      if(((e.target.id == `qtyRow${newNum}`) && (+e.target.value > stokJohar)) || ((e.target.id == `satuanRow${newNum}`) && (+e.target.value * +ukuranRow) > stokJohar)) {
        $('#gud'+newNum).modal("show");
        kodeModal = newNum;
        teksJoharRow.textContent = `${stokJohar}`;
        teksSatuanRow.textContent = `\u00A0${pcs.innerHTML} /\u00A0`;
        teksJoharUkuranRow.textContent = `${stokJohar / ukuranRow}`;
        teksUkuranRow.textContent = `\u00A0${satuanUkuran.innerHTML}`;

        qtyOrderRow.textContent = `${qtyRow.value}`;
        qtySatuanRow.textContent = `\u00A0${pcs.innerHTML} /\u00A0`;
        qtyOrderUkuranRow.textContent = `${qtyRow.value / ukuranRow}`;
        qtyUkuranRow.textContent = `\u00A0${satuanUkuran.innerHTML}`;

        sisaQtyRow.textContent = `${+qtyRow.value - +stokJohar}`;
        sisaSatuanRow.textContent = `\u00A0${pcs.innerHTML} /\u00A0`;
        sisaQtyUkuranRow.textContent = `${(qtyRow.value - +stokJohar) / ukuranRow}`;
        sisaUkuranRow.textContent = `\u00A0${satuanUkuran.innerHTML}`;
        const stokGudangRow = document.querySelectorAll('.stokGudangRow'+newNum);
        for(let i = 0; i < stokGudangRow.length; i++) {
          stokGudangRow[i].textContent = `${stokLain[i]} ${pcs.innerHTML} / ${stokLain[i] / ukuranRow} ${satuanUkuran.innerHTML}`;
        }
        qtyGudangRow.value = stokJohar;
      }
      else {
        kodeGudangRow.value = "GDG01";
        qtyGudangRow.value = e.target.value;
      }

      netPast = +nettoRow.value.replace(/\./g, "");
      jumlahRow.value = addCommas(e.target.value * hargaRow.value.replace(/\./g, ""));
      if(diskonRow.value != "") {
        var angkaDiskon = hitungDiskon(diskonRow.value)
        diskonRpRow.value = addCommas(angkaDiskon * jumlahRow.value.replace(/\./g, "") / 100);
      }

      nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
      checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    ppn.value = 0;
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  }

  function hitungQtyRow(kode, angka) {
    if(kode == `qtyRow${newNum}`)
      satuanRow.value = +angka / +ukuranRow;
    else if(kode == `satuanRow${newNum}`) 
      qtyRow.value = +angka * +ukuranRow;
  }

  $('#gud'+newNum).on('shown.bs.modal', function(e) {
    const kodeGudRow = document.querySelectorAll(".kodeGudRow"+newNum);
    const stokGudangRow = document.querySelectorAll('.stokGudangRow'+newNum);
    const btnPilihRow = document.querySelectorAll(".btnPilihRow"+newNum);
    for(let i = 0; i < btnPilihRow.length; i++) {
      btnPilihRow[i].addEventListener("click", function (e) {
        kodeGudangRow.value = 'GDG01';
        qtyGudangRow.value = stokJohar;
        totPast = +qtyGudangRow.value + +stokGudangRow[i].textContent;
        if(totPast < qtyOrderRow.textContent) {
          sisa = +sisaQtyRow.textContent - +stokGudangRow[i].textContent;
          qtyGudangRow.value = qtyGudangRow.value.concat(`,${stokGudangRow[i].textContent}`);
          qtyGudangRow.value = qtyGudangRow.value.concat(`,${sisa}`);
          kodeGudangRow.value = kodeGudangRow.value.concat(`,${kodeGudRow[i].value}`);
          @foreach($gudang as $g)
            if(('{{ $g->id }}' != kodeGudRow.value) && ('{{ $g->id }}' != 'GDG01')) {
              kodeGudangRow.value = kodeGudangRow.value.concat(`,{{ $g->id }}`);
            }
          @endforeach
        }
        else {
          qtyGudangRow.value = qtyGudangRow.value.concat(`,${sisaQtyRow.textContent}`);
          kodeGudangRow.value = kodeGudangRow.value.concat(`,${kodeGudRow[i].value}`);
        }
        $('#gud'+newNum).modal("hide");
      });
    }
  });

  /** Inputan hanya bisa angka **/
  diskonRow.addEventListener("keypress", function (e, evt) {
    evt = (evt) ? evt : window.event;
    var charCodeRow = (evt.which) ? evt.which : evt.keyCode;
    if (charCodeRow > 31 && charCodeRow != 43  && (charCodeRow < 48 || charCodeRow > 57)) {
      $(diskonRow).tooltip('show');
      
      e.preventDefault();
    }
    
    return true;
  });

  /** Tampil Diskon Rp **/
  diskonRow.addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      netPast = nettoRow.value.replace(/\./g, "");
      nettoRow.value = addCommas(+nettoRow.value.replace(/\./g, "") + +diskonRpRow.value.replace(/\./g, ""))
      checkSubtotal(netPast, nettoRow.value.replace(/\./g, ""));
      diskonRpRow.value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      netPast = +nettoRow.value.replace(/\./g, "")
      diskonRpRow.value = addCommas((angkaDiskon * jumlahRow.value.replace(/\./g, "") / 100).toFixed(0));
      nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""))
      checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    ppn.value = 0;
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  });
  
  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    if(qtyRow.value != "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      total_ppn(totalNotPPN.value.replace(/\./g, ""));
    }
    
    const curNum = $(this).closest('tr').find('td:first-child').text();
    const lastNum = $(tablePO).find('tr:last').attr("id");
    console.log(curNum);
    console.log(lastNum);
    if(+curNum < +lastNum) {
      $(newRow).remove();
      $(newMod).remove();
      $(newModNotif).remove();
      var j = curNum;
      console.log(tablePO);
      var selisih = +lastNum - +curNum;
      for(let i = +curNum; i < +lastNum; i++) {
        $(tablePO).find('tr:nth-child('+i+') td:first-child').html(i);
      }
    }
    else if(+curNum == +lastNum) {
      $(newRow).remove();
    }
    jumBaris.value -= 1;
  })

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
  brgNama[i].addEventListener("keyup", displayHarga) ;
  kodeBarang[i].addEventListener("keyup", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
        satuanUkuran.innerHTML = '{{ substr($br->satuan, -3) }}';
        if(satuanUkuran.innerHTML == 'Dus')
          pcs.innerHTML = 'Pcs';
        else
          pcs.innerHTML = 'Meter';
        ukuran = '{{ $br->ukuran }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipe[i].value = '{{ $hb->hargaBarang->tipe }}';
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        qty[i].setAttribute('required', true);
      }
    @endforeach

    kodeGudang[i].value = 'GDG01';
    qty[i].value = '';
    satuan[i].value = '';
  }
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("change", displayQty);
  satuan[i].addEventListener("change", displayQty);

  function displayQty(e) {
    stokJohar = 0;
    stokLain = [];
    totStok = 0;
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->id_gudang }}' == 'GDG01')) {
        stokJohar = '{{ $s->stok }}';
        totStok = +totStok + +stokJohar;
      }
      else if('{{ $s->id_barang }}' == kodeBarang[i].value){
        stokLain.push('{{ $s->stok }}');
        totStok = +totStok + +'{{ $s->stok }}';
      }
    @endforeach

    hitungQty(i, e.target.id, e.target.value);

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      netto[i].value = "";
      kodeGudang[i].value = "GDG01";
      qtyGudang[i].value = "";
      qty[i].value = "";
      satuan[i].value = "";
    }
    else if(((e.target.id == 'qty') && (+e.target.value > totStok)) || ((e.target.id == 'satuan') && (+e.target.value * +ukuran) > totStok)) {
      $('#notif'+i).modal("show");
      nmbrg[i].textContent = brgNama[i].value;
      totalstok[i].textContent = `${totStok} ${pcs.innerHTML}`;
      totalsatuan[i].textContent = `${totStok / ukuran} ${satuanUkuran.innerHTML}`;

      qty[i].value = "";
      satuan[i].value = "";
      jumlah[i].value = "";
      netto[i].value = "";

      return false;
    }
    else {
      if(((e.target.id == 'qty') && (+e.target.value > stokJohar)) || ((e.target.id == 'satuan') && (+e.target.value * +ukuran) > stokJohar)) {
        $('#'+i).modal("show");
        kodeModal = i;
        teksJohar[i].textContent = `${stokJohar}`;
        teksSatuan[i].textContent = `\u00A0${pcs.innerHTML} /\u00A0`;
        teksJoharUkuran[i].textContent = `${stokJohar / ukuran}`;
        teksUkuran[i].textContent = `\u00A0${satuanUkuran.innerHTML}`;
        // qtyOrder[i].textContent = `${qty[i].value} ${pcs.innerHTML} / ${qty[i].value / ukuran} ${satuanUkuran.innerHTML}`;
        qtyOrder[i].textContent = `${qty[i].value}`;
        qtySatuan[i].textContent = `\u00A0${pcs.innerHTML} /\u00A0`;
        qtyOrderUkuran[i].textContent = `${qty[i].value / ukuran}`;
        qtyUkuran[i].textContent = `\u00A0${satuanUkuran.innerHTML}`;

        sisaQty[i].textContent = `${+qty[i].value - +stokJohar}`;
        sisaSatuan[i].textContent = `\u00A0${pcs.innerHTML} /\u00A0`;
        sisaQtyUkuran[i].textContent = `${(qty[i].value - +stokJohar) / ukuran}`;
        sisaUkuran[i].textContent = `\u00A0${satuanUkuran.innerHTML}`;
        const stokGudang = document.querySelectorAll('.stokGudang'+i);
        const gudangSatuan = document.querySelectorAll('.gudangSatuan'+i);
        const stokGudangUkuran = document.querySelectorAll('.stokGudangUkuran'+i);
        const gudangUkuran = document.querySelectorAll('.gudangUkuran'+i);
        for(let i = 0; i < stokGudang.length; i++) {
          stokGudang[i].textContent = `${stokLain[i]}`;
          gudangSatuan[i].textContent = `\u00A0${pcs.innerHTML} /\u00A0`;
          stokGudangUkuran[i].textContent = `${stokLain[i] / ukuran}`;
          gudangUkuran[i].textContent = `\u00A0${satuanUkuran.innerHTML}`;
        }
        qtyGudang[i].value = stokJohar;
      }
      else {
        kodeGudang[i].value = "GDG01";
        qtyGudang[i].value = e.target.value;
      }

      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas(angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100);
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    ppn.value = 0;
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  }
}  

/** Tampil Diskon Rupiah Otomatis **/
for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("keyup", function (e) {
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
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  });
}

/** Tampil Kode Gudang Tambahan **/
for(let j = 0; j < modalGudang.length; j++) {
  $('#'+j).on('shown.bs.modal', function(e) {
    const kodeGud = document.querySelectorAll(".kodeGud"+j);
    const stokGudang = document.querySelectorAll('.stokGudang'+j);
    
    const btnPilih = document.querySelectorAll(".btnPilih"+j);
    for(let i = 0; i < btnPilih.length; i++) {
      btnPilih[i].addEventListener("click", function (e) {
        kodeGudang[j].value = 'GDG01';
        qtyGudang[j].value = stokJohar;
        totPast = +qtyGudang[j].value + +stokGudang[i].textContent;
        if(totPast < qtyOrder[j].textContent) {
          sisa = +sisaQty[j].textContent - +stokGudang[i].textContent;
          qtyGudang[j].value = qtyGudang[j].value.concat(`,${stokGudang[i].textContent}`);
          qtyGudang[j].value = qtyGudang[j].value.concat(`,${sisa}`);
          kodeGudang[j].value = kodeGudang[j].value.concat(`,${kodeGud[i].value}`);
          @foreach($gudang as $g)
            if(('{{ $g->id }}' != kodeGud[i].value) && ('{{ $g->id }}' != 'GDG01')) {
              kodeGudang[j].value = kodeGudang[j].value.concat(`,{{ $g->id }}`);
            }
          @endforeach
        }
        else {
          qtyGudang[j].value = qtyGudang[j].value.concat(`,${sisaQty[j].textContent}`);
          kodeGudang[j].value = kodeGudang[j].value.concat(`,${kodeGud[i].value}`);
        }
        $('#'+j).modal("hide");
      });
    }
  });
}

/** Hitung Qty **/
function hitungQty(urutan, kode, angka) {
  if(kode == 'qty')
    satuan[urutan].value = +angka / +ukuran;
  else if(kode == 'satuan') 
    qty[urutan].value = +angka * +ukuran;
}

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon =  ((totDiskon - 100) * -1).toFixed(2);
  return totDiskon;
}

/** Check Jumlah Netto onChange **/
function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
    totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
    totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") + (+Now - +Past));
  }
}

/** Hitung PPN Dan Total **/
function total_ppn(sub) {
  // ppn.value = addCommas(Math.floor(sub * 10 / 100));
  grandtotal.value = addCommas(+sub + +ppn.value.replace(/\./g, ""));
}

function displayTotal(e) {
  totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +e.target.value.replace(/\./g, ""));
  grandtotal.value = totalNotPPN.value;
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    if(inputan == "tempo") {
      $(tempo).tooltip('show');
    }
    else {
      for(let i = 1; i <= qty.length; i++) {
        if(inputan == i)
          $(qty[inputan-1]).tooltip('show');
      }
    }
    return false;
  }
  return true;
}

/** Inputan hanya bisa angka dan plus **/
function angkaPlus(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && charCode != 43  && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= diskon.length; i++) {
      if(inputan == i)
        $(diskon[inputan-1]).tooltip('show');
    }
    return false;
  }
  return true;
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
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      total_ppn(totalNotPPN.value.replace(/\./g, ""));
    }

    for(let j = i; j < hapusBaris.length; j++) {
      netto[j].value = netto[j+1].value;
      diskonRp[j].value = diskonRp[j+1].value;
      diskon[j].value = diskon[j+1].value;
      jumlah[j].value = jumlah[j+1].value;
      harga[j].value = harga[j+1].value;
      tipe[j].value = tipe[j+1].value;
      satuan[j].value = satuan[j+1].value;
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
  if((namaCust.value == "") || (tanggalKirim.value == "") || 
  (kategori.value == "") || (kodeBarang[0].value == "") || (qty[0].value == "")) {
    e.stopPropagation();
  }
  else {
    if((+grandtotal.value.replace(/\./g, "") + +piutang.value) > +limit.value) {
      document.getElementById("submitSO").dataset.toggle = "modal";
      document.getElementById("submitSO").dataset.target = "#modalLimit";
      limitTitle.textContent = namaCust.value;
      limitNama.textContent = namaCust.value;
      limitAngka.textContent = addCommas(limit.value);
      totalSO.textContent = grandtotal.value;
      var cek = 0;
      @foreach($totalKredit as $t)
        if('{{ $t->id_customer }}' == kodeCust.value) {
          totalKredit.textContent = addCommas('{{ $t->total }}');
          totalTagihan.textContent = addCommas(+'{{ $t->total }}' + +grandtotal.value.replace(/\./g, ""));
          cek = 1;
        }
      @endforeach

      if(cek == 0) {
        totalKredit.textContent = 0;
        totalTagihan.textContent = addCommas(0 + +grandtotal.value.replace(/\./g, ""));
      }
      
      return false;
    } 
    else {
      document.getElementById("submitSO").dataset.toggle = "modal";
      document.getElementById("submitSO").dataset.target = "#modalKonfirm";
      return false;
    }
  }
}

/** Autocomplete Input Text **/
$(function() {
  var barangKode = [];
  var barangNama = [];
  @foreach($barang as $b)
    barangKode.push('{{ $b->id }}');
    barangNama.push('{{ $b->nama }}');
  @endforeach

  var tipeHarga = [];
  @foreach($hrg as $h)
    tipeHarga.push('{{ $h->tipe }}');
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

  /*-- Autocomplete Input Tipe Harga --*/
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