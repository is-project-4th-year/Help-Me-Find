<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">
    <title>Note-Z-Book</title>

    @vite(['resources/css/style.css'])
    @vite(['resources/js/script.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>


</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="left">
            <button class="menu-btn" id="menu-btn" onclick="toggleDrawer()"><i class="fa fa-navicon"></i></button>
            <h1>Note-Z-Book</h1>
        </div>
        <button id="profileDisplay" class="profile-btn">
            <i class="fa fa-user"></i>
        </button>
    </nav>

    <div class="below-navbar">

        <!-- Sidebar Drawer -->
        <aside class="drawer" id="drawer">
            <ul>

                <li>
                    <button onclick="window.location.href='{{ route('home') }}'">
                        <i class="fa fa-home"></i> Home
                    </button>
                </li>

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

        {{-- Profile Sideview  --}}
        <aside id="profilePopup" class="profile-popup">
            <div class="profile-content">
                <span class="close-btn" id="closeProfilePopupBtn">&times;</span>
                <div class="profile-header">
                    <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="profile-img">

                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content-book">
            {{-- <div class="tab" >
                <div id="tab1" class="tab-button" onclick="detailTab()">
                    Details
                </div>
                <div id="tab2" class="tab-button" onclick="logTab()">
                    Logs
                </div>
            </div> --}}
            {{-- <img src="{{asset('images/bookArt.png')}}" /> --}}
            <div  id="bookDetails" class="main-card">
                <div class="card">
                    <h2 style="font-size: 30px">Book Details</h2>
                    <p>Take a look at the book details!</p>
                </div>

                <div class="card">
                    <h2 style="padding-bottom: 7px"> Synopsis </h2>
                    <div class="synopsis"> </div>
                </div>

                <button type="" class="button">
                    {{ __('Login') }}
                </button>

                <button type="" class="button">
                    {{ __('Login') }}
                </button>
            </div>
            <div id="bookLogs" class="main-card">
                <div class="card">
                    <h2 style="font-size: 30px">Book Details</h2>
                    <p>Take a look at the book details!</p>
                </div>

                <div class="card">
                    <h2 style="padding-bottom: 7px"> Synopsis </h2>
                    <div class="synopsis"> </div>
                </div>
            </div>



        </main>
    </div>

</body>
</html>
