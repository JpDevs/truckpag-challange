<?php

namespace App\Http\Controllers;

use App\Services\ProductsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    protected $rules = [
        'file' => ['file', 'mimetypes:application/json'],
        'code' => ['integer'],
        'status' => ['string', 'in:draft,trash,published'],
        'url' => ['string'],
        'creator' => ['string'],
        'product_name' => ['string'],
        'quantity' => ['string'],
        'brands' => ['string'],
        'categories' => ['string'],
        'labels' => ['string'],
        'cities' => ['string'],
        'purchase_places' => ['string'],
        'stores' => ['string'],
        'ingredients_text' => ['string'],
        'traces' => ['string'],
        'serving_size' => ['string'],
        'serving_quantity' => ['numeric'],
        'nutriscore_score' => ['integer'],
        'nutriscore_grade' => ['string', 'max:1'],
        'main_category' => ['string'],
        'image_url' => ['string'],
    ];
    protected $productsService;

    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $response = $this->paginate($this->productsService->getAllProducts());

            return $this->setResponse($response);

        } catch (\Exception $e) {
            return $this->setError($e, $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validated();
            if (isset($validated['file'])) {
                $file = file_get_contents($validated['file']);
                $validated = json_decode($file, true);
            }
            $response = $this->productsService->createProduct($validated, isset($file));
            return $this->setResponse($response, 201);

        } catch (\Exception $e) {
            return $this->setError($e, $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $response = $this->productsService->getProduct($id);

            return $this->setResponse($response);

        } catch (\Exception $e) {
            return $this->setError($e, $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $validated = $this->validated();
            if (isset($validated['file'])) {
                $file = file_get_contents($validated['file']);
                $validated = json_decode($file, true);
            }
            $response = $this->productsService->updateProduct($id, $validated, isset($file));
            
            return $this->setResponse($response);

        } catch (\Exception $e) {
            return $this->setError($e, $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $response = $this->productsService->deleteProduct($id);

            return $this->setResponse($response, 204);

        } catch (\Exception $e) {
            return $this->setError($e, $e->getCode());
        }
    }
}
