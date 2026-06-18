<?php

namespace App\Http\Controllers;

use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesHistoryController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    public function index(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $search = $request->get('search');

        $filters = [];
        if (!empty($fromDate)) {
            $filters['from_date'] = $fromDate;
        }
        if (!empty($toDate)) {
            $filters['to_date'] = $toDate;
        }
        
        // If search is provided, check if it fits invoice format or filter otherwise
        if (!empty($search)) {
            $filters['invoice_number'] = $search;
        }

        // Sellers only see their own history
        if (Auth::user()->isSeller()) {
            $filters['seller_id'] = Auth::id();
        }

        $sales = $this->saleService->getFilteredSales($filters, 15);

        return view('pos.sale_history', compact('sales', 'fromDate', 'toDate', 'search'));
    }

    public function show($id)
    {
        $sale = $this->saleService->getSaleById((int)$id);

        if (!$sale) {
            return response()->json(['message' => 'Sale not found.'], 404);
        }

        // Security check: seller can only see their own receipts
        if (Auth::user()->isSeller() && $sale->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'invoice_number' => $sale->invoice_number,
            'sale_date' => $sale->sale_date->format('d-M-Y h:i A'),
            'cashier' => $sale->user->name ?? 'N/A',
            'payment_method' => $sale->payment_method,
            'total_amount' => (float)$sale->total_amount,
            'payment_amount' => (float)$sale->payment_amount,
            'change_amount' => (float)$sale->change_amount,
            'discount' => 0.0, // We can compute it if needed, or if stored
            'items' => $sale->saleDetails->map(fn ($detail) => [
                'name' => $detail->product->product_name ?? 'Product Deleted',
                'quantity' => $detail->quantity,
                'price' => (float)$detail->price,
                'subtotal' => (float)$detail->subtotal,
            ]),
        ]);
    }
}
