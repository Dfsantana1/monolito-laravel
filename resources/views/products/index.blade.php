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
            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">🔍 Buscar Productos</h2>
                    <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar por nombre</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   placeholder="Ej: iPhone, camiseta..."
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
                                   placeholder="0" min="0" step="0.01"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label for="max_price" class="block text-sm font-medium text-gray-700">Precio máximo</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                                   placeholder="1000" min="0" step="0.01"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        
                        <div class="md:col-span-4 flex flex-wrap gap-2">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 flex items-center">
                                <span>🔍</span>
                                <span class="ml-2">Filtrar</span>
                            </button>
                            <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 flex items-center">
                                <span>🗑️</span>
                                <span class="ml-2">Limpiar</span>
                            </a>
                            <a href="{{ route('products.featured') }}" class="bg-yellow-500 text-white px-6 py-2 rounded-md hover:bg-yellow-600 flex items-center">
                                <span>⭐</span>
                                <span class="ml-2">Destacados</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contador de resultados -->
            @if($products->count() > 0)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        Mostrando {{ $products->count() }} de {{ $products->total() }} productos
                    </p>
                </div>
            @endif

            <!-- Grid de productos -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative">
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
                            
                            @if($product->is_featured)
                                <div class="absolute top-2 right-2">
                                    <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full flex items-center">
                                        ⭐ Destacado
                                    </span>
                                </div>
                            @endif
                            
                            @if($product->sale_price)
                                <div class="absolute top-2 left-2">
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        -{{ $product->discount_percentage }}% OFF
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <div class="mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">📂 {{ $product->category->name }}</p>
                            </div>
                            
                            <div class="flex items-center mb-3">
                                @if($product->sale_price)
                                    <span class="text-2xl font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-lg text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="mr-2">📦</span>
                                    <span class="{{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ $product->stock > 0 ? $product->stock . ' disponibles' : 'Sin stock' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                @if(auth()->check())
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                                        <button type="submit" 
                                                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm font-medium flex items-center justify-center {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                            <span class="mr-2">🛒</span>
                                            {{ $product->stock == 0 ? 'Sin stock' : 'Agregar al carrito' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium flex items-center justify-center">
                                        <span class="mr-2">🔐</span>
                                        Iniciar sesión para comprar
                                    </a>
                                @endif
                                
                                <a href="{{ route('products.show', $product) }}" 
                                   class="w-full text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center justify-center border border-indigo-300 rounded-md py-2 hover:bg-indigo-50">
                                    <span class="mr-2">👁️</span>
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-6xl mb-4">🔍</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No se encontraron productos</h3>
                        <p class="text-gray-500 mb-4">Intenta ajustar tus filtros de búsqueda</p>
                        <a href="{{ route('products.index') }}" 
                           class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                            Ver todos los productos
                        </a>
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