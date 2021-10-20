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
                        <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="kode" value="{{ $newcode }}" readonly>
                      </div>
                      <label for="tanggal" class="col-2 col-form-label text-bold text-right">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" tabindex="2" class="form-control datepicker form-control-sm text-bold mt-1" name="tanggal" value="{{ $tanggal }}" required>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -18px">
                  <label for="customer" class="col-2 col-form-label text-bold">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-3">
                    <input type="text" tabindex="3" name="namaCustomer" id="namaCustomer" placeholder="Nama Customer" class="form-control form-control-sm mt-1" required autofocus/>
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
                    <input type="hidden" name="kodeSales" id="kodeSales"
                    />
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row" style="margin-top: -18px">
                      <label for="tglKirim" class="col-2 col-form-label text-bold">Tanggal Kirim</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" tabindex="4" name="tanggalKirim" id="tanggalKirim" placeholder="DD-MM-YYYY" class="form-control datepicker form-control-sm mt-1" autocomplete="off" required />
                        <input type="hidden" name="jumBaris" id="jumBaris" value="5">
                      </div>
                      <label for="kat" class="col-2 col-form-label text-bold text-right" style="margin-top: -35px">Kategori</label>
                      <span class="col-form-label text-bold" style="margin-top: -35px">:</span>
                        <div class="col-2" style="margin-top: -30px">
                            <select class="form-control form-control-sm" tabindex="5" id="kategori" name="kategori">
                                <option value="CPL" selected>CPL</option>
                                @foreach($kategori as $item)
                                    @if(($item->nama == 'MCB') || ($item->nama == 'BOSS'))
                                        <option value="{{ $item->nama }}">{{ $item->nama }}</option>
                                    @else
                                        <option value="{{ ucfirst(strtolower($item->nama)) }}">{{ ucfirst(strtolower($item->nama)) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
{{--                      <div class="col-auto" style="margin-top: -35px">--}}
{{--                        <div class="form-check form-check-inline mt-2">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="CPL" id="kategori" required>--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat1">CPL</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline mt-2" style="margin-left: 5px">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="Phillips" id="kategori" required>--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat2">Phillips</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="Prime">--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat3">Prime</label>--}}
{{--                        </div>--}}
{{--                        <br>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="MCB">--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat6">MCB</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="Extrana">--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat4">Extrana</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="Nitto">--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat5">Nitto</label>--}}
{{--                        </div>--}}
{{--                        <br>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="BOSS">--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat7">Pipa</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori"  value="Maspion">--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat8">Maspion</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-check form-check-inline" style="margin-left: -1px">--}}
{{--                          <input class="form-check-input" tabindex="5" type="radio" name="kategori" value="Panasonic">--}}
{{--                          <label class="form-check-label text-bold text-dark" for="kat7">Panasonic</label>--}}
{{--                        </div>--}}
{{--                      </div>--}}
                    </div>
                  </div>
                  <div class="col" style="margin-left: -350px; margin-right:-40px; margin-top: -120px">
                    <div class="form-group row subtotal-po">
                      <label for="tempo" class="col-6 col-form-label text-bold text-right">Jatuh Tempo</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" tabindex="6" class="form-control form-control-sm text-bold mt-1" name="tempo" id="tempo" onkeypress="return angkaSaja(event, 'tempo', 'tem')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" readonly >
                      </div>
                      <span class="col-form-label text-bold input-right">hari</span>
                    </div>
                    <div class="form-group row total-po">
                      <label for="pkp" class="col-6 col-form-label text-bold text-right">PKP</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3 pkp-check">
                        <div class="form-check mt-2">
                          <input class="form-check-input" tabindex="5" type="radio" name="pkp" id="pkp" value="1" >
                          <label class="form-check-label text-bold text-dark" for="pkp1">Ya</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" tabindex="5" type="radio" name="pkp" id="pkp" value="0" >
                          <label class="form-check-label text-bold text-dark" for="pkp2">Tidak</label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="jenis" class="col-6 col-form-label text-bold text-right">Jenis</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3 jenis-check">
                        <div class="form-check mt-2">
                          <input class="form-check-input" tabindex="5" type="radio" name="jenis" id="jenis" value="C" required checked disabled>
                          <label class="form-check-label text-bold text-dark" for="jenis1">Cash</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" tabindex="5" type="radio" name="jenis" id="jenis" value="T" disabled>
                          <label class="form-check-label text-bold text-dark" for="jenis2">Tempo</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Tabel Data Detil PO -->
              <span class="table-add float-right mb-3 mr-2"><a href="#!" tabindex="-1" class="text-primary text-bold">
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
                  @php $tab = 6; @endphp
                  @for($i=1; $i<=5; $i++)
                    <tr class="text-bold text-dark" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" tabindex="{{ $tab++ }}" name="kodeBarang[]" id="kodeBarang" class="form-control form-control-sm text-bold text-dark kodeBarang"
                        value="{{ old('kodeBarang[]') }}" @if($i == 1) required @endif >
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 2 }}" name="namaBarang[]" id="namaBarang" class="form-control form-control-sm text-bold text-dark namaBarang"
                        value="{{ old('namaBarang[]') }}" @if($i == 1) required @endif>
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 3 }}" name="qty[]" id="qty" class="form-control form-control-sm text-bold text-dark text-right qty"
                        value="{{ old('qty[]') }}" onkeypress="return angkaSaja(event, {{$i}}, 'qty')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                        <input type="hidden" name="teksSat[]" class="teksSat">
                        <input type="hidden" name="teksSatUk[]" class="teksSatUk">
                        <input type="hidden" name="ukuran[]" class="ukuran">
                        <input type="hidden" name="kodeGudang[]" class="kodeGudang">
                        <input type="hidden" name="qtyGudang[]" class="qtyGudang">
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 4 }}" name="satuan[]" id="satuan" class="form-control form-control-sm text-bold text-dark text-right satuan"
                        value="{{ old('satuan[]') }}" onkeypress="return angkaSaja(event, {{$i}}, 'sat')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 5 }}" name="tipe[]" id="tipe" class="form-control form-control-sm text-bold text-dark text-center tipe"
                        value="{{ old('tipe[]') }}">
                      </td>
                      <td>
                        <input type="text" name="harga[]" id="harga" readonly class="form-control form-control-sm text-bold text-dark text-right harga" value="{{ old('harga[]') }}" onkeypress="return angkaSajaHarga(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                      </td>
                      <td>
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ old('jumlah[]') }}" >
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 6 }}" name="diskon[]" id="diskon" class="form-control form-control-sm text-bold text-right text-dark diskon"
                        value="{{ old('diskon[]') }}" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9 dan tanda +" autocomplete="off">
                      </td>
                      <td>
                        <input type="text" name="diskonRp[]" id="diskonRp" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark diskonRp"
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
                            <h5>Qty input tidak bisa melebihi total stok. Total stok <b>Kenari</b> untuk barang <span class="col-form-label text-bold nmbrg"></span> adalah <span class="col-form-label text-bold totalstok"></span><span class="col-form-label text-bold totalsatuan"></span></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endfor
                </tbody>
              </table>

              <div class="form-group row justify-content-end subtotal-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext form-control-sm text-bold text-secondary text-right mt-1" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Diskon Faktur</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="diskonFaktur" id="diskonFaktur" class="form-control form-control-sm text-bold text-dark text-right mt-1 diskon-faktur" placeholder="Input Diskon" onkeypress="return angkaSaja(event, 'OKE')" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Total Sebelum PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext form-control-sm text-bold text-secondary text-right mt-1" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext form-control-sm text-bold text-danger text-right"
                  />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext form-control-sm text-bold text-danger text-right mt-1"
                  />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" tabindex="{{ $tab++ }}" class="btn btn-success btn-block text-bold" onclick="return checkRequired(event)" id="submitSO" >Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" tabindex="{{ $tab += 2 }}" class="btn btn-outline-secondary btn-block text-bold" id="resetSO">Reset</button>
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
                          <button type="submit" formaction="{{ route('so-process-kenari', ['id' => $newcode, 'status' => 'LIMIT']) }}" formmethod="POST" class="btn btn-success btn-block text-bold btnCetak">Simpan</button>
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
                          <button type="submit" formaction="{{ route('so-process-kenari', ['id' => $newcode, 'status' => 'CETAK']) }}" formmethod="POST" class="btn btn-success btn-block text-bold btnCetak">Cetak</button>
                        </div>
                        <div class="col-3">
                          <button type="submit" formaction="{{ route('so-process-kenari', ['id' => $newcode, 'status' => 'INPUT']) }}" formmethod="POST" class="btn btn-outline-secondary btn-block text-bold">Input Lagi</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Modal Konfirmasi -->

              <div class="modal" id="modalDuplikat" tabindex="-1" role="dialog" aria-labelledby="modalNotif" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="h2 text-bold">&times;</span>
                      </button>
                      <h4 class="modal-title text-bold">Notifikasi Barang</h4>
                    </div>
                    <div class="modal-body text-dark">
                      <h5>Terdapat <b>Kode Barang</b> yang sama. Silahkan <b>Jumlahkan Qty pada Kode Barang yang Sama </b>atau ubah kode barang.</h5>
                    </div>
                  </div>
                </div>
              </div>

              {{-- @if($status == 'true')
                <!-- Tampilan Cetak -->
                <iframe src="{{url('kenari/so/cetak/'.$lastSO[0]->id)}}" id="frameCetak" name="frameCetak" frameborder="0" hidden></iframe>
              @endif --}}

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
/* @if($status == 'true')
  const printFrame = document.getElementById("frameCetak").contentWindow;

  printFrame.window.onafterprint = function(e) {
    window.location = "{{ route('so-after-print-kenari', $lastSO[0]->id) }}";
  }

  printFrame.window.focus();
  printFrame.window.print();
@endif */

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
const kodeSales = document.getElementById('kodeSales');
const npwp = document.getElementById('npwp');
const tempo = document.getElementById('tempo');
const tanggalKirim = document.getElementById('tanggalKirim');
const radios = document.querySelectorAll('input[type=radio][name="kategori"]');
const kategori = document.getElementById('kategori');
const radiosJen = document.querySelectorAll('input[type=radio][name="jenis"]');
const jenis = document.getElementById('jenis');
const pkp = document.getElementById('pkp');
const pcs = document.getElementById("pcs");
const satuanUkuran = document.getElementById("satuanUkuran");
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const teksSat = document.querySelectorAll(".teksSat");
const teksSatUk = document.querySelectorAll(".teksSatUk");
const ukuran = document.querySelectorAll(".ukuran");
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
const totalstok = document.querySelectorAll(".totalstok");
const totalsatuan = document.querySelectorAll(".totalsatuan");
const nmbrg = document.querySelectorAll(".nmbrg");
const totalKredit = document.getElementById('totalKredit');
const totalSO = document.getElementById('totalSO');
const totalTagihan = document.getElementById('totalTagihan');
const limitTitle = document.getElementById('limitTitle');
const limitNama = document.getElementById('limitNama');
const limitAngka = document.getElementById('limitAngka');
var netPast; var tab = '{{ $tab }}'; var tempTempo = '';
var kodeModal; var totPast;
var sisa; var stokJohar; var stokLain; var totStok;

