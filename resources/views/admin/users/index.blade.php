@extends('layouts.myapp')

@section('title', 'Manage Users & Reservations')

@section('content')
<div class="container py-4">

    {{-- Admins Section --}}
    <div class="text-center my-5">
        <h2 class="mb-3">Admins</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary mb-4">
            <i class="fas fa-plus me-1"></i> Add New Admin/User
        </a>

        <div class="row">
            @forelse ($admins as $admin)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="row g-0 align-items-center">
                            <div class="col-4 p-3">
                                @if($admin->avatar)
                                    <img src="{{ $admin->avatar }}" alt="admin avatar" class="img-fluid rounded-circle">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="default avatar" class="img-fluid rounded-circle">
                                @endif
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h5 class="card-title mb-1">{{ $admin->name }}</h5>
                                    <p class="card-text"><small class="text-muted">{{ $admin->email }}</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">No admins found.</p>
            @endforelse
        </div>
    </div>

    {{-- Clients Section --}}
    <div class="text-center my-5">
        <h2 class="mb-4">Clients</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Client</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined At</th>
                        <th>Reservations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                        <tr>
                            <td>
                                @if($client->avatar)
                                    <img src="{{( $client->avatar) }}" alt="client avatar" class="rounded-circle img-thumbnail" width="50">
                                @else
                                    <img src="{{ asset('images/default-avatar.png') }}" alt="default avatar" class="rounded-circle img-thumbnail" width="50">
                                @endif
                            </td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if ($client->reservations_count)
                                    <span class="fw-bold">{{ $client->reservations_count }}</span> reservation{{ $client->reservations_count > 1 ? 's' : '' }}
                                @else
                                    <span class="text-muted">No reservations</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $client) }}" class="btn btn-sm btn-primary">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No clients found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    </div>

</div>
@endsection
