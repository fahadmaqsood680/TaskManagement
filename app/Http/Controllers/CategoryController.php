<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $user = Auth::user();
        if($user->role == 'admin'){
            $categories = Category::all();
        }else{
            $categories=Category::where('user_id',$user->id)->get();
        }
      
        return view('pages.categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.categories.add-edit-category');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);
        Category::create([
            'name'=>$request->name,
            'user_id'=>Auth::user()->id
        ]);
        return redirect()->route('categories.index')->with('success', 'Category added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('view', $category);
        return view('pages.categories.add-edit-category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name
        ]);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
