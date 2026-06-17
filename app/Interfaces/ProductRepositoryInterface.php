<?php

namespace App\Interfaces;

interface ProductRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByCode($code);
    public function updateStock($id, $quantity);
}