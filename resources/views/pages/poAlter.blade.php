@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Purchase Order</h1>
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
            <form action="{{ route('po-create', $newcode) }}" method="POST">
              @csrf
              <div class="form-group row">
                <label for="kode" class="col-2 col-form-label text-bold ">Nomor PO</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="kode" value="{{ $newcode }}" readonly>
                </div>
                {{-- <div class="col-1"></div> --}}
                <label for="nama" class="col-2 col-form-label text-bold ">Tanggal PO</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-2">
                  <input type="text" class="form-control col-form-label-sm text-bold" name="tanggal" value="{{ $tanggal }}" readonly>
                </div>
              </div> 
              <div class="form-group row">
                <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                <span class="col-form-label text-bold">:</span>
                <div class="col-4">
                  <select name="namaSupplier" class="form-control col-form-label-sm">
                    @foreach($supplier as $s) 
                      <option value="{{ $s->id }}">{{ $s->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              {{-- <div class="form-group row">
                <label for="keterangan" class="col-1 col-form-label text-bold">Keterangan</label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm ml-1" name="keterangan" placeholder="Keterangan" 
                    value="{{ old('keterangan') }}">
                </div>
              </div> --}}
              <hr>
              <div class="form-row">
                <div class="form-group col-sm-3">
                  <label for="kode" class="col-form-label text-bold ">Nama Barang</label>
                  <input type="text" name="namaBarang" id="namaBarang" placeholder="Nama Barang" class="form-control form-control-sm namaBarang">
                  <input type="hidden" name="kodeBarang" id="idBarang" />
                </div>
                {{-- <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Pack</label>
                  <input type="text" name="pack" placeholder="Qty(Pack)" class="form-control form-control-sm">
                </div> --}}
                <div class="form-group col-sm-1">
                  <label for="kode" class="col-form-label text-bold ">Pcs</label>
                  <input type="text" name="pcs" id="qty" placeholder="Qty (Pcs)" class="form-control form-control-sm">
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Harga</label>
                  <input type="text" name="harga" id="harga" placeholder="Harga Satuan" class="form-control form-control-sm text-bold" readonly>
                  <input type="hidden" name="kodeHarga" id="idHarga" />
                </div>
                <div class="form-group col-sm-2">
                  <label for="kode" class="col-form-label text-bold ">Jumlah</label>
                  <input type="text" name="jumlah" id="jumlah" placeholder="Jumlah Harga" class="form-control form-control-sm text-bold" readonly>
                </div>
                <div class="form-group col-auto">
                  <label for="" class="col-form-label text-bold " ></label>
                  <button type="submit" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>
            </form>
            <hr>
            <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
              <thead class="text-center text-bold">
                <td>No</td>
                <td>Nama Barang</td>
                {{-- <td>Pack</td> --}}
                <td>Qty (Pcs)</td>
                <td>Harga</td>
                <td>Jumlah</td>
              </thead>
              <tbody>
                @if($itemsRow != 0)
                  @php $i = 1; @endphp
                  @foreach($items as $item)
                    <tr class="text-bold">
                      <td align="center">{{ $i }}</td>
                      <td>{{ $item->barang->nama }}</td>
                      {{-- <td></td> --}}
                      <td align="right">{{ $item->qty }}</td>
                      <td align="right">{{ $item->harga }}</td>
                      <td align="right">{{ $item->qty * $item->harga }}</td>
                    </tr>
                    @php $i++; @endphp
                  @endforeach
                @else
                  <tr>
                    <td colspan=6 class="text-center">Belum ada Detail PO</td>
                  </tr>
                @endif 
              </tbody>
            </table>
            <hr>
            <div class="form-row justify-content-center">
              <div class="col-2">
                <button type="submit" class="btn btn-success btn-block text-bold">Submit</button>
              </div>
              <div class="col-2">
                <button type="reset" class="btn btn-outline-secondary btn-block text-bold">Reset</button>
              </div>
            </div>
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
const namaBrg = document.getElementById('namaBarang');
const kodeBarang = document.getElementById('idBarang');
const harga = document.getElementById('harga');
const kodeHarga = document.getElementById('idHarga');
const jumlah = document.getElementById('jumlah');
const qty = document.getElementById('qty');
const tableID = document.getElementById('tablePO');
// const tambahBaris = document.querySelector('add-table-line');
// var baris = 0;
// const newTr = `
//   <tr class="hide">
//     <td align="center">${baris++}</td>
//     <td>${namaBrg.value}</td>
//     <td>${qty.value}</td>
//     <td>${harga.value}</td>
//     <td>${jumlah.value}</td>
//   </tr>`;

// Call Fungsi Harga Setelah Nama Barang Dipilih
namaBrg.addEventListener('change', displayHarga);
qty.addEventListener('change', displayJumlah);
// tambahBaris.addEventListener('click', displayBaris, false);

// Tampil Harga Otomatis
function displayHarga(e) {
  var idBarang;
  @foreach($barang as $br)
    if('{{ $br->nama }}' == e.target.value) {
      idBarang = '{{ $br->id }}';
    }
  @endforeach
  @foreach($harga as $hb)
    if(('{{ $hb->id_barang }}' == idBarang) && ('{{ $hb->id_harga }}' == 1)) {
      var hargaBeli = '{{ $hb->harga }}';
      var idHarga = '{{ $hb->id_harga }}';
    }
  @endforeach
  
  kodeBarang.value = idBarang;
  kodeHarga.value = idHarga;
  harga.value = hargaBeli;
  jumlah.value = hargaBeli;
}

function displayJumlah(e) {
  jumlah.value = e.target.value * harga.value;
} 

function displayBaris(e) {
  const clone = tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');
  if(tableID.find('tbody tr').length === 0) {
    $('tbody').append(newTr);
  }

  tableID.append(clone);
}

var barang = [];
@foreach($barang as $b)
  barang.push('{{ $b->nama }}');
@endforeach

autocomplete(namaBrg, barang);

function autocomplete(inp, arr) {
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}
</script>
@endpush