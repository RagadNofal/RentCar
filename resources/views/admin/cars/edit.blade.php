@extends('layouts.myapp')

@section('title', 'Edit Car')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Car</h5>
                    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Back to Cars
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Brand -->
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                            <input type="text" name="brand" id="brand" class="form-control @error('brand') is-invalid @enderror"
                                value="{{ old('brand', $car->brand) }}" required maxlength="255">
                            @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Model -->
                        <div class="mb-3">
                            <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                            <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror"
                                value="{{ old('model', $car->model) }}" required maxlength="255">
                            @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Engine -->
                        <div class="mb-3">
                            <label for="engine" class="form-label">Engine <span class="text-danger">*</span></label>
                            <input type="text" name="engine" id="engine" class="form-control @error('engine') is-invalid @enderror"
                                value="{{ old('engine', $car->engine) }}" required maxlength="255">
                            @error('engine') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Price Per Day -->
                        <div class="mb-3">
                            <label for="price_per_day" class="form-label">Price Per Day ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="price_per_day" id="price_per_day"
                                class="form-control @error('price_per_day') is-invalid @enderror"
                                value="{{ old('price_per_day', $car->price_per_day) }}" required>
                            @error('price_per_day') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="quantity" id="quantity"
                                class="form-control @error('quantity') is-invalid @enderror"
                                value="{{ old('quantity', $car->quantity) }}" required>
                            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror"
                                value="{{ old('category', $car->category) }}" maxlength="255" placeholder="Optional">
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="">Select Status</option>
                                <option value="Available" {{ old('status', $car->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="Unavailable" {{ old('status', $car->status) == 'Unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                       

                        <!-- Stars -->
                        <div class="mb-3">
                            <label for="stars" class="form-label">Stars <span class="text-danger">*</span></label>
                            <input type="number" min="0" max="5" name="stars" id="stars" class="form-control @error('stars') is-invalid @enderror"
                                value="{{ old('stars', $car->stars) }}" required>
                            @error('stars') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Car Image</label>
                            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif"
                                class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text">Leave empty to keep the current image. Max size: 2MB.</div>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if ($car->image)
                            <div class="mb-3">
                                <p>Current Image:</p>
                                <img src="{{ asset($car->image) }}" alt="Car Image" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        @endif

                        <div class="text-end">
                            <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Car</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = document.getElementById('imagePreview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'imagePreview';
                        preview.classList.add('img-thumbnail');
                        preview.style.maxHeight = '200px';
                        imageInput.parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endsection