/** Call Fungsi Setelah Inputan Terisi **/
namaCust.addEventListener('keyup', displayCust);
namaCust.addEventListener('blur', displayCust);
tanggalKirim.addEventListener("keyup", formatTanggal);

newRow.addEventListener("click", displayRow);
diskonFaktur.addEventListener('keyup', formatNominal);
diskonFaktur.addEventListener('keyup', displayTotal);

kategori.addEventListener('change', displayJenis);

// Array.prototype.forEach.call(radios, function(radio) {
//    radio.addEventListener('change', displayJenis);
// });

Array.prototype.forEach.call(radiosJen, function(radio) {
   radio.addEventListener('change', displayTempo);
});

/** Tampil Id Supplier **/
function displayCust(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }} ({{ $c->alamat }})' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
      limit.value = '{{ $c->limit }}';
      namaSales.value = '{{ $c->sales->nama }}';
      kodeSales.value = '{{ $c->id_sales }}';
      npwp.value = '{{ $c->npwp }}';
      tempo.value = '{{ $c->tempo }}';
      tempTempo = '{{ $c->tempo }}';
      namaCust.value = '{{ $c->nama }}';
    }
    else if(e.target.value == '') {
      kodeCust.value = '';
      limit.value = '';
      piutang.value = '';
      namaSales.value = '';
      kodeSales.value = '';
      npwp.value = '';
      tempo.value = '';
      tempTempo = '';
      namaCust.value = '';
    }

    radiosJen[0].checked = false;
    radiosJen[0].checked = false;
  @endforeach

  @foreach($totalKredit as $t)
    if('{{ $t->id_customer }}' == kodeCust.value) {
      piutang.value = '{{ $t->total }}';
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

  tanggalKirim.value = value;
}

