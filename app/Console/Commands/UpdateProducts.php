<?php

namespace App\Console\Commands;

use App\Constants\ProductsConstants;
use App\Models\Products;
use App\Services\ImportService;
use App\Services\ProductsService;
use Illuminate\Console\Command;

class UpdateProducts extends Command
{
    protected $productsService;
    protected $importService;
    protected $model;

    public function __construct(ProductsService $productsService, ImportService $importService, Products $model)
    {
        parent::__construct();
        $this->productsService = $productsService;
        $this->importService = $importService;
        $this->model = $model;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update all products using open food facts api endpoints';

    /**
     * Execute the console command.
     */
    public function handle($attempts = 0)
    {
        $this->model::truncate();
        $paths = ProductsConstants::PATCHS;
        $data = [];
        if ($attempts >= 10) {
            return false;
        }
        try {
            foreach ($paths as $path) {
                $endpoint = env('API_URL') . $path;
                $data[] = $this->importService->getProducts($endpoint);
            }
            $output = $this->productsService->createProduct($data, true, true);
        } catch (\Exception $e) {
            $attempts = $attempts + 1;
            $this->handle($attempts);
        }

    }
}
