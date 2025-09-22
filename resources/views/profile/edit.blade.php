<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - Ecommerce</title>
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
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-gray-900 flex items-center">
                        <i class="fas fa-receipt mr-1"></i>Mis Pedidos
                    </a>
                    <div class="relative group">
                        <button class="text-blue-600 font-medium flex items-center">
                            <i class="fas fa-user mr-1"></i>{{ auth()->user()->name }}
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-blue-600 bg-blue-50 font-medium">
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
                    <i class="fas fa-user mr-2 text-blue-600"></i>Perfil de Usuario
                </h1>
                <a href="{{ route('home') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i>Volver al Inicio
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Información del perfil -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-user-edit mr-2 text-blue-600"></i>Información del Perfil
                            </h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-lock mr-2 text-blue-600"></i>Cambiar Contraseña
                            </h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-trash mr-2 text-red-600"></i>Eliminar Cuenta
                            </h3>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

                <!-- Sidebar con información del usuario -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <div class="mb-4">
                                <i class="fas fa-user-circle text-blue-600 text-6xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                            <p class="text-gray-600 mt-1">{{ auth()->user()->email }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-calendar mr-1"></i>
                                Miembro desde {{ auth()->user()->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-chart-bar mr-2 text-blue-600"></i>Estadísticas
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div class="border-r border-gray-200">
                                    <div class="text-2xl font-bold text-blue-600">{{ auth()->user()->orders->count() }}</div>
                                    <div class="text-sm text-gray-600">Pedidos</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-600">{{ auth()->user()->cartItems->count() }}</div>
                                    <div class="text-sm text-gray-600">En Carrito</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-center">
                            <i class="fas fa-shopping-bag text-blue-600 text-3xl mb-3"></i>
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Ecommerce</h4>
                            <p class="text-sm text-gray-600">Tu tienda online de confianza</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
