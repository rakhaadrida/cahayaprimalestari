@extends('pages.keuangan.detail')
@extends('layouts.admin')

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
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" name="tahun" id="tahun" autofocus>
                  </div>
                  <label for="bulan" class="col-auto col-form-label text-bold ">Bulan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2">
                    <input type="text" class="form-control form-control-sm text-bold mt-1" id="bulan" name="bulan">
                  </div>
                  <div class="col-1 mt-1" style="margin-left: -10px">
                    <button type="submit" formaction="{{ route('lap-keu-show') }}" formmethod="POST" id="btn-cari" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
                  </div>
                </div>   
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
                  @php $no = 1; $ret = 0; @endphp
                  @forelse($sales as $s)
                    <tr class="text-dark">
                      <td rowspan="4" align="center" class="align-middle" @if($no % 2 == 0) style="background-color: white" @endif>{{ $no }}</td>
                      <td rowspan="4" class="align-middle" @if($no % 2 == 0) style="background-color: white" @endif>{{ $s->nama }}</td>
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
                            <a href="#Detail{{ $i->id_kategori }}{{ $i->id_sales }}" class="btn btn-link btn-sm text-bold" data-toggle="modal" style="font-size: 14px; padding: 0px 0px;">
                              {{ number_format($i->hpp, 0, "", ",") }}
                            </a>
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
                    <tr class="text-dark">
                      <td align="center">Retur</td>
                      @php $hpp = 0; @endphp
                      @foreach($jenis as $j)
                        <td align="right" class="align-middle"></td>
                      @endforeach
                      <td align="right" class="align-middle"></td>
                      <td align="right" class="align-middle">
                        @if(($ret != $retur->count()) && ($retur[$ret]->id_sales == $s->id))
                          {{ number_format($retur[$ret]->total, 0, "", ",") }}
                        @else
                          0
                        @endif
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
                        @if(($ret != $retur->count()) && ($retur[$ret]->id_sales == $s->id))
                          {{ number_format($laba - $retur[$ret]->total, 0, "", ",") }}
                          @php $ret++; @endphp
                        @else
                          {{ number_format($laba, 0, "", ",") }}
                        @endif
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
<script type="text/javascript">
const tahun = document.getElementById('tahun');
const bulan = document.getElementById('bulan');

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