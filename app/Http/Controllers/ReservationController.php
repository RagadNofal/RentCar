<?php

namespace App\Http\Controllers;

use App\Models\{Car, Reservation, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\ReservationMadeNotification;
use App\Models\User;

class ReservationController extends Controller
{
   

  public function create($car_id)
{
    $car = Car::findOrFail($car_id);
    $user = Auth::user();

    // Add discount info like in index()
    $discount = $car->getApplicableDiscount(now());
    $car->original_price = $car->price_per_day;

    if ($discount) {
        $car->reduce = $discount->amount;
        $car->discount = $discount;
        $car->final_price = $car->price_per_day - ($car->price_per_day * $discount->amount / 100);
    } else {
        $car->reduce = 0;
        $car->discount = null;
        $car->final_price = $car->price_per_day;
    }

    // Reserved date ranges
    $reservedRanges = $car->reservations()
        ->where('status', '!=', 'cancelled')
        ->get()
        ->map(function ($res) {
            return [
                'from' => Carbon::parse($res->start_date)->toDateString(),
                'to'   => Carbon::parse($res->end_date)->toDateString(),
            ];
        });

    return view('reservation.create', compact('car', 'user', 'reservedRanges'));
}


    public function store(Request $request, $car_id)
{
    $request->validate([
        'reservation_dates' => 'required|string',
        'pickup_location' => ['required', 'string'],
        'dropoff_location' => ['required', 'string'],
    ]);

    DB::beginTransaction();

    try {
        $car = Car::where('id', $car_id)->lockForUpdate()->firstOrFail();
        $user = Auth::user();

        [$start_date, $end_date] = explode(' to ', $request->reservation_dates);
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);

        if ($start->lt(Carbon::today()) || $end->lte($start)) {
            return back()->with('error', 'Please select valid reservation dates.');
        }

        // Check for exact duplicate reservation if the car quantity is 1
if ($car->quantity == 1) {
    $hasExactDuplicate = Reservation::where('user_id', $user->id)
        ->where('car_id', $car->id)
        ->whereDate('start_date', $start->toDateString())
        ->whereDate('end_date', $end->toDateString())
        ->whereIn('status', [Reservation::STATUS_PENDING, Reservation::STATUS_ACTIVE])
        ->exists();

    if ($hasExactDuplicate) {
        return back()->with('error', 'You already have a reservation for this exact car and date.');
    }
}

        // Check how many cars are already booked for this date range
        $overlappingCount = Reservation::where('car_id', $car->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($query) use ($start, $end) {
                          $query->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                      });
            })
            ->whereIn('status', [Reservation::STATUS_PENDING, Reservation::STATUS_ACTIVE])
            ->count();

        if ($overlappingCount >= $car->quantity) {
            return back()->with('error', 'No cars are available for the selected dates.');
        }

        $days = $start->diffInDays($end) + 1;
        $total = $days * $car->price_per_day;

        $status = $start->lte(Carbon::now()) ? Reservation::STATUS_ACTIVE : Reservation::STATUS_PENDING;

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'start_date' => $start,
            'end_date' => $end,
            'days' => $days,
            'price_per_day' => $car->price_per_day,
            'total_price' => $total,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'status' => $status,
        ]);

        DB::commit();
$user->notify(new ReservationMadeNotification($reservation));
        return redirect()->route('payment.create', ['reservation' => $reservation->id]);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong while processing your reservation.');
    }
}

    public function myReservations()
    {
        $reservations = Reservation::with('car', 'payment')
            ->where('user_id', Auth::id())
            ->get();

        return view('clientReservations', compact('reservations'));
    }

    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (Carbon::now()->gte($reservation->start_date)) {
            return back()->with('error', 'You cannot cancel a reservation that has already started.');
        }

        $reservation->update(['status' => Reservation::STATUS_CANCELED]);
        $reservation->payment->update(['payment_status' => Payment::STATUS_CANCELED]);
        $reservation->car->update(['status' => 'Available']);

        return back()->with('success', 'Reservation canceled successfully.');
    }

    public function editStatus(Reservation $reservation)
    {
        return view('admin.updateStatus', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate(['status' => 'required|in:Active,Pending,Canceled']);
        $reservation->update(['status' => $request->status]);

        if ($request->status === Reservation::STATUS_CANCELED) {
            $reservation->car->update(['status' => 'Available']);
            $reservation->payment->update(['payment_status' => Payment::STATUS_CANCELED]);
        }

        return redirect()->route('adminDashboard')->with('success', 'Reservation status updated.');
    }
}
