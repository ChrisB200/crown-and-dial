<?php

use App\Models\Address;
use App\Models\BasketItem;
use App\Models\Brand;
use App\Models\Card;
use App\Models\Category;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Watch;
use App\Models\WatchImage;
use App\Models\WatchOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class);

function checkoutMakeCategory(string $name = 'luxury'): Category
{
    return Category::create(['name' => $name, 'description' => 'Description']);
}

function checkoutMakeBrand(string $name = 'Rolex'): Brand
{
    return Brand::create(['name' => $name]);
}

function checkoutMakeSupplier(string $name = 'Supplier', string $contact = 'supplier@example.com'): Supplier
{
    return Supplier::create(['name' => $name, 'contact' => $contact]);
}

function checkoutMakeWatch(
    Category $category,
    Brand $brand,
    Supplier $supplier,
    string $name,
    string $price
): Watch {
    return Watch::create([
        'brand_id' => $brand->id,
        'supplier_id' => $supplier->id,
        'category_id' => $category->id,
        'price' => $price,
        'name' => $name,
        'description' => 'A test watch description.',
    ]);
}

function checkoutMakeWatchImage(Watch $watch, string $url = 'https://example.com/watch.jpg'): WatchImage
{
    return WatchImage::create([
        'watch_id' => $watch->id,
        'position' => 1,
        'url' => $url,
    ]);
}

function checkoutPayload(): array
{
    return [
        'shipping-line-1' => '10 Test Street',
        'shipping-postcode' => 'AB12 3CD',
        'shipping-city' => 'Test City',
        'billing-line-1' => '20 Billing Street',
        'billing-postcode' => 'EF45 6GH',
        'billing-city' => 'Billing City',
        'card-name' => 'Test Cardholder',
        'card-number' => '4111111111111111',
        'card-expiry' => '12/34',
        'card-cvv' => '123',
    ];
}

test('checkout redirects back to basket when basket is empty', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('checkout.index'));

    $response->assertRedirect(route('basket.index'));
    $response->assertSessionHas('error', 'There are no items in your basket');
});

test('checkout index page renders when basket has items', function () {
    $user = User::factory()->create();

    $category = checkoutMakeCategory();
    $brand = checkoutMakeBrand('Rolex');
    $supplier = checkoutMakeSupplier('Tudor Supplier');

    $watch = checkoutMakeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    checkoutMakeWatchImage($watch);

    BasketItem::create([
        'user_id' => $user->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user)->get(route('checkout.index'));

    $response->assertOk();
    $response->assertSeeText('CHECKOUT');
    $response->assertSeeText($watch->name);
    $response->assertSeeText('£' . number_format((float)$watch->price * 2, 2));
});

test('placing an order creates records and clears the basket', function () {
    $user = User::factory()->create();

    $category = checkoutMakeCategory();
    $brand = checkoutMakeBrand('Rolex');
    $supplier = checkoutMakeSupplier('Tudor Supplier');

    $watch = checkoutMakeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    checkoutMakeWatchImage($watch);

    BasketItem::create([
        'user_id' => $user->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 2,
    ]);

    $expectedTotal = (float)$watch->price * 2;

    $response = $this
        ->actingAs($user)
        ->post(route('checkout.store'), checkoutPayload());

    $order = Order::query()->firstOrFail();

    $response->assertRedirect(route('checkout.show', $order));
    $response->assertSessionHas('success', 'Order placed successfully!');

    expect($order->status)->toBe('pending');
    expect((float)$order->total)->toBe($expectedTotal);
    expect($order->user_id)->toBe($user->id);

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'shipping_address_id' => $order->shipping_address_id,
        'billing_address_id' => $order->billing_address_id,
        'card_id' => $order->card_id,
    ]);

    $this->assertDatabaseHas('addresses', [
        'id' => $order->shipping_address_id,
        'user_id' => $user->id,
        'line_1' => '10 Test Street',
    ]);

    $this->assertDatabaseHas('addresses', [
        'id' => $order->billing_address_id,
        'user_id' => $user->id,
        'line_1' => '20 Billing Street',
    ]);

    $this->assertDatabaseHas('cards', [
        'id' => $order->card_id,
        'user_id' => $user->id,
        'name' => 'Test Cardholder',
    ]);

    $this->assertDatabaseHas('watch_orders', [
        'order_id' => $order->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 2,
    ]);

    $this->assertDatabaseCount('watch_orders', 1);
    $this->assertDatabaseMissing('basket_items', [
        'user_id' => $user->id,
    ]);
});

test('checkout show page renders for a placed order', function () {
    $user = User::factory()->create();

    $category = checkoutMakeCategory();
    $brand = checkoutMakeBrand('Rolex');
    $supplier = checkoutMakeSupplier('Tudor Supplier');

    $watch = checkoutMakeWatch($category, $brand, $supplier, 'Test Watch', '250.00');
    checkoutMakeWatchImage($watch);

    BasketItem::create([
        'user_id' => $user->id,
        'watch_id' => $watch->id,
        'size' => 40,
        'quantity' => 2,
    ]);

    $this->actingAs($user)->post(route('checkout.store'), checkoutPayload());

    $order = Order::query()->firstOrFail();

    $response = $this->actingAs($user)->get(route('checkout.show', $order));

    // This is intentionally a render assertion to catch any Blade/view issues.
    $response->assertOk();
    $response->assertSeeText('Successfully placed your order');
});

