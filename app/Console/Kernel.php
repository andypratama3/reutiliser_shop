<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SimulateConcurrentCheckout;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SimulateConcurrentCheckout::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        //
    }

    protected function commands(): void
    {
        //
    }
}
