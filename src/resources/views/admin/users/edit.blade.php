@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit User #{{ $user->id }}</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-lg space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm text-slate-300 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm text-slate-300 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm text-slate-300 mb-1">Password <span class="text-slate-500">(leave blank to keep current)</span></label>
            <input type="password" name="password" id="password"
                   class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
            @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm text-slate-300 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
        </div>

        <div>
            <label for="role" class="block text-sm text-slate-300 mb-1">Role</label>
            <select name="role" id="role"
                    class="w-full bg-slate-700 border border-slate-600 rounded px-3 py-2 text-white focus:border-[#39792D] focus:outline-none">
                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer</option>
                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-[#39792D] hover:bg-[#2d6023] text-white rounded transition">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-slate-600 hover:bg-slate-500 text-white rounded transition">
                Cancel
            </a>
        </div>
    </form>
@endsection
