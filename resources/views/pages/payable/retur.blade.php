<style>
  .ui-autocomplete {
    z-index:1050;
  }
</style>
@foreach($ap as $a)
  <div class="modal modalGudang" id="Retur{{ $a->id_bm }}" tabindex="-1" role="dialog" aria-labelledby="{{$i-1}}" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true" class="h2 text-bold">&times;</span>
          </button>
          <h5 class="modal-title text-bold"><i class="fa fa-money-bill-alt fa-fw"></i> Detail Retur Faktur <strong>{{ $a->id_bm }}</strong></h5>
        </div>
        <div class="modal-body text-dark">
          <form action="" method="POST">
            @csrf
            <input type="hidden" name="kode" value="{{ $a->id }}">
            <table class="table table-responsive-md table-bordered table-striped table-md" style="font-size: 16px" id="dataTabRetur{{ $a->id }}" width="100%" cellspacing="0">
              <thead class="text-center text-bold text-dark">
                <tr class="text-center">
                  <th rowspan="2" class="align-middle" style="width: 30px">No</th>
                  <th rowspan="2" class="align-middle"style="width: 100px">Kode</th>
                  <th rowspan="2" class="align-middle">Nama Barang</th>
                  <th rowspan="2" class="align-middle"style="width: 110px">Tgl. Retur</th>
                  <th rowspan="2" class="align-middle"style="width: 60px">Qty</th>
                  <th rowspan="2" class="align-middle"style="width: 90px">Harga</th>
                  <th rowspan="2" class="align-middle"style="width: 100px">Jumlah</th>
                  <th colspan="2">Diskon</th>
                  <th rowspan="2" class="align-middle"style="width: 110px">Total</th>
                </tr>
                <tr class="text-center">
                  <th style="width: 90px">%</th>
                  <th style="width: 110px">Rupiah</th>
                </tr>
              </thead>
              <tbody class="table-ar">
                @php 
                  $i = 1; $totalQty = 0; $totalRet = 0;
                  $retur = App\Models\DetilRAP::join('ap_retur', 'ap_retur.id', 
                          'detilrap.id_retur')->where('id_ap', $a->id)->get();
                @endphp
                @foreach($retur as $d)
                  <tr class="table-modal-first-row text-dark" >
                    <td class="text-center" >{{ $i }}</td>
                    <td class="text-center">{{ $d->id_barang }}</td>
                    <td>{{ $d->barang->nama }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($d->tgl_retur)->format('d-M-y') }}</td>
                    <td class="text-right">{{ $d->qty }}</td>
                    {{-- @php $kurang -= $d->cicil; @endphp --}}
                    <td class="text-right">{{ number_format($d->harga, 0, "", ".") }}</td>
                    <td class="text-right">{{ number_format($d->qty * $d->harga, 0, "", ".") }}</td>
                    <td class="text-right">{{ $d->diskon }}</td>
                    <td class="text-right">{{ number_format($d->diskonRp, 0, "", ".") }}</td>
                    <td class="text-right">{{ number_format(($d->qty * $d->harga) - $d->diskonRp, 0, "", ".") }}</td>
                  </tr>
                  @php $i++; $totalQty += $d->qty; $totalRet += (($d->qty * $d->harga) - $d->diskonRp) @endphp
                @endforeach
                @if($a->keterangan == 'BELUM LUNAS')
                  <tr class="text-dark">
                    <td class="text-center align-middle">{{ $i }}</td>
                    <td class="align-middle">
                      <input type="text" class="form-control form-control-sm text-bold text-dark kodeBarang" name="kodeBarang{{$a->id}}" id="kodeBarang{{$a->id}}">
                    </td>
                    <td class="align-middle">
                      <input type="text" class="form-control form-control-sm text-bold text-dark namaBarang" name="namaBarang{{$a->id}}" id="namaBarang{{$a->id}}">
                    </td>
                    <td class="align-middle">
                      <input type="text" class="form-control datepicker form-control-sm text-bold text-dark text-center tglRetur" name="tglRetur{{$a->id}}" id="tglRetur{{$a->id}}" placeholder="DD-MM-YYYY" autocomplete="off">
                    </td>
                    <td class="align-middle">
                      <input type="text" class="form-control form-control-sm text-bold text-dark text-right qty" name="qty{{$a->id}}" id="qty{{$a->id}}" onkeypress="return angkaSaja(event)" autocomplete="off">
                    </td>
                    <td class="align-middle">
                      <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" name="harga{{$a->id}}" id="harga{{$a->id}}">
                    </td>
                    <td class="text-right align-middle">
                      <input type="text" name="jumlah{{$a->id}}" id="jumlah{{$a->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah">
                    </td>
                    <td class="align-middle" style="width: 90px">
                      <input type="text" name="diskon{{$a->id}}" id="diskon{{$a->id}}" class="form-control form-control-sm text-bold text-dark text-right diskon" onkeypress="return angkaPlus(event)" autocomplete="off">
                    </td>
                    <td class="text-right align-middle" style="width: 110px">
                      <input type="text" name="diskonRp{{$a->id}}" id="diskonRp{{$a->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right diskonRp">
                    </td>
                    <td class="text-right align-middle">
                      <input type="text" name="netto{{$a->id}}" id="netto{{$a->id}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto">
                    </td>
                  </tr>
                @endif 
              </tbody>
              <tfoot>
                <tr class="text-right text-bold text-dark">
                  <td colspan="4" class="align-middle text-center ">Total</td>
                  <td>
                    {{-- <input type="text" name="total{{$a->id_so}}" id="total{{$a->id_so}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right total" value=""> --}}
                    {{ number_format($totalQty, 0, "", ".") }}
                  </td>
                  <td colspan="4"></td>
                  <td>
                    {{-- <input type="text" name="total{{$a->id_so}}" id="total{{$a->id_so}}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right total" value=""> --}}
                    {{ number_format($totalRet, 0, "", ".") }}
                  </td>
                </tr>
              </tfoot>
            </table>
            <hr>

            @if($a->keterangan == 'BELUM LUNAS')
              <div class="form-row justify-content-center">
                <div class="col-3">
                  <button type="submit" class="btn btn-success btn-block text-bold" formaction="{{ route('ap-retur') }}" formmethod="POST">Submit</button>
                </div>
                <div class="col-3">
                  <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-block text-bold">Batal</button>
                </div>
              </div>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
  
