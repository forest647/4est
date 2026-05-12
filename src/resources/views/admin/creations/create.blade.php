@extends('layouts.admin')

@section('title', 'New Creation')

@section('content')
    <h1 class="text-2xl font-bold mb-6">New Creation</h1>

    <form method="POST" action="{{ route('admin.creations.store') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf

        {{-- Basic Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="size" class="block text-sm text-slate-300 mb-1">Size</label>
                <input type="text" name="size" id="size" value="{{ old('size') }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
                @error('size') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="price" class="block text-sm text-slate-300 mb-1">Price</label>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
                @error('price') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="category_id" class="block text-sm text-slate-300 mb-1">Category</label>
                <select name="category_id" id="category_id"
                        class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
                    <option value="">-- Select --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="material_id" class="block text-sm text-slate-300 mb-1">Material</label>
                <select name="material_id" id="material_id"
                        class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
                    <option value="">-- Select --</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                            {{ $material->name }}
                        </option>
                    @endforeach
                </select>
                @error('material_id') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="download" class="block text-sm text-slate-300 mb-1">Download File</label>
            <input type="file" name="download" id="download"
                   class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-slate-600 file:text-white">
            @error('download') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Translations --}}
        <div class="space-y-6">
            <h2 class="text-lg font-semibold border-b border-slate-700 pb-2">English Translation</h2>
            <div>
                <label for="translations_en_name" class="block text-sm text-slate-300 mb-1">Name (EN) *</label>
                <input type="text" name="translations[en][name]" id="translations_en_name"
                       value="{{ old('translations.en.name') }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
                @error('translations.en.name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="translations_en_description" class="block text-sm text-slate-300 mb-1">Description (EN) *</label>
                <textarea name="translations[en][description]" id="translations_en_description" rows="4"
                          class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">{{ old('translations.en.description') }}</textarea>
                @error('translations.en.description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <h2 class="text-lg font-semibold border-b border-slate-700 pb-2">Romanian Translation</h2>
            <div>
                <label for="translations_ro_name" class="block text-sm text-slate-300 mb-1">Name (RO)</label>
                <input type="text" name="translations[ro][name]" id="translations_ro_name"
                       value="{{ old('translations.ro.name') }}"
                       class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
                @error('translations.ro.name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="translations_ro_description" class="block text-sm text-slate-300 mb-1">Description (RO)</label>
                <textarea name="translations[ro][description]" id="translations_ro_description" rows="4"
                          class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">{{ old('translations.ro.description') }}</textarea>
                @error('translations.ro.description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Images --}}
        <div>
            <label for="images" class="block text-sm text-slate-300 mb-1">Gallery Images</label>
            <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png"
                   class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:bg-slate-600 file:text-white">
            @error('images.*') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-[#39792D] hover:bg-[#2d6023] text-white rounded transition">
                Save
            </button>
            <a href="{{ route('admin.creations.index') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded transition">
                Cancel
            </a>
        </div>
    </form>
@endsection
