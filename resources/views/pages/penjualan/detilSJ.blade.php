@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Surat Jalan</h1>
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
                      <label for="kode" class="col-2 col-form-label text-bold text-dark">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" class="form-control form-control-sm text-bold" name="kode" id="kodeSO" value="{{ $items[0]->id_so }}">
                      </div>
                      <div class="col-1 mt-1" style="margin-left: -10px">
                        <button type="submit" formaction="{{ route('sj-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                      </div>
                    </div>  
                  </div>
                  <div class="col" style="margin-left: -380px">
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right text-dark">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="tglSO" 
                        value="{{ $items[0]->so->tgl_so }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaCust" class="col-5 col-form-label text-bold text-right text-dark">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaCust"
                        value="{{ $items[0]->so->customer->nama }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSales" class="col-5 col-form-label text-bold text-right text-dark">Nama Sales</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="namaSales"
                        value="{{ $items[0]->so->customer->sales->nama }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row sj-left-input">
                  <label for="nama" class="col-2 col-form-label text-bold text-dark">Tanggal SJ</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" class="form-control form-control-sm text-bold" name="tanggal" value="{{ $tanggal }}">
                  </div>
                </div>
                <div class="form-group row input-header">
                  <label for="alamat" class="col-2 col-form-label text-bold text-dark">Keterangan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-5">
                    <input type="text" name="namaSupplier" id="namaSupplier" class="form-control form-control-sm">
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold text-dark">
                  <td style="width: 50px">No</td>
                  <td style="width: 100px">Kode Barang</td>
                  <td style="width: 250px">Nama Barang</td>
                  <td style="width: 90px">Qty SO</td>
                  <td style="width: 100px">Qty Revisi</td>
                  <td>Keterangan</td>
                  <td style="width: 60px">Ubah</td>
                  <td style="width: 60px">Hapus</td>
                </thead>
                <tbody>
                  @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td align="center">{{ $item->id_barang }} </td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="right">{{ $item->qty }}</td>
                        <td align="right">
                          <input type="text" class="form-control form-control-sm" name="qtyRevisi" placeholder="Qty Revisi">
                        </td>
                        <td align="right">
                          <input type="text" class="form-control form-control-sm" name="detailKet" placeholder="Keterangan Revisi">
                        </td>
                        <td align="center">
                          <a href="" id="editButton{{$i}}" 
                          onclick="return displayEditable({{$i}})">
                            <i class="fas fa-fw fa-edit fa-lg ic-edit mt-1"></i>
                          </a>
                          <a href="" id="updateButton{{$i}}" class="ic-update" 
                          onclick="return processEditable({{$i}})">
                            <i class="fas fa-fw fa-save fa-lg mt-1"></i>
                          </a>
                        </td>
                        <td align="center">
                          <a href="">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                  <tr>
                    <td colspan=8 class="text-center text-bold h4 p-2"><i>Silahkan Input Nomor SO</i></td>
                  </tr>
                  @endif
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
<script type="text/javascript">
const kode = document.getElementById("kodeSO");
const supplier = document.getElementById("namaSupplier");

/** Autocomplete Input Kode PO **/
$(function() {
  var so = [];
  @foreach($salesOrder as $so)
    so.push('{{ $so->id }}');
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
      response($.ui.autocomplete.filter(so, extractLast(request.term)));
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