@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Notifikasi</h1>
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
                  @foreach($notif as $item)
                    @if((($item->so != NULL) && ($item->so->user->roles == 'KENARI')) && (($item->tipe != 'Dokumen')))
                      <div class="carousel-item @if($item->id == $kode) active @endif"/>
                        @php 
                          $items = \App\Models\DetilSO::with(['so', 'barang'])
                                    ->select('id_barang', 'diskon')
                                    ->selectRaw('avg(harga) as harga, sum(qty) as qty')
                                    ->where('id_so', $item->id_dokumen)
                                    ->groupBy('id_barang', 'diskon')
                                    ->get();

                          $itemsUpdate = \App\Models\DetilApproval::with(['approval', 'barang'])->where('id_app', $item->id)->get();
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
                                  <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->so->customer->nama }} ({{ $item->so->id_customer }})" />
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
                                  <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-M-y') }}" />
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
                                    <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                                    value="{{ \Carbon\Carbon::parse($item->so->tgl_so)->add($item->so->tempo, 'days')->format('d-M-y') }}" >
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
                              <div class="col" style="margin-left: -450px; @if($item->status == 'APPROVE_LIMIT') margin-top: -10px @elseif(($item->tipe == 'Dokumen') && ($item->status == 'BATAL')) margin-top: -43px @else margin-top: -20px @endif">
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
                              $no = 1; $subtotal = 0; $totalKredit = 0;
                            @endphp
                            @foreach($itemsUpdate as $i)
                              <tr class="text-bold text-dark">
                                <td align="center">{{ $no }}</td>
                                <td align="center">{{ $i->id_barang }} </td>
                                <td>{{ $i->barang->nama }}</td>
                                <td align="right">{{ $i->qty }}</td>
                                @if($item->tipe == 'Faktur')
                                  @foreach($gudang as $g)
                                    @php
                                      $itemGud = \App\Models\DetilApproval::where('id_app',
                                              $i->id_app)
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
                              @php $no++; @endphp
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
                          <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                          <span class="col-form-label text-bold">:</span>
                          <div class="col-2 mr-1">
                            <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" value="{{number_format($subtotal, 0, "", ".")}}" />
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

                        
                        @if(($item->status != 'APPROVE_LIMIT') && ($item->status != 'BATAL'))
                          <div class="container so-update-container text-dark" style="margin-top: 40px">
                            <div class="row" >
                              <div class="col-12">
                                <div class="form-group row customer-detail">
                                  <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal Approve</label>
                                  <span class="col-form-label text-bold">:</span>
                                  <div class="col-3">
                                    <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                                    value="{{ \Carbon\Carbon::parse($itemsUpdate[0]->approval->tanggal)->format('d-M-y') }}" >
                                  </div>
                                </div>
                              </div>
                              <div class="col" style="margin-left: -450px">
                                <div class="form-group row customer-detail">
                                  <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Keterangan</label>
                                  <span class="col-form-label text-bold">:</span>
                                  <div class="col-7">
                                    <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                                    value="{{ $itemsUpdate[0]->approval->keterangan }}" >
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
                                    <input type="text" name="keterangan" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                                    value="{{ $itemsUpdate[0]->approval->status }}" >
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Tabel Data Update SO -->
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
                                  @if($iu->id_barang != $itemsUpdate[$i-1]->id_barang) class="bg-warning text-danger" @endif>
                                    {{ $iu->id_barang }} 
                                  </td>
                                  <td 
                                  @if($iu->barang->nama != $itemsUpdate[$i-1]->barang->nama) class="bg-warning text-danger" @endif>
                                  {{ $iu->barang->nama }}</td>
                                  <td align="right" 
                                  @if($iu->qty != $itemsUpdate[$i-1]->qty) class="bg-warning text-danger" @endif>
                                    {{ $iu->qty }}
                                  </td>
                                  @if($item->tipe == 'Faktur')
                                    @foreach($gudang as $g)
                                      @php
                                        $itemGud = \App\Models\DetilSO::where('id_so',
                                                $item->id_dokumen)
                                                ->where('id_barang', $iu->id_barang)
                                                ->where('id_gudang', $g->id)->get();
                                        
                                        $qtyGud = \App\Models\DetilApproval::where('id_app',
                                                $item->id)
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
                                  <td align="right">
                                    {{ number_format($iu->harga, 0, "", ".") }}
                                  </td>
                                  <td align="right" 
                                  @if($iu->qty != $itemsUpdate[$i-1]->qty) class="bg-warning text-danger" @endif>
                                    {{number_format(($iu->qty * $iu->harga), 0, "", ".")}}
                                  </td>
                                  @if($item->tipe == 'Faktur') 
                                    <td align="right"
                                    @if($iu->diskon != $itemsUpdate[$i-1]->diskon) class="bg-warning text-danger" @endif>
                                      {{ $iu->diskon }} %
                                    </td>
                                    @php 
                                      $diskon = 100;
                                      $arrDiskon = explode("+", $iu->diskon);
                                      for($j = 0; $j < sizeof($arrDiskon); $j++) {
                                        $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                                      } 
                                      $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                                    @endphp
                                    <td align="right" 
                                    @if($iu->qty != $itemsUpdate[$i-1]->qty) class="bg-warning text-danger" @endif>
                                      {{ number_format((($iu->qty * $iu->harga) * str_replace(",", ".", $diskon)) / 100, 0, "", ".") }}
                                    </td>
                                    <td align="right" 
                                    @if($iu->qty != $itemsUpdate[$i-1]->qty) class="bg-warning text-danger" @endif>
                                      {{ number_format(($iu->qty * $iu->harga) - 
                                      ((($iu->qty * $iu->harga) * str_replace(",", ".", $diskon)) / 100), 0, "", ".") }}
                                    </td>
                                  @endif
                                  @php 
                                    if($item->tipe != 'Dokumen') {
                                      $subtotalUpdate += ($iu->qty * $iu->harga) - 
                                      ((($iu->qty * $iu->harga) * str_replace(",", ".", $diskon)) / 100); 
                                    } else {
                                      $subtotalUpdate += $iu->qty * $iu->harga;
                                    }
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
                          <hr>
                          <!-- End Tabel Data Update SO -->
                        @endif

                        <!-- Button Submit dan Reset -->
                        <div class="form-row justify-content-center">
                          <div class="col-3">
                            @if(($item->tipe == 'Faktur') && (($item->status == 'UPDATE') || ($item->status == 'APPROVE_LIMIT')))
                              <button type="submit" formaction="{{ route('cetak-faktur-kenari', ['status' => 'false', 'awal' => '0', 'akhir' => '0']) }}"  formmethod="GET" class="btn btn-primary btn-block text-bold">Cetak</button>
                            @else
                              <button type="submit" formaction="{{ route('notif-read-kenari', $item->id) }}" formmethod="GET" class="btn btn-success btn-block text-bold">Tandai Sudah Dibaca</button>
                            @endif
                          </div>
                          <div class="col-3">
                            <button type="submit" formaction="{{ route('notif-kenari') }}" formmethod="GET" class="btn btn-outline-secondary btn-block text-bold">Kembali</button>
                          </div>
                        </div>
                        <!-- End Button Submit dan Reset -->

                        {{-- @if($item->tipe == 'Faktur')
                          <!-- Tampilan Cetak -->
                          <iframe src="{{url('so/cetak/'.$item->id_dokumen)}}" id="frameCetak" name="frameCetak" frameborder="0" hidden></iframe>
                        @endif

                        <script type="text/javascript">
                          function printFaktur() {
                            const printFrame = document.getElementById("frameCetak").contentWindow;

                            printFrame.window.onafterprint = function(e) {
                              window.location = "{{ route('notif-after-print', ['id' => $item->id_dokumen, 'kode' => $item->id]) }}";
                            }
                            
                            printFrame.window.focus();
                            printFrame.window.print();
                          }
                        </script> --}}

                      </div>
                    @endif
                  @endforeach
                </div>
                @if(($notif->count() > 0) && ($notif->count() != 1))
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

@endpush