/** Tampil Input Tempo **/
function displayJenis(e) {
    if(e.target.value != 'CPL') {
        if((e.target.value == 'Extrana') && (tempTempo == 0))
            tempTempo = '30';

        tempo.value = tempTempo;
        radiosJen[0].checked = false;
        radiosJen[0].disabled = false;
        radiosJen[1].disabled = false;
    }
    else {
        tempo.setAttribute('readonly', 'true');
        tempo.removeAttribute('required');
        tempo.value = '';
        radiosJen[0].checked = true;
        radiosJen[0].disabled = true;
        radiosJen[1].disabled = true;
    }

  /* if((radios[1].checked) || (radios[2].checked) || (radios[3].checked) || (radios[4].checked) || (radios[5].checked) || (radios[6].checked) || (radios[7].checked) || (radios[8].checked)) {
    if((radios[4].checked) && (tempTempo == 0))
      tempTempo = '30';

    tempo.value = tempTempo;
    radiosJen[0].checked = false;
    radiosJen[0].disabled = false;
    radiosJen[1].disabled = false;
  }
  else if(radios[0].checked) {
    tempo.setAttribute('readonly', 'true');
    tempo.removeAttribute('required');
    tempo.value = '';
    radiosJen[0].checked = true;
    radiosJen[0].disabled = true;
    radiosJen[1].disabled = true;
  } */
}

