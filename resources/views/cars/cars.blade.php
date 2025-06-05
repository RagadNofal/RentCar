@extends('layouts.myapp')
@section('content')
<div class="container mt-5 p-4 bg-light rounded shadow">
    <form action="{{ route('carSearch') }}">
        <div class="row g-3 align-items-center justify-content-center">
            <div class="col-md-2">
                <input type="text" name="brand" class="form-control" placeholder="Brand">
            </div>
            <div class="col-md-2">
                <input type="text" name="model" class="form-control" placeholder="Model">
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control" placeholder="Min Price $">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control" placeholder="Max Price $">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </form>
</div>

<div class="container my-4">
    <div class="row justify-content-center">
        @foreach ($cars as $car)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <a href="{{ route('car.reservation', ['car' => $car->id]) }}">
                        <div class="position-relative">
                            <img src="{{ $car->image }}" class="card-img-top img-fluid" alt="Car image" style="height: 200px; object-fit: cover;">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-2">{{ $car->reduce }}% OFF</span>
                        </div>
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-5 fw-bold">${{ $car->price_per_day }}</span>
                                <span class="text-muted text-decoration-line-through">${{ intval(($car->price_per_day * 100) / (100 - $car->reduce)) }}</span>
                            </div>
                            <div>
                                @for ($i = 0; $i < $car->stars; $i++)
                                    <i class="bi bi-star-fill text-primary"></i>
                                @endfor
                                <span class="badge bg-primary">{{ $car->stars }}.0</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="{{ route('car.reservation', ['car' => $car->id]) }}" class="btn btn-dark w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-calendar2-check-fill me-2"></i> Reserve
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="d-flex justify-content-center mb-5">
    {{ $cars->links('pagination::bootstrap-5') }}
</div>
@endsection
