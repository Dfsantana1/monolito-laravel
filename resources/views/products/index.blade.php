<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos - Ecommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.2s ease-in-out;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
        }
        .featured-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
        }
        .filter-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
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
                        <a class="nav-link active" href="{{ route('home') }}">
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
                    @if(auth()->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                <i class="fas fa-shopping-cart me-1"></i>Carrito
                                @if(auth()->user()->cartItems->count() > 0)
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
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <!-- Mensajes de éxito/error -->
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

        <!-- Filtros -->
        <div class="card filter-section">
            <div class="card-body">
                <h2 class="h4 mb-4">
                    <i class="fas fa-search me-2"></i>Buscar Productos
                </h2>
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Buscar por nombre</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   placeholder="Ej: iPhone, camiseta..."
                                   class="form-control">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="category" class="form-label">Categoría</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="min_price" class="form-label">Precio mínimo</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" 
                                   placeholder="0" min="0" step="0.01" class="form-control">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="max_price" class="form-label">Precio máximo</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" 
                                   placeholder="1000" min="0" step="0.01" class="form-control">
                        </div>
                        
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Filtrar
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Limpiar
                                </a>
                                <a href="{{ route('products.featured') }}" class="btn btn-warning">
                                    <i class="fas fa-star me-1"></i>Destacados
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contador de resultados -->
        @if($products->count() > 0)
            <div class="mb-3">
                <p class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Mostrando {{ $products->count() }} de {{ $products->total() }} productos
                </p>
            </div>
        @endif

        <!-- Grid de productos -->
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            @if(!empty($product->images) && is_array($product->images))
                                <img src="{{ $product->images[0] }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-image card-img-top"
                                     onerror="this.src='https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}'">
                            @else
                                <img src="https://via.placeholder.com/300x300?text={{ urlencode($product->name) }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-image card-img-top">
                            @endif
                            
                            @if($product->is_featured)
                                <span class="featured-badge badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>Destacado
                                </span>
                            @endif
                            
                            @if($product->sale_price)
                                <span class="discount-badge badge bg-danger">
                                    -{{ $product->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-folder me-1"></i>{{ $product->category->name }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                @if($product->sale_price)
                                    <span class="h5 text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="h5 text-dark fw-bold">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <p class="card-text text-muted small mb-3">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-box me-1"></i>
                                    <span class="{{ $product->stock > 10 ? 'text-success' : ($product->stock > 0 ? 'text-warning' : 'text-danger') }}">
                                        {{ $product->stock > 0 ? $product->stock . ' disponibles' : 'Sin stock' }}
                                    </span>
                                </small>
                            </div>
                            
                            <div class="mt-auto">
                                @if(auth()->check())
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-2">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                                        <button type="submit" 
                                                class="btn btn-primary w-100 {{ $product->stock == 0 ? 'disabled' : '' }}"
                                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-shopping-cart me-1"></i>
                                            {{ $product->stock == 0 ? 'Sin stock' : 'Agregar al carrito' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-secondary w-100 mb-2">
                                        <i class="fas fa-lock me-1"></i>Iniciar sesión para comprar
                                    </a>
                                @endif
                                
                                <a href="{{ route('products.show', $product) }}" 
                                   class="btn btn-outline-primary w-100">
                                    <i class="fas fa-eye me-1"></i>Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No se encontraron productos</h3>
                        <p class="lead">Intenta ajustar tus filtros de búsqueda</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-list me-2"></i>Ver todos los productos
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
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