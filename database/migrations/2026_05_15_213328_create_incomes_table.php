<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('incomes');
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('external_id')->unique();
            $table->date('date');
            $table->string('supplier_article');
            $table->integer('quantity');
            $table->decimal('total_price', 12, 2);
            $table->string('warehouse_name')->nullable();
            $table->integer('nm_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};