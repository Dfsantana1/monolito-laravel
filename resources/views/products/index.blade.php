<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <h1 class="text-3xl font-bold text-gray-900">Mi Tienda</h1>
                <nav class="flex space-x-4">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900">Carrito</a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900">Mis Órdenes</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-gray-900">Cerrar Sesión</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900">Registrarse</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select name="category" id="category" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="min_price" class="block text-sm font-medium text-gray-700">Precio mínimo</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700">Precio máximo</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div class="md:col-span-4">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Filtrar
                            </button>
                            <a href="{{ route('products.index') }}" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grid de productos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
                        <div class="aspect-w-1 aspect-h-1">
                            @if(!empty($product->images) && is_array($product->images))
                                <img src="{{ $product->images[0] }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover"
                                     onerror="this.src='https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}'">
                            @else
                                <img src="https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                                @if($product->is_featured)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full">
                                        Destacado
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-2">{{ $product->category->name }}</p>
                            
                            <div class="flex items-center mb-3">
                                @if($product->sale_price)
                                    <span class="text-2xl font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-lg text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full ml-2">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @else
                                    <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $product->description }}</p>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                                
                                @if(auth()->check())
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                                        <button type="submit" 
                                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm"
                                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                            {{ $product->stock == 0 ? 'Sin stock' : 'Agregar al carrito' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                                        Iniciar sesión
                                    </a>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Ver detalles →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No se encontraron productos.</p>
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</body>
</html>