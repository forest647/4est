@extends('layouts.app')

@section('title', __('Register') . ' - 4est.info')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-lg p-8">
        <h1 class="text-2xl font-bold text-white mb-6 text-center">{{ __('Register') }}</h1>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-900/50 border border-red-700 rounded text-red-300 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Name') }}</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D]">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D]">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Password') }}</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D]">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D]">
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-[#39792D] hover:bg-[#2d6023] text-white font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-[#39792D] focus:ring-offset-2 focus:ring-offset-slate-800">
                {{ __('Register') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}" class="text-[#39792D] hover:text-green-400 transition-colors">{{ __('Login') }}</a>
        </p>
    </div>
</div>
@endsection
