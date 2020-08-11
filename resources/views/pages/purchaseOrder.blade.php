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
            <form action="{{ route('po.store') }}" method="POST">
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
              <span class="add-table-line float-left mb-2 mr-2">
                <a href="#!" class="text-secondary">
                  <i class="fas fa-plus" aria-hidden="true"> 
                    <span class="add-line ml-2"> Tambah Baris </span>  
                  </i>
                </a>
              </span>
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover">
                <thead class="text-center text-bold">
                  <td>No</td>
                  <td>Nama Barang</td>
                  <td>Pack</td>
                  <td>Qty (Pcs)</td>
                  <td>Harga</td>
                  <td>Jumlah</td>
                </thead>
                <tbody>
                  @for($i=1; $i<=2; $i++)
                    <tr>
                      <td align="center">{{ $i }}</td>
                      <td >
                        <input type="text" name="namaBarang" id="barang" placeholder="Nama Barang" class="" onclick="displayHarga(event)">
                        {{-- <select name="namaBarang" class="form-control form-control-sm" onchange="displayHarga(event)">
                          @foreach($barang as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                          @endforeach
                        </select> --}}
                      </td>
                      <td> 
                        <input type="text" name="pack" class="form-control form-control-sm">
                      </td>
                      <td>
                        <input type="text" name="qty" class="form-control form-control-sm">
                      </td>
                      <td>
                        <input type="text" id="harga" name="harga" class="form-control form-control-sm">
                      </td>
                      <td>
                        <input type="text" name="jumlah" class="form-control form-control-sm">
                      </td>
                    </tr>
                  @endfor
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
<script>
const $tableID = $('#table');
const newTr = `
  <tr class="hide">
    <td align="center">{{ $i }}</td>
    <td>
      <select name="namaBarang" class="form-control form-control-sm">
        @foreach($barang as $b)
          <option value="{{ $b->id }}">{{ $b->nama }}</option>
        @endforeach
      </select>
    </td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
    <td><input type="hidden" name="harga" value="" /></td>
    <td><input type="hidden" name="jumlah" value="" /></td>
  </tr>`;

$('.add-table-line').on('click', 'i', () => {

  const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');

  if ($tableID.find('tbody tr').length === 0) {

    $('tbody').append(newTr);
  }

  $tableID.find('table').append($clone);
});

function displayHarga(e) {
  const harga = document.getElementById('harga');
  harga.value = '12000';
}

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

var namaOrang = ["Rakha", "Adrida", "Bagaspati"];
var barang = {!! json_encode($namaBarang) !!};

// console.log(barang);

autocomplete(document.getElementById("barang"), barang);
</script>
@endpush