<?php

namespace App\Actions\Kependudukan;

use App\Services\Kependudukan\DuplicateScannerService;
use Illuminate\Support\Collection;

class ScanDuplicateNikAction
{
    public function __construct(
        private readonly DuplicateScannerService $scanner
    ) {}

    /**
     * Execute full NIK scan and return grouped result for display.
     *
     * @return array{duplicates: Collection, anomali: Collection, total_issues: int}
     */
    public function handle(): array
    {
        return $this->scanner->fullScan();
    }
}
