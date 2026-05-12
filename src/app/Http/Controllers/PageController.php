<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about(string $locale)
    {
        return view('about');
    }
}
