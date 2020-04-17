<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * A basic feature test example.
     *
     * @return void
     * @throws Exception
     */
    public function testCreateProduct()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/products', $this->getData());
        $this->assertCount(1, Product::all());

    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData()
    {
        factory(Category::class)->create();
        return [
            'product_name' => $this->faker->name,
            'product_description' => $this->faker->paragraph,
            'sku'=>$this->faker->word,
            'units_in_stock'=>random_int(200,20000),
            'product_image' => UploadedFile::fake()->image('tests/stubs/images.jpg', 256, 197),
            'price_per_unit' => random_int(1, 1000),
            'category_id' => '1'
        ];
    }

    public function testProductNameIsRequired()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['product_name' => '']));
        $response->assertStatus(422);
    }

    public function testPricePerUnitIsRequired()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['price_per_unit' => '']));
        $response->assertStatus(422);
    }

    public function testUnitInStockIsRequired()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['units_in_stock' => '']));
        $response->assertStatus(422);
    }
    public function testSKURequired()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['sku' => '']));
        $response->assertStatus(422);
    }

    public function testProductNameUniqueness()
    {
        $this->post('/api/products', array_merge($this->getData(), ['product_name' => 'car']));
        $response_two = $this->post('/api/products', array_merge($this->getData(), ['product_name' => 'car']));
        $response_two->assertStatus(422);
    }

    public function testProductImageUpload()
    {
        Storage::fake('public');
        $this->post('/api/products', $this->getData());
        Storage::disk('public')->assertExists('/uploads/images/', 'images.jpg');
    }

    public function testCategoryIdIsRequired()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['category_id' => '']));
        $response->assertStatus(422);
    }

    public function testCategoryIdExistsInCategoriesTable()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['category_id' => $this->faker->randomDigit]));
        $response->assertStatus(422);
    }

    public function testUpdateProduct()
    {
        $this->withoutExceptionHandling();
        $this->post('/api/products', $this->getData(), ['Accept' => 'application/json']);

        $product = Product::first();

        $response = $this->put('/api/products/' . $product->id, ['product_name' => 'dvx78']);

        $response->assertStatus(200);
        $this->assertEquals('dvx78', Product::first()->product_name);
        $this->assertJson($product);
    }

    public function testShowProduct()
    {
        $this->post('/api/products', $this->getData(), ['Accept' => 'application/json']);
        $product = Product::first();
        $response = $this->get('/api/products/' . $product->id);
        $response->assertStatus(200);
        $this->assertJson($product);
    }

    public function testProductNotFound()
    {
        $response = $this->get('/api/products/' . $this->faker->randomDigit);
        $response->assertStatus(422);
        $response->assertExactJson(['product not found']);
    }

    public function testProductDelete()
    {
        $this->post('/api/products', $this->getData(), ['Accept' => 'application/json']);
        $product = Product::first();
        $response = $this->delete('/api/products/' . $product->id);
        $this->assertCount(0, Product::all());
        $response->assertStatus(200);
        $response->assertExactJson(['product deleted']);
    }

    public function testGetAllProduct()
    {
        $this->withoutExceptionHandling();

        $this->post('/api/products', $this->getData(), ['Accept' => 'application/json']);
        $this->post('/api/products', $this->getData(), ['Accept' => 'application/json']);
        $response = $this->get('/api/products', ['Accept' => 'application/json']);
        $this->assertCount(2, Product::all());
        $response->assertStatus(200);
        $response->assertJson(Product::all()->toArray());
    }
}
