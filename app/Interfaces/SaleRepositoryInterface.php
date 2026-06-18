<?php

namespace App\Interfaces;

use App\Models\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SaleRepositoryInterface
{
    public function create(array $data): Sale;

    public function createDetail(array $data): void;

    public function getRecentByUser(int $userId, int $limit = 10): Collection;

    public function getById(int $id): ?Sale;

    public function getFilteredSales(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function getSalesSummary(array $filters): array;

    public function getBestSellingProducts(int $limit = 10): Collection;

    public function getStaffPerformance(string $fromDate, string $toDate, int $limit = 10): Collection;

    public function getSalesChartData(string $period, ?int $userId = null): array;
}
