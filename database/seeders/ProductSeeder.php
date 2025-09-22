<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Electrónicos
            [
                'category' => 'Electrónicos',
                'name' => 'iPhone 15 Pro',
                'description' => 'El iPhone más avanzado con cámara profesional y chip A17 Pro',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'stock' => 50,
                'sku' => 'IPH15PRO-128',
                'images' => ['iphone15pro-1.jpg', 'iphone15pro-2.jpg'],
                'is_featured' => true,
            ],
            [
                'category' => 'Electrónicos',
                'name' => 'MacBook Air M2',
                'description' => 'Laptop ultradelgada con chip M2 y pantalla Liquid Retina',
                'price' => 1199.99,
                'sale_price' => null,
                'stock' => 30,
                'sku' => 'MBA-M2-256',
                'images' => ['macbook-air-m2.jpg'],
                'is_featured' => true,
            ],
            [
                'category' => 'Electrónicos',
                'name' => 'Samsung Galaxy S24',
                'description' => 'Smartphone Android con IA integrada y cámara de 200MP',
                'price' => 999.99,
                'sale_price' => 899.99,
                'stock' => 40,
                'sku' => 'SGS24-256',
                'images' => ['galaxy-s24.jpg'],
                'is_featured' => false,
            ],
            [
                'category' => 'Electrónicos',
                'name' => 'AirPods Pro 2',
                'description' => 'Auriculares inalámbricos con cancelación activa de ruido',
                'price' => 249.99,
                'sale_price' => null,
                'stock' => 100,
                'sku' => 'APP2-GEN2',
                'images' => ['airpods-pro-2.jpg'],
                'is_featured' => false,
            ],

            // Ropa
            [
                'category' => 'Ropa',
                'name' => 'Camiseta Básica Algodón',
                'description' => 'Camiseta 100% algodón orgánico, cómoda y duradera',
                'price' => 29.99,
                'sale_price' => 19.99,
                'stock' => 200,
                'sku' => 'CAM-ALG-M',
                'images' => ['camiseta-basica.jpg'],
                'is_featured' => false,
            ],
            [
                'category' => 'Ropa',
                'name' => 'Jeans Clásicos',
                'description' => 'Jeans de mezclilla con corte clásico y lavado vintage',
                'price' => 79.99,
                'sale_price' => null,
                'stock' => 150,
                'sku' => 'JEANS-CL-32',
                'images' => ['jeans-clasicos.jpg'],
                'is_featured' => false,
            ],
            [
                'category' => 'Ropa',
                'name' => 'Chaqueta Deportiva',
                'description' => 'Chaqueta deportiva impermeable con capucha',
                'price' => 89.99,
                'sale_price' => 69.99,
                'stock' => 80,
                'sku' => 'CHAQ-DEP-M',
                'images' => ['chaqueta-deportiva.jpg'],
                'is_featured' => true,
            ],

            // Hogar y Jardín
            [
                'category' => 'Hogar y Jardín',
                'name' => 'Set de Ollas Antiadherentes',
                'description' => 'Set de 5 ollas antiadherentes de acero inoxidable',
                'price' => 149.99,
                'sale_price' => 119.99,
                'stock' => 60,
                'sku' => 'OLLAS-ANTI-5P',
                'images' => ['ollas-antiadherentes.jpg'],
                'is_featured' => false,
            ],
            [
                'category' => 'Hogar y Jardín',
                'name' => 'Aspiradora Robot',
                'description' => 'Aspiradora robot inteligente con mapeo de habitaciones',
                'price' => 299.99,
                'sale_price' => null,
                'stock' => 25,
                'sku' => 'ASP-ROB-INT',
                'images' => ['aspiradora-robot.jpg'],
                'is_featured' => true,
            ],

            // Deportes
            [
                'category' => 'Deportes',
                'name' => 'Pelota de Fútbol Oficial',
                'description' => 'Pelota de fútbol oficial FIFA Quality Pro',
                'price' => 49.99,
                'sale_price' => 39.99,
                'stock' => 100,
                'sku' => 'PEL-FUT-OF',
                'images' => ['pelota-futbol.jpg'],
                'is_featured' => false,
            ],
            [
                'category' => 'Deportes',
                'name' => 'Mancuernas Ajustables',
                'description' => 'Set de mancuernas ajustables de 5-50 lbs',
                'price' => 199.99,
                'sale_price' => null,
                'stock' => 40,
                'sku' => 'MANC-AJ-5-50',
                'images' => ['mancuernas-ajustables.jpg'],
                'is_featured' => true,
            ],

            // Libros
            [
                'category' => 'Libros',
                'name' => 'El Principito',
                'description' => 'Clásico de la literatura francesa de Antoine de Saint-Exupéry',
                'price' => 12.99,
                'sale_price' => null,
                'stock' => 200,
                'sku' => 'LIB-PRIN-ESP',
                'images' => ['principito.jpg'],
                'is_featured' => false,
            ],
            [
                'category' => 'Libros',
                'name' => 'Clean Code',
                'description' => 'Guía para escribir código limpio y mantenible',
                'price' => 39.99,
                'sale_price' => 29.99,
                'stock' => 80,
                'sku' => 'LIB-CLEAN-ENG',
                'images' => ['clean-code.jpg'],
                'is_featured' => true,
            ],

            // Juguetes
            [
                'category' => 'Juguetes',
                'name' => 'Lego Creator Set',
                'description' => 'Set de construcción Lego Creator con 1000+ piezas',
                'price' => 79.99,
                'sale_price' => 59.99,
                'stock' => 120,
                'sku' => 'LEGO-CRE-1000',
                'images' => ['lego-creator.jpg'],
                'is_featured' => false,
            ],
            [
                'category' => 'Juguetes',
                'name' => 'Puzzle 1000 Piezas',
                'description' => 'Puzzle de 1000 piezas con imagen de paisaje montañoso',
                'price' => 24.99,
                'sale_price' => null,
                'stock' => 150,
                'sku' => 'PUZ-1000-MONT',
                'images' => ['puzzle-1000.jpg'],
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            
            if ($category) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name']),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'sale_price' => $productData['sale_price'],
                    'stock' => $productData['stock'],
                    'sku' => $productData['sku'],
                    'images' => $productData['images'],
                    'is_active' => true,
                    'is_featured' => $productData['is_featured'],
                ]);
            }
        }
    }
}
