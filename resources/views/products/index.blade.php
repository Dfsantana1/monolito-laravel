<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos - Ecommerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-shopping-bag mr-2 text-blue-600"></i>Ecommerce
                    </a>
                </div>
                <nav class="flex space-x-4">
                    <a href="{{ route('home') }}" class="text-blue-600 font-medium flex items-center">
                        <i class="fas fa-home mr-1"></i>Inicio
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                        <i class="fas fa-box mr-1"></i>Productos
                    </a>
                    @if(auth()->check())
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                            <i class="fas fa-shopping-cart mr-1"></i>Carrito
                            @if(auth()->user()->cartItems->count() > 0)
                                <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full ml-1">{{ auth()->user()->cartItems->count() }}</span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                            <i class="fas fa-receipt mr-1"></i>Mis Pedidos
                        </a>
                        <div class="relative group">
                            <button class="text-gray-700 hover:text-gray-900 flex items-center">
                                <i class="fas fa-user mr-1"></i>{{ auth()->user()->name }}
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>Perfil
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                            <i class="fas fa-sign-in-alt mr-1"></i>Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                            <i class="fas fa-user-plus mr-1"></i>Registrarse
                        </a>
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
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-search mr-2"></i>Buscar Productos
                    </h2>
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
                                <i class="fas fa-search mr-2"></i>Filtrar
                            </button>
                            <a href="{{ route('products.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400 flex items-center">
                                <i class="fas fa-times mr-2"></i>Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contador de resultados -->
            @if($products->count() > 0)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
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
                                        <i class="fas fa-star mr-1"></i>Destacado
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
                                <p class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-folder mr-1"></i>{{ $product->category->name }}
                                </p>
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
                                    <i class="fas fa-box mr-2"></i>
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
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            {{ $product->stock == 0 ? 'Sin stock' : 'Agregar al carrito' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium flex items-center justify-center">
                                        <i class="fas fa-lock mr-2"></i>Iniciar sesión para comprar
                                    </a>
                                @endif
                                
                                <a href="{{ route('products.show', $product) }}" 
                                   class="w-full text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center justify-center border border-indigo-300 rounded-md py-2 hover:bg-indigo-50">
                                    <i class="fas fa-eye mr-2"></i>Ver detalles
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