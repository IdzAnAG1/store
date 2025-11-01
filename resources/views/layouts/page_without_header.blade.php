<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="grid min-h-screen  items-center bg-[#1A1D21] text-white">

<main class="max-w-6xl mx-auto px-6 py-10 ">
    @yield('content')
</main>

<footer class="max-w-6xl mx-auto px-6 py-8 text-sm text-gray-500">
    © {{ date('Y-m-d') }} {{ config('app.name') }}
</footer>
</body>

</html>
