@extends('layouts.admin')

@section('title', 'Creations')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ __('Creations') }}</h1>
        <a href="{{ route('admin.creations.create') }}"
           class="px-4 py-2 bg-[#39792D] hover:bg-[#2d6023] text-white rounded transition">
            + New Creation
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full bg-slate-800 border border-slate-700 rounded-lg">
            <thead>
                <tr class="border-b border-slate-700 text-left text-sm text-slate-400">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Name (EN)</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($creations as $creation)
                    <tr class="border-b border-slate-700/50 text-sm">
                        <td class="px-4 py-3">{{ $creation->id }}</td>
                        <td class="px-4 py-3">{{ $creation->translations->first()->name ?? '(no translation)' }}</td>
                        <td class="px-4 py-3">{{ $creation->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">&euro;{{ number_format($creation->price, 2) }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.creations.edit', $creation) }}"
                               class="px-3 py-1 bg-slate-600 hover:bg-slate-500 rounded text-sm transition">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.creations.destroy', $creation) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this creation?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1 bg-red-700 hover:bg-red-600 rounded text-sm transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-400">No creations yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
