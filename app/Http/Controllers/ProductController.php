<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([ // Add validation!
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'description' => 'nullable|string',
        'category' => 'required|string',
        'imageurl' => 'nullable|string',
    ]);

    $product = Product::create($validatedData); // Use $validatedData

    return response()->json(['message' => 'Producto creado exitosamente', 'product' => $product], 201);
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $product = Product::find($id);
    
    if (!$product) {
        return response()->json(['message' => 'Producto no encontrado'], 404);
    }

    return response()->json($product);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        if ($product) {
            $product->update($request->all());
            return response()->json(['message' => 'Producto actualizado correctamente', 'product' => $product]);
        } else {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        
        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Producto eliminado correctamente']);
        } else {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }
    }   
    }

    