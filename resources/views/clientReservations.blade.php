@extends('layouts.myapp')

@section('content')
<div class="container my-5">
    <!-- User Profile -->
    <div class="bg-white rounded shadow p-4 d-flex flex-wrap align-items-center mb-5">
        <div class="col-12 col-md-3 text-center mb-4 mb-md-0">
            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle border border-primary shadow" style="width: 150px; height: 150px; object-fit: cover;">
        </div>
        <div class="col-12 col-md-3">
            <h2 class="h1 text-secondary">{{ Auth::user()->name }}</h2>
            <p class="text-muted">{{ Auth::user()->email }}</p>
        </div>
        <div class="col-12 col-md-6 mt-4 mt-md-0">
            <div class="row g-3">
                <div class="col-6">
                    <div class="bg-primary bg-opacity-25 border border-primary rounded text-center p-3">
                        <p class="text-muted mb-1">Total Reservations</p>
                        <h3 class="text-primary">{{ Auth::user()->reservations->count() }}</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-success bg-opacity-25 border border-success rounded text-center p-3">
                        <p class="text-muted mb-1">Active Reservations</p>
                        <h3 class="text-success">{{ Auth::user()->reservations->where('status', 'Active')->count() }}</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-info bg-opacity-25 border border-info rounded text-center p-3">
                        <p class="text-muted mb-1">Pending Reservations</p>
                        <h3 class="text-info">{{ Auth::user()->reservations->where('status', 'Pending')->count() }}</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-danger bg-opacity-25 border border-danger rounded text-center p-3">
                        <p class="text-muted mb-1">Canceled Reservations</p>
                        <h3 class="text-danger">{{ Auth::user()->reservations->where('status', 'Canceled')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Book-Like Layout -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="h3 text-center text-muted mb-4">Reservations</h2>

        <!-- Tabs -->
        <ul class="nav nav-tabs justify-content-center mb-4" id="reservationTabs" role="tablist">
            @foreach(['Pending', 'Active', 'Completed', 'Canceled'] as $index => $status)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="{{ strtolower($status) }}-tab" data-bs-toggle="tab" data-bs-target="#{{ strtolower($status) }}" type="button" role="tab">
                        {{ $status }}
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="reservationTabsContent">
            @foreach(['Pending', 'Active', 'Completed', 'Canceled'] as $index => $status)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ strtolower($status) }}" role="tabpanel">
                    @php $filtered = $reservations->where('status', $status); @endphp
                    @forelse ($filtered as $reservation)
                        <div class="row bg-light p-3 rounded mb-4">
                            <div class="col-md-4 d-none d-md-block">
                                <img src="{{ $reservation->car->image }}" alt="" class="img-fluid rounded">
                            </div>
                            <div class="col-md-8">
                                <h4 class="text-dark">{{ $reservation->car->brand }} {{ $reservation->car->model }} {{ $reservation->car->engine }}</h4>
                                <div class="row mt-3">
                                    <div class="col-sm-4">
                                        <strong>From:</strong>
                                        <p class="text-primary">{{ \Carbon\Carbon::parse($reservation->start_date)->format('y-m-d') }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <strong>To:</strong>
                                        <p class="text-primary">{{ \Carbon\Carbon::parse($reservation->end_date)->format('y-m-d') }}</p>
                                    </div>
                                 <div class="col-sm-4">
    <strong>Price:</strong>
    @if ($reservation->payment)
        <p>
            <span class="text-muted text-decoration-line-through me-2">
                ${{ number_format($reservation->total_price, 2) }}
            </span>
            <span class="badge bg-success fs-6">
                ${{ number_format($reservation->payment->amount, 2) }} Paid
            </span>
        </p>
    @else
        <p>
            <span class="badge bg-warning text-dark">Unpaid</span>
            <span class="ms-2">${{ number_format($reservation->total_price, 2) }}</span>
        </p>
    @endif
</div>


                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <strong>Payment:</strong>
                                        <p>
                                            @if ($reservation->payment)
                                                @php
                                                        $statusColor = match($reservation->payment->payment_status) {
                                                            'Paid' => 'success',
                                                            'Canceled' => 'danger',
                                                            default => 'info',
                                                        };
                                                    @endphp

                                                    <span class="badge bg-{{ $statusColor }} bg-opacity-25 text-{{ $statusColor }} border border-{{ $statusColor }}">
                                                        {{ $reservation->payment->payment_status }}
                                                    </span>

                                            @else
                                                <span class="badge bg-secondary text-white">N/A</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Reservation:</strong>
                                        <p>
                                            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary">
    {{ $status }}
</span>


                                        </p>
                                    </div>
                                </div>

                                <div class="d-md-none mt-3">
                                    <img src="{{ $reservation->car->image }}" alt="" class="img-fluid rounded">
                                </div>

                                @if (!in_array($reservation->status, ['Canceled']))
                                    <div class="mt-4 text-center">
                                       <a href="{{ route('invoice', ['reservation' => $reservation->id]) }}" target="_blank" class="btn border border-primary bg-primary bg-opacity-10 text-primary w-100">
                                            <i class="bi bi-receipt me-1"></i> Get Reservation Invoice
                                        </a>

                                    </div>
                                @endif

                                @if ($reservation->status === 'Pending' && now()->lt(\Carbon\Carbon::parse($reservation->start_date)))
                                    <button class="btn border border-danger bg-danger bg-opacity-10 text-danger w-100 mt-2" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $reservation->id }}">
                                        <i class="bi bi-x-circle me-1"></i> Cancel Reservation
                                    </button>

                                @endif
                            </div>
                        </div>

                        @if (in_array($reservation->status, ['Pending', 'Active']))
                        <!-- Cancel Confirmation Modal -->
                        <div class="modal fade" id="cancelModal{{ $reservation->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $reservation->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Cancel Reservation</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to cancel this reservation?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep</button>
                                            <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="text-center py-5">
                            <h5 class="text-muted">No {{ $status }} reservations</h5>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
