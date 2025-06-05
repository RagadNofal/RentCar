@extends('layouts.myapp')

@section('content')
<div class="container mt-5">
    <form action="" method="GET">
        @csrf
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror" 
                       placeholder="Brand" value="{{ request('brand') }}">
                @error('brand')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" 
                       placeholder="Model" value="{{ request('model') }}">
                @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <input type="number" name="min_price" min="0" class="form-control @error('min_price') is-invalid @enderror" 
                       placeholder="$ Minimum Price" value="{{ request('min_price') }}">
                @error('min_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-3">
                <input type="number" name="max_price" min="0" class="form-control @error('max_price') is-invalid @enderror" 
                       placeholder="$ Maximum Price" value="{{ request('max_price') }}">
                @error('max_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 d-flex justify-content-center gap-3 mt-3">
                <button type="submit" class="btn btn-primary px-4">Search</button>
                <a href="{{ route('cars') }}" class="btn btn-secondary px-4">All</a>
            </div>
        </div>
    </form>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        @forelse ($cars as $car)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="position-relative">
                        <a href="{{ route('car.reservation', ['car' => $car->id]) }}">
                            <img src="{{ $car->image }}" class="card-img-top" alt="Car Image">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">{{ $car->reduce }}% OFF</span>
                        </a>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h5>
                        <p class="card-text">
                            <strong class="text-dark">${{ $car->price_per_day }}</strong>
                            <del class="text-muted">
                                ${{ intval(($car->price_per_day * 100) / (100 - $car->reduce)) }}
                            </del>
                        </p>
                        <div class="d-flex align-items-center mb-2">
                            @for ($i = 0; $i < $car->stars; $i++)
                                <i class="bi bi-star-fill text-primary"></i>
                            @endfor
                            <span class="badge bg-primary ms-2">{{ $car->stars }}.0</span>
                        </div>
                        <a href="{{ route('car.reservation', ['car' => $car->id]) }}" class="btn btn-dark mt-auto">
                            <i class="bi bi-calendar-plus me-2"></i> Reserve
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <h4 class="text-muted">No car found!</h4>
            </div>
        @endforelse
    </div>
</div>

@if ($cars->hasPages())
    <div class="d-flex justify-content-center mb-4">
        {{ $cars->links('pagination::bootstrap-5') }}
    </div>
@endif
@endsection
