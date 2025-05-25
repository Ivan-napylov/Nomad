<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Список всех товаров (виден всем авторизованным)
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    // Создать новый товар (только Admin)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    // Показать товар по id (виден всем)
    public function show(Product $product)
    {
        return $product;
    }

    // Обновить товар (только Admin)
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    // Удалить товар (только Admin)
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }
}
