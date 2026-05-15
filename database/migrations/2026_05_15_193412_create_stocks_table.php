<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('supplier_article');
            $table->integer('quantity');
            $table->integer('quantity_full');
            $table->string('warehouse_name');
            $table->integer('nm_id')->unique();
            $table->decimal('price', 10, 2);
            $table->integer('discount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};