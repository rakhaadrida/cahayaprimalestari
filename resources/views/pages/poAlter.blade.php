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
            <form action="" method="">
              @csrf
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold ">Nomor PO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control col-form-label-sm text-bold" name="kode" value="{{ $newcode }}" readonly>
                      </div>
                      {{-- <div class="col-1"></div> --}}
                      <label for="nama" class="col-auto col-form-label text-bold ">Tanggal PO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2">
                        <input type="text" class="form-control col-form-label-sm text-bold" name="tanggal" value="{{ $tanggal }}" readonly>
                      </div>
                    </div>   
                  </div>
                  <div class="col" style="margin-left: -320px">
                    <div class="form-group row subtotal-po">
                      <label for="subtotal" class="col-5 col-form-label text-bold ">Sub Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        @php $subtotal = 0; @endphp
                        @foreach($items as $item)
                          @php 
                            $subtotal += $item->qty * $item->harga;
                          @endphp
                        @endforeach
                        @php $ppn = $subtotal * 10 / 100; @endphp
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="subtotal" value="{{ $subtotal }}">
                      </div>
                    </div>
                    <div class="form-group row total-po">
                      <label for="ppn" class="col-5 col-form-label text-bold ">PPN</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold" name="ppn" 
                        value="{{ $ppn }}">
                      </div>
                    </div>
                    <div class="form-group row total-po">
                      <label for="grandtotal" class="col-5 col-form-label text-bold ">Grand Total</label>
                      <span class="col-form-label text-bold">:</span>
                      <span class="col-form-label text-bold ml-2">Rp</span>
                      <div class="col-5">
                        <input type="text" class="form-control col-form-label-sm text-bold grand-total" name="grandtotal" 
                        value="{{ $subtotal + $ppn  }}" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row supplier-row">
                  <label for="alamat" class="col-2 col-form-label text-bold ">Nama Supplier</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-4">
                    <input type="text" name="namaSupplier" id="namaSupplier" placeholder="Nama Supplier" class="form-control form-control-sm"
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->supplier->nama }}" readonly
                      @endif
                    />
                    <input type="hidden" name="kodeSupplier" id="idSupplier" 
                      @if($itemsRow != 0) 
                        value="{{ $items[0]->id_supplier }}"
                      @endif
                    />
                  </div>
                </div>
              </div>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              {{-- <div class="form-group row">
                <label for="keterangan" class="col-1 col-form-label text-bold">Keterangan</label>
                <div class="form-group col-2">
                  <input type="text" class="form-control col-form-label-sm ml-1" name="keterangan" placeholder="Keterangan" 
                    value="{{ old('keterangan') }}">
                </div>
              </div> --}}
              <hr>

              <!-- Inputan Detil PO -->
              <div class="form-row">
                <div class="form-group col-sm-3">
                  <label for="kode" class="col-form-label text-bold ">Nama Barang</label>
                  <input type="text" name="namaBarang" id="namaBarang" placeholder="Nama Barang" class="form-control form-control-sm">
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
                  <button type="submit" formaction="{{ route('po-create', $newcode) }}" formmethod="POST" class="btn btn-primary btn-block btn-md form-control form-control-md text-bold mt-2">Tambah</button>
                </div>
              </div>          
              <hr>
              <!-- End Inputan Detil PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="tablePO">
                <thead class="text-center text-bold text-dark">
                  <td>No</td>
                  <td>Nama Barang</td>
                  {{-- <td>Pack</td> --}}
                  <td>Qty (Pcs)</td>
                  <td>Harga</td>
                  <td>Jumlah</td>
                  <td>Ubah</td>
                  <td>Hapus</td>
                </thead>
                <tbody>
                  @if($itemsRow != 0)
                    @php $i = 1; @endphp
                    @foreach($items as $item)
                      <tr class="text-bold">
                        <td align="center">{{ $i }}</td>
                        <td class="editable{{$i}}" id="editableBarang{{$i}}">
                          {{ $item->barang->nama }}
                        </td>
                        {{-- <td></td> --}}
                        <td align="right" class="editable{{$i}}" id="editableQty{{$i}}">
                          {{ $item->qty }}
                        </td>
                        <td align="right" class="autoharga">{{ $item->harga }}</td>
                        <td align="right" class="autototal">{{ $item->qty * $item->harga }}</td>
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
                          <a href="{{ route('po-remove', ['po' => $item->id_po, 'barang' => $item->id_barang]) }}">
                            <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                          </a>
                        </td>
                      </tr>
                      @php $i++; @endphp
                    @endforeach
                  @else
                    <tr>
                      <td colspan=7 class="text-center">Belum ada Detail PO</td>
                    </tr>
                  @endif 
                </tbody>
              </table>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" formaction="{{ route('po-process', $newcode) }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
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
<!-- /.container-fluid -->
@endsection

@push('addon-script')
<script type="text/javascript">
const namaSup = document.getElementById('namaSupplier');
const kodeSup = document.getElementById('idSupplier');
const namaBrg = document.getElementById('namaBarang');
const kodeBarang = document.getElementById('idBarang');
const harga = document.getElementById('harga');
const kodeHarga = document.getElementById('idHarga');
const jumlah = document.getElementById('jumlah');
const qty = document.getElementById('qty');

/** Call Fungsi Setelah Inputan Terisi **/
namaSup.addEventListener('change', displaySupp);
namaBrg.addEventListener('change', displayHarga);
qty.addEventListener('change', displayJumlah);

/** Tampil Id Supplier **/
function displaySupp(e) {
  var idSupp;
  @foreach($supplier as $s)
    if('{{ $s->nama }}' == e.target.value) {
      idSupp = '{{ $s->id }}';
    }
  @endforeach
  
  kodeSup.value = idSupp;
}

/** Tampil Harga Otomatis **/
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

/** Tampil Jumlah Harga Otomatis **/
function displayJumlah(e) {
  jumlah.value = e.target.value * harga.value;
} 

/** Tampil Table Column Editable **/
function displayEditable(no) {
  document.getElementById("editButton"+no).style.display = "none";
  document.getElementById("updateButton"+no).style.display = "block";
  let row = document.querySelectorAll(".editable"+no);

  row.forEach(function(e) {
    e.contentEditable = true;
    e.style.backgroundColor = "lightgrey";
    e.style.color = "black";
  })

  return false;
}

/* function processEditable(no) {
  let editableBarang = document.getElementById("editableBarang"+no);
  let editableQty = document.getElementById("editableQty"+no);
  const itemsEdit = [];

  itemsEdit.push({
    barang: editableBarang.value;
    qty: editableQty.value;
  })  
  
  $.ajax({
    url: '/po/update',
    type: 'post',
    data: itemsEdit
  });
} */

/** Autocomplete Input Text **/
$(function() {
  var barang = [];
  @foreach($barang as $b)
    barang.push('{{ $b->nama }}');
  @endforeach

  var supplier = [];
  @foreach($supplier as $s)
    supplier.push('{{ $s->nama }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Barang --*/
  $(namaBrg).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(barang, extractLast(request.term)));
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

  /*-- Autocomplete Input Supplier --*/
  $(namaSupplier).on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(supplier, extractLast(request.term)));
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

/*
autocomplete(namaBrg, barang);
*/

// function autocomplete(inp, arr) {
//   var currentFocus;
//   /*execute a function when someone writes in the text field:*/
//   inp.addEventListener("input", function(e) {
//       var a, b, i, val = this.value;
//       console.log(val);
//       /*close any already open lists of autocompleted values*/
//       closeAllLists();
//       if (!val) { return false;}
//       currentFocus = -1;
//       /*create a DIV element that will contain the items (values):*/
//       a = document.createElement("DIV");
//       a.setAttribute("id", this.id + "autocomplete-list");
//       a.setAttribute("class", "autocomplete-items");
//       /*append the DIV element as a child of the autocomplete container:*/
//       this.parentNode.appendChild(a);
//       /*for each item in the array...*/
//       for (i = 0; i < arr.length; i++) {
//         console.log(arr[i].indexOf(val));
//         /*check if the item starts with the same letters as the text field value:*/
//         if (arr[i].toUpperCase().indexOf(val) == val.toUpperCase()) {
//         // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
//           /*create a DIV element for each matching element:*/
//           b = document.createElement("DIV");
//           /*make the matching letters bold:*/
//           b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
//           b.innerHTML += arr[i].substr(val.length);
//           /*insert a input field that will hold the current array item's value:*/
//           b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
//           /*execute a function when someone clicks on the item value (DIV element):*/
//           b.addEventListener("click", function(e) {
//               /*insert the value for the autocomplete text field:*/
//               inp.value = this.getElementsByTagName("input")[0].value;
//               /*close the list of autocompleted values,
//               (or any other open lists of autocompleted values:*/
//               closeAllLists();
//           });
//           a.appendChild(b);
//         }
//       }
//   });
//   /*execute a function presses a key on the keyboard:*/
//   inp.addEventListener("keydown", function(e) {
//       var x = document.getElementById(this.id + "autocomplete-list");
//       if (x) x = x.getElementsByTagName("div");
//       if (e.keyCode == 40) {
//         /*If the arrow DOWN key is pressed,
//         increase the currentFocus variable:*/
//         currentFocus++;
//         /*and and make the current item more visible:*/
//         addActive(x);
//       } else if (e.keyCode == 38) { //up
//         /*If the arrow UP key is pressed,
//         decrease the currentFocus variable:*/
//         currentFocus--;
//         /*and and make the current item more visible:*/
//         addActive(x);
//       } else if (e.keyCode == 13) {
//         /*If the ENTER key is pressed, prevent the form from being submitted,*/
//         e.preventDefault();
//         if (currentFocus > -1) {
//           /*and simulate a click on the "active" item:*/
//           if (x) x[currentFocus].click();
//         }
//       }
//   });
//   function addActive(x) {
//     /*a function to classify an item as "active":*/
//     if (!x) return false;
//     /*start by removing the "active" class on all items:*/
//     removeActive(x);
//     if (currentFocus >= x.length) currentFocus = 0;
//     if (currentFocus < 0) currentFocus = (x.length - 1);
//     /*add class "autocomplete-active":*/
//     x[currentFocus].classList.add("autocomplete-active");
//   }
//   function removeActive(x) {
//     /*a function to remove the "active" class from all autocomplete items:*/
//     for (var i = 0; i < x.length; i++) {
//       x[i].classList.remove("autocomplete-active");
//     }
//   }
//   function closeAllLists(elmnt) {
//     /*close all autocomplete lists in the document,
//     except the one passed as an argument:*/
//     var x = document.getElementsByClassName("autocomplete-items");
//     for (var i = 0; i < x.length; i++) {
//       if (elmnt != x[i] && elmnt != inp) {
//         x[i].parentNode.removeChild(x[i]);
//       }
//     }
//   }
//   /*execute a function when someone clicks in the document:*/
//   document.addEventListener("click", function (e) {
//       closeAllLists(e.target);
//   });
// }
</script>
@endpush