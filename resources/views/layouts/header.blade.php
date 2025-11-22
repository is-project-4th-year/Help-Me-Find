<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Help Me Find</title>

    {{-- DARK MODE SCRIPT --}}
    {{-- <script>
        // Runs before the DOM is loaded to prevent FOUC (Flash of Unstyled Content)
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script> --}}
    {{-- END DARK MODE SCRIPT --}}

    @vite(['resources/css/style.css'])
    @vite(['resources/css/list.css'])

    @vite(['resources/js/script.js'])
    @vite(['resources/js/app.js'])

    {{-- <script src="http://192.168.100.10:8000/js/script.js"></script> --}}

    <link rel="icon" href="{{ asset('images/bookLogo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



</head>
