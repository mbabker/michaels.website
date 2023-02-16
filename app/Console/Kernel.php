<?php

namespace App\Console;

use App\Console\Commands\GenerateSitemap;
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
        $schedule->command(GenerateSitemap::class)->daily();
        $schedule->command(FetchGoogleFontsCommand::class)->weekly();
    }
}
