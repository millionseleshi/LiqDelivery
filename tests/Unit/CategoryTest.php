<?php

namespace Tests\Unit;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;;

class CategoryTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    //Category has many products
    public function testCategoryCreate()
    {
        Category::create([
            'category_name'=>$this->faker->name
        ]);
        $this->assertCount(1,Category::all());
    }
}
