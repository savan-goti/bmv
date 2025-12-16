<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Enums\Status;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Just Do It. Nike is a leading global sportswear and athletic footwear brand known for innovation and performance.',
                'website' => 'https://www.nike.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Impossible is Nothing. Adidas is a multinational corporation that designs and manufactures sports shoes, clothing and accessories.',
                'website' => 'https://www.adidas.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Puma',
                'slug' => 'puma',
                'description' => 'Forever Faster. Puma is a German multinational corporation that designs and manufactures athletic and casual footwear, apparel and accessories.',
                'website' => 'https://www.puma.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Reebok',
                'slug' => 'reebok',
                'description' => 'Be More Human. Reebok is an American-inspired global brand with a deep fitness heritage and a clear mission.',
                'website' => 'https://www.reebok.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Under Armour',
                'slug' => 'under-armour',
                'description' => 'The Only Way Is Through. Under Armour is an American sports equipment company that manufactures footwear, sports and casual apparel.',
                'website' => 'https://www.underarmour.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'New Balance',
                'slug' => 'new-balance',
                'description' => 'Fearlessly Independent Since 1906. New Balance is an American sports footwear and apparel brand.',
                'website' => 'https://www.newbalance.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Converse',
                'slug' => 'converse',
                'description' => 'Converse is an American shoe company that designs, distributes, and licenses sneakers, skating shoes, lifestyle brand footwear, apparel, and accessories.',
                'website' => 'https://www.converse.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Vans',
                'slug' => 'vans',
                'description' => 'Off The Wall. Vans is an American manufacturer of skateboarding shoes and related apparel.',
                'website' => 'https://www.vans.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Asics',
                'slug' => 'asics',
                'description' => 'Sound Mind, Sound Body. ASICS is a Japanese multinational corporation which produces footwear and sports equipment.',
                'website' => 'https://www.asics.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Fila',
                'slug' => 'fila',
                'description' => 'Fila is a South Korean-owned sportswear brand that was originally founded in Italy in 1911.',
                'website' => 'https://www.fila.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Levi\'s',
                'slug' => 'levis',
                'description' => 'Quality Never Goes Out Of Style. Levi Strauss & Co. is an American clothing company known worldwide for its Levi\'s brand of denim jeans.',
                'website' => 'https://www.levi.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'H&M',
                'slug' => 'hm',
                'description' => 'H&M is a Swedish multinational clothing-retail company known for its fast-fashion clothing.',
                'website' => 'https://www.hm.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Zara',
                'slug' => 'zara',
                'description' => 'Zara is a Spanish fast fashion retailer based in Arteixo, Galicia, Spain.',
                'website' => 'https://www.zara.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Gap',
                'slug' => 'gap',
                'description' => 'Gap Inc. is an American worldwide clothing and accessories retailer.',
                'website' => 'https://www.gap.com',
                'status' => Status::Active,
            ],
            [
                'name' => 'Calvin Klein',
                'slug' => 'calvin-klein',
                'description' => 'Calvin Klein Inc. is an American fashion house and luxury goods manufacturer.',
                'website' => 'https://www.calvinklein.com',
                'status' => Status::Active,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $this->command->info('15 brands seeded successfully!');
    }
}
