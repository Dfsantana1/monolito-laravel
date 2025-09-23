# 🚀 Despliegue de Microservicios E-commerce

## Inicio Rápido

### Prerrequisitos
- Docker y Docker Compose instalados
- Al menos 4GB de RAM disponible
- Puertos 3000, 8000-8006, 3306, 6379, 27017, 5672, 15672 libres

### Despliegue Automático
```bash
# Clonar el repositorio
git clone <repository-url>
cd microservices

# Ejecutar script de inicio
./start.sh
```

### Despliegue Manual
```bash
# Construir y levantar todos los servicios
docker-compose up --build -d

# Verificar estado de los servicios
docker-compose ps

# Ver logs de un servicio específico
docker-compose logs -f [service-name]

# Parar todos los servicios
docker-compose down
```

## 🏗️ Arquitectura de Servicios

### Servicios Backend
| Servicio | Puerto | Tecnología | Base de Datos |
|----------|--------|------------|---------------|
| API Gateway | 8000 | Kong | - |
| User Service | 8001 | Laravel/Lumen | MySQL |
| Product Service | 8002 | Laravel/Lumen | MySQL |
| Cart Service | 8003 | Node.js | Redis |
| Order Service | 8004 | Laravel/Lumen | MySQL |
| Payment Service | 8005 | Node.js | MySQL |
| Notification Service | 8006 | Node.js | MongoDB |

### Servicios de Infraestructura
| Servicio | Puerto | Propósito |
|----------|--------|-----------|
| MySQL | 3306 | Bases de datos relacionales |
| Redis | 6379 | Cache y carrito temporal |
| MongoDB | 27017 | Logs de notificaciones |
| RabbitMQ | 5672/15672 | Message broker |

### Frontend
| Servicio | Puerto | Tecnología |
|----------|--------|------------|
| React SPA | 3000 | React + Material-UI |

## 🔧 Configuración

### Variables de Entorno
Cada servicio tiene su archivo `.env` con configuraciones específicas:

#### User Service
```env
DB_HOST=mysql
DB_DATABASE=user_service
JWT_SECRET=your-jwt-secret-key
```

#### Product Service
```env
DB_HOST=mysql
DB_DATABASE=product_service
```

#### Cart Service
```env
REDIS_HOST=redis
PRODUCT_SERVICE_URL=http://product-service:8002
```

## 📊 Monitoreo y Logs

### Ver Logs
```bash
# Todos los servicios
docker-compose logs -f

# Servicio específico
docker-compose logs -f user-service
docker-compose logs -f product-service
docker-compose logs -f cart-service
```

### Health Checks
```bash
# API Gateway
curl http://localhost:8000/health

# User Service
curl http://localhost:8001/health

# Product Service
curl http://localhost:8002/health

# Cart Service
curl http://localhost:8003/health
```

## 🧪 Testing

### Probar APIs
```bash
# Registrar usuario
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Obtener productos
curl http://localhost:8000/api/products

# Obtener categorías
curl http://localhost:8000/api/categories
```

## 🔍 Troubleshooting

### Problemas Comunes

#### Servicios no inician
```bash
# Verificar logs
docker-compose logs [service-name]

# Reiniciar servicio
docker-compose restart [service-name]

# Reconstruir servicio
docker-compose up --build [service-name]
```

#### Base de datos no conecta
```bash
# Verificar que MySQL esté corriendo
docker-compose ps mysql

# Ver logs de MySQL
docker-compose logs mysql

# Reiniciar MySQL
docker-compose restart mysql
```

#### Redis no conecta
```bash
# Verificar Redis
docker-compose exec redis redis-cli ping

# Ver logs
docker-compose logs redis
```

### Limpiar Todo
```bash
# Parar y eliminar contenedores
docker-compose down

# Eliminar volúmenes (CUIDADO: elimina datos)
docker-compose down -v

# Eliminar imágenes
docker-compose down --rmi all
```

## 📈 Escalabilidad

### Escalar Servicios
```bash
# Escalar Cart Service a 3 instancias
docker-compose up --scale cart-service=3 -d

# Escalar Product Service a 2 instancias
docker-compose up --scale product-service=2 -d
```

### Load Balancing
El API Gateway (Kong) maneja automáticamente el load balancing entre instancias del mismo servicio.

## 🔒 Seguridad

### Configuración de Producción
1. Cambiar todas las contraseñas por defecto
2. Usar certificados SSL/TLS
3. Configurar firewall
4. Habilitar autenticación en bases de datos
5. Usar secrets management

### Variables Sensibles
```bash
# Generar JWT secret
openssl rand -base64 32

# Generar contraseña MySQL
openssl rand -base64 16
```

## 📚 Documentación de APIs

### User Service
- `POST /api/auth/register` - Registro de usuario
- `POST /api/auth/login` - Login
- `GET /api/users/profile` - Perfil del usuario
- `PUT /api/users/profile` - Actualizar perfil

### Product Service
- `GET /api/products` - Listar productos
- `GET /api/products/{id}` - Obtener producto
- `GET /api/categories` - Listar categorías

### Cart Service
- `GET /api/cart/{userId}` - Obtener carrito
- `POST /api/cart/{userId}/items` - Agregar item
- `PUT /api/cart/{userId}/items/{itemId}` - Actualizar item
- `DELETE /api/cart/{userId}/items/{itemId}` - Eliminar item

## 🚀 Despliegue en Producción

### Kubernetes
```bash
# Aplicar configuraciones de K8s
kubectl apply -f infrastructure/kubernetes/

# Verificar pods
kubectl get pods

# Verificar servicios
kubectl get services
```

### Docker Swarm
```bash
# Inicializar swarm
docker swarm init

# Desplegar stack
docker stack deploy -c docker-compose.yml microservices
```

## 📞 Soporte

Para problemas o preguntas:
1. Revisar logs: `docker-compose logs -f`
2. Verificar estado: `docker-compose ps`
3. Consultar documentación de APIs
4. Crear issue en el repositorio

---

**Nota**: Este es un entorno de desarrollo. Para producción, configurar apropiadamente la seguridad, monitoreo y backup.
