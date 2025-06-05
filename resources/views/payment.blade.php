@extends('layouts.myapp')

@section('title', 'Complete Payment')

@section('styles')
    <style>
        .payment-card {
            max-width: 800px;
            margin: 0 auto;
        }

        .card-image {
            height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .card-info {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .payment-details {
            margin-top: 2rem;
        }

        .form-label {
            font-weight: 500;
        }

        .price-details {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container my-5">
        <div class="payment-card shadow-sm">
            <div class="card">
                <div class="card-body p-4">
                    <h1 class="card-title mb-4">Complete Your Reservation</h1>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Reservation Summary</h5>
                                    <hr>
                                        <div class="d-flex justify-content-center mb-3">
                                            <img src="{{ $reservation->car->image }}" 
                                                alt="Car Image" 
                                                class="img-fluid rounded shadow-sm" 
                                                style="max-height: 240px; object-fit: cover;">
                                        </div>



                                    <h4>{{ $reservation->car->brand }} {{ $reservation->car->model }}</h4>
                                    <p class="text-muted">{{ $reservation->car->engine }} • {{ $reservation->car->stars }} Stars • {{ ucfirst($reservation->car->status) }}</p>


                                    <div class="card-info mt-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="mb-1"><strong>Pick-up Date:</strong></p>
                                                <p>{{ \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y') }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1"><strong>Return Date:</strong></p>
                                                <p>{{ \Carbon\Carbon::parse($reservation->end_date)->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <p class="mb-1"><strong>Pick-up Location:</strong></p>
                                                <p>{{ $reservation->pickup_location }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p class="mb-1"><strong>Return Location:</strong></p>
                                                <p>{{ $reservation->dropoff_location }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <p><strong>Duration:</strong> {{ $reservation->days }} {{ Str::plural('day', $reservation->days) }}</p>
                                        <p><strong>Daily Rate:</strong> ${{ number_format($reservation->price_per_day, 2) }}</p>

                                        <div class="price-breakdown bg-white p-2 rounded mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span>Car Rental ({{ $reservation->days }} {{ Str::plural('day', $reservation->days) }}):</span>
                                                <span>${{ number_format($reservation->price_per_day * $reservation->days, 2) }}</span>
                                            </div>
                                        </div>

                                        <p><strong>Total Amount:</strong> <span class="fw-bold text-primary">${{ number_format($reservation->total_price, 2) }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Payment Information</h5>
                                    <hr>

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('payment.store', $reservation) }}" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="card_number" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="card_holder" class="form-label">Card Holder Name</label>
                                            <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="Name on card" required>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Expiration Date</label>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select class="form-select" name="expiry_month" required>
                                                            <option value="" selected disabled>Month</option>
                                                            @for ($i = 1; $i <= 12; $i++)
                                                                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <select class="form-select" name="expiry_year" required>
                                                            <option value="" selected disabled>Year</option>
                                                            @for ($i = date('y'); $i <= date('y') + 10; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="cvv" class="form-label">CVV</label>
                                                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                                <label class="form-check-label" for="terms">
                                                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg">Pay Now ${{ number_format($reservation->total_price, 2) }}</button>
                                        </div>

                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-lock me-1"></i> Your payment is secure. We use encryption to protect your data.
                                            </small>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Rental Agreement Terms</h6>
                    <p>By proceeding with this reservation, you agree to the following terms:</p>
                    <ul>
                        <li>The driver must be at least 21 years old and possess a valid driver's license.</li>
                        <li>A credit card in the driver's name must be presented at the time of pickup.</li>
                        <li>The car must be returned with the same amount of fuel as when it was picked up.</li>
                        <li>Smoking is not allowed in the vehicle.</li>
                        <li>The vehicle must not be taken off-road or used for racing.</li>
                        <li>Any damage to the vehicle will be charged to the renter.</li>
                        <li>Reservations can be canceled up to 24 hours before pickup without penalty.</li>
                        <li>Late returns may incur additional fees.</li>
                    </ul>
                    <p>Please read all terms carefully before proceeding.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
