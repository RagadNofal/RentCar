@extends('layouts.myapp')
@section('content')
<div class="container bg-white rounded p-4 my-5">
    <div class="row">
        {{-- -------------------------- left -------------------------- --}}
        <div class="col-md-8 border-end border-dark px-3">

            <h2 class="ms-3 display-4">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h2>

            <div class="d-flex align-items-end mt-4 ms-3">
                <h3 class="text-muted fs-4">Price:</h3>
                <p class="mb-0">
                    <span class="fs-3 fw-bold text-primary ms-3 me-2 border border-primary px-2 py-1 rounded">{{ $car->price_per_day }} $</span>
                    <span class="fs-5 text-danger text-decoration-line-through">
                        {{ intval(($car->price_per_day * 100) / (100 - $car->reduce)) }} $
                    </span>
                </p>
            </div>

            <div class="d-flex align-items-center justify-content-around mt-4 me-4">
                <div class="flex-grow-1 border-bottom border-secondary mx-2"></div>
                <p class="mb-0">Order Information</p>
                <div class="flex-grow-1 border-bottom border-secondary mx-2"></div>
            </div>

            <div class="px-3">
                <form id="reservation_form" action="{{ route('car.reservationStore', ['car' => $car->id]) }}" method="POST">
                    @csrf
                    <div class="row g-3 mt-4">
                        {{-- Full Name --}}
                        <div class="col-md-6">
                            <label for="full-name" class="form-label">Full Name</label>
                            <input type="text" name="full-name" id="full-name" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" name="email" id="email" value="{{ $user->email }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Reservation Dates --}}
                        <div class="col-12">
                            <label for="laravel-flatpickr" class="form-label">Reservation Dates</label>
                            <x-flatpickr 
                                range 
                                id="laravel-flatpickr" 
                                name="reservation_dates" 
                                class="form-control @error('reservation_dates') is-invalid @enderror" 
                                placeholder="Select reservation date range" 
                            />
                            @error('reservation_dates')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>


                        {{-- Pickup Location --}}
                        <div class="col-md-6">
                            <label for="pickup_location" class="form-label">Pickup Location</label>
                           <select name="pickup_location" id="pickup_location" class="form-select @error('pickup_location') is-invalid @enderror">
                                <option value="" disabled {{ old('pickup_location') ? '' : 'selected' }}>Select pickup location</option>
                                @foreach (['Company Site','Queen Alia Airport','7th Circle','Mecca Street','University of Jordan','Downtown','Other'] as $location)
                                    <option value="{{ $location }}" {{ old('pickup_location') == $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pickup_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Dropoff Location --}}
                        <div class="col-md-6">
                            <label for="dropoff_location" class="form-label">Dropoff Location</label>
                            <select name="dropoff_location" id="dropoff_location" class="form-select @error('dropoff_location') is-invalid @enderror">
                                <option value="" disabled {{ old('dropoff_location') ? '' : 'selected' }}>Select Dropoff location</option>
                                @foreach (['Company Site','Queen Alia Airport','7th Circle','Mecca Street','University of Jordan','Downtown','Other'] as $location)
                                    <option value="{{ $location }}" {{ old('dropoff_location') == $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dropoff_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                       
                    </div>

                    {{-- Desktop Submit --}}
                    <div class="mt-4 d-none d-md-block">
                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow">Order Now</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- -------------------------- right -------------------------- --}}
        <div class="col-md-4 d-flex flex-column align-items-center mt-4 mt-md-0">
            <div class="position-relative w-75">
                <img src="{{ $car->image }}" alt="product image" class="img-fluid rounded shadow">
                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-primary mt-3 ms-3">
                    {{ $car->reduce }}% OFF
                </span>
            </div>

            <p class="mt-3 d-none d-md-block fs-5 text-center">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</p>

            <div class="mt-2 d-none d-md-block">
                <div class="d-flex align-items-center">
                    @for ($i = 0; $i < $car->stars; $i++)
                        <i class="bi bi-star-fill text-primary me-1"></i>
                    @endfor
                    <span class="badge bg-primary ms-2">{{ $car->stars }}.0</span>
                </div>
            </div>

            {{-- Duration & Price --}}
            <div class="mt-4 w-100 ps-3">
                <p id="duration" class="text-secondary">Estimated Duration:
                    <span class="badge bg-light text-dark border border-primary ms-2">-- days</span>
                </p>
            </div>

            <div class="mt-3 w-100 ps-3">
                <p id="total-price" class="text-secondary">Estimated Price:
                    <span class="badge bg-light text-dark border border-primary ms-2">-- $</span>
                </p>
            </div>

            {{-- Mobile Submit --}}
            <div id="mobile_submit_button" class="mt-4 w-100 d-md-none">
                <button type="submit" class="btn btn-primary w-100 fw-bold shadow">Order Now</button>
            </div>
        </div>
    </div>

    {{-- SweetAlert error --}}
    @if (session('error'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "error",
                title: "{{ session('error') }}"
            });
        </script>
    @endif
</div>

{{-- JavaScript for dynamic price calculation --}}
<script>
    $(document).ready(function() {
        const flatpickrElement = document.getElementById('laravel-flatpickr');

        if (flatpickrElement && flatpickrElement._flatpickr) {
            flatpickrElement._flatpickr.config.onChange.push(function(selectedDates) {
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1];

                    if (startDate && endDate && startDate <= endDate) {
                       const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                        const pricePerDay = {{ $car->price_per_day }};
                        const totalPrice = duration * pricePerDay;
                        $('#duration span').text(duration + ' days');
                        $('#total-price span').text(totalPrice + ' $');
                    } else {
                        $('#duration span').text('-- days');
                        $('#total-price span').text('-- $');
                    }
                } else {
                    $('#duration span').text('-- days');
                    $('#total-price span').text('-- $');
                }
            });
        }

        document.getElementById("mobile_submit_button").addEventListener("click", function() {
            document.getElementById("reservation_form").submit();
        });
    });
</script>
@endsection
