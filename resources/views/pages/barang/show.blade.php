@foreach($items as $item)
<div class="modal fade" id="DetailBarang{{ $item->id }}" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content ">
      <div class="modal-header">
        <div class="row">
          <div class="col-10">
            <h6 class="modal-title" style="font-size: 18px"><i class="fa fa-user fa-fw"></i>  Detail Barang <b>{{ $item->nama }}</b></h6>
          </div>
          <div class="col-2">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
        </div>
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

      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            {{-- <div class="form-group row">
              <label for="kode" class="col-2 col-form-label text-bold ">Kode Barang</label>
              <span class="col-form-label text-bold">:</span>
              <div class="col-2">{{ $item->id }}</div>
            </div> --}}
            
            <table class="table table-responsive table-md table-modal">
              <tbody>
                <tr class="table-modal-first-row">
                  <td width="150px" class="text-bold">Kode Barang</td>
                  <td>:</td>
                  <td width="280px">{{ $item->id }}</td>
                </tr>
                <tr>
                  <td width="150px" class="text-bold">Nama Barang</td>
                  <td>:</td>
                  <td>{{ $item->nama }}</td>
                </tr>
                <tr>
                  <td width="150px" class="text-bold">Satuan</td>
                  <td>:</td>
                  <td>{{ $item->satuan }}</td>
                </tr>
                <tr>
                  <td width="150px" class="text-bold">Ukuran</td>
                  <td>:</td>
                  <td>{{ $item->ukuran }}  {{ $item->satuan }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="text-bold text-center bg-success text-white">Data Harga</td>
                </tr>
                @foreach($harga as $h)
                  <tr>
                    <td width="100px" class="text-bold">{{ $h->nama }}</td>
                    <td>:</td>
                    @foreach($hargaBarang as $hb)
                      @if(($hb->id_harga == $h->id) && ($hb->id_barang == $item->id))
                        <td>{{ number_format($hb->harga_ppn, 0, "", ".") }}</td>
                        @break
                      @endif
                    @endforeach
                  </tr>
                @endforeach
                <tr>
                  <td colspan="3" class="text-bold text-center bg-success text-white">Data Stok</td>
                </tr>
                @foreach($gudang as $g)
                  <tr>
                    <td width="100px" class="text-bold">{{ $g->nama }}</td>
                    <td>:</td>
                    @foreach($stok as $s)
                      @if(($s->id_gudang == $g->id) && ($s->id_barang == $item->id))
                        <td>{{ $s->stok }} <b> @if($item->satuan == "Pcs / Pack") Pcs @elseif($item->satuan == "Meter / Rol") Rol @endif </b></td>
                        @break
                      @endif
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          {{-- <div class="col">
            <table class="table table-responsive" border="0">
              <tbody>
                <tr>
                  <td colspan="3" class="text-bold text-dark">Data Stok</td>
                </tr>
                @foreach($gudang as $g)
                  <tr>
                    <td width="100px" class="text-dark">{{ $g->nama }}</td>
                    <td>:</td>
                    @foreach($stok as $s)
                      @if(($s->id_gudang == $g->id) && ($s->id_barang == $item->id))
                        <td class="text-bold">{{ $s->stok }}</td>
                        @break
                      @endif
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> --}}
        </div>
        
      </div>
    </div>
  </div>
</div>
@endforeach