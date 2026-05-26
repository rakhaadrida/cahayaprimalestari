<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <style>
        body {
            margin: 0;
            font-family: "Nunito", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #858796;
            text-align: left;
            background-color: #fff;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-bottom: 0.5rem;
            font-weight: 400;
            line-height: 1.2;
        }

        h5 {
            font-size: 1.25rem;
        }

        .text-center {
            text-align: center !important;
        }

        .text-bold {
            font-weight: bold
        }

        .text-dark {
            color: #292a2b !important;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        table {
            border-collapse: collapse;
            font-size: 8px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #858796;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #000000;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 1px solid #000000;
        }

        .table tbody+tbody {
            border-top: 1px solid #000000;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #000000;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000000;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .cetak-rekap-all {
            margin-bottom: -25px;
        }

        .title-rekap {
            margin-top: -30px;
        }

        .waktu-cetak {
            font-size: 12px !important;
            margin-top: -10px !important;
        }

        .table-cetak {
            font-size: 11px;
        }

        .table-cetak th,
        .table-cetak td {
            padding-top: 0rem !important;
            padding-bottom: 0.1rem !important;
            border: 0.5px solid #292a2b !important;
            border-width: thin !important;
        }
    </style>
</head>
<body>
    <div class="cetak-rekap-all">
        <center>
            <div class="title-rekap-all">
                <h5 class="text-bold text-dark title-rekap">Transaksi Harian Toko</h5>
                <h6 class="text-dark waktu-cetak">Tanggal : {{ $startDate }} s/d {{ $finalDate }}</h6>
                <h6 class="text-dark waktu-cetak">Waktu Cetak : {{ $printTime }}</h6>
            </div>
        </center>
        <table class="table table-sm table-bordered table-cetak" style="page-break-inside: always">
            <thead class="text-center text-dark text-bold" >
            <tr>
                <th style="width: 10px" class="align-middle">No</th>
                <th style="width: 50px" class="align-middle">No. Faktur</th>
                <th style="width: 50px" class="align-middle">Tgl. Faktur</th>
                <th style="width: 60px" class="align-middle">Total</th>
                <th style="width: 130px" class="align-middle">Keterangan</th>
            </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($items as $item)
                    <tr class="text-dark">
                        <td align="center" class="align-middle">{{ $i }}</td>
                        <td align="center" class="align-middle">{{ $item->nomor }}</td>
                        <td align="center" class="align-middle">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-M-y') }}
                        </td>
                        <td align="right" class="align-middle">
                            {{ number_format($item->total, 0, "", ",") }}
                        </td>
                        <td></td>
                    </tr>
                    @php $i++ @endphp
                @endforeach
            </tbody>
        </table>
    </div>
    </body>
</html>
