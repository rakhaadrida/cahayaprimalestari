@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      @if($message = Session::get('simpanPassword'))
        <div class="alert alert-success alert-block">
          <button type="button" class="close btn-sm" data-dismiss="alert" 
          style="margin-top: -8px">x</button>
          <strong>{{ $message }} </strong>
        </div>
      @endif
      <div class="card">
        <div class="card-header text-dark text-bold">Ganti Password</div>
        <div class="card-body">
          <form method="" action="">
            @csrf
            <div class="form-group row">
              <label for="oldPassword" class="col-md-4 col-form-label text-md-right">{{ __('Password Lama') }}</label>
              <div class="col-md-6">
                <input id="oldPassword" type="text" class="form-control @error('oldPassword') is-invalid @enderror" name="oldPassword" value="{{ old('oldPassword') }}" required autocomplete="oldPassword" autofocus>
                @error('oldPassword')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="newPassword" class="col-md-4 col-form-label text-md-right">{{ __('Password Baru') }}</label>
              <div class="col-md-6">
                <input id="newPassword" type="password" class="form-control @error('newPassword') is-invalid @enderror" name="newPassword" required autocomplete="newPassword">
                @error('newPassword')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row">
              <label for="confirmPassword" class="col-md-4 col-form-label text-md-right">{{ __('Konfirmasi Password') }}</label>
              <div class="col-md-6">
                <input id="confirmPassword" type="password" class="form-control @error('confirmPassword') is-invalid @enderror" name="confirmPassword" required autocomplete="confirmPassword" data-toogle="tooltip" data-placement="bottom" title="Password tidak sesuai dengan password baru">
                @error('confirmPassword')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="form-group row mb-0">
              <div class="col-md-6 offset-md-4">
                <button type="submit" id="submitPass" class="btn btn-primary" 
                onclick="return checkPassword(event)">Simpan</button>
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
const newPass = document.getElementById('newPassword');
const confirmPass = document.getElementById('confirmPassword');

function checkPassword(e) {
  if(newPass.value != confirmPass.value) {
    $(confirmPass).tooltip('show');
    newPass.style.borderColor = "red";
    newPass.style.borderWidth = "2px";
    confirmPass.style.borderColor = "red";
    confirmPass.style.borderWidth = "2px";

    return false;
  }
  else { 
    document.getElementById("submitPass").formMethod = "POST";
    document.getElementById("submitPass").formAction = "{{ route('user-process') }}";
  }
}

</script>
@endpush
