<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChildCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $childCategories = [
            // Electronics > Mobile Phones & Accessories
            'Mobile Phones & Accessories' => [
                'Smartphones',
                'Feature Phones',
                'Phone Cases & Covers',
                'Screen Protectors',
                'Chargers & Cables',
                'Power Banks',
                'Phone Holders & Stands',
                'Selfie Sticks',
            ],
            // Electronics > Computers & Laptops
            'Computers & Laptops' => [
                'Laptops',
                'Desktop Computers',
                'Tablets',
                'Monitors',
                'Keyboards & Mice',
                'External Hard Drives',
                'Laptop Bags',
                'Webcams',
            ],
            // Electronics > Cameras & Photography
            'Cameras & Photography' => [
                'DSLR Cameras',
                'Mirrorless Cameras',
                'Point & Shoot Cameras',
                'Action Cameras',
                'Camera Lenses',
                'Tripods & Monopods',
                'Camera Bags',
                'Memory Cards',
            ],
            // Electronics > Audio & Headphones
            'Audio & Headphones' => [
                'Over-Ear Headphones',
                'In-Ear Headphones',
                'Wireless Earbuds',
                'Bluetooth Speakers',
                'Home Theater Systems',
                'Soundbars',
                'Microphones',
                'Audio Cables',
            ],
            // Fashion > Men's Clothing
            'Men\'s Clothing' => [
                'T-Shirts & Polos',
                'Shirts',
                'Jeans & Pants',
                'Shorts',
                'Suits & Blazers',
                'Jackets & Coats',
                'Sweaters & Hoodies',
                'Activewear',
            ],
            // Fashion > Women's Clothing
            'Women\'s Clothing' => [
                'Dresses',
                'Tops & Blouses',
                'Jeans & Pants',
                'Skirts',
                'Ethnic Wear',
                'Jackets & Coats',
                'Sweaters & Cardigans',
                'Activewear',
            ],
            // Fashion > Footwear
            'Footwear' => [
                'Men\'s Casual Shoes',
                'Men\'s Formal Shoes',
                'Men\'s Sports Shoes',
                'Women\'s Heels',
                'Women\'s Flats',
                'Women\'s Sports Shoes',
                'Kids\' Shoes',
                'Sandals & Slippers',
            ],
            // Fashion > Jewelry & Accessories
            'Jewelry & Accessories' => [
                'Necklaces & Pendants',
                'Earrings',
                'Bracelets & Bangles',
                'Rings',
                'Belts',
                'Scarves & Stoles',
                'Hats & Caps',
                'Sunglasses',
            ],
            // Home & Kitchen > Furniture
            'Furniture' => [
                'Sofas & Couches',
                'Beds & Mattresses',
                'Dining Tables & Chairs',
                'Office Furniture',
                'Storage Cabinets',
                'TV Units',
                'Bookshelves',
                'Outdoor Furniture',
            ],
            // Home & Kitchen > Kitchen Appliances
            'Kitchen Appliances' => [
                'Refrigerators',
                'Microwave Ovens',
                'Dishwashers',
                'Coffee Makers',
                'Blenders & Mixers',
                'Toasters',
                'Air Fryers',
                'Food Processors',
            ],
            // Home & Kitchen > Cookware & Dining
            'Cookware & Dining' => [
                'Pots & Pans',
                'Bakeware',
                'Dinnerware Sets',
                'Glassware',
                'Cutlery',
                'Kitchen Knives',
                'Serving Dishes',
                'Food Storage',
            ],
            // Sports & Outdoors > Fitness Equipment
            'Fitness Equipment' => [
                'Treadmills',
                'Exercise Bikes',
                'Dumbbells & Weights',
                'Yoga Mats',
                'Resistance Bands',
                'Pull-up Bars',
                'Foam Rollers',
                'Jump Ropes',
            ],
            // Sports & Outdoors > Cycling
            'Cycling' => [
                'Road Bikes',
                'Mountain Bikes',
                'Electric Bikes',
                'Bike Helmets',
                'Bike Lights',
                'Bike Locks',
                'Cycling Apparel',
                'Bike Accessories',
            ],
            // Sports & Outdoors > Camping & Hiking
            'Camping & Hiking' => [
                'Tents',
                'Sleeping Bags',
                'Backpacks',
                'Camping Stoves',
                'Hiking Boots',
                'Flashlights & Lanterns',
                'Camping Furniture',
                'Navigation Tools',
            ],
            // Beauty & Personal Care > Skincare
            'Skincare' => [
                'Facial Cleansers',
                'Moisturizers',
                'Serums & Treatments',
                'Face Masks',
                'Sunscreen',
                'Eye Care',
                'Exfoliators',
                'Toners',
            ],
            // Beauty & Personal Care > Makeup
            'Makeup' => [
                'Foundation & Concealer',
                'Lipstick & Lip Gloss',
                'Eye Shadow',
                'Mascara',
                'Eyeliner',
                'Blush & Bronzer',
                'Makeup Brushes',
                'Nail Polish',
            ],
            // Beauty & Personal Care > Hair Care
            'Hair Care' => [
                'Shampoo',
                'Conditioner',
                'Hair Oil',
                'Hair Styling Products',
                'Hair Dryers',
                'Hair Straighteners',
                'Hair Color',
                'Hair Accessories',
            ],
            // Books & Media > Books
            'Books' => [
                'Fiction',
                'Non-Fiction',
                'Children\'s Books',
                'Comics & Graphic Novels',
                'Textbooks',
                'Self-Help',
                'Biography & Memoir',
                'Cookbooks',
            ],
            // Books & Media > Video Games
            'Video Games' => [
                'PlayStation Games',
                'Xbox Games',
                'Nintendo Games',
                'PC Games',
                'VR Games',
                'Game Controllers',
                'Gaming Headsets',
                'Gaming Chairs',
            ],
            // Toys & Games > Building Toys
            'Building Toys' => [
                'LEGO Sets',
                'Building Blocks',
                'Construction Sets',
                'Magnetic Tiles',
                'Model Kits',
                'Wooden Blocks',
                'Engineering Toys',
                'Architecture Sets',
            ],
            // Toys & Games > Educational Toys
            'Educational Toys' => [
                'STEM Toys',
                'Learning Tablets',
                'Alphabet & Numbers',
                'Science Kits',
                'Musical Instruments',
                'Art Supplies',
                'Puzzles',
                'Flash Cards',
            ],
            // Automotive > Car Electronics
            'Car Electronics' => [
                'Car Stereos',
                'GPS Navigation',
                'Dash Cams',
                'Car Speakers',
                'Backup Cameras',
                'Car Chargers',
                'Bluetooth Adapters',
                'Radar Detectors',
            ],
            // Automotive > Car Accessories
            'Car Accessories' => [
                'Car Covers',
                'Floor Mats',
                'Seat Covers',
                'Steering Wheel Covers',
                'Air Fresheners',
                'Phone Holders',
                'Organizers',
                'Sun Shades',
            ],
            // Health & Wellness > Vitamins & Supplements
            'Vitamins & Supplements' => [
                'Multivitamins',
                'Vitamin D',
                'Vitamin C',
                'Omega-3 Fish Oil',
                'Probiotics',
                'Calcium',
                'Protein Supplements',
                'Herbal Supplements',
            ],
            // Health & Wellness > Fitness Nutrition
            'Fitness Nutrition' => [
                'Protein Powder',
                'Pre-Workout',
                'Post-Workout',
                'Energy Bars',
                'BCAAs',
                'Creatine',
                'Mass Gainers',
                'Fat Burners',
            ],
            // Grocery & Gourmet > Beverages
            'Beverages' => [
                'Coffee',
                'Tea',
                'Soft Drinks',
                'Juices',
                'Energy Drinks',
                'Water',
                'Sports Drinks',
                'Milk & Dairy',
            ],
            // Grocery & Gourmet > Snacks & Sweets
            'Snacks & Sweets' => [
                'Chips & Crisps',
                'Cookies & Biscuits',
                'Chocolates',
                'Candy',
                'Nuts & Seeds',
                'Popcorn',
                'Dried Fruits',
                'Protein Bars',
            ],
        ];

        foreach ($childCategories as $subCategoryName => $children) {
            $subCategory = SubCategory::where('name', $subCategoryName)->first();
            
            if ($subCategory) {
                foreach ($children as $childName) {
                    $slug = Str::slug($childName);
                    ChildCategory::updateOrCreate(
                        ['slug' => $slug],
                        [
                            'category_id' => $subCategory->category_id,
                            'sub_category_id' => $subCategory->id,
                            'name' => $childName,
                            'image' => null,
                            'status' => Status::Active->value,
                        ]
                    );
                }
            }
        }
    }
}
