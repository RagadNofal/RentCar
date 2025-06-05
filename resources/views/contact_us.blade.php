@extends('layouts.myapp')
@section('content')
<div class="container mt-5">
    <h2 class="text-center fw-bold display-5 mb-3">Contact Us</h2>
    <p class="text-center text-muted mb-5">
        Got a technical issue? Want to send feedback about a beta feature? Need details about our Business plan? Let us know.
    </p>

    <div class="row g-5">
        <div class="col-md-6 order-md-1 order-2">
            <form id="contact-form">
                <div class="row mb-3">
                    <div class="col">
                        <label for="first-name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first-name" placeholder="Ragad" required>
                    </div>
                    <div class="col">
                        <label for="last-name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last-name" placeholder="Nofal" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="contact.ragad@gmail.com" required>
                    </div>
                    <div class="col">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" placeholder="+962000000000" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <select class="form-select" id="subject" required>
                        <option selected disabled>Select subject</option>
                        <option value="reservation">Reservation</option>
                        <option value="payment">Payment</option>
                        <option value="car problem">Car Problem</option>
                        <option value="cancelation">Cancelation</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label">Your Message</label>
                    <textarea class="form-control" id="message" rows="5" placeholder="Leave a comment..."></textarea>
                </div>

                <button type="submit" class="btn btn-outline-primary px-4 fw-bold">Send Message</button>
            </form>
        </div>

        <div class="col-md-6 order-md-2 order-1 text-center">
            <div class="mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-2" style="width: 80px; height: 80px;">
                    <i class="fas fa-building fa-2x text-secondary"></i>
                </div>
                <h5 class="fw-bold">Company information:</h5>
                <p class="mb-1">RentCar </p>
                <p class="mb-1">Location: Jordan</p>
            </div>
            <div>
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-2" style="width: 80px; height: 80px;">
                    <i class="fas fa-envelope fa-2x text-secondary"></i>
                </div>
                <h5 class="fw-bold">Need Help?</h5>
                <p class="mb-1">contact@rentcar.com</p>
                <p>+962 600000000</p>
            </div>
        </div>
    </div>
</div>
@endsection
