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
        // Obtener datos de DummyJSON
        $dummyProducts = $this->fetchDummyProducts();
        
        // Mapear categorías de DummyJSON a nuestras categorías
        $categoryMapping = [
            'smartphones' => 'Electrónicos',
            'laptops' => 'Electrónicos',
            'mobile-accessories' => 'Electrónicos',
            'mens-shirts' => 'Ropa',
            'mens-shoes' => 'Ropa',
            'mens-watches' => 'Ropa',
            'womens-dresses' => 'Ropa',
            'womens-shoes' => 'Ropa',
            'womens-watches' => 'Ropa',
            'womens-bags' => 'Ropa',
            'womens-jewellery' => 'Ropa',
            'furniture' => 'Hogar y Jardín',
            'home-decoration' => 'Hogar y Jardín',
            'kitchen-accessories' => 'Hogar y Jardín',
            'sports-accessories' => 'Deportes',
            'sunglasses' => 'Deportes',
            'beauty' => 'Libros',
            'fragrances' => 'Libros',
            'skin-care' => 'Libros',
            'groceries' => 'Juguetes',
            'tops' => 'Ropa',
            'tablets' => 'Electrónicos',
            'motorcycle' => 'Deportes',
            'vehicle' => 'Deportes',
        ];

        foreach ($dummyProducts as $productData) {
            $categoryName = $categoryMapping[$productData['category']] ?? 'Electrónicos';
            $category = Category::where('name', $categoryName)->first();
            
            if ($category) {
                // Calcular precio de oferta si hay descuento
                $salePrice = null;
                if ($productData['discountPercentage'] > 0) {
                    $salePrice = $productData['price'] * (1 - $productData['discountPercentage'] / 100);
                }

                Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['title'],
                    'slug' => Str::slug($productData['title']),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'sale_price' => $salePrice,
                    'stock' => $productData['stock'],
                    'sku' => $productData['sku'] ?? 'SKU-' . $productData['id'],
                    'images' => $productData['images'] ?? [],
                    'is_active' => true,
                    'is_featured' => $productData['rating'] >= 4.5, // Productos con rating alto son destacados
                ]);
            }
        }
    }

    private function fetchDummyProducts()
    {
        $products = [];
        
        // Obtener productos de diferentes categorías
        $categories = ['smartphones', 'laptops', 'mens-shirts', 'womens-dresses', 'furniture', 'sports-accessories'];
        
        foreach ($categories as $category) {
            $response = file_get_contents("https://dummyjson.com/products/category/{$category}?limit=5");
            $data = json_decode($response, true);
            
            if (isset($data['products'])) {
                $products = array_merge($products, $data['products']);
            }
        }
        
        // Si no se obtuvieron productos, usar algunos productos específicos
        if (empty($products)) {
            $response = file_get_contents('https://dummyjson.com/products?limit=20');
            $data = json_decode($response, true);
            $products = $data['products'] ?? [];
        }
        
        return $products;
    }
}
