<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('customer_name');
            $table->string('customer_phone', 30);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->date('entry_date');
            $table->date('estimated_finish_date');
            $table->enum('status', ['diproses', 'selesai', 'diambil'])->default('diproses')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('orders'); }
};
