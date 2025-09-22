<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido #{{ $order->order_number }} - Ecommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .order-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .status-badge {
            font-size: 1rem;
            padding: 0.75rem 1.5rem;
        }
        .product-card {
            transition: transform 0.2s ease-in-out;
            border-left: 4px solid #007bff;
        }
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }
        .address-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
        }
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.75rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #007bff;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #007bff;
        }
        .timeline-item.completed::before {
            background: #28a745;
            box-shadow: 0 0 0 2px #28a745;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-shopping-bag me-2"></i>Ecommerce
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-box me-1"></i>Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.featured') }}">
                            <i class="fas fa-star me-1"></i>Destacados
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart me-1"></i>Carrito
                            @if(auth()->check() && auth()->user()->cartItems->count() > 0)
                                <span class="badge bg-warning text-dark">{{ auth()->user()->cartItems->count() }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-receipt me-1"></i>Mis Pedidos
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-edit me-2"></i>Perfil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Order Header -->
        <div class="order-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h2 mb-2">
                        <i class="fas fa-receipt me-2"></i>Pedido #{{ $order->order_number }}
                    </h1>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-calendar me-2"></i>
                        Realizado el {{ $order->created_at->format('d/m/Y') }} a las {{ $order->created_at->format('H:i') }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge status-badge 
                        @if($order->status == 'pending') bg-warning
                        @elseif($order->status == 'processing') bg-info
                        @elseif($order->status == 'shipped') bg-primary
                        @elseif($order->status == 'delivered') bg-success
                        @elseif($order->status == 'cancelled') bg-danger
                        @endif">
                        @switch($order->status)
                            @case('pending') Pendiente @break
                            @case('processing') Procesando @break
                            @case('shipped') Enviado @break
                            @case('delivered') Entregado @break
                            @case('cancelled') Cancelado @break
                        @endswitch
                    </span>
                    <div class="mt-2">
                        <h3 class="mb-0">${{ number_format($order->total, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Order Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-box me-2"></i>Productos ({{ $order->orderItems->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($order->orderItems as $item)
                            <div class="card product-card m-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            @if($item->product && $item->product->images)
                                                <img src="{{ $item->product->images[0] }}" 
                                                     alt="{{ $item->product_name }}" 
                                                     class="product-image">
                                            @else
                                                <div class="product-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-1">{{ $item->product_name }}</h6>
                                            <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                            @if($item->product)
                                                <div class="mt-1">
                                                    <a href="{{ route('products.show', $item->product) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>Ver Producto
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <span class="badge bg-primary fs-6">{{ $item->quantity }}</span>
                                            <div class="small text-muted mt-1">cantidad</div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <div class="h6 mb-0">${{ number_format($item->price, 2) }}</div>
                                            <div class="small text-muted">precio unitario</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary & Actions -->
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Resumen del Pedido
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        
                        @if($order->tax > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Impuestos:</span>
                                <span>${{ number_format($order->tax, 2) }}</span>
                            </div>
                        @endif
                        
                        @if($order->shipping > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Envío:</span>
                                <span>${{ number_format($order->shipping, 2) }}</span>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span><i class="fas fa-gift me-1"></i>Envío:</span>
                                <span>GRATIS</span>
                            </div>
                        @endif
                        
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total:</span>
                            <span class="text-success">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>Estado del Pago
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Estado:</span>
                            <span class="badge 
                                @if($order->payment_status == 'completed') bg-success
                                @elseif($order->payment_status == 'pending') bg-warning
                                @elseif($order->payment_status == 'failed') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        
                        @if($order->payment_method)
                            <div class="d-flex justify-content-between mt-2">
                                <span>Método:</span>
                                <span>{{ ucfirst($order->payment_method) }}</span>
                            </div>
                        @endif
                        
                        @if($order->status == 'pending' && $order->payment_status != 'completed')
                            <div class="mt-3">
                                <a href="{{ route('payments.show', $order) }}" class="btn btn-primary w-100">
                                    <i class="fas fa-credit-card me-1"></i>Pagar Ahora
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Addresses -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt me-2"></i>Direcciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="address-card mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-home me-1"></i>Dirección de Facturación
                            </h6>
                            <div class="small">
                                {{ $order->billing_address['name'] ?? 'N/A' }}<br>
                                {{ $order->billing_address['address'] ?? 'N/A' }}<br>
                                {{ $order->billing_address['city'] ?? 'N/A' }}, {{ $order->billing_address['state'] ?? 'N/A' }} {{ $order->billing_address['zip'] ?? 'N/A' }}<br>
                                {{ $order->billing_address['country'] ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <div class="address-card">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-truck me-1"></i>Dirección de Envío
                            </h6>
                            <div class="small">
                                {{ $order->shipping_address['name'] ?? 'N/A' }}<br>
                                {{ $order->shipping_address['address'] ?? 'N/A' }}<br>
                                {{ $order->shipping_address['city'] ?? 'N/A' }}, {{ $order->shipping_address['state'] ?? 'N/A' }} {{ $order->shipping_address['zip'] ?? 'N/A' }}<br>
                                {{ $order->shipping_address['country'] ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Historial del Pedido
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item completed">
                                <h6 class="mb-1">Pedido Realizado</h6>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            
                            @if($order->status != 'pending')
                                <div class="timeline-item completed">
                                    <h6 class="mb-1">Procesando</h6>
                                    <small class="text-muted">El pedido está siendo preparado</small>
                                </div>
                            @endif
                            
                            @if($order->status == 'shipped' || $order->status == 'delivered')
                                <div class="timeline-item completed">
                                    <h6 class="mb-1">Enviado</h6>
                                    <small class="text-muted">El pedido ha sido enviado</small>
                                </div>
                            @endif
                            
                            @if($order->status == 'delivered')
                                <div class="timeline-item completed">
                                    <h6 class="mb-1">Entregado</h6>
                                    <small class="text-muted">El pedido ha sido entregado</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-arrow-left me-1"></i>Volver a Mis Pedidos
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-1"></i>Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-shopping-bag me-2"></i>Ecommerce</h5>
                    <p class="mb-0">Tu tienda online de confianza</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; {{ date('Y') }} Ecommerce. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
