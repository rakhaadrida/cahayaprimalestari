<html>
  <body>
    <center>
      <h2 class="text-bold text-dark">Laporan Keuangan {{ $bul }}</h2>
      <h5 class="waktu-cetak">Waktu Cetak : {{$waktu}}</h5>
      
    </center>
    <br>

    <!-- Tabel Data Detil BM-->
    <table class="table table-sm table-bordered table-cetak">
      <thead class="text-center text-dark text-bold" style="background-color: lightgreen">
        <tr style="font-size: 14px">
          {{-- <th>NO</th> --}}
          <th>SALES</th>
          @foreach($jenis as $j)
            <th>{{$j->nama}}</th>
          @endforeach
          <th>RETUR</th>
          <th>Grand Total</th>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php 
          $no = 1; $subRevenue = 0; $subHPP = 0; $subRetur = 0; $subLaba = 0;
        @endphp
        @forelse($sales as $s)
          <tr>
            {{-- <td rowspan="2" align="center">{{ $no }}</td> --}}
            <td rowspan="2" align="center">{{ $s->nama }}</td>
            @php $total = 0; @endphp
            @foreach($jenis as $j)
              @php $cek = 0; @endphp
              <td rowspan="2" align="right">
                @foreach($items as $i)
                  @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                    {{ $i->total }}
                    @php $total += $i->total; $cek = 1; @endphp
                    @break
                  @endif
                @endforeach
              </td>
            @endforeach
            <td rowspan="2" align="right">
              @foreach($retur as $r)
                @if($r->id_sales == $s->id)
                  {{ $r->total }}
                  @php $total -= $r->total; $cek = 1; @endphp
                  @break
                @endif
              @endforeach
            </td>
            <td rowspan="2" align="right">{{ $total }}</td>
            @php $subRevenue += $total @endphp
          </tr>
          <tr></tr>
          @php $no++ @endphp
        @empty
          <tr>
            <td colspan="12" rowspan="2"><i>Belum Ada Laporan Keuangan</i></td>
          </tr>
        @endforelse
        @php $subLaba -= $diskon->first()->diskon; @endphp
        <tr>
          <td rowspan="2" align="center">Grand Total</td>
          <td rowspan="2" align="right">{{ $subRevenue - $diskon->first()->diskon }}</td>
          @foreach($jenis as $j)
            <td rowspan="2" align="right"></td>
          @endforeach
          <td rowspan="2" align="right"></td>
          <td rowspan="2" align="right"></td>
        </tr>
        <tr></tr>
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
