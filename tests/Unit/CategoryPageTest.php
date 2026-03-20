<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Watch;
use App\Models\WatchImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class);

function makeBrand(string $name = 'Brand'): Brand
{
    return Brand::create(['name' => $name]);
}

function makeSupplier(string $name = 'Supplier', string $contact = 'contact@example.com'): Supplier
{
    return Supplier::create(['name' => $name, 'contact' => $contact]);
}

function makeWatch(Category $category, Brand $brand, Supplier $supplier, string $name, string $price): Watch
{
    return Watch::create([
        'brand_id' => $brand->id,
        'supplier_id' => $supplier->id,
        'category_id' => $category->id,
        'price' => $price,
        'name' => $name,
        'description' => 'A test watch description.',
    ]);
}

function makeWatchImage(Watch $watch, string $url = 'https://example.com/watch.jpg'): WatchImage
{
    return WatchImage::create([
        'watch_id' => $watch->id,
        'position' => 1,
        'url' => $url,
    ]);
}

test('category page renders for a valid category', function () {
    $category = Category::create(['name' => 'luxury', 'description' => 'Luxury watches.']);
    $brand = makeBrand('Rolex');
    $supplier = makeSupplier('Tudor Supplier', 'supplier@example.com');

    $watch = makeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    makeWatchImage($watch);

    $response = $this->get(route('watches.category', $category->name));

    $response->assertOk();
    $response->assertSeeText('LUXURY WATCHES');
    $response->assertSeeText($watch->name);
});

test('category page filters watches by q search param', function () {
    $category = Category::create(['name' => 'luxury', 'description' => 'Luxury watches.']);
    $brand = makeBrand('Rolex');
    $supplier = makeSupplier('Tudor Supplier', 'supplier@example.com');

    $matching = makeWatch($category, $brand, $supplier, 'Alpha Watch', '250.00');
    makeWatchImage($matching, 'https://example.com/alpha.jpg');

    $nonMatching = makeWatch($category, $brand, $supplier, 'Beta Watch', '150.00');
    makeWatchImage($nonMatching, 'https://example.com/beta.jpg');

    $response = $this->get(route('watches.category', ['slug' => $category->name, 'q' => 'Alpha']));

    $response->assertOk();
    $response->assertSeeText($matching->name);
    $response->assertDontSeeText($nonMatching->name);
});

test('category page returns 404 for unknown category', function () {
    $response = $this->get(route('watches.category', 'does-not-exist'));
    $response->assertNotFound();
});

