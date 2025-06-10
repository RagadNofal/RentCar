@extends('layouts.myapp')

@section('title', 'All Discounts')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Discounts</h2>
        <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
            + Create Discount
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($discounts->isEmpty())
        <div class="alert alert-info">No discounts found.</div>
    @else
    <div class="row g-4">
        @foreach($discounts as $discount)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        {{ ucfirst($discount->type) }} Discount
                        @if($discount->code)
                            <span class="badge bg-primary ms-2">{{ $discount->code }}</span>
                        @endif
                    </h5>

                    <p class="card-text text-muted small mb-2">
                        <strong>Amount:</strong> {{ number_format($discount->amount, 2) }} JD
                    </p>

                    <p class="card-text mb-2">
                        {!! nl2br(e($discount->description ?? 'No description')) !!}
                    </p>

                    <ul class="list-unstyled small text-muted mb-3">
                        <li><strong>Status:</strong> 
                            <span class="badge {{ $discount->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $discount->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </li>
                        <li><strong>Valid:</strong> {{ \Carbon\Carbon::parse($discount->start_date)->format('d M Y') }} – {{ \Carbon\Carbon::parse($discount->end_date)->format('d M Y') }}</li>
                        @if($discount->type === 'category')
                            <li><strong>Category:</strong> {{ $discount->category }}</li>
                        @elseif($discount->type === 'car')
                            <li><strong>Cars:</strong> {{ $discount->cars->count() }} linked</li>
                        @else
                            <li><strong>Applies to:</strong> All cars</li>
                        @endif
                    </ul>

                    <div class="mt-auto d-flex justify-content-between">
                        <a href="{{ route('admin.discounts.show', $discount->id) }}" class="btn btn-sm btn-info text-white">View</a>
                        <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                        <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this discount?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>

                    <form action="{{ route('admin.discounts.toggle', $discount->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm w-100 {{ $discount->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }}">
                            Toggle: {{ $discount->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
