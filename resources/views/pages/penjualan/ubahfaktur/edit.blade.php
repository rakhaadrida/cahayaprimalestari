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
            <form action="" method="" id="editSO">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold text-dark">Nomor Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark" name="kode" value="{{ $items[0]->id }}">
                      </div>
                    </div>
                  </div>
                  <div class="col" @if(Auth::user()->roles == 'SUPER') style="margin-left: -660px" @else style="margin-left: -480px" @endif>
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right text-dark">Tanggal Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      @if(Auth::user()->roles == 'SUPER') <div class="col-3"> @else <div class="col-4"> @endif
                        @if(Auth::user()->roles != 'SUPER')
                          <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="tglSO" value="{{ \Carbon\Carbon::parse($items[0]->tgl_so)->format('d-M-y') }}">
                        @else
                          <input type="text" class="form-control datepicker form-control-sm text-bold text-dark mt-1" name="tglSO" value="{{ \Carbon\Carbon::parse($items[0]->tgl_so)->format('d-m-Y') }}">
                        @endif
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaCust" class="col-5 col-form-label text-bold text-right text-dark">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        @if(Auth::user()->roles != 'SUPER')
                          <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="namaCust" id="namaCust" value="{{ $items[0]->customer->nama }}">
                        @else
                          <input type="text" class="form-control form-control-sm text-bold text-dark mt-1" name="namaCust" id="namaCust" value="{{ $items[0]->customer->nama }}">
                        @endif
                        <input type="hidden" name="kodeCust" id="kodeCust" value="{{ $items[0]->id_customer }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSales" class="col-5 col-form-label text-bold text-right text-dark">Nama Sales</label>
                      <span class="col-form-label text-bold">:</span>
                      @if(Auth::user()->roles == 'SUPER') <div class="col-3"> @else <div class="col-5"> @endif
                        @if(Auth::user()->roles != 'SUPER')
                          <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="namaSales" id="namaSales" value="{{ $items->first()->sales->nama }}" />
                        @else
                          <input type="text" class="form-control form-control-sm text-bold text-dark mt-1" name="namaSales" id="namaSales" value="{{ $items->first()->sales->nama }}" />
                        @endif
                        <input type="hidden" name="kodeSales" id="kodeSales" value="{{ $items[0]->id_sales }}">
                      </div>
                    </div>
                    @if(Auth::user()->roles == 'SUPER')
                      <div class="form-group row sj-after-first">
                        <label for="kategori" class="col-5 col-form-label text-bold text-right text-dark">Kategori</label>
                        <span class="col-form-label text-bold">:</span>
                        <div class="col-3">
                          <input type="text" class="form-control form-control-sm text-bold text-dark mt-1" name="kategori" id="kategori" value="{{ $items->first()->kategori }}" />
                        </div>
                        <label for="tempo" class="col-2 col-form-label text-bold text-right text-dark" style="margin-left: -45px">Tempo</label>
                        <span class="col-form-label text-bold">:</span>
                        <div class="col-2">
                          <input type="text" class="form-control form-control-sm text-bold text-dark mt-1" name="tempo" id="tempo" value="{{ $items->first()->tempo }}"  onkeypress="return angkaSaja(event)" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off"/>
                        </div>
                        <span class="col-form-label text-bold">Hari</span>
                      </div>
                    @endif
                  </div>
                </div>
                {{-- <div class="form-group row so-update-left"> --}}
                <div class="form-group row @if(Auth::user()->roles != 'SUPER') so-update-left @endif" @if(Auth::user()->roles == 'SUPER') style="margin-top: -120px" @endif>
                  <label for="nama" class="col-2 col-form-label text-bold text-dark">Tanggal Update</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark" name="tanggal" value="{{ $tanggal }}">
                  </div>
                </div>
                <div class="form-group row so-update-input">
                  <label for="alamat" class="col-2 col-form-label text-bold text-dark">Keterangan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" tabindex="1" name="keterangan" id="keterangan" class="form-control form-control-sm mt-1 text-dark" required autofocus>
                    @php
                      if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                        $itemsApp = \App\Models\NeedApproval::where('id_dokumen', $items[0]->id)
                                    ->latest()->get();
                        // $itemsRow = $itemsApp[0]->need_appdetil->count();
                        $itemsRow = \App\Models\NeedAppDetil::where('id_app', $itemsApp[0]->id)
                                    ->groupBy('id_barang')->get();
                      }
                    @endphp
                    <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $itemsRow->count() }}">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="nama" value="{{ $nama }}">
                    <input type="hidden" name="tglAwal" value="{{ $tglAwal }}">
                    <input type="hidden" name="tglAkhir" value="{{ $tglAkhir }}">
                  </div>
                </div>
                <div class="form-group row so-update-input" @if(Auth::user()->roles == 'SUPER') style="margin-bottom: 35px" @endif>
                  <label for="alamat" class="col-2 col-form-label text-bold text-dark"></label>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" >
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <td class="align-middle" style="width: 30px">No</td>
                    <td class="align-middle" style="width: 90px">Kode</td>
                    <td class="align-middle">Nama Barang</td>
                    <td class="align-middle" style="width: 55px">Qty</td>
                    @foreach($gudang as $g)
                      <td class="align-middle" style="width: 45px">{{ substr($g->nama, 0, 3) }}</td>
                    @endforeach
                    <td class="align-middle" style="width: 50px">Tipe Harga</td>
                    <td class="align-middle" style="width: 80px">Harga</td>
                    <td class="align-middle" style="width: 100px">Jumlah</td>
                    <td class="align-middle" style="width: 100px">Diskon(%)</td>
                    <td class="align-middle" style="width: 70px">Diskon(Rp)</td>
                    <td class="align-middle" style="width: 100px">Netto (Rp)</td>
                    <td class="align-middle">Hapus</td>
                  </tr>
                </thead>
                <tbody id="tablePO">
                  @php
                    $i = 1; $subtotal = 0; $tab = 1;
                    if(($items->first()->need_approval->count() != 0) && ($items->first()->need_approval->last()->status == 'PENDING_UPDATE')) {
                      $itemsDetail = \App\Models\NeedAppDetil::with(['barang'])
                                  ->select('id_barang', 'diskon')
                                  ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                  ->where('id_app', $items->first()->need_approval->last()->id)
                                  ->groupBy('id_barang', 'diskon')
                                  ->get();
                    } else {
                      $itemsDetail = \App\Models\DetilSO::select('id_barang', 'diskon')
                                  ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                  ->where('id_so', $items->first()->id)
                                  ->groupBy('id_barang', 'diskon')
                                  ->get();
                    }
                  @endphp
                  @foreach($itemsDetail as $item)
                    <tr class="text-dark" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" tabindex="{{ $tab++ }}" name="kodeBarang[]" class="form-control form-control-sm text-bold text-dark kodeBarang" value="{{ $item->id_barang }}" required>
                        <input type="hidden" name="kodeBarangAwal[]" class="kodeBarangAwal" value="{{ $item->id_barang }}">
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 2 }}" name="namaBarang[]" class="form-control form-control-sm text-bold text-dark text-wrap namaBarang" value="{{ $item->barang->nama }}" required>
                        <input type="hidden" name="qtyAwal" class="text-bold text-dark qtyAwal" value="{{ $item->qty }}">
                        <input type="hidden" name="namaBarangAwal[]" class="namaBarangAwal" value="{{ $item->barang->nama }}">
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 3 }}" name="qty[]" class="form-control form-control-sm text-bold text-dark text-right qty" value="{{ $item->qty }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" required>
                        @php $arrKode = ''; $arrQty = ''; $kodeAwal = ''; @endphp
                        @foreach($gudang as $g)
                          @php
                            if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                              $itemGud = \App\Models\NeedAppDetil::where('id_app',
                                      $items[0]->need_approval->last()->id)
                                      ->where('id_barang', $item->id_barang)
                                      ->where('id_gudang', $g->id)->get();
                            } else {
                              $itemGud = \App\Models\DetilSO::where('id_so', $items->first()->id)
                                      ->where('id_barang', $item->id_barang)
                                      ->where('id_gudang', $g->id)->get();
                            }
                            if($itemGud->count() != 0) {
                              if($arrKode == '') {
                                $arrKode = $itemGud[0]->id_gudang;
                                $arrQty = $itemGud[0]->qty;
                                $kodeAwal = $arrKode;
                                $namaAwal = $itemGud[0]->gudang->nama;
                              } else {
                                $arrKode = $arrKode.",".$itemGud[0]->id_gudang;
                                $arrQty = $arrQty.",".$itemGud[0]->qty;
                              }
                            }
                          @endphp
                        @endforeach
                        <input type="hidden" name="teksSat[]" class="teksSat" value="{{ substr($item->barang->satuan, 0, 3) }}">
                        <input type="hidden" name="KodeGudangArr[]" class="text-bold text-dark kodeGudangArr" value="{{ $arrKode }}">
                        <input type="hidden" name="qtyAwalArr[]" class="text-bold text-dark qtyAwalArr" value="{{ $arrQty }}">
                        <input type="hidden" name="kodeGudang[]" class="kodeGudang" value="{{ $arrKode }}">
                        <input type="hidden" name="qtyGudang[]" class="qtyGudang" value="{{ $arrQty }}">
                      </td>
                      @foreach($gudang as $g)
                        @php
                          if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                            $itemGud = \App\Models\NeedAppDetil::where('id_app',
                                    $items[0]->need_approval->last()->id)
                                    ->where('id_barang', $item->id_barang)
                                    ->where('id_gudang', $g->id)->get();
                          } else {
                            $itemGud = \App\Models\DetilSO::where('id_so', $items[0]->id)
                                    ->where('id_barang', $item->id_barang)
                                    ->where('id_gudang', $g->id)->get();
                          }
                        @endphp
                        @if($itemGud->count() != 0)
                          <td>
                            <input type="text" name="{{$g->id}}[]" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark gud{{$g->id}}" value="{{ $itemGud[0]->qty }}">
                          </td>
                        @else
                          <td>
                            <input type="text" name="{{$g->id}}[]" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark gud{{$g->id}}"
                            value="">
                          </td>
                        @endif
                      @endforeach
                      @php
                        foreach($harga as $hb) {
                          if($hb->id_barang == $item->id_barang) {
                            $tipe = $hb->hargaBarang->tipe;
                            break;
                          }
                          else
                            $tipe = '';
                        }
                      @endphp
                      <td>
                        <input type="text" tabindex="{{ $tab += 4 }}" name="tipe[]" id="tipe" class="form-control form-control-sm text-bold text-dark text-center tipe"
                        value="{{ $tipe }}">
                      </td>
                      <td align="right">
                        <input type="text" name="harga[]" class="@if(($item->barang->jenis->nama != 'NITTO') && ($item->barang->jenis->nama != 'BOSS')) form-control-plaintext @else form-control @endif form-control-sm text-bold text-dark text-right harga" value="{{ number_format($item->harga, 0, "", ".") }}" @if(($item->barang->jenis->nama != 'NITTO') && ($item->barang->jenis->nama != 'BOSS')) readonly @endif>
                      </td>
                      <td align="right">
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ number_format($item->qty * $item->harga, 0, "", ".") }}" >
                      </td>
                      <td align="right" style="width: 60px">
                        <input type="text" tabindex="{{ $tab += 5 }}" name="diskon[]" class="form-control form-control-sm text-bold text-right diskon" value="{{ $item->diskon }}" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9, tanda +, dan tanda koma" autocomplete="off" required>
                      </td>
                      @php
                        $diskon = 100;
                        $item->diskon = str_replace(",", ".", $item->diskon);
                        $arrDiskon = explode("+", $item->diskon);
                        for($j = 0; $j < sizeof($arrDiskon); $j++) {
                          $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                        }
                        $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                      @endphp
                      <td align="right" >
                        <input type="text" name="diskonRp[]" id="diskonRp" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark diskonRp"
                        value="{{ number_format((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100, 0, "", ".") }}" >
                      </td>
                      <td align="right">
                        <input type="text" name="netto[]" id="netto" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto" value="{{ number_format(($item->qty * $item->harga) -
                        ((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100), 0, "", ".") }}" >
                      </td>
                      <td align="center" class="align-middle">
                        <a href="#" class="icRemove">
                          <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                        </a>
                      </td>
                      @php $subtotal += ($item->qty * $item->harga) -
                        ((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100);
                      @endphp
                    </tr>
                    <div class="modal modalGudang" id="{{$i-1}}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}-" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="h2 text-bold">&times;</span>
                            </button>
                            <h4 class="modal-title text-bold">Pilih Gudang</h4>
                          </div>
                          <div class="modal-body text-dark">
                            <p>Qty order melebihi stok pada gudang <strong>{{ $stok[0]->gudang->nama }}@if($namaAwal != 'Johar Baru') dan {{ $namaAwal }}@endif</strong>. Pilih gudang lainnya untuk memenuhi qty order.</p>
                            <input type="hidden" id="kodeModal{{$i-1}}" value="{{$i-1}}">
                            <div class="form-group row" style="margin-top: -10px">
                              <label for="kode" class="col-6 col-form-label text-bold">Qty Order</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold qtyOrder"></span>
                              <span class="col-form-label text-bold qtySatuan"></span>
                              <span class="col-form-label text-bold qtyOrderUkuran"></span>
                              <span class="col-form-label text-bold qtyUkuran"></span>
                            </div>
                            <div class="form-group row" style="margin-top: -30px">
                              {{-- <label for="kode" class="col-4 col-form-label text-bold">Stok {{ $gudang[0]->nama }}</label> --}}
                              <label for="kode" class="col-6 col-form-label text-bold">Stok {{ $stok[0]->gudang->nama }} @if($namaAwal != 'Johar Baru')& {{ $namaAwal }}@endif</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold stokJohar"></span>
                              <span class="col-form-label text-bold stokSatuan"></span>
                              <span class="col-form-label text-bold stokJoharUkuran"></span>
                              <span class="col-form-label text-bold stokUkuran"></span>
                            </div>
                            <div class="form-group row" style="margin-top: -20px">
                              <label for="kode" class="col-6 col-form-label text-bold">Sisa Qty Order</label>
                              <span class="col-auto col-form-label text-bold">:</span>
                              <span class="col-form-label text-bold sisaQty"></span>
                              <span class="col-form-label text-bold sisaSatuan"></span>
                              <span class="col-form-label text-bold sisaQtyUkuran"></span>
                              <span class="col-form-label text-bold sisaUkuran"></span>
                            </div>
                            <label for="pilih" style="margin-bottom: -5px">Pilih Gudang Tambahan</label>
                            @foreach($gudang as $g)
                              {{-- @if($g->id != 'GDG01') --}}
                              @if(($g->id != $kodeAwal) && ($g->id != 'GDG01'))
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
                    @php $i++; @endphp
                  @endforeach
                </tbody>
              </table>

              <div class="form-group row justify-content-end subtotal-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal, 0, "", ".") }}" >
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Diskon Faktur</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="diskonFaktur" id="diskonFaktur" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($items[0]->diskon, 0, "", ".") }}" >
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Total Sebelum PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal - $items[0]->diskon, 0, "", ".") }}" >
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right"
                  value="0" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-right"
                  value="{{ number_format($subtotal - $items[0]->diskon, 0, "", ".") }}"
                  />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('so-update') }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
                </div>
                <div class="col-2">
                  <button type="reset" tabindex="{{ $tab += 2 }}" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                </div>
                <div class="col-2">
                  <a href="{{ url()->previous() }}" tabindex="{{ $tab += 3 }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
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

