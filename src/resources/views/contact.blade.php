@extends('layouts.app')

@section('title', __('Contact') . ' - 4est.info')

@section('content')
    <h1 class="text-3xl font-bold mb-8">{{ __('Contact') }}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        {{-- Left Sidebar: Contact Info --}}
        <div class="space-y-6">
            <div class="bg-slate-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">
                    {{ app()->getLocale() === 'ro' ? 'Informatii de contact' : 'Contact Information' }}
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#39792D] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-slate-400">{{ __('Email') }}</div>
                            <a href="mailto:contact@4est.info" class="text-white hover:text-[#39792D] transition-colors">
                                contact@4est.info
                            </a>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#39792D] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-slate-400">{{ app()->getLocale() === 'ro' ? 'Telefon' : 'Phone' }}</div>
                            <span class="text-white">+40 123 456 789</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-[#39792D] mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.121.553 4.116 1.523 5.852L.058 23.71a.5.5 0 00.607.607l5.858-1.465A11.948 11.948 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22a9.94 9.94 0 01-5.38-1.576l-.386-.231-3.474.868.868-3.474-.231-.386A9.94 9.94 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-slate-400">WhatsApp</div>
                            <a href="https://wa.me/40123456789" target="_blank" rel="noopener"
                               class="text-white hover:text-[#39792D] transition-colors">
                                +40 123 456 789
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Contact Form --}}
        <div class="lg:col-span-2">
            <div class="bg-slate-800 rounded-lg p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-900/50 border border-green-700 rounded-lg text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-900/50 border border-red-700 rounded-lg text-red-300">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.send', ['locale' => app()->getLocale()]) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Name') }} *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D] transition-colors">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Email') }} *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D] transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-300 mb-1">{{ app()->getLocale() === 'ro' ? 'Telefon' : 'Phone' }}</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D] transition-colors">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Subject') }} *</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                   class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D] transition-colors">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-slate-300 mb-1">{{ __('Message') }} *</label>
                        <textarea id="message" name="message" rows="6" required
                                  class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-400 focus:outline-none focus:border-[#39792D] focus:ring-1 focus:ring-[#39792D] transition-colors resize-y">{{ old('message') }}</textarea>
                    </div>

                    {{-- reCAPTCHA placeholder - will be implemented in Task 11 --}}

                    <button type="submit"
                            class="bg-[#39792D] hover:bg-[#2d5f23] text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                        {{ __('Send message') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Billing Info (hidden by default, shown with ?df=1) --}}
    @if(request('df') == 1)
        <section class="mb-12">
            <div class="bg-slate-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">
                    {{ app()->getLocale() === 'ro' ? 'Date de facturare' : 'Billing Information' }}
                </h2>
                <div class="text-slate-300 space-y-2">
                    <p><strong>{{ app()->getLocale() === 'ro' ? 'Nume' : 'Name' }}:</strong> Ionel Padure</p>
                    <p><strong>{{ app()->getLocale() === 'ro' ? 'Adresa' : 'Address' }}:</strong> —</p>
                    <p><strong>{{ app()->getLocale() === 'ro' ? 'CUI' : 'Tax ID' }}:</strong> —</p>
                    <p><strong>{{ app()->getLocale() === 'ro' ? 'Banca' : 'Bank' }}:</strong> —</p>
                    <p><strong>IBAN:</strong> —</p>
                </div>
            </div>
        </section>
    @endif
@endsection
