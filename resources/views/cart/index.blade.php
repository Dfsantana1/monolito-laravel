<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Mi Tienda</title>
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
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900">Mis Órdenes</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-gray-900">Cerrar Sesión</button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Carrito de Compras</h2>

                    @if($cartItems->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Items del carrito -->
                            <div class="lg:col-span-2">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4 py-4 border-b border-gray-200">
                                        @if(!empty($item->product->images) && is_array($item->product->images))
                                            <img src="{{ $item->product->images[0] }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-20 h-20 object-cover rounded-lg"
                                                 onerror="this.src='https://via.placeholder.com/100x100?text={{ urlencode($item->product->name) }}'">
                                        @else
                                            <img src="https://via.placeholder.com/100x100?text={{ urlencode($item->product->name) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="w-20 h-20 object-cover rounded-lg">
                                        @endif
                                        
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ $item->product->category->name }}</p>
                                            <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                        </div>

                                        <div class="flex items-center space-x-4">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <label for="quantity_{{ $item->id }}" class="text-sm font-medium text-gray-700">Cantidad:</label>
                                                <input type="number" name="quantity" id="quantity_{{ $item->id }}" 
                                                       value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" 
                                                       class="w-16 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                                    Actualizar
                                                </button>
                                            </form>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900">${{ number_format($item->total, 2) }}</p>
                                            <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} c/u</p>
                                        </div>

                                        <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                Eliminar
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
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h3>
                                    
                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Subtotal:</span>
                                            <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">IVA (16%):</span>
                                            <span class="font-medium">${{ number_format($tax, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Envío:</span>
                                            <span class="font-medium">
                                                @if($shipping > 0)
                                                    ${{ number_format($shipping, 2) }}
                                                @else
                                                    Gratis
                                                @endif
                                            </span>
                                        </div>
                                        <div class="border-t pt-2">
                                            <div class="flex justify-between text-lg font-bold">
                                                <span>Total:</span>
                                                <span>${{ number_format($total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($shipping > 0)
                                        <p class="text-sm text-gray-600 mb-4">
                                            ¡Agrega ${{ number_format(1000 - $subtotal, 2) }} más para envío gratis!
                                        </p>
                                    @endif

                                    <a href="{{ route('orders.checkout') }}" 
                                       class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 font-medium text-center block">
                                        Proceder al pago
                                    </a>
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
