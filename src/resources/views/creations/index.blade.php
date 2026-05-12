@extends('layouts.app')

@section('title', __('Creations') . ' - 4est.info')

@section('content')
    <h1 class="text-3xl font-bold mb-6">{{ __('Creations') }}</h1>

    {{-- Category Filters --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('creations.index', ['locale' => app()->getLocale()]) }}"
           class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('category') ? 'bg-[#39792D] text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
            {{ __('All categories') }}
        </a>
        @foreach($categories as $category)
            <a href="{{ route('creations.index', ['locale' => app()->getLocale(), 'category' => $category->slug]) }}"
               class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('category') === $category->slug ? 'bg-[#39792D] text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    {{-- Creations Grid --}}
    @if($creations->isEmpty())
        <p class="text-slate-400">{{ __('No creations found.') }}</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($creations as $creation)
                <x-creation-card :creation="$creation" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $creations->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