@endforeach

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

/* @foreach($ap as $a)
  $('#dataTabRetur'+'{{ $a->id }}').dataTable( {
    "columnDefs": [
      { "orderable": false }
    ],
    "aaSorting" : [],
    "footerCallback": function ( row, data, start, end, display ) {
      var api = this.api(), data;

      // Remove the formatting to get integer data for summation
      var intVal = function ( i ) {
        return typeof i === 'string' ?
          i.replace(/[\$.]/g, '')*1 :
          typeof i === 'number' ?
              i : 0;
      };

      $.each([4, 9], function(index, value) {

        var column = api
          .column(value, {
              page: 'current'
          })
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal($(b).val());
          }, 0 );

        var column_total = api
          .column(value)
          .data()
          .reduce( function (a, b) {
            return intVal(a) + intVal($(b).val());
          }, 0 );

        // Update footer
        $(api.column(value).footer()).html(addCommas(column));
      }); 
    }
  });
@endforeach */

const kodeBarang = document.querySelectorAll('.kodeBarang');
const brgNama = document.querySelectorAll(".namaBarang");
const tglRetur = document.querySelectorAll('.tglRetur');
const qty = document.querySelectorAll('.qty');
const harga = document.querySelectorAll('.harga');
const jumlah = document.querySelectorAll('.jumlah');
const diskon = document.querySelectorAll('.diskon');
const diskonRp = document.querySelectorAll('.diskonRp');
const netto = document.querySelectorAll('.netto');
const total = document.querySelectorAll('.total');

for(let i = 0; i < kodeBarang.length; i++) {
  brgNama[i].addEventListener("keyup", displayHarga) ;
  kodeBarang[i].addEventListener("keyup", displayHarga);
  brgNama[i].addEventListener("blur", displayHarga) ;
  kodeBarang[i].addEventListener("blur", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
      }
    @endforeach

    qty[i].value = '';
  }
}

for(let i = 0; i < tglRetur.length; i++) {
  tglRetur[i].addEventListener("keyup", function(e) {
    var value = e.target.value.replaceAll("-","");
    var arrValue = value.split("", 3);
    var kode = arrValue.join("");

    if(value.length > 2 && value.length <= 4) 
      value = value.slice(0,2) + "-" + value.slice(2);
    else if(value.length > 4 && value.length <= 8)
      value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);
    
    tglRetur[i].value = value;
  });
}

for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("keyup", function(e) {
    if(e.target.value == "") {
      // total[i].value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      diskonRp[i].value = "";
      netto[i].value = "";
    }
    else {  
      // netPast = +jumlah[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas(angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100);
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      // checkSubtotal(netPast, +jumlah[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
  });
}

for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      // netPast = jumlah[i].value.replace(/\./g, "");
      netto[i].value = addCommas(+netto[i].value.replace(/\./g, "") + +diskonRp[i].value.replace(/\./g, ""))
      // checkSubtotal(netPast, jumlah[i].value.replace(/\./g, ""));
      diskonRp[i].value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      console.log(angkaDiskon);
      netPast = +netto[i].value.replace(/\./g, "");
      diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""))
      // checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    // total_ppn(subtotal.value.replace(/\./g, ""));
    // totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    // grandtotal.value = totalNotPPN.value;
  });
}

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon =  ((totDiskon - 100) * -1).toFixed(2);
  return totDiskon;
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

/** Inputan hanya bisa angka dan plus **/
function angkaPlus(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && charCode != 43  && (charCode < 48 || charCode > 57)) {
    return false;
  }
  return true;
}

// function checkSubtotal(Past, Now) {
//   if(Past > Now) {
//     subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
//     totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - (+Past - +Now));
//   } else {
//     subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
//     totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") + (+Now - +Past));
//   }
// }

/** Add Thousand Separators **/
function addCommas(nStr) {
	nStr += '';
	x = nStr.split(',');
	x1 = x[0];
	x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}
	return x1 + x2;
}

/** Autocomplete Input Kode PO **/
  $(function() {
    var kode = [];
    var nama = [];
    @foreach($barang as $b)
      kode.push('{{ $b->id }}');
      nama.push('{{ $b->nama }}');
    @endforeach
      
    function split(val) {
      return val.split(/,\s*/);
    }

    function extractLast(term) {
      return split(term).pop();
    }

    /*-- Autocomplete Input Nama Barang --*/
    $(brgNama).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(nama, extractLast(request.term)));
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

    /*-- Autocomplete Input Kode Barang --*/
    $(kodeBarang).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(kode, extractLast(request.term)));
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