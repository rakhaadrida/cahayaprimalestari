@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Progress Transfer Tagihan Faktur {{ $item->first()->id_bm }}</h1>
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
              <!-- Inputan Data Id, Tanggal, Supplier BM -->
               <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="1" readonly class="form-control form-control-sm text-bold text-dark" name="kodeSO" value="{{ $item->first()->id_bm }}" >
                        <input type="hidden" name="kode" value="{{ $item->first()->id_bm }}">
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="2" readonly class="form-control datepicker form-control-sm text-bold text-dark" name="tanggal" value="{{ $item->first()->bm->first()->tanggal }}">
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -360px">
                    <div class="form-group row subtotal-po">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Total Tagihan</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-right text-dark" name="subtotal" id="subtotal" value={{ number_format($totalBM->first()->totBM, 0, "", ".") }}>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" tabindex="5" name="namaCust" readonly class="form-control form-control-sm text-bold text-dark" value="{{ $item->first()->bm->first()->supplier->nama }}" />
                  </div>
                  <input type="hidden" name="jumBaris" id="jumBaris" value="3">
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier BM -->

              <!-- Tabel Data Detil BM-->
              {{-- @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-primary text-bold">
                  Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
                </span>
              @endif --}}
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <tr class="text-center">
                    <th style="width: 60px">No</th>
                    <th style="width: 160px">Tgl. Bayar</th>
                    <th style="width: 160px">Jumlah Cicil</th>
                    <th style="width: 160px">Kurang Bayar</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php $i = 1; $total = 0; $kurang = $totalBM->first()->totBM - $retur->first()->total - $potBM->first()->potongan; @endphp
                  @foreach($detilap as $d)
                    @if($d->transfer != 0)
                      <tr class="table-modal-first-row text-dark">
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($d->tgl_bayar)->format('d-M-y') }}</td>
                        <td class="text-right">{{ number_format($d->transfer, 0, "", ".") }}</td>
                        @php $kurang -= $d->transfer; @endphp
                        <td class="text-right">{{ number_format($kurang, 0, "", ".") }}</td>
                      </tr>
                      @php $i++; $total += $d->transfer; @endphp
                    @endif
                  @endforeach
                  @if($item->first()->keterangan == 'BELUM LUNAS')
                    <input type="hidden" name="kurangAwal" class="kurangAwal" value="{{ $kurang }}">
                    <tr class="text-dark">
                      <td class="text-center align-middle">{{ $i }}</td>
                      <td class="text-center align-middle">
                        <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl{{$item->first()->id_bm}}" id="tglBayar{{$item->first()->id_bm}}" placeholder="DD-MM-YYYY" autocomplete="off">
                      </td>
                      <td class="text-right align-middle">
                        <input type="text" name="bayar{{$item->first()->id_bm}}" id="bayar{{$item->first()->id_bm}}" class="form-control form-control-sm text-bold text-dark text-right bayarModal" autocomplete="off">
                      </td>
                      <td class="text-right align-middle">
                        <input type="text" name="kurang{{$item->first()->id_bm}}" id="kurang{{$item->first()->id_bm}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right kurang">
                      </td>
                    </tr>
                  @endif
                  <tr style="font-size: 16px !important">
                    <td colspan="2" class="text-center text-bold text-dark" >Total</td>
                    <td class="text-right text-bold text-dark">{{ number_format($total, 0, "", ".") }}</td>
                    <td class="text-right text-bold text-dark">{{ number_format($kurang, 0, "", ".") }}</td>
                  </tr>
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              @if(($item->first()->keterangan == 'BELUM LUNAS') && (Auth::user()->roles != 'OFFICE02'))
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ap-transfer') }}" formmethod="POST">Submit</button>
                  </div>
                  <div class="col-2">
                    <button type="reset" data-dismiss="modal" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                  </div>
                  <div class="col-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                  </div>
                </div>
              @endif
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

const tglBayar = document.querySelectorAll('.tglBayar');
const bayarModal = document.querySelectorAll('.bayarModal');
const kurang = document.querySelectorAll('.kurang');
const kurangAwal = document.querySelectorAll('.kurangAwal');

for(let i = 0; i < tglBayar.length; i++) {
  tglBayar[i].addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4) 
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
    
    tglBayar[i].value = value;
  });
}

for(let i = 0; i < bayarModal.length; i++) {
  bayarModal[i].addEventListener("keyup", function(e) {
    $(this).val(function(index, value) {
      return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    });
  });

  bayarModal[i].addEventListener("blur", function(e) {
    kurang[i].value = addCommas(kurangAwal[i].value.replace(/\./g, "") - e.target.value.replace(/\,/g, ""));
  });
}

/** Add Thousand Separators **/
function addCommas(nStr) {
	nStr += '';
	x = nStr.split(',');
	x1 = x[0];
	x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
}

</script>
@endpush