@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
            <h3 class="text-sm text-slate-400 uppercase tracking-wide">Total Creations</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalCreations }}</p>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
            <h3 class="text-sm text-slate-400 uppercase tracking-wide">Total Users</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
        </div>
        <div class="bg-slate-800 border border-slate-700 rounded-lg p-6">
            <h3 class="text-sm text-slate-400 uppercase tracking-wide">Total Visits</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalVisits }}</p>
        </div>
    </div>

    {{-- Recent Visits --}}
    <h2 class="text-xl font-bold mb-4">Recent Visits</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-slate-800 border border-slate-700 rounded-lg">
            <thead>
                <tr class="border-b border-slate-700 text-left text-sm text-slate-400">
                    <th class="px-4 py-3">IP</th>
                    <th class="px-4 py-3">City</th>
                    <th class="px-4 py-3">Country</th>
                    <th class="px-4 py-3">Page</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentVisits as $visit)
                    <tr class="border-b border-slate-700/50 text-sm">
                        <td class="px-4 py-3">{{ $visit->ip }}</td>
                        <td class="px-4 py-3">{{ $visit->city ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $visit->country ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $visit->page }}</td>
                        <td class="px-4 py-3">{{ $visit->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-400">No visits recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
