<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductsTest extends TestCase
{
    /**
     * @return array
     * @throws \Random\RandomException
     * @test
     * @author João Pedro B. Santos
     */
    public function product_creation(): array
    {
        $response = $this->json('POST', '/api/products', [
            'code' => $data['code'] = random_int(1000, 9999),
            'status' => 'draft',

        ]);

        $response->assertStatus(201);

        return ['data' => $response->content()];
    }

    /**
     * @return array
     * @author João Pedro B. Santos
     * @test
     */
    public function display_all_products(): array
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        return ['data' => $response->content()];
    }

    /**
     * @param array $params
     * @return array
     * @author João Pedro B. Santos
     * @test
     */
    public function display_unit_product(array $params = []): array
    {
        if(empty($params)) {
            $params = $this->setParams();
        }

        $response = $this->get("/api/products/{$params['code']}");
        $response->assertStatus(200);

        return ['data' => $response->content()];
    }


    /**
     * @param array $params
     * @return array
     * @test
     */
    public function product_update(array $params = []): array
    {
        if(empty($params)) {
            $params = $this->setParams();
        }

        $response = $this->json('PUT', "/api/products/{$params['code']}", [
            'status' => 'trash'
        ]);

        $response->assertStatus(200);

        return ['data' => $response->content()];
    }

    /**
     * @param array $params
     * @return void
     * @author João Pedro B. Santos
     * @test
     */
    public function product_destroy(array $params = [])
    {
        if(empty($params)) {
            $params = $this->setParams();
        }

        $response = $this->delete("/api/products/{$params['code']}");

        $response->assertStatus(204);
    }

    /**
     * @return array
     * @author João Pedro B. Santos
     * @throws \Random\RandomException
     */
    protected function setParams(): array
    {
        $params = $this->product_creation()['data'];
        return json_decode($params, true);
    }
}
