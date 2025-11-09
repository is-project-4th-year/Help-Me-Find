<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">

    <!-- Load the new Tailwind-powered CSS file -->
    @vite(['resources/css/app.css'])
    {{-- <link href="{{ asset('build/assets/app.css') }}" rel="stylesheet"> --}}

    <title>Welcome - Help Me Find</title>
</head>
<body class="bg-background text-foreground">

    <!-- Welcome Page Header -->
    <header class="w-full absolute top-0 left-0 z-10">
        <nav class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-primary">
                Help-Me-Find
            </div>
            <div class="space-x-2">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80 h-9 px-4 py-2">
                    Login
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
                    Register
                </a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <main>
        <div class="relative min-h-screen flex items-center justify-center overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/bookArt.png') }}" alt="Background Art" class="w-full h-full object-cover opacity-30">
                <div class="absolute inset-0 bg-background/50"></div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-10 text-center p-6 max-w-3xl">
                <h1 class="text-5xl md:text-6xl font-extrabold text-primary mb-6">
                    Lost something? Found something?
                </h1>
                <p class="text-xl md:text-2xl text-foreground mb-10">
                    Welcome to Help-Me-Find, your central hub for recovering lost items and reporting found ones.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-md text-lg font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-12 px-8 py-3">
                    Get Started
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-card border-t border-border">
        <div class="max-w-6xl mx-auto px-6 py-8 text-center text-muted-foreground">
            <p>&copy; {{ date('Y') }} Help-Me-Find. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
