@foreach($items as $item)
<div class="modal fade" id="DetailCustomer{{ $item->id }}" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="row">
          <div class="col-10">
            <h4 class="modal-title" style="font-size: 18px"><i class="fa fa-user fa-fw"></i> Detail Customer <b>{{ $item->nama }}</b></h4>
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
        <form method="POST" action="">
          <table class="table table-responsive table-md table-modal">
            <tbody>
              <tr class="table-modal-first-row">
                <td width="200px" class="text-bold">Kode Customer</td>
                <td class="text-bold">:</td>
                <td><b></b>{{ $item->id }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">Nama Customer</td>
                <td class="text-bold">:</td>
                <td>{{ $item->nama }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">Alamat</td>
                <td class="text-bold">:</td>
                <td width="300px">{{ $item->alamat }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">Telepon</td>
                <td class="text-bold">:</td>
                <td>{{ $item->telepon }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">Contact Person</td>
                <td class="text-bold">:</td>
                <td>{{ $item->contact_person }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">NPWP</td>
                <td class="text-bold">:</td>
                <td>{{ $item->npwp }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">Limit</td>
                <td class="text-bold">:</td>
                <td>{{ number_format($item->limit, 0, "", ".") }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">Tempo</td>
                <td class="text-bold">:</td>
                <td>{{ $item->tempo }}</td>
              </tr>
              <tr>
                <td width="200px" class="text-bold">Sales Cover</td>
                <td class="text-bold">:</td>
                <td>
                  @if($item->id_sales != '') {{ $item->sales->nama }} @else - @endif
                </td>
              </tr>
              {{-- <tr>
                <td width="200px" class="text-bold">KTP</td>
                <td class="text-bold">:</td>
                <td>
                  @if($item->ktp != NULL) <img src="{{ url($item->ktp) }}" alt="">@endif
                  <img src="{{ url($item->ktp) }}" alt="">
                  <img src="{{ $item->ktp != NULL ? url($item->ktp) : url('') }}" alt="">
                  {{ $item->ktp != NULL ? $item->ktp : '' }}
                </td>
              </tr> --}}
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach