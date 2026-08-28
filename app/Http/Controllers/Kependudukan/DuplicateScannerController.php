<?php

namespace App\Http\Controllers\Kependudukan;

use App\Actions\Kependudukan\ScanDuplicateNikAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DuplicateScannerController extends Controller
{
    /**
     * Show the NIK Duplicate Scanner page.
     */
    public function index(ScanDuplicateNikAction $action): View
    {
        $result = $action->handle();

        return view('kependudukan.duplicates', [
            'duplicates' => $result['duplicates'],
            'anomali' => $result['anomali'],
            'total_issues' => $result['total_issues'],
        ]);
    }
}
