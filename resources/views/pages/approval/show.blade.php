@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Approval</h1>
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
                  <div class="carousel-item @if($item->id == $kode) active @endif "/>
                    @php 
                      if($item->tipe == 'Faktur') {
                        $items = \App\Models\DetilSO::with(['so', 'barang'])->where('id_so', $item->id_dokumen)->get();

                        $itemsUpdate = \App\Models\NeedApproval::with(['so'])->where('id_dokumen', $item->id)->get();
                      } 
                      elseif($item->tipe == 'Dokumen') {
                        $items = \App\Models\DetilBM::with(['bm', 'barang'])->where('id_bm', $item->id_dokumen)->get();

                        $itemsUpdate = \App\Models\NeedApproval::with(['bm'])->where('id_dokumen', $item->id)->get();
                      }

                    @endphp
                    <div class="container so-update-container">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row" >
                            <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor @if($item->tipe == 'Faktur') SO @else BM @endif</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $item->id_dokumen }}" >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row">
                            <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama @if($item->tipe == 'Faktur') Customer @else Supplier @endif</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-7">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold"
                              @if($item->tipe == 'Faktur')
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
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal @if($item->tipe == 'Faktur') SO @else BM @endif</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
                              @if($item->tipe == 'Faktur')
                                value="{{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-M-y') }}" >
                              @else
                                value="{{ \Carbon\Carbon::parse($item->bm->tanggal)->format('d-M-y') }}" >
                              @endif
                            </div>
                          </div>
                        </div>
                        @if($item->tipe == 'Faktur')   
                          <div class="col" style="margin-left: -450px">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Sales</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-4">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
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
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
                                value="{{ $item->bm->gudang->nama }}" >
                              </div>
                            </div>
                          </div>
                        @endif
                      </div>
                      <div class="row" style="margin-top: -5px">
                        <div class="col-12">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Status</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-3">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
                              @if($item->tipe == 'Faktur')
                                value="{{ $item->so->status }}" >
                              @else
                                value="{{ $item->bm->status }}" >
                              @endif
                            </div>
                          </div>
                        </div>
                        @if($item->tipe == 'Faktur') 
                          <div class="col" style="margin-left: -450px">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Jatuh Tempo</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-4">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ \Carbon\Carbon::parse($items[0]->so->tgl_so)->add($items[0]->so->tempo, 'days')->format('d-m-Y') }}" >
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
                          $j = 1; $subtotal = 0;
                        @endphp
                        @foreach($items as $i)
                          <tr class="text-bold">
                            <td align="center">{{ $j }}</td>
                            <td align="center">{{ $i->id_barang }} </td>
                            <td>{{ $i->barang->nama }}</td>
                            <td align="right">{{ $i->qty }}</td>
                            <td align="right">
                              {{ number_format($i->harga, 0, "", ".") }}
                            </td>
                            <td align="right">
                              {{number_format(($i->qty * $i->harga), 0, "", ".")}}
                            </td>
                            @if($item->tipe == 'Faktur') 
                              <td align="right">{{ $i->diskon }} %</td>
                              <td align="right">
                                {{ number_format((($i->qty * $i->harga) * $i->diskon) / 100, 0, "", ".") }}
                              </td>
                              <td align="right">
                                {{ number_format(($i->qty * $i->harga) - 
                                ((($i->qty * $i->harga) * $i->diskon) / 100), 0, "", ".") }}
                              </td>
                            @endif
                            @php 
                              if($item->tipe == 'Faktur')
                                $subtotal += ($i->qty * $i->harga) - 
                                ((($i->qty * $i->harga) * $i->diskon) / 100); 
                              @else
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
                      <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" value="{{number_format($subtotal, 0, "", ".")}}" />
                      </div>
                    </div>
                    <div class="row justify-content-center" style="margin-top: -80px">
                      <i class="fas fa-arrow-down fa-4x text-primary"></i>
                    </div>
                    <hr>
                    <!-- End Tabel Data Awal SO -->

                    @foreach($itemsUpdate as $iu)
                      <div class="container so-update-container" style="margin-top: 40px">
                        <div class="row" >
                          <div class="col-12">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal Ubah</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-3">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $iu->tanggal }}" >
                              </div>
                            </div>
                          </div>
                          <div class="col" style="margin-left: -450px">
                            <div class="form-group row customer-detail">
                              <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Keterangan</label>
                              <span class="col-form-label text-bold">:</span>
                              <div class="col-7">
                                <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $iu->keterangan }}" >
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
                                <input type="text" name="keterangan" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $iu->status }}" >
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Tabel Data Update SO -->
                      @php
                        $detilUpdate = \App\Models\NeedAppDetil::with(['need_app', 'barang'])->where('id_app', $item->id)->get();
                      @endphp
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
                          @php 
                            $i = 1; $subtotalUpdate = 0;
                          @endphp
                          @foreach($detilUpdate as $detItem)
                            <tr class="text-bold">
                              <td align="center">{{ $i }}</td>
                              <td align="center"
                              @if($detItem->id_barang != $items[$i-1]->id_barang) class="bg-warning text-danger" @endif>
                                {{ $detItem->id_barang }} 
                              </td>
                              <td @if($detItem->barang->nama != $items[$i-1]->barang->nama) class="bg-warning text-danger" @endif>{{ $detItem->barang->nama }}</td>
                              <td align="right" 
                              @if($detItem->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                                {{ $detItem->qty }}
                              </td>
                              <td align="right">
                                {{ number_format($detItem->harga, 0, "", ".") }}
                              </td>
                              <td align="right"
                              @if($detItem->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                                {{number_format(($detItem->qty * $detItem->harga), 0, "", ".")}}
                              </td>
                              <td align="right"
                              @if($detItem->diskon != $items[$i-1]->diskon) class="bg-warning text-danger" @endif>
                                {{ $detItem->diskon }} %
                              </td>
                              <td align="right"
                              @if($detItem->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                                {{ number_format((($detItem->qty * $detItem->harga) * $detItem->diskon) / 100, 0, "", ".") }}
                              </td>
                              <td align="right"
                              @if($detItem->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                                {{ number_format(($detItem->qty * $detItem->harga) - 
                                ((($detItem->qty * $detItem->harga) * $detItem->diskon) / 100), 0, "", ".") }}
                              </td>
                              @php $subtotalUpdate += ($detItem->qty * $detItem->harga) - 
                                ((($detItem->qty * $detItem->harga) * $detItem->diskon) / 100); 
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
                          <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotalUpdate * 10 / 100, 0, "", ".") }}" />
                        </div>
                      </div>
                      <div class="form-group row justify-content-end grandtotal-so">
                        <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                        <span class="col-form-label text-bold">:</span>
                        <div class="col-2 mr-1">
                          <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right
                          @if($subtotalUpdate != $subtotal) bg-warning text-danger @endif " value="{{number_format($subtotalUpdate + ($subtotalUpdate * 10 / 100),0,"",".")}}" />
                        </div>
                      </div>
                      @if($item->id != $itemsUpdate[$itemsUpdate->count() - 1]->id)
                        <div class="row justify-content-center" style="margin-top: -80px">
                          <i class="fas fa-arrow-down fa-4x text-primary"></i>
                        </div>
                      @endif
                      <hr>
                      <!-- End Tabel Data Update SO -->
                    @endforeach

                    <!-- Button Submit dan Reset -->
                    <div class="form-row justify-content-center">
                      <div class="col-2">
                        <button type="submit" formaction="{{route('app-process', $items[0]->id_so)}}" formmethod="POST" class="btn btn-success btn-block text-bold">Approve</button>
                      </div>
                      <div class="col-2">
                        <button type="submit" formaction="{{route('app-batal', $items[0]->id_so)}}" formmethod="POST" class="btn btn-danger btn-block text-bold">Batal Ubah</button>
                      </div>
                    </div>
                    <!-- End Button Submit dan Reset -->
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