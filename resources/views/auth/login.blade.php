<?php
    $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link href="{{ asset('build/assets/app.css') }}" rel="stylesheet"> --}}


    <title>Help Me Find</title>
    <script>
        window.onload = function() {
            const message = "<?php echo $message; ?>";
            if (message) {
                alert(message);
            }
        }
    </script>
</head>

<body class="min-h-screen flex items-center justify-center p-4 bg-background">

    <div class="w-full max-w-md rounded-lg shadow-md bg-card text-card-foreground border border-border" id="auth-tabs">
        <div class="text-center p-6">
            <h1 class="text-2xl font-bold">Help-Me-Find</h1>
            <p class="text-muted-foreground">Lost and found item recovery platform</p>
        </div>
        <div class="p-6 pt-0">
            <div class="grid w-full grid-cols-2 bg-muted p-1 rounded-md mb-4">
                <button data-tab="login" class="tab-trigger inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-card data-[state=active]:text-foreground data-[state=active]:shadow-sm" data-state="active">
                    Login
                </button>
                <button data-tab="register" class="tab-trigger inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-card data-[state=active]:text-foreground data-[state=active]:shadow-sm">
                    Register
                </button>
            </div>

            @if($errors->any())
                <div class="alert alert-danger bg-destructive/10 border border-destructive/50 text-destructive p-3 rounded-md mb-4" style="font-size: 15px;">
                    <div>
                        @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="tab-content" id="login-content">
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label for="login-email" class="text-sm font-medium leading-none">Email</label>
                        <input
                            id="login-email"
                            type="email"
                            placeholder="Enter your email"
                            class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="login-password" class="text-sm font-medium leading-none">Password</label>
                        <input
                            id="login-password"
                            type="password"
                            placeholder="Enter your password"
                            class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            name="password"
                            required
                        />
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full">
                        Sign In
                    </button>
                </form>
            </div>

            <div class="tab-content hidden" id="register-content">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label for="register-name" class="text-sm font-medium leading-none">Full Name</label>
                        <input
                            id="register-name"
                            type="text"
                            placeholder="Enter your full name"
                            class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            name="name" {{-- Laravel typically uses 'name' --}}
                            value="{{ old('name') }}"
                            required
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="register-email" class="text-sm font-medium leading-none">Email</label>
                        <input
                            id="register-email"
                            type="email"
                            placeholder="Enter your email"
                            class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            name="email"
                            value="{{ old('email') }}"
                            required
                        />
                    </div>
                    <div class="space-y-2">
                        <label for="register-password" class="text-sm font-medium leading-none">Password</label>
                        <input
                            id="register-password"
                            type="password"
                            placeholder="Create a password"
                            class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            name="password"
                            required
                        />
                    </div>

                    <div class="space-y-2">
                        <label for="register-password-confirm" class="text-sm font-medium leading-none">Confirm Password</label>
                        <input
                            id="register-password-confirm"
                            type="password"
                            placeholder="Confirm your password"
                            class="flex h-9 w-full rounded-md border border-border bg-input px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                            name="password_confirmation"
                            required
                        />
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2 w-full">
                        Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabContainer = document.getElementById('auth-tabs');
            const tabTriggers = tabContainer.querySelectorAll('.tab-trigger');
            const tabContents = tabContainer.querySelectorAll('.tab-content');

            tabTriggers.forEach(trigger => {
                trigger.addEventListener('click', function () {
                    const tabName = this.getAttribute('data-tab');

                    // Update trigger states
                    tabTriggers.forEach(t => t.setAttribute('data-state', ''));
                    this.setAttribute('data-state', 'active');

                    // Update content visibility
                    tabContents.forEach(content => {
                        if (content.id === `${tabName}-content`) {
                            content.classList.remove('hidden');
                        } else {
                            content.classList.add('hidden');
                        }
                    });
                });
            });

            // Check for validation errors and show the correct tab
            @if ($errors->has('name') || $errors->has('password_confirmation'))
                // If register errors exist, switch to register tab
                document.querySelector('.tab-trigger[data-tab="register"]').click();
            @endif
        });
    </script>
</body>
</html>
