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
                    <input type="text" name="keterangan" id="keterangan" class="form-control form-control-sm">
                    <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $itemsRow }}">
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold text-dark">
                  <td style="width: 50px" class="align-middle">No</td>
                  <td style="width: 100px">Kode Barang</td>
                  <td style="width: 250px" class="align-middle">Nama Barang</td>
                  <td style="width: 90px" class="align-middle">Qty SO</td>
                  <td style="width: 100px" class="align-middle">Qty Revisi</td>
                  <td class="align-middle">Keterangan</td>
                  <td style="width: 60px" class="align-middle" id="editHead">Edit</td>
                  <td style="width: 60px" class="align-middle" id="deleteHead">Hapus</td>
                </thead>
                <tbody id="tablePO">
                  @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center" class="align-middle">{{ $i }}</td>
                        <td>
                          <input type="text" name="kodeBarang[]" readonly class="form-control-plaintext form-control-sm text-bold kodeBarang" value="{{ $item->id_barang }}">
                        </td>
                        <td>
                          <input type="text" name="namaBarang[]" readonly class="form-control-plaintext form-control-sm text-bold namaBarang" value="{{ $item->barang->nama }}">
                        </td>
                        <td> 
                          <input type="text" name="qty[]" readonly class="form-control-plaintext form-control-sm text-bold text-right qty" value="{{ $item->qty }}">
                        </td>
                        <td align="right" class="qtyRevisi{{$i}}" id="editableQty{{$i}}">
                        </td>
                        <td align="center" class="keterangan{{$i}}" id="editableKet{{$i}}">
                        </td>
                        <td align="center">
                          <a href="" id="editButton{{$i}}" 
                          onclick="return displayEditable({{$i}})">
                            <i class="fas fa-fw fa-edit fa-lg ic-edit mt-1 align-middle"></i>
                          </a>
                          <button type="button" id="updateButton{{$i}}" class=" btn btn-md ic-update">
                            <i class="fas fa-fw fa-save fa-lg align-middle mt-1"></i>
                          </button>
                        </td>
                        <td align="center" class="align-middle">
                          <a href="#" class="icRemove" id="removeButton{{$i}}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                          <a href="" id="cancelButton{{$i}}" class="ic-cancel" 
                          onclick="return cancelEditable({{$i}})">
                            <i class="fas fa-fw fa-history fa-lg mt-1"></i>
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

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('sj-process', $items[0]->id_so) }}" formmethod="POST" class="btn btn-success btn-block text-bold"
                    id="submitBM" onclick="return checkEditable()" >Submit</>
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
const kode = document.getElementById("kodeSO");
const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const qty = document.querySelectorAll(".qty");
const qtyRevisi = document.querySelectorAll(".qtyRevisi");
const keterangan = document.querySelectorAll(".keterangan");
const hapusBaris = document.querySelectorAll(".icRemove");
const jumBaris = document.getElementById('jumBaris');

function displayEditable(no) {
  document.getElementById("editButton"+no).style.display = "none";
  document.getElementById("updateButton"+no).style.display = "block";
  document.getElementById("removeButton"+no).style.display = "none";
  document.getElementById("cancelButton"+no).style.display = "block";
  let rowQty = document.querySelectorAll(".qtyRevisi"+no);
  let rowKet = document.querySelectorAll(".keterangan"+no);
  document.getElementById("editHead").innerText = "Simpan"
  document.getElementById("deleteHead").innerText = "Batal"

  rowQty.forEach(function(e) {
    const editQty = `
      <input type="text" name="editQty[]" class="form-control form-control-sm text-bold" 
      value="">
    `;
    $(e).empty();
    $(e).append(editQty);
  })

  rowKet.forEach(function(e) {
    const editKet = `
      <input type="text" name="editKet[]" class="form-control form-control-sm text-bold" value="">
    `;
    $(e).empty();
    $(e).append(editKet);
  })

  return false;
}

function cancelEditable(no) {
  document.getElementById("updateButton"+no).style.display = "none";
  document.getElementById("editButton"+no).style.display = "block";
  document.getElementById("cancelButton"+no).style.display = "none";
  document.getElementById("removeButton"+no).style.display = "block";
  document.getElementById("editHead").innerText = "Edit";
  document.getElementById("deleteHead").innerText = "Delete";
  const tdQty = document.getElementById("editableQty"+no);
  const tdKet = document.getElementById("editableKet"+no);
  const inputQty = tdQty.getElementsByTagName('input')[0];
  const inputKet = tdKet.getElementsByTagName('input')[0];
  const isiQty = inputQty.value;
  const isiKet = inputKet.value;
  $(tdQty).empty();
  $(tdKet).empty();
  tdQty.innerText = isiQty;
  tdKet.innerText = isiKet;

  return false;
}

function checkEditable(e) {
  var j = 0;
  for(let i = 1; i <= '{{ $itemsRow }}'; i++) {
    var getRow = document.getElementById("updateButton"+i);
    if(getRow.style.display == "block") {
      j = 1;
    }
  }

  if(j == 1) {
    alert(`Silahkan simpan perubahan terlebih dahulu`);
    return false;
  }
  else {
    document.getElementById("submitBM").formMethod = "POST";
    document.getElementById("submitBM").formAction = '{{ route('bm-process', $items[0]->id_so) }}';
  }
}

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    for(let j = i; j < hapusBaris.length; j++) {
      if(j == hapusBaris.length - 1) {
        $(tablePO).find('tr:last').remove();  
      }
      else {
        qty[j].value = qty[j+1].value;
        brgNama[j].value = brgNama[j+1].value;
        kodeBarang[j].value = kodeBarang[j+1].value;
      }     
    } 
  });
  jumBaris.value -= 1;
}

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

</script>
@endpush