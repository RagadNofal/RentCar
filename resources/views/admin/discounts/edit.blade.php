@extends('layouts.myapp') 

@section('content')
<div class="container mt-4">
    <h2>Edit Discount #{{ $discount->id }}</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Update form --}}
    <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST" id="discount-form">
        @csrf
        @method('PUT')

        {{-- Discount Type --}}
        <div class="mb-3">
            <label for="type" class="form-label">Discount Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="car" {{ old('type', $discount->type) == 'car' ? 'selected' : '' }}>Car</option>
                <option value="category" {{ old('type', $discount->type) == 'category' ? 'selected' : '' }}>Category</option>
                <option value="global" {{ old('type', $discount->type) == 'global' ? 'selected' : '' }}>Global</option>
            </select>
        </div>

        {{-- Code --}}
        <div class="mb-3">
            <label for="code" class="form-label">Discount Code</label>
            <input type="text" name="code" id="code" class="form-control" maxlength="50"
                value="{{ old('code', $discount->code) }}" placeholder="Enter discount code">
        </div>

        {{-- Amount --}}
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="0"
                value="{{ old('amount', $discount->amount) }}" required>
        </div>

        {{-- Start Date --}}
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ old('start_date', $discount->start_date->format('Y-m-d')) }}" required>
        </div>

        {{-- End Date --}}
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                value="{{ old('end_date', $discount->end_date->format('Y-m-d')) }}" required>
        </div>

        {{-- Category (only for category type) --}}
        <div class="mb-3" id="category-field" style="display: none;">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" id="category" class="form-control"
                value="{{ old('category', $discount->category) }}">
        </div>

        {{-- Cars (only for car type) --}}
        <div class="mb-3" id="cars-field" style="display: none;">
            <label for="car_ids" class="form-label">Select Cars</label>
            <select name="car_ids[]" id="car_ids" class="form-select" multiple>
                @foreach(\App\Models\Car::all() as $car)
                <option value="{{ $car->id }}"
                    {{ in_array($car->id, old('car_ids', $discount->cars->pluck('id')->toArray())) ? 'selected' : '' }}>
                    {{ $car->name ?? "Car #$car->id" }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Is Active --}}
        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                value="1" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Is Active</label>
        </div>
{{-- Description --}}
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control" rows="3" maxlength="1000" placeholder="Enter a description for this discount...">{{ old('description', $discount->description) }}</textarea>
</div>
        <button type="submit" class="btn btn-primary">Update Discount</button>
        <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    // Show/hide category and cars fields based on selected type
    function toggleFields() {
        const type = document.getElementById('type').value;
        document.getElementById('category-field').style.display = type === 'category' ? 'block' : 'none';
        document.getElementById('cars-field').style.display = type === 'car' ? 'block' : 'none';

        // For global, hide both category and cars fields
        if (type === 'global') {
            document.getElementById('category-field').style.display = 'none';
            document.getElementById('cars-field').style.display = 'none';
        }
    }

    document.getElementById('type').addEventListener('change', toggleFields);

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', toggleFields);
</script>
@endsection