const namaCust = document.getElementById("namaCust");
const kodeCust = document.getElementById("kodeCust");
const namaSales = document.getElementById("namaSales");
const kodeSales = document.getElementById("kodeSales");
const kategori = document.getElementById("kategori");
const tempo = document.getElementById("tempo");
const editSO = document.getElementById("editSO");
const kodeBarang = document.querySelectorAll('.kodeBarang');
const kodeBarangAwal = document.querySelectorAll('.kodeBarangAwal');
const brgNama = document.querySelectorAll(".namaBarang");
const namaBarangAwal = document.querySelectorAll('.namaBarangAwal');
const qtyAwal = document.querySelectorAll(".qtyAwal");
const qty = document.querySelectorAll(".qty");
const teksSat = document.querySelectorAll(".teksSat");
const kodeGudangArr = document.querySelectorAll(".kodeGudangArr");
const qtyAwalArr = document.querySelectorAll(".qtyAwalArr");
const kodeGudang = document.querySelectorAll(".kodeGudang");
const qtyGudang = document.querySelectorAll(".qtyGudang");
const tipe = document.querySelectorAll(".tipe");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const diskon = document.querySelectorAll(".diskon");
const diskonRp = document.querySelectorAll(".diskonRp");
const netto = document.querySelectorAll(".netto");
const hapusBaris = document.querySelectorAll(".icRemove");
const subtotal = document.getElementById('subtotal');
const diskonFaktur = document.getElementById('diskonFaktur');
const totalNotPPN = document.getElementById('totalNotPPN');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
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
var ukuran; var satuanUkuran; var pcs; var qtyJoharAwal;
var netPast; var cek; var stokTambah; var qtyAwalModal; var tempQty = 0;
var kodeModal; var arrKodeGud; var arrQtyAwal; var arrQtyGud;
var totTemp; var qtyJohar; var qtyLebih; var stokAwal; var kodeAwal;
var sisa; var stokJohar; var stokLain; var kodeLain; var totStok; var kg;

