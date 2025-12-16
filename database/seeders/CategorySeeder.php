<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Home & Kitchen',
                'slug' => 'home-kitchen',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Sports & Outdoors',
                'slug' => 'sports-outdoors',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Beauty & Personal Care',
                'slug' => 'beauty-personal-care',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Books & Media',
                'slug' => 'books-media',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Toys & Games',
                'slug' => 'toys-games',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Automotive',
                'slug' => 'automotive',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'image' => null,
                'status' => Status::Active->value,
            ],
            [
                'name' => 'Grocery & Gourmet',
                'slug' => 'grocery-gourmet',
                'image' => null,
                'status' => Status::Active->value,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
