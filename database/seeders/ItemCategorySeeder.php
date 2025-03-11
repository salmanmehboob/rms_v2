<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ItemCategory;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itemCategories = [
            'drinks',
            'fastfoods',
            'junkfoods',
        ];

        foreach ($itemCategories as $category) {
            // Check if the category already exists
            if (!ItemCategory::where('name', $category)->exists()) {
                ItemCategory::create([
                    'name' => $category,
                ]);
            }
        }
    }
}