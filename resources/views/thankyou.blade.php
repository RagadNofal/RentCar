<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/x-icon" href="/images/logos/LOGOtext.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-white shadow rounded p-4 p-md-5 w-100" style="max-width: 700px;">
            <div class="text-center mb-4">
                <img src="/images/logos/LOGO.png" alt="Company Logo" class="img-fluid" style="max-width: 100px;">
            </div>
            <h1 class="text-center fw-bold text-primary display-5">Thank You!</h1>
            <p class="text-center text-muted fs-5">We appreciate your trust in RentCar.</p>

            <div class="bg-light p-4 rounded mt-4 position-relative">
                <div class="position-absolute top-0 start-50 translate-middle-y" style="width: 0; height: 0; border-left: 20px solid transparent; border-right: 20px solid transparent; border-bottom: 30px solid #0d35a3;"></div>
                <h4 class="text-center mb-3">What Happens Next?</h4>
                <p class="text-secondary mb-3">
                    Please visit one of our nearby locations and show your reservation invoice (either printed or digital) to receive your rental car.
                </p>
                <p class="text-secondary mb-0">
                    If you need assistance, don’t hesitate to reach out. We're here to make your experience as smooth as possible.
                </p>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-center mt-4 gap-3">
                <a href="{{ route('invoice', ['reservation' => $reservation->id]) }}"
                   class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-printer-fill"></i> Print Invoice
                </a>

                <a href="{{ route('clientReservation') }}"
                   class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-arrow-left-circle-fill"></i> Back to Reservations
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons CDN (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</body>

</html>
