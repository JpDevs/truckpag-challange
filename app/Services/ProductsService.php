<?php

namespace App\Services;

use App\Models\Products;
use App\Repositories\ProductsRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ProductsService
{
    protected $productsRepository;
    protected $model;

    public function __construct(ProductsRepository $productsRepository, Products $model)
    {
        $this->productsRepository = $productsRepository;
        $this->model = $model;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllProducts(): LengthAwarePaginator
    {
        $perPage = request()->query('perPage') ?? 10;
        return $this->model::isActive()->simplePaginate($perPage);
    }

    public function getProduct($code)
    {
        return $this->model::isActive()->where('code', $code)->firstOrFail();
    }

    public function createProduct(array $data, bool $import = false, bool $many = false)
    {
        $callBack = function () use ($data, $import, $many) {
            if ($import) {
                $data = $this->mountData($data, $many);
                $output = $this->model::insert($data);
                if ($output) {
                    return $data;
                }
            }

            $data['code'] = random_int(1000, 9999);
            $data['created_t'] = now()->toDateTimeString();
            $data['created_t'] = strtotime($data['created_t']);
            $data['last_modified_t'] = now()->toDateTimeString();
            $data['last_modified_t'] = strtotime($data['last_modified_t']);

            return $this->model::create($data);
        };

        return DB::transaction($callBack);

    }

    public function updateProduct(int $code, array $data, $import = false)
    {
        $callBack = function () use ($data, $import, $code) {
            if ($import) {
                $data = $this->mountData($data);
            }
            return $this->model::where('code', $code)->firstOrFail()->update($data);
        };

        $response = DB::transaction($callBack);
        if ($response) {
            return $data;
        }
    }


    public function deleteProduct(int $code)
    {
        return DB::transaction(function () use ($code) {
            return $this->model::where('code', $code)->firstOrFail()->delete();
        });
    }

    public function mountData(array $data, $many = false)
    {
        if ($many) {
            $data = $data[0];
        }
        foreach ($data as $row) {
            $output[] = [
                'code' => sanitizeInt($row['code'] ?? random_int(1000, 9999)),
                'status' => $row['status'] ?? 'draft',
                'imported_t' => now(),
                'url' => $row['url'] ?: null,
                'creator' => $row['creator'] ?: null,
                'created_t' => $row['created_t'] ?: null,
                'last_modified_t' => $row['last_modified_t'] ?: null,
                'product_name' => $row['product_name'] ?: null,
                'quantity' => $row['quantity'] ?? 0,
                'brands' => $row['brands'] ?: null,
                'categories' => $row['categories'] ?: null,
                'labels' => $row['labels'] ?: null,
                'cities' => $row['cities'] ?: null,
                'purchase_places' => $row['purchase_places'] ?: null,
                'stores' => $row['stores'] ?: null,
                'ingredients_text' => $row['ingredients_text'] ?: null,
                'traces' => $row['traces'] ?: null,
                'serving_size' => $row['serving_size'] ?? null ,
                'serving_quantity' => $row['serving_quantity'] ?: null,
                'nutriscore_score' => $row['nutriscore_score'] ?: null,
                'nutriscore_grade' => $row['nutriscore_grade'] ?: null,
                'main_category' => $row['main_category'] ?: null,
                'image_url' => $row['image_url'] ?: null,
            ];
        }
        return $output;
    }
}
