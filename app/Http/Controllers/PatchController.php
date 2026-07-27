<?php

namespace App\Http\Controllers;

use App\Exports\CancelledOrderExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PatchController extends Controller
{
    public function cancelledOrder() {
        $date = Carbon::now()->toDateString();
        $fileDate = Carbon::parse($date)->format('d-M');

        return Excel::download(new CancelledOrderExport(), 'Faktur-Batal-'.$fileDate.'.xlsx');
    }
}
