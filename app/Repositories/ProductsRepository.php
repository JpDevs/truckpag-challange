<?php

namespace App\Repositories;

use App\Models\Products;
use Illuminate\Pagination\Paginator;

class ProductsRepository
{
    protected $model;

    public function __construct(Products $model)
    {
        $this->model = $model;
    }

    public function find($code)
    {
        return $this->model::where('code', $code)->firstOrFail();
    }

    public function getAll(): Paginator
    {
        $perPage = request()->query('perPage') ?? 10;
        return $this->model::isActive()->simplePaginate($perPage);
    }
}
