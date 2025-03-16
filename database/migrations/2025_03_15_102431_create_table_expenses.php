<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('expense_category_id')
                ->constrained('expense_categories') // Matches parent table name
                ->cascadeOnDelete(); // Cascade delete if parent is deleted

            $table->string('name'); // Expense name
            $table->string('image')->nullable(); // Optional image
            $table->integer('Amount'); // Quantity of expense
            $table->string('expense_details'); // Cost price
            $table->boolean('is_stock')->default(1); // Boolean for stock
            $table->softDeletes(); // Soft deletes
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};