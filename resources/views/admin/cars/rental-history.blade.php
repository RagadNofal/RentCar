@extends('layouts.myapp')

@section('title', 'Rental History for ' . $car->brand . ' ' . $car->model)

@section('content')
<div class="container my-4">
    <h2>Rental History for {{ $car->brand }} {{ $car->model }}</h2>
    <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back to Cars
    </a>

    @if($rentals->isEmpty())
        <div class="alert alert-info">No rental records found for this car.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Renter Name</th>
                    <th>Pickup Date</th>
                    <th>Return Date</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rentals as $index => $rental)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $rental->user ? $rental->user->name : 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($rental->pickup_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($rental->return_date)->format('Y-m-d') }}</td>
                    <td>
                        @if($rental->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($rental->status == 'confirmed')
                            <span class="badge bg-success">Confirmed</span>
                        @elseif($rental->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($rental->status) }}</span>
                        @endif
                    </td>
                    <td>${{ number_format($rental->total_price, 2) }}</td>
                    <td>{{ $rental->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
