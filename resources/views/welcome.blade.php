<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help Me Find</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/welcome.css'])

</head>
<body>
    <div class="container">
        <div class="left">
            {{-- <h1>Welcome to Your Digital Lost and Found App!</h1>
            <p style="padding-bottom: 20px">Report and discover lost items quickly and safely.</p> --}}
            <h1>"Help Me Find!"</h1>
            <p style="padding-bottom: 20px">Lost something? Ask your community to help you find it!</p>
            <p style="padding-bottom: 20px">A Digital Lost-and-Found that Makes Missing Belongings to Returnable Treasures.</p>
            <button class="btn btn-primary">Get Started</button>
            <button class="btn btn-secondary" onclick="location.href='{{ route('login') }}'">Sign In</button>
        </div>
        <div class="right image-box">
            <img src="{{ asset('images/lost-and-found.png') }}" alt="Help Me Find illustration">
        </div>
    </div>
</body>
</html>
