@php $j = 0; @endphp
@foreach($retur as $r)
  <div class="modal modalGudang" id="Detail{{ $r->id }}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
          </button>
          <h5 class="modal-title text-bold"><i class="fa fa-money-bill-alt fa-fw"></i> Detail Kirim Retur <strong>{{ $r->id }}</strong></h5>
        </div>
        <div class="modal-body text-dark">
          <form action="" method="POST">
            @csrf
            {{-- <div class="form-group row justify-content-center">
              <label for="nama" class="col-2 col-form-label text-bold ">Tanggal Kirim</label>
              <span class="col-form-label text-bold">:</span>
              <div class="col-3 mt-1">
                <input type="text" class="form-control @if($r->detilrj[0]->tgl_kirim == '') datepicker @endif form-control-sm text-bold tglKirim" name="tanggal{{$r->id}}" required
                @if($r->detilrj[0]->tgl_kirim != '') value ="{{ \Carbon\Carbon::parse($r->detilrj[0]->tgl_kirim)->format('d-M-y') }}" readonly @endif>
              </div>
            </div>  --}}
            <input type="hidden" name="kode" value="{{ $r->id }}">
            <table class="table table-responsive table-bordered table-striped table-sm" style="font-size: 16px">
              <thead class="text-center text-bold text-dark">
                <tr class="text-center bg-gradient-danger text-white">
                  <th class="align-middle" style="width: 40px">No</th>
                  <th class="align-middle" style="width: 90px">Kode Barang</th>
                  <th class="align-middle" style="width: 325px">Nama Barang</th>
                  <th class="align-middle" style="width: 70px">Qty Retur</th>
                  <th class="align-middle" style="width: 70px">Qty Bagus</th>
                  <th class="align-middle" style="width: 100px">Tgl. Kirim</th>
                  <th class="align-middle" style="width: 70px">Qty Kirim</th>
                  <th class="align-middle" style="width: 70px">Potong Tagihan</th>
                </tr>
              </thead>
              <tbody class="table-ar">
                @php 
                  $i = 1; $totalRetur = 0; $totalKirim = 0; $totalPotong = 0;
                  $detil = App\Models\DetilRJ::where('id_retur', $r->id)->get();
                @endphp
                @foreach($detil as $dr)
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
                      <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglBayar" name="tgl{{$r->id}}{{$dr->id_barang}}" id="tglBayar{{$dr->id_barang}}" placeholder="DD-MM-YYYY" autocomplete="off" @if($dr->tgl_kirim != '') value ="{{ \Carbon\Carbon::parse($dr->tgl_kirim)->format('d-M-y') }}" readonly @endif>
                    </td>
                    <td class="text-right align-middle">
                      <input type="text" name="kirim{{$r->id}}{{$dr->id_barang}}" id="kirim{{$r->id}}{{$dr->id_barang}}" class="form-control form-control-sm text-bold text-dark text-right kirimModal" onkeypress="return angkaSaja(event)" autocomplete="off"
                      @if($dr->qty_kirim != '') value ="{{ $dr->qty_kirim }}" readonly @endif>
                    </td>
                    <td class="align-middle text-right">{{ $dr->potong }}</td>
                  </tr>
                  @php $i++; $totalRetur += $dr->qty_retur; $totalKirim += $dr->qty_kirim; $totalPotong += $dr->potong; @endphp
                @endforeach
                <tr class="bg-gradient-danger text-bold text-white">
                  <td colspan="3" class="text-center">Total</td>
                  <td class="text-right">{{ number_format($totalRetur, 0, "", ".") }}</td>
                  <td></td>
                  <td></td>
                  <td class="text-right">{{ number_format($totalKirim, 0, "", ".") }}</td>
                  <td class="text-right">{{ number_format($totalPotong, 0, "", ".") }}</td>
                </tr>
              </tbody>
            </table>
            <hr>

            @if($r->status == 'INPUT')
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('retur-jual-process') }}" formmethod="POST">Submit</button>
                </div>
                <div class="col-2">
                  <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-block text-bold">Batal</button>
                </div>
              </div>
            {{-- @elseif($r->status == 'LENGKAP')
              <div class="form-row justify-content-center">
                <div class="col-3">
                  <button type="button" id="btnCetak" class="btn btn-primary btn-block text-bold btnCetak">Cetak</button>
                </div>
              </div>

              <iframe src="{{url('retur/penjualan/cetak/'.$r->id)}}" id="frameCetak{{$j}}" frameborder="0" hidden></iframe>
              @php $j++; @endphp --}}
            @endif            
          </form>
        </div>
      </div>
    </div>
  </div>
@endforeach

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

const tglKirim = document.querySelectorAll(".tglKirim");
const tglBayar = document.querySelectorAll('.tglBayar');
const kirimModal = document.querySelectorAll('.kirimModal');
const batalModal = document.querySelectorAll('.batalModal');
const kurang = document.querySelectorAll('.kurang');
const kurangAwal = document.querySelectorAll('.kurangAwal');
const btnCetak = document.querySelectorAll('.btnCetak');
// const frameCetak = document.querySelectorAll('.frameCetak');

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

// function cetakRetur(e) {
//   const printFrame = document.getElementById("frameCetak"+).contentWindow;

//   printFrame.window.onafterprint = function(e) {
//     alert('ok');
//   }

//   printFrame.window.print();
//   window.print();
// }

</script>