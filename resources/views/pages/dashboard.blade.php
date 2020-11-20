@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0 text-gray-800 menu-title">Dashboard</h1>
    {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              @if(Auth::user()->roles == 'SUPER')
                <div class="text-md font-weight-bold text-primary text-uppercase mb-2">Penjualan (Tahun)</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($salesAnnual[0]->sales, 0, "", ".") }}</div>
              @elseif(Auth::user()->roles == 'ADMIN')
                <div class="text-md font-weight-bold text-primary text-uppercase mb-2">Transaksi (Total)</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transMonthly }}
                ({{ $transAnnual }})</div>
              @endif
            </div>
            <div class="col-auto">
              @if(Auth::user()->roles == 'SUPER')
                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
              @elseif(Auth::user()->roles == 'ADMIN')
                <i class="fas fa-check fa-2x text-gray-300"></i>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              @if(Auth::user()->roles == 'SUPER')
                <div class="text-md font-weight-bold text-success text-uppercase mb-2">Penjualan (Bulan)</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($salesMonthly[0]->sales, 0, "", ".") }}</div>
              @elseif(Auth::user()->roles == 'ADMIN') 
                <div class="text-md font-weight-bold text-success text-uppercase mb-2">Faktur Belum Dicetak</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $needPrint }}</div>
              @endif
            </div>
            <div class="col-auto">
              @if(Auth::user()->roles == 'SUPER')
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              @elseif(Auth::user()->roles == 'ADMIN')
                <i class="fas fa-print fa-2x text-gray-300"></i>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              @if(Auth::user()->roles == 'SUPER')
                <div class="text-md font-weight-bold text-info text-uppercase mb-2">Transaksi (Total)</div>
                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $transMonthly }}
                ({{ $transAnnual }})</div>
              @elseif(Auth::user()->roles == 'ADMIN')
                <div class="text-md font-weight-bold text-info text-uppercase mb-2">Faktur Pending</div>
                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $stillPending }}</div>
              @endif
            </div>
            <div class="col-auto">
              @if(Auth::user()->roles == 'SUPER')
                <i class="fas fa-check fa-2x text-gray-300"></i>
              @elseif(Auth::user()->roles == 'ADMIN')
                <i class="fas fa-spinner fa-2x text-gray-300"></i>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              @if(Auth::user()->roles == 'SUPER')
                <div class="text-md font-weight-bold text-warning text-uppercase mb-2">Total Piutang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($receivable, 0, "", ".") }}</div>
              @elseif(Auth::user()->roles == 'ADMIN')
                <div class="text-md font-weight-bold text-warning text-uppercase mb-2">Re-stok Barang</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($receivable, 0, "", ".") }}</div>
              @endif
            </div>
            <div class="col-auto">
              @if(Auth::user()->roles == 'SUPER')
                <i class="fas fa-donate fa-2x text-gray-300"></i>
              @elseif(Auth::user()->roles == 'ADMIN')
                <i class="fas fa-recycle fa-2x text-gray-300"></i>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          @if(Auth::user()->roles == 'SUPER')
            <h6 class="m-0 font-weight-bold text-dark">Grafik Penjualan</h6>
          @elseif(Auth::user()->roles == 'ADMIN')
            <h6 class="m-0 font-weight-bold text-dark">Transaksi Terakhir</h6>
          @endif
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-area">
            <canvas id="myAreaChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          @if(Auth::user()->roles == 'SUPER')
            <h6 class="m-0 font-weight-bold text-dark">Tipe Transaksi</h6>
          @elseif(Auth::user()->roles == 'ADMIN')
            <h6 class="m-0 font-weight-bold text-dark">Status Faktur</h6>
          @endif
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-1 pb-1">
            <canvas id="myPieChart"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Cash
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Extrana
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-info"></i> Prime
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->

@endsection

@push('addon-script')
<script src="{{ url('backend/vendor/chart.js/Chart.min.js') }}"></script>
<script type="text/javascript">
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';
Chart.defaults.global.defaultFontSize = '14';

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

// Area Chart
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
    datasets: [{
      label: "Penjualan",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      data: ['{{$arrTotal[0]}}', '{{$arrTotal[1]}}', '{{$arrTotal[2]}}', '{{$arrTotal[3]}}', '{{$arrTotal[4]}}', '{{$arrTotal[5]}}', '{{$arrTotal[6]}}', '{{$arrTotal[7]}}', '{{$arrTotal[8]}}', '{{$arrTotal[9]}}', '{{$arrTotal[10]}}', '{{$arrTotal[11]}}'],
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: -5,
        right: 25,
        top: 0,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 12
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return 'Rp ' + number_format(value);
          }
        },
        gridLines: {
          color: "rgb(0, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          borderWidth: 2,
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ': Rp ' + number_format(tooltipItem.yLabel);
        }
      }
    }
  }
});

// Pie Chart
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Prime", "Extrana", "Cash"],
    datasets: [{
      data: ['{{$salesPerType[2]->total}}', '{{$salesPerType[1]->total}}', '{{$salesPerType[0]->total}}'],
      backgroundColor: ['#36b9cc', '#1cc88a', '#4e73df'],
      hoverBackgroundColor: ['#2c9faf', '#17a673', '#2e59d9'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false,
    },
    cutoutPercentage: 80,
  },
});
</script>
@endpush