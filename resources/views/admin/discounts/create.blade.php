@extends('layouts.myapp') 
@section('title', 'Create Discount')

@section('content')
<div class="container mt-4">
    <h2>Create Discount</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Error!</strong> Please fix the following issues:<br>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.discounts.store') }}" id="discount-form">
        @csrf

        {{-- Type --}}
        <div class="mb-3">
            <label for="type" class="form-label">Discount Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="">-- Select Type --</option>
                <option value="car">Car-Specific</option>
                <option value="category">Category-Based</option>
                <option value="global">Global</option>
            </select>
        </div>

      {{-- Car IDs (only for 'car') --}}
<div class="mb-3 d-none" id="car-section">
    <label class="form-label">Select Cars</label>

    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start" type="button" id="carDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
            Choose Cars
        </button>
        <ul class="dropdown-menu w-100 p-2" aria-labelledby="carDropdownButton" style="max-height: 300px; overflow-y: auto;">
            @foreach(\App\Models\Car::all() as $car)
                <li>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="car_ids[]" value="{{ $car->id }}" id="carCheckbox{{ $car->id }}">
                        <label class="form-check-label" for="carCheckbox{{ $car->id }}">
                            {{ $car->brand }}-{{ $car->model }}
                        </label>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <small class="form-text text-muted">Check the cars you want to apply the discount to.</small>
</div>


        {{-- Category (only for 'category') --}}
        <div class="mb-3 d-none" id="category-section">
            <label for="category" class="form-label">Category Name</label>
            <input type="text" name="category" id="category" class="form-control" maxlength="255">
        </div>

        {{-- Code (nullable for car/category, required for global) --}}
        <div class="mb-3" id="code-section">
            <label for="code" class="form-label">Discount Code</label>
            <input type="text" name="code" id="code" class="form-control" maxlength="50">
        </div>

        {{-- Amount --}}
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (JD)</label>
                <input type="number" step="1" min="0" max="99" name="amount" id="amount" class="form-control" required>
            </div>


        {{-- Start Date --}}
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>

        {{-- End Date --}}
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div>

        {{-- Is Active --}}
        <div class="mb-3">
            <label for="is_active" class="form-label">Is Active?</label>
            <select name="is_active" id="is_active" class="form-select">
                <option value="1" selected>Yes</option>
                <option value="0">No</option>
            </select>
        </div>
{{-- Description --}}
<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea name="description" id="description" class="form-control" rows="3" maxlength="1000" placeholder="Enter a description for this discount (e.g. Eid Offer for sedan cars)...">{{ old('description') }}</textarea>
</div>

        <button type="submit" class="btn btn-primary">Create Discount</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const carSection = document.getElementById('car-section');
        const categorySection = document.getElementById('category-section');
        const codeInput = document.getElementById('code');

        typeSelect.addEventListener('change', function () {
            const type = this.value;

            carSection.classList.toggle('d-none', type !== 'car');
            categorySection.classList.toggle('d-none', type !== 'category');

            // For global, code is required; otherwise optional
            if (type === 'global') {
                codeInput.required = true;
            } else {
                codeInput.required = false;
            }
        });
    });
</script>
@endpush
