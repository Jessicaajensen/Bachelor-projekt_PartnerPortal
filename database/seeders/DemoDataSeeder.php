<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Partner;
use App\Models\Brand;
use App\Models\Product;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nordlys = Partner::create(['name' => 'Nordlys Ure']);
        $fjordhaven = Partner::create(['name' => 'Fjordhaven Time']);

        $hamilton = Brand::create(['name' => 'Hamilton']);
        $tissot = Brand::create(['name' => 'Tissot']);
        $festina = Brand::create(['name' => 'Festina']);

        $nordlys->brands()->attach([$hamilton->id, $festina->id]);
        $fjordhaven->brands()->attach([$tissot->id, $festina->id]);

        $hamiltonModels = [
        'Khaki Field Mechanical 38 mm',
        'Khaki Field Auto 38 mm',
        'Khaki Field Quartz 38 mm',
        'Jazzmaster Open Heart 40 mm',
        'Jazzmaster Thinline 40 mm',
        'Khaki Navy Scuba Auto 40 mm',
        'Khaki Aviation Pilot Day Date 42 mm',
        'Intra-Matic Auto Chrono 40 mm',
        'Ventura Quartz',
        'American Classic Railroad Auto 40 mm',
];

foreach ($hamiltonModels as $i => $name) {
    Product::create([
        'partner_id' => $nordlys->id,
        'brand_id' => $hamilton->id,
        'sku' => 'HAM-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
        'name' => $name,
        'ean' => '200' . $hamilton->id . str_pad($i + 1, 9, '0', STR_PAD_LEFT),
        'recommended_price' => 2000 + $i * 250,
    ]);
}

        $tissotModels = [
      'PRX Powermatic 80 40 mm',
        'PRX Quartz 35 mm',
        'PRX Quartz 40 mm',
        'Gentleman Powermatic 80 Silicium 40 mm',
        'Le Locle Powermatic 80 39 mm',
        'Seastar 1000 Powermatic 80 40 mm',
        'Heritage Visodate Automatic 40 mm',
        'Supersport Chrono 45 mm',
        'T-Classic Everytime Swissmatic 40 mm',
        'Carson Premium Lady 30 mm',
];

foreach ($tissotModels as $i => $name) {
    Product::create([
        'partner_id' => $fjordhaven->id,
        'brand_id' => $tissot->id,
        'sku' => 'TIS-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
        'name' => $name,
        'ean' => '200' . $tissot->id . str_pad($i + 1, 9, '0', STR_PAD_LEFT),
        'recommended_price' => 2000 + $i * 250,
    ]);
}

$festinaModels = [
      'Timeless Chronograph 43 mm',
        'Chrono Sport 45 mm',
        'Swiss Made Automatic 40 mm',
        'Boyfriend Collection 36 mm',
        'Classics Stål 40 mm',
        'Mademoiselle 32 mm',
        'Rivera 36 mm',
        'Chrono Bike 43 mm',
        'Prestige Quartz 42 mm',
        'Lady Quartz 28 mm',
];

foreach ($festinaModels as $i => $name) {
    $partner = $i < 5 ? $nordlys : $fjordhaven;
    Product::create([
        'partner_id' => $partner->id,
        'brand_id' => $festina->id,
        'sku' => 'FES-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
        'name' => $name,
        'ean' => '200' . $festina->id . str_pad($i + 1, 9, '0', STR_PAD_LEFT),
        'recommended_price' => 2000 + $i * 250,
    ]);
}
    }
}
