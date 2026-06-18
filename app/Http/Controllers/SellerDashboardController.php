<?php

namespace App\Http\Controllers;

use App\Services\SaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $today = now()->format('Y-m-d');
        
        $filtersToday = [
            'from_date' => $today,
            'to_date' => $today,
            'seller_id' => $sellerId
        ];

        // Today's summary for seller
        $summary = $this->saleService->getSalesSummary($filtersToday);

        // Recent sales for seller
        $recentSales = $this->saleService->getFilteredSales(['seller_id' => $sellerId], 5);

        // Chart filter (default is 'today' or 'week' for seller)
        $chartPeriod = $request->get('chart_period', 'today');
        $chartData = $this->saleService->getSalesChartData($chartPeriod, $sellerId);

        if ($request->ajax()) {
            return response()->json($chartData);
        }

        return view('pos.sellerdashboard', [
            'todaySales' => $summary['total_sales'],
            'todayOrders' => $summary['total_orders'],
            'recentSales' => $recentSales,
            'chartData' => $chartData
        ]);
    }
}