namaCust.addEventListener('keyup', displayCust);
namaCust.addEventListener('blur', displayCust);
namaSales.addEventListener('keyup', displaySales);
namaSales.addEventListener('blur', displaySales);
editSO.addEventListener("keypress", checkEnter);

/** Tampil Id Supplier **/
function displayCust(e) {
  @foreach($customer as $c)
    if('{{ $c->nama }} ({{ $c->alamat }})' == e.target.value) {
      kodeCust.value = '{{ $c->id }}';
      namaSales.value = '{{ $c->sales->nama }}';
      kodeSales.value = '{{ $c->id_sales }}';
      namaCust.value = '{{ $c->nama }}';
    }
    else if(e.target.value == '') {
      kodeCust.value = '';
      namaSales.value = '';
      kodeSales.value = '';
      namaCust.value = '';
    }
  @endforeach
}

/** Tampil Id Sales **/
function displaySales(e) {
  @foreach($sales as $s)
    if('{{ $s->nama }}' == e.target.value) {
      kodeSales.value = '{{ $s->id }}';
    }
    else if(e.target.value == '') {
      kodeSales.value = '';
    }
  @endforeach
}

function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
}

/** Tampil Nama dan Kode Barang Otomatis **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("keyup", displayHarga) ;
  kodeBarang[i].addEventListener("keyup", displayHarga);
  brgNama[i].addEventListener("blur", displayHarga) ;
  kodeBarang[i].addEventListener("blur", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;

      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ ($br->nama) }}'.replace(/&quot;/g, '\"') == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}'.replace(/&quot;/g, '\"');
      }

      if(('{{ $br->jenis->nama == 'NITTO' }}') || ('{{ $br->jenis->nama == 'BOSS' }}')) {
        harga[i].removeAttribute('readonly');
      } else {
        harga[i].setAttribute('readonly', 'true');
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipe[i].value = '{{ $hb->hargaBarang->tipe }}';
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        qty[i].setAttribute('required', true);
      }
    @endforeach

    if(qty[i].value != '') {
      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(harga[i].value.replace(/\./g, "") * qty[i].value);
      netto[i].value = addCommas(jumlah[i].value.replace(/\./g, "") - diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
    }

    if((kodeBarangAwal[i].value != kodeBarang[i].value) || (namaBarangAwal[i].value != brgNama[i].value)) {
      qty[i].value = '';
      qtyAwal[i].value = 0;
      kodeGudangArr[i].value = 'GDG01';
      qtyAwalArr[i].value = 0;
      kodeGudang[i].value = 'GDG01';
      qtyGudang[i].value = 0;
      kodeBarangAwal[i].value = kodeBarang[i].value;
      namaBarangAwal[i].value = brgNama[i].value;
    }
  }
}

for(let i = 0; i < harga.length; i++) {
  harga[i].addEventListener("keyup", function(e) {
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
  });
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("blur", function (e) {
    stokJohar = 0;
    stokLain = []; kodeLain = [];
    // stokAwal = []; kodeAwal = [];
    totStok = 0;
    arrKodeGud = kodeGudangArr[i].value.split(',');
    arrQtyAwal = qtyAwalArr[i].value.split(',');
    var kg = [];
    var urutKG = 1;

    @foreach($gudang as $g)
      kg.push('{{ $g->id }}');
    @endforeach

    @foreach($stok as $s)
      // if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->id_gudang }}' == 'GDG01'))
      if(('{{ $s->id_barang }}' == kodeBarang[i].value) && (('{{ $s->id_gudang }}' == arrKodeGud[0]) || ('{{ $s->id_gudang }}' == 'GDG01'))) {
        stokJohar += +'{{ $s->stok }}';
        totStok += +'{{ $s->stok }}';
        if('{{ $s->id_gudang }}' == 'GDG01') {
          stokAwal = '{{ $s->stok }}';
          kodeAwal = '{{ $s->id_gudang }}';
        }
      }
      else if('{{ $s->id_barang }}' == kodeBarang[i].value) {
        for(let k = urutKG; k < kg.length; k++) {
          if('{{ $s->id_gudang }}' == kg[k]) {
            stokLain.push('{{ $s->stok }}');
            kodeLain.push('{{ $s->id_gudang }}');
            totStok = +totStok + +'{{ $s->stok }}';
            break;
          } else {
            stokLain.push(0);
            kodeLain.push(kg[k]);
          }
        }
        urutKG++;
        // stokLain.push('{{ $s->stok }}');
        // kodeLain.push('{{ $s->id_gudang }}');
        // totStok += +'{{ $s->stok }}';
      }
    @endforeach

    // console.log(totStok);

    @foreach($barang as $br)
      if('{{ $br->id }}' == kodeBarang[i].value) {
        satuanUkuran = '{{ substr($br->satuan, -3) }}';
        if(satuanUkuran == 'Dus')
          pcs = 'Pcs';
        else
          pcs = 'Rol';
        ukuran = '{{ $br->ukuran }}';
      }
    @endforeach

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      netto[i].value = "";
      kodeGudang[i].value = "GDG01";
      qtyGudang[i].value = "";
      qty[i].value = "";
    }
    else if((+e.target.value - +qtyAwal[i].value) > totStok) {
      $('#notif'+i).modal("show");
      nmbrg[i].textContent = brgNama[i].value;
      // if(kodeBarang[i].value == kodeAwal[i].textContent)
      totStok += +qtyAwal[i].value;

      totalstok[i].textContent = `${totStok} ${pcs}`;
      totalsatuan[i].textContent = `${totStok / ukuran} ${satuanUkuran}`;

      qty[i].value = "";
      jumlah[i].value = "";
      netto[i].value = "";

      return false;
    }
    else {
      if((+e.target.value - +qtyAwal[i].value) > stokJohar) {
        var arrJumQty = qtyGudang[i].value.split(',');
        var jumQty = arrJumQty.reduce(getSum, 0);
        if((tempQty == 0) || ((tempQty > 0) && (jumQty != e.target.value))) {
          $('#'+i).modal("show");
          kodeModal = i;
          teksJohar[i].textContent = `${+stokJohar + +arrQtyAwal[0]}`;
          teksSatuan[i].textContent = `\u00A0${pcs} `;
          if(teksSat[i].value == 'Pcs') {
            teksJoharUkuran[i].textContent = `\u00A0/ ${stokJohar / ukuran}`;
            teksUkuran[i].textContent = `\u00A0${satuanUkuran}`;
          } else {
            teksJoharUkuran[i].textContent = ``;
            teksUkuran[i].textContent = ``;
          }

          qtyOrder[i].textContent = `${qty[i].value}`;
          qtySatuan[i].textContent = `\u00A0${pcs} `;
          if(teksSat[i].value == 'Pcs') {
            qtyOrderUkuran[i].textContent = `\u00A0/ ${qty[i].value / ukuran}`;
            qtyUkuran[i].textContent = `\u00A0${satuanUkuran}`;
          } else {
            qtyOrderUkuran[i].textContent = ``;
            qtyUkuran[i].textContent = ``;
          }

          sisaQty[i].textContent = `${+qty[i].value - (+stokJohar + +arrQtyAwal[0])}`;
          sisaSatuan[i].textContent = `\u00A0${pcs} `;
          if(teksSat[i].value == 'Pcs') {
            sisaQtyUkuran[i].textContent = `\u00A0/ ${(qty[i].value - +stokJohar) / ukuran}`;
            sisaUkuran[i].textContent = `\u00A0${satuanUkuran}`;
          } else {
            sisaQtyUkuran[i].textContent = ``;
            sisaUkuran[i].textContent = ``;
          }

          const kodeGud = document.querySelectorAll(".kodeGud"+i);
          const stokGudang = document.querySelectorAll('.stokGudang'+i);
          const gudangSatuan = document.querySelectorAll('.gudangSatuan'+i);
          const stokGudangUkuran = document.querySelectorAll('.stokGudangUkuran'+i);
          const gudangUkuran = document.querySelectorAll('.gudangUkuran'+i);
          cek = 0;
          for(let j = 0; j < stokGudang.length; j++) {
            if(kodeGud[j].value == arrKodeGud[cek+1]) {
              stokTambah = +stokLain[j] + +arrQtyAwal[cek+1];
              cek++;
            } else {
              stokTambah = stokLain[j];
            }

            stokGudang[j].textContent = `${stokTambah != null ? stokTambah : 0}`;
            gudangSatuan[j].textContent = `\u00A0${pcs} `;
            if(teksSat[i].value == 'Pcs') {
              stokGudangUkuran[j].textContent = `\u00A0/ ${stokTambah != null ? stokTambah : 0 / ukuran}`;
              gudangUkuran[j].textContent = `\u00A0${satuanUkuran}`;
            } else {
              stokGudangUkuran[j].textContent = ``;
              gudangUkuran[j].textContent = ``;
            }
            // stokGudangUkuran[i].textContent = `${stokTambah / ukuran}`;
            // gudangUkuran[i].textContent = `\u00A0${satuanUkuran}`;
          }
          // qtyGudang[i].value = +stokJohar + +arrQtyAwal[0];
          qtyAwalModal = +arrQtyAwal[0] + (+e.target.value - +arrQtyAwal[0] - +stokAwal) - sisaQty[i].textContent;
          if(kodeAwal != arrKodeGud[0]) {
            kodeGudang[i].value = `${arrKodeGud[0]},${kodeAwal}`;
            qtyGudang[i].value = `${qtyAwalModal},${stokAwal}`;
          } else {
            qtyJoharAwal = +qtyAwalModal + +stokAwal;
            kodeGudang[i].value = `${arrKodeGud[0]}`;
            qtyGudang[i].value = `${qtyJoharAwal}`;
          }
        }
      }
      else {
        if(+e.target.value < +qtyAwal[i].value) {
          kodeGudang[i].value = kodeGudangArr[i].value;
          var sisa = e.target.value;
          for(let j = 0; j < arrQtyAwal.length; j++) {
            if((+sisa - +arrQtyAwal[j]) > 0)
              sisa -= +arrQtyAwal[j];
            else {
              arrQtyAwal[j] = sisa;
              sisa = 0;
            }

            if(j == 0)
              qtyGudang[i].value = arrQtyAwal[j];
            else
              qtyGudang[i].value = qtyGudang[i].value.concat(`,${arrQtyAwal[j]}`);
          }
          // qtyJohar = +arrQtyAwal[0] - (+qtyAwal[i].value - +e.target.value);
        }
        else {
          kodeGudang[i].value = kodeGudangArr[i].value;
          qtyGudang[i].value = qtyAwal[i].value;
          // kodeGudang[i].value = arrKodeGud[0];
          // qtyGudang[i].value = arrQtyAwal[0];
          if(arrKodeGud[0] == 'GDG01') {
            qtyJohar = +arrQtyAwal[0] + (+e.target.value - +qtyAwal[i].value);
            for(let j = 0; j < arrQtyAwal.length; j++) {
              if(j == 0)
                qtyGudang[i].value = qtyJohar;
              else
                qtyGudang[i].value = qtyGudang[i].value.concat(`,${arrQtyAwal[j]}`);
            }
          } else {
            qtyLebih = +e.target.value - +qtyAwal[i].value;
            kodeGudang[i].value = kodeGudang[i].value.concat(`,${kodeAwal}`);
            if(qtyLebih < stokAwal) {
              qtyGudang[i].value = qtyGudang[i].value.concat(`,${qtyLebih}`);
            } else {
              qtyLebih -= stokAwal;
              qtyGudang[i].value = +qtyLebih + +arrQtyAwal[0];
              for(let j = 1; j < arrQtyAwal.length; j++) {
                qtyGudang[i].value = qtyGudang[i].value.concat(`,${arrQtyAwal[j]}`);
              }
              qtyGudang[i].value = qtyGudang[i].value.concat(`,${stokAwal}`);
            }

            /* for(let j = 0; j < stokLain.length; j++) {
              kodeGudang[i].value = kodeGudang[i].value.concat(`,${kodeLain[j]}`);
              if(qtyLebih < stokLain[j]) {
                // kodeGudang[i].value = kodeGudang[i].value.concat(`,${kodeLain[j]}`);
                qtyGudang[i].value = qtyGudang[i].value.concat(`,${qtyLebih}`);
                break;
              } else {
                qtyLebih -= stokLain[j];
                qtyGudang[i].value = qtyGudang[i].value.concat(`,${stokLain[j]}`);
              }
            } */
          }
        }

        // kodeGudang[i].value = kodeGudangArr[i].value;
        // qtyGudang[i].value = `${qtyJohar},${arrQtyAwal[1]},${arrQtyAwal[2]}`;

        // kodeGudang[i].value = 'GDG01';
        // qtyGudang[i].value = e.target.value;

        cek = 0;
        @foreach($gudang as $g)
          arrKodeGud = kodeGudang[i].value.split(',');
          // arrKodeGud.sort();
          arrQtyGud = qtyGudang[i].value.split(',');
          var kode = '{{ $g->id }}';
          var qtyGudangDet = document.querySelectorAll('.gud'+kode)[i];
          qtyGudangDet.value = '';
          for(let j = 0; j < arrKodeGud.length; j++) {
            if('{{ $g->id }}' == arrKodeGud[j]) {
              // var qtyGudangDet = document.querySelectorAll('.gud'+kode)[i];
              qtyGudangDet.value = arrQtyGud[j];
              cek++;
            }
            // else {
              // var qtyGudangDet = document.querySelectorAll('.gud'+kode)[i];
              // qtyGudangDet.value = '';
            // }
          }
        @endforeach
      }

      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    tempQty = e.target.value;
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  });
}

