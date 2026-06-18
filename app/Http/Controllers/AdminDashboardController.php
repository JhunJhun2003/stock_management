<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\SaleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
        protected ProductService $productService,
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        if (Auth::user()->isSeller()) {
            return redirect()->route('seller.dashboard');
        }

        $today = now()->format('Y-m-d');
        $filtersToday = [
            'from_date' => $today,
            'to_date' => $today
        ];

        // Today's summary
        $summary = $this->saleService->getSalesSummary($filtersToday);

        // General counts
        $totalProducts = $this->productService->getAllProducts()->count();

        // Recent sales
        $recentSales = $this->saleService->getFilteredSales([], 5);

        // Chart filter (default is 'week')
        $chartPeriod = $request->get('chart_period', 'week');
        $chartData = $this->saleService->getSalesChartData($chartPeriod);

        if ($request->ajax()) {
            return response()->json($chartData);
        }

        return view('pos.dashboard', [
            'todaySales' => $summary['total_sales'],
            'todayCosts' => $summary['total_cost'],
            'todayProfit' => $summary['net_profit'],
            'todayOrders' => $summary['total_orders'],
            'totalProducts' => $totalProducts,
            'recentSales' => $recentSales,
            'chartData' => $chartData,
        ]);
    }
}
