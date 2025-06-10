<?php

namespace App\Http\Controllers;

use App\Models\{Reservation, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use App\Models\Car;
use App\Models\Discount;
use Carbon\Carbon;
class PaymentController extends Controller
{
    public function create(Request $request)
{
    $reservationId = $request->reservation;
    $reservation = Reservation::with('car')->findOrFail($reservationId);

    return view('payment', compact('reservation'));
}


public function check(Request $request, Reservation $reservation)
{
    $code = $request->query('code');
    $discount = Discount::where('code', $code)
        ->where('is_active', true)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->first();

    if (!$discount) {
        return response()->json(['valid' => false]);
    }

    $original = $reservation->total_price;
    $amount = ($discount->amount / 100) * $original;
    $newTotal = max(0, $original - $amount);

    return response()->json([
        'valid' => true,
        'amount' => number_format($amount, 2),
        'new_total' => number_format($newTotal, 2),
        'code' => $code
    ]);
}




public function store(Request $request, Reservation $reservation)
{
    $request->validate([
        'card_number' => 'required|string|size:16',
        'card_holder' => 'required|string|max:255',
        'expiry_month' => 'required|string|size:2',
        'expiry_year' => 'required|string|size:2',
        'cvv' => 'required|string|size:3',
        'discount_code' => 'nullable|string|max:255',
    ]);

    $user = Auth::user();
    $car = $reservation->car;
    $code = $request->input('discount_code');
    $discount = null;

    // If a code is provided, validate and check it
    if ($code) {
        $key = 'discount-attempt:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['discount_code' => 'Too many attempts. Please try again in 5 minutes.']);
        }

        RateLimiter::hit($key, 300);

        $discount = $car->getDiscountByCode($code);

        if (!$discount) {
            return back()->withErrors(['discount_code' => 'Invalid or expired discount code.'])->withInput();
        }

        $alreadyUsed = Payment::whereHas('reservation', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('discount_id', $discount->id)
            ->where('payment_status', 'Paid')
            ->exists();

        if ($alreadyUsed) {
            return back()->withErrors(['discount_code' => 'You have already used this discount code.'])->withInput();
        }
    }

    // Use the trait to calculate discounted price
    $basePrice = $reservation->total_price;
    $finalAmount = $car->calculateDiscountedPrice($basePrice, $discount);
//dd( $finalAmount);
    // Store payment with discount ID and amount
    $payment = Payment::create([
        'reservation_id' => $reservation->id,
        'discount_id' => $discount?->id,
        'payment_status' => Payment::STATUS_PAID,
        'amount' => $finalAmount,
    ]);

    return redirect()->route('payment.thankyou', $reservation->id)
        ->with('success', 'Payment recorded successfully.');
}


    public function show(Reservation $reservation)
    {
        $payment = $reservation->payment;
        return view('payment.show', compact('reservation', 'payment'));
    }

    public function edit(Payment $payment)
    {
        return view('admin.updatePayment', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_status' => 'required|in:Pending,Paid,Canceled'
        ]);

        $payment->update(['payment_status' => $request->payment_status]);

        return redirect()->route('adminDashboard')->with('success', 'Payment status updated.');
    }
    public function thankYou(Reservation $reservation)
{
    return view('thankyou', compact('reservation'));
}

}
