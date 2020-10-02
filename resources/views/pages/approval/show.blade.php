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
              <div class="container so-update-container" style="margin-top: -15px">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row" >
                      <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $items[0]->id_so }}" >
                      </div>
                    </div>
                  </div> 
                  <div class="col" style="margin-left: -450px">
                    <div class="form-group row">
                      <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-7">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $items[0]->so->customer->nama }} ({{ $items[0]->so->id_customer }})" >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top: -5px">
                  <div class="col-12">
                    <div class="form-group row customer-detail">
                      <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ \Carbon\Carbon::parse($items[0]->so->tgl_so)->format('d-m-Y') }}" >
                      </div>
                    </div>
                  </div> 
                  <div class="col" style="margin-left: -450px">
                    <div class="form-group row customer-detail">
                      <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Nama Sales</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $items[0]->so->customer->sales->nama }}" >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top: -5px">
                  <div class="col-12">
                    <div class="form-group row customer-detail">
                      <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Status</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-3">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $items[0]->so->status }}" >
                      </div>
                    </div>
                  </div>
                  <div class="col" style="margin-left: -450px">
                    <div class="form-group row customer-detail">
                      <label for="tanggal" class="col-4 form-control-sm text-bold mt-1">Jatuh Tempo</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ \Carbon\Carbon::parse($items[0]->so->tgl_so)->add($items[0]->so->tempo, 'days')->format('d-m-Y') }}" >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row" style="margin-top: -5px">
                  <div class="col-12">
                    <div class="form-group row customer-detail">
                      <label for="keterangan" class="col-2 form-control-sm text-bold mt-1">Keterangan</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" value="{{ $itemsUpdate[0]->keterangan }}" >
                      </div>
                    </div>
                  </div>
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
                  <td style="width: 80px">Diskon(%)</td>
                  <td style="width: 110px">Diskon(Rp)</td>
                  <td style="width: 120px">Netto (Rp)</td>
                </thead>
                <tbody>
                  @php 
                    $i = 1; $subtotal = 0;
                  @endphp
                  @foreach($items as $item)
                    <tr class="text-bold">
                      <td align="center">{{ $i }}</td>
                      <td align="center">{{ $item->id_barang }} </td>
                      <td>{{ $item->barang->nama }}</td>
                      <td align="right">{{ $item->qty }}</td>
                      <td align="right">
                        {{ number_format($item->harga, 0, "", ".") }}
                      </td>
                      <td align="right">
                        {{number_format(($item->qty * $item->harga), 0, "", ".")}}
                      </td>
                      <td align="right">{{ $item->diskon }} %</td>
                      <td align="right">
                        {{ number_format((($item->qty * $item->harga) * $item->diskon) / 100, 0, "", ".") }}
                      </td>
                      <td align="right">
                        {{ number_format(($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100), 0, "", ".") }}
                      </td>
                      @php $subtotal += ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100); 
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
                  <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal, 0, "", ".") }}" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2 mr-1">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($subtotal * 10 / 100, 0, "", ".") }}" />
                </div>
              </div>
              <div class="form-group row justify-content-end grandtotal-so">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2 mr-1">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" value="{{number_format($subtotal + ($subtotal * 10 / 100),0,"",".")}}" />
                </div>
              </div>
              <div class="row justify-content-center" style="margin-top: -80px">
                <i class="fas fa-arrow-down fa-4x text-primary"></i>
              </div>
              <hr>
              <!-- End Tabel Data Awal SO -->

              <!-- Tabel Data Update SO -->
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
                  @foreach($itemsUpdate as $item)
                    <tr class="text-bold">
                      <td align="center">{{ $i }}</td>
                      <td align="center"
                      @if($item->id_barang != $items[$i-1]->id_barang) class="bg-warning text-danger" @endif>
                        {{ $item->id_barang }} 
                      </td>
                      <td @if($item->barang->nama != $items[$i-1]->barang->nama) class="bg-warning text-danger" @endif>{{ $item->barang->nama }}</td>
                      <td align="right" 
                      @if($item->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                        {{ $item->qty }}
                      </td>
                      <td align="right">
                        {{ number_format($item->harga, 0, "", ".") }}
                      </td>
                      <td align="right"
                      @if($item->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                        {{number_format(($item->qty * $item->harga), 0, "", ".")}}
                      </td>
                      <td align="right"
                      @if($item->diskon != $items[$i-1]->diskon) class="bg-warning text-danger" @endif>
                        {{ $item->diskon }} %
                      </td>
                      <td align="right"
                      @if($item->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                        {{ number_format((($item->qty * $item->harga) * $item->diskon) / 100, 0, "", ".") }}
                      </td>
                      <td align="right"
                      @if($item->qty != $items[$i-1]->qty) class="bg-warning text-danger" @endif>
                        {{ number_format(($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100), 0, "", ".") }}
                      </td>
                      @php $subtotalUpdate += ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100); 
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
              <hr>
              <!-- End Tabel Data Update SO -->

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