// Pilih Tipe
for(let i = 0; i < tipe.length; i++) {
  tipe[i].addEventListener("keyup", displayTipe);
  tipe[i].addEventListener("blur", displayTipe);

  function displayTipe(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
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
          var angkaDiskon = hitungDiskon(diskon[i].value);
          diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g,"") / 100).toFixed(0));
        }

        netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
        checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
        totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
        grandtotal.value = totalNotPPN.value;
      }
    @endforeach
  }
}

/** Tampil Kode Gudang Tambahan **/
for(let j = 0; j < modalGudang.length; j++) {
  $('#'+j).on('shown.bs.modal', function(e) {
    const kodeGud = document.querySelectorAll(".kodeGud"+j);
    const stokGudang = document.querySelectorAll('.stokGudang'+j);
    // kodeGudang[j].value = 'GDG01';
    // kodeGudang[j].value = arrKodeGud[0];
    // qtyGudang[j].value = +stokJohar + +arrQtyAwal[0];
    var cek = 0;

    const btnPilih = document.querySelectorAll(".btnPilih"+j);
    for(let i = 0; i < btnPilih.length; i++) {
      btnPilih[i].disabled = false;
      // btnPilih[i].addEventListener("click", function (e) {
      $(btnPilih[i]).off('click').on('click', function(e) {
        totPast = +stokGudang[i].textContent;
        if(+totPast < +sisaQty[j].textContent) {
          btnPilih[i].disabled = true;
          // btnPilih[i].removeEventListener('click', unclick, false);
          sisa = +sisaQty[j].textContent - +stokGudang[i].textContent;
          sisaQty[j].textContent = `${sisa}`;
          qtyGudang[j].value = qtyGudang[j].value.concat(`,${stokGudang[i].textContent}`);
          kodeGudang[j].value = kodeGudang[j].value.concat(`,${kodeGud[i].value}`);
        } else {
          qtyGudang[j].value = qtyGudang[j].value.concat(`,${sisaQty[j].textContent}`);
          kodeGudang[j].value = kodeGudang[j].value.concat(`,${kodeGud[i].value}`);
          cek = 1;
          // $('#'+j).modal("hide");
        }

        /* totPast = +qtyGudang[j].value + +stokGudang[i].textContent;
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
        } */

        @foreach($gudang as $g)
          arrKodeGud = kodeGudang[j].value.split(',');
          arrQtyGud = qtyGudang[j].value.split(',');
          var kode = '{{ $g->id }}';
          for(k = 0; k < arrKodeGud.length; k++) {
            if('{{ $g->id }}' == arrKodeGud[k]) {
              const qtyGudangDetil = document.querySelectorAll('.gud'+kode)[j];
              qtyGudangDetil.value = arrQtyGud[k];
              break;
            } else {
              const qtyGudangDetil = document.querySelectorAll('.gud'+kode)[j];
              qtyGudangDetil.value = '';
            }
          }
        @endforeach

        if(cek == 1)
          $('#'+j).modal("hide");

        // btnPilih[i].removeEventListener('click', handler);
      });
    }
  });
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
      netPast = +netto[i].value.replace(/\./g, "")
      diskonRp[i].value = addCommas(((angkaDiskon * jumlah[i].value.replace(/\./g, "")) / 100).toFixed(0));
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  }
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    $(tempo).tooltip('show');

    return false;
  }
  return true;
}