/** Tampil Input Tempo **/
function displayTempo(e) {
  if(radiosJen[1].checked) {
    tempo.removeAttribute('readonly');
    tempo.setAttribute('required', 'true');
    tempo.value = tempTempo;
  }
  else if(radiosJen[0].checked) {
    tempo.setAttribute('readonly', 'true');
    tempo.removeAttribute('required');
    tempo.value = '';
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
      <td align="center" class="align-middle nomor">${newNo}</td>
      <td>
        <input type="text" tabindex="${tab++}" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark kdBrgRow">
      </td>
      <td>
        <input type="text" tabindex="${tab += 2}" name="namaBarang[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark nmBrgRow">
      </td>
      <td>
        <input type="text" tabindex="${tab += 3}" name="qty[]" id="qtyRow${newNum}" class="form-control form-control-sm text-bold text-dark text-right qtyRow" value="{{ old('qty[]') }}" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
        <input type="hidden" name="teksSat[]" class="teksSatRow" id="teksSatRow${newNum}">
        <input type="hidden" name="kodeGudang[]" class="kodeGudangRow" id="kodeGudangRow${newNum}">
        <input type="hidden" name="qtyGudang[]" class="qtyGudangRow" id="qtyGudangRow${newNum}">
      <td>
        <input type="text" tabindex="${tab += 4}" name="satuan[]" id="satuanRow${newNum}" class="form-control form-control-sm text-bold text-dark text-right satuanRow"
        value="{{ old('satuan[]') }}" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9"
        autocomplete="off">
      </td>
      <td>
        <input type="text" tabindex="${tab += 5}" name="tipe[]" id="tipeRow${newNum}" class="form-control form-control-sm text-bold text-dark text-center tipeRow"
        value="{{ old('tipe[]') }}">
      </td>
      <td>
        <input type="text" name="harga[]" id="hargaRow${newNum}" readonly class="form-control form-control-sm text-bold text-dark text-right hargaRow" onkeypress="return angkaSajaHarga(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
      </td>
      <td>
        <input type="text" name="jumlah[]" id="jumlahRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlahRow">
      </td>
      <td style="width: 60px">
        <input type="text" tabindex="${tab += 6}" name="diskon[]" id="diskonRow${newNum}" class="form-control form-control-sm text-bold text-right text-dark diskonRow"
        value="{{ old('diskon[]') }}" onkeypress="return angkaPlus(event, ${newNum})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9 dan tanda +" autocomplete="off">
      </td>
      <td style="width: 120px">
        <input type="text" name="diskonRp[]" id="diskonRpRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark diskonRpRow"
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
            <h5>Qty input tidak bisa melebihi total stok. Total stok <b>Kenari</b> untuk barang <span class="col-form-label text-bold nmbrgRow" id="nmbrgRow${newNum}"></span> adalah <span class="col-form-label text-bold totalstokRow" id="totalstokRow${newNum}"></span><span class="col-form-label text-bold totalsatuanRow" id="totalsatuanRow${newNum}"></span></h5>
          </div>
        </div>
      </div>
    </div>
  `;

  $(tablePO).append(newTr);
  $(tablePO).append(newModal);
  // jumBaris.value = newNum;
  jumBaris.value = +jumBaris.value + 1;
  const newRow = document.getElementById(newNum);
  const newMod = document.getElementById("gud"+newNum);
  const newModNotif = document.getElementById("notif"+newNum);
  const brgRow = document.getElementById("nmBrgRow"+newNum);
  const kodeRow = document.getElementById("kdBrgRow"+newNum);
  const qtyRow = document.getElementById("qtyRow"+newNum);
  const teksSatRow = document.getElementById("teksSatRow"+newNum);
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
  const totalstokRow = document.getElementById("totalstokRow"+newNum);
  const totalsatuanRow = document.getElementById("totalsatuanRow"+newNum);
  const nmbrgRow = document.getElementById("nmbrgRow"+newNum);
  var ukuranRow;
  kodeRow.focus();
  document.getElementById("submitSO").tabIndex = tab++;
  document.getElementById("resetSO").tabIndex = tab++;

  /** Tampil Harga **/
  brgRow.addEventListener("keyup", displayHargaRow);
  kodeRow.addEventListener("keyup", displayHargaRow);
  brgRow.addEventListener("blur", displayHargaRow);
  kodeRow.addEventListener("blur", displayHargaRow);

  brgRow.addEventListener("change", resetQtyRow);
  kodeRow.addEventListener("change", resetQtyRow);

  function displayHargaRow(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      $(this).parents('tr').find('input').val('');
      qtyRow.removeAttribute('required');
      diskonRow.removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeRow.value = '{{ $br->id }}';
        brgRow.value = '{{ $br->nama }}';
        satuanUkuran.innerHTML = '{{ substr($br->satuan, -3) }}';
        if(satuanUkuran.innerHTML == 'Dus') {
          pcs.innerHTML = 'Pcs';
          teksSatRow.value = 'Pcs';
          // satuanRow.value = '';
          satuanRow.removeAttribute('readonly');
        }
        else if(satuanUkuran.innerHTML == 'Rol') {
          pcs.innerHTML = 'Rol';
          teksSatRow.value = 'Rol';
          satuanUkuran.innerHTML = 'Meter';
          satuanRow.value = '{{ $br->ukuran }}';
          satuanRow.setAttribute('readonly', 'true');
        }
        else if(satuanUkuran.innerHTML == 'Set') {
          pcs.innerHTML = 'Set';
          satuanUkuran.innerHTML = 'Dus';
          teksSatUkRow.value = 'Dus';
          teksSatRow.value = 'Set';
          // satuanRow.value = '';
        }
        else {
          pcs.innerHTML = 'Meter';
          teksSatRow.value = 'Meter';
          satuanUkuran.innerHTML = '';
          satuanRow.value = '{{ $br->ukuran }}';
          satuanRow.setAttribute('readonly', 'true');
        }
        ukuranRow = '{{ $br->ukuran }}';

        if(('{{ $br->jenis->nama == 'NITTO' }}') || ('{{ $br->jenis->nama == 'BOSS' }}') || ('{{ $br->jenis->nama == 'POWERLINDO' }}')) {
          hargaRow.removeAttribute('readonly');
        } else {
          hargaRow.setAttribute('readonly', 'true');
        }
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeRow.value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipeRow.value = '{{ $hb->hargaBarang->tipe }}';
        hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
        qtyRow.setAttribute('required', true);
        diskonRow.setAttribute('required', true);
      }
    @endforeach

    // kodeGudangRow.value = '{{ $gudang[0]->id }}';
    // qtyGudangRow.value = '';
    // qtyRow.value = '';
  }

  function resetQtyRow(e) {
    kodeGudangRow.value = '{{ $gudang[0]->id }}';
    qtyGudangRow.value = '';
    qtyRow.value = '';
    satuanRow.value = '';
  }

  hargaRow.addEventListener("keyup", displayHargaCustomRow);
  hargaRow.addEventListener("blur", displayHargaCustomRow);

  function displayHargaCustomRow(e) {
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

    netPast = +nettoRow.value.replace(/\./g, "");
    jumlahRow.value = addCommas(hargaRow.value.replace(/\./g, "") * qtyRow.value);
    if(diskonRow.value != "") {
      var angkaDiskon = hitungDiskon(diskonRow.value)
      diskonRpRow.value = addCommas((angkaDiskon * jumlahRow.value.replace(/\./g, "") / 100).toFixed(0));
    }

    nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
    checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    ppn.value = 0;
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
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

  satuanRow.addEventListener("keypress", function (e, evt) {
    evt = (evt) ? evt : window.event;
    var charCodeRow = (evt.which) ? evt.which : evt.keyCode;
    if (charCodeRow > 31 && (charCodeRow < 48 || charCodeRow > 57)) {
      $(satuanRow).tooltip('show');
      e.preventDefault();
    }

    return true;
  });

  /** Tampil Jumlah **/
  qtyRow.addEventListener("blur", displayQtyRow);
  if(teksSatRow.value == 'Pcs')
    satuanRow.addEventListener("blur", displayQtyRow);
  else
    satuanRow.addEventListener("change", displayQtyRow);

  function displayQtyRow(e) {
    stokJohar = 0;
    stokLain = [];
    totStok = 0;
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeRow.value) && ('{{ $s->gudang->tipe }}' == 'KENARI'))
        totStok = '{{ $s->stok }}';

    @endforeach

    hitungQtyRow(e.target.id, e.target.value, teksSatRow.value, ukuranRow);

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      jumlahRow.value = "";
      nettoRow.value = "";
      kodeGudangRow.value = '{{ $gudang[0]->id }}';
      qtyGudangRow.value = "";
      qtyRow.value = "";
      satuanRow.value = "";
    }
    else if(((e.target.id == `qtyRow${newNum}`) && (+e.target.value > totStok)) || ((e.target.id == `satuanRow${newNum}`) && (teksSatRow.value == 'Pcs') && (+e.target.value * +ukuranRow) > totStok)) {
      $('#notif'+newNum).modal("show");
      nmbrgRow.textContent = brgRow.value;
      totalstokRow.textContent = `${totStok} ${pcs.innerHTML}`;
      if(teksSatRow.value == 'Pcs')
        totalsatuanRow.textContent = ` atau ${totStok / ukuranRow} ${satuanUkuran.innerHTML}`;
      else
        totalsatuanRow.textContent = ``;

      checkSubtotal(+nettoRow.value.replace(/\./g, ""), 0);
      ppn.value = 0;
      totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;

      qtyRow.value = "";
      satuanRow.value = "";
      jumlahRow.value = "";
      nettoRow.value = "";

      return false;
    }
    else {
      kodeGudangRow.value = '{{ $gudang[0]->id }}';
      qtyGudangRow.value = e.target.value;

      netPast = +nettoRow.value.replace(/\./g, "");
      jumlahRow.value = addCommas(qtyRow.value * hargaRow.value.replace(/\./g, ""));

      if(diskonRow.value != "") {
        var angkaDiskon = hitungDiskon(diskonRow.value)
        diskonRpRow.value = addCommas((angkaDiskon * jumlahRow.value.replace(/\./g, "") / 100).toFixed(0));
      }

      nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
      checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
    }

    ppn.value = 0;
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  }

  function hitungQtyRow(kode, angka, teks, ukuran) {
    if(kode == `qtyRow${newNum}`) {
      if((teks == 'Pcs') || (teks == 'Set'))
        satuanRow.value = +angka / +ukuran;
      else if(teks == 'Rol')
        satuanRow.value = +angka * +ukuran;
    }
    else if(kode == `satuanRow${newNum}`)
      qtyRow.value = +angka * +ukuran;
  }

  tipeRow.addEventListener("keyup", displayTipeRow);
  tipeRow.addEventListener("blur", displayTipeRow);

  function displayTipeRow(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      hargaRow.value = "";
      jumlahRow.value = "";
      diskonRpRow.value = "";
      nettoRow.value = "";
    }

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeRow.value) && ('{{ $hb->hargaBarang->tipe }}' == e.target.value)) {
        hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
        jumlahRow.value = addCommas(qtyRow.value * hargaRow.value.replace(/\./g, ""));

        netPast = +nettoRow.value.replace(/\./g, "");
        if(diskonRow.value != "") {
          var angkaDiskon = hitungDiskon(diskonRow.value)
          diskonRpRow.value = addCommas((angkaDiskon * jumlahRow.value.replace(/\./g,"") / 100).toFixed(0));
        }

        nettoRow.value = addCommas(+jumlahRow.value.replace(/\./g, "") - +diskonRpRow.value.replace(/\./g, ""));
        checkSubtotal(netPast, +nettoRow.value.replace(/\./g, ""));
        grandtotal.value = totalNotPPN.value;
      }
    @endforeach
  }

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
  diskonRow.addEventListener("keyup", displayDiskonRow);
  diskonRow.addEventListener("blur", displayDiskonRow);

  function displayDiskonRow(e) {
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
  }

  /** Delete Table Row **/
  hapusRow.addEventListener("click", function (e) {
    if(qtyRow.value != "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +nettoRow.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      total_ppn(totalNotPPN.value.replace(/\./g, ""));
    }

    const curNum = $(this).closest('tr').find('td:first-child').text();
    // const lastNum = $(tablePO).find('tr:last').attr("id");
    const lastNum = $(tablePO).find('tr:last td:first-child').text();
    var numRow;
    if(+curNum < +lastNum) {
      $(newRow).remove();
      $(newMod).remove();
      $(newModNotif).remove();
      var j = curNum;
      var selisih = +lastNum - +curNum;
      var nomor = document.querySelectorAll('.nomor');
      for(let i = +curNum; i < +lastNum; i++) {
        // $(tablePO).find('tr:nth-child('+i+') td:first-child').html(i);
        nomor[i-6].innerHTML = i;
      }
      numRow = lastNum;
    }
    else if(+curNum == +lastNum) {
      $(newRow).remove();
      numRow = +curNum - 1;
    }
    jumBaris.value -= 1;
    var kdBrg = document.querySelectorAll('.kdBrgRow');
    if(jumBaris.value > 5)
      // document.getElementById("kdBrgRow"+numRow).focus();
      kdBrg[lastNum-7].focus();
    else
      kodeBarang[4].focus();
  })

  /** Autocomplete Nama  Barang **/
  $(function() {
    var idBarang = [];
    var nmBarang = [];
    @foreach($barang as $b)
      idBarang.push('{{ $b->id }}');
      nmBarang.push('{{ $b->nama }}');
    @endforeach

    var tipeHrg = [];
    @foreach($hrg as $h)
      tipeHrg.push('{{ $h->tipe }}');
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

    /*-- Autocomplete Input Tipe Harga --*/
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

/** Tampil Harga Barang **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("keyup", displayHarga) ;
  kodeBarang[i].addEventListener("keyup", displayHarga);
  brgNama[i].addEventListener("blur", displayHarga) ;
  kodeBarang[i].addEventListener("blur", displayHarga);

  brgNama[i].addEventListener("change", resetQty) ;
  kodeBarang[i].addEventListener("change", resetQty);

  function displayHarga(e) {
    satuan[i].removeAttribute('readonly');

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
      diskon[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
        satuanUkuran.innerHTML = '{{ substr($br->satuan, -3) }}';
        if(satuanUkuran.innerHTML == 'Dus') {
          pcs.innerHTML = 'Pcs';
          teksSatUk[i].value = 'Dus';
          teksSat[i].value = 'Pcs';
          // satuan[i].value = '';
        }
        else if(satuanUkuran.innerHTML == 'Rol') {
          pcs.innerHTML = 'Rol';
          teksSat[i].value = 'Rol';
          teksSatUk[i].value = 'Meter';
          satuanUkuran.innerHTML = 'Meter';
          satuan[i].value = '{{ $br->ukuran }}';
          satuan[i].setAttribute('readonly', 'true');
        }
        else if(satuanUkuran.innerHTML == 'Set') {
          pcs.innerHTML = 'Set';
          satuanUkuran.innerHTML = 'Dus';
          teksSatUk[i].value = 'Dus';
          teksSat[i].value = 'Set';
          // satuan[i].value = '';
        }
        else {
          pcs.innerHTML = 'Meter';
          teksSat[i].value = 'Meter';
          teksSatUk[i].value = '';
          satuanUkuran.innerHTML = '';
          satuan[i].value = '{{ $br->ukuran }}';
          satuan[i].setAttribute('readonly', 'true');
        }
        ukuran[i].value = '{{ $br->ukuran }}';

        if(('{{ $br->jenis->nama == 'NITTO' }}') || ('{{ $br->jenis->nama == 'BOSS' }}') || ('{{ $br->jenis->nama == 'POWERLINDO' }}')) {
          harga[i].removeAttribute('readonly');
        } else {
          harga[i].setAttribute('readonly', 'true');
        }
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipe[i].value = '{{ $hb->hargaBarang->tipe }}';
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        qty[i].setAttribute('required', true);
        diskon[i].setAttribute('required', true);
      }
    @endforeach
  }

  function resetQty(e) {
    kodeGudang[i].value = '{{ $gudang[0]->id }}';
    qtyGudang[i].value = '';
    qty[i].value = '';
    satuan[i].value = '';
  }
}

for(let i = 0; i < harga.length; i++) {
  harga[i].addEventListener("keyup", displayHargaCustom);
  harga[i].addEventListener("blur", displayHargaCustom);

  function displayHargaCustom(e) {
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });

    netPast = +netto[i].value.replace(/\./g, "");
    jumlah[i].value = addCommas(harga[i].value.replace(/\./g, "") * qty[i].value);
    if(diskon[i].value != "") {
      var angkaDiskon = hitungDiskon(diskon[i].value)
      diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
    }

    netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
    checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    ppn.value = 0;
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  }
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("blur", displayQty);

  if(teksSat[i].value == 'Pcs')
    satuan[i].addEventListener("blur", displayQty);
  else
    satuan[i].addEventListener("change", displayQty);

  function displayQty(e) {
    stokJohar = 0;
    stokLain = [];
    totStok = 0;
    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->gudang->tipe }}' == 'KENARI'))
        totStok = '{{ $s->stok }}';
    @endforeach

    hitungQty(i, e.target.id, e.target.value, teksSat[i].value, ukuran[i].value);

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      diskonRp[i].value = "";
      netto[i].value = "";
      kodeGudang[i].value = '{{ $gudang[0]->id }}';
      qtyGudang[i].value = "";
      qty[i].value = "";
      satuan[i].value = "";
    }
    else if(((e.target.id == 'qty') && (+e.target.value > totStok)) || ((e.target.id == 'satuan') && (teksSat[i].value == 'Pcs') && ((+e.target.value * +ukuran[i].value) > totStok))) {
      $('#notif'+i).modal("show");
      nmbrg[i].textContent = brgNama[i].value;
      totalstok[i].textContent = `${totStok} ${teksSat[i].value}`;

      if(teksSat[i].value == 'Pcs')
        totalsatuan[i].textContent = ` atau ${totStok / ukuran[i].value} ${satuanUkuran.innerHTML}`;
      else
        totalsatuan[i].textContent = ``;

      // netPast = +netto[i].value.replace(/\./g, "");
      checkSubtotal(+netto[i].value.replace(/\./g, ""), 0);
      ppn.value = 0;
      totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;

      qty[i].value = "";
      satuan[i].value = "";
      jumlah[i].value = "";
      netto[i].value = "";

      return false;
    }
    else {
      kodeGudang[i].value = '{{ $gudang[0]->id }}';
      qtyGudang[i].value = qty[i].value;

      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(qty[i].value * harga[i].value.replace(/\./g, ""));

      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
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

// Pilih Tipe
for(let i = 0; i < tipe.length; i++) {
  tipe[i].addEventListener("keyup", displayTipe);
  tipe[i].addEventListener("blur", displayTipe);

  function displayTipe(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
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
        grandtotal.value = totalNotPPN.value;
      }
    @endforeach
  }
}

/** Tampil Diskon Rupiah Otomatis **/
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
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  }
}

/** Hitung Qty **/
function hitungQty(urutan, kode, angka, teks, ukuran) {
  if(kode == 'qty') {
    if((teks == 'Pcs') || (teks == 'Set'))
      satuan[urutan].value = +angka / +ukuran;
    else if(teks == 'Rol')
      satuan[urutan].value = +angka * +ukuran;
  }
  else if(kode == 'satuan') {
    qty[urutan].value = +angka * +ukuran;
  }
}

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon =  ((totDiskon - 100) * -1);
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
function angkaSaja(evt, inputan, teks) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    if(inputan == "tempo") {
      $(tempo).tooltip('show');
    }
    else {
      for(let i = 1; i <= qty.length; i++) {
        if(inputan == i) {
          if(teks == 'qty')
            $(qty[inputan-1]).tooltip('show');
          else
            $(satuan[inputan-1]).tooltip('show');
        }
      }
    }
    return false;
  }
  return true;
}

/** Inputan hanya bisa angka **/
function angkaSajaHarga(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= harga.length; i++) {
      if(inputan == i)
        $(harga[inputan-1]).tooltip('show');
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
      // total_ppn(totalNotPPN.value.replace(/\./g, ""));
    }

    for(let j = i; j < hapusBaris.length; j++) {
      if(j+1 != hapusBaris.length) {
        netto[j].value = netto[j+1].value;
        diskonRp[j].value = diskonRp[j+1].value;
        diskon[j].value = diskon[j+1].value;
        jumlah[j].value = jumlah[j+1].value;
        harga[j].value = harga[j+1].value;
        tipe[j].value = tipe[j+1].value;
        satuan[j].value = satuan[j+1].value;
        teksSat[j].value = teksSat[j+1].value;
        qtyGudang[j].value = qtyGudang[j+1].value;
        kodeGudang[j].value = kodeGudang[j+1].value;
        qty[j].value = qty[j+1].value;
        brgNama[j].value = brgNama[j+1].value;
        kodeBarang[j].value = kodeBarang[j+1].value;
        if(kodeBarang[j+1].value == "")
          qty[j].removeAttribute('required');
        else
          qty[j+1].removeAttribute('required');
      } else {
        netto[j].value = '';
        diskonRp[j].value = '';
        diskon[j].value = '';
        jumlah[j].value = '';
        harga[j].value = '';
        tipe[j].value = '';
        satuan[j].value = '';
        teksSat[j].value = '';
        qtyGudang[j].value = '';
        kodeGudang[j].value = '';
        qty[j].value = '';
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

function checkRequired(e) {
  // if((namaCust.value == "") || (namaSales.value == "") || (tanggalKirim.value == "") || (!$(radios).is(':checked')) ||
  // (!$(radiosJen).is(':checked')) || (kodeBarang[0].value == "") || (qty[0].value == "")) {
  if((namaCust.value == "") || (namaSales.value == "") || (tanggalKirim.value == "") ||
      (!$(radiosJen).is(':checked')) || (kodeBarang[0].value == "") || (qty[0].value == "")) {
    e.stopPropagation();
  }
  else {
    const kdRow = document.querySelectorAll('.kdBrgRow');
    document.getElementById("submitSO").removeAttribute('data-toggle');
    document.getElementById("submitSO").removeAttribute('data-target');
    cek = 0;
    var kode = [];
    for(let i = 0; i < (jumBaris.value - kdRow.length); i++) {
      if(kodeBarang[i].value != '') {
        kode.push(kodeBarang[i].value);
      }
    }

    for(let i = 0; i < kdRow.length; i++) {
      if(kdRow[i].value != '') {
        kode.push(kdRow[i].value);
      }
    }

    cek = new Set(kode).size !== kode.length;

    if(cek === true) {
      document.getElementById("submitSO").dataset.toggle = "modal";
      document.getElementById("submitSO").dataset.target = "#modalDuplikat";
      return false;
    }
    else if((+grandtotal.value.replace(/\./g, "") + +piutang.value) > +limit.value) {
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
    /* document.getElementById("submitSO").dataset.toggle = "modal";
    document.getElementById("submitSO").dataset.target = "#modalKonfirm";
    return false; */
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
    // customer.push('{{ $c->nama }}');
    customer.push('{{ $c->nama }} ({{ $c->alamat }})');
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
