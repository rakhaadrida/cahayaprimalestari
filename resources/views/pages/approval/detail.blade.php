@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Histori Approval</h1>
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
              <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner">
                  @foreach($approval as $item)
                  <div class="carousel-item @if($item->id_dokumen == $kode) active
                    @endif "
                  />
                    @php 
                      if($item->tipe != 'Dokumen') {
                        // $items = \App\Models\DetilSO::with(['so', 'barang'])->where('id_so', $item->id_dokumen)->get();
                        $items = \App\Models\DetilSO::with(['so', 'barang'])
                                  ->select('id_barang', 'diskon')
                                  ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                  ->where('id_so', $item->id_dokumen)
                                  ->groupBy('id_barang', 'diskon')
                                  ->get();

                        $itemsUpdate = \App\Models\Approval::with(['so'])->where('id_dokumen', $item->id_dokumen)->get();
                      } 
                      else {
                        $items = \App\Models\DetilBM::with(['bm', 'barang'])->where('id_bm', $item->id_dokumen)->get();

                        $itemsUpdate = \App\Models\Approval::with(['bm'])->where('id_dokumen', $item->id_dokumen)->get();
                      }

                    @endphp
                    <div class="container so-update-container text-dark">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row" >
                            <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor @if($item->tipe == 'Faktur') SO @else BM @endif</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->id_dokumen }}" >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row">
                            <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama @if($item->tipe != 'Dokumen') Customer @else Supplier @endif</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-7">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark"
                              @if($item->tipe != 'Dokumen')
                                value="{{ $item->so->customer->nama }} ({{ $item->so->id_customer }})" >
                              @else
                                value="{{ $item->bm->supplier->nama }} ({{ $item->bm->id_supplier }})" >
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row" style="margin-top: -5px">
                        <div class="col-12">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal @if($item->tipe != 'Dokumen') SO @else BM @endif</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                              @if($item->tipe != 'Dokumen')
                                value="{{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-M-y') }}" >
                              @else
                                value="{{ \Carbon\Carbon::parse($item->bm->tanggal)->format('d-M-y') }}" >
                              @endif
                            </div>
                          </div>
                        </div>
                        @if($item->tipe != 'Dokumen')   
                          <div class="col" style="margin-left: -450px">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Sales</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-4">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                                value="{{ $item->so->customer->sales->nama }}" >
                              </div>
                            </div>
                          </div>
                        @else
                          <div class="col" style="margin-left: -450px">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Gudang</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-4">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="namaGudang"
                                value="{{ $item->bm->gudang->nama }}" >
                                <input type="hidden" name="kodeGudang" value="{{ $item->bm->id_gudang }}">
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>
                      <div class="row" style="margin-top: -5px">
                        <div class="col-12">
                          <div class="form-group row customer-detail" @if($item->status == 'BATAL') style="margin-top: -20px" @endif>
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Status</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-3">
                              <input type="text" name="status" readonly class="form-control-plaintext col-form-label-sm text-bold @if($item->status == 'BATAL') bg-warning text-danger @else text-dark @endif" value="{{ $item->status }}">
                              <input type="hidden" name="tipe" value="{{ $item->tipe }}">
                            </div>
                          </div>
                        </div>
                        @if($item->tipe != 'Dokumen') 
                          <div class="col" style="margin-left: -450px">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Jatuh Tempo</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-4">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->so->tgl_so)->add($item->so->tempo, 'days')->format('d-m-Y') }}" >
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>
                      <div class="row" style="margin-top: 5px;">
                        <div class="col-12">
                          @if($item->status == 'APPROVE_LIMIT')
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-2 form-control-sm text-bold text-dark mt-1" style="font-size: 16px">Limit</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-2">
                                <input type="text" readonly class="form-control-plaintext col-form-label-md bg-warning text-danger text-bold text-lg" value="{{ number_format($item->so->customer->limit, 0, "", ".") }}" >
                              </div>
                            </div>
                          @endif
                        </div>
                        @if(($item->status == 'APPROVE_LIMIT') || ($item->status == 'BATAL'))
                          <div class="col" style="margin-left: -450px; @if($item->tipe == 'Limit') margin-top: -10px @else margin-top: -20px @endif">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Keterangan</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-6">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->keterangan }}" >
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>
                    </div>
                    @if(($itemsUpdate->last()->status != 'LIMIT') && ($itemsUpdate->last()->status != 'PENDING_BATAL'))
                      @foreach($itemsUpdate as $iu)
                        @if($itemsUpdate[0]->id != $iu->id)
                          <div class="container so-update-container text-dark" style="margin-top: 40px">
                            <div class="row" >
                              <div class="col-12">
                                <div class="form-group row customer-detail">
                                  <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal Approve</label>
                                  <span class="col-form-label text-bold">:</span>
                                  <div class="col-3">
                                    <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($iu->tanggal)->format('d-M-y') }}" >
                                  </div>
                                </div>
                              </div>
                              <div class="col" style="margin-left: -450px">
                                <div class="form-group row customer-detail">
                                  <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Keterangan</label>
                                  <span class="col-form-label text-bold">:</span>
                                  <div class="col-7">
                                    <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $iu->keterangan }}" >
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row" style="margin-top: -5px">
                              <div class="col-12">
                                <div class="form-group row customer-detail">
                                  <label for="keterangan" class="col-2 form-control-sm text-bold mt-1">Status</label>
                                  <span class="col-form-label text-bold">:</span>
                                  <div class="col-5">
                                    <input type="text" name="statusApp{{$item->id_dokumen}}" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $iu->status }}" >
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif

                        @php
                          $detilUpdate = \App\Models\DetilApproval::with(['approval', 'barang'])
                                        ->select('id_barang', 'diskon')
                                        ->selectRaw('avg(harga) as harga, sum(qty) as qty')
                                        ->where('id_app', $iu->id)
                                        ->groupBy('id_barang', 'diskon')
                                        ->get();
                        @endphp

                        <!-- Tabel Data Awal SO -->
                        <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                          <thead class="text-center text-bold text-dark">
                            <td style="width: 30px">No</td>
                            <td style="width: 80px">Kode</td>
                            <td>Nama Barang</td>
                            <td style="width: 50px">Qty</td>
                            @if($item->tipe == 'Faktur') 
                              @foreach($gudang as $g)
                                <td style="width: 50px">{{ substr($g->nama, 0, 3) }}</td>
                              @endforeach
                            @endif
                            <td>Harga</td>
                            <td>Jumlah</td>
                            @if($item->tipe != 'Dokumen') 
                              <td style="width: 80px">Diskon(%)</td>
                              <td style="width: 110px">Diskon(Rp)</td>
                              <td style="width: 120px">Netto (Rp)</td>
                            @endif
                          </thead>
                          <tbody>
                            @php 
                              $j = 1; $subtotal = 0;
                            @endphp
                            @foreach($detilUpdate as $i)
                              <tr class="text-bold text-dark">
                                <td align="center">{{ $j }}</td>
                                <td align="center">{{ $i->id_barang }} </td>
                                <td>{{ $i->barang->nama }}</td>
                                <td align="right">{{ $i->qty }}</td>
                                @if($item->tipe == 'Faktur')
                                  @foreach($gudang as $g)
                                    @php
                                      $itemGud = \App\Models\DetilSO::where('id_so',
                                              $item->id_dokumen)
                                              ->where('id_barang', $i->id_barang)
                                              ->where('id_gudang', $g->id)->get();
                                    @endphp
                                    @if($itemGud->count() != 0)
                                      <td align="right">{{ $itemGud[0]->qty }}</td>
                                    @else
                                      <td></td>
                                    @endif
                                  @endforeach
                                @endif
                                <td align="right">
                                  {{ number_format($i->harga, 0, "", ".") }}
                                </td>
                                <td align="right">
                                  {{number_format(($i->qty * $i->harga), 0, "", ".")}}
                                </td>
                                @if($item->tipe != 'Dokumen') 
                                  <td align="right">{{ $i->diskon }} %</td>
                                  @php 
                                    $diskon = 100;
                                    $arrDiskon = explode("+", $i->diskon);
                                    for($j = 0; $j < sizeof($arrDiskon); $j++) {
                                      $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                                    } 
                                    $diskon = number_format((($diskon - 100) * -1), 2, ".", "");
                                  @endphp
                                  <td align="right">
                                    {{ number_format((($i->qty * $i->harga) * $diskon) / 100, 0, "", ".") }}
                                  </td>
                                  <td align="right">
                                    {{ number_format(($i->qty * $i->harga) - 
                                    ((($i->qty * $i->harga) * $diskon) / 100), 0, "", ".") }}
                                  </td>
                                @endif
                                @php 
                                  if($item->tipe != 'Dokumen') {
                                    $subtotal += ($i->qty * $i->harga) - 
                                    ((($i->qty * $i->harga) * $diskon) / 100); 
                                  }
                                  else
                                    $subtotal += $i->qty * $i->harga;
                                @endphp
                              </tr>
                              @php $j++; @endphp
                            @endforeach
                          </tbody>
                        </table>

                        <div class="form-group row justify-content-end subtotal-so">
                          <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2 mr-1">
                            <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal, 0, "", ".") }}" />
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
                          <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">@if($item->status != 'APPROVE_LIMIT') Total Tagihan @else Total SO @endif</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2 mr-1">
                            <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold @if($item->status != 'APPROVE_LIMIT') bg-warning text-danger @else text-dark @endif text-lg text-right" value="{{number_format($subtotal, 0, "", ".")}}" />
                          </div>
                        </div>
                        @if($item->status == 'APPROVE_LIMIT')
                          <div class="form-group row justify-content-end" style="margin-top: 5px">
                            <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Kredit</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2 mr-1">
                              <input type="text" name="totalKredit" id="totalKredit" readonly class="form-control-plaintext text-bold text-dark text-lg text-right" @foreach($total as $t)
                                @if($t->id_customer == $item->so->id_customer)
                                  value="{{number_format($t->total, 0, "", ".")}}" 
                                  @php $totalKredit = $t->total; @endphp
                                @endif
                              @endforeach 
                              />
                            </div>
                          </div>
                          <br>
                          <div class="form-group row justify-content-end" style="margin-top: -40px">
                            <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2 mr-1">
                              <input type="text" name="totalTagihan" id="totalTagihan" readonly class="form-control-plaintext text-bold bg-warning text-danger text-lg text-right" value="{{number_format($subtotal + $totalKredit, 0, "", ".")}}" />
                            </div>
                          </div>
                        @endif
                        @if(($item->status != 'APPROVE_LIMIT') && ($item->status != 'BATAL'))
                          <div class="row justify-content-center" style="margin-top: -80px">
                            <i class="fas fa-arrow-down fa-4x text-primary"></i>
                          </div>
                        @endif
                        <hr>
                        <!-- End Tabel Data Awal SO -->
                      @endforeach
                    @endif

                    @if(($item->status != 'APPROVE_LIMIT') && ($item->status != 'BATAL'))
                    <!-- Tabel Data Update SO -->
                      <div class="container so-update-container text-dark" style="margin-top: 40px">
                        <div class="row" >
                          <div class="col-12">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal Ubah</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-3">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}" >
                              </div>
                            </div>
                          </div>
                          <div class="col" style="margin-left: -450px">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Keterangan</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-7">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->keterangan }}" >
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="margin-top: -5px">
                          <div class="col-12">
                            <div class="form-group row customer-detail">
                              <label for="keterangan" class="col-2 form-control-sm text-bold mt-1">Status</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-5">
                                <input type="text" name="statusApp{{$item->id_dokumen}}" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->tipe == 'Faktur' ? $item->so->status : $item->bm->status }}" >
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                        <thead class="text-center text-bold text-dark">
                          <td style="width: 30px">No</td>
                          <td style="width: 80px">Kode</td>
                          <td>Nama Barang</td>
                          <td style="width: 50px">Qty</td>
                          @if($item->tipe == 'Faktur') 
                            @foreach($gudang as $g)
                              <td style="width: 50px">{{ substr($g->nama, 0, 3) }}</td>
                            @endforeach
                          @endif
                          <td>Harga</td>
                          <td>Jumlah</td>
                          @if($item->tipe == 'Faktur') 
                            <td style="width: 80px">Diskon(%)</td>
                            <td style="width: 110px">Diskon(Rp)</td>
                            <td style="width: 120px">Netto (Rp)</td>
                          @endif
                        </thead>
                        <tbody>
                          @php 
                            $i = 1; $subtotalUpdate = 0;
                          @endphp
                          @foreach($items as $iu)
                            <tr class="text-bold text-dark">
                              <td align="center">{{ $i }}</td>
                              <td align="center"
                              @if((($i <= $items->count()) && ($iu->id_barang != $items[$i-1]->id_barang)) || ($i > $items->count())) class="bg-warning text-danger" @endif>
                                {{ $iu->id_barang }} 
                              </td>
                              <td
                              @if((($i <= $items->count()) && ($iu->barang->nama != $items[$i-1]->barang->nama)) || ($i > $items->count())) class="bg-warning text-danger" @endif>
                                {{ $iu->barang->nama }}</td>
                              <td align="right"
                              @if((($i <= $items->count()) && ($iu->qty != $items[$i-1]->qty)) || ($i > $items->count())) class="bg-warning text-danger" @endif>
                                {{ $iu->qty }}
                              </td>
                              @if($item->tipe == 'Faktur')
                                @foreach($gudang as $g)
                                  @php
                                    $itemGud = \App\Models\DetilApproval::where('id_app',
                                            $item->id)
                                            ->where('id_barang', $iu->id_barang)
                                            ->where('id_gudang', $g->id)->get();
                                    
                                    $qtyGud = \App\Models\DetilSO::where('id_so',
                                            $item->id_dokumen)
                                            ->where('id_barang', $iu->id_barang)
                                            ->where('id_gudang', $g->id)->get();
                                  @endphp
                                  @if($itemGud->count() != 0)
                                    <td align="right" 
                                    @if(($qtyGud->count() == 0) || ($itemGud[0]->qty != $qtyGud[0]->qty)) class="bg-warning text-danger" @endif>
                                      {{ $itemGud[0]->qty }}
                                    </td>
                                  @else
                                    <td></td>
                                  @endif
                                @endforeach
                              @endif
                              <td align="right"
                              @if((($i <= $items->count()) && ($iu->harga != $items[$i-1]->harga) || ($i > $items->count()))) class="bg-warning text-danger" @endif>
                                {{ number_format($iu->harga, 0, "", ".") }}
                              </td>
                              <td align="right"
                              @if((($i <= $items->count()) && ($iu->qty != $items[$i-1]->qty)) || ($i > $items->count())) class="bg-warning text-danger" @endif>
                                {{number_format(($iu->qty * $iu->harga), 0, "", ".")}}
                              </td>
                              @php 
                                if($item->diskon != '') {
                                  $diskon = 100;
                                  $arrDiskon = explode("+", $iu->diskon);
                                  for($j = 0; $j < sizeof($arrDiskon); $j++) {
                                    $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                                  } 
                                  $diskon = number_format((($diskon - 100) * -1), 2, ".", "");
                                } else {
                                  $diskon = NULL;
                                }
                              @endphp
                              @if($item->tipe == 'Faktur') 
                                <td align="right"
                                @if((($i <= $items->count()) && ($iu->diskon != $items[$i-1]->diskon)) || ($i > $items->count())) class="bg-warning text-danger" @endif>
                                  {{ $iu->diskon }} %
                                </td>
                                <td align="right"
                                @if((($i <= $items->count()) && ($iu->qty != $items[$i-1]->qty)) || ($i > $items->count())) class="bg-warning text-danger" @endif>
                                  {{ number_format((($iu->qty * $iu->harga) * $diskon) / 100, 0, "", ".") }}
                                </td>
                                <td align="right"
                                @if((($i <= $items->count()) && ($iu->qty != $items[$i-1]->qty)) || ($i > $items->count())) class="bg-warning text-danger" @endif>
                                  {{ number_format(($iu->qty * $iu->harga) - 
                                  ((($iu->qty * $iu->harga) * $diskon) / 100), 0, "", ".") }}
                                </td>
                              @endif
                              @php $subtotalUpdate += ($iu->qty * $iu->harga) - 
                                ((($iu->qty * $iu->harga) * $diskon) / 100); 
                              @endphp
                            </tr>
                            @php $i++; @endphp
                          @endforeach
                        </tbody>
                      </table>

                      <div class="form-group row justify-content-end subtotal-so">
                        <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                        <span class="col-form-label text-bold">:</span>
                        <div class="col-2 mr-1">
                          <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotalUpdate, 0, "", ".") }}" />
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
                          <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right
                          @if($subtotalUpdate != $subtotal) bg-warning text-danger @endif " value="{{number_format($subtotalUpdate, 0, "", ".")}}" />
                        </div>
                      </div>
                      <br>
                    @endif
                    <!-- End Tabel Data Update SO -->
                  </div>
                  @endforeach
                </div>
                @if(($approval->count() > 0) && ($approval->count() != 1))
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

</script>
@endpush