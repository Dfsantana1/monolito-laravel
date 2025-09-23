# Microservices E-commerce Platform

Esta es una plataforma de e-commerce construida con microservicios usando Laravel Lumen, Node.js, React y Docker.

## 🏗️ Arquitectura

### Servicios

1. **Frontend (React)** - Puerto 3000
   - Interfaz de usuario construida con React y Material-UI
   - Proxy de API hacia el API Gateway

2. **API Gateway (Kong)** - Puerto 8000
   - Punto de entrada único para todos los microservicios
   - Enrutamiento, autenticación y rate limiting

3. **User Service (Laravel Lumen)** - Puerto 8001
   - Gestión de usuarios y autenticación JWT
   - Base de datos: MySQL

4. **Product Service (Laravel Lumen)** - Puerto 8002
   - Catálogo de productos y categorías
   - Base de datos: MySQL

5. **Cart Service (Node.js)** - Puerto 8003
   - Gestión del carrito de compras
   - Cache: Redis

6. **Order Service (Laravel Lumen)** - Puerto 8004
   - Gestión de pedidos
   - Base de datos: MySQL

7. **Payment Service (Laravel Lumen)** - Puerto 8005
   - Procesamiento de pagos
   - Base de datos: MySQL

8. **Notification Service (Node.js)** - Puerto 8006
   - Notificaciones y mensajería
   - Base de datos: MongoDB
   - Message Broker: RabbitMQ

### Infraestructura

- **MySQL 8.0** - Base de datos principal
- **Redis 7** - Cache y sesiones
- **MongoDB 6** - Base de datos de notificaciones
- **RabbitMQ 3** - Message broker

## 🚀 Inicio Rápido

### Prerrequisitos

- Docker y Docker Compose instalados
- Git

### Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd monolito-laravel/microservices
   ```

2. **Ejecutar el script de inicio**
   ```bash
   chmod +x start.sh
   ./start.sh
   ```

3. **Verificar que todos los servicios estén funcionando**
   ```bash
   docker-compose ps
   ```

### Acceso a los Servicios

- **Frontend**: http://localhost:3000
- **API Gateway**: http://localhost:8000
- **User Service**: http://localhost:8001
- **Product Service**: http://localhost:8002
- **Cart Service**: http://localhost:8003
- **Order Service**: http://localhost:8004
- **Payment Service**: http://localhost:8005
- **Notification Service**: http://localhost:8006

### Herramientas de Administración

- **RabbitMQ Management**: http://localhost:15672 (guest/guest)
- **MySQL**: localhost:3306 (root/password)
- **Redis**: localhost:6379
- **MongoDB**: localhost:27017

## 🔧 Comandos Útiles

### Gestión de Servicios

```bash
# Iniciar todos los servicios
docker-compose up -d

# Iniciar un servicio específico
docker-compose up -d user-service

# Ver logs de un servicio
docker-compose logs -f user-service

# Ver logs de todos los servicios
docker-compose logs -f

# Parar todos los servicios
docker-compose down

# Parar y eliminar volúmenes
docker-compose down -v
```

### Desarrollo

```bash
# Reconstruir un servicio
docker-compose up --build -d user-service

# Ejecutar comandos dentro de un contenedor
docker-compose exec user-service php artisan migrate
docker-compose exec product-service php artisan migrate

# Acceder al shell de un contenedor
docker-compose exec user-service sh
```

### Base de Datos

```bash
# Ejecutar migraciones
docker-compose exec user-service php artisan migrate
docker-compose exec product-service php artisan migrate
docker-compose exec order-service php artisan migrate
docker-compose exec payment-service php artisan migrate

# Ejecutar seeders
docker-compose exec product-service php artisan db:seed
```

## 📁 Estructura del Proyecto

```
microservices/
├── api-gateway/           # Kong API Gateway
├── frontend/              # React Frontend
├── services/
│   ├── user-service/      # User Management (Laravel Lumen)
│   ├── product-service/   # Product Catalog (Laravel Lumen)
│   ├── cart-service/      # Shopping Cart (Node.js)
│   ├── order-service/     # Order Management (Laravel Lumen)
│   ├── payment-service/   # Payment Processing (Laravel Lumen)
│   └── notification-service/ # Notifications (Node.js)
├── infrastructure/        # Database init scripts
├── shared/               # Shared utilities
├── docker-compose.yml    # Docker Compose configuration
└── start.sh             # Startup script
```

## 🔐 Autenticación

El sistema usa JWT (JSON Web Tokens) para la autenticación:

1. **Registro**: `POST /auth/register`
2. **Login**: `POST /auth/login`
3. **Perfil**: `GET /auth/me` (requiere token)
4. **Logout**: `POST /auth/logout` (requiere token)

## 🛒 API Endpoints

### User Service (8001)
- `POST /auth/register` - Registrar usuario
- `POST /auth/login` - Iniciar sesión
- `GET /auth/me` - Obtener perfil
- `GET /users` - Listar usuarios
- `GET /users/{id}` - Obtener usuario

### Product Service (8002)
- `GET /products` - Listar productos
- `GET /products/{id}` - Obtener producto
- `GET /products/featured` - Productos destacados
- `GET /categories` - Listar categorías

### Cart Service (8003)
- `GET /cart/{userId}` - Obtener carrito
- `POST /cart/{userId}/items` - Agregar item
- `PUT /cart/{userId}/items/{itemId}` - Actualizar item
- `DELETE /cart/{userId}/items/{itemId}` - Eliminar item

## 🐛 Solución de Problemas

### Servicios no inician

1. Verificar que Docker esté ejecutándose
2. Verificar puertos disponibles
3. Revisar logs: `docker-compose logs [service-name]`

### Problemas de base de datos

1. Verificar que MySQL esté ejecutándose
2. Ejecutar migraciones manualmente
3. Verificar configuración de conexión

### Problemas de red

1. Verificar que la red `microservices-network` exista
2. Reiniciar servicios: `docker-compose restart`

## 📝 Notas de Desarrollo

- Los servicios Laravel usan Lumen (versión ligera de Laravel)
- Redis se usa para cache y sesiones
- MongoDB se usa para notificaciones
- RabbitMQ se usa para mensajería asíncrona
- Kong se usa como API Gateway

## 🤝 Contribución

1. Fork el proyecto
2. Crear una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abrir un Pull Request
