<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::where('admin_id', $this->getAdminId())->get();
        $brands = \App\Models\Brand::where('admin_id', $this->getAdminId())->get();
        return view('admin.categories.index', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        \App\Models\Category::create([
            'name' => $request->name,
            'admin_id' => $this->getAdminId()
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::where('admin_id', $this->getAdminId())->findOrFail($id);
        $request->validate(['name' => 'required|string|max:255']);
        $category->update(['name' => $request->name]);
        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = \App\Models\Category::where('admin_id', $this->getAdminId())->findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }
}
