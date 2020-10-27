@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Rekap Stok Barang</h1>
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

              <div class="container so-container">  
                <div class="form-group row justify-content-center" style="margin-top: -10px">
                  <label for="tanggal" class="col-auto col-form-label text-bold">Tanggal Rekap</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tanggal" >
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('rs-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>

              <div class="container" style="margin-bottom: 5px">
                <div class="row justify-content-center">
                  <h4 class="text-bold text-dark">Rekap Stok Barang</h4>
                </div>
                <div class="row justify-content-center">
                  <h5 class="text-dark kode-cetak-stok">
                    Dari Kode {{$stok[0]->id_barang}} s/d {{ $stok[$stok->count() - 1]->id_barang}}
                  </h5>
                </div>
                <div class="row justify-content-center">
                  <h6 class="text-dark ">Waktu : {{ \Carbon\Carbon::now()->format('d F Y, H:i:s') }}</h6>
                </div>
              </div>

              <div class="row justify-content-center" style="margin-bottom: 15px">
                <div class="col-2">
                  <a href="{{ url('/rekap/cetak') }}" class="btn btn-primary btn-block text-bold btnprnt">Print</a>
                </div>
                <div class="col-2">
                  <button type="submit" formaction="{{ route('rs-pdf') }}" formmethod="POST" formtarget="_blank" class="btn btn-success btn-block text-bold">Download PDF</>
                </div>
                <div class="col-2">
                  <button type="submit" formaction="{{ route('rs-excel') }}" formmethod="POST"  class="btn btn-danger btn-block text-bold">Download Excel</>
                </div>
              </div>

              <!-- Tabel Data Detil BM-->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-dark text-bold">
                  <td style="width: 40px" class="align-middle">No</td>
                  <td style="width: 100px">Kode Barang</td>
                  <td class="align-middle">Nama Barang</td>
                  <td style="width: 110px; background-color: yellow" class="align-middle">Total Stok</td>
                  @foreach($gudang as $g)
                    <td style="width: 110px" class="align-middle">{{ $g->nama }}</td>
                  @endforeach
                </thead>
                <tbody id="tablePO">
                  @php $i = 1; @endphp
                  @foreach($stok as $s)
                    <tr class="text-dark text-bold">
                      <td align="center">{{ $i }}</td>
                      <td>{{ $s->id_barang }}</td>
                      <td>{{ $s->barang->nama }}</td>
                      <td align="right" style="background-color: yellow">{{ $s->total }}</td>
                      @php
                        $stokGd = \App\Models\StokBarang::where('id_barang', $s->id_barang)->get();
                      @endphp
                      @foreach($stokGd as $sg)
                        <td align="right">{{ $sg->stok }}</td>
                      @endforeach
                    </tr>
                    @php $i++ @endphp
                  @endforeach
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
  <script src="{{ url('backend/vendor/jquery/jquery.printPage.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.btnprnt').printPage();
    });
  </script>
@endpush