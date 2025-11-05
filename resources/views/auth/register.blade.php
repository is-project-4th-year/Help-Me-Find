<?php
    $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
    // echo $message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">
    {{-- <link rel="stylesheet" href="style.css"/> --}}
    @vite(['resources/css/sign.css'])
    <link href="{{ asset('build/assets/sign.css') }}" rel="stylesheet">


    <title>Help Me Find</title>
    <script>
        window.onload = function() {
            const message = "<?php echo $message; ?>";
            if (message) {
                alert(message);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            if (localStorage.getItem("lightMode") === "true") {
                document.body.classList.add("light-mode");
            }
        });
    </script>
</head>
<body class="signBody">
    <div class="signBox">
        <text> Register </text>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label> First Name</label> <br>
            <input type="text" placeholder="Please enter your First Name." class="signTextArea" id="firstName" name="firstName" value="{{ old('firstName') }}" required> <br>

            <label> Last Name</label> <br>
            <input type="text" placeholder="Please enter your Last Name." class="signTextArea" id="lastName" name="lastName" value="{{ old('lastName') }}" required> <br>

            {{-- <label> Username </label> <br>
            <input type="text" placeholder="Please enter a Username" class="signTextArea" id="username" name="username" value="{{ old('username') }}" required> <br> --}}

            <label> Email</label> <br>
            <input type="email" placeholder="Please enter your Email Address." class="signTextArea" id="email" name="email" value="{{ old('email') }}" required> <br>

            <label> Password</label> <br>
            <input type="password" placeholder="Please enter a password." class="signTextArea" id="password" name="password" required> <br>


            <label> Confirm Password</label> <br>
            <input type="password" placeholder="Please enter a password." class="signTextArea" id="password_confirmation" name="password_confirmation" required> <br>

            @if($errors->any())
                <div class="alert alert-danger" style="color:red; font-size: 15px;">
                    <div>
                        @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <button type="submit" class="signButton">{{ __(key: '  Register') }}</button>
            {{-- <input class="signButton" name="register" type="submit" value="Register"> <br> --}}
        </form>
        <div>
            <a class="signLink" href="{{ route('login') }}"> Already have an account? Login!</a>
        </div>
    </div>
</body>
</html>
