@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Kartu Stok</h1>
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
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Dari Kode Barang</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="kodeAwal" id="kodeAwal" value="{{ $itemsBRG[0]->id }}">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s/d</label>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="kodeAkhir" id="kodeAkhir" 
                    value="{{ $itemsBRG[$itemsBRG->count() - 1]->id }}">
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Dari Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" value="{{ $awal }}">
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s/d</label>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAkhir" value="{{ $akhir }}" >
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="" formmethod="" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container">
                <div class="row justify-content-center">
                  <h5 class="text-bold text-dark">
                    Kode Barang : {{$itemsBRG[0]->id}} s/d {{$itemsBRG[$itemsBRG->count() - 1]->id}}
                  </h5>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h5 class="text-bold text-dark">Tanggal : {{$awal}} s/d {{$akhir}}</h5>
                </div>
              </div>
              <br>

              <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner">
                  @foreach($itemsBRG as $item)
                  <div class="carousel-item 
                    @if($item->id == $itemsBRG[0]->id) active
                    @endif "
                  />
                  <div class="container so-update-container">
                    <div class="form-group row">
                      <label for="kode" class="col-auto form-control-sm text-bold mt-1">Kode Barang</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold"
                        @if($rowBM != 0)
                          value="{{ $item->id }} - {{ $item->nama }}"
                        @elseif($rowSO != 0)
                          value="{{ $item->id }} - {{ $item->nama }}"
                        @endif 
                        >
                      </div>
                    </div>
                  </div>

                  <!-- Tabel Data Detil PO -->
                  <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                    <thead class="text-center text-bold text-dark" style="background-color: yellow">
                      <tr>
                        <td rowspan="2" style="width: 30px" class="align-middle">No</td>
                        <td rowspan="2" style="width: 95px" class="align-middle">Tanggal</td>
                        <td rowspan="2" style="width: 100px"class="align-middle">Tipe Transaksi</td>
                        <td rowspan="2" style="width: 100px"class="align-middle">Nomor Transaksi</td>
                        <td rowspan="2" style="width: 140px" class="align-middle">Keterangan</td>
                        <td colspan="3">Pemasukan</td>
                        <td colspan="3">Pengeluaran</td>
                        <td rowspan="2" style="width: 80px" class="align-middle">Pemakai</td>
                        <td rowspan="2" style="width: 50px" class="align-middle">Waktu</td>
                      </tr>
                      <tr>
                        <td style="width: 40px">Pack</td>
                        <td style="width: 45px">Pcs</td>
                        <td style="width: 90px">Rupiah</td>
                        <td style="width: 40px">Pack</td>
                        <td style="width: 45px">Pcs</td>
                        <td style="width: 90px">Rupiah</td>
                      </tr>
                    </thead>
                    <tbody>
                      @if(($rowBM != 0) || ($rowSO != 0))
                        <tr>
                          <td colspan="5" class="text-bold text-dark text-center">Stok Awal</td>
                          <td class="text-bold text-dark text-right">100</td>
                          <td colspan="7"></td>
                        </tr>
                        @php 
                          $i = 1; $totalBM = 0; $totalSO = 0;
                          $itemsBM = \App\DetilBM::with(['bm', 'barang'])
                                      ->where('id_barang', $item->id)
                                      ->whereHas('bm', function($q) use($awal, $akhir) {
                                          $q->whereBetween('tanggal', [$awal, $akhir]);
                                      })->get();
                          $itemsSO = \App\DetilSO::with(['so', 'barang'])
                                      ->where('id_barang', $item->id)
                                      ->whereHas('so', function($q) use($awal, $akhir) {
                                          $q->whereBetween('tgl_so', [$awal, $akhir]);
                                      })->get();
                        @endphp
                        @foreach($itemsBM as $ib)
                          <tr class="text-bold">
                            <td align="center">{{ $i }}</td>
                            <td align="center">{{ $ib->bm->tanggal }} </td>
                            <td>Barang Masuk</td>
                            <td>{{ $ib->bm->id }}</td>
                            <td>{{ $ib->bm->supplier->nama }}</td>
                            <td align="right">{{ $ib->qty }}</td>
                            <td align="right"></td>
                            <td align="right">
                              {{ number_format($ib->qty * $ib->harga, 0, "", ".") }}
                            </td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            @php $totalBM += $ib->qty @endphp
                          </tr>
                          @php $i++; @endphp
                        @endforeach
                        @foreach($itemsSO as $is)
                          <tr class="text-bold">
                            <td align="center">{{ $i }}</td>
                            <td align="center">{{ $is->so->tgl_so }} </td>
                            <td>Penjualan Barang</td>
                            <td>{{ $is->so->id }}</td>
                            <td>{{ $is->so->customer->nama }}</td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right">{{ $is->qty }}</td>
                            <td align="right"></td>
                            <td align="right">
                              {{ number_format($is->qty * $is->harga, 0, "", ".") }}
                            </td>
                            <td align="right"></td>
                            <td align="right"></td>
                            @php $totalSO += $is->qty @endphp
                          </tr>
                          @php $i++; @endphp
                        @endforeach
                        <tr>
                          <td colspan="5" class="text-bold text-dark text-center">Total</td>
                          <td class="text-bold text-dark text-right">{{ $totalBM }}</td>
                          <td colspan="2"></td>
                          <td class="text-bold text-dark text-right">{{ $totalSO }}</td>
                          <td colspan="4"></td>
                        </tr>
                        <tr style="background-color: yellow">
                          <td colspan="5" class="text-bold text-dark text-center">Stok Akhir</td>
                          <td class="text-bold text-dark text-right">100</td>
                          <td colspan="7"></td>
                        </tr>
                      @else 
                        <tr>
                          <td colspan="12" class="text-center text-bold h4 p-2"><i>Tidak ada transaksi untuk kode dan tanggal tersebut</i></td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                  <hr>
                  <!-- End Tabel Data Detil PO -->

                  </div>
                  @endforeach
                </div>
                @if(($itemsBRG->count() > 0) && ($itemsBRG->count() != 1)) 
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
const kodeAwal = document.getElementById("kodeAwal");
const kodeAkhir = document.getElementById("kodeAkhir");

/** Call Fungsi Setelah Inputan Terisi **/


/** Autocomplete Input Text **/


</script>
@endpush