@extends('layouts.myapp')

@section('title', 'Add New Car')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"> Add New Car</h5>
                    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Cars
                    </a>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Please fix the following issues:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-exclamation-circle-fill text-danger me-1"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Section: Basic Information --}}
                        <h6 class="mt-3 border-bottom pb-2">🔍 Basic Info</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                                <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror" value="{{ old('brand') }}" placeholder="e.g. Toyota" required>
                                @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                                <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" placeholder="e.g. Corolla 2022" required>
                                @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Section: Specifications --}}
                        <h6 class="mt-4 border-bottom pb-2"> Specifications</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="engine" class="form-label">Engine <span class="text-danger">*</span></label>
                                <input type="text" name="engine" id="engine" class="form-control @error('engine') is-invalid @enderror" value="{{ old('engine') }}" placeholder="e.g. 2.0L Hybrid" required>
                                @error('engine') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    <option value="Sedan" {{ old('category') == 'Sedan' ? 'selected' : '' }}>Sedan</option>
                                    <option value="SUV" {{ old('category') == 'SUV' ? 'selected' : '' }}>SUV</option>
                                    <option value="Luxury" {{ old('category') == 'Luxury' ? 'selected' : '' }}>Luxury</option>
                                    <option value="Sports" {{ old('category') == 'Sports' ? 'selected' : '' }}>Sports</option>
                                    <option value="Electric" {{ old('category') == 'Electric' ? 'selected' : '' }}>Electric</option>
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stars" class="form-label">Stars (0–5)</label>
                                <input type="number" name="stars" id="stars" class="form-control @error('stars') is-invalid @enderror" value="{{ old('stars', 5) }}" min="0" max="5">
                                @error('stars') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Section: Pricing & Stock --}}
                        <h6 class="mt-4 border-bottom pb-2"> Pricing & Stock</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="price_per_day" class="form-label">Price Per Day ($) <span class="text-danger">*</span></label>
                                <input type="number" name="price_per_day" id="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror" value="{{ old('price_per_day') }}" step="0.01" required>
                                @error('price_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" min="1" required>
                                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Unavailable" {{ old('status') == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Section: Image Upload --}}
                        <h6 class="mt-4 border-bottom pb-2"> Car Image</h6>
                        <div class="mb-3">
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Accepted formats: JPG, PNG. Max size: 2MB</small>
                        </div>

                        {{-- Submit --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Add Car
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
