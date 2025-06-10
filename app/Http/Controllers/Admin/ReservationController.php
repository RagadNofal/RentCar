<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
 public function index(Request $request) 
{
    $query = Reservation::with(['user', 'car', 'payment']); 

    if ($request->has('status') && $request->status != 'all') {
        $query->where('status', ucfirst($request->status));
    }

    if ($request->has('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->has('car_id')) {
        $query->where('car_id', $request->car_id);
    }

    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhereHas('user', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              })
              ->orWhereHas('car', function ($q) use ($search) {
                  $q->where('model', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
              });
        });
    }

    $reservations = $query->orderBy('created_at', 'desc')->paginate(10);

    $users = User::where('role', '!=', 'admin')->orderBy('name')->get();
    $cars = Car::orderBy('model')->get();
    $statuses = ['all', 'Pending', 'Active', 'Completed', 'Canceled'];

    return view('admin.reservations.index', compact('reservations', 'users', 'cars', 'statuses'));
}


    public function show(Reservation $reservation)
    {
        $reservation->load(['user', 'car','payment']);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $reservation->load(['user', 'car']);

       $users = User::where('role', '!=', 'admin')->orderBy('name')->get();

        $cars = Car::orderBy('model')->get();
        $statuses = ['Pending', 'Active', 'Completed', 'Canceled'];

        return view('admin.reservations.edit', compact('reservation', 'users', 'cars', 'statuses'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validatedData = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'car_id'        => 'required|exists:cars,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'status'        => 'required|in:Pending,Active,Completed,Canceled',
            'total_price'   => 'required|numeric|min:0',
        ]);

        if ($reservation->car_id != $validatedData['car_id']) {
            $oldCar = Car::find($reservation->car_id);
            if ($oldCar && $reservation->status === 'Active') {
                $oldCar->update(['status' => 'Available']);
            }

            $newCar = Car::find($validatedData['car_id']);
            if ($newCar && $validatedData['status'] === 'Active') {
                $newCar->update(['status' => 'Unavailable']);
            }
        } elseif ($reservation->status !== $validatedData['status']) {
            $car = Car::find($reservation->car_id);

            if ($car) {
                if ($validatedData['status'] === 'Active') {
                    $car->update(['status' => 'Unavailable']);
                } elseif (in_array($validatedData['status'], ['Completed', 'Canceled'])) {
                    $car->update(['status' => 'Available']);
                }
            }
        }

        $reservation->update($validatedData);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

   public function destroy(Reservation $reservation) 
{
    if ($reservation->status === 'Active') {
        $car = Car::find($reservation->car_id);

        if ($car) {
            // Increase quantity by 1
            $car->increment('quantity');

            // If car was unavailable and now has quantity, mark it as available
            if ($car->status === 'Unavailable' && $car->quantity > 0) {
                $car->update(['status' => 'Available']);
            }
        }
    }

    $reservation->delete();

    return redirect()->route('admin.reservations.index')
        ->with('success', 'Reservation deleted successfully.');
}


public function reservationTimeline()
{
    $startDate = Carbon::today();
    $endDate = Carbon::today()->addDays(14);

    $cars = Car::with(['reservations' => function ($q) use ($startDate, $endDate) {
        $q->whereBetween('pickup_date', [$startDate, $endDate])
          ->orWhereBetween('dropoff_date', [$startDate, $endDate]);
    }])->get();

    $carCategories = Car::select('category', DB::raw('count(*) as count'))
        ->groupBy('category')
        ->get()
        ->map(function ($item) {
            return (object)[
                'name' => $item->category,
                'count' => $item->count
            ];
        });

    return view('dashboard.timeline', compact('cars', 'startDate', 'endDate', 'carCategories'));
}


}
