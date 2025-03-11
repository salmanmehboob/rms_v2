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
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT (Primary Key)

            // Foreign key reference to item_categories table
            $table->foreignId('item_category_id')
                ->constrained('item_categories')
                ->cascadeOnDelete(); // Ensures category deletion cascades to items

            $table->string('name'); // VARCHAR(255) NOT NULL
            $table->string('image', 100)->nullable(); // VARCHAR(100) NULL DEFAULT NULL
            $table->integer('quantity'); // INT(11) NOT NULL
            $table->decimal('cost_price', 20); // VARCHAR(20) NOT NULL
            $table->decimal('retail_price', 20); // VARCHAR(20) NOT NULL
            $table->boolean('status')->default(0); // TINYINT(1) NULL DEFAULT '0'

            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // deleted_at (for soft delete support)
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