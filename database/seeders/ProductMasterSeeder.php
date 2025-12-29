<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\HsnSac;
use App\Models\Color;
use App\Models\Size;
use App\Models\Supplier;
use App\Enums\Status;

class ProductMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Units
        $units = [
            ['name' => 'Pieces', 'short_name' => 'PCS', 'type' => 'product', 'status' => Status::Active],
            ['name' => 'Kilograms', 'short_name' => 'KG', 'type' => 'product', 'status' => Status::Active],
            ['name' => 'Grams', 'short_name' => 'G', 'type' => 'product', 'status' => Status::Active],
            ['name' => 'Meters', 'short_name' => 'M', 'type' => 'product', 'status' => Status::Active],
            ['name' => 'Service Hours', 'short_name' => 'HR', 'type' => 'service', 'status' => Status::Active],
        ];
        foreach ($units as $unit) {
            Unit::updateOrCreate(['short_name' => $unit['short_name']], $unit);
        }

        // HSN / SAC
        $hsnSacs = [
            ['code' => '6109', 'description' => 'T-shirts, singlets and other vests, knitted or crocheted', 'type' => 'product', 'status' => Status::Active],
            ['code' => '6203', 'description' => 'Men\'s or boys\' suits, ensembles, jackets, blazers, trousers', 'type' => 'product', 'status' => Status::Active],
            ['code' => '9983', 'description' => 'Other professional, technical and business services', 'type' => 'service', 'status' => Status::Active],
        ];
        foreach ($hsnSacs as $hsn) {
            HsnSac::updateOrCreate(['code' => $hsn['code']], $hsn);
        }

        // Colors
        $colors = [
            ['name' => 'Red', 'color_code' => '#FF0000', 'status' => Status::Active],
            ['name' => 'Blue', 'color_code' => '#0000FF', 'status' => Status::Active],
            ['name' => 'Black', 'color_code' => '#000000', 'status' => Status::Active],
            ['name' => 'White', 'color_code' => '#FFFFFF', 'status' => Status::Active],
        ];
        foreach ($colors as $color) {
            Color::updateOrCreate(['name' => $color['name']], $color);
        }

        // Sizes
        $sizes = [
            ['name' => 'S', 'status' => Status::Active],
            ['name' => 'M', 'status' => Status::Active],
            ['name' => 'L', 'status' => Status::Active],
            ['name' => 'XL', 'status' => Status::Active],
            ['name' => 'XXL', 'status' => Status::Active],
        ];
        foreach ($sizes as $size) {
            Size::updateOrCreate(['name' => $size['name']], $size);
        }

        // Suppliers
        $suppliers = [
            ['name' => 'Default Supplier', 'email' => 'supplier@example.com', 'phone' => '1234567890', 'address' => '123 Supplier Street', 'status' => Status::Active],
        ];
        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(['email' => $supplier['email']], $supplier);
        }
    }
}
