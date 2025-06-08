<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ActivatePendingReservations;

class DispatchActivatePendingReservations extends Command
{
    protected $signature = 'reservations:activate';
    protected $description = 'Dispatch job to activate pending reservations whose start date is today';

    public function handle()
    {
        ActivatePendingReservations::dispatch();
        $this->info('ActivatePendingReservations job dispatched!');
    }
}
