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
          <th>NO</th>
          <th>SALES</th>
          <th>DETAIL</th>
          @foreach($jenis as $j)
            <th>{{$j->nama}}</th>
          @endforeach
          <th>Grand Total</th>
        </tr>
      </thead>
      <tbody id="tablePO">
        @php 
          $no = 1; $subRevenue = 0; $subHPP = 0; $subRetur = 0; $subLaba = 0;
        @endphp
        @forelse($sales as $s)
          <tr>
            <td rowspan="4" align="center">{{ $no }}</td>
            <td rowspan="4" align="center">{{ $s->nama }}</td>
            <td align="center">Revenue</td>
            @php $total = 0; @endphp
            @foreach($jenis as $j)
              @php $cek = 0; @endphp
              <td align="right">
              @foreach($items as $i)
                @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                  {{ $i->total }}
                  @php $total += $i->total; $cek = 1; @endphp
                  @break
                @endif
              @endforeach
              </td>
            @endforeach
            <td align="right">{{ $total }}</td>
            @php $subRevenue += $total @endphp
          </tr>\
          <tr>
            <td align="center">HPP</td>
            @foreach($jenis as $j)
              <td align="right"></td>
            @endforeach
            <td align="right"></td>
          </tr>
          <tr>
            <td align="center">Retur</td>
            @php $ret = 0; $arrRet = []; $k = 0; @endphp
            @foreach($jenis as $j)
              @php $cekRetur = 0; @endphp
              <td align="right">
                @foreach($retur as $r)
                  @if(($r->id_sales == $s->id) && ($r->id_kategori == $j->id))
                    {{ $r->total }}
                    @php 
                      $ret += $r->total; $arrRet[$k] = $r->total; $cekRetur = 1;
                    @endphp
                    @break
                  @else
                    @php $arrRet[$k] = 0; @endphp
                  @endif
                @endforeach
                @if($retur->count() == 0)
                  @php $arrRet[$k] = 0; @endphp
                @endif
                @php $k++ @endphp
              </td>
            @endforeach
            <td align="right">{{ $ret }}</td>
            @php $subRetur += $ret; @endphp
          </tr>
          <tr>
            <td align="center">Laba</td>
            @php $laba = 0; $k = 0; @endphp
            @foreach($jenis as $j)
              @php $cekLaba = 0; @endphp 
              <td align="right">
              @foreach($items as $i)
                @if(($i->id_sales == $s->id) && ($i->id_kategori == $j->id))
                  {{ $i->total - $arrRet[$k] }}
                  @php $laba += ($i->total - $arrRet[$k]) @endphp
                @endif
              @endforeach
              @php $k++ @endphp
              </td>
            @endforeach
            <td align="right">{{ $laba }}</td>
            @php $subLaba += $laba; @endphp
          </tr>
          @php $no++ @endphp
        @empty
          <tr>
            <td colspan="12"><i>Belum Ada Laporan Keuangan</i></td>
          </tr>
        @endforelse
        @php $subLaba -= $diskon->first()->diskon; @endphp
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Total Revenue</td>
          <td align="right">{{ $subRevenue - $diskon->first()->diskon }}</td>
        </tr>
        @foreach($kat as $k)
          <tr>
            <td colspan="{{ $jenis->count() + 3 }}" align="right">Total HPP {{ $k->barang->jenis->nama }}</td>
            <td align="right"></td>
          </tr>
        @endforeach
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Total Retur</td>
          <td align="right"></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Total Laba</td>
          <td align="right"></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Pendapatan Lain-Lain</td>
          <td></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Total Laba dan Pendapatan</td>
          <td align="right"></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Beban Gaji</td>
          <td align="right"></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Beban Penjualan</td>
          <td align="right"></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Beban Lain-Lain</td>
          <td align="right"></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Petty Cash</td>
          <td align="right"></td>
        </tr>
        <tr>
          <td colspan="{{ $jenis->count() + 3 }}" align="right">Grand Total</td>
          <td align="right"></td>
        </tr>
      </tbody>
    </table>
    <br>
    <!-- End Tabel Data Detil PO -->
    <h4>Copyright &copy; {{$sejak}} @if($tahun->year != $sejak) - {{$tahun->year}} @endif | rakhaadrida</h4>
  </body>
</html>
