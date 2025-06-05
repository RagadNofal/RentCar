@extends('layouts.myapp')
@section('content')
    <div class="d-flex justify-content-center mt-5">
        <div class="card col-10 col-sm-8 col-md-6 col-lg-5 shadow">
            <div class="card-body p-4">
                <h4 class="text-center mb-4">Register</h4>

                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control">
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control">
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Choose an Avatar:</label>
                        <div class="row text-center">
                            @for ($i = 1; $i <= 6; $i++)
                                <div class="col-4 col-md-2 mb-2">
                                    <input type="radio" name="avatar_option" value="/images/avatars/avatar_{{ $i }}.jpg" id="avatar_{{ $i }}" class="d-none avatar-radio">
                                    <label for="avatar_{{ $i }}">
                                        <img src="/images/avatars/avatar_{{ $i }}.jpg" class="avatar img-thumbnail" width="50">
                                    </label>
                                </div>
                            @endfor
                        </div>
                        <p class="text-center mt-2 fw-bold">OR</p>
                        <input type="file" name="avatar_choose" class="form-control mt-2">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control">
                        @error('password')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">Confirm Password:</label>
                        <input type="password" id="password-confirm" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .avatar.selected {
            border: 3px solid #0d6efd;
            border-radius: 50%;
            padding: 3px;
        }
    </style>

    <script>
        document.querySelectorAll('.avatar-radio').forEach((radio, index) => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('.avatar').forEach(img => img.classList.remove('selected'));
                radio.nextElementSibling.querySelector('img').classList.add('selected');
            });
        });
    </script>
@endsection
