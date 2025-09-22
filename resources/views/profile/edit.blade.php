<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - Ecommerce</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-card {
            transition: transform 0.2s ease-in-out;
            border-left: 4px solid #007bff;
        }
        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .profile-section {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
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
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item active" href="{{ route('profile.edit') }}">
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
                        <i class="fas fa-user text-primary me-2"></i>Perfil de Usuario
                    </h1>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i>Volver al Inicio
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

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card profile-card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-edit me-2"></i>Información del Perfil
                                </h5>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        <div class="card profile-card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-lock me-2"></i>Cambiar Contraseña
                                </h5>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <div class="card profile-card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-trash me-2"></i>Eliminar Cuenta
                                </h5>
                            </div>
                            <div class="card-body">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card profile-section">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-user-circle text-primary" style="font-size: 4rem;"></i>
                                </div>
                                <h4>{{ auth()->user()->name }}</h4>
                                <p class="text-muted">{{ auth()->user()->email }}</p>
                                <small class="text-muted">
                                    Miembro desde {{ auth()->user()->created_at->format('M Y') }}
                                </small>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Estadísticas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <h5 class="text-primary">{{ auth()->user()->orders->count() }}</h5>
                                            <small class="text-muted">Pedidos</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="text-success">{{ auth()->user()->cartItems->count() }}</h5>
                                        <small class="text-muted">En Carrito</small>
                                    </div>
                                </div>
                            </div>
                        </div>
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
