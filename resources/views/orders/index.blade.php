<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Ecommerce</title>
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
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                        <i class="fas fa-shopping-cart mr-1"></i>Carrito
                        @if(auth()->check() && auth()->user()->cartItems->count() > 0)
                            <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full ml-1">{{ auth()->user()->cartItems->count() }}</span>
                        @endif
                    </a>
                    <a href="{{ route('orders.index') }}" class="text-blue-600 font-medium flex items-center">
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-receipt mr-2 text-blue-600"></i>Mis Pedidos
                </h1>
                <a href="{{ route('home') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i>Continuar Comprando
                </a>
            </div>

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

            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all duration-300">
                            <!-- Header del pedido -->
                            <div class="px-6 py-4 border-b border-gray-200">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="mb-2 md:mb-0">
                                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                            <i class="fas fa-hashtag text-gray-500 mr-2"></i>Pedido #{{ $order->order_number }}
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col md:items-end">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mb-2
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @endif">
                                            @switch($order->status)
                                                @case('pending') Pendiente @break
                                                @case('processing') Procesando @break
                                                @case('shipped') Enviado @break
                                                @case('delivered') Entregado @break
                                                @case('cancelled') Cancelado @break
                                            @endswitch
                                        </span>
                                        <div class="text-2xl font-bold text-green-600">
                                            ${{ number_format($order->total, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenido del pedido -->
                            <div class="p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <!-- Productos -->
                                    <div class="lg:col-span-2">
                                        <h4 class="text-sm font-medium text-gray-500 mb-4 flex items-center">
                                            <i class="fas fa-box mr-2"></i>Productos ({{ $order->orderItems->count() }})
                                        </h4>
                                        
                                        <div class="space-y-4">
                                            @foreach($order->orderItems as $item)
                                                <div class="flex items-center space-x-4 py-3 border-b border-gray-100 last:border-b-0">
                                                    <div class="flex-shrink-0">
                                                        @if($item->product && $item->product->images)
                                                            <img src="{{ $item->product->images[0] }}" 
                                                                 alt="{{ $item->product_name }}" 
                                                                 class="w-16 h-16 object-cover rounded-lg">
                                                        @else
                                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                <i class="fas fa-image text-gray-400"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h5 class="text-sm font-medium text-gray-900 truncate">{{ $item->product_name }}</h5>
                                                        <p class="text-xs text-gray-500">SKU: {{ $item->product_sku }}</p>
                                                    </div>
                                                    <div class="flex items-center space-x-4">
                                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                                                            {{ $item->quantity }}
                                                        </span>
                                                        <span class="text-sm font-semibold text-gray-900">
                                                            ${{ number_format($item->total, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Resumen -->
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <h4 class="text-sm font-medium text-gray-500 mb-4 flex items-center">
                                            <i class="fas fa-info-circle mr-2"></i>Resumen
                                        </h4>
                                        
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Subtotal:</span>
                                                <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                                            </div>
                                            
                                            @if($order->tax > 0)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Impuestos:</span>
                                                    <span class="font-medium">${{ number_format($order->tax, 2) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($order->shipping > 0)
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Envío:</span>
                                                    <span class="font-medium">${{ number_format($order->shipping, 2) }}</span>
                                                </div>
                                            @else
                                                <div class="flex justify-between text-green-600">
                                                    <span class="flex items-center">
                                                        <i class="fas fa-gift mr-1"></i>Envío:
                                                    </span>
                                                    <span class="font-medium">GRATIS</span>
                                                </div>
                                            @endif
                                            
                                            <hr class="my-3">
                                            <div class="flex justify-between text-base font-semibold">
                                                <span>Total:</span>
                                                <span class="text-green-600">${{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 space-y-2">
                                            <a href="{{ route('orders.show', $order) }}" 
                                               class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 text-sm font-medium flex items-center justify-center">
                                                <i class="fas fa-eye mr-1"></i>Ver Detalles
                                            </a>
                                            
                                            @if($order->status == 'pending')
                                                <a href="{{ route('orders.show', $order) }}" 
                                                   class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-medium flex items-center justify-center">
                                                    <i class="fas fa-credit-card mr-1"></i>Pagar Ahora
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                @if($orders->hasPages())
                    <div class="flex justify-center mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- Estado vacío -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📋</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No tienes pedidos aún</h3>
                    <p class="text-gray-500 mb-6">Comienza a comprar y tus pedidos aparecerán aquí</p>
                    <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 inline-flex items-center">
                        <i class="fas fa-shopping-bag mr-2"></i>Explorar Productos
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
