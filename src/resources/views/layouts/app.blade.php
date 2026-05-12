<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="4est.info - Laser cutting, 3D printing, woodworking creations">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '4est.info')</title>
    <link rel="icon" href="{{ asset('storage/res/logo_sm.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col">
    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main class="flex-1 container mx-auto px-4 py-6 max-w-7xl">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    @stack('scripts')
</body>
</html>
