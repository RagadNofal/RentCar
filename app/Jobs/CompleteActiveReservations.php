<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\Reservation;
class CompleteActiveReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
   public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();

        // Find all reservations that ended yesterday and are not yet completed or cancelled
        $reservations = Reservation::where('status', '!=', 'Completed')
            ->where('status', '!=', 'Cancelled')
            ->whereDate('end_date', $yesterday)
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->update(['status' => 'Completed']);
        }
    }
}
