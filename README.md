# 🛒 E-Commerce Monolito Laravel

Una aplicación de comercio electrónico desarrollada con Laravel 12, implementando una arquitectura monolítica moderna con características avanzadas de e-commerce.

## 📋 Tabla de Contenidos

- [Descripción del Proyecto](#-descripción-del-proyecto)
- [Arquitectura del Sistema](#-arquitectura-del-sistema)
- [Características Principales](#-características-principales)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Instalación](#-instalación)
- [Ventajas de la Arquitectura Monolítica](#-ventajas-de-la-arquitectura-monolítica)
- [Desventajas y Consideraciones](#-desventajas-y-consideraciones)
- [Desarrollo](#-desarrollo)

## 🎯 Descripción del Proyecto

Este proyecto es una aplicación de e-commerce completa desarrollada con Laravel 12, que implementa una arquitectura monolítica. La aplicación permite a los usuarios navegar por un catálogo de productos, gestionar un carrito de compras, realizar pedidos y procesar pagos de forma segura.

### Funcionalidades Principales

- **Catálogo de Productos**: Navegación y búsqueda de productos con filtros avanzados
- **Gestión de Carrito**: Agregar, modificar y eliminar productos del carrito
- **Sistema de Órdenes**: Proceso completo de checkout y seguimiento de pedidos
- **Autenticación de Usuarios**: Sistema de registro y login seguro
- **Gestión de Perfiles**: Actualización de información personal
- **Sistema de Pagos**: Integración con múltiples métodos de pago
- **Panel de Administración**: Gestión de productos y categorías

## 🏗️ Arquitectura del Sistema

### Enfoque Arquitectónico: Monolito Modular

La aplicación implementa una **arquitectura monolítica modular** que combina las ventajas de simplicidad del monolito con la organización de microservicios:

```
┌─────────────────────────────────────────────────────────────┐
│                    CAPA DE PRESENTACIÓN                     │
├─────────────────────────────────────────────────────────────┤
│  Views (Blade)  │  Components  │  Assets (CSS/JS)          │
├─────────────────────────────────────────────────────────────┤
│                    CAPA DE CONTROLADORES                    │
├─────────────────────────────────────────────────────────────┤
│ ProductController │ CartController │ OrderController        │
│ PaymentController │ ProfileController                       │
├─────────────────────────────────────────────────────────────┤
│                    CAPA DE LÓGICA DE NEGOCIO                │
├─────────────────────────────────────────────────────────────┤
│  Models (Eloquent)  │  Services  │  Requests (Validation)  │
├─────────────────────────────────────────────────────────────┤
│                    CAPA DE DATOS                            │
├─────────────────────────────────────────────────────────────┤
│  Migrations  │  Seeders  │  SQLite Database                │
└─────────────────────────────────────────────────────────────┘
```

### Principios Arquitectónicos

1. **Separación de Responsabilidades**: Cada capa tiene una responsabilidad específica
2. **Inversión de Dependencias**: Los controladores dependen de abstracciones (interfaces)
3. **Principio DRY**: Reutilización de código a través de componentes y traits
4. **Patrón MVC**: Modelo-Vista-Controlador para organización clara
5. **Middleware Pattern**: Procesamiento de requests a través de middleware

## ✨ Características Principales

### 🛍️ Gestión de Productos
- Catálogo con filtros por categoría, precio y búsqueda
- Productos destacados
- Gestión de inventario (stock)
- Precios con descuentos
- Múltiples imágenes por producto
- SKU único para cada producto

### 🛒 Carrito de Compras
- Agregar/eliminar productos
- Actualizar cantidades
- Cálculo automático de totales
- Persistencia de sesión
- Validación de stock disponible

### 📦 Sistema de Órdenes
- Proceso de checkout completo
- Múltiples direcciones (facturación y envío)
- Cálculo automático de impuestos y envío
- Números de orden únicos
- Estados de orden (pending, processing, shipped, delivered, cancelled)

### 💳 Sistema de Pagos
- Múltiples métodos de pago (tarjeta de crédito, débito, PayPal)
- Validación de datos de pago
- Confirmación de transacciones
- Manejo de errores de pago

### 👤 Gestión de Usuarios
- Registro y autenticación
- Perfiles de usuario editables
- Historial de órdenes
- Seguridad con middleware

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 12**: Framework PHP moderno
- **PHP 8.2+**: Lenguaje de programación
- **Eloquent ORM**: Mapeo objeto-relacional
- **SQLite**: Base de datos ligera
- **Laravel Breeze**: Autenticación y scaffolding

### Frontend
- **Blade Templates**: Motor de plantillas de Laravel
- **Tailwind CSS 3.1**: Framework de CSS utilitario
- **Alpine.js 3.4**: Framework JavaScript ligero
- **Vite 7.0**: Herramienta de build moderna

### Herramientas de Desarrollo
- **Laravel Pint**: Code style fixer
- **PHPUnit 11.5**: Testing framework
- **Laravel Pail**: Log viewer
- **Concurrently**: Ejecución paralela de tareas

## 📁 Estructura del Proyecto

```
monolito-laravel/
├── app/
│   ├── Http/Controllers/          # Controladores de la aplicación
│   │   ├── ProductController.php  # Gestión de productos
│   │   ├── CartController.php     # Gestión del carrito
│   │   ├── OrderController.php    # Gestión de órdenes
│   │   ├── PaymentController.php  # Procesamiento de pagos
│   │   └── ProfileController.php  # Gestión de perfiles
│   ├── Models/                    # Modelos Eloquent
│   │   ├── Product.php           # Modelo de productos
│   │   ├── Order.php             # Modelo de órdenes
│   │   ├── CartItem.php          # Modelo de items del carrito
│   │   ├── Category.php          # Modelo de categorías
│   │   └── User.php              # Modelo de usuarios
│   └── View/Components/          # Componentes Blade reutilizables
├── database/
│   ├── migrations/               # Migraciones de base de datos
│   ├── seeders/                  # Seeders para datos de prueba
│   └── database.sqlite          # Base de datos SQLite
├── resources/
│   ├── views/                    # Vistas Blade
│   │   ├── products/            # Vistas de productos
│   │   ├── cart/                # Vistas del carrito
│   │   ├── orders/              # Vistas de órdenes
│   │   ├── auth/                # Vistas de autenticación
│   │   └── layouts/             # Layouts base
│   ├── css/                     # Estilos CSS
│   └── js/                      # JavaScript
├── routes/
│   ├── web.php                  # Rutas web
│   └── auth.php                 # Rutas de autenticación
└── tests/                       # Tests automatizados
```

## 🚀 Instalación

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- Node.js 18+ y npm
- SQLite (incluido con PHP)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd monolito-laravel
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar variables de entorno**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar base de datos**
```bash
# La base de datos SQLite se crea automáticamente
touch database/database.sqlite
```

6. **Ejecutar migraciones y seeders**
```bash
php artisan migrate --seed
```

7. **Compilar assets**
```bash
npm run build
```

## ⚙️ Configuración

### Variables de Entorno Importantes

```env
APP_NAME="E-Commerce Monolito"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database/database.sqlite

MAIL_MAILER=log
```

### Configuración de Pagos

Para configurar el sistema de pagos, actualiza las variables de entorno:

```env
PAYMENT_GATEWAY_URL=https://api.payment-provider.com
PAYMENT_GATEWAY_KEY=your_api_key
PAYMENT_GATEWAY_SECRET=your_secret_key
```

## 🎮 Uso

### Iniciar el Servidor de Desarrollo

```bash
# Opción 1: Comando de desarrollo completo
composer run dev

# Opción 2: Servidor Laravel + Vite por separado
php artisan serve
npm run dev
```

### Acceder a la Aplicación

- **URL Principal**: http://localhost:8000
- **Catálogo de Productos**: http://localhost:8000/products
- **Carrito**: http://localhost:8000/cart (requiere autenticación)
- **Órdenes**: http://localhost:8000/orders (requiere autenticación)

### Usuarios de Prueba

El seeder crea usuarios de prueba:
- **Email**: admin@example.com
- **Password**: password

## 🗄️ Base de Datos

### Entidades Principales

La aplicación utiliza las siguientes entidades principales:

- **Users**: Usuarios del sistema con autenticación
- **Categories**: Categorías de productos
- **Products**: Catálogo de productos con inventario
- **CartItems**: Items en el carrito de compras
- **Orders**: Órdenes de compra
- **OrderItems**: Items específicos de cada orden

### Relaciones

- Un usuario puede tener múltiples órdenes
- Un usuario puede tener múltiples items en el carrito
- Una categoría puede tener múltiples productos
- Un producto puede estar en múltiples carritos y órdenes
- Una orden puede tener múltiples items

### Migraciones

```bash
# Ejecutar migraciones
php artisan migrate

# Ver estado de migraciones
php artisan migrate:status

# Rollback de migraciones
php artisan migrate:rollback
```

## 🔌 API Endpoints

### Productos
```
GET  /products              # Listar productos
GET  /products/{id}         # Ver producto específico
GET  /products/featured     # Productos destacados
```

### Carrito (Autenticación requerida)
```
GET    /cart                # Ver carrito
POST   /cart/add/{product}  # Agregar producto
PATCH  /cart/update/{item}  # Actualizar cantidad
DELETE /cart/remove/{item}  # Eliminar producto
DELETE /cart/clear          # Limpiar carrito
```

### Órdenes (Autenticación requerida)
```
GET  /orders              # Listar órdenes del usuario
GET  /orders/checkout     # Formulario de checkout
POST /orders              # Crear nueva orden
GET  /orders/{id}         # Ver orden específica
```

### Pagos (Autenticación requerida)
```
GET  /orders/{order}/payment        # Formulario de pago
POST /orders/{order}/payment        # Procesar pago
GET  /orders/{order}/payment/success # Pago exitoso
GET  /orders/{order}/payment/failure # Pago fallido
```

## ✅ Ventajas de la Arquitectura Monolítica

### 🚀 Desarrollo y Despliegue
- **Simplicidad de Despliegue**: Un solo artefacto para desplegar
- **Desarrollo Rápido**: No hay latencia de red entre servicios
- **Debugging Fácil**: Todo el código en un solo lugar
- **Transacciones ACID**: Consistencia de datos garantizada

### 💰 Costos y Recursos
- **Menor Complejidad**: Menos infraestructura que gestionar
- **Costos Reducidos**: Un solo servidor de base de datos
- **Monitoreo Simple**: Una sola aplicación que supervisar
- **Backup Unificado**: Una sola base de datos que respaldar

### 🔧 Mantenimiento
- **Refactoring Global**: Cambios que afectan múltiples módulos son más fáciles
- **Testing Integral**: Tests de integración más simples
- **Consistencia de Código**: Un solo lenguaje y framework
- **Onboarding Rápido**: Los desarrolladores entienden la estructura más fácilmente

### 📈 Escalabilidad Vertical
- **Optimización de Recursos**: Mejor aprovechamiento del hardware
- **Cache Compartido**: Cache de aplicación más eficiente
- **Conexiones de BD**: Pool de conexiones optimizado

## ⚠️ Desventajas y Consideraciones

### 🔄 Escalabilidad Horizontal
- **Escalado Limitado**: Difícil escalar componentes individuales
- **Punto Único de Falla**: Si falla la aplicación, todo falla
- **Tecnología Única**: Limitado a un stack tecnológico

### 👥 Equipos de Desarrollo
- **Conflictos de Código**: Múltiples desarrolladores en el mismo código
- **Deployments Acoplados**: Cambios pequeños requieren deploy completo
- **Responsabilidades Difusas**: Límites de responsabilidad menos claros

### 🛠️ Mantenimiento a Largo Plazo
- **Deuda Técnica**: Puede volverse difícil de mantener con el tiempo
- **Tamaño del Código**: La base de código puede crecer significativamente
- **Testing Complejo**: Tests más lentos con el crecimiento de la aplicación

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter=ProductTest

# Con coverage
php artisan test --coverage
```

### Tipos de Tests

- **Unit Tests**: Tests de modelos y lógica de negocio
- **Feature Tests**: Tests de integración de funcionalidades
- **Browser Tests**: Tests end-to-end (si se implementan)

## 🚀 Desarrollo

### Comandos Útiles

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generar recursos
php artisan make:controller ProductController
php artisan make:model Product -m
php artisan make:migration create_products_table

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Estándares de Código

```bash
# Formatear código
./vendor/bin/pint

# Verificar estilo
./vendor/bin/pint --test
```

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

## 📞 Contacto

**Desarrollador**: Diego
**Proyecto**: E-Commerce Monolito Laravel
**Arquitectura**: Monolito Modular con Laravel 12

---

*Este README documenta la arquitectura y funcionalidades de una aplicación de e-commerce desarrollada con Laravel, implementando un enfoque monolítico moderno y modular.*