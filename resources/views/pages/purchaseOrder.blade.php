@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Purchase Order</h1>
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
            <form action="{{ route('po.store') }}" method="POST">
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-2 col-form-label text-bold ">Nomor PO</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="kode"
                  value="{{ $newcode }}" readonly>
                </div>
                {{-- <div class="col-1"></div> --}}
                <label for="nama" class="col-2 col-form-label text-bold ">Tanggal PO</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm" name="tanggal"
                  value="{{ old('tanggal') }}" readonly>
                </div>
              </div> 
              <div class="form-group row">
                <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <select name="namaSupplier" class="form-control col-form-label-sm">
                    @foreach($supplier as $s) 
                      <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              {{-- <div class="form-group row">
                <label for="keterangan" class="col-1 col-form-label text-bold">Keterangan</label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm ml-1" name="keterangan" placeholder="Keterangan" 
                    value="{{ old('keterangan') }}">
                </div>
              </div> --}}
              <hr>
              <span class="add-table-line float-left mb-2 mr-2">
                <a href="#!" class="text-secondary">
                  <i class="fas fa-plus" aria-hidden="true"> 
                    <span class="add-line ml-2"> Tambah Baris </span>  
                  </i>
                </a>
              </span>
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold">
                  <td>No</td>
                  <td>Nama Barang</td>
                  <td>Pack</td>
                  <td>Qty (Pcs)</td>
                  <td>Harga</td>
                  <td>Jumlah</td>
                </thead>
                <tbody>
                  @for($i=1; $i<=5; $i++)
                    <tr>
                      <td align="center">{{ $i }}</td>
                      <td >
                        <select name="namaBarang" class="form-control form-control-sm">
                          @foreach($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                          @endforeach
                        </select>
                      </td>
                      <td contenteditable="true"></td>
                      <td contenteditable="true"></td>
                      <td></td>
                      <td></td>
                    </tr>
                  @endfor
                </tbody>
              </table>
              <hr>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold">Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
                </div>
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
<script>
const $tableID = $('#table');
const newTr = `
  <tr class="hide">
    <td align="center">{{ $i }}</td>
    <td>
      <select name="namaBarang" class="form-control form-control-sm">
        @foreach($barang as $b)
          <option value="{{ $b->id }}">{{ $b->nama }}</option>
        @endforeach
      </select>
    </td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
  </tr>`;

$('.add-table-line').on('click', 'i', () => {

  const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');

  if ($tableID.find('tbody tr').length === 0) {

    $('tbody').append(newTr);
  }

  $tableID.find('table').append($clone);
});
</script>
@endpush