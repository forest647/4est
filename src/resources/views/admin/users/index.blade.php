@extends('layouts.admin')

@section('title', 'Users')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ __('Users') }}</h1>
        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2 bg-[#39792D] hover:bg-[#2d6023] text-white rounded transition">
            + New User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full bg-slate-800 border border-slate-700 rounded-lg">
            <thead>
                <tr class="border-b border-slate-700 text-left text-sm text-slate-400">
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Created</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b border-slate-700/50 text-sm">
                        <td class="px-4 py-3">{{ $user->id }}</td>
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs {{ $user->role === 'admin' ? 'bg-[#39792D]/30 text-green-300' : 'bg-slate-600 text-slate-300' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="px-3 py-1 bg-slate-600 hover:bg-slate-500 rounded text-sm transition">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-700 hover:bg-red-600 rounded text-sm transition">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-400">No users yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
