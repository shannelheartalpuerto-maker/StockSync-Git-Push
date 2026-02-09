<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        // This method might not be used if we display brands in categories index
        // But good to have for API or standalone view if needed
        $brands = \App\Models\Brand::where('admin_id', $this->getAdminId())->get();
        return view('admin.brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        \App\Models\Brand::create([
            'name' => $request->name,
            'admin_id' => $this->getAdminId()
        ]);

        return redirect()->back()->with('success', 'Brand created successfully.');
    }

    public function update(Request $request, $id)
    {
        $brand = \App\Models\Brand::where('admin_id', $this->getAdminId())->findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $brand->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $brand = \App\Models\Brand::where('admin_id', $this->getAdminId())->findOrFail($id);
        $brand->delete();
        return redirect()->back()->with('success', 'Brand deleted successfully.');
    }
}
