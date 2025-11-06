
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Help Me Find</title>

    @vite(['resources/css/style.css'])
    @vite(['resources/css/grid.css'])
    @vite(['resources/js/script.js'])
    @vite(['resources/js/app.js'])



    <link href="{{ asset('build/assets/style.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/grid.css') }}" rel="stylesheet">
    <script src="{{ asset('build/assets/script.js') }}" defer></script>

    {{-- <script src="http://192.168.100.10:8000/js/script.js"></script> --}}

    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>

</head>
