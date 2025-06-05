<?php

namespace App\Http\Controllers;

use App\Models\{Car, Reservation, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('car', 'user')->latest()->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function create($car_id)
    {
        $car = Car::findOrFail($car_id);
        $user = Auth::user();
        return view('reservation.create', compact('car', 'user'));
    }

   public function store(Request $request, $car_id)
{
    $request->validate([
        'reservation_dates' => 'required|string',
        'pickup_location' => 'required|string',
        'dropoff_location' => 'required|string',
    ]);

    $car = Car::findOrFail($car_id);
    $user = Auth::user();

    //  Block if car is unavailable
    if ($car->status !== Car::STATUS_AVAILABLE || $car->quantity < 1) {
        return back()->withErrors(['car_id' => 'This car is not available for reservation.']);
    }

    // // ✅ Limit active reservations to 2
    // $activeCount = Reservation::where('user_id', $user->id)
    //     ->where('status', Reservation::STATUS_ACTIVE)
    //     ->count();

    // if ($activeCount >= 2) {
    //     return back()->with('error', 'You cannot have more than 2 active reservations.');
    // }

    [$start_date, $end_date] = explode(' to ', $request->reservation_dates);
    $start = Carbon::parse($start_date);
    $end = Carbon::parse($end_date);

    if ($start->lt(Carbon::today()) || $end->lte($start)) {
        return back()->with('error', 'Please select valid reservation dates.');
    }

    //  Check for overlapping reservations
    $hasDuplicate = Reservation::where('user_id', $user->id)
        ->where('car_id', $car->id)
        ->where(function ($query) use ($start, $end) {
            $query->whereBetween('start_date', [$start, $end])
                  ->orWhereBetween('end_date', [$start, $end])
                  ->orWhere(function ($query) use ($start, $end) {
                      $query->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                  });
        })
        ->whereIn('status', [Reservation::STATUS_PENDING, Reservation::STATUS_ACTIVE])
        ->exists();

    if ($hasDuplicate) {
        return back()->with('error', 'You have already made a similar reservation for this car.');
    }

    //  Calculate price
    $days = $start->diffInDays($end) + 1;
    $total = $days * $car->price_per_day;

    // Create reservation
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
        'status' => Reservation::STATUS_PENDING,
    ]);


    $car->decrement('quantity');

    if ($car->quantity <= 0) {
        $car->update(['status' => Car::STATUS_UNAVAILABLE]);
    }

    return redirect()->route('payment.create', ['reservation' => $reservation->id]);
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

