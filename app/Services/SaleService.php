<?php

namespace App\Services;

use App\Models\Sale;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\SaleRepositoryInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SaleService
{
    public function __construct(
        protected SaleRepositoryInterface $saleRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function processCheckout(int $userId, array $items, string $paymentMethod, float $paymentAmount, float $discount = 0, ?string $customerName = null, ?string $userRole = null): Sale
    {
        if (empty($items)) {
            throw new InvalidArgumentException('Cart is empty.');
        }

        return DB::transaction(function () use ($userId, $items, $paymentMethod, $paymentAmount, $discount, $customerName, $userRole) {
            $totalAmount = 0;
            $lineItems = [];

            foreach ($items as $item) {
                $product = $this->productRepository->getById($item['product_id']);

                if (!$product || !$product->is_active) {
                    throw new InvalidArgumentException('Product not found or inactive.');
                }

                $quantity = (int) $item['quantity'];

                if ($quantity < 1) {
                    throw new InvalidArgumentException('Invalid quantity.');
                }

                if (!$product->hasStock($quantity)) {
                    throw new InvalidArgumentException("Insufficient stock for {$product->product_name}. Available: {$product->stock}");
                }

                $price = $product->getPriceForRole($userRole);
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;

                $lineItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            $totalAmount -= $discount;

            if ($totalAmount < 0) {
                $totalAmount = 0;
            }

            if ($paymentMethod === 'cash' && $paymentAmount < $totalAmount) {
                throw new InvalidArgumentException('Payment amount is less than total.');
            }

            $changeAmount = $paymentMethod === 'cash'
                ? max(0, $paymentAmount - $totalAmount)
                : 0;

            $sale = $this->saleRepository->create([
                'user_id' => $userId,
                'customer_name' => $customerName,
                'invoice_number' => Sale::generateInvoiceNumber(),
                'total_amount' => $totalAmount,
                'discount' => $discount,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'sale_date' => now(),
            ]);

            foreach ($lineItems as $line) {
                $this->saleRepository->createDetail([
                    'sale_id' => $sale->id,
                    'product_id' => $line['product']->id,
                    'quantity' => $line['quantity'],
                    'price' => $line['price'],
                    'subtotal' => $line['subtotal'],
                ]);

                $line['product']->decreaseStock($line['quantity']);
            }

            return $sale->load('saleDetails.product');
        });
    }

    public function getSaleById(int $id): ?Sale
    {
        return $this->saleRepository->getById($id);
    }

    public function getRecentSalesByUser(int $userId, int $limit = 10): Collection
    {
        return $this->saleRepository->getRecentByUser($userId, $limit);
    }

    public function getFilteredSales(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->saleRepository->getFilteredSales($filters, $perPage);
    }

    public function getSalesSummary(array $filters): array
    {
        return $this->saleRepository->getSalesSummary($filters);
    }

    public function getBestSellingProducts(int $limit = 10): Collection
    {
        return $this->saleRepository->getBestSellingProducts($limit);
    }

    public function getStaffPerformance(string $fromDate, string $toDate, int $limit = 10): Collection
    {
        return $this->saleRepository->getStaffPerformance($fromDate, $toDate, $limit);
    }

    public function getSalesChartData(string $period, ?int $userId = null): array
    {
        return $this->saleRepository->getSalesChartData($period, $userId);
    }
}
