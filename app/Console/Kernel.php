<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\GoogleFonts\Commands\FetchGoogleFontsCommand;

final class Kernel extends ConsoleKernel
{
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(Commands\GenerateSitemap::class)->daily();
        $schedule->command(FetchGoogleFontsCommand::class)->weekly();
    }
}
