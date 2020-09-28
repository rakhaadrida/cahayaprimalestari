@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Penerimaan Barang</h1>
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

              <!-- Tabel Data Detil BM-->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold">
                  <td style="width: 40px">No</td>
                  <td style="width: 100px">Kode Barang</td>
                  <td>Nama Barang</td>
                  <td style="width: 80px">Total Stok</td>
                  @foreach($gudang as $g)
                    <td style="width: 80px">{{ $g->nama }}</td>
                  @endforeach
                </thead>
                <tbody id="tablePO">
                  @php $i = 1; @endphp
                  @foreach($stok as $s)
                    <tr>
                      <td style="width: 40px">{{ $i }}</td>
                      <td style="width: 100px">{{ $s->id_barang }}</td>
                      <td>{{ $s->barang->nama }}</td>
                      <td style="width: 80px">{{ $s->total }}</td>
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