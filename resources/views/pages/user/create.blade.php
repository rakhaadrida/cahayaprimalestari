@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-dark text-bold">Add New User</div>

        <div class="card-body">
          <form method="POST" action="{{ route('user.store') }}">
            @csrf

            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" onkeypress="return tanpaSpasi(event)" data-toogle="tooltip" data-placement="bottom" title="Tidak boleh ada spasi" required autocomplete="off" autofocus>

                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <label for="name" class="col-md-4 col-form-label text-md-right">
                Roles
              </label>

              <div class="col-md-6">
                <select class="custom-select mr-sm-2" name="roles">
                  <option selected>-- PILIH --</option>
                  <option value="SUPER">SUPER</option>
                  <option value="ADMIN">ADMIN</option>
                  <option value="AR">AR</option>
                  <option value="AP">AP</option>
                  <option value="GUDANG">GUDANG</option>
                  <option value="KENARI">KENARI</option>
                  <option value="OFFICE02">OFFICE02</option>
                </select>

                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Register</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.container-fluid -->
@endsection

@push('addon-script')
<script type="text/javascript">
const nama = document.getElementById('name');

function tanpaSpasi(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if(charCode == 32) {
    $(nama).tooltip('show');
    return false;
  }
}
</script>
@endpush
