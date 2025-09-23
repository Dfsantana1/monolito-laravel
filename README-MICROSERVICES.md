# Migración de Monolito Laravel a Microservicios

## 📋 Resumen del Proyecto

Este proyecto migra una aplicación de e-commerce monolítica en Laravel a una arquitectura de microservicios distribuida, desplegable en Kubernetes con Docker.

## 🏗️ Arquitectura Actual vs Nueva

### Monolito Original
- **Tecnología**: Laravel 12.0 + PHP 8.2
- **Base de datos**: SQLite
- **Frontend**: Blade templates + Tailwind CSS
- **Estructura**: Aplicación monolítica con todos los dominios acoplados

### Nueva Arquitectura de Microservicios
```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT (React SPA)                       │
└─────────────────────┬───────────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────────┐
│                    API GATEWAY (Kong)                           │
│  • Authentication & Authorization (JWT)                        │
│  • Rate Limiting                                                │
│  • Request Routing                                              │
│  • Load Balancing                                               │
└─────┬─────────┬─────────┬─────────┬─────────┬─────────┬─────────┘
      │         │         │         │         │         │
      ▼         ▼         ▼         ▼         ▼         ▼
┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐
│  USER   │ │PRODUCT  │ │  CART   │ │  ORDER  │ │ PAYMENT │ │NOTIFY   │
│ SERVICE │ │ SERVICE │ │ SERVICE │ │ SERVICE │ │ SERVICE │ │ SERVICE │
│         │ │         │ │         │ │         │ │         │ │         │
│ Laravel │ │ Laravel │ │ Node.js │ │ Laravel │ │ Node.js │ │ Node.js │
│ + MySQL │ │ + MySQL │ │ + Redis │ │ + MySQL │ │ + MySQL │ │+ MongoDB│
└─────────┘ └─────────┘ └─────────┘ └─────────┘ └─────────┘ └─────────┘
      │         │         │         │         │         │
      └─────────┼─────────┼─────────┼─────────┼─────────┘
                │         │         │         │
                ▼         ▼         ▼         ▼
        ┌─────────────────────────────────────────────┐
        │           MESSAGE BROKER                    │
        │         (RabbitMQ)                          │
        │  • Event-driven communication               │
        │  • Async processing                         │
        │  • Service decoupling                      │
        └─────────────────────────────────────────────┘
```

## 🎯 Servicios Identificados

### 1. **User Service** (Puerto: 8001)
- **Responsabilidad**: Autenticación, autorización, gestión de usuarios
- **Tecnología**: Laravel/Lumen + MySQL
- **Endpoints**:
  - `POST /auth/login` - Iniciar sesión
  - `POST /auth/register` - Registro de usuario
  - `GET /users/profile` - Obtener perfil
  - `PUT /users/profile` - Actualizar perfil

### 2. **Product Service** (Puerto: 8002)
- **Responsabilidad**: Catálogo de productos, categorías, inventario
- **Tecnología**: Laravel/Lumen + MySQL
- **Endpoints**:
  - `GET /products` - Listar productos
  - `GET /products/{id}` - Obtener producto
  - `GET /categories` - Listar categorías
  - `POST /products` - Crear producto (admin)

### 3. **Cart Service** (Puerto: 8003)
- **Responsabilidad**: Gestión temporal del carrito de compras
- **Tecnología**: Node.js + Express + Redis
- **Endpoints**:
  - `GET /cart` - Obtener carrito
  - `POST /cart/items` - Agregar item
  - `PUT /cart/items/{id}` - Actualizar item
  - `DELETE /cart/items/{id}` - Eliminar item

### 4. **Order Service** (Puerto: 8004)
- **Responsabilidad**: Procesamiento de órdenes, estados
- **Tecnología**: Laravel/Lumen + MySQL
- **Endpoints**:
  - `POST /orders` - Crear orden
  - `GET /orders` - Listar órdenes del usuario
  - `GET /orders/{id}` - Obtener orden
  - `PUT /orders/{id}/status` - Actualizar estado

### 5. **Payment Service** (Puerto: 8005)
- **Responsabilidad**: Procesamiento de pagos
- **Tecnología**: Node.js + Express + MySQL
- **Endpoints**:
  - `POST /payments/process` - Procesar pago
  - `GET /payments/{id}/status` - Estado del pago
  - `POST /payments/webhook` - Webhook de confirmación

### 6. **Notification Service** (Puerto: 8006)
- **Responsabilidad**: Envío de notificaciones (email, SMS)
- **Tecnología**: Node.js + Express + MongoDB
- **Endpoints**:
  - `POST /notifications/send` - Enviar notificación
  - `GET /notifications/history` - Historial de notificaciones

## 🚀 Plan de Migración

### Fase 1: Preparación
- [x] Análisis del monolito actual
- [x] Diseño de arquitectura distribuida
- [ ] Configuración de API Gateway (Kong)
- [ ] Configuración de Message Broker (RabbitMQ)

### Fase 2: Extracción de Servicios
- [ ] User Service
- [ ] Product Service
- [ ] Cart Service
- [ ] Order Service
- [ ] Payment Service
- [ ] Notification Service

