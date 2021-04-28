@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Laporan Keuangan Data HPP</h1>
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

  <div class="row" style="overflow-x:auto;">
    <div class="card-body">
      <div class="table-responsive">
        <div class="card show">
          <div class="card-body">
            <form action="" method="">
              @csrf  
              <div class="container">
                <div class="row justify-content-center">
                  <h4 class="text-bold text-dark">Detail HPP Kategori {{$jenis->first()->nama}} dengan Sales {{$sal->first()->nama}}</h4>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h5 class="text-bold text-dark">Bulan : {{$bulan}}</h5>
                </div>
              </div>

              <!-- Tabel Data Detil PO -->
              <table class="table table-responsive table-bordered table-striped table-sm" style="font-size: 14px">
                <thead class="text-center text-bold text-dark">
                  <tr class="text-center bg-gradient-danger text-white">
                    <th class="align-middle" style="width: 60px">No</th>
                    <th class="align-middle" style="width: 340px">Nama Barang</th>
                    <th class="align-middle" style="width: 65px">Qty SO</th>
                    <th class="align-middle" style="width: 95px">Harga</th>
                    <th class="align-middle" style="width: 115px">Total</th>
                    <th colspan="2" class="align-middle" style="width: 120px">Diskon</th>
                    <th class="align-middle" style="width: 115px">Nominal Diskon</th>
                    <th class="align-middle" style="width: 120px">HPP</th>
                  </tr>
                </thead>
                <tbody>
                  @php 
                    $j = 1; $totalQty = 0; $totalHpp = 0; $subtotal = 0; $totalDiskon = 0;
                  @endphp
                  @if(Auth::user()->roles == 'SUPER')
                    @foreach($hppPerKat as $h)
                      @if($h->get('id_sales') == $sales->first()->id)
                        <tr class="table-modal-first-row text-dark text-bold">
                          <td class="text-center">{{ $j }}</td>
                          <td>{{ $h->get('nama') }}</td>
                          <td class="text-right">{{ $h->get('qty') }}</td>
                          <td class="text-right">{{ number_format($h->get('harga'), 0, "", ".") }}</td>
                          @php 
                            $total = $h->get('qty') * $h->get('harga');
                            $diskon = $total * $h->get('disPersen') / 100;
                          @endphp
                          <td class="text-right">{{ number_format($total, 0, "",".") }}</td>
                          <td class="text-right" style="width: 100px" class="text-center">{{ $h->get('diskon') }}</td>
                          <td style="width: 95px" class="text-right">{{ number_format($h->get('disPersen'), 2, ",", "") }} %</td>
                          <td class="text-right">{{ number_format($diskon, 0, "", ".") }}</td>
                          @php $totalQty += $h->get('qty'); @endphp
                          <td class="text-right">{{ number_format($h->get('hpp'), 0, "", ".") }}</td>
                        </tr>
                        @php $j++; $totalHpp += $h->get('hpp'); $subtotal += $total; 
                          $totalDiskon += $diskon; @endphp
                      @endif
                    @endforeach
                  @endif
                  <tr class="bg-gradient-danger text-white">
                    <td colspan="2" class="text-center text-bold">Total</td>
                    <td class="text-right text-bold">{{ number_format($totalQty, 0, "", ".") }}</td>
                    <td></td>
                    <td class="text-right text-bold">{{ number_format($subtotal, 0, "", ".") }}</td>
                    <td colspan="2"></td>
                    <td class="text-right text-bold">{{ number_format($totalDiskon, 0, "", ".") }}</td>
                    <td class="text-right text-bold">{{ number_format($totalHpp, 0, "", ".") }}</td>
                  </tr>
                </tbody>
              </table>
              <!-- End Tabel Data Detil PO -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.container-fluid -->
@endsection