function getSum(total, num) {
  return +total + +num;
}

/** Check Jumlah Netto onChange **/
function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
  }
}

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  angka = angka.replace(/\,/g, ".");
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon =  ((totDiskon - 100) * -1).toFixed(2);
  return totDiskon;
}

/** Hitung PPN Dan Total **/
function total_ppn(sub) {
  ppn.value = addCommas(sub * 10 / 100);
  grandtotal.value = addCommas(+sub + +ppn.value.replace(/\./g, ""));
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
      totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
    }

    for(let j = i; j < jumBaris.value; j++) {
      if(j == jumBaris.value - 1) {
        $(tablePO).find('tr:last-child').remove();
      }
      else {
        netto[j].value = netto[j+1].value;
        diskonRp[j].value = diskonRp[j+1].value;
        diskon[j].value = diskon[j+1].value;
        jumlah[j].value = jumlah[j+1].value;
        harga[j].value = harga[j+1].value;
        @foreach($gudang as $g)
          var kodGud = '{{ $g->id }}';
          var gud = document.querySelectorAll('.gud'+kodGud);
          gud[j].value = gud[j+1].value;
        @endforeach
        qtyGudang[j].value = qtyGudang[j+1].value;
        kodeGudang[j].value = kodeGudang[j+1].value;
        qtyAwalArr[j].value = qtyAwalArr[j+1].value;
        kodeGudangArr[j].value = kodeGudangArr[j+1].value;
        qty[j].value = qty[j+1].value;
        qtyAwal[j].value = qtyAwal[j+1].value;
        brgNama[j].value = brgNama[j+1].value;
        kodeBarang[j].value = kodeBarang[j+1].value;
        if(kodeBarang[j+1].value == "")
          qty[j].removeAttribute('required');
        else
          qty[j+1].removeAttribute('required');
      }
    }
    jumBaris.value -= 1;
  });
}

