<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_category_id')->constrained('item_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('quantity');
            $table->decimal('cost_price', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->boolean('is_stock')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
}
