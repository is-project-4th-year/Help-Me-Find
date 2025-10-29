<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
  <!-- ===== Bar ===== -->
    @include('layouts.bar')

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
