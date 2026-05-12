@extends('layouts.app')

@section('title', '4est.info - ' . __('Home'))

@section('content')
    {{-- Hero Section --}}
    <section class="relative rounded-xl overflow-hidden mb-12">
        <div class="relative h-80 md:h-96">
            <img src="{{ asset('storage/res/01.png') }}" alt="4est hero"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 to-slate-900/40"></div>
            <div class="absolute inset-0 flex items-center">
                <div class="px-8 md:px-16 max-w-2xl">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('Welcome to 4est') }}</h1>
                    <p class="text-lg text-slate-300 mb-6">
                        {{ app()->getLocale() === 'ro'
                            ? 'Creații realizate prin tăiere laser, imprimare 3D și prelucrarea lemnului.'
                            : 'Creations made through laser cutting, 3D printing, and woodworking.' }}
                    </p>
                    <a href="{{ route('creations.index', ['locale' => app()->getLocale()]) }}"
                       class="inline-block bg-[#39792D] hover:bg-[#2d5f23] text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                        {{ __('Creations') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Etsy Promo Section --}}
    <section class="mb-12">
        <div class="bg-slate-800 rounded-xl p-8 flex flex-col md:flex-row items-center gap-6">
            <div class="flex-1">
                <h2 class="text-2xl font-bold mb-3">
                    {{ app()->getLocale() === 'ro' ? 'Planuri digitale disponibile' : 'Digital plans available' }}
                </h2>
                <p class="text-slate-400 mb-4">
                    {{ app()->getLocale() === 'ro'
                        ? 'Descoperă planurile digitale pentru proiectele tale de tăiere laser pe magazinul nostru Etsy.'
                        : 'Discover digital plans for your laser cutting projects on our Etsy shop.' }}
                </p>
                <a href="https://www.etsy.com/shop/4estInfo" target="_blank" rel="noopener"
                   class="inline-block border border-[#39792D] text-[#39792D] hover:bg-[#39792D] hover:text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                    {{ app()->getLocale() === 'ro' ? 'Vizitează magazinul Etsy' : 'Visit Etsy Shop' }}
                </a>
            </div>
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/res/logo_sm.png') }}" alt="4est logo" class="w-24 h-24 opacity-50">
            </div>
        </div>
    </section>

    {{-- Featured Creations --}}
    <section class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">{{ __('Featured creations') }}</h2>
            <a href="{{ route('creations.index', ['locale' => app()->getLocale()]) }}"
               class="text-[#39792D] hover:underline text-sm font-medium">
                {{ __('View all creations') }} &rarr;
            </a>
        </div>

        @if($featured->isEmpty())
            <p class="text-slate-400">{{ __('No creations found.') }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featured as $creation)
                    <x-creation-card :creation="$creation" />
                @endforeach
            </div>
        @endif
    </section>
@endsection
