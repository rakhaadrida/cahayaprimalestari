@foreach($items as $item)
<div class="modal fade" id="DetailBarang{{ $item->id }}" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content ">
      <div class="modal-header">
        <h4 class="modal-title text-dark" style="font-size: 20px"><i class="fa fa-box fa-fw"></i><b>  Detail Barang {{ $item->nama }}</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
            
            <table class="table table-responsive" border="0">
              <tbody>
                <tr>
                  <td width="150px" class="text-dark">Kode Barang</td>
                  <td>:</td>
                  <td class="text-bold">{{ $item->id }}</td>
                </tr>
                <tr>
                  <td width="150px" class="text-dark">Nama Barang</td>
                  <td>:</td>
                  <td class="text-bold">{{ $item->nama }}</td>
                </tr>
                <tr>
                  <td width="150px" class="text-dark">Ukuran</td>
                  <td>:</td>
                  <td class="text-bold">{{ $item->ukuran }}</td>
                </tr>
                <tr>
                  <td width="150px" class="text-dark">Isi</td>
                  <td>:</td>
                  <td class="text-bold">{{ $item->isi }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="text-bold text-dark">Data Harga</td>
                </tr>
                @foreach($harga as $h)
                  <tr>
                    <td width="100px" class="text-dark">{{ $h->nama }}</td>
                    <td>:</td>
                    @foreach($hargaBarang as $hb)
                      @if(($hb->id_harga == $h->id) && ($hb->id_barang == $item->id))
                        <td class="text-bold">{{ $hb->harga }}</td>
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