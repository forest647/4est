<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - @yield('title', '4est')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <div class="flex min-h-screen">
        {{-- Mobile menu toggle --}}
        <button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 bg-slate-800 rounded">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Sidebar --}}
        <aside id="sidebar" class="w-64 bg-slate-800 border-r border-slate-700 flex-shrink-0 fixed lg:static inset-y-0 left-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
            <div class="p-4 border-b border-slate-700">
                <h2 class="text-xl font-bold">4est Admin</h2>
                <p class="text-sm text-slate-400">{{ Auth::user()->name }}</p>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-4 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-[#39792D] text-white' : 'hover:bg-slate-700' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.creations.index') }}"
                   class="block px-4 py-2 rounded {{ request()->routeIs('admin.creations.*') ? 'bg-[#39792D] text-white' : 'hover:bg-slate-700' }}">
                    {{ __('Creations') }}
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="block px-4 py-2 rounded {{ request()->routeIs('admin.users.*') ? 'bg-[#39792D] text-white' : 'hover:bg-slate-700' }}">
                    {{ __('Users') }}
                </a>
                <hr class="border-slate-700 my-4">
                <a href="{{ route('home', ['locale' => config('app.locale')]) }}"
                   class="block px-4 py-2 rounded hover:bg-slate-700">
                    &larr; {{ __('Back') }} to Site
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded hover:bg-slate-700 text-red-400">
                        {{ __('Logout') }}
                    </button>
                </form>
            </nav>
        </aside>

        {{-- Sidebar overlay for mobile --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"></div>

        {{-- Main content --}}
        <main class="flex-1 p-6 lg:p-8 pt-16 lg:pt-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-900/50 border border-green-700 rounded text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-900/50 border border-red-700 rounded text-red-300">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script>
        const toggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });
            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
