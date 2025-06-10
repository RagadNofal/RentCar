@extends('layouts.myapp')

@section('title', 'View Discount')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Discount Details</h4>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>Type:</strong>
                <span class="badge bg-info text-dark">{{ ucfirst($discount->type) }}</span>
            </div>

            @if($discount->type === 'category')
                <div class="mb-3">
                    <strong>Category:</strong> {{ $discount->category }}
                </div>
            @endif

            @if($discount->code)
                <div class="mb-3">
                    <strong>Discount Code:</strong> {{ $discount->code }}
                </div>
            @endif

            <div class="mb-3">
                <strong>Amount:</strong> {{ number_format($discount->amount, 2) }} JD
            </div>
@if($discount->description)
    <div class="mb-3">
        <strong>Description:</strong>
        <p>{{ $discount->description }}</p>
    </div>
@endif

            <div class="mb-3">
                <strong>Start Date:</strong> {{ \Carbon\Carbon::parse($discount->start_date)->format('d M Y') }}
            </div>

            <div class="mb-3">
                <strong>End Date:</strong> {{ \Carbon\Carbon::parse($discount->end_date)->format('d M Y') }}
            </div>

            <div class="mb-3">
                <strong>Status:</strong>
                @if($discount->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-secondary">Inactive</span>
                @endif
            </div>

            @if($discount->type === 'car')
                <div class="mb-3">
                    <strong>Applies to Cars:</strong>
                    <ul class="list-group mt-2">
                        @forelse($discount->cars as $car)
                            <li class="list-group-item">
                                <strong>{{ $car->model }}</strong> - {{ $car->category }} - {{ number_format($car->price_per_day, 2) }} JD/day
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No cars assigned to this discount.</li>
                        @endforelse
                    </ul>
                </div>
            @endif
        </div>

        <div class="card-footer">
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Back to Discounts</a>
        </div>
    </div>
</div>
@endsection
