@php
    $otherLocale = app()->getLocale() === 'ro' ? 'en' : 'ro';
    $switchUrl = preg_replace('#^/' . app()->getLocale() . '#', '/' . $otherLocale, request()->getRequestUri());
    $currentRoute = request()->route()?->getName();
@endphp

<nav class="bg-slate-800 border-b border-slate-700 sticky top-0 z-50">
    <div class="container mx-auto max-w-7xl px-4">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="flex items-center gap-2">
                <img src="{{ asset('storage/res/logo_sm.png') }}" alt="4est" class="h-8 w-8">
                <span class="text-xl font-bold text-[#39792D]">4est</span>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                   class="text-sm font-medium transition-colors {{ $currentRoute === 'home' ? 'text-[#39792D]' : 'text-slate-300 hover:text-[#39792D]' }}">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('creations.index', ['locale' => app()->getLocale()]) }}"
                   class="text-sm font-medium transition-colors {{ $currentRoute === 'creations.index' || $currentRoute === 'creations.show' ? 'text-[#39792D]' : 'text-slate-300 hover:text-[#39792D]' }}">
                    {{ __('Creations') }}
                </a>
                <a href="{{ route('about', ['locale' => app()->getLocale()]) }}"
                   class="text-sm font-medium transition-colors {{ $currentRoute === 'about' ? 'text-[#39792D]' : 'text-slate-300 hover:text-[#39792D]' }}">
                    {{ __('About') }}
                </a>
                <a href="{{ route('contact.show', ['locale' => app()->getLocale()]) }}"
                   class="text-sm font-medium transition-colors {{ $currentRoute === 'contact.show' ? 'text-[#39792D]' : 'text-slate-300 hover:text-[#39792D]' }}">
                    {{ __('Contact') }}
                </a>
            </div>

            {{-- Auth Links + Language Switcher + Mobile Toggle --}}
            <div class="flex items-center gap-4">
                {{-- Auth Links (Desktop) --}}
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="/admin" class="text-sm font-medium text-slate-300 hover:text-[#39792D] transition-colors">{{ __('Admin') }}</a>
                        @endif
                        <span class="text-sm text-slate-400">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-slate-300 hover:text-[#39792D] transition-colors">{{ __('Logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-[#39792D] transition-colors">{{ __('Login') }}</a>
                    @endauth
                </div>

                {{-- Language Switcher --}}
                <div class="flex items-center gap-1 text-sm">
                    <a href="{{ app()->getLocale() === 'ro' ? '#' : $switchUrl }}"
                       class="{{ app()->getLocale() === 'ro' ? 'text-[#39792D] font-bold' : 'text-slate-400 hover:text-white' }}">
                        RO
                    </a>
                    <span class="text-slate-600">|</span>
                    <a href="{{ app()->getLocale() === 'en' ? '#' : $switchUrl }}"
                       class="{{ app()->getLocale() === 'en' ? 'text-[#39792D] font-bold' : 'text-slate-400 hover:text-white' }}">
                        EN
                    </a>
                </div>

                {{-- Mobile Hamburger --}}
                <button id="mobileMenuBtn" class="md:hidden text-slate-300 hover:text-white" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menuIcon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden md:hidden pb-4">
            <div class="flex flex-col gap-2">
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}"
                   class="px-3 py-2 rounded text-sm font-medium {{ $currentRoute === 'home' ? 'bg-slate-700 text-[#39792D]' : 'text-slate-300 hover:bg-slate-700' }}">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('creations.index', ['locale' => app()->getLocale()]) }}"
                   class="px-3 py-2 rounded text-sm font-medium {{ $currentRoute === 'creations.index' || $currentRoute === 'creations.show' ? 'bg-slate-700 text-[#39792D]' : 'text-slate-300 hover:bg-slate-700' }}">
                    {{ __('Creations') }}
                </a>
                <a href="{{ route('about', ['locale' => app()->getLocale()]) }}"
                   class="px-3 py-2 rounded text-sm font-medium {{ $currentRoute === 'about' ? 'bg-slate-700 text-[#39792D]' : 'text-slate-300 hover:bg-slate-700' }}">
                    {{ __('About') }}
                </a>
                <a href="{{ route('contact.show', ['locale' => app()->getLocale()]) }}"
                   class="px-3 py-2 rounded text-sm font-medium {{ $currentRoute === 'contact.show' ? 'bg-slate-700 text-[#39792D]' : 'text-slate-300 hover:bg-slate-700' }}">
                    {{ __('Contact') }}
                </a>

                {{-- Mobile Auth Links --}}
                <div class="border-t border-slate-700 mt-2 pt-2">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="/admin" class="px-3 py-2 rounded text-sm font-medium text-slate-300 hover:bg-slate-700 block">{{ __('Admin') }}</a>
                        @endif
                        <span class="px-3 py-2 text-sm text-slate-400 block">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded text-sm font-medium text-slate-300 hover:bg-slate-700 w-full text-left">{{ __('Logout') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded text-sm font-medium text-slate-300 hover:bg-slate-700 block">{{ __('Login') }}</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('mobileMenuBtn').addEventListener('click', function () {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
</script>
