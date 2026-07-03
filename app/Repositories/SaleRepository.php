<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Interfaces\SaleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\User;

class SaleRepository implements SaleRepositoryInterface
{
    public function create(array $data): Sale
    {
        return Sale::create($data);
    }

    public function createDetail(array $data): void
    {
        SaleDetail::create($data);
    }

    public function getRecentByUser(int $userId, int $limit = 10): Collection
    {
        return Sale::with('saleDetails.product')
            ->where('user_id', $userId)
            ->latest('sale_date')
            ->limit($limit)
            ->get();
    }

    public function getById(int $id): ?Sale
    {
        return Sale::with(['user', 'saleDetails.product'])->find($id);
    }

    public function getFilteredSales(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Sale::query()->with(['user', 'saleDetails.product']);

        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            $query->whereBetween('sale_date', [$filters['from_date'] . ' 00:00:00', $filters['to_date'] . ' 23:59:59'], 'and');
        }

        if (!empty($filters['seller_id'])) {
            $query->where('user_id', $filters['seller_id']);
        }

        if (!empty($filters['customer_name'])) {
            $query->where('customer_name', 'like', '%' . $filters['customer_name'] . '%');
        }

        if (!empty($filters['invoice_number'])) {
            $query->where('invoice_number', 'like', '%' . $filters['invoice_number'] . '%');
        }

        return $query->latest('sale_date')->paginate($perPage);
    }

    public function getSalesSummary(array $filters): array
    {
        // Build base query
        $query = Sale::query();

        if (!empty($filters['from_date']) && !empty($filters['to_date'])) {
            $query->whereBetween('sale_date', [$filters['from_date'] . ' 00:00:00', $filters['to_date'] . ' 23:59:59'], 'and');
        }

        if (!empty($filters['seller_id'])) {
            $query->where('user_id', $filters['seller_id']);
        }

        if (!empty($filters['customer_name'])) {
            $query->where('customer_name', 'like', '%' . $filters['customer_name'] . '%');
        }

        if (!empty($filters['invoice_number'])) {
            $query->where('invoice_number', 'like', '%' . $filters['invoice_number'] . '%');
        }

        $saleIds = (clone $query)->pluck('id')->toArray();

        $totalSales = (clone $query)->sum('total_amount') ?? 0;
        $totalOrders = (clone $query)->count('*') ?? 0;

        $totalItemsSold = 0;
        $totalCost = 0;

        if (!empty($saleIds)) {
            $totalItemsSold = SaleDetail::query()->whereIn('sale_id', $saleIds, 'and', false)->sum('quantity') ?? 0;
            
            // Calculate total cost based on quantity sold and cost of product at that moment
            $totalCost = DB::table('sale_details')
                ->join('products', 'sale_details.product_id', '=', 'products.id')
                ->leftJoin('users', 'sale_details.sale_id', '=', 'users.id')
                ->whereIn('sale_details.sale_id', $saleIds, 'and', false)
                ->sum(DB::raw('sale_details.quantity * products.home_cost')) ?? 0;
        }

        $netProfit = $totalSales - $totalCost;

        return [
            'total_sales' => (float)$totalSales,
            'total_orders' => (int)$totalOrders,
            'total_items_sold' => (int)$totalItemsSold,
            'total_cost' => (float)$totalCost,
            'net_profit' => (float)$netProfit,
        ];
    }

    public function getBestSellingProducts(int $limit = 10): Collection
    {
        return Product::withCount(['saleDetails as total_sold' => function($query) {
            $query->select(DB::raw('sum(quantity)'));
        }])
        ->withSum('saleDetails as total_revenue', 'subtotal')
        ->orderByDesc('total_sold')
        ->limit($limit)
        ->get();
    }

    public function getStaffPerformance(string $fromDate, string $toDate, int $limit = 10): Collection
    {
        return User::withCount(['sales as total_sales' => function($query) use ($fromDate, $toDate) {
            $query->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }])
        ->withSum(['sales as total_revenue' => function($query) use ($fromDate, $toDate) {
            $query->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }], 'total_amount')
        ->whereHas('sales', function($query) use ($fromDate, $toDate) {
            $query->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        })
        ->orderByDesc('total_revenue')
        ->limit($limit)
        ->get();
    }

    public function getSalesChartData(string $period, ?int $userId = null): array
    {
        $labels = [];
        $sales = [];

        if ($period === 'today') {
            // Hourly sales for today: 9 AM to 6 PM
            for ($hour = 9; $hour <= 18; $hour++) {
                $labels[] = ($hour > 12 ? ($hour - 12) . ' PM' : ($hour === 12 ? '12 PM' : $hour . ' AM'));
                
                    $hourQuery = Sale::query()->whereRaw('DATE(sale_date) = ?', [now()->toDateString()], 'and')
                        ->whereRaw('HOUR(sale_date) = ?', [$hour], 'and');
                
                if ($userId) {
                    $hourQuery->where('user_id', $userId);
                }

                $sales[] = (float)($hourQuery->sum('total_amount') ?? 0);
            }
        } elseif ($period === 'week') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('M d');

                    $dayQuery = Sale::query()->whereRaw('DATE(sale_date) = ?', [$date->toDateString()], 'and');
                if ($userId) {
                    $dayQuery->where('user_id', $userId);
                }

                $sales[] = (float)($dayQuery->sum('total_amount') ?? 0);
            }
        } else {
            // Last 6 months
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $labels[] = $date->format('M');

                $monthQuery = Sale::query()->whereRaw('MONTH(sale_date) = ?', [$date->month], 'and')
                    ->whereRaw('YEAR(sale_date) = ?', [$date->year], 'and');
                
                if ($userId) {
                    $monthQuery->where('user_id', $userId);
                }

                $sales[] = (float)($monthQuery->sum('total_amount') ?? 0);
            }
        }

        return [
            'labels' => $labels,
            'sales' => $sales,
        ];
    }
}
