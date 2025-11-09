{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Help Me Find</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Import the new auth.css file via Vite --}}
    @vite(['resources/css/auth.css'])
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <h1 class="auth-title">Help-Me-Find</h1>
                <p class="auth-description">Sign in to your account</p>
            </div>

            <div class="auth-content">
                {{-- Display all errors at the top --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email" />
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" class="form-input" type="password" name="password" required placeholder="Enter your password" />
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary btn-full">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="auth-footer">
                    <a href="{{ route('register') }}">Don't have an account? Register</a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
