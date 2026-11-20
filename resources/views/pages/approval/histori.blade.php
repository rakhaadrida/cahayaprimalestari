@extends('layouts.admin')

@push('addon-style')
	<link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
	<link href="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
	<div class="container-fluid">
		<div class="d-sm-flex align-items-center justify-content-between mb-2">
			<h1 class="h3 mb-0 text-gray-800 menu-title">Histori Approval</h1>
		</div>
		<div class="row">
			<div class="card-body">
				<div class="table-responsive">
					<div class="card show">
						<div class="card-body">
							<form action="">
								@csrf
								<div class="container so-container">
									<div class="form-group row justify-content-center" >
										<label for="kode" class="col-auto col-form-label text-bold">Tanggal</label>
										<span class="col-form-label text-bold">:</span>
										<div class="col-2">
											<input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAwal" id="tglAwal" value="{{ $startDate }}" autocomplete="off" autofocus>
										</div>
										<label for="tanggal" class="col-auto col-form-label text-bold ">s/d</label>
										<div class="col-2">
											<input type="text" class="form-control datepicker form-control-sm text-bold mt-1" name="tglAkhir" id="tglAkhir" value="{{ $endDate }}" autocomplete="off">
										</div>
										<div class="col-1 mt-1" style="margin-left: -10px">
											<button type="submit" id="btn-cari" formaction="{{ route('app-histori') }}" formmethod="GET" class="btn btn-primary btn-sm btn-block text-bold">Cari</button>
										</div>
									</div>
								</div>
								<hr>
								<table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" id="dataTable" width="100%" cellspacing="0">
								<thead class="text-center text-bold text-dark">
									<tr align="center">
									<th>No</th>
									<th>No. Transaksi</th>
									<th>Tanggal Approval</th>
									<th>Customer / Supplier</th>
									<th>Status</th>
									<th>Keterangan</th>
									<th>Detail</th>
									</tr>
								</thead>
								<tbody>
									@php $i=1; @endphp
									@forelse($items as $item)
										<tr class="text-dark">
											<td class="align-middle" align="center">{{ $i }}</td>
											<td class="align-middle" align="center">{{ $item->id_dokumen }}</td>
											<td class="align-middle" align="center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}</td>
											@if($item->tipe == 'Faktur') 
												<td class="align-middle" align="center">{{ $item->so->customer->nama }}</td>
											@elseif($item->tipe == 'Dokumen') 
												<td class="align-middle" align="center">{{ $item->bm->supplier->nama }}</td>
											@elseif($item->tipe == 'Transfer') 
												<td class="align-middle" align="center">TRANSFER BARANG</td>
											@elseif($item->tipe == 'RJ') 
												<td class="align-middle" align="center">{{ $item->rj->customer->nama }}</td>
											@elseif($item->tipe == 'RB') 
												<td class="align-middle" align="center">{{ $item->rb->supplier->nama }}</td>
											@endif
											<td class="align-middle" align="center">{{ $item->status }}</td>
											<td class="align-middle">{{ $item->keterangan }}</td>
											<td class="align-middle" align="center">
												<a href="{{ route('app-detail', $item->id_dokumen) }}" class="btn btn-success btn-sm"><i class="fas fa-fw fa-eye"></i></a>
											</td>
										</tr>
										@php $i++; @endphp
									@empty
										<tr>
											<td colspan="7" class="text-center">Tidak Ada Histori Approval</td>
										</tr>
									@endforelse
								</tbody>
								</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('addon-script')
	<script src="{{ url('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
	<script src="{{ url('backend/js/demo/datatables-demo.js') }}"></script>
	<script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
	<script type="text/javascript">
		$.fn.datepicker.dates['id'] = {
            days:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],
            daysShort:["Mgu","Sen","Sel","Rab","Kam","Jum","Sab"],
            daysMin:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
            months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
            monthsShort:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"],
            today:"Hari Ini",
            clear:"Kosongkan"
        };

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'id',
        });

		$('#dataTable').dataTable({
			"pageLength": 100
		});

		const tglAwal = document.getElementById('tglAwal');
        const tglAkhir = document.getElementById('tglAkhir');

        tglAwal.addEventListener("keyup", formatTanggal);
        tglAkhir.addEventListener("keyup", formatTanggal);

        function formatTanggal(e) {
            var value = e.target.value.replaceAll("-","");
            var arrValue = value.split("", 3);
            var kode = arrValue.join("");

            if(value.length > 2 && value.length <= 4)
                value = value.slice(0,2) + "-" + value.slice(2);
            else if(value.length > 4 && value.length <= 8)
                value = value.slice(0,2) + "-" + value.slice(2,4) + "-" + value.slice(4);

            if(e.target.id == 'tglAwal')
                tglAwal.value = value;
            else
                tglAkhir.value = value;
        }
	</script>
@endpush