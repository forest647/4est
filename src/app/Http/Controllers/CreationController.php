<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Creation;
use Illuminate\Http\Request;

class CreationController extends Controller
{
    public function index(string $locale, Request $request)
    {
        $query = Creation::with(['translation', 'coverImage', 'category']);

        if ($request->has('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $creations = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('creations.index', compact('creations', 'categories'));
    }

    public function show(string $locale, string $slug)
    {
        $fallback = config('app.fallback_locale', 'en');

        $creation = Creation::whereHas('translations',
            fn ($q) => $q->where('slug', $slug)
        )->with(['translations', 'galleryImages', 'category', 'material'])->firstOrFail();

        $translation = $creation->translations->firstWhere('locale', $locale)
            ?? $creation->translations->firstWhere('locale', $fallback)
            ?? $creation->translations->first();

        $related = Creation::where('category_id', $creation->category_id)
            ->where('id', '!=', $creation->id)
            ->with(['translation', 'coverImage'])
            ->take(4)
            ->get();

        $next = Creation::where('id', '>', $creation->id)->orderBy('id')->first();
        $prev = Creation::where('id', '<', $creation->id)->orderBy('id', 'desc')->first();

        return view('creations.show', compact('creation', 'translation', 'related', 'next', 'prev'));
    }
}
