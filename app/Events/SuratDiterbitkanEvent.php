<?php

namespace App\Events;

use App\Models\SuratArsip;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SuratDiterbitkanEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public SuratArsip $suratArsip
    ) {}
}
