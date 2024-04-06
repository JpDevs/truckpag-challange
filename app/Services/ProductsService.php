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
        return $this->model::isActive()->paginate($perPage);
    }

    public function getProduct($code)
    {
        return $this->model::isActive()->where('code', $code)->firstOrFail();
    }

    public function createProduct(array $data, $import = false)
    {
        $callBack = function () use ($data, $import) {
            if ($import) {
                $data = $this->mountData($data);
                return $this->model::createMany($data);
            }
            return $this->model::create($data);
        };

        return DB::transaction($callBack);

    }

    public function updateProduct(int $id, array $data, $import = false)
    {
        $callBack = function () use ($data, $import, $id) {
            if ($import) {
                $data = $this->mountData($data);
            }
            $this->model::where('id', $id)->update($data);
        };

        return DB::transaction($callBack);
    }


    public function deleteProduct(int $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->model::where('id', $id)->firstOrFail()->delete();
        });
    }

    public function mountData(array $data)
    {
        $output = [];
        foreach ($data as $row) {
            $output[] = [
                'code' => sanitizeInt($row['code']) ?? null,
                'status' => $row['status'] ?? 'draft',
                'imported_t' => now(),
                'url' => $row['url'] ?? null,
                'creator' => $row['creator'] ?? null,
                'created_t' => $row['created_t'] ?? null,
                'last_modified_t' => $row['last_modified_t'] ?? null,
                'product_name' => $row['product_name'],
                'quantity' => $row['quantity'],
                'brands' => $row['brands'],
                'categories' => $row['categories'],
                'labels' => $row['labels'],
                'cities' => $row['cities'],
                'purchase_places' => $row['purchase_places'],
                'stores' => $row['stores'],
                'ingredients_text' => $row['ingredients_text'],
                'traces' => $row['traces'],
                'serving_size' => $row['serving_size'],
                'serving_quantity' => $row['serving_quantity'],
                'nutriscore_score' => $row['nutriscore_score'],
                'nutriscore_grade' => $row['nutriscore_grade'],
                'main_category' => $row['main_category'],
                'image_url' => $row['image_url'] ?? null,
            ];
        }
        return $output;
    }
}
