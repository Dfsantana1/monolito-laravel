<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Mi Tienda</title>
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
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-gray-900">Carrito</a>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Formulario de checkout -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Información de Envío y Facturación</h2>
                        
                        <form method="POST" action="{{ route('orders.store') }}">
                            @csrf
                            
                            <!-- Información de facturación -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Facturación</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="billing_name" class="block text-sm font-medium text-gray-700">Nombre completo *</label>
                                        <input type="text" name="billing_name" id="billing_name" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="billing_email" class="block text-sm font-medium text-gray-700">Email *</label>
                                        <input type="email" name="billing_email" id="billing_email" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="billing_phone" class="block text-sm font-medium text-gray-700">Teléfono *</label>
                                        <input type="tel" name="billing_phone" id="billing_phone" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="billing_postal_code" class="block text-sm font-medium text-gray-700">Código postal *</label>
                                        <input type="text" name="billing_postal_code" id="billing_postal_code" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="billing_address" class="block text-sm font-medium text-gray-700">Dirección *</label>
                                        <textarea name="billing_address" id="billing_address" rows="2" required
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    </div>
                                    <div>
                                        <label for="billing_city" class="block text-sm font-medium text-gray-700">Ciudad *</label>
                                        <input type="text" name="billing_city" id="billing_city" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="billing_state" class="block text-sm font-medium text-gray-700">Estado *</label>
                                        <input type="text" name="billing_state" id="billing_state" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Información de envío -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Envío</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="shipping_name" class="block text-sm font-medium text-gray-700">Nombre completo *</label>
                                        <input type="text" name="shipping_name" id="shipping_name" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="shipping_phone" class="block text-sm font-medium text-gray-700">Teléfono *</label>
                                        <input type="tel" name="shipping_phone" id="shipping_phone" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="shipping_postal_code" class="block text-sm font-medium text-gray-700">Código postal *</label>
                                        <input type="text" name="shipping_postal_code" id="shipping_postal_code" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="shipping_city" class="block text-sm font-medium text-gray-700">Ciudad *</label>
                                        <input type="text" name="shipping_city" id="shipping_city" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label for="shipping_state" class="block text-sm font-medium text-gray-700">Estado *</label>
                                        <input type="text" name="shipping_state" id="shipping_state" required
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Dirección *</label>
                                        <textarea name="shipping_address" id="shipping_address" rows="2" required
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Método de pago -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Método de Pago</h3>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="credit_card" checked
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Tarjeta de Crédito</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="debit_card"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">Tarjeta de Débito</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="payment_method" value="paypal"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700">PayPal</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Notas adicionales -->
                            <div class="mb-8">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notas adicionales (opcional)</label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                          placeholder="Instrucciones especiales para la entrega..."></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 font-medium">
                                Confirmar Pedido
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Pedido</h3>
                        
                        <div class="space-y-4 mb-6">
                            @foreach($cartItems as $item)
                                <div class="flex items-center space-x-4">
                                    @if(!empty($item->product->images) && is_array($item->product->images))
                                        <img src="{{ $item->product->images[0] }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg"
                                             onerror="this.src='https://via.placeholder.com/64x64?text={{ urlencode($item->product->name) }}'">
                                    @else
                                        <img src="https://via.placeholder.com/64x64?text={{ urlencode($item->product->name) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-16 h-16 object-cover rounded-lg">
                                    @endif
                                    
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($item->total, 2) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4">
                            <div class="space-y-2">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
