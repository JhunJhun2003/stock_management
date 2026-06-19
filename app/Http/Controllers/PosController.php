<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

class PosController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected SaleService $saleService
    ) {}

    public function index()
    {
        $products = $this->productService->getActiveProductsForPos();
        $categories = $this->productService->getCategories();

        return view('pos.pos', compact('products', 'categories'));
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,kpay,card',
            'payment_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
        ]);

        try {
            $sale = $this->saleService->processCheckout(
                Auth::id(),
                $validated['items'],
                $validated['payment_method'],
                (float) $validated['payment_amount'],
                (float) ($validated['discount'] ?? 0),
                $validated['customer_name'] ?? null
            );
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Sale completed successfully.',
            'sale' => [
                'invoice_number' => $sale->invoice_number,
                'total_amount' => $sale->total_amount,
                'payment_amount' => $sale->payment_amount,
                'change_amount' => $sale->change_amount,
                'payment_method' => $sale->payment_method,
                'customer_name' => $sale->customer_name,
                'discount' => (float)$sale->discount,
                'sale_date' => $sale->sale_date->format('d-M-Y h:i A'),
                'cashier' => Auth::user()->name,
                'items' => $sale->saleDetails->map(fn ($detail) => [
                    'name' => $detail->product->product_name,
                    'quantity' => $detail->quantity,
                    'subtotal' => $detail->subtotal,
                ]),
            ],
        ]);
    }
}
