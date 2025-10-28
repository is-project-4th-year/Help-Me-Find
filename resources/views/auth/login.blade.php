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
    @vite(['resources/css/sign.css'])

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

{{-- @section('content') --}}
<body class="signBody">
    <card class="signBox">
        <text> Login </text>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label> Email </label> <br>
            <input id="email" placeholder="Please enter your email." type="email" class="signTextArea @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required> <br>

            <label> Password </label> <br>
            <input id="password" placeholder="Please enter your password."type="password" class="signTextArea @error('password') is-invalid @enderror" name="password" required> <br>


            @if($errors->any())
                <div class="alert alert-danger" style="color:red; font-size: 15px;">
                    <div>
                        @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <button type="submit" class="signButton">
                {{ __('Login') }}
            </button>
            {{-- <input class="signButton" name="login" type="submit" value="Login"> <br> --}}
        </form>

        <div>
            <a class="signLink" href="{{ route('register') }}"> Don't have an account? Sign up!</a>
        </div>
    </card>
</body>
</html>
