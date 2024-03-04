<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->when(request()->q, function ($products) {
            $products = $products->where('name','like', '%'. request()->q . '%');
        })->paginate(10);

        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::latest()->get();
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'title' => 'required|unique:products',
            'category_id' => 'required',
            'content' => 'required',
            'weight' => 'required',
            'price' => 'required',
            'discount' => 'required',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //save ke DB
        $product = Product::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'slug' => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'content' => $request->content,
            'weight' => $request->weight,
            'price' => $request->price,
            'discount' => $request->discount,
            'keyword' => $request->keyword,
            'description' => $request->description
        ]);

        if ($product) {
            //redirect dengan pesan success
            return redirect()->route('admin.product.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.product.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::latest()->get();
        return view('admin.product.edit', compact('categories','product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'title' => 'required|unique:products,title,'.$product->id,
            'category_id' => 'required',
            'content' => 'required',
            'weight' => 'required',
            'price' => 'required',
            'discount' => 'required',
        ]);

        //check jika image kosong
        if ($request->file('image') == '') {
            //update data tanpa image
            $product = Product::findOrFail($product->id);
            $product->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'content' => $request->content,
                'weight' => $request->weight,
                'price' => $request->price,
                'discount' => $request->discount,
                'keyword' => $request->keyword,
                'description' => $request->description
            ]);
        }else{
            //hapus image lama
            Storage::disk('local')->delete('public/products/'.basename($product->image));

            //upload image baru
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            $product = Product::findOrFail($product->id);
            $product->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'content' => $request->content,
                'weight' => $request->weight,
                'price' => $request->price,
                'discount' => $request->discount,
                'keyword' => $request->keyword,
                'description' => $request->description
            ]);
        }

        if ($product) {
            //redirect dengan pesan success
            return redirect()->route('admin.product.index')->with(['success' => 'Data Berhasil Diupdate!']);
        }else{
            //redirect dengan pesan error
            return redirect()->route('admin.product.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $image = Storage::disk('local')->delete('public/products/'.basename($product->image));
        $product->delete();

        if ($product){
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