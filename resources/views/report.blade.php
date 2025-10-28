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
    <nav class="navbar">
        <div class="left">
            <button class="menu-btn" id="menu-btn" onclick="toggleDrawer()"><i class="fa fa-navicon"></i></button>
            <h1>Help Me Find</h1>
        </div>
        <button id="profileDisplay" class="profile-btn">
            <i class="fa fa-user"></i>
        </button>
    </nav>

    <div class="below-navbar">

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
        <main class="main-content">
            <div class="main-card">

                <div class="card">
                    <h2> This item belongs to {{ $owner->firstName }} </h2>
                </div>

                <div class="card">
                    <h2>Your Item Tag QR Code</h2>
                    <form method="POST" action="" enctype="multipart/form-data">
                        {{-- <form method="POST" action="{{ route('finder.submit', $owner->id) }}" enctype="multipart/form-data"> --}}
                        @csrf
                        <label>Description:</label><br>
                        <textarea name="description" required></textarea><br>
                        <label>Location Found:</label><br>
                        <input name="location" required><br>
                        <label>Upload Image:</label><br>
                        <input type="file" name="image" accept="image/*"><br>
                        <button type="submit">Submit Report</button>
                    </form>
                </div>


            </div>
        </main>

    </div>



</body>
</html>
