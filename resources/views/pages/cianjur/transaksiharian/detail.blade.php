@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
  	<div class="d-sm-flex align-items-center justify-content-between mb-0">
      	<h1 class="h3 mb-0 text-gray-800 menu-title">Transaksi Harian Toko</h1>
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
                <div class="container so-update-container text-dark">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group row">
                        <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor Faktur</label>
                        <span class="col-form-label text-bold">:</span>
                        <div class="col-2">
                          <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->nomor }}" >
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group row customer-detail">
                        <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal Faktur</label>
                        <span class="col-form-label text-bold">:</span>
                        <div class="col-2">
                          <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}" >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                  <thead class="text-center text-bold text-dark">
                    <td style="width: 60px">No</td>
                    <td style="width: 150px">Kode</td>
                    <td>Nama Barang</td>
                    <td style="width: 120px">Qty</td>
                    <td>Harga</td>
                    <td>Jumlah</td>
                  </thead>
                  <tbody>
                    @php 
                      $itemsDetail = \App\Models\FakturItem::query()
                        ->join('barang', 'barang.id', '=', 'faktur_item.id_barang')
                        ->where('id_faktur', $item->id)
                        ->get();
                    @endphp
                    @foreach($itemsDetail as $key => $itemDet)
                      <tr class="text-dark">
                        <td align="center">{{ ++$key }}</td>
                        <td align="center">{{ $itemDet->id_barang }} </td>
                        <td>{{ $itemDet->barang->nama }}</td>
                        <td align="right">{{ $itemDet->qty }}</td>
                        <td align="right">{{ number_format($itemDet->harga, 0, "", ".") }}</td>
                        <td align="right">{{ number_format($itemDet->jumlah, 0, "", ".") }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="form-group row justify-content-end subtotal-so">
                  <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Sub Total</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mr-1">
                    <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($item->total, 0, "", ".") }}" />
                  </div>
                </div>
                <hr>
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')

@endpush