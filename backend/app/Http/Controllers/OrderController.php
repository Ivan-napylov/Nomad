<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendOrderNotificationJob;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $orders = Order::with('products')->get();
        } else {
            $orders = $user->orders()->with('products')->get();
        }

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        $productsData = $request->input('products');

        // Проверяем наличие товара на складе
        $productIds = collect($productsData)->pluck('id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($productsData as $item) {
            $product = $products[$item['id']];
            if ($product->stock < $item['quantity']) {
                return response()->json([
                    'message' => "Недостаточно товара '{$product->title}' на складе",
                ], 422);
            }
        }

        DB::transaction(function () use ($user, $productsData, $products, &$order) {
            // Можно здесь вызвать внешний сервис (пункт 3) — пока shipping_cost = 0
            $shippingCost = 0; // потом заменим

            $order = Order::create([
                'user_id' => $user->id,
                'shipping_cost' => $shippingCost,
            ]);

            foreach ($productsData as $item) {
                $product = $products[$item['id']];
                // Уменьшаем stock
                $product->decrement('stock', $item['quantity']);

                // Связываем товар с заказом
                $order->products()->attach($product->id, ['quantity' => $item['quantity']]);
            }
        });

        SendOrderNotificationJob::dispatch($order);

        // Тут можно поставить событие для уведомлений (пункт 2)

        return response()->json($order->load('products'), 201);
    }

    public function show(Order $order, Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Доступ запрещён'], 403);
        }

        return response()->json($order->load('products'));
    }
}

