<nav>
    <div class="logo">
        <button class="menu-btn" id="menu-btn" onclick="toggleDrawer()"><i class="fa fa-navicon"></i></button>
        <a href="/">Help-Me-Find</a>
    </div>
    <ul class="options">
        {{-- TODO: Create a notification module that shows who is texting you. When pressing the notification, take the user to the chat page.--}}
        <button id="profileDisplay" class="profile-btn">
            <i class="fa fa-user" style="margin: 0 "></i>
        </button>
    </ul>
</nav>

<aside class="drawer" id="drawer">
    <ul class="options">
        <hr style="border: 0.5px solid rgba(167, 124, 67, 0.2); width: 90%; margin: 10px;">

        <li><a href="{{ route('found') }}"><i class="fa fa-bullhorn fa-fw"></i> Report Found</a></li>
        <li><a href="{{ route('lostItems') }}"><i class="fa fa-search fa-fw"></i> Lost Items</a></li>
        <li><a href="{{ route('chat.index') }}"><i class="fa fa-comments fa-fw"></i> Chats</a></li>

        {{-- This block has been uncommented and updated --}}
        {{-- <li>
            <button id="light-mode-btn" onclick="toggleLightMode()">
                <i class="fa fa-sun-o"></i> Light Mode
            </button>
        </li> --}}

    </ul>
</aside>

{{-- Profile Sideview --}}
<aside id="profilePopup" class="profile-popup">
    <div class="profile-content">
        <span class="close-btn" id="closeProfilePopupBtn">&times;</span>
        <div class="profile-header">

            <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="profile-img">
            <h3>{{ auth()->user()->firstName }} {{ auth()->user()->lastName }}</h3>
            {{-- <p>{{ '@'.auth()->user()->username }}</p> --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="fa fa-sign-out"></i> Logout
                </button>
            </form>
        </div>
    </div>
</aside>
