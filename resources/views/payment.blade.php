@extends('layouts.myapp')

@section('title', 'Complete Payment')

@section('styles')
<style>
    .payment-card {
        max-width: 900px;
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
    .form-label {
        font-weight: 500;
    }
    .price-breakdown {
        background: #fff;
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
    }
    .btn-lg {
        font-size: 1.1rem;
    }
</style>
@endsection

@section('content')
<div class="container my-5">
    <div class="payment-card shadow-sm">
        <div class="card">
            <div class="card-body p-4">
                <h1 class="card-title mb-4">Complete Your Reservation</h1>
                <div class="row g-4">
                    {{-- Reservation Summary --}}
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Reservation Summary</h5>
                                <hr>
                                <div class="d-flex justify-content-center mb-3">
                                    <img src="{{ $reservation->car->image }}" alt="Car Image" class="img-fluid rounded shadow-sm" style="max-height: 240px; object-fit: cover;">
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

                                    <div class="price-breakdown">
    {{-- Subtotal --}}
    <div class="d-flex justify-content-between">
        <span>Subtotal ({{ $reservation->days }} {{ Str::plural('day', $reservation->days) }})</span>
        <span>${{ number_format($reservation->price_per_day * $reservation->days, 2) }}</span>
    </div>

    {{-- Discount (if any) --}}
    @if (old('discount_code') && session('errors') === null)
        @php
            $discountAmount = ($reservation->car->getDiscountByCode(old('discount_code'))?->amount ?? 0) / 100 * $reservation->total_price;
        @endphp
        <div class="d-flex justify-content-between text-success mt-2">
            <span>Discount ({{ old('discount_code') }})</span>
            <span>- ${{ number_format($discountAmount, 2) }}</span>
        </div>
    @endif
</div>


                                    <p class="mb-0"><strong>Total Amount:</strong>
    <span class="text-primary fw-bold total-amount">
        $@php
            if (old('discount_code') && session('errors') === null) {
                echo number_format($reservation->total_price - $discountAmount, 2);
            } else {
                echo number_format($reservation->total_price, 2);
            }
        @endphp
    </span>
</p>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Form --}}
                    <div class="col-md-6">
                        <div class="card h-100">
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

                                    {{-- Card Number --}}
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" value="{{ old('card_number') }}" placeholder="1234 5678 9012 3456" required>
                                    </div>

                                    {{-- Card Holder --}}
                                    <div class="mb-3">
                                        <label for="card_holder" class="form-label">Card Holder Name</label>
                                        <input type="text" class="form-control" id="card_holder" name="card_holder" value="{{ old('card_holder') }}" placeholder="Name on card" required>
                                    </div>

                                    {{-- Expiration Date + CVV --}}
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Expiration Date</label>
                                            <div class="row gx-2">
                                                <div class="col-6">
                                                    <select class="form-select" name="expiry_month" required>
                                                        <option value="" disabled selected>Month</option>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <option value="{{ sprintf('%02d', $i) }}" {{ old('expiry_month') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                                {{ sprintf('%02d', $i) }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <select class="form-select" name="expiry_year" required>
                                                        <option value="" disabled selected>Year</option>
                                                        @for ($i = date('y'); $i <= date('y') + 10; $i++)
                                                            <option value="{{ $i }}" {{ old('expiry_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" name="cvv" value="{{ old('cvv') }}" placeholder="123" required>
                                        </div>
                                    </div>

                                    {{-- Discount Code --}}
                                   <div class="mb-3">
                                        <label for="discount_code" class="form-label">Discount Code (optional)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="discount_code" name="discount_code" placeholder="Enter code">
                                            <button class="btn btn-outline-success" type="button" id="applyDiscountBtn">Apply</button>
                                        </div>
                                        <div id="discountFeedback" class="form-text text-success mt-1" style="display: none;"></div>
                                    </div>


                                    {{-- Terms --}}
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                                        </label>
                                    </div>


{{-- Submit --}}
<div class="d-grid">
    <button type="submit" class="btn btn-primary btn-lg">
        Pay Now
    </button>
</div>



                                    <div class="text-center mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-lock me-1"></i> Your payment is secure with SSL encryption.
                                        </small>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Terms Modal --}}
        <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Rental Agreement Terms</h6>
                        <ul>
                            <li>Driver must be 21+ with valid license</li>
                            <li>Credit card in driver’s name at pickup</li>
                            <li>Return car with same fuel</li>
                            <li>No smoking inside</li>
                            <li>No off-road or racing use</li>
                            <li>Damage is renter's responsibility</li>
                            <li>Cancel up to 24h in advance</li>
                            <li>Late returns may be charged</li>
                        </ul>
                        <p>Read all terms carefully before paying.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('applyDiscountBtn').addEventListener('click', function () {
    const code = document.getElementById('discount_code').value;
    const reservationId = "{{ $reservation->id }}";

    fetch(`/discount/check/${reservationId}?code=${code}`)
        .then(response => response.json())
        .then(data => {
            const feedback = document.getElementById('discountFeedback');
            if (data.valid) {
                feedback.textContent = `Discount applied: -$${data.amount} (${data.code})`;
                feedback.style.display = 'block';

                document.querySelector('.total-amount').textContent = `$${data.new_total}`;
                document.querySelector('.pay-now-btn').textContent = `Pay Now $${data.new_total}`;
            } else {
                feedback.textContent = 'Invalid or expired discount code.';
                feedback.style.display = 'block';
                feedback.classList.remove('text-success');
                feedback.classList.add('text-danger');
            }
        });
});
</script>
@endpush
