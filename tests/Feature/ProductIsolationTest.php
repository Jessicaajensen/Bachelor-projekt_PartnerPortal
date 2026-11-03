<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DemoDataSeeder::class);
    }

    public function test_partner_ser_kun_egne_produkter(): void
    {
        $anna = User::where('email', 'anna@nordlys.test')->firstOrFail();

        $eget = Product::where('partner_id', $anna->partner_id)->firstOrFail();
        $andet = Product::where('partner_id', '!=', $anna->partner_id)->firstOrFail();

        $response = $this->actingAs($anna)->get('/products');

        $response->assertOk();
        $response->assertSee($eget->sku);
        $response->assertDontSee($andet->sku);
    }

    
    public function test_partner_faar_404_paa_anden_partners_produkt(): void
    {
        $anna = User::where('email', 'anna@nordlys.test')->firstOrFail();

        $andet = Product::where('partner_id', '!=', $anna->partner_id)->firstOrFail();

        $response = $this->actingAs($anna)->get('/products/' . $andet->id);

        $response->assertNotFound();
    }

    public function test_partner_faar_404_paa_samme_maerke_hos_anden_partner(): void
    {
        $anna = User::where('email', 'anna@nordlys.test')->firstOrFail();

        $annasMaerker = Product::where('partner_id', $anna->partner_id)->pluck('brand_id');

        $samtMaerke = Product::where('partner_id', '!=', $anna->partner_id)
            ->whereIn('brand_id', $annasMaerker)
            ->firstOrFail();

        $response = $this->actingAs($anna)->get('/products/' . $samtMaerke->id);

        $response->assertNotFound();
    }
}