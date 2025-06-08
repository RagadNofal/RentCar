@extends('layouts.myapp')

@section('title', $car->brand . ' ' . $car->model)

@section('styles')
    <style>
        .car-image {
            max-height: 400px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .spec-item {
            margin-bottom: 1.5rem;
        }

        .spec-icon {
            width: 45px;
            height: 45px;
            background-color: #f0f2f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .spec-icon i {
            color: #0d6efd;
            font-size: 1.2rem;
        }

       .status-ribbon {
    width: 160px;
    background-color: #28a745; /* Default green */
    color: white;
    text-align: center;
    font-weight: bold;
    text-transform: uppercase;
    padding: 6px 0;
    position: absolute;
    top: 20px;
    left: -40px;
    transform: rotate(-45deg);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    z-index: 10;
    font-size: 0.75rem;
}

.status-ribbon.unavailable {
    background-color: #6c757d; /* Secondary gray */
}

    </style>
@endsection

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
        <h2 class="mb-3">{{ $car->brand }} {{ $car->model }}</h2>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-primary">
                <i class="fas fa-pencil-alt me-1"></i> Edit
            </a>
            <a href="{{ route('admin.cars.rental-history', $car->id) }}" class="btn btn-info text-white">
                <i class="fas fa-history me-1"></i> Rental History
            </a>
            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this car?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">
                    <i class="fas fa-trash-alt me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left section -->
     <!-- Car Detail Card -->
<div class="col-lg-8">
    <div class="card shadow-sm position-relative border-0">
        <!-- Availability Ribbon -->
        <div class="position-absolute top-0 start-0 m-3">
            <span class="badge px-3 py-2 rounded-pill text-white {{ strtolower($car->status) === 'available' ? 'bg-primary' : 'bg-secondary' }}">
                <i class="fas {{ strtolower($car->status) === 'available' ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                {{ ucfirst($car->status) }}
            </span>
        </div>

        <!-- Car Image -->
        <img src="{{ asset($car->image ?? 'default-car.jpg') }}" class="car-image card-img-top rounded-top" alt="{{ $car->brand }} {{ $car->model }}">

        <!-- Car Body -->
<div class="card-body">
    <!-- Car Title -->
    <h3 class="fw-semibold text-primary mb-4">
        <i class="fas fa-car-side me-2 text-secondary"></i>
        {{ $car->brand }} {{ $car->model }}
    </h3>

    <!-- Tags & Availability -->
    <div class="d-flex flex-wrap gap-2 mb-4">
       
            <span class="badge rounded-pill bg-primary text-white px-3 py-2">
                <i class="fas fa-tags me-1"></i> {{ $car->category }}
            </span>
      
        <span class="badge rounded-pill bg-light text-dark border border-primary px-3 py-2">
            <i class="fas fa-boxes me-1"></i> {{ $car->quantity }} in stock
        </span>
    </div>

    <!-- Engine Info -->
    <div class="mb-4">
        <h6 class="text-uppercase text-muted small mb-1">Engine</h6>
        <p class="mb-0 fs-6">{{ $car->engine }}</p>
    </div>

    <!-- Rating -->
    <div>
        <h6 class="text-uppercase text-muted small mb-1">Rating</h6>
        <div class="d-flex align-items-center">
            @for ($i = 0; $i < $car->stars; $i++)
                <i class="fas fa-star text-warning me-1 fs-5"></i>
            @endfor
            @for ($i = $car->stars; $i < 5; $i++)
                <i class="far fa-star text-muted me-1 fs-5"></i>
            @endfor
            <span class="ms-2 text-muted small">({{ $car->stars }} / 5)</span>
        </div>
    </div>
</div>

    </div>
</div>


        <!-- Right section -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Pricing & Info</h5>
                </div>
                <div class="card-body">
                    <div class="spec-item d-flex align-items-center">
                        <div class="spec-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Daily Rate</h6>
                            <p class="mb-0 text-primary fw-bold">${{ number_format($car->price_per_day, 2) }}</p>
                        </div>
                    </div>

                    <div class="spec-item d-flex align-items-center">
                        <div class="spec-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Discount</h6>
                            <p class="mb-0">{{ $car->reduce }}%</p>
                        </div>
                    </div>

                    <div class="spec-item d-flex align-items-center">
                        <div class="spec-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">In Stock</h6>
                            <p class="mb-0">{{ $car->quantity }}</p>
                        </div>
                    </div>

                    <div class="spec-item d-flex align-items-center">
                        <div class="spec-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Status</h6>
                            <p class="mb-0">{{ ucfirst($car->status) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
