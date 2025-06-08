@extends('layouts.myapp')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Add </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf
<div class="mb-3">
    <label for="role" class="form-label">Role:</label>
    <select name="role" id="role" class="form-select" required>
        <option value="">-- Select Role --</option>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
    </select>
    @error('role')
        <div class="text-danger small">{{ $message }}</div>
    @enderror
</div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Choose an Avatar:</label>
                            <div class="row g-2 text-center">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="col-4 col-md-2">
                                        <input type="radio" name="avatar_option" value="/images/avatars/avatar_{{ $i }}.jpg" id="avatar_{{ $i }}" class="d-none avatar-radio">
                                        <label for="avatar_{{ $i }}">
                                            <img src="/images/avatars/avatar_{{ $i }}.jpg" class="avatar img-thumbnail" width="60">
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            <p class="text-center mt-2 fw-semibold">OR</p>
                            <input type="file" name="avatar_choose" class="form-control mt-2">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">Confirm Password:</label>
                            <input type="password" id="password-confirm" name="password_confirmation" class="form-control" required>
                        </div>

                       

                         <div class="text-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar.selected {
        border: 3px solid #0d6efd;
        border-radius: 50%;
        padding: 3px;
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.7);
    }
</style>

<script>
    document.querySelectorAll('.avatar-radio').forEach((radio) => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.avatar').forEach(img => img.classList.remove('selected'));
            radio.nextElementSibling.querySelector('img').classList.add('selected');
        });
    });
</script>
@endsection
