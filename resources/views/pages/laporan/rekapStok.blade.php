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

              <div class="row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('rs-cetak') }}" formmethod="POST" class="btn btn-primary btn-block text-bold">Print</>
                </div>
                <div class="col-2">
                  <button type="submit" formaction="{{ route('rs-cetak') }}" formmethod="POST" formtarget="_blank" class="btn btn-success btn-block text-bold">Download PDF</>
                </div>
                <div class="col-2">
                  <button type="submit" formaction="{{ route('rs-excel') }}" formmethod="POST"  class="btn btn-danger btn-block text-bold">Download Excel</>
                </div>
              </div>
              <hr>

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
<script type="text/javascript">

</script>
@endpush