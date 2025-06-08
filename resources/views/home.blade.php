@extends('layouts.myapp')
@section('content')
<main>
    <div class="bg-light py-5">
        <div class="container py-md-5">
            <div class="row align-items-center">
                <!-- Text Content -->
                <div class="col-md-6 text-center text-md-start px-4">
                    <h1 class="fw-bold display-5 mb-4">
                        <span class="text-primary">Simple</span> & Reliable Car Rental Experience
                    </h1>
                    <div class="d-md-none mb-4">
                        <img src="/images/home car.png" class="img-fluid" alt="home car">
                    </div>
                    <p class="mb-4">
                        Planning a trip? From short city rides to long-distance travels, we offer a wide range of vehicles to meet your needs. Booking is quick, easy, and 100% secure.
                    </p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <a href="/cars" class="btn btn-primary fw-bold px-4">View Cars</a>
                        <a href="/contact_us" class="btn btn-outline-primary px-4">Contact Us</a>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-md-6 d-none d-md-block">
                    <img src="/images/home car.png" class="img-fluid" alt="home car">
                </div>
            </div>
        </div>

        {{-- Cars Section --}}
        <div class="container my-5">
            <div class="d-flex align-items-center justify-content-center">
                <hr class="flex-grow-1 border-top border-primary opacity-100" style="height: 2px;">
                <p class="mx-4 fw-bold text-primary mb-0 fs-5">CARS</p>
                <hr class="flex-grow-1 border-top border-primary opacity-100" style="height: 2px;">
            </div>
            <div class="d-flex justify-content-end me-md-5 me-2 mt-3">
                <a href="/cars" class="btn btn-outline-primary btn-sm">See All</a>
            </div>
        </div>

        {{-- Cars List --}}
        <div class="container my-4">
            <div class="row g-4 justify-content-center">
                @foreach ($cars as $car)
                    <div class="col-md-4">
                        <div class="card border shadow-sm h-100">
                            <div class="position-relative">
                               <a href="{{ route('car.reservation', ['car' => $car->id]) }}">
    <img src="{{ $car->image }}" class="card-img-top object-fit-cover" alt="Car Image" style="height: 240px; object-fit: cover;">
    
    <!-- Left badge: Discount -->
    <span class="position-absolute top-0 start-0 m-2 badge bg-primary">
        {{ $car->reduce }}% OFF
    </span>

    <!-- Right badge: Availability -->
    <span class="position-absolute top-0 end-0 m-2 badge {{ $car->status === 'Available' ? 'bg-success' : 'bg-danger' }}">
        {{ $car->status === 'Available' ? 'Available' : 'Not Available' }}
    </span>
</a>

                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title fw-bold">{{ $car->brand }} {{ $car->model }} {{ $car->engine }}</h5>
                                    <p class="text-muted mb-2"><i class="bi bi-tags"></i> {{ $car->category }}</p> <!-- Category Added -->

                                    <div class="d-flex justify-content-between align-items-center my-3">
                                        <div>
                                            <span class="fs-4 fw-bold text-dark">${{ $car->price_per_day }}</span><br>
                                            <small class="text-muted text-decoration-line-through">
                                                ${{ intval(($car->price_per_day * 100) / (100 - $car->reduce)) }}
                                            </small>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 0; $i < $car->stars; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0d6efd" class="bi bi-star-fill me-1" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.32-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.63.282.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                </svg>
                                            @endfor
                                            <span class="badge bg-primary ms-2">{{ $car->stars }}.0</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('car.reservation', ['car' => $car->id]) }}" class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2 mt-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="width: 1.2em;">
                                        <path style="fill: #0d6efd;" d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z"/>
                                    </svg>
                                    Reserve
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Our Numbers Section --}}
        <div class="container my-5">
            <h2 class="text-center fs-2 fw-medium font-car">
                <span class="text-dark">Our</span> <span class="text-primary">Numbers</span>
            </h2>
            <div class="bg-dark text-white rounded p-4 mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                <div class="text-center border-bottom border-md-bottom-0 border-md-end pb-3 pb-md-0 px-md-4 flex-fill">
                    <h3 class="fs-1 fw-medium font-car text-primary">50</h3>
                    <p class="fs-5 font-car">Cars</p>
                </div>
                <div class="text-center border-bottom border-md-bottom-0 border-md-end pb-3 pb-md-0 px-md-4 flex-fill">
                    <h3 class="fs-1 fw-medium font-car text-primary">500 +</h3>
                    <p class="fs-5 font-car">Satisfied Clients</p>
                </div>
                <div class="text-center pb-3 pb-md-0 px-md-4 flex-fill">
                    <h3 class="fs-1 fw-medium font-car text-primary">500</h3>
                    <p class="fs-5 font-car">Reservations</p>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Section -->
        <section class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-semibold display-6 text-dark">
                        Why <span class="text-primary">Choose Us</span>
                    </h2>
                    <p class="text-muted fs-5 mt-3">
                        Elevate your car rental experience with seamless booking, top-tier vehicles, and 24/7 support tailored to your journey.
                    </p>
                </div>

                <div class="row g-4">
                    <!-- Features -->
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white shadow-sm rounded-4 p-4 h-100 text-center transition-hover">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-headset fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark">24/7 Support</h5>
                            <p class="text-muted small">We're always here to assist you with dedicated, round-the-clock customer service.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white shadow-sm rounded-4 p-4 h-100 text-center transition-hover">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-speedometer2 fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Super Cars</h5>
                            <p class="text-muted small">Drive premium vehicles that combine style, performance, and unforgettable moments.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white shadow-sm rounded-4 p-4 h-100 text-center transition-hover">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-arrow-counterclockwise fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Free Cancellation</h5>
                            <p class="text-muted small">Plans change—we get it. Enjoy the freedom of flexible, penalty-free cancellations.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="bg-white shadow-sm rounded-4 p-4 h-100 text-center transition-hover">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:60px;height:60px;">
                                <i class="bi bi-arrow-counterclockwise fs-4"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Free Cancellation</h5>
                            <p class="text-muted small">Plans change—we get it. Enjoy the freedom of flexible, penalty-free cancellations.</p>
                        </div>
                    </div>
                    <!-- Add more features as needed -->
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
