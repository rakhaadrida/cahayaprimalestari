@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Barang Masuk</h1>
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
                  @foreach($items as $item)
                  <div class="carousel-item @if($item->id == $kode) active
                    @endif "
                  />
                    <div class="container so-update-container text-dark">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row">
                            <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor BM</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark"
                              @if($items->count() != 0)
                                value="{{ $item->id }}"
                              @endif
                              >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -480px">
                          <div class="form-group row">
                            <label for="tanggal" class="col-3 form-control-sm text-bold mt-1">Nama Supplier</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-8">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark text-wrap" 
                              @if($items->count() != 0)
                                value="{{ $item->supplier->nama }} ({{ $item->id_supplier }})"
                              @endif
                              >
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal BM</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark"
                              @if($items->count() != 0)
                                value="{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}"
                              @endif
                              >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -480px">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-3 form-control-sm text-bold mt-1">Nama Gudang</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-4">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                              @if($items->count() != 0)
                                value="{{ $item->gudang->nama }}"
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
                      </div>
                    </div>

                    <!-- Tabel Data Detil PO -->
                    <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                      <thead class="text-center text-bold text-dark">
                        <td style="width: 50px">No</td>
                        <td style="width: 100px">Kode</td>
                        <td>Nama Barang</td>
                        <td style="width: 80px">Qty</td>
                        <td>Harga</td>
                        <td>Jumlah</td>
                      </thead>
                      <tbody>
                        @if($items->count() != 0)
                          @php 
                            $i = 1; $subtotal = 0;
                            $itemsDetail = \App\Models\DetilBM::with(['barang'])
                                      ->where('id_bm', $item->id)->get();
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
                              @php $subtotal += $itemDet->qty * $itemDet->harga; 
                              @endphp
                            </tr>
                            @php $i++; @endphp
                          @endforeach
                        @else
                          <tr>
                            <td colspan="6" class="text-center text-bold h4 p-2"><i>Belum ada Detail BM</i></td>
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
                      <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="0"
                        />
                      </div>
                    </div>
                    <div class="form-group row justify-content-end grandtotal-so">
                      <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Grand Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mr-1">
                        <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" value=" {{number_format($subtotal, 0, "", ".") }}"
                        />
                      </div>
                    </div>
                    <hr>
                    <!-- End Tabel Data Detil PO -->

                    <!-- Button Submit dan Reset -->
                    <div class="form-row justify-content-center">
                      <div class="col-2">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
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