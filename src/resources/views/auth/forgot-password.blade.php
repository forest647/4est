@extends('layouts.app')

@section('title', __('Forgot Password') . ' - 4est.info')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-lg p-8">
        <h1 class="text-2xl font-bold text-white mb-2 text-center">{{ __('Forgot Password') }}</h1>
        <p class="text-sm text-slate-400 text-center mb-6">{{ __('Enter your email and we will send you a reset link.') }}</p>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-900/50 border border-green-700 rounded text-green-300 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-900/50 border border-red-700 rounded text-red-300 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D]">
            </div>

            <button type="submit"
                    class="w-full py-2 px-4 bg-[#39792D] hover:bg-[#2d6023] text-white font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-[#39792D] focus:ring-offset-2 focus:ring-offset-slate-800">
                {{ __('Send Reset Link') }}
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            <a href="{{ route('login') }}" class="text-[#39792D] hover:text-green-400 transition-colors">{{ __('Back to login') }}</a>
        </p>
    </div>
</div>
@endsection
