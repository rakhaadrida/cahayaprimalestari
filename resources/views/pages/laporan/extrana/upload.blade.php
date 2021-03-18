@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-2">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Upload File Data Komisi</h1>
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
            <form action="{{ route('komisi-store-upload') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="container" style="margin-bottom: -10px">
                <div class="row justify-content-center">
                  <h5 class="text-bold text-dark">
                    Data File Excel Komisi Sales Fadil per Bulan
                  </h5>
                </div>
              </div>
              <hr>
              @for($i = 0; $i < 12; $i++)
                @php
                  $j = $i + 1;
                  $tanggal = $tahun.'-'.$j.'-01';
                  $item = \App\Models\Komisi::where('bulan', $i+1)->get();
                @endphp
                <div class="form-group row" style="margin-top: 0px">
                  <label for="komisi" class="col-2 col-form-label text-bold">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('MMMM') }}</label>
                  <span class="col-form-label text-bold" style="margin-left: -60px">:</span>
                  <div class="col-3">
                    <input type="file" name="{{$i}}" class="form-control col-form-label-sm @error('komisi') is-invalid @enderror">
                    @error('komisi') <div class="text-muted">{{ $message }}</div> @enderror
                  </div>
                  <a href="{{ route('komisi-download', $i+1) }}" class="col-form-label text-primary">{{ $item->count() != 0 ? substr($item->first()->file, 14) : '' }}</a>
                </div>
              @endfor
              <hr>
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" class="btn btn-success btn-block text-bold">Submit</button>
                </div>
                <div class="col-2">
                  <button type="reset" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                </div>
                <div class="col-2">
                  <a href="{{ route('komisi') }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                </div>
              </div>
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

</script>
@endpush