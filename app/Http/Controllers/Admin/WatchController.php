<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Watch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class WatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $watches = Watch::query()
            ->with(['brand', 'category', 'supplier', 'inventory'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.watches.index', compact('watches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all();
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.watches.create', compact('brands', 'categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "brand_id" => 'required|exists:brands,id',
            "category_id" => 'required|exists:categories,id',
            "supplier_id" => 'required|exists:suppliers,id',
            "price" => "required|numeric",
            "name" => "required|string",
            "description" => "required|string",
            "size" => "required|integer",
        ]);

        if ($request->hasFile("image")) {
            $validated["image_path"] = $request->file("image")->store("watches", "public");
        }

        Watch::create($validated);

        return redirect()->route("admin.watches.index")->with("success", "Watch created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Watch $watch)
    {
        return view('admin.watches.show', compact('watch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Watch $watch)
    {
        $brands = Brand::all();
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.watches.edit', compact('watch', 'brands', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Watch $watch)
    {
        $validated = $request->validate([
            "brand_id" => 'required|exists:brands,id',
            "category_id" => 'required|exists:categories,id',
            "supplier_id" => 'required|exists:suppliers,id',
            "price" => "required|numeric",
            "name" => "required|string",
            "description" => "required|string",
            "size" => "required|integer",
        ]);

        if ($request->hasFile("image")) {
            $validated["image_path"] = $request->file("image")->store("watches", "public");
        }

        $watch->update($validated);

        return redirect()->route("admin.watches.index")->with("success", "Watch updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Watch $watch)
    {
        $watch->delete();
        return redirect()->route("admin.watches.index")->with("success", "Watch deleted");
    }
}
