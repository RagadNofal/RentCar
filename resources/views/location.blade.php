@extends('layouts.myapp')

@section('content')
<div class="container py-5">

    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <img src="/images/shop_2.jpg" class="img-fluid rounded shadow-sm" alt="About RentCar" loading="lazy">
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold text-primary">About RentCar</h1>
            <p class="lead">
                More than just rentals — we're your travel partner. Based in the heart of Amman, RentCar provides reliable and convenient vehicle solutions for all types of travelers.
            </p>
            <p>
                From quick city drives to longer adventures, our professional team is here to make sure you drive away happy — every time.
            </p>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="text-center mb-5">
        <h2 class="text-primary mb-3">Why Choose Us?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4 border rounded shadow-sm h-100">
                    <i class="bi bi-clock-history fs-2 text-primary"></i>
                    <h5 class="mt-3">Fast Booking</h5>
                    <p>Reserve your car in minutes using our simple online system or friendly customer service.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded shadow-sm h-100">
                    <i class="bi bi-shield-check fs-2 text-primary"></i>
                    <h5 class="mt-3">Reliable Service</h5>
                    <p>All vehicles are regularly maintained and fully insured to keep you safe on the road.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 border rounded shadow-sm h-100">
                    <i class="bi bi-geo-alt fs-2 text-primary"></i>
                    <h5 class="mt-3">Prime Location</h5>
                    <p>We're close to major landmarks, airports, and hotels — making pick-up and drop-off a breeze.</p>
                </div>
            </div>
        </div>
    </div>

   
    <!-- Small Location Map -->
    <div class="mb-5">
        <h4 class="text-primary mb-3 text-center">Our Location</h4>
        <div class="mx-auto" style="max-width: 600px;">
            <div class="ratio ratio-4x3 rounded shadow-sm">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3389.123456789!2d35.9123456!3d31.9631589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151ca1f123456789%3A0xabcdef1234567890!2sAppTrainers%20-%20Queen%20Rania%20St%2C%20Amman%2C%20Jordan!5e0!3m2!1sen!2sjo!4v1686498234799!5m2!1sen!2sjo"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

</div>
@endsection
