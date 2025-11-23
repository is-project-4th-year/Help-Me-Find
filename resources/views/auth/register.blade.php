{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Help Me Find</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Import the new auth.css file via Vite --}}
    <link href="{{ asset('build/assets/auth.css') }}" rel="stylesheet">
    @vite(['resources/css/auth.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">

            <div class="auth-header">
                <h1 class="auth-title">Help-Me-Find</h1>
                <p class="auth-description"><i class="fa fa-user-plus"></i> Create your new account</p>
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

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="firstName" class="form-label">First Name</label>
                        <input id="firstName" class="form-input" type="text" name="firstName" value="{{ old('firstName') }}" required autofocus placeholder="Enter your first name" />
                    </div>

                    <div class="form-group">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input id="lastName" class="form-input" type="text" name="lastName" value="{{ old('lastName') }}" required placeholder="Enter your last name" />
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email" />
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" class="form-input" type="password" name="password" required placeholder="Create a password" />
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required placeholder="Confirm your password" />
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary btn-full">
                            Create Account
                        </button>
                    </div>
                </form>

                <div class="auth-footer">
                    {{-- UPDATED --}}
                    <a href="{{ route('login') }}"><i class="fa fa-sign-in"></i> Already have an account? Login</a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
