<?php

namespace App\Http\Controllers;

use App\Services\ProductsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    protected $rules = [];
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
            $response = DB::transaction(function () use ($validated) {
                return $this->productsService->createProduct($validated);
            });

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
    public function update(Request $request, string $id)
    {
        try {
            $validated = $this->validated();
            $response = DB::transaction(function () use ($validated) {
                return $this->productsService->updateProduct($id, $validated);
            });

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

            return $this->setResponse($response);

        } catch (\Exception $e) {
            return $this->setError($e, $e->getCode());
        }
    }
}
