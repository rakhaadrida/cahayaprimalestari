@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Transfer Barang</h1>
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
                    @php 
                      $itemsDetail = \App\Models\DetilTB::where('id_tb', $item->id)->get();
                    @endphp
                    <div class="container so-update-container text-dark">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row">
                            <label for="kode" class="col-2 form-control-sm text-bold text-right mt-1">No. TB</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->id }}" >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row">
                            <label for="tanggal" class="col-3 form-control-sm text-bold text-right mt-1">Nama User</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-7">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->user->name }}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-2 form-control-sm text-bold text-right mt-1">Tanggal TB</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->tgl_tb)->format('d-M-y') }}" >
                            </div>
                          </div>
                        </div>
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-3 form-control-sm text-bold text-right mt-1">Status</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-7">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->status }}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Tabel Data Detil PO -->
                    <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                      <thead class="text-center text-bold text-dark">
                        <td style="width: 60px">No</td>
                        <td style="width: 140px">Kode Barang</td>
                        <td>Nama Barang</td>
                        <td style="width: 180px">Gudang Asal</td>
                        <td style="width: 120px">Qty Transfer</td>
                        <td style="width: 180px">Gudang Tujuan</td>
                      </thead>
                      <tbody>
                        @php 
                          $i = 1; $subtotal = 0;
                        @endphp
                        @foreach($itemsDetail as $itemDet)
                          <tr class="text-dark">
                            <td align="center">{{ $i }}</td>
                            <td align="center">{{ $itemDet->id_barang }} </td>
                            <td>{{ $itemDet->barang->nama }}</td>
                            <td align="center">{{ $itemDet->gudangAsal->nama }}</td>
                            <td align="center">{{ $itemDet->qty }}</td>
                            <td align="center">{{ $itemDet->gudangTuju->nama }}</td>
                          </tr>
                          @php $i++; @endphp
                        @endforeach
                      </tbody>
                    </table>
                    <hr>
                    <!-- End Tabel Data Detil PO -->

                    <!-- Button Submit dan Reset -->
                    <div class="form-row justify-content-center">
                      @if($item->status != 'BATAL')
                        <div class="col-2">
                          <a href="" class="btn btn-danger btn-block text-bold"  data-toggle="modal" 
                          data-target="#{{$item->id}}">Batal</a>
                          {{-- formaction="{{ route('tb-status', $item->id) }}" formmethod="POST" --}}
                        </div>
                      @endif
                      <div class="col-2">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                      </div>
                    </div>
                    <!-- End Button Submit dan Reset -->

                    <!-- Modal Ganti Status -->
                    <div class="modal" id="{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="{{$item->id}}" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" class="h2 text-bold">&times;</span>
                            </button>
                            <h4 class="modal-title">Ubah Status Transfer <b>{{$item->id}}</b></h4>
                          </div>
                          <div class="modal-body">
                              <div class="form-group row">
                                <label for="kode" class="col-2 col-form-label text-bold">Status</label>
                                <span class="col-form-label text-bold">:</span>
                                <div class="col-2">
                                  <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="statusUbah" value="BATAL">
                                </div>
                              </div>
                              <div class="form-group subtotal-so">
                                <label for="keterangan" class="col-form-label">Keterangan</label>
                                <input type="text" class="form-control" name="ket{{$item->id}}" 
                                id="ket{{$item->id}}" data-toogle="tooltip" data-placement="bottom" title="Form keterangan harus diisi">
                              </div>
                              <hr>
                              <div class="form-row justify-content-center">
                                <div class="col-3">
                                  <button type="submit" class="btn btn-success btn-block text-bold" id="btn{{$item->id}}" onclick="return checkEditable({{$item->id}})">Simpan</button>
                                </div>
                                <div class="col-3">
                                  <button button type="button" class="btn btn-outline-secondary btn-block text-bold" data-dismiss="modal">Batal</button>
                                </div>
                              </div>
                          </div>
                        </div>
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
<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
function checkEditable(kode) {
  const ket = document.getElementById("ket"+kode.id);
  if(ket.value == "") {
    $(ket).tooltip('show');
    return false;
  }
  else {
    @foreach($items as $item)
      if('{{ $item->id }}' == kode.id) {
        document.getElementById("btn"+kode.id).formMethod = "POST";
        document.getElementById("btn"+kode.id).formAction = '{{ route('tb-status', $item->id) }}';
      }
    @endforeach
  }
}
</script>
@endpush