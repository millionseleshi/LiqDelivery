<?php

namespace Tests\Feature;

use App\Category;
use App\Product;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;


class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;


    /**
     * A basic feature test example.
     *
     * @return void
     * @throws Exception
     */
    public function testCreateProduct()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/products', $this->getData(), ['Accept' => 'application/json']);
        $product = Product::all();
        $this->assertCount(1, $product);
        $response->assertStatus(201);
        $this->assertJson($product);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getData(): array
    {
        $category = factory(Category::class)->create();
        return [
            'product_name' => $this->faker->name(),
            'product_description' => $this->faker->paragraph(),
            'product_image' => UploadedFile::fake()->image('tests/stubs/images.jpg', 256, 197),
            'price_per_unit' => random_int(1, 1000),
            'category_id' => $category->id
        ];
    }

    public function testProductNameIsRequired()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['product_name' => '']));
        $response->assertSessionHasErrors(['product_name']);
    }

    public function testPricePerUnitIsRequired()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['price_per_unit' => '']));
        $response->assertSessionHasErrors(['price_per_unit']);
    }

    public function testProductNameUniqueness()
    {
        $response_one = $this->post('/api/products', array_merge($this->getData(), ['product_name' => 'car']));
        $response_two = $this->post('/api/products', array_merge($this->getData(), ['product_name' => 'car']));
        $response_two->assertSessionHasErrors(['product_name']);
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
        $response->assertSessionHasErrors(['category_id']);
    }

    public function testCategoryIdExistsInCategoriesTable()
    {
        $response = $this->post('/api/products', array_merge($this->getData(), ['category_id' => $this->faker->randomDigit]));
        $response->assertSessionHasErrors(['category_id']);
    }

    public function testUpdateProduct()
    {

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
        $response->assertStatus(404);
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
