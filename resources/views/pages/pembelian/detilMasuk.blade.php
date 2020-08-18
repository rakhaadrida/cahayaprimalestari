@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Penerimaan Barang</h1>
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
              <div class="form-group row">
                <label for="kode" class="col-2 col-form-label text-bold">Nomor PO</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="kode" id="kodePO" value="{{ $itemPo->id }}">
                </div>
                <div class="col-1" style="margin-left: -10px">
                  <button type="submit" formaction="{{ route('bm-process') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-block text-bold">Cari</button>
                </div>
              </div>
              <div class="form-group row input-header">
                <label for="nama" class="col-2 col-form-label text-bold">Tanggal Terima</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="tanggal" value="{{ $tanggal }}" readonly>
                </div>
              </div> 
              <div class="form-group row input-header">
                <label for="alamat" class="col-2 col-form-label text-bold">Nama Supplier</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-3">
                  <input type="text" name="namaSupplier" id="namaSupplier" class="form-control form-control-sm text-bold" value="{{ $itemPo->supplier->nama }}" readonly>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold">
                  <td>No</td>
                  <td>Nama Barang</td>
                  <td>Qty PO (Pcs)</td>
                  <td>Dikirim (Pcs)</td>
                  <td>Keterangan</td>
                </thead>
                <tbody>
                  @if($items != null)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="center">{{ $item->qty }}</td>
                        <td>
                          <input type="text" name="qtyDikirim" class="form-control form-control-sm input-table">
                        </td>
                        <td>
                          <input type="text" name="keterangan" class="form-control form-control-sm input-table">
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=5 class="text-center">Nomor PO Belum Pernah Disimpan</td>
                    </tr>
                  @endif 
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
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
<script type="text/javascript">
const kode = document.getElementById("kodePO");
const supplier = document.getElementById("namaSupplier");

/** Autocomplete Input Kode PO **/
$(function() {
  var po = [];
  @foreach($po as $p)
    po.push('{{ $p->id }}');
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
      response($.ui.autocomplete.filter(po, extractLast(request.term)));
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