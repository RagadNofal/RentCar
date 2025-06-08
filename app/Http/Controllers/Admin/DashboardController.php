<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total cars count
        $totalCars = Car::count();

        // Available cars count (based on enum status: 'Available')
        $availableCars = Car::where('status', 'Available')->count();

        // Active reservations count (based on enum status: 'Active')
        $activeReservations = Reservation::where('status', 'Active')->count();

        // Total users count (excluding admins)
       $totalUsers = User::where('role', '!=', 'admin')->count();



        // Recent 5 reservations with user and car
        $recentReservations = Reservation::with(['user', 'car'])
            ->latest()
            ->take(5)
            ->get();

        // Get car categories with counts
        $carCategories = DB::table('cars')
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get()
            ->map(function($item) {
                return (object)[
                    'name' => $item->category,
                    'count' => $item->count
                ];
            });
        return view('admin.dashboard', compact(
            'totalCars',
            'availableCars',
            'activeReservations',
            'totalUsers',
            'recentReservations',
            'carCategories'
        ));
    }


public function reservationFunnel()
{
    $total = DB::table('reservations')->count();

    $counts = DB::table('reservations')
        ->selectRaw("status, COUNT(*) as count")
        ->groupBy('status')
        ->pluck('count', 'status');

    // Use constants from the Reservation model
    $statuses = [
        Reservation::STATUS_PENDING,
        Reservation::STATUS_ACTIVE,
        Reservation::STATUS_COMPLETED,
        Reservation::STATUS_CANCELED,
    ];

    $funnel = [];
    foreach ($statuses as $status) {
        $funnel[$status] = $counts[$status] ?? 0;
    }


    return response()->json([
        'total' => $total,
        'funnel' => $funnel,
    ]);
}
public function reservationTimeline()
{
    $startDate = Carbon::today();
    $endDate = Carbon::today()->addDays(14);

    $cars = Car::with(['reservations' => function ($q) use ($startDate, $endDate) {
        $q->whereBetween('pickup_date', [$startDate, $endDate])
          ->orWhereBetween('dropoff_date', [$startDate, $endDate]);
    }])->get();

    return view('dashboard.timeline', compact('cars', 'startDate', 'endDate'));
}


}
