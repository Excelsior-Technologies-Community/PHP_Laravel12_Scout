<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'iPhone 16 Pro Max', 'description' => 'Latest iPhone with A18 Pro chip, 6.7-inch Super Retina XDR display, titanium design.', 'price' => 1199.00],
            ['name' => 'Samsung Galaxy S25 Ultra', 'description' => 'Premium Android flagship with 200MP camera, 6.8-inch Dynamic AMOLED 2X display.', 'price' => 1299.00],
            ['name' => 'MacBook Pro 16 M4', 'description' => 'Professional laptop with M4 Pro chip, 16-core CPU, Liquid Retina XDR display.', 'price' => 2499.00],
            ['name' => 'Sony WH-1000XM5 Headphones', 'description' => 'Industry-leading noise canceling wireless headphones with 30-hour battery life.', 'price' => 399.99],
            ['name' => 'PlayStation 5 Digital Edition', 'description' => 'Next-generation gaming console with SSD storage and 4K gaming support.', 'price' => 449.00],
            ['name' => 'Nintendo Switch OLED', 'description' => 'Hybrid gaming console with 7-inch OLED screen and enhanced audio.', 'price' => 349.99],
            ['name' => 'DJI Mavic 4 Pro Drone', 'description' => '4K HDR drone with 51-minute flight time, triple cameras, and ActiveTrack 5.0.', 'price' => 899.00],
            ['name' => 'Canon EOS R5 Mark II', 'description' => 'Full-frame mirrorless camera with 60.6MP sensor, 8K video, and IBIS stabilization.', 'price' => 3899.00],
            ['name' => 'Tesla Model 3', 'description' => 'All-electric sedan with autopilot, 272-mile range, and 16-inch touchscreen interior.', 'price' => 42490.00],
            ['name' => 'Rolex Submariner', 'description' => 'Iconic luxury watch with Oyster case, rotating bezel, and luminescent markers.', 'price' => 15900.00],
            ['name' => 'Herman Miller Aeron Chair', 'description' => 'Ergonomic office chair with PostureFit SL, adjustable arms, and breathable mesh.', 'price' => 1895.00],
            ['name' => 'Aesop Hand Lotion Set', 'description' => 'Premium hand care collection with nourishing ingredients and signature fragrance.', 'price' => 85.00],
            ['name' => 'Apple AirPods Pro 3', 'description' => 'Active Noise Cancelling earbuds with Transparency mode, up to 24 hours battery life.', 'price' => 249.00],
            ['name' => 'Oura Ring Generation 4', 'description' => 'Health and fitness ring with sleep tracking, heart rate monitoring, and body temperature sensing.', 'price' => 299.00],
            ['name' => 'Breville Barista Pro Espresso Machine', 'description' => 'Automatic espresso machine with conical burr grinder, PID temperature control, and steam wand.', 'price' => 1299.95],
        ];

        foreach ($products as $p) {
            Product::create([
                'name' => $p['name'],
                'description' => $p['description'],
                'price' => $p['price'],
            ]);
        }

        $this->command->info('15 products added successfully!');
    }
}
