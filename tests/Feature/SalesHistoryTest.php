<?php

use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a seller to view their own receipt when the sale owner id is a string', function () {
    $seller = User::factory()->create([
        'name' => 'Seller One',
        'email' => 'seller@example.com',
        'role' => 'shop',
    ]);

    $sale = Sale::create([
        'user_id' => (string) $seller->id,
        'customer_name' => 'Walk-in Customer',
        'invoice_number' => 'INV-TEST-001',
        'total_amount' => 1000,
        'discount' => 100,
        'payment_amount' => 1000,
        'change_amount' => 0,
        'payment_method' => 'cash',
        'sale_date' => now(),
    ]);

    $response = $this->actingAs($seller)->getJson('/sales/' . $sale->id);

    $response->assertOk()
        ->assertJsonPath('invoice_number', 'INV-TEST-001');
});
