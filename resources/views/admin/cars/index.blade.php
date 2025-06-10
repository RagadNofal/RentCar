@extends('layouts.myapp')

@section('title', 'Car Management')

@section('styles')
    <style>
        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-available { background-color: #28a745; }
        .status-rented { background-color: #dc3545; }
        .status-maintenance { background-color: #ffc107; }

        .car-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.car-card .card-body {
    flex-grow: 1;
}

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

       .car-image-container {
    height: 200px;
    overflow: hidden;
    border-radius: 0.25rem 0.25rem 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa; /* Optional: adds background for consistency */
}

.car-image {
    height: 100%;
    width: auto;
    object-fit: cover;
}


        .car-card:hover .car-image {
            transform: scale(1.1);
        }

        .car-spec {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .car-spec i {
            width: 20px;
            margin-right: 8px;
            color: var(--primary-color);
        }

        .list-view .car-card {
            display: flex;
            flex-direction: row;
            height: auto;
        }

      .list-view .car-image-container {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

.list-view .car-image {
    height: 100%;
    width: auto;
    object-fit: cover;
}


        .list-view .card-body {
            flex: 1;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Car Management</h1>
            <a href="{{ route('admin.cars.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Car
            </a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search cars...">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <select id="statusFilter" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="Available">Available</option>
                            <option value="Rented">Unavailable</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <option value="sedan">Sedan</option>
                            <option value="suv">SUV</option>
                            <option value="luxury">Luxury</option>
                            <option value="sports">Sports</option>
                            <option value="electric">Electric</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select id="sortBy" class="form-select">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                            <option value="name_asc">Name: A to Z</option>
                            <option value="name_desc">Name: Z to A</option>
                        </select>
                    </div>

                    <div class="col-md-3 text-md-end">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary active" id="gridViewBtn">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="listViewBtn">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline-primary ms-2" id="downloadCarList">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <span class="text-muted">Showing <span id="countCars">{{ $cars->count() }}</span> of
                <span id="totalCars">{{ $cars->total() }}</span> cars</span>
        </div>

        <!-- Grid/List View -->
        <div id="gridView" class="row g-4">
            @forelse($cars as $car)
                <div class="col-xl-3 col-lg-4 col-md-6 car-item"
                    data-status="{{ $car->status }}"
                    data-category="{{ strtolower($car->category) }}"
                    data-name="{{ strtolower($car->brand . ' ' . $car->model) }}"
                    data-price="{{ $car->final_price }}"
                    data-date="{{ $car->created_at ? $car->created_at->timestamp : '' }}">
                    
                    <div class="card car-card shadow-sm h-100 position-relative">
                        <img src="{{ $car->image }}" class="card-img-top object-fit-cover" alt="Car Image" style="height: 240px; object-fit: cover;">

                        @if($car->reduce > 0)
                            <span class="position-absolute top-0 start-0 m-2 badge bg-primary">
                                {{ $car->reduce }}% OFF
                            </span>
                        @endif

                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">{{ $car->brand }} {{ $car->model }}</h5>
                                <span class="badge bg-{{ $car->status === 'Available' ? 'success' : 'danger' }}">
                                    {{ $car->status }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted">{{ $car->category ?? 'Uncategorized' }}</p>
                            <div class="mt-2"><strong>Engine:</strong> {{ $car->engine }}</div>
                            <div class="mt-2"><strong>Stars:</strong> {{ $car->stars }} ⭐</div>

                            <div class="mt-3">
                                @if($car->reduce > 0)
                                    <div>
                                        <span class="text-muted text-decoration-line-through me-2">
                                            ${{ number_format($car->original_price, 2) }}
                                        </span>
                                        <span class="fw-bold text-success">
                                            ${{ number_format($car->final_price, 2) }} / day
                                        </span>
                                    </div>
                                @else
                                    <h6 class="mb-0">${{ number_format($car->price_per_day, 2) }} / day</h6>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.cars.show', $car->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <a href="{{ route('admin.cars.edit', $car->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-pencil-alt me-1"></i>Edit
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $car->id }}">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal-{{ $car->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $car->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel-{{ $car->id }}">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete <strong>{{ $car->brand }} {{ $car->model }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No cars found matching your criteria.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $cars->links() }}
        </div>
    </div>
@endsection



@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteCarModal = document.getElementById('deleteCarModal');
            deleteCarModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const carId = button.getAttribute('data-car-id');
                const carName = button.getAttribute('data-car-name');

                document.getElementById('carNameToDelete').textContent = carName;
                document.getElementById('deleteCarForm').action = `/admin/cars/${carId}`;
            });

            // Filtering, Searching, Sorting, View Toggle
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const sortBy = document.getElementById('sortBy');
            const gridView = document.getElementById('gridView');
            const gridViewBtn = document.getElementById('gridViewBtn');
            const listViewBtn = document.getElementById('listViewBtn');

            const filterCars = () => {
                const query = searchInput.value.toLowerCase();
                const status = statusFilter.value;
                const category = categoryFilter.value;
                const sort = sortBy.value;

                let cars = Array.from(document.querySelectorAll('.car-item'));

                cars.forEach(car => {
                    const name = car.dataset.name;
                    const stat = car.dataset.status;
                    const cat = car.dataset.category;

                    const matchQuery = name.includes(query);
                    const matchStatus = !status || stat === status;
                    const matchCategory = !category || cat === category;

                    car.style.display = matchQuery && matchStatus && matchCategory ? '' : 'none';
                });

                if (['price_asc', 'price_desc', 'name_asc', 'name_desc', 'newest', 'oldest'].includes(sort)) {
                    cars.sort((a, b) => {
                        if (sort === 'price_asc') return a.dataset.price - b.dataset.price;
                        if (sort === 'price_desc') return b.dataset.price - a.dataset.price;
                        if (sort === 'name_asc') return a.dataset.name.localeCompare(b.dataset.name);
                        if (sort === 'name_desc') return b.dataset.name.localeCompare(a.dataset.name);
                        if (sort === 'newest') return b.dataset.date - a.dataset.date;
                        if (sort === 'oldest') return a.dataset.date - b.dataset.date;
                    });
                    cars.forEach(car => gridView.appendChild(car));
                }
            };

            searchInput.addEventListener('input', filterCars);
            statusFilter.addEventListener('change', filterCars);
            categoryFilter.addEventListener('change', filterCars);
            sortBy.addEventListener('change', filterCars);

            gridViewBtn.addEventListener('click', () => {
                gridViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
                gridView.classList.remove('list-view');
            });

            listViewBtn.addEventListener('click', () => {
                listViewBtn.classList.add('active');
                gridViewBtn.classList.remove('active');
                gridView.classList.add('list-view');
            });
        });
    </script>
@endsection
