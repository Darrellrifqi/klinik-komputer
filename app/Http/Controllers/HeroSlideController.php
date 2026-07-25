<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('order_index')->get();
        return view('dashboard.superadmin.hero.index', compact('slides'));
    }

    public function create()
    {
        return view('dashboard.superadmin.hero.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
            'tag' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'cta_text_primary' => 'nullable|string|max:50',
            'cta_url_primary' => 'nullable|string|max:255',
            'cta_text_secondary' => 'nullable|string|max:50',
            'cta_url_secondary' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'order_index' => 'nullable|integer',
        ]);

        $imagePath = $request->file('image')->store('hero_slides', 'public');

        HeroSlide::create([
            'image_path' => $imagePath,
            'tag' => $request->tag,
            'title' => $request->title,
            'description' => $request->description,
            'cta_text_primary' => $request->cta_text_primary,
            'cta_url_primary' => $request->cta_url_primary,
            'cta_text_secondary' => $request->cta_text_secondary,
            'cta_url_secondary' => $request->cta_url_secondary,
            'is_active' => $request->has('is_active'),
            'order_index' => $request->order_index ?? 0,
        ]);

        return redirect()->route('admin.hero')->with('success', 'Hero slide berhasil ditambahkan.');
    }

    public function edit(HeroSlide $hero)
    {
        return view('dashboard.superadmin.hero.edit', compact('hero'));
    }

    public function update(Request $request, HeroSlide $hero)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'tag' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'cta_text_primary' => 'nullable|string|max:50',
            'cta_url_primary' => 'nullable|string|max:255',
            'cta_text_secondary' => 'nullable|string|max:50',
            'cta_url_secondary' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'order_index' => 'nullable|integer',
        ]);

        $data = [
            'tag' => $request->tag,
            'title' => $request->title,
            'description' => $request->description,
            'cta_text_primary' => $request->cta_text_primary,
            'cta_url_primary' => $request->cta_url_primary,
            'cta_text_secondary' => $request->cta_text_secondary,
            'cta_url_secondary' => $request->cta_url_secondary,
            'is_active' => $request->has('is_active'),
            'order_index' => $request->order_index ?? 0,
        ];

        if ($request->hasFile('image')) {
            if ($hero->image_path && Storage::disk('public')->exists($hero->image_path)) {
                Storage::disk('public')->delete($hero->image_path);
            }
            $data['image_path'] = $request->file('image')->store('hero_slides', 'public');
        }

        $hero->update($data);

        return redirect()->route('admin.hero')->with('success', 'Hero slide berhasil diperbarui.');
    }

    public function destroy(HeroSlide $hero)
    {
        if ($hero->image_path && Storage::disk('public')->exists($hero->image_path)) {
            Storage::disk('public')->delete($hero->image_path);
        }
        $hero->delete();

        return redirect()->route('admin.hero')->with('success', 'Hero slide berhasil dihapus.');
    }
}
