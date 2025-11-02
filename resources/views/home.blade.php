<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  @include('layouts.bar')

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
            <p style="font-size: 15px; color: #4e342e; margin-bottom: 15px;">
                Print this QR code and attach it to your personal items (like a book or laptop). If someone finds it, they can scan the code to report it directly to you.
            </p>
            <div class="qr-code-box">
                {!! $qrCode !!}
                <p style="font-size: 14px; margin-top: 10px; color: #5d4037;">
                    Finder Report Link: <a href="{{ $link }}" target="_blank" style="color: #8d6e63; text-decoration: none;">View Link</a>
                </p>
            </div>
            {{-- Adding a clearer call to action for utility --}}
            <button class="btn" style="margin-top: 25px;" onclick="alert('Functionality not implemented: Print/Download QR Code')">
                <i class="fa fa-print"></i> Print / Download QR Code
            </button>
        </div>
        {{-- </main> --}}

    {{-- </div> --}}

    <footer>
        &copy; 2025 Help-Me-Find | Designed with ❤ by Bethelhem
    </footer>

</body>
</html>
