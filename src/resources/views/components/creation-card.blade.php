@props(['creation'])
@php
    $translation = $creation->translation;
    $coverImage = $creation->coverImage;
    $imageUrl = $coverImage
        ? asset('storage/creations/' . str_pad($creation->id, 8, '0', STR_PAD_LEFT) . '/' . $coverImage->filename)
        : asset('storage/res/generic_creation.jpg');
@endphp

<a href="{{ route('creations.show', ['locale' => app()->getLocale(), 'slug' => $translation?->slug ?? $creation->id]) }}"
   class="group block">
    <div class="bg-slate-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
        <div class="aspect-video overflow-hidden">
            <img src="{{ $imageUrl }}" alt="{{ $translation?->name ?? 'Creation' }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy">
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-lg truncate">{{ $translation?->name ?? 'Untitled' }}</h3>
            <span class="text-sm text-[#39792D]">{{ $creation->category?->name }}</span>
        </div>
    </div>
</a>
