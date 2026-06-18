<?php

namespace App\Interfaces;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

interface SaleRepositoryInterface
{
    public function create(array $data): Sale;

    public function createDetail(array $data): void;

    public function getRecentByUser(int $userId, int $limit = 10): Collection;
}
