@extends('layouts.myapp')

@section('title', 'Admin Dashboard')

@section('styles')
    <style>
.funnel-step {
  position: relative;
  min-height: 2.5rem;
  background-color: #f8f9fa; /* equivalent of bg-light */
  overflow: hidden; /* to clip bar overflow */
  z-index: 0;
}

.bar-fill {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 0;
  z-index: 1; /* place bar above bg, but below text */
  opacity: 0.5;
  transition: width 0.5s ease;
  border-radius: 0.25rem;
  pointer-events: none;
}

/* Then make sure text is above bar */
.funnel-step > span,
.funnel-step > text-node { /* the text nodes */
  position: relative;
  z-index: 2;
}

.bar-fill.bg-primary { background-color: #0d6efd !important; }
.bar-fill.bg-warning { background-color: #ffc107 !important; }
.bar-fill.bg-success { background-color: #198754 !important; }
.bar-fill.bg-danger { background-color: #dc3545 !important; }
.funnel-step {
  position: relative;
  min-height: 2.5rem;
  background-color: #f8f9fa;
  padding: 0.5rem 0.75rem; /* ensure padding */
  border-radius: 0.25rem;
}

.bar-fill {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 0;
  opacity: 0.4;
  transition: width 0.5s ease;
  border-radius: 0.25rem;
  pointer-events: none;
}

.bar-fill.bg-primary { background-color: #0d6efd !important; }
.bar-fill.bg-warning { background-color: #ffc107 !important; }
.bar-fill.bg-success { background-color: #198754 !important; }
.bar-fill.bg-danger { background-color: #dc3545 !important; }


        .dashboard-card {
            transition: all 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .dashboard-icon {
            font-size: 3rem;
            color: var(--primary-color);
        }

        .text-primary-gradient {
            background: linear-gradient(45deg, var(--primary-color), #4da3ff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .activity-timeline .timeline-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 20px;
        }

        .activity-timeline .timeline-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #e9ecef;
        }

        .activity-timeline .timeline-item:last-child:before {
            height: 50%;
        }

        .activity-timeline .timeline-point {
            position: absolute;
            left: -6px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background-color: var(--primary-color);
            border: 2px solid white;
        }

        .activity-timeline .timeline-content {
            padding-bottom: 10px;
        }

        .border-left-primary {
            border-left: 4px solid #4e73df;
        }

        .border-left-success {
            border-left: 4px solid #1cc88a;
        }

        .border-left-info {
            border-left: 4px solid #36b9cc;
        }

        .border-left-warning {
            border-left: 4px solid #f6c23e;
        }

        .chart-pie {
            position: relative;
            height: 15rem;
            width: 100%;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">Admin Dashboard</h1>
                <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Add New Car
                </a>
            </div>
            <hr>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Cars
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCars ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Available Cars
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableCars ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Active Reservations
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeReservations ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Reservations</h6>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Car</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($recentReservations) && count($recentReservations) > 0)
                                    @foreach ($recentReservations as $reservation)
                                        <tr>
                                            <td>{{ $reservation->id }}</td>
                                            <td>{{ $reservation->user->name }}</td>
                                            <td>{{ $reservation->car->brand }} {{ $reservation->car->model }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($reservation->start_date)->format('M d, Y') }} -
                                                {{ \Carbon\Carbon::parse($reservation->end_date)->format('M d, Y') }}
                                            </td>
                                            <td>
                                                @php
                                                    $status = strtolower($reservation->status);
                                                @endphp

                                                @if ($status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif ($status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($status == 'canceled' || $status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @elseif ($status == 'completed')
                                                    <span class="badge bg-info">Completed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No recent reservations</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow rounded-4 border-0 mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold text-primary">
                        <i class="fas fa-chart-bar me-2 text-secondary"></i>
                        Car Categories
                    </h5>
                </div>
                <div class="card-body">
                    @if (!empty($carCategories) && count($carCategories) > 0)
                        @php
                            $total = $carCategories->sum('count');
                        @endphp
                        <div class="list-group">
                            @foreach ($carCategories as $category)
                                @php
                                    $percentage = $total > 0 ? round(($category->count / $total) * 100) : 0;
                                    $color = sprintf('%06X', mt_rand(0, 0xFFFFFF));
                                @endphp
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-medium">
                                            <i class="fas fa-tag me-2" style="color: #{{ $color }}"></i>
                                            {{ $category->name }}
                                        </span>
                                        <span class="badge bg-primary rounded-pill">{{ $category->count }}</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: #{{ $color }};" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted mt-3">No categories available</p>
                    @endif
                </div>
            </div>

           <div class="card mt-4">
                <div class="card-header">Reservation Funnel</div>
                <div class="card-body">
                    <div id="funnelBar" class="d-flex flex-column gap-2">
                    <div class="funnel-step position-relative bg-light rounded p-2" id="pendingBar">
                        Pending: <span>0</span>
                        <div class="bar-fill bg-primary rounded position-absolute top-0 start-0 h-100" style="z-index: -1;"></div>
                    </div>
                    <div class="funnel-step position-relative bg-light rounded p-2" id="activeBar">
                        Active: <span>0</span>
                        <div class="bar-fill bg-warning rounded position-absolute top-0 start-0 h-100" style="z-index: -1;"></div>
                    </div>
                    <div class="funnel-step position-relative bg-light rounded p-2" id="completedBar">
                        Completed: <span>0</span>
                        <div class="bar-fill bg-success rounded position-absolute top-0 start-0 h-100" style="z-index: -1;"></div>
                    </div>
                    <div class="funnel-step position-relative bg-light rounded p-2" id="cancelledBar">
                        Cancelled: <span>0</span>
                        <div class="bar-fill bg-danger rounded position-absolute top-0 start-0 h-100" style="z-index: -1;"></div>
                    </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="timelineChart"></div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
   fetch('{{ route('admin.reservations.timeline') }}')
  .then(res => res.json())
  .then(data => {
      const cars = data.cars;
      const startDate = new Date(data.startDate);
    const series = cars.map(car => {
        return {
            name: car.name,
            data: car.reservations.map(r => ({
                x: r.status.charAt(0).toUpperCase() + r.status.slice(1),
                y: [
                    new Date(r.pickup_date).getTime(),
                    new Date(r.dropoff_date).getTime()
                ]
            }))
        };
    });

    const options = {
        chart: {
            type: 'rangeBar',
            height: 500,
        },
        plotOptions: {
            bar: {
                horizontal: true,
                rangeBarGroupRows: true
            }
        },
        xaxis: {
            type: 'datetime',
            min: startDate.getTime(),
            max: new Date(startDate.getTime() + 14 * 24 * 60 * 60 * 1000).getTime()
        },
        series: series,
        colors: ['#0d6efd', '#ffc107', '#198754', '#dc3545'],
    };

    const chart = new ApexCharts(document.querySelector("#timelineChart"), options);
    chart.render();
});
    fetch('{{ route('admin.dashboard.funnel') }}')
        .then(res => res.json())
        .then(data => {
            const total = data.total || 1;
            const funnel = data.funnel;

            const setFunnelStep = (id, count, color) => {
                const percent = ((count / total) * 100).toFixed(1);
                const container = document.getElementById(id);
                if (container) {
                    let barFill = container.querySelector('.funnel-fill-bar');
                    if (!barFill) {
                        barFill = document.createElement('div');
                        barFill.classList.add('funnel-fill-bar');
                        barFill.style.position = 'absolute';
                        barFill.style.top = '0';
                        barFill.style.left = '0';
                        barFill.style.height = '100%';
                        barFill.style.borderRadius = '0.25rem';
                        barFill.style.opacity = '0.4';
                        barFill.style.transition = 'width 0.5s ease';
                        barFill.style.pointerEvents = 'none';
                        container.appendChild(barFill);
                    }

                    barFill.style.width = percent + '%';
                    barFill.style.backgroundColor = color;

                    const span = container.querySelector('span');
                    if (span) {
                        span.innerText = `${count} (${percent}%)`;
                    }
                }
            };

            setFunnelStep('pendingBar', funnel.Pending || 0, '#0d6efd');
            setFunnelStep('activeBar', funnel.Active || 0, '#ffc107');
            setFunnelStep('completedBar', funnel.Completed || 0, '#198754');
            setFunnelStep('cancelledBar', funnel.Cancelled || 0, '#dc3545');
        });
});
</script>
@endpush
