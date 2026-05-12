<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Creation;
use App\Models\CreationTranslation;
use App\Models\GalleryImage;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;
use Illuminate\Support\Str;

class AdminCreationController extends Controller
{
    public function __construct(private ImageService $imageService) {}
    public function index()
    {
        $creations = Creation::with(['translations' => fn($q) => $q->where('locale', 'en'), 'category'])->latest()->get();
        return view('admin.creations.index', compact('creations'));
    }

    public function create()
    {
        $categories = Category::all();
        $materials = Material::all();
        return view('admin.creations.create', compact('categories', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'material_id' => 'required|exists:materials,id',
            'translations.en.name' => 'required|string|max:255',
            'translations.en.description' => 'required|string',
            'translations.ro.name' => 'nullable|string|max:255',
            'translations.ro.description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'download' => 'nullable|file|max:51200',
        ]);

        $creation = Creation::create([
            'size' => $request->size,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'material_id' => $request->material_id,
        ]);

        // Handle download file
        if ($request->hasFile('download')) {
            $downloadDir = 'downloads/' . str_pad($creation->id, 8, '0', STR_PAD_LEFT);
            $request->file('download')->storeAs($downloadDir, $request->file('download')->getClientOriginalName(), 'public');
            $creation->update(['download' => $request->file('download')->getClientOriginalName()]);
        }

        // Save translations
        foreach (['en', 'ro'] as $locale) {
            $transData = $request->input("translations.{$locale}");
            if (!empty($transData['name'])) {
                CreationTranslation::create([
                    'creation_id' => $creation->id,
                    'locale' => $locale,
                    'name' => $transData['name'],
                    'slug' => Str::slug($transData['name']),
                    'description' => $transData['description'] ?? '',
                ]);
            }
        }

        // Handle image uploads
        if ($request->hasFile('images')) {
            $ranking = 0;
            foreach ($request->file('images') as $image) {
                $this->imageService->processAndStore($image, $creation->id);
                GalleryImage::create([
                    'creation_id' => $creation->id,
                    'filename' => $image->getClientOriginalName(),
                    'ranking' => $ranking++,
                ]);
            }
        }

        return redirect()->route('admin.creations.index')->with('success', 'Creation added successfully.');
    }

    public function edit(Creation $creation)
    {
        $creation->load(['translations', 'galleryImages']);
        $categories = Category::all();
        $materials = Material::all();
        $translations = $creation->translations->keyBy('locale');
        return view('admin.creations.edit', compact('creation', 'categories', 'materials', 'translations'));
    }

    public function update(Request $request, Creation $creation)
    {
        $request->validate([
            'size' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'material_id' => 'required|exists:materials,id',
            'translations.en.name' => 'required|string|max:255',
            'translations.en.description' => 'required|string',
            'translations.ro.name' => 'nullable|string|max:255',
            'translations.ro.description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
            'download' => 'nullable|file|max:51200',
        ]);

        $creation->update([
            'size' => $request->size,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'material_id' => $request->material_id,
        ]);

        // Handle download
        if ($request->hasFile('download')) {
            $downloadDir = 'downloads/' . str_pad($creation->id, 8, '0', STR_PAD_LEFT);
            $request->file('download')->storeAs($downloadDir, $request->file('download')->getClientOriginalName(), 'public');
            $creation->update(['download' => $request->file('download')->getClientOriginalName()]);
        }

        // Update translations
        foreach (['en', 'ro'] as $locale) {
            $transData = $request->input("translations.{$locale}");
            if (!empty($transData['name'])) {
                CreationTranslation::updateOrCreate(
                    ['creation_id' => $creation->id, 'locale' => $locale],
                    [
                        'name' => $transData['name'],
                        'slug' => Str::slug($transData['name']),
                        'description' => $transData['description'] ?? '',
                    ]
                );
            }
        }

        // Delete selected images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $img = GalleryImage::find($imageId);
                if ($img) {
                    $this->imageService->deleteImage($creation->id, $img->filename);
                    $img->delete();
                }
            }
        }

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $maxRanking = $creation->galleryImages()->max('ranking') ?? -1;
            foreach ($request->file('images') as $image) {
                $this->imageService->processAndStore($image, $creation->id);
                GalleryImage::create([
                    'creation_id' => $creation->id,
                    'filename' => $image->getClientOriginalName(),
                    'ranking' => ++$maxRanking,
                ]);
            }
        }

        return redirect()->route('admin.creations.index')->with('success', 'Creation updated successfully.');
    }

    public function destroy(Creation $creation)
    {
        // Delete image files
        $this->imageService->deleteCreationImages($creation->id);

        // Delete download files
        $downloadDir = 'downloads/' . str_pad($creation->id, 8, '0', STR_PAD_LEFT);
        Storage::disk('public')->deleteDirectory($downloadDir);

        $creation->delete();

        return redirect()->route('admin.creations.index')->with('success', 'Creation deleted successfully.');
    }
}
