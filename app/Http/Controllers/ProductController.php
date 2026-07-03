<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    ) {}

    public function index()
    {
        $products = $this->productService->getPaginatedProducts(PHP_INT_MAX);
        $lowStockProducts = $this->productService->getLowStockProducts();

        return view('pos.product_management', compact('products', 'lowStockProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|unique:products,product_code',
            'product_name' => 'required|string|max:255|unique:products,product_name',
            'category' => 'nullable|string|max:100',
            'home_cost' => 'required|numeric|min:0',
            'shop_cost' => 'required|numeric|min:0',
            'home_price' => 'required|numeric|min:0',
            'shop_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $this->productService->createProduct([
                'product_code' => $validated['product_code'],
                'product_name' => $validated['product_name'],
                'category' => $validated['category'],
                'home_cost' => $validated['home_cost'],
                'shop_cost' => $validated['shop_cost'],
                'home_price' => $validated['home_price'],
                'shop_price' => $validated['shop_price'],
                'stock' => $validated['stock'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
        } catch (InvalidArgumentException $e) {
            return redirect()->route('products.index')->with('error', $e->getMessage());
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_code' => 'required|string|unique:products,product_code,' . $id,
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $id,
            'category' => 'nullable|string|max:100',
            'home_cost' => 'required|numeric|min:0',
            'shop_cost' => 'required|numeric|min:0',
            'home_price' => 'required|numeric|min:0',
            'shop_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $this->productService->updateProduct($id, [
                'product_code' => $validated['product_code'],
                'product_name' => $validated['product_name'],
                'category' => $validated['category'],
                'home_cost' => $validated['home_cost'],
                'shop_cost' => $validated['shop_cost'],
                'home_price' => $validated['home_price'],
                'shop_price' => $validated['shop_price'],
                'stock' => $validated['stock'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true,
            ]);
        } catch (InvalidArgumentException $e) {
            return redirect()->route('products.index')->with('error', $e->getMessage());
        }

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $this->productService->deleteProduct($id);

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
