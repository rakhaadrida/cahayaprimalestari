@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Detail Kirim Retur {{ $item->first()->id }}</h1>
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
            <form action="" method="" id="kirimRJ">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier BM -->
               <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor Retur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="1" readonly class="form-control form-control-sm text-bold text-dark" name="kode" value="{{ $item->first()->id }}" >
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal Retur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" tabindex="2" readonly class="form-control datepicker form-control-sm text-bold text-dark" name="tanggal" value="{{ \Carbon\Carbon::parse($item->first()->tanggal)->format('d-M-y') }}">
                      </div>
                    </div>   
                  </div>
                </div>
                <div class="form-group row subtotal-so">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4 mt-1">
                    <input type="text" tabindex="5" name="namaCust" readonly class="form-control form-control-sm text-bold text-dark" value="{{ $item->first()->customer->nama }}" />
                    <input type="hidden" name="kodeCustomer" value="{{ $item->first()->id_customer }}">
                  </div>
                  <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $retur->count() }}">
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
                    <th class="align-middle" style="width: 40px">No</th>
                    <th class="align-middle" style="width: 90px">Kode Barang</th>
                    <th class="align-middle" style="width: 325px">Nama Barang</th>
                    <th class="align-middle" style="width: 65px">Qty Retur</th>
                    <th class="align-middle" style="width: 65px">Qty Bagus</th>
                    <th class="align-middle" style="width: 100px">Tgl. Kirim</th>
                    <th class="align-middle" style="width: 65px">Qty Kirim</th>
                    <th class="align-middle" style="width: 70px">Potong Tagihan</th>
                  </tr>
                </thead>
                <tbody id="tablePO" class="table-ar">
                  @php 
                    $i = 1; $totalRetur = 0; $totalKirim = 0; $totalPotong = 0;
                  @endphp
                  @foreach($retur as $dr)
                    @php $stok = App\Models\StokBarang::where('id_barang', $dr->id_barang)
                                ->where('id_gudang', $gudang[0]->id)->where('status', 'T')->get();
                    @endphp
                    <tr class="text-dark text-bold">
                      <td class="text-center align-middle">{{ $i }}</td>
                      <td class="text-center align-middle">{{ $dr->id_barang }}</td>
                      <td class="align-middle">{{ $dr->barang->nama }}</td>
                      <td class="align-middle text-right">{{ $dr->qty_retur }}</td>
                      <td class="align-middle text-right">{{ $stok->count() != 0 ? $stok[0]->stok : '0' }}</td>
                      <td class="text-center align-middle">
                        <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl[]" id="tglBayar{{$dr->id_barang}}" placeholder="DD-MM-YYYY" autocomplete="off" @if($dr->tgl_kirim != '') value ="{{ \Carbon\Carbon::parse($dr->tgl_kirim)->format('d-M-y') }}" readonly @endif>
                      </td>
                      <td class="text-right align-middle">
                        <input type="text" name="kirim[]" id="kirim{{$item->first()->id}}{{$dr->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right kirimModal" onkeypress="return angkaSaja(event)" autocomplete="off"
                        @if($dr->qty_kirim != '') value ="{{ $dr->qty_kirim }}" readonly @endif>
                      </td>
                      <td class="align-middle text-right">{{ $dr->potong }}</td>
                    </tr>
                    @php $i++; $totalRetur += $dr->qty_retur; $totalKirim += $dr->qty_kirim; $totalPotong += $dr->potong; @endphp
                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="text-right text-bold text-dark" style="font-size: 16px">
                    <td colspan="3" class="text-center">Total</td>
                    <td class="text-right">{{ number_format($totalRetur, 0, "", ".") }}</td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ number_format($totalKirim, 0, "", ".") }}</td>
                    <td class="text-right">{{ number_format($totalPotong, 0, "", ".") }}</td>
                  </tr>
                </tfoot>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              @if($item->first()->status == 'INPUT')
                <div class="form-row justify-content-center">
                  <div class="col-2">
                    <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('retur-jual-process') }}" formmethod="POST">Submit</button>
                  </div>
                  <div class="col-2">
                    <button type="submit" class="btn btn-outline-danger btn-block text-bold" formaction="{{ route('retur-jual-batal', $item->first()->id) }}" formmethod="POST">Batal Retur</button>
                  </div>
                  <div class="col-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                  </div>
                </div>
              @else
                <div class="form-row justify-content-center">
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

const kirimRJ = document.getElementById("kirimRJ");
const tglKirim = document.querySelectorAll(".tglKirim");
const tglBayar = document.querySelectorAll('.tglBayar');
const kirimModal = document.querySelectorAll('.kirimModal');
const batalModal = document.querySelectorAll('.batalModal');
const kurang = document.querySelectorAll('.kurang');
const kurangAwal = document.querySelectorAll('.kurangAwal');
const btnCetak = document.querySelectorAll('.btnCetak');
// const frameCetak = document.querySelectorAll('.frameCetak');

kirimRJ.addEventListener("keypress", checkEnter);

function checkEnter(e) {
  var key = e.charCode || e.keyCode || 0;     
  if (key == 13) {
    alert("Silahkan Klik Tombol Submit");
    e.preventDefault();
  }
}

for(let i = 0; i < tglKirim.length; i++) {
  tglKirim[i].addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4) 
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
    
    tglKirim[i].value = value;
  });
}

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

for(let i = 0; i < kirimModal.length; i++) {
  kirimModal[i].addEventListener("change", function(e) {
    if(kurang[i].value == '')
      kurang[i].value = kurangAwal[i].value - e.target.value;
    else
      kurang[i].value -= e.target.value;
  });
}

for(let i = 0; i < batalModal.length; i++) {
  batalModal[i].addEventListener("change", function(e) {
    if(kurang[i].value == '')
      kurang[i].value = kurangAwal[i].value - e.target.value;
    else
      kurang[i].value -= e.target.value;
  });
}

for(let i = 0; i < btnCetak.length; i++) {
  btnCetak[i].addEventListener("click", function(e) {
    const printFrame = document.getElementById("frameCetak"+i).contentWindow;
    const printTTR = document.getElementById("frameTTR"+i).contentWindow;


    printFrame.window.onafterprint = function(e) {
      alert('ok');
    }

    printFrame.window.print();
    printTTR.window.print();
    // window.print();
  });
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}

</script>
@endpush