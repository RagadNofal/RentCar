<?php

namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivatePendingReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public function handle()
{
    $today = Carbon::today('Asia/Amman');
   $reservations = Reservation::where('status', 'Pending')
    ->whereDate('start_date', '<=',$today)
    ->get();


    foreach ($reservations as $reservation) {
          
        $reservation->update(['status' => 'Active']);
    }
}
}
