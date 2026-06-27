<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\SaleService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
        protected ProductService $productService,
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'sales');
        $sellerId = $request->get('seller_id', '');
        $invoiceNumber = $request->get('invoice_number', '');

        $filters = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'seller_id' => $sellerId,
            'invoice_number' => $invoiceNumber
        ];

        // Eager-loaded sales (load all matching records for report view)
        $sales = $this->saleService->getFilteredSales($filters, PHP_INT_MAX);

        // Calculate statistics via SaleService
        $summary = $this->saleService->getSalesSummary($filters);
        $totalSales = $summary['total_sales'];
        $totalOrders = $summary['total_orders'];
        $totalItemsSold = $summary['total_items_sold'];
        $totalCost = $summary['total_cost'];
        $netProfit = $summary['net_profit'];
        $averageSale = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Get all sellers for the dropdown filter
        $sellers = $this->userService->getAllUsers()->filter(fn($u) => !$u->isAdmin())->values();

        // Get additional data based on report type
        $reportData = [];
        $reportTitle = 'အရောင်းမှတ်တမ်းအသေးစိတ်';
        
        if ($reportType === 'products') {
            $reportTitle = 'Product Report - Best Selling Products';
            $reportData = $this->saleService->getBestSellingProducts(10);
        } elseif ($reportType === 'users') {
            $reportTitle = 'User Report - Staff Performance';
            $reportData = $this->saleService->getStaffPerformance($fromDate, $toDate, 10);
        }

        return view('pos.report', compact(
            'sales',
            'totalSales',
            'totalOrders',
            'totalItemsSold',
            'totalCost',
            'netProfit',
            'averageSale',
            'fromDate',
            'toDate',
            'reportType',
            'reportData',
            'reportTitle',
            'sellers',
            'sellerId',
            'invoiceNumber'
        ));
    }

    public function export(Request $request)
    {
        $fromDate = $request->get('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->get('to_date', now()->format('Y-m-d'));
        $reportType = $request->get('report_type', 'sales');
        $sellerId = $request->get('seller_id', '');
        $invoiceNumber = $request->get('invoice_number', '');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $reportType . '_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($reportType, $fromDate, $toDate, $sellerId, $invoiceNumber) {
            $handle = fopen('php://output', 'w');
            
            if ($reportType === 'sales') {
                // Sales Report CSV with Sales, Cost, and Profit
                fputcsv($handle, [
                    'Invoice Number', 'Date', 'Customer Name', 'User', 'Subtotal', 'Discount', 'Sales Amount', 'Cost Amount', 'Net Profit', 'Payment Method', 'Items Count'
                ]);

                $filters = [
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                    'seller_id' => $sellerId,
                    'invoice_number' => $invoiceNumber
                ];

                // Get all matching sales
                $sales = $this->saleService->getFilteredSales($filters, 10000); // large perPage to get all records

                foreach ($sales as $sale) {
                    $saleCost = 0;
                    foreach ($sale->saleDetails as $detail) {
                        $saleCost += $detail->quantity * ($detail->product->cost ?? 0);
                    }
                    $saleProfit = $sale->total_amount - $saleCost;

                    fputcsv($handle, [
                        $sale->invoice_number,
                        $sale->sale_date->format('Y-m-d H:i'),
                        $sale->customer_name ?? 'Walk-in',
                        $sale->user->name ?? 'N/A',
                        $sale->total_amount + $sale->discount,
                        $sale->discount,
                        $sale->total_amount,
                        $saleCost,
                        $saleProfit,
                        $sale->payment_method ?? 'cash',
                        $sale->saleDetails->sum('quantity') ?? 0
                    ]);
                }
            } elseif ($reportType === 'products') {
                // Product Report CSV
                fputcsv($handle, ['Product Name', 'Total Sold', 'Total Revenue', 'Average Price']);

                $products = $this->saleService->getBestSellingProducts(100);

                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->product_name,
                        $product->total_sold ?? 0,
                        $product->total_revenue ?? 0,
                        $product->price
                    ]);
                }
            } elseif ($reportType === 'users') {
                // User Report CSV
                fputcsv($handle, ['Staff Name', 'Total Sales', 'Total Revenue', 'Avg. Sale']);

                $users = $this->saleService->getStaffPerformance($fromDate, $toDate, 100);

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