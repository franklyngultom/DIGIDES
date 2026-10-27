<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal pembersihan retensi berkas & data kedaluwarsa sesuai Kebijakan Keamanan dan Retensi DIGIDES
Schedule::command('digides:prune-retention')->dailyAt('02:00');

