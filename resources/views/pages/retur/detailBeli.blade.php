@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Retur Pembelian</h1>
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
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold">Nomor Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" class="form-control form-control-sm text-bold" name="kode" id="kodeSO" value="{{ $items[0]->id_bm }}">
                      </div>
                      <div class="col-1 mt-1" style="margin-left: -10px">
                        <button type="submit" formaction="{{ route('ret-detail-jual') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                      </div>
                    </div>  
                  </div>
                  <div class="col" style="margin-left: -580px">
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right">Tanggal Faktur</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark mt-1" name="tglSO"
                        value="{{ \Carbon\Carbon::parse($items[0]->bm->tanggal)->format('d-M-y') }}" >
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaCust" class="col-5 col-form-label text-bold text-right">Nama Supplier</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark mt-1" name="namaCust"
                        value="{{ $items[0]->bm->supplier->nama }}" >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -40px">
                  <label for="nama" class="col-2 col-form-label text-bold">Tanggal Retur</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" class="form-control datepicker form-control-sm text-bold" name="tanggal" value="{{ $tanggal }}">
                  </div>
                </div>
                <input type="hidden" name="jumBaris" value="{{ $items->count() }}">
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold text-dark">
                  <td style="width: 50px">No</td>
                  <td style="width: 80px">Kode Barang</td>
                  <td style="width: 330px">Nama Barang</td>
                  <td style="width: 90px">Qty Faktur</td>
                  <td style="width: 80px">Qty Retur</td>
                </thead>
                <tbody>
                  @php $i = 1; @endphp
                  @forelse($items as $item)
                    <tr class="text-bold text-dark">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td align="center" class="align-middle">{{ $item->id_barang }}</td>
                      <td class="align-middle">{{ $item->barang->nama }}</td>
                      <td align="center" class="align-middle">{{ $item->qty }}</td>
                      <td>
                        <input type="text" name="qty[]" id="qty" class="form-control form-control-sm text-bold text-dark text-center qty" 
                        value="{{ old('qty[]') }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9">
                      </td>
                    </tr>
                    @php $i++; @endphp
                  @empty
                    <tr>
                      <td colspan="5" class="text-center text-bold h4 p-2"><i>Silahkan Input Nomor Faktur</i></td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('ret-process-beli', $items[0]->id_bm) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                </div>
                <div class="col-2">
                  <a href="{{ route('retur-stok') }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                </div>
              </div>
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

const kode = document.getElementById("kodeSO");

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    $(qty[inputan-1]).tooltip('show');
    return false;
  }
  return true;
}

/** Autocomplete Input Kode PO **/
$(function() {
  var kodeSO = [];
  @foreach($bm as $b)
    kodeSO.push('{{ $b->id }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Barang --*/
  $(kode).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kodeSO, extractLast(request.term)));
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

/* Tampil Data tanpa Refresh
$('#btn-cari').click(function(e) {
  e.preventDefault();
  $.ajax({
    url: '/barangmasuk/process',
    type: 'post',
    data: {kode: kode.value},
    dataType: 'json',
    success: function(data) {
      $.each(data, function() {
        $.each(this, function(index, value) {
          supplier.value = value.id;
          console.log(value);
        });
        
      });
    },
  })
})
*/

</script>
@endpush