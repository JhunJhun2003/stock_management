<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Interfaces\SaleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

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
}
