<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electrónicos',
                'description' => 'Dispositivos electrónicos y gadgets',
                'image' => 'electronics.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Ropa',
                'description' => 'Ropa para hombre, mujer y niños',
                'image' => 'clothing.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Hogar y Jardín',
                'description' => 'Artículos para el hogar y jardín',
                'image' => 'home.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Deportes',
                'description' => 'Artículos deportivos y fitness',
                'image' => 'sports.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Libros',
                'description' => 'Libros y material educativo',
                'image' => 'books.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Juguetes',
                'description' => 'Juguetes para niños de todas las edades',
                'image' => 'toys.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'image' => $category['image'],
                'is_active' => $category['is_active'],
            ]);
        }
    }
}
