<?php

namespace App\Http\Controllers;

use App\Models\Creation;

class HomeController extends Controller
{
    public function index(string $locale)
    {
        $featured = Creation::with(['translation', 'coverImage', 'category'])
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('featured'));
    }
}
