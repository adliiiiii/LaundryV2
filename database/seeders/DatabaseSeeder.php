<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@laundry.test');
        User::updateOrCreate(['email' => $adminEmail], [
            'name' => env('ADMIN_NAME', 'Administrator'),
            'password' => User::where('email', $adminEmail)->exists()
                ? User::where('email', $adminEmail)->value('password')
                : Hash::make(env('ADMIN_PASSWORD', 'password')),
        ]);

        $satuan = Category::firstOrCreate(['name' => 'Satuan'], ['type' => 'satuan', 'estimasi_hari' => 2, 'is_active' => true]);
        $kiloan = Category::firstOrCreate(['name' => 'Kiloan'], ['type' => 'kiloan', 'estimasi_hari' => 2, 'is_active' => true]);
        Item::firstOrCreate(['category_id' => $satuan->id, 'name' => 'Bed cover'], ['price' => 25000, 'unit' => 'pcs', 'is_active' => true]);
        Item::firstOrCreate(['category_id' => $satuan->id, 'name' => 'Jaket'], ['price' => 15000, 'unit' => 'pcs', 'is_active' => true]);
        Item::firstOrCreate(['category_id' => $kiloan->id, 'name' => 'Cuci kering'], ['price' => 7000, 'unit' => 'kg', 'is_active' => true]);
        Item::firstOrCreate(['category_id' => $kiloan->id, 'name' => 'Cuci setrika'], ['price' => 9000, 'unit' => 'kg', 'is_active' => true]);
    }
}
