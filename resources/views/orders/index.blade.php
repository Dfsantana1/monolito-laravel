<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Ecommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .order-card {
            transition: transform 0.2s ease-in-out;
            border-left: 4px solid #007bff;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }
        .order-item {
            border-bottom: 1px solid #f8f9fa;
            padding: 0.75rem 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
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
                        <a class="nav-link active" href="{{ route('orders.index') }}">
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
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-receipt text-primary me-2"></i>Mis Pedidos
                    </h1>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Continuar Comprando
                    </a>
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

                @if($orders->count() > 0)
                    <div class="row">
                        @foreach($orders as $order)
                            <div class="col-12 mb-4">
                                <div class="card order-card">
                                    <div class="card-header bg-white">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h5 class="mb-1">
                                                    <i class="fas fa-hashtag text-muted me-2"></i>Pedido #{{ $order->order_number }}
                                                </h5>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-md-6 text-md-end">
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
                                                    <strong class="h5 text-success">${{ number_format($order->total, 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="text-muted mb-3">
                                                    <i class="fas fa-box me-2"></i>Productos ({{ $order->orderItems->count() }})
                                                </h6>
                                                
                                                @foreach($order->orderItems as $item)
                                                    <div class="order-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-2">
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
                                                            <div class="col-6">
                                                                <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                                <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                                            </div>
                                                            <div class="col-2 text-center">
                                                                <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                                            </div>
                                                            <div class="col-2 text-end">
                                                                <strong>${{ number_format($item->total, 2) }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="bg-light p-3 rounded">
                                                    <h6 class="text-muted mb-3">
                                                        <i class="fas fa-info-circle me-2"></i>Resumen
                                                    </h6>
                                                    
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
                                                    <div class="d-flex justify-content-between fw-bold">
                                                        <span>Total:</span>
                                                        <span class="text-success">${{ number_format($order->total, 2) }}</span>
                                                    </div>
                                                    
                                                    <div class="mt-3">
                                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm w-100">
                                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                                        </a>
                                                    </div>
                                                    
                                                    @if($order->status == 'pending')
                                                        <div class="mt-2">
                                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-sm w-100">
                                                                <i class="fas fa-credit-card me-1"></i>Pagar Ahora
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="fas fa-receipt"></i>
                        <h3>No tienes pedidos aún</h3>
                        <p class="lead">Comienza a comprar y tus pedidos aparecerán aquí</p>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Explorar Productos
                        </a>
                    </div>
                @endif
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
