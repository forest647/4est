@extends('layouts.app')

@section('title', ($translation?->name ?? 'Creation') . ' - 4est.info')

@section('content')
    {{-- Previous / Next Navigation --}}
    <div class="flex items-center justify-between mb-6">
        @if($prev)
            @php
                $prevTranslation = $prev->translations?->where('locale', app()->getLocale())->first()
                    ?? $prev->translation;
            @endphp
            <a href="{{ route('creations.show', ['locale' => app()->getLocale(), 'slug' => $prevTranslation?->slug ?? $prev->id]) }}"
               class="text-slate-400 hover:text-[#39792D] transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('Previous') }}
            </a>
        @else
            <span></span>
        @endif

        @if($next)
            @php
                $nextTranslation = $next->translations?->where('locale', app()->getLocale())->first()
                    ?? $next->translation;
            @endphp
            <a href="{{ route('creations.show', ['locale' => app()->getLocale(), 'slug' => $nextTranslation?->slug ?? $next->id]) }}"
               class="text-slate-400 hover:text-[#39792D] transition-colors flex items-center gap-1">
                {{ __('Next') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        {{-- Left: Image Gallery (2/3) --}}
        <div class="lg:col-span-2">
            @php
                $paddedId = str_pad($creation->id, 8, '0', STR_PAD_LEFT);
                $galleryImages = $creation->galleryImages;
                $firstImage = $galleryImages->first();
                $mainImageUrl = $firstImage
                    ? asset('storage/creations/' . $paddedId . '/' . $firstImage->filename)
                    : asset('storage/res/generic_creation.jpg');
            @endphp

            {{-- Main Image --}}
            <div class="rounded-lg overflow-hidden bg-slate-800 mb-4">
                <img id="mainImage" src="{{ $mainImageUrl }}" alt="{{ $translation?->name ?? 'Creation' }}"
                     class="w-full max-h-[500px] object-contain mx-auto">
            </div>

            {{-- Thumbnails --}}
            @if($galleryImages->count() > 1)
                <div class="grid grid-cols-6 gap-2">
                    @foreach($galleryImages as $image)
                        @php
                            $thumbUrl = asset('storage/creations/' . $paddedId . '/' . $image->filename);
                        @endphp
                        <button onclick="changeImage('{{ $thumbUrl }}')"
                                class="rounded overflow-hidden border-2 border-transparent hover:border-[#39792D] transition-colors focus:border-[#39792D] focus:outline-none">
                            <img src="{{ $thumbUrl }}" alt="{{ $translation?->name ?? 'Thumbnail' }}"
                                 class="w-full aspect-square object-cover" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: Details Card (1/3) --}}
        <div>
            <div class="bg-slate-800 rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $translation?->name ?? 'Untitled' }}</h1>

                <div class="space-y-3 mb-6">
                    @if($creation->category)
                        <div class="flex justify-between">
                            <span class="text-slate-400">{{ __('Category') }}</span>
                            <span>{{ $creation->category->name }}</span>
                        </div>
                    @endif

                    @if($creation->material)
                        <div class="flex justify-between">
                            <span class="text-slate-400">{{ __('Material') }}</span>
                            <span>{{ $creation->material->name }}</span>
                        </div>
                    @endif

                    @if($creation->size)
                        <div class="flex justify-between">
                            <span class="text-slate-400">{{ __('Size') }}</span>
                            <span>{{ $creation->size }}</span>
                        </div>
                    @endif

                    @if($creation->price)
                        <div class="flex justify-between">
                            <span class="text-slate-400">{{ __('Price') }}</span>
                            <span class="text-[#39792D] font-semibold">{{ $creation->price }}</span>
                        </div>
                    @endif
                </div>

                @if($creation->download)
                    <a href="{{ $creation->download }}" target="_blank" rel="noopener"
                       class="block w-full text-center bg-[#39792D] hover:bg-[#2d5f23] text-white font-semibold py-3 rounded-lg transition-colors">
                        {{ __('Download') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Description --}}
    @if($translation?->description)
        <section class="mb-12">
            <h2 class="text-xl font-bold mb-4">{{ __('Description') }}</h2>
            <div class="bg-slate-800 rounded-lg p-6 prose prose-invert max-w-none">
                {!! $translation->description !!}
            </div>
        </section>
    @endif

    {{-- Related Creations --}}
    @if($related->isNotEmpty())
        <section class="mb-12">
            <h2 class="text-xl font-bold mb-6">{{ __('Related creations') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related as $relatedCreation)
                    <x-creation-card :creation="$relatedCreation" />
                @endforeach
            </div>
        </section>
    @endif
@endsection

@push('scripts')
<script>
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
    }
</script>
@endpush
