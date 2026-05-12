<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Creation;
use App\Models\Statistic;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $recentVisits = Statistic::latest()->take(100)->get();
        $totalVisits = Statistic::count();
        $totalCreations = Creation::count();
        $totalUsers = User::count();

        return view('admin.dashboard', compact('recentVisits', 'totalVisits', 'totalCreations', 'totalUsers'));
    }
}
