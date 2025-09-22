<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Ecommerce</title>
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
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                        <i class="fas fa-home mr-1"></i>Inicio
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                        <i class="fas fa-box mr-1"></i>Productos
                    </a>
                    <a href="{{ route('cart.index') }}" class="text-blue-600 font-medium flex items-center">
                        <i class="fas fa-shopping-cart mr-1"></i>Carrito
                        @if(auth()->check() && auth()->user()->cartItems->count() > 0)
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
                </nav>
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <span class="mr-2">🛒</span>
                            Carrito de Compras
                        </h2>
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $cartItems->count() }} {{ $cartItems->count() == 1 ? 'producto' : 'productos' }}
                        </span>
                    </div>

                    @if($cartItems->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Items del carrito -->
                            <div class="lg:col-span-2">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4 py-6 border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        @if(!empty($item->product->images) && is_array($item->product->images))
                                            <img src="{{ $item->product->images[0] }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-24 h-24 object-cover rounded-lg shadow-sm"
                                                 onerror="this.src='https://via.placeholder.com/100x100?text={{ urlencode($item->product->name) }}'">
                                        @else
                                            <img src="https://via.placeholder.com/100x100?text={{ urlencode($item->product->name) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-24 h-24 object-cover rounded-lg shadow-sm">
                                        @endif
                                        
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600 mb-1">📂 {{ $item->product->category->name }}</p>
                                            <p class="text-xs text-gray-500">SKU: {{ $item->product->sku }}</p>
                                            <div class="mt-2">
                                                <span class="text-sm text-gray-500">Stock disponible: </span>
                                                <span class="text-sm font-medium {{ $item->product->stock > 10 ? 'text-green-600' : ($item->product->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ $item->product->stock }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-4">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-3">
                                                @csrf
                                                @method('PATCH')
                                                <label for="quantity_{{ $item->id }}" class="text-sm font-medium text-gray-700">Cantidad:</label>
                                                <input type="number" name="quantity" id="quantity_{{ $item->id }}" 
                                                       value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" 
                                                       class="w-20 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-center">
                                                <button type="submit" class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-md hover:bg-indigo-200 text-sm font-medium">
                                                    ✏️ Actualizar
                                                </button>
                                            </form>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-xl font-bold text-gray-900">${{ number_format($item->total, 2) }}</p>
                                            <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} c/u</p>
                                        </div>

                                        <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-100 text-red-700 px-3 py-2 rounded-md hover:bg-red-200 text-sm font-medium flex items-center"
                                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @endforeach

                                <div class="mt-4">
                                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                            Vaciar carrito
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Resumen del pedido -->
                            <div class="lg:col-span-1">
                                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-6 rounded-lg border border-indigo-200">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                        <span class="mr-2">📋</span>
                                        Resumen del pedido
                                    </h3>
                                    
                                    <div class="space-y-3 mb-6">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Subtotal:</span>
                                            <span class="font-semibold text-gray-900">${{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">IVA (16%):</span>
                                            <span class="font-semibold text-gray-900">${{ number_format($tax, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Envío:</span>
                                            <span class="font-semibold {{ $shipping > 0 ? 'text-gray-900' : 'text-green-600' }}">
                                                @if($shipping > 0)
                                                    ${{ number_format($shipping, 2) }}
                                                @else
                                                    🎉 Gratis
                                                @endif
                                            </span>
                                        </div>
                                        <div class="border-t border-gray-300 pt-3">
                                            <div class="flex justify-between items-center text-xl font-bold text-gray-900">
                                                <span>Total:</span>
                                                <span class="text-indigo-600">${{ number_format($total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($shipping > 0)
                                        <div class="bg-yellow-100 border border-yellow-300 rounded-md p-3 mb-4">
                                            <p class="text-sm text-yellow-800 flex items-center">
                                                <span class="mr-2">💡</span>
                                                ¡Agrega ${{ number_format(1000 - $subtotal, 2) }} más para envío gratis!
                                            </p>
                                        </div>
                                    @else
                                        <div class="bg-green-100 border border-green-300 rounded-md p-3 mb-4">
                                            <p class="text-sm text-green-800 flex items-center">
                                                <span class="mr-2">🎉</span>
                                                ¡Tienes envío gratis!
                                            </p>
                                        </div>
                                    @endif

                                    <div class="space-y-3">
                                        <a href="{{ route('orders.checkout') }}" 
                                           class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 font-medium text-center block flex items-center justify-center">
                                            <span class="mr-2">💳</span>
                                            Proceder al pago
                                        </a>
                                        
                                        <a href="{{ route('products.index') }}" 
                                           class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-200 font-medium text-center block flex items-center justify-center">
                                            <span class="mr-2">🛍️</span>
                                            Continuar comprando
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg mb-4">Tu carrito está vacío</p>
                            <a href="{{ route('products.index') }}" 
                               class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-medium">
                                Continuar comprando
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
