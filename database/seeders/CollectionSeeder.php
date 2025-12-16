<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use App\Enums\Status;
use Carbon\Carbon;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            [
                'name' => 'Summer Collection 2025',
                'slug' => 'summer-collection-2025',
                'description' => 'Explore our vibrant summer collection featuring lightweight fabrics, bright colors, and trendy designs perfect for the warm season.',
                'start_date' => Carbon::create(2025, 6, 1),
                'end_date' => Carbon::create(2025, 8, 31),
                'is_featured' => true,
                'status' => Status::Active,
            ],
            [
                'name' => 'Winter Essentials 2025',
                'slug' => 'winter-essentials-2025',
                'description' => 'Stay warm and stylish with our winter essentials collection. Cozy sweaters, jackets, and accessories for the cold season.',
                'start_date' => Carbon::create(2025, 12, 1),
                'end_date' => Carbon::create(2026, 2, 28),
                'is_featured' => true,
                'status' => Status::Active,
            ],
            [
                'name' => 'Spring Fashion 2025',
                'slug' => 'spring-fashion-2025',
                'description' => 'Refresh your wardrobe with our spring fashion collection. Floral patterns, pastel colors, and light layers.',
                'start_date' => Carbon::create(2025, 3, 1),
                'end_date' => Carbon::create(2025, 5, 31),
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'Autumn Trends 2025',
                'slug' => 'autumn-trends-2025',
                'description' => 'Embrace the fall season with our autumn trends collection. Earthy tones, layered looks, and comfortable fabrics.',
                'start_date' => Carbon::create(2025, 9, 1),
                'end_date' => Carbon::create(2025, 11, 30),
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'New Arrivals',
                'slug' => 'new-arrivals',
                'description' => 'Check out our latest arrivals! Fresh styles and trending pieces added weekly.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => true,
                'status' => Status::Active,
            ],
            [
                'name' => 'Best Sellers',
                'slug' => 'best-sellers',
                'description' => 'Our most popular products that customers love. Top-rated items with proven quality and style.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => true,
                'status' => Status::Active,
            ],
            [
                'name' => 'Clearance Sale',
                'slug' => 'clearance-sale',
                'description' => 'Huge discounts on selected items! Limited stock available. Shop now before they\'re gone.',
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => Carbon::now()->addDays(30),
                'is_featured' => true,
                'status' => Status::Active,
            ],
            [
                'name' => 'Sportswear Collection',
                'slug' => 'sportswear-collection',
                'description' => 'Performance meets style. Athletic wear designed for comfort and functionality.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'Casual Wear',
                'slug' => 'casual-wear',
                'description' => 'Comfortable and stylish everyday wear. Perfect for relaxed occasions and daily activities.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'Formal Collection',
                'slug' => 'formal-collection',
                'description' => 'Elegant and sophisticated pieces for professional and formal occasions.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'Kids Collection',
                'slug' => 'kids-collection',
                'description' => 'Fun, colorful, and comfortable clothing for children of all ages.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'Eco-Friendly Line',
                'slug' => 'eco-friendly-line',
                'description' => 'Sustainable fashion made from eco-friendly materials. Style with a conscience.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => true,
                'status' => Status::Active,
            ],
            [
                'name' => 'Premium Collection',
                'slug' => 'premium-collection',
                'description' => 'Luxury items crafted with the finest materials and exceptional attention to detail.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'Accessories Hub',
                'slug' => 'accessories-hub',
                'description' => 'Complete your look with our range of accessories. Bags, belts, hats, and more.',
                'start_date' => null,
                'end_date' => null,
                'is_featured' => false,
                'status' => Status::Active,
            ],
            [
                'name' => 'Limited Edition',
                'slug' => 'limited-edition',
                'description' => 'Exclusive limited edition pieces. Once they\'re gone, they\'re gone forever!',
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'is_featured' => true,
                'status' => Status::Active,
            ],
        ];

        foreach ($collections as $collection) {
            Collection::create($collection);
        }

        $this->command->info('15 collections seeded successfully!');
    }
}
