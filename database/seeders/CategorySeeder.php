<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Antipasto',
            'Aperitivo',
            'Primo',
            'Secondo',
            'Contorno',
            'Dessert',
            'Panino',
            'Pizza',
            'Poke',
            'Bevande'
        ];

        foreach ($categories as $category) {
            $new_category = new Category();
            $new_category->title = $category;
            $new_category->save();
        }
    }
}
