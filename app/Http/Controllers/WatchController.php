<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Watch;

class WatchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q'); // ← unified input name

        $query = Watch::with('brand', 'category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('brand', function ($b) use ($search) {
                        $b->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $watches = $query->latest()->paginate(12)->withQueryString();
        $categoryName = "ALL";

        return view('watches.index', compact('watches', 'categoryName', 'search'));
    }

    public function show(Watch $watch)
    {
        return view('watches.show', compact('watch'));
    }

    public function category(string $slug, Request $request)
    {
        $category = Category::where("name", $slug)->firstOrFail();

        $query = Watch::where("category_id", $category->id);

        if ($search = $request->input('q')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $watches = $query->paginate(12)->withQueryString();
        $categoryName = strtoupper($category->name);

        return view("watches.index", compact("watches", "categoryName", "search"));
    }
}
