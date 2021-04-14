@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

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
                  <label for="kode" class="col-2 col-form-label text-right text-bold">Dari Kode Barang</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="kodeAwal" id="kodeAwal" value="{{ $itemsBRG[0]->id }}" required autofocus>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s / d</label>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="kodeAkhir" id="kodeAkhir" value="{{ $itemsBRG[$itemsBRG->count() - 1]->id }}" required>
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-right text-bold">Dari Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" value="{{ \Carbon\Carbon::parse($awal)->format('d-m-Y') }}" autocomplete="off" required>
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s / d</label>
                  <div class="col-2">
                    <input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAkhir" id="tglAkhir" value="{{ \Carbon\Carbon::parse($akhir)->format('d-m-Y') }}" autocomplete="off" required>
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('ks-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
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
                  <h5 class="text-bold text-dark">Tanggal : {{ \Carbon\Carbon::parse($awal)->format('d-M-y') }} s/d {{ \Carbon\Carbon::parse($akhir)->format('d-M-y') }}</h5>
                </div>
                <div class="row justify-content-end" style="margin-top: -55px">
                  <div class="col-2">
                    <button type="submit" formaction="{{ route('ks-excel') }}" formmethod="POST"  class="btn btn-success btn-block text-bold">Download Excel</>
                  </div>
                </div>
              </div>
              <br>

              <div id="so-carousel" class="carousel slide" data-interval="false" wrap="false">
                <div class="carousel-inner">
                  @php $j=0; @endphp
                  @foreach($itemsBRG as $item)
                  <div class="carousel-item 
                    @if($item->id == $itemsBRG[0]->id) active
                    @endif "
                  />
                  <div class="container so-update-container">
                    <div class="form-group row">
                      <label for="kode" class="col-auto form-control-sm text-bold text-dark mt-1">Kode Barang</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark"
                        value="{{ $item->id }} - {{ $item->nama }}" >
                      </div>
                    </div>
                  </div>

                  <!-- Tabel Data Detil PO -->
                  <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover table-kartu-stok">
                    <thead class="text-center text-bold text-dark" style="background-color: yellow">
                      <tr>
                        <td rowspan="3" style="width: 30px" class="align-middle">No</td>
                        <td rowspan="3" style="width: 80px" class="align-middle">Tanggal</td>
                        <td rowspan="3" style="width: 60px"class="align-middle">Tipe Transaksi</td>
                        <td rowspan="3" style="width: 60px"class="align-middle">Nomor Transaksi</td>
                        <td rowspan="3" style="width: 120px" class="align-middle">Keterangan</td>
                        <td colspan="4" class="align-middle">Pemasukan</td>
                        <td colspan="{{ $gudang->count() + 2 }}">Pengeluaran</td>
                        <td rowspan="3" style="width: 65px" class="align-middle">Pemakai</td>
                      </tr>
                      <tr>
                        <td rowspan="2" style="width: 40px" class="align-middle">
                          @if($item->satuan == "Pcs / Dus") Pcs @elseif($item->satuan == "Set") Set @elseif($item->satuan == "Meter / Rol") Rol @else Meter @endif
                        </td>
                        <td rowspan="2" style="width: 40px" class="align-middle">
                          @if($item->satuan == "Pcs / Dus") Pcs @elseif($item->satuan == "Set") Set @elseif($item->satuan == "Meter / Rol") Rol @else Meter @endif TB
                        </td>
                        <td rowspan="2" style="width: 40px" class="align-middle">Gudang</td>
                        {{-- <td style="width: 45px">
                          @if($item->satuan == "Pcs / Dus") Dus @else Rol @endif
                        </td> --}}
                        <td rowspan="2" style="width: 70px" class="align-middle">Rupiah</td>
                        <td rowspan="2" style="width: 40px" class="align-middle">
                          @if($item->satuan == "Pcs / Dus") Pcs @elseif($item->satuan == "Set") Set @elseif($item->satuan == "Meter / Rol") Rol @else Meter @endif
                        </td>
                        <td colspan="{{ $gudang->count() }}">Dari Gudang</td>
                        <td rowspan="2" style="width: 70px" class="align-middle">Rupiah</td>
                      </tr>
                      <tr>
                        @foreach($gudang as $g)
                          <td style="width: 40px">{{ substr($g->nama, 0, 3) }}</td>
                        @endforeach
                        {{-- <td style="width: 45px">
                          @if($item->satuan == "Pcs / Dus") Dus @else Rol @endif
                        </td> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @php 
                        $itemsBM = \App\Models\DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                                    ->select('id', 'id_bm', 'id_barang', 'tanggal', 'barangmasuk.created_at', 'detilbm.diskon as id_asal', 'disPersen as id_tujuan', 'qty')
                                    ->where('id_barang', $item->id)
                                    ->whereHas('bm', function($q) use($awal, $akhir) {
                                        $q->whereBetween('tanggal', [$awal, $akhir])
                                        ->where('status', '!=', 'BATAL');
                                    });
                        $itemsSO = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                                    ->select('id', 'id_so', 'id_barang', 'tgl_so as tanggal', 'so.created_at', 'detilso.diskon as id_asal', 'diskonRp as id_tujuan')->selectRaw('sum(qty) as qty')
                                    ->where('id_barang', $item->id)
                                    ->whereHas('so', function($q) use($awal, $akhir) {
                                        $q->whereBetween('tgl_so', [$awal, $akhir])
                                        ->where('status', '!=', 'BATAL');
                                    })->groupBy('id_so', 'id_barang');
                        $itemsRJ = \App\Models\DetilRJ::join('returjual', 'returjual.id', 'detilrj.id_retur')
                                    ->select('id', 'id_retur', 'id_barang', 'tanggal', 'returjual.created_at', 'tgl_kirim as id_asal', 'id_kirim as id_tujuan', 'qty_retur as qty')
                                    ->where('id_barang', $item->id)
                                    ->whereHas('retur', function($q) use($awal, $akhir) {
                                        $q->whereBetween('tanggal', [$awal, $akhir])
                                        ->where('status', '!=', 'BATAL');
                                    });
                        $itemsKRJ = \App\Models\DetilRJ::join('returjual', 'returjual.id', 'detilrj.id_retur')
                                    ->select('id_kirim as id', 'id_retur', 'id_barang', 'tgl_kirim as tanggal', 'returjual.created_at', 'tanggal as id_asal', 'potong as id_tujuan', 'qty_kirim as qty')->where('id_barang', $item->id)->where('qty_kirim', '!=', 0)
                                    ->whereHas('retur', function($q) use($awal, $akhir) {
                                        $q->whereBetween('tanggal', [$awal, $akhir])
                                        ->where('status', '!=', 'BATAL');
                                    });
                        $itemsRB = \App\Models\DetilRB::join('returbeli', 'returbeli.id', 'detilrb.id_retur')
                                    ->select('id', 'id_retur', 'id_barang', 'tanggal', 'returbeli.created_at', 'detilrb.created_at as id_asal', 'returbeli.updated_at as id_tujuan', 'qty_retur as qty')
                                    ->where('id_barang', $item->id)
                                    ->whereHas('returbeli', function($q) use($awal, $akhir) {
                                        $q->whereBetween('tanggal', [$awal, $akhir])
                                        ->where('status', '!=', 'BATAL');
                                    });
                        $itemsTRB = \App\Models\DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                                    ->select('id_terima as id', 'id_retur', 'id_barang', 'tanggal', 'returterima.created_at', 'qty_batal as id_asal', 'potong as id_tujuan', 'qty_terima as qty')->where('id_barang', $item->id)->where('qty_terima', '!=', 0)
                                    ->whereHas('returterima', function($q) use($awal, $akhir) {
                                        $q->whereBetween('tanggal', [$awal, $akhir]);
                                    });
                        $items = \App\Models\DetilTB::join('transferbarang', 'transferbarang.id', 'detiltb.id_tb')
                                    ->select('id', 'id_tb', 'id_barang', 'tgl_tb as tanggal', 'transferbarang.created_at', 'id_asal', 'id_tujuan', 'qty')->where('id_barang', $item->id)
                                    ->whereHas('tb', function($q) use($awal, $akhir) {
                                        $q->whereBetween('tgl_tb', [$awal, $akhir]);
                                    })->union($itemsBM)->union($itemsSO)->union($itemsRJ)->union($itemsKRJ)
                                    ->union($itemsRB)->union($itemsTRB)->orderBy('created_at')->get();

                        $tambahGd = \App\Models\DetilSO::join('so', 'so.id', 'detilso.id_so')
                                    ->selectRaw('sum(qty) as qty')->where('id_barang', $item->id)
                                    ->where('tgl_so', '>', $akhir)->whereNotIn('status', ['BATAL', 'LIMIT'])->get();
                        $kurangGd = \App\Models\DetilBM::join('barangmasuk', 'barangmasuk.id', 'detilbm.id_bm')
                                    ->selectRaw('sum(qty) as qty')->where('id_barang', $item->id)
                                    ->where('tanggal', '>', $akhir)->where('status', '!=', 'BATAL')->get();
                        $kurangRJ = \App\Models\DetilRJ::join('returjual', 'returjual.id', 'detilrj.id_retur')
                                    ->selectRaw('sum(qty_retur - qty_kirim) as qty')
                                    ->where('status', 'INPUT')->where('id_barang', $item->id)
                                    ->where('tanggal', '>', $akhir)->get();
                        $detilRT = \App\Models\DetilRT::join('returterima', 'returterima.id', 'detilrt.id_terima')
                                    ->join('returbeli', 'returbeli.id', 'returterima.id_retur')
                                    ->selectRaw('sum(qty_terima + qty_batal) as qty')
                                    ->where('status', 'INPUT')->where('id_barang', $item->id)
                                    ->where('returbeli.tanggal', '>', $akhir)->get();
                        $detilRB = \App\Models\DetilRB::join('returbeli', 'returbeli.id', 'detilrb.id_retur')
                                    ->selectRaw('sum(qty_retur) as retur')
                                    ->where('status', 'INPUT')->where('id_barang', $item->id)
                                    ->where('tanggal', '>', $akhir)->get();
                        $tambahRB = $detilRB->first()->retur - $detilRT->first()->qty;
                      
                      @endphp
                      @if($items->count() != 0)
                        <tr>
                          <td colspan="5" class="text-bold text-dark text-center">Stok Awal</td>
                          <td class="text-bold text-dark text-right">{{ $stokAwal[$j] }}</td>
                          <td colspan="{{ $gudang->count() + 6 }}"></td>
                        </tr>
                        @php 
                          $i = 1; $totalBM = 0; $totalSO = 0;        
                        @endphp
                        @foreach($items as $it)
                          <tr class="text-dark">
                            <td align="center" class="align-middle">{{ $i }}</td>
                            <td align="center" class="align-middle">{{ \Carbon\Carbon::parse($it->tanggal)->format('d-M-y') }}</td>
                            <td class="align-middle">
                              @if(substr($it->id, 0, 2) == 'BM')Barang Masuk @elseif((substr($it->id, 0, 2) == 'TB'))Transfer @elseif(((substr($it->id, 0, 3) == 'RTJ') || (substr($it->id, 0, 3) == 'RTT')))Retur Customer @elseif((substr($it->id, 0, 3) == 'KRM'))Kirim Retur @elseif(substr($it->id, 0, 3) == 'RTB') Retur Supplier @elseif(substr($it->id, 0, 3) == 'TRM') Terima Barang Retur @else Penjualan @endif
                            </td>
                            <td class="align-middle">{{ $it->id }}</td>
                            @php
                              $nama = ''; $namaGud = ''; $total = ''; $user = '';
                              if(substr($it->id, 0, 2) == 'BM') {
                                $namaBM = \App\Models\BarangMasuk::where('id', $it->id)->get();
                                $nama = ($namaBM->count() != 0 ? $namaBM->first()->supplier->nama : '0');
                                $namaGud = ($namaBM->count() != 0 ? $namaBM->first()->gudang->nama : '0');
                                $total = ($namaBM->count() != 0 ? $namaBM->first()->total : '0');
                                $user = ($namaBM->count() != 0 ? $namaBM->first()->user->name: '0');
                              }
                              elseif((substr($it->id, 0, 2) == 'TB')) {
                                $namaTB = \App\Models\DetilTB::where('id_tb', $it->id)
                                          ->where('id_barang', $item->id)->get();
                                $nama = ($namaTB->count() != 0 ? $namaTB->first()->gudangAsal->nama : '0');
                                $namaGud = ($namaTB->count() != 0 ? $namaTB->first()->gudangTuju->nama: '0');
                                $user = ($namaTB->count() != 0 ? $namaTB->first()->tb->user->name: '0');
                              } 
                              elseif(((substr($it->id, 0, 3) == 'RTJ') || (substr($it->id, 0, 3) == 'RTT'))) {
                                $namaRJ = \App\Models\ReturJual::where('id', $it->id)->get();
                                $nama = ($namaRJ->count() != 0 ? $namaRJ->first()->customer->nama : '0');
                                $namaGud = 'Retur Jelek';
                                $user = '';
                              }
                              elseif((substr($it->id, 0, 3) == 'KRM')) {
                                $namaRJ = \App\Models\ReturJual::where('id', $it->id_tb)->get();
                                $nama = ($namaRJ->count() != 0 ? $namaRJ->first()->customer->nama : '0');
                                $total = 0;
                                $user = '';
                              }
                              elseif(substr($it->id, 0, 3) == 'RTB') {
                                $namaRJ = \App\Models\ReturBeli::where('id', $it->id)->get();
                                $nama = ($namaRJ->count() != 0 ? $namaRJ->first()->supplier->nama : '0');
                                $total = 0;
                                $user = '';
                              }
                              elseif(substr($it->id, 0, 3) == 'TRM') {
                                $namaRJ = \App\Models\ReturBeli::where('id', $it->id_tb)->get();
                                $nama = ($namaRJ->count() != 0 ? $namaRJ->first()->supplier->nama : '0');
                                $namaGud = 'Retur Bagus';
                                $user = '';
                              }
                              else {
                                $namaSO = \App\Models\SalesOrder::where('id', $it->id)->get();
                                $nama = ($namaSO->count() != 0 ? $namaSO->first()->customer->nama : '0');
                                $total = ($namaSO->count() != 0 ? $namaSO->first()->total : '0');
                                $user = ($namaSO->count() != 0 ? $namaSO->first()->user->name: '0');
                              }
                            @endphp
                            <td class="align-middle">{{ $nama }}</td>
                            <td class="align-middle" align="right">
                              {{ ((substr($it->id, 0, 2) == 'BM') || (substr($it->id, 0, 3) == 'RTJ') || (substr($it->id, 0, 3) == 'RTT') || (substr($it->id, 0, 3) == 'TRM')) ? $it->qty : '' }}
                            </td>
                            <td class="align-middle" align="right">
                              {{ substr($it->id, 0, 2) == 'TB' ? $it->qty : '' }}
                            </td>
                            <td class="align-middle">{{ $namaGud }}</td>
                            <td class="align-middle" align="right">
                              {{ substr($it->id, 0, 2) == 'BM' ? number_format($total, 0, "", ".") : '' }}
                            </td>
                            <td class="align-middle" align="right">
                              {{ ((substr($it->id, 0, 2) != 'BM') && (substr($it->id, 0, 2) != 'TB') && (substr($it->id, 0, 3) != 'RTJ') && (substr($it->id, 0, 3) != 'RTT') && (substr($it->id, 0, 3) != 'TRM')) ? $it->qty : '' }}
                            </td>
                            @foreach($gudang as $g)
                              @php
                                if(($g->tipe == 'RETUR') && (substr($it->id, 0, 3) == 'KRM')) {
                                  $itemGud = \App\Models\DetilRJ::select('qty_kirim as qty')
                                          ->where('id_retur', $it->id_tb)
                                          ->where('id_barang', $it->id_barang)->get();
                                } elseif(($g->tipe == 'RETUR') && (substr($it->id, 0, 3) == 'RTB')) {
                                  $itemGud = \App\Models\DetilRB::select('qty_retur as qty')
                                          ->where('id_retur', $it->id_tb)
                                          ->where('id_barang', $it->id_barang)->get();
                                } else {
                                  $itemGud = \App\Models\DetilSO::where('id_so', $it->id)
                                            ->where('id_barang', $it->id_barang)
                                            ->where('id_gudang', $g->id)->get();
                                }
                              @endphp
                              @if($itemGud->count() != 0)
                                <td class="align-middle" align="right">{{ $itemGud->first()->qty }}
                                </td>
                              @else
                                <td></td>
                              @endif
                            @endforeach
                            <td class="align-middle" align="right">
                              {{ ((substr($it->id, 0, 2) != 'BM') && (substr($it->id, 0, 2) != 'TB') && (substr($it->id, 0, 3) != 'RTJ') && (substr($it->id, 0, 3) != 'RTT') && (substr($it->id, 0, 3) != 'TRM')) ? number_format($total, 0, "", ".") : '' }}
                            </td>
                            <td class="align-middle" align="center">
                              {{ $user }} <br> {{ \Carbon\Carbon::parse($it->created_at)->format('H:i:s') }}
                            </td>
                            @php
                              if((substr($it->id, 0, 2) == 'BM') || (substr($it->id, 0, 3) == 'RTJ') || (substr($it->id, 0, 3) == 'RTT') || (substr($it->id, 0, 3) == 'TRM')) 
                                $totalBM += $it->qty; 
                              elseif((substr($it->id, 0, 2) != 'BM') && (substr($it->id, 0, 2) != 'TB') && (substr($it->id, 0, 3) != 'RTJ') && (substr($it->id, 0, 3) != 'RTT') && (substr($it->id, 0, 3) != 'TRM'))
                                $totalSO += $it->qty;
                            @endphp
                          </tr>
                          @php $i++; @endphp
                        @endforeach
                        <tr>
                          <td colspan="5" class="text-bold text-dark text-center">Total</td>
                          <td class="text-bold text-dark text-right">
                            {{ $stokAwal[$j] + $totalBM }}
                          </td>
                          <td colspan="3"></td>
                          <td class="text-bold text-dark text-right">{{ $totalSO }}</td>
                          <td colspan="{{ $gudang->count() + 2 }}"></td>
                        </tr>
                        <tr style="background-color: yellow">
                          <td colspan="5" class="text-bold text-dark text-center">Stok Akhir</td>
                          <td class="text-bold text-dark text-right">{{ $stok[$j]->total + $tambahGd->first()->qty - $kurangGd->first()->qty - $kurangRJ->first()->qty + $tambahRB }}</td>
                          <td colspan="{{ $gudang->count() + 6 }}"></td>
                        </tr>
                      @else 
                        <tr>
                          <td colspan="{{ $gudang->count() + 12 }}" class="text-center text-bold h4 p-2"><i>Tidak ada transaksi untuk kode dan tanggal tersebut</i></td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                  <hr>
                  <!-- End Tabel Data Detil PO -->

                  </div>
                  @php $j++; @endphp
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
<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">
$.fn.datepicker.dates['id'] = {
  days:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],
  daysShort:["Mgu","Sen","Sel","Rab","Kam","Jum","Sab"],
  daysMin:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
  months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
  monthsShort:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"],
  today:"Hari Ini",
  clear:"Kosongkan"
};

$('.datepicker').datepicker({
  format: 'dd-mm-yyyy',
  autoclose: true,
  todayHighlight: true,
  language: 'id',
});

const kodeAwal = document.getElementById("kodeAwal");
const kodeAkhir = document.getElementById("kodeAkhir");
const tglAwal = document.getElementById('tglAwal');
const tglAkhir = document.getElementById('tglAkhir');

/** Call Fungsi Setelah Inputan Terisi **/
tglAwal.addEventListener("keyup", formatTanggal);
tglAkhir.addEventListener("keyup", formatTanggal);

function formatTanggal(e) {
  var value = e.target.value.replaceAll("-","");
  var arrValue = value.split("", 3);
  var kode = arrValue.join("");

  if(value.length > 2 && value.length <= 4) 
    value = value.slice(0,2) + "-" + value.slice(2);
  else if(value.length > 4 && value.length <= 8)
    value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
  
  if(e.target.id == 'tglAwal')
    tglAwal.value = value;
  else
    tglAkhir.value = value;
}

$(function() {
  $("[autofocus]").on("focus", function() {
    if (this.setSelectionRange) {
      var len = this.value.length * 2;
      this.setSelectionRange(len, len);
    } else {
      this.value = this.value;
    }
    this.scrollTop = 999999;
  }).focus();
});

/** Autocomplete Input Text **/
$(function() {
  var barang = [];
  @foreach($barang as $b)
    barang.push('{{ $b->id }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Kode Barang --*/
  $(kodeAwal).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(barang, extractLast(request.term)));
    },
    focus: function() {
      // prevent value inserted on focus
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      // remove the current input
      terms.pop();
      // add the selected item
      terms.push(ui.item.value);
      // add placeholder to get the comma-and-space at the end
      terms.push("");
      this.value = terms.join("");
      return false;
    }
  });

  $(kodeAkhir).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(barang, extractLast(request.term)));
    },
    focus: function() {
      // prevent value inserted on focus
      return false;
    },
    select: function(event, ui) {
      var terms = split(this.value);
      // remove the current input
      terms.pop();
      // add the selected item
      terms.push(ui.item.value);
      // add placeholder to get the comma-and-space at the end
      terms.push("");
      this.value = terms.join("");
      return false;
    }
  });
});

</script>
@endpush