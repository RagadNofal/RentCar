@extends('layouts.myapp')

@section('title', 'Rental History for ' . $car->brand . ' ' . $car->model)

@section('content')
<div class="container my-4">
    <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Cars
    </a>

    {{-- Car Summary Card --}}
    <div class="card mb-4 shadow-sm">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{ $car->image }}" class="img-fluid rounded-start" alt="Car Image" style="height: 100%; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h3 class="card-title">{{ $car->brand }} {{ $car->model }} ({{ $car->engine }})</h3>
                    <p class="card-text">
                        <strong>Category:</strong> {{ $car->category ?? 'N/A' }}<br>
                        <strong>Status:</strong> 
                        <span class="badge {{ $car->status === 'Available' ? 'bg-success' : 'bg-danger' }}">
                            {{ $car->status }}
                        </span><br>
                        <strong>Stars:</strong> 
                        @for ($i = 0; $i < $car->stars; $i++)
                            <i class="bi bi-star-fill text-warning"></i>
                        @endfor
                        <br>
                        <strong>Price Per Day:</strong> ${{ number_format($car->price_per_day, 2) }}
                    </p>

                    {{-- Statistics --}}
                    <div class="mt-3 d-flex flex-wrap gap-4">
                        <div><strong>Total Rentals:</strong> {{ $rentals->count() }}</div>
                        <div>
                            <strong>Total Revenue:</strong> $
                            {{ number_format($rentals->sum(fn($r) => optional($r->payment)->amount), 2) }}
                        </div>
                        <div>
                            <strong>Average Revenue:</strong> $
                            {{ number_format($rentals->avg(fn($r) => optional($r->payment)->amount), 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rental History Table --}}
    @if($rentals->isEmpty())
        <div class="alert alert-info">No rental records found for this car.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Renter</th>
                        <th>Pickup</th>
                        <th>Return</th>
                        <th>Status</th>
                        <th>Total Price</th>
                        <th>Paid Amount</th>
                        <th>Payment Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rentals as $index => $rental)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $rental->user->name ?? 'N/A' }}</td>
                        <td>{{  $rental->pickup_location}}</td>
                        <td>{{  $rental->dropoff_location }}</td>
                        <td>
                            @switch($rental->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst($rental->status) }}</span>
                            @endswitch
                        </td>
                        <td>${{ number_format($rental->total_price, 2) }}</td>
                        <td>
                            @if($rental->payment)
                                <span class="text-success fw-bold">${{ number_format($rental->payment->amount, 2) }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($rental->payment)
                                <span class="badge bg-{{ $rental->payment->payment_status == 'Paid' ? 'success' : ($rental->payment->payment_status == 'Canceled' ? 'danger' : 'info') }} text-white">
                                    {{ $rental->payment->payment_status }}
                                </span>
                            @else
                                <span class="badge bg-secondary">No Payment</span>
                            @endif
                        </td>
                        <td>{{ $rental->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

