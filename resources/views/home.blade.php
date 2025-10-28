<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">
    <title>Help Me Find</title>

    @vite(['resources/css/style.css'])
    @vite(['resources/js/script.js'])

    {{-- <script src="http://192.168.100.10:8000/js/script.js"></script> --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>


</head>
<body>

    <!-- Navbar -->
    <nav>
        <div class="logo">
            <button class="menu-btn" id="menu-btn" onclick="toggleDrawer()"><i class="fa fa-navicon"></i></button>
            Help Me Find
        </div>
        <ul class="options">
            <li><a href="/">Home</a></li>
            <li><a href="{{ route('found') }}">Report Found</a></li>
            <li><a href="{{ route('lostItems') }}">Lost Items</a></li>
            <button id="profileDisplay" class="profile-btn">
                <i class="fa fa-user"></i>
            </button>
        </ul>
    </nav>

    {{-- <div class="below-navbar"> --}}

        <!-- Sidebar Drawer -->
        <aside class="drawer" id="drawer">
            <ul>
                <hr style="border: 0.5px solid rgba(167, 124, 67, 0.2); width: 90%; margin: 10px;">

                <li>
                    <button id="light-mode-btn" onclick="toggleLightMode()">
                        <i class="fa fa-moon-o"></i> Light Mode
                    </button>
                </li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <i class="fa fa-sign-out"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        {{-- Profile Sideview --}}
        <aside id="profilePopup" class="profile-popup">
            <div class="profile-content">
                <span class="close-btn" id="closeProfilePopupBtn">&times;</span>
                <div class="profile-header">

                    <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="profile-img">
                    <h3>{{ auth()->user()->firstName }}</h3>
                    {{-- <p>{{ '@'.auth()->user()->username }}</p> --}}
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        {{-- <main class="main-content"> --}}
        <div class="container">
            {{-- <h1>Welcome to Lost & Found</h1> --}}
            <h2>Welcome Back, {{auth()->user()->firstName }}</h2>
            <p style="font-size: 17px; color: #5d4037; max-width: 550px; margin: 0 auto;">
                A smart system that helps you easily report found belongings, search for lost items,
                and use AI image recognition to identify what you’ve found.
                Choose an option below to get started.
            </p>
        </div>

        <div class="container">
            <h2>Your Item Tag QR Code</h2>
            <div>{!! $qrCode !!}</div>
            <p>Link: <a href="{{ $link }}" target="_blank">{{ $link }}</a></p>
        </div>
        {{-- </main> --}}

    {{-- </div> --}}

    <footer>
        &copy; 2025 Help-Me-Find | Designed with ❤ by Bethelhem
    </footer>

</body>
</html>
