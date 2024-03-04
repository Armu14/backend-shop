<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->when(request()->q, function ($categories) {
            $categories = $categories->where('name','like', '%'. request()->q . '%');
        })->paginate(10);
dd($categories);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|unique:categories'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        //save ke DB
        $category = Category::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);

        if ($category) {
            //redirect dengan pesan success
            return redirect()->route('admin.category.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.category.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name,'.$category->id
        ]);

        //check jika image kosong
        if ($request->file('image') == '') {
            //update data tanpa image
            $category = Category::findOrFail($category->id);
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);
        }else{
            //hapus image lama
            Storage::disk('local')->delete('public/categories/'.basename($category->image));

            //upload image baru
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            $category = Category::findOrFail($category->id);
            $category->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
            ]);
        }

        if ($category) {
            //redirect dengan pesan success
            return redirect()->route('admin.category.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.category.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $image = Storage::disk('local')->delete('public/categories/'.basename($category->image));
        $category->delete();

        if ($category){
            return response()->json([
                'status' => 'success'
            ]);
        }else{
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}