<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ProductCollection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $imageName = time() . '.' . $request->image->extension();  
        $request->image->move(public_path('images'), $imageName);

        $product = Product::create([
            'name'  => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $request->imageName,
        ]);

        return response()->json([
            'message' => 'Added new product',
            'data'    => $product
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductCollection($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return new ProductCollection($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $product->update([
            'name'  => $request->name,
            'price' => $request->price,
            'stock' => $request->stock ?? $product->stock,
            'image' => $request->imageName ?? $product->image,
        ]);
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
