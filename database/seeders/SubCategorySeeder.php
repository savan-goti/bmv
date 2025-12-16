<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subCategories = [
            // Electronics
            'Electronics' => [
                'Mobile Phones & Accessories',
                'Computers & Laptops',
                'Cameras & Photography',
                'Audio & Headphones',
                'Television & Video',
                'Smart Home Devices',
                'Wearable Technology',
                'Gaming Consoles',
            ],
            // Fashion
            'Fashion' => [
                'Men\'s Clothing',
                'Women\'s Clothing',
                'Kids\' Clothing',
                'Footwear',
                'Bags & Luggage',
                'Watches',
                'Jewelry & Accessories',
                'Eyewear',
            ],
            // Home & Kitchen
            'Home & Kitchen' => [
                'Furniture',
                'Kitchen Appliances',
                'Home Decor',
                'Bedding & Linens',
                'Cookware & Dining',
                'Storage & Organization',
                'Lighting',
                'Garden & Outdoor',
            ],
            // Sports & Outdoors
            'Sports & Outdoors' => [
                'Fitness Equipment',
                'Outdoor Recreation',
                'Sports Apparel',
                'Cycling',
                'Camping & Hiking',
                'Water Sports',
                'Team Sports',
                'Yoga & Pilates',
            ],
            // Beauty & Personal Care
            'Beauty & Personal Care' => [
                'Skincare',
                'Makeup',
                'Hair Care',
                'Fragrances',
                'Personal Care Appliances',
                'Bath & Body',
                'Men\'s Grooming',
                'Nail Care',
            ],
            // Books & Media
            'Books & Media' => [
                'Books',
                'E-Books & Readers',
                'Music',
                'Movies & TV Shows',
                'Video Games',
                'Magazines',
                'Audiobooks',
                'Educational Media',
            ],
            // Toys & Games
            'Toys & Games' => [
                'Action Figures & Dolls',
                'Board Games & Puzzles',
                'Building Toys',
                'Educational Toys',
                'Outdoor Play',
                'Electronic Toys',
                'Arts & Crafts',
                'Baby & Toddler Toys',
            ],
            // Automotive
            'Automotive' => [
                'Car Electronics',
                'Car Accessories',
                'Motorcycle Accessories',
                'Tires & Wheels',
                'Car Care & Cleaning',
                'Tools & Equipment',
                'Replacement Parts',
                'Interior Accessories',
            ],
            // Health & Wellness
            'Health & Wellness' => [
                'Vitamins & Supplements',
                'Medical Supplies',
                'Fitness Nutrition',
                'Weight Management',
                'Herbal & Natural',
                'First Aid',
                'Sexual Wellness',
                'Health Monitors',
            ],
            // Grocery & Gourmet
            'Grocery & Gourmet' => [
                'Fresh Produce',
                'Beverages',
                'Snacks & Sweets',
                'Pantry Staples',
                'Organic & Natural',
                'International Foods',
                'Gourmet Gifts',
                'Baby Food & Formula',
            ],
        ];

        foreach ($subCategories as $categoryName => $subs) {
            $category = Category::where('name', $categoryName)->first();
            
            if ($category) {
                foreach ($subs as $subName) {
                    SubCategory::create([
                        'category_id' => $category->id,
                        'name' => $subName,
                        'slug' => Str::slug($subName),
                        'image' => null,
                        'status' => Status::Active->value,
                    ]);
                }
            }
        }
    }
}
