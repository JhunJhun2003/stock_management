<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'sales');
        $sellerId = $request->get('seller_id', ''); // Get seller filter

        // Base query for sales
        $salesQuery = Sale::with(['user', 'saleDetails'])
            ->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);

        // Apply seller filter if selected
        if (!empty($sellerId)) {
            $salesQuery->where('user_id', $sellerId);
        }

        // Get sales data
        $sales = $salesQuery->orderBy('sale_date', 'desc')->paginate(15);

        // Calculate statistics
        $totalSales = $salesQuery->sum('total_amount') ?? 0;
        $totalOrders = $salesQuery->count() ?? 0;
        $totalItemsSold = SaleDetail::whereIn('sale_id', $salesQuery->pluck('id'))
            ->sum('quantity') ?? 0;
        $averageSale = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Get all sellers for the dropdown filter
        // $sellers = User::where('role', 'seller')
        //     ->orWhere('role', 'staff')
        //     ->orWhere('is_admin', false)
        //     ->orderBy('name')
        //     ->get();
        $sellers = User::where('is_admin', false)->orderBy('name')->get();

        // Get additional data based on report type
        $reportData = [];
        $reportTitle = 'Sales Report Details';
        
        if ($reportType === 'products') {
            $reportTitle = 'Product Report - Best Selling Products';
            // $reportData = Product::withCount('saleDetails as total_sold')
            //     ->withSum('saleDetails as total_revenue', 'subtotal')
            //     ->orderBy('total_sold', 'desc')
            //     ->limit(10)
            //     ->get();
            $reportData = Product::withCount('saleDetails as total_sold')
                ->withSum('saleDetails as total_revenue', 'subtotal')
                ->orderBy('total_sold', 'desc')
                ->limit(10)
                ->get();
        } elseif ($reportType === 'users') {
            $reportTitle = 'User Report - Staff Performance';
            $reportData = User::withCount('sales as total_sales')
                ->withSum('sales as total_revenue', 'total_amount')
                ->whereHas('sales', function($query) use ($fromDate, $toDate) {
                    $query->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
                })
                ->orderBy('total_revenue', 'desc')
                ->limit(10)
                ->get();
        }

        return view('pos.report', compact(
            'sales',
            'totalSales',
            'totalOrders',
            'totalItemsSold',
            'averageSale',
            'fromDate',
            'toDate',
            'reportType',
            'reportData',
            'reportTitle',
            'sellers',
            'sellerId'
        ));
    }

    public function export(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'sales');
        $sellerId = $request->get('seller_id', '');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $reportType . '_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($reportType, $fromDate, $toDate, $sellerId) {
            $handle = fopen('php://output', 'w');
            
            if ($reportType === 'sales') {
                // Sales Report CSV
                fputcsv($handle, [
                    'Invoice Number', 'Date', 'User', 'Total Amount', 'Payment Method', 'Items Count'
                ]);

                $salesQuery = Sale::with(['user', 'saleDetails'])
                    ->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
                
                // Apply seller filter if selected
                if (!empty($sellerId)) {
                    $salesQuery->where('user_id', $sellerId);
                }

                $sales = $salesQuery->orderBy('sale_date', 'desc')->get();

                foreach ($sales as $sale) {
                    fputcsv($handle, [
                        $sale->invoice_number,
                        $sale->sale_date->format('Y-m-d H:i'),
                        $sale->user->name ?? 'N/A',
                        $sale->total_amount,
                        $sale->payment_method ?? 'cash',
                        $sale->saleDetails->sum('quantity') ?? 0
                    ]);
                }
            } elseif ($reportType === 'products') {
                // Product Report CSV
                fputcsv($handle, ['Product Name', 'Total Sold', 'Total Revenue', 'Average Price']);

                $products = Product::withCount('saleDetails as total_sold')
                    ->withSum('saleDetails as total_revenue', 'subtotal')
                    ->orderBy('total_sold', 'desc')
                    ->limit(10)
                    ->get();

                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->name,
                        $product->total_sold ?? 0,
                        $product->total_revenue ?? 0,
                        $product->price
                    ]);
                }
            } elseif ($reportType === 'users') {
                // User Report CSV
                fputcsv($handle, ['Staff Name', 'Total Sales', 'Total Revenue', 'Avg. Sale']);

                $usersQuery = User::withCount('sales as total_sales')
                    ->withSum('sales as total_revenue', 'total_amount')
                    ->whereHas('sales', function($query) use ($fromDate, $toDate) {
                        $query->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
                    });

                // Apply seller filter if selected
                if (!empty($sellerId)) {
                    $usersQuery->where('id', $sellerId);
                }

                $users = $usersQuery->orderBy('total_revenue', 'desc')
                    ->limit(10)
                    ->get();

                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->name,
                        $user->total_sales ?? 0,
                        $user->total_revenue ?? 0,
                        $user->total_sales > 0 ? round($user->total_revenue / $user->total_sales) : 0
                    ]);
                }
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}