/** Autocomplete Input Text **/
$(function() {
  var customer = [];
  @foreach($customer as $c)
    customer.push('{{ $c->nama }} ({{ $c->alamat }})');
  @endforeach

  var sales = [];
  @foreach($sales as $s)
    sales.push('{{ $s->nama }}');
  @endforeach

  var kat = ['CPL', 'Extrana C', 'Extrana T', 'Maspion C', 'Maspion T', 'MCB C', 'MCB T', 'Nitto C', 'Nitto T',
                  'Panasonic C', 'Panasonic T', 'Phillips C', 'Phillips T', 'Pipa C', 'Pipa T', 'Prime C', 'Prime T'];

  var kodeBrg = [];
  var namaBrg = [];
  @foreach($barang as $b)
    kodeBrg.push('{{ $b->id }}');
    namaBrg.push('{{ $b->nama }}'.replace(/&quot;/g, '\"'));
  @endforeach

  var tipeHarga = [];
  @foreach($hrg as $h)
    tipeHarga.push('{{ $h->tipe }}');
  @endforeach

  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

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

  $(namaSales).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(sales, extractLast(request.term)));
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

  $(kategori).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kat, extractLast(request.term)));
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

  /*-- Autocomplete Input Barang --*/
  for(let i = 0; i < brgNama.length; i++) {
    $(brgNama[i]).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(namaBrg, extractLast(request.term)));
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
  }

  for(let i = 0; i < kodeBarang.length; i++) {
    $(kodeBarang[i]).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(kodeBrg, extractLast(request.term)));
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
  }

  /*-- Autocomplete Input Tipe Harga --*/
  for(let i = 0; i < tipe.length; i++) {
    $(tipe[i]).on("keydown", function(event) {
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
  }
});


</script>
@endpush
