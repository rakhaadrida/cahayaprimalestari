@extends('layouts.admin')

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
            <form action="" method="">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold text-dark">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold" name="kode" value="{{ $items[0]->id_so }}">
                      </div>
                    </div>  
                  </div>
                  <div class="col" style="margin-left: -380px">
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right text-dark">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="tglSO" 
                        value="{{ $items[0]->so->tgl_so }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaCust" class="col-5 col-form-label text-bold text-right text-dark">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaCust"
                        value="{{ $items[0]->so->customer->nama }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSales" class="col-5 col-form-label text-bold text-right text-dark">Nama Sales</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaSales"
                        value="{{ $items[0]->so->customer->sales->nama }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row so-update-left">
                  <label for="nama" class="col-2 col-form-label text-bold text-dark">Tanggal Update</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" readonly class="form-control-plaintext form-control-sm text-bold" name="tanggal" value="{{ $tanggal }}">
                  </div>
                </div>
                <div class="form-group row so-update-input">
                  <label for="alamat" class="col-2 col-form-label text-bold text-dark">Keterangan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-5">
                    <input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm mt-1">
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil PO -->
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
                      <td align="right">{{ $item->harga }}</td>
                      <td align="right">{{ $item->qty * $item->harga }}</td>
                      <td align="right">{{ $item->diskon }} %</td>
                      <td align="right">
                        {{ (($item->qty * $item->harga) * $item->diskon) / 100 }}
                      </td>
                      <td align="right">
                        {{ ($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * $item->diskon) / 100) }}
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
                  <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ $subtotal }}" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2 mr-1">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ $subtotal * 10 / 100 }}" />
                </div>
              </div>
              <div class="form-group row justify-content-end grandtotal-so">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2 mr-1">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-lg text-right" 
                  value="{{ $subtotal + ($subtotal * 10 / 100) }}" />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="" formmethod="" class="btn btn-success btn-block text-bold">Submit</>
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
@endsection

@push('addon-script')
<script type="text/javascript">
const kode = document.getElementById("kodeSO");
const supplier = document.getElementById("namaSupplier");


</script>
@endpush