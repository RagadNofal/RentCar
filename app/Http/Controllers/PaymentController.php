<?php

namespace App\Http\Controllers;

use App\Models\{Reservation, Payment};
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Request $request)
{
    $reservationId = $request->reservation;
    $reservation = Reservation::with('car')->findOrFail($reservationId);

    return view('payment', compact('reservation'));
}


    public function store(Request $request, Reservation $reservation)
    {
        
        $request->validate([
           
            'card_number' => 'required|string|size:16',
            'card_holder' => 'required|string|max:255',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|size:2',
            'cvv' => 'required|string|size:3',
        ]);

        $payment = Payment::create([
            'reservation_id' => $reservation->id,
          
            'payment_status' => Payment::STATUS_PAID,
           
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
