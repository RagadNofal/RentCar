<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CompleteActiveReservations;
class DispatchCompleteActiveReservations extends Command
{
   protected $signature = 'reservations:complete';
    protected $description = 'Dispatch job to complete reservations whose end date was yesterday';

    public function handle()
    {
        CompleteActiveReservations::dispatch();
        $this->info('CompleteEndedReservations job dispatched!');
    }
}
