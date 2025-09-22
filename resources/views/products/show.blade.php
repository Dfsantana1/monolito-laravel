<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Mi Tienda</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <h1 class="text-3xl font-bold text-gray-900">Mi Tienda</h1>
                <nav class="flex space-x-4">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900">Catálogo</a>
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
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('products.index') }}" class="hover:text-gray-700">Catálogo</a></li>
                    <li>/</li>
                    <li><a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="hover:text-gray-700">{{ $product->category->name }}</a></li>
                    <li>/</li>
                    <li class="text-gray-900">{{ $product->name }}</li>
                </ol>
            </nav>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
                    <!-- Imagen del producto -->
                    <div>
                        @if(!empty($product->images) && is_array($product->images))
                            <img src="{{ $product->images[0] }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-96 object-cover rounded-lg"
                                 onerror="this.src='https://via.placeholder.com/600x600?text={{ urlencode($product->name) }}'">
                        @else
                            <img src="https://via.placeholder.com/600x600?text={{ urlencode($product->name) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-96 object-cover rounded-lg">
                        @endif
                    </div>

                    <!-- Información del producto -->
                    <div>
                        <div class="mb-4">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <p class="text-lg text-gray-600">{{ $product->category->name }}</p>
                        </div>

                        <div class="mb-6">
                            @if($product->sale_price)
                                <div class="flex items-center mb-2">
                                    <span class="text-4xl font-bold text-red-600">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-2xl text-gray-500 line-through ml-4">${{ number_format($product->price, 2) }}</span>
                                    <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full ml-4">
                                        -{{ $product->discount_percentage }}% OFF
                                    </span>
                                </div>
                            @else
                                <span class="text-4xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h3>
                            <p class="text-gray-600">{{ $product->description }}</p>
                        </div>

                        <div class="mb-6">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-semibold text-gray-900">SKU:</span>
                                    <span class="text-gray-600">{{ $product->sku }}</span>
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900">Stock:</span>
                                    <span class="text-gray-600">{{ $product->stock }} unidades</span>
                                </div>
                            </div>
                        </div>

                        @if(auth()->check())
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-6">
                                @csrf
                                <div class="flex items-center space-x-4">
                                    <label for="quantity" class="text-sm font-medium text-gray-900">Cantidad:</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                           class="w-20 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <button type="submit" 
                                            class="bg-indigo-600 text-white px-8 py-3 rounded-md hover:bg-indigo-700 font-medium"
                                            {{ $product->stock == 0 ? 'disabled' : '' }}>
                                        {{ $product->stock == 0 ? 'Sin stock' : 'Agregar al carrito' }}
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="mb-6">
                                <a href="{{ route('login') }}" 
                                   class="bg-indigo-600 text-white px-8 py-3 rounded-md hover:bg-indigo-700 font-medium inline-block">
                                    Iniciar sesión para comprar
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Productos relacionados -->
            @if($relatedProducts->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Productos relacionados</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow">
                                <div class="aspect-w-1 aspect-h-1">
                                    @if(!empty($relatedProduct->images) && is_array($relatedProduct->images))
                                        <img src="{{ $relatedProduct->images[0] }}" 
                                             alt="{{ $relatedProduct->name }}" 
                                             class="w-full h-48 object-cover"
                                             onerror="this.src='https://via.placeholder.com/300x300?text={{ urlencode($relatedProduct->name) }}'">
                                    @else
                                        <img src="https://via.placeholder.com/300x300?text={{ urlencode($relatedProduct->name) }}" 
                                             alt="{{ $relatedProduct->name }}" 
                                             class="w-full h-48 object-cover">
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $relatedProduct->name }}</h3>
                                    
                                    <div class="flex items-center mb-3">
                                        @if($relatedProduct->sale_price)
                                            <span class="text-xl font-bold text-red-600">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                            <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($relatedProduct->price, 2) }}</span>
                                        @else
                                            <span class="text-xl font-bold text-gray-900">${{ number_format($relatedProduct->price, 2) }}</span>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('products.show', $relatedProduct) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        Ver detalles →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
