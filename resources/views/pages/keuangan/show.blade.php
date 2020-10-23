@extends('layouts.admin')

@push('addon-style')
  <link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Laporan Keuangan</h1>
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
                <div class="form-group row justify-content-center">
                  <label for="tahun" class="col-auto col-form-label text-bold">Tahun</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-1">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="tahun" id="tahun" value="{{$tahun}}">
                  </div>
                  <label for="bulan" class="col-auto col-form-label text-bold ">Bulan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" id="bulan" name="bulan" value="{{$bulan}}">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('lap-keu-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>   
                {{-- <div class="form-group row" style="margin-top: -10px">
                  <label for="kode" class="col-2 col-form-label text-bold">Tanggal Awal</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAwal" >
                  </div>
                  <label for="tanggal" class="col-auto col-form-label text-bold ">s / d</label>
                  <div class="col-2">
                    <input type="date" class="form-control form-control-sm text-bold mt-1" name="tglAkhir" >
                  </div>
                </div>   --}}
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->

              <div class="container">
                <div class="row justify-content-center">
                  <h4 class="text-bold text-dark">Laporan Keuangan {{$tahun}}</h4>
                </div>
                <div class="row justify-content-center" style="margin-top: -5px">
                  <h5 class="text-bold text-dark">Bulan : {{$bulan}}</h5>
                </div>
                {{-- <div class="row justify-content-end" style="margin-top: -55px">
                  <div class="col-2">
                    <button type="submit" formaction="{{ route('lap-keu-show') }}" formmethod="POST"  class="btn btn-success btn-block text-bold">Download Excel</>
                  </div>
                </div> --}}
              </div>

              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" @if($items->count() != 0) id="dataTable" width="100%" cellspacing="0" @endif>
                <thead class="text-center text-bold text-dark">
                  <tr style="font-size: 14px">
                    <th style="width: 10px" class="align-middle">NO</th>
                    <th style="width: 120px" class="align-middle">SALES</th>
                    <th style="width: 60px" class="align-middle">DETAIL</th>
                    @foreach($jenis as $j)
                      <th style="width: 70px" class="align-middle">{{$j->nama}}</th>
                    @endforeach
                    <th style="width: 50px" class="align-middle">RETUR</th>
                    <th style="width: 40px" class="align-middle">Grand Total</th>
                  </tr>
                </thead>
                <tbody class="table-ar">
                  @php $no = 1 @endphp
                  @forelse($sales as $s)
                    <tr class="text-dark">
                      <td rowspan=3 align="center" class="align-middle">{{ $no }}</td>
                      <td rowspan=3 class="align-middle">{{ $s->nama }}</td>
                      <td align="center" style="background-color: #f0ededda !important">Revenue</td>
                      @php $total = 0; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle" style="background-color: #f0ededda !important">
                        @foreach($items as $i)
                          @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                            {{ number_format($i->total, 0, "", ",") }}
                            @php $total += $i->total @endphp
                          @endif
                        @endforeach
                        </td>
                      @endforeach
                      <td align="right" class="align-middle" style="background-color: #f0ededda !important"></td>
                      <td align="right" class="align-middle" style="background-color: #f0ededda !important">
                        {{ number_format($total, 0, "", ",") }}
                      </td>
                    </tr>
                    <tr class="text-dark" style="background-color: white">
                      <td align="center">HPP</td>
                      @php $hpp = 0; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle">
                        @foreach($items as $i)
                          @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                            {{ number_format($i->hpp, 0, "", ",") }}
                            @php $hpp += $i->hpp @endphp
                          @endif
                        @endforeach
                        </td>
                      @endforeach
                      <td align="right" class="align-middle"></td>
                      <td align="right" class="align-middle">
                        {{ number_format($hpp, 0, "", ",") }}
                      </td>
                    </tr>
                    <tr class="text-dark text-bold" style="background-color: yellow">
                      <td align="center">Laba</td>
                      @php $laba = 0; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle">
                        @foreach($items as $i)
                          @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                            {{ number_format($i->total - $i->hpp, 0, "", ",") }}
                            @php $laba += ($i->total - $i->hpp) @endphp
                          @endif
                        @endforeach
                        </td>
                      @endforeach
                      <td align="right" class="align-middle"></td>
                      <td align="right" class="align-middle">
                        {{ number_format($laba, 0, "", ",") }}
                      </td>
                    </tr>
                    @php $no++ @endphp
                  @empty
                    <tr>
                      <td colspan=12 class="text-center text-bold h4 p-2"><i>Belum Ada Laporan Keuangan</i></td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
              <!-- End Tabel Data Detil PO -->

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
{{-- <script src="{{ url('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('backend/js/demo/datatables-demo.js') }}"></script> --}}
<script type="text/javascript">
const tahun = document.getElementById('tahun');
const bulan = document.getElementById('bulan');

/** Sort Datatable **/
/* $('#dataTable').dataTable( {
  "columnDefs": [
    { "orderable": false, "targets": [0, 1, 2] }
  ],
  "aaSorting" : []
}); */

/** Autocomplete Input Text **/
$(function() {
  var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
              'September', 'Oktober', 'November', 'Desember'];
  var yearNow = new Date();
  yearNow = yearNow.getFullYear();
  var tahun = [`${yearNow}`, `${yearNow-1}`, `${yearNow-2}`];
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Customer --*/
  $("#bulan").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(bulan, extractLast(request.term)));
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

  /*-- Autocomplete Input Customer --*/
  $("#tahun").on("keydown", function(event) {
    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
      event.preventDefault();
    }
  })
  .autocomplete({
    minLength: 0,
    source: function(request, response) {
      // delegate back to autocomplete, but extract the last term
      response($.ui.autocomplete.filter(tahun, extractLast(request.term)));
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