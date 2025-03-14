<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['General','Technology', 'Health', 'Business', 'Entertainment', 'Sports', 'Science'];
        $categoryData = [];

        foreach ($categories as $category) {
            $categoryData[] = [
                'id' => Str::uuid(),
                'name' => $category,
                'slug' => Str::slug($category),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Category::insert($categoryData);
    }
}
