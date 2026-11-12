<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_an_order_with_a_service_item(): void
    {
        $admin = User::factory()->create();
        $category = Category::create(['name' => 'Kiloan', 'type' => 'kiloan', 'estimasi_hari' => 2, 'is_active' => true]);
        $item = Item::create(['category_id' => $category->id, 'name' => 'Cuci setrika', 'price' => 9000, 'unit' => 'kg', 'is_active' => true]);

        $this->actingAs($admin)->post(route('orders.store'), [
            'customer_name' => 'Pelanggan Tes',
            'customer_phone' => '08123456789',
            'entry_date' => '2026-11-12',
            'items' => [['item_id' => (string) $item->id, 'qty' => '2.5']],
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'order_code' => 'LDR-20261112-0001',
            'customer_name' => 'Pelanggan Tes',
            'total_price' => 22500,
            'estimated_finish_date' => '2026-11-14',
        ]);
        $this->assertDatabaseHas('order_items', [
            'item_id' => $item->id,
            'item_name' => 'Cuci setrika',
            'qty' => 2.5,
            'subtotal' => 22500,
        ]);
    }

    public function test_order_create_page_has_guidance_when_no_active_service_exists(): void
    {
        $this->actingAs(User::factory()->create())->get(route('orders.create'))
            ->assertOk()->assertSee('Belum ada layanan aktif')->assertSee('Tambah item layanan');
    }
}