### Fase 3: Comunicación y Eventos
- [ ] Implementación de eventos asíncronos
- [ ] API REST entre servicios
- [ ] Circuit breakers y resilencia

### Fase 4: Frontend y Despliegue
- [ ] Frontend SPA (React)
- [ ] Containerización con Docker
- [ ] Configuración de Kubernetes
- [ ] CI/CD Pipeline

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel/Lumen**: Framework PHP para servicios principales
- **Node.js + Express**: Servicios ligeros (Cart, Payment, Notification)
- **MySQL**: Base de datos relacional para datos persistentes
- **Redis**: Cache y almacenamiento temporal
- **MongoDB**: Logs y datos no estructurados

### Infraestructura
- **Docker**: Containerización
- **Kubernetes**: Orquestación de contenedores
- **Kong**: API Gateway
- **RabbitMQ**: Message Broker
- **Nginx**: Load balancer

### Frontend
- **React**: Single Page Application
- **Axios**: Cliente HTTP
- **React Router**: Navegación
- **Material-UI**: Componentes de UI

## 📁 Estructura del Proyecto

```
microservices-ecommerce/
├── api-gateway/                 # Kong configuration
├── services/
│   ├── user-service/           # Laravel/Lumen
│   ├── product-service/        # Laravel/Lumen
│   ├── cart-service/           # Node.js + Express
│   ├── order-service/          # Laravel/Lumen
│   ├── payment-service/        # Node.js + Express
│   └── notification-service/   # Node.js + Express
├── frontend/                   # React SPA
├── infrastructure/
│   ├── docker/                 # Docker configurations
│   ├── kubernetes/             # K8s manifests
│   └── rabbitmq/               # Message broker config
├── shared/
│   ├── events/                 # Event definitions
│   └── utils/                  # Shared utilities
└── docker-compose.yml          # Local development
```

## 🚀 Instrucciones de Despliegue

### Desarrollo Local
```bash
# Clonar el repositorio
git clone <repository-url>
cd microservices-ecommerce

# Levantar todos los servicios
docker-compose up -d

# Verificar servicios
docker-compose ps
```

### Despliegue en Kubernetes
```bash
# Aplicar configuraciones de Kubernetes
kubectl apply -f infrastructure/kubernetes/

# Verificar pods
kubectl get pods

# Verificar servicios
kubectl get services
```

## 🔧 Variables de Entorno

Cada servicio requiere las siguientes variables de entorno:

### User Service
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=user_service
DB_USERNAME=root
DB_PASSWORD=password
JWT_SECRET=your-jwt-secret
```

### Product Service
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=product_service
DB_USERNAME=root
DB_PASSWORD=password
```

### Cart Service
```env
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=
```

## 📊 Monitoreo y Logging

- **Logs centralizados**: ELK Stack (Elasticsearch, Logstash, Kibana)
- **Métricas**: Prometheus + Grafana
- **Trazabilidad**: Jaeger para distributed tracing
- **Health checks**: Endpoints de salud en cada servicio

## 🔒 Seguridad

- **Autenticación**: JWT tokens
- **Autorización**: RBAC (Role-Based Access Control)
- **HTTPS**: Certificados SSL/TLS
- **Rate limiting**: Implementado en API Gateway
- **CORS**: Configurado para frontend

## 🧪 Testing

- **Unit tests**: PHPUnit para Laravel, Jest para Node.js
- **Integration tests**: Tests de API entre servicios
- **E2E tests**: Cypress para frontend
- **Load testing**: Artillery para pruebas de carga

## 📈 Escalabilidad

- **Horizontal scaling**: Cada servicio escala independientemente
- **Auto-scaling**: Configurado en Kubernetes
- **Load balancing**: Distribución de carga automática
- **Caching**: Redis para cache distribuido

## 🚨 Troubleshooting

### Problemas Comunes
1. **Servicios no se comunican**: Verificar configuración de red en Docker/K8s
2. **Base de datos no conecta**: Verificar variables de entorno
3. **JWT inválido**: Verificar secret y expiración
4. **RabbitMQ no funciona**: Verificar configuración de mensajes

### Comandos Útiles
```bash
# Ver logs de un servicio
docker-compose logs -f service-name

# Reiniciar un servicio
docker-compose restart service-name

# Ver estado de Kubernetes
kubectl get all

# Debug de un pod
kubectl exec -it pod-name -- /bin/bash
```

## 📚 Documentación Adicional

- [API Documentation](docs/api/)
- [Deployment Guide](docs/deployment/)
- [Development Guide](docs/development/)
- [Architecture Decisions](docs/architecture/)

## 🤝 Contribución

1. Fork el proyecto
2. Crear feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a branch (`git push origin feature/AmazingFeature`)
5. Abrir Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para detalles.

## 👥 Equipo

- **Backend**: Equipo de microservicios
- **Frontend**: Equipo de React
- **DevOps**: Equipo de infraestructura
- **QA**: Equipo de testing

---

**Nota**: Esta migración sigue el patrón Strangler Fig, permitiendo migrar gradualmente sin interrumpir el servicio actual.
