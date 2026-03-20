<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Watch;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q'); // ← unified input name
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $query = Watch::with('brand', 'category', 'reviews.user');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($b) use ($search) {
                        $b->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        $watches = $query->latest()->paginate(12)->withQueryString();
        $categoryName = 'ALL';
        $wishlistIds = auth()->check()
            ? auth()->user()->wishlistWatches()->pluck('watch_id')->all()
            : [];

        return view('watches.index', compact('watches', 'categoryName', 'search', 'wishlistIds'));
    }

    public function show(Watch $watch)
    {
        $watch->load('inventory');
        $stockStatus = $watch->stockStatus();

        $inWishlist = auth()->check()
            ? auth()->user()->wishlistWatches()->where('watch_id', $watch->id)->exists()
            : false;

        return view('watches.show', compact('watch', 'inWishlist', 'stockStatus'));
    }

    public function category(string $slug, Request $request)
    {
        $category = Category::where('name', $slug)->firstOrFail();

        $query = Watch::where('category_id', $category->id);

        $search = $request->input('q');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        $watches = $query->paginate(12)->withQueryString();
        $categoryName = strtoupper($category->name);
        $wishlistIds = auth()->check()
            ? auth()->user()->wishlistWatches()->pluck('watch_id')->all()
            : [];

        return view('watches.index', compact('watches', 'categoryName', 'search', 'wishlistIds'));
    }
}
