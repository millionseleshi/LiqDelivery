<?php

namespace Tests\Feature;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateCategory()
    {
        $response = $this->post('/api/categories', $this->getCategory(), ['accept' => 'application/json']);
        $this->assertCount(1, Category::all());
        $response->assertStatus(201);
    }

    /**
     * @return array
     */
    public function getCategory()
    {
        return [
            'category_name' => $this->faker->name(),
            'category_description' => $this->faker->paragraph()
        ];

    }

    public function testCategoryNameIsRequired()
    {
        $response = $this->post('/api/categories', array_merge($this->getCategory(), ['category_name' => '']));
        $response->assertSessionHasErrors(['category_name']);
    }

    public function testCategoryNameIsUnique()
    {
        $this->post('/api/categories', array_merge($this->getCategory(), ['category_name' => 'tech']));
        $response = $this->post('/api/categories', array_merge($this->getCategory(), ['category_name' => 'tech']));
        $response->assertSessionHasErrors(['category_name']);
    }

    public function testUpdateCategory()
    {
        $this->post('/api/categories', $this->getCategory());
        $category = Category::first();
        $response = $this->put('/api/categories/' . $category->id, ['category_name' => 'home appliance']);
        $response->assertStatus(200);
        $this->assertEquals('home appliance', Category::first()->category_name);
    }

    public function testShowCategory()
    {
        $this->post('/api/categories', $this->getCategory());
        $category = Category::first();
        $response = $this->get('/api/categories/' . $category->id);
        $response->assertStatus(302);
        $response->assertExactJson($category->toArray());
    }

    public function testCategoryNotFound()
    {
        $response = $this->get('/api/categories/' . $this->faker->randomDigit);
        $response->assertStatus(404);
        $response->assertExactJson(['category not found']);
    }

    public function testDeleteCategory()
    {

        $this->post('/api/categories', $this->getCategory());
        $category = Category::first();
        $response = $this->delete('/api/categories/' . $category->id);
        $this->assertCount(0, Category::all());
        $response->assertStatus(200);
        $response->assertExactJson(['category deleted']);
    }

    public function testGetAllCategory()
    {
        $this->post('/api/categories', $this->getCategory());
        $this->post('/api/categories', $this->getCategory());
        $response = $this->get('/api/categories');
        $this->assertCount(2, Category::all());
        $response->assertJson(Category::all()->toArray());
    }
}
