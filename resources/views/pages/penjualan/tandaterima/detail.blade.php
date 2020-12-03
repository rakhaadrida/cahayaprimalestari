@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Tanda Terima</h1>
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
                      $itemsDetail = \App\Models\TandaTerima::where('id', $item->id)
                                      ->get();
                    @endphp
                    <div class="container so-update-container text-dark">
                      <div class="row">
                        <div class="col-12">
                          <div class="form-group row">
                            <label for="kode" class="col-2 form-control-sm text-bold mt-1">No. Tanda Terima</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ $item->id }}" >
                            </div>
                          </div>
                        </div> 
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row">
                            <label for="tanggal" class="col-3 form-control-sm text-bold mt-1">Nama User</label>
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
                            <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tgl Tanda Terima</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-2">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" value="{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}" >
                            </div>
                          </div>
                        </div>
                        <div class="col" style="margin-left: -450px">
                          <div class="form-group row customer-detail">
                            <label for="tanggal" class="col-3 form-control-sm text-bold mt-1">No. Faktur</label>
                            <span class="col-form-label text-bold">:</span>
                            <div class="col-7">
                              <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" 
                              @if($itemsDetail->count() == 1)
                                value="{{ $item->id_so }}"
                              @else 
                                value="{{ $itemsDetail[0]->id_so }} - {{ $itemsDetail->last()->id_so }}"
                              @endif
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Tabel Data Detil PO -->
                    <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                      <thead class="text-center text-bold text-dark">
                        <td style="width: 30px">No</td>
                        <td style="width: 100px">No. Faktur</td>
                        <td style="width: 120px">Tgl. Faktur</td>
                        <td>Nama Customer</td>
                        <td style="width: 100px">Kategori</td>
                        <td style="width: 120px">Tempo</td>
                        <td style="width: 160px">Status</td>
                      </thead>
                      <tbody>
                        @php 
                          $i = 1; $subtotal = 0;
                        @endphp
                        @foreach($itemsDetail as $itemDet)
                          <tr class="text-dark">
                              <td align="center">{{ $i }}</td>
                              <td align="center">{{ $itemDet->id_so }} </td>
                              <td align="center">
                                {{ \Carbon\Carbon::parse($item->so->tgl_so)->format('d-M-y') }}
                              </td>
                              <td>{{ $itemDet->so->customer->nama }}</td>
                              <td align="center">{{ $itemDet->so->kategori }}</td>
                              <td align="center">
                                {{ \Carbon\Carbon::parse($item->so->tgl_so)->addDays($item->so->tempo)->format('d-M-y') }}
                              </td>
                              <td align="center">{{ $itemDet->so->status }}</td>
                            </tr>
                          @php $i++; @endphp
                        @endforeach
                      </tbody>
                    </table>
                    <hr>
                    <!-- End Tabel Data Detil PO -->

                    <!-- Button Submit dan Reset -->
                    <div class="form-row justify-content-center">
                      <div class="col-2">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                      </div>
                    </div>
                    <!-- End Button Submit dan Reset -->
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

</script>
@endpush