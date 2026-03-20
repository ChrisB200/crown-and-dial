<?php

use App\Models\BasketItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Watch;
use App\Models\WatchImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class);

function basketMakeBrand(string $name = 'Brand'): Brand
{
    return Brand::create(['name' => $name]);
}

function basketMakeSupplier(string $name = 'Supplier', string $contact = 'supplier@example.com'): Supplier
{
    return Supplier::create(['name' => $name, 'contact' => $contact]);
}

function basketMakeCategory(string $name = 'luxury'): Category
{
    return Category::create(['name' => $name, 'description' => 'Description']);
}

function basketMakeWatch(Category $category, Brand $brand, Supplier $supplier, string $name, string $price): Watch
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

function basketMakeWatchImage(Watch $watch, string $url = 'https://example.com/watch.jpg'): WatchImage
{
    return WatchImage::create([
        'watch_id' => $watch->id,
        'position' => 1,
        'url' => $url,
    ]);
}

test('adding an item to the basket creates a basket item', function () {
    $user = User::factory()->create();

    $category = basketMakeCategory();
    $brand = basketMakeBrand('Rolex');
    $supplier = basketMakeSupplier('Tudor Supplier');
    $watch = basketMakeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    basketMakeWatchImage($watch);

    $response = $this
        ->actingAs($user)
        ->post(route('basket.store', $watch), ['size' => 40]);

    $response->assertRedirect(route('basket.index'));
    $response->assertSessionHas('success', 'Item added to the basket');

    $this->assertDatabaseHas('basket_items', [
        'user_id' => $user->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 1,
    ]);
});

test('adding the same watch and size increments quantity', function () {
    $user = User::factory()->create();

    $category = basketMakeCategory();
    $brand = basketMakeBrand('Rolex');
    $supplier = basketMakeSupplier('Tudor Supplier');
    $watch = basketMakeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    basketMakeWatchImage($watch);

    $this
        ->actingAs($user)
        ->post(route('basket.store', $watch), ['size' => 40]);

    $this
        ->actingAs($user)
        ->post(route('basket.store', $watch), ['size' => 40]);

    $this->assertDatabaseHas('basket_items', [
        'user_id' => $user->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 2,
    ]);
});

test('basket index displays the correct computed total', function () {
    $user = User::factory()->create();

    $category = basketMakeCategory();
    $brand = basketMakeBrand('Rolex');
    $supplier = basketMakeSupplier('Tudor Supplier');

    $watch1 = basketMakeWatch($category, $brand, $supplier, 'Watch A', '100.00');
    basketMakeWatchImage($watch1);

    $watch2 = basketMakeWatch($category, $brand, $supplier, 'Watch B', '50.00');
    basketMakeWatchImage($watch2);

    BasketItem::create([
        'user_id' => $user->id,
        'watch_id' => $watch1->id,
        'size' => 40,
        'quantity' => 2,
    ]);

    BasketItem::create([
        'user_id' => $user->id,
        'watch_id' => $watch2->id,
        'size' => 42,
        'quantity' => 1,
    ]);

    $expectedTotal = (float)$watch1->price * 2 + (float)$watch2->price * 1;
    $expectedTotalFormatted = number_format($expectedTotal, 2);

    $response = $this->actingAs($user)->get(route('basket.index'));
    $response->assertOk();
    $response->assertSeeText('£' . $expectedTotalFormatted);
});

test('updating basket quantity returns JSON and persists', function () {
    $user = User::factory()->create();

    $category = basketMakeCategory();
    $brand = basketMakeBrand('Rolex');
    $supplier = basketMakeSupplier('Tudor Supplier');
    $watch = basketMakeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    basketMakeWatchImage($watch);

    $item = BasketItem::create([
        'user_id' => $user->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 1,
    ]);

    $response = $this
        ->actingAs($user)
        ->patch(route('basket.update', $item), ['quantity' => 3]);

    $response->assertOk();
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('basket_items', [
        'id' => $item->id,
        'quantity' => 3,
    ]);
});

test('deleting a basket item removes it and redirects', function () {
    $user = User::factory()->create();

    $category = basketMakeCategory();
    $brand = basketMakeBrand('Rolex');
    $supplier = basketMakeSupplier('Tudor Supplier');
    $watch = basketMakeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    basketMakeWatchImage($watch);

    $item = BasketItem::create([
        'user_id' => $user->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 1,
    ]);

    $response = $this
        ->actingAs($user)
        ->delete(route('basket.destroy', $item));

    $response->assertRedirect(route('basket.index'));
    $this->assertDatabaseMissing('basket_items', [
        'id' => $item->id,
    ]);
});

