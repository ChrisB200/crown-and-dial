<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(15);
        return view("admin.suppliers.index", compact("suppliers"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.suppliers.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "contact" => "required|string",
        ]);

        Supplier::create($validated);

        return redirect()->route("admin.suppliers.index")->with("success", "Supplier created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return view("admin.suppliers.show", compact("supplier"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view("admin.suppliers.edit", compact("supplier"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            "name" => "required|string",
            "contact" => "required|string",
        ]);

        $supplier->update($validated);

        return redirect()->route("admin.suppliers.index")->with("success", "Supplier updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {

        $supplier->delete();
        return redirect()->route("admin.suppliers.index")->with("success", "Supplier deleted");
    }
}
