@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Ubah Faktur</h1>
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
                <div class="form-group row">
                  <label for="kode" class="col-2 col-form-label text-bold">Nomor SO</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="kode" id="kode" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Nama Customer</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="namaCustomer" id="namaCustomer" >
                    <input type="hidden" name="kodeCustomer">
                  </div>
                </div>   
                <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Tanggal Awal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">Tanggal Akhir</label>
                  <span class="col-form-label text-bold ml-3">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAkhir" >
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('so-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>  
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 form-control-sm text-bold mt-1">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold"
                        @if($itemsRow != 0)
                          value="{{ $items->id }}"
                        @endif
                        >
                      </div>
                    </div>
                  </div> 
                  <div class="col" style="margin-left: -450px">
                    <div class="form-group row">
                      <label for="tanggal" class="col-auto form-control-sm text-bold mt-1">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-8">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" 
                        @if($itemsRow != 0)
                          value="{{ $items->customer->nama }} ({{ $items->id_customer }})"
                        @endif
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row" style="margin-top: -25px">
                  <label for="tanggal" class="col-2 form-control-sm text-bold mt-1">Tanggal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold"
                    @if($itemsRow != 0)
                      value="{{ $items->tgl_so }}"
                    @endif
                    >
                  </div>
                </div> 
              </div>

              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold text-dark">
                  <td style="width: 30px">No</td>
                  <td style="width: 80px">Kode</td>
                  <td>Nama Barang</td>
                  <td style="width: 50px">Qty</td>
                  <td>Harga</td>
                  <td>Jumlah</td>
                  <td style="width: 80px">Diskon(%)</td>
                  <td style="width: 110px">Diskon(Rp)</td>
                  <td style="width: 120px">Netto (Rp)</td>
                </thead>
                <tbody>
                  @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($itemsDetail as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td align="center">{{ $item->id_barang }} </td>
                        <td>{{ $item->barang->nama }}</td>
                        <td align="right">{{ $item->qty }}</td>
                        <td align="right">{{ $item->harga }}</td>
                        <td align="right">{{ $item->qty * $item->harga }}</td>
                        <td align="center">{{ $item->diskon }}</td>
                        <td align="center">
                          {{ (($item->qty * $item->harga) * $item->diskon) / 100 }}
                        </td>
                        <td align="center">
                          {{ ($item->qty * $item->harga) - 
                          ((($item->qty * $item->harga) * $item->diskon) / 100) }}
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan="9" class="text-center text-bold h4 p-2"><i>Belum ada Detail SO</i></td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="" formmethod="POST" class="btn btn-danger btn-block text-bold">Batal</>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-info btn-block text-bold">Ubah</button>
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
<!-- /.container-fluid -->
@endsection

@push('addon-script')
<script type="text/javascript">
const namaCust = document.getElementById('namaCustomer');
const kodeCust = document.getElementById('idCustomer');
const kodeSO = document.getElementById('kode');

/** Call Fungsi Setelah Inputan Terisi **/

/** Autocomplete Input Text **/
$(function() {
  var customer = [];
  var kodeFaktur = [];
  @foreach($customer as $c)
    customer.push('{{ $c->nama }}');
  @endforeach
  @foreach($so as $s)
    kodeFaktur.push('{{ $s->id }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Customer --*/
  $(namaCust).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(customer, extractLast(request.term)));
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

  /*-- Autocomplete Input Kode SO --*/
  $(kodeSO).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(kodeFaktur, extractLast(request.term)));
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