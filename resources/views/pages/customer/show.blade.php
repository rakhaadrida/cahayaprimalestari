@foreach($items as $item)
<div class="modal fade" id="DetailCustomer{{ $item->id }}" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="font-size: 20px"><i class="fa fa-user fa-fw"></i><b>  Detail Customer {{ $item->nama }}</b></h4>
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
        <form method="POST" action="" enctype="multipart/form-data">
          <table class="table table-responsive" border="0">
            <tbody>
              <tr>
                <td width="200px">Kode Customer</td>
                <td>:</td>
                <td><b></b>{{ $item->id }}</td>
              </tr>
              <tr>
                <td width="200px">Nama Customer</td>
                <td>:</td>
                <td>{{ $item->nama }}</td>
              </tr>
              <tr>
                <td width="200px">Alamat</td>
                <td>:</td>
                <td>{{ $item->alamat }}</td>
              </tr>
              <tr>
                <td width="200px">Telepon</td>
                <td>:</td>
                <td>{{ $item->telepon }}</td>
              </tr>
              <tr>
                <td width="200px">Contact Person</td>
                <td>:</td>
                <td>{{ $item->contact_person }}</td>
              </tr>
              <tr>
                <td width="200px">Tempo</td>
                <td>:</td>
                <td>{{ $item->tempo }}</td>
              </tr>
              <tr>
                <td width="200px">Limit</td>
                <td>:</td>
                <td>{{ $item->limit }}</td>
              </tr>
              <tr>
                <td width="200px">Sales Cover</td>
                <td>:</td>
                <td>{{ $item->sales_cover }}</td>
              </tr>
            </tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach