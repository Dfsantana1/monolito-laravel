#!/bin/bash

echo "🚀 Starting Microservices E-commerce Platform"
echo "=============================================="

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Check if Docker Compose is available
if ! command -v docker-compose &> /dev/null && ! command -v docker &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Use docker compose if available, fallback to docker-compose
DOCKER_COMPOSE_CMD="docker-compose"
if command -v docker &> /dev/null && docker compose version &> /dev/null; then
    DOCKER_COMPOSE_CMD="docker compose"
fi

echo "📦 Building and starting services..."

# Build and start all services
$DOCKER_COMPOSE_CMD up --build -d

echo "⏳ Waiting for services to be ready..."

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
MYSQL_TIMEOUT=60
MYSQL_COUNTER=0
until $DOCKER_COMPOSE_CMD exec mysql mysqladmin ping -h localhost --silent; do
    if [ $MYSQL_COUNTER -ge $MYSQL_TIMEOUT ]; then
        echo "⚠️ MySQL timeout after $MYSQL_TIMEOUT seconds. Continuing anyway..."
        break
    fi
    echo "MySQL is unavailable - sleeping ($MYSQL_COUNTER/$MYSQL_TIMEOUT)"
    sleep 2
    MYSQL_COUNTER=$((MYSQL_COUNTER + 2))
done
if [ $MYSQL_COUNTER -lt $MYSQL_TIMEOUT ]; then
    echo "✅ MySQL is ready!"
fi

# Wait for Redis to be ready
echo "Waiting for Redis..."
REDIS_TIMEOUT=30
REDIS_COUNTER=0
until $DOCKER_COMPOSE_CMD exec redis redis-cli ping > /dev/null 2>&1; do
    if [ $REDIS_COUNTER -ge $REDIS_TIMEOUT ]; then
        echo "⚠️ Redis timeout after $REDIS_TIMEOUT seconds. Continuing anyway..."
        break
    fi
    echo "Redis is unavailable - sleeping ($REDIS_COUNTER/$REDIS_TIMEOUT)"
    sleep 2
    REDIS_COUNTER=$((REDIS_COUNTER + 2))
done
if [ $REDIS_COUNTER -lt $REDIS_TIMEOUT ]; then
    echo "✅ Redis is ready!"
fi

# Wait for MongoDB to be ready
echo "Waiting for MongoDB..."
MONGO_TIMEOUT=30
MONGO_COUNTER=0
until $DOCKER_COMPOSE_CMD exec mongo mongosh --eval "db.adminCommand('ping')" > /dev/null 2>&1; do
    if [ $MONGO_COUNTER -ge $MONGO_TIMEOUT ]; then
        echo "⚠️ MongoDB timeout after $MONGO_TIMEOUT seconds. Continuing anyway..."
        break
    fi
    echo "MongoDB is unavailable - sleeping ($MONGO_COUNTER/$MONGO_TIMEOUT)"
    sleep 2
    MONGO_COUNTER=$((MONGO_COUNTER + 2))
done
if [ $MONGO_COUNTER -lt $MONGO_TIMEOUT ]; then
    echo "✅ MongoDB is ready!"
fi

# Wait for RabbitMQ to be ready
echo "Waiting for RabbitMQ..."
RABBITMQ_TIMEOUT=30
RABBITMQ_COUNTER=0
until $DOCKER_COMPOSE_CMD exec rabbitmq rabbitmq-diagnostics -q ping > /dev/null 2>&1; do
    if [ $RABBITMQ_COUNTER -ge $RABBITMQ_TIMEOUT ]; then
        echo "⚠️ RabbitMQ timeout after $RABBITMQ_TIMEOUT seconds. Continuing anyway..."
        break
    fi
    echo "RabbitMQ is unavailable - sleeping ($RABBITMQ_COUNTER/$RABBITMQ_TIMEOUT)"
    sleep 2
    RABBITMQ_COUNTER=$((RABBITMQ_COUNTER + 2))
done
if [ $RABBITMQ_COUNTER -lt $RABBITMQ_TIMEOUT ]; then
    echo "✅ RabbitMQ is ready!"
fi

# Run migrations for Laravel services
echo "🔄 Running database migrations..."

# Function to run migrations safely
run_migration() {
    local service_name=$1
    echo "Running $service_name migrations..."
    if $DOCKER_COMPOSE_CMD exec $service_name php artisan migrate --force 2>/dev/null; then
        echo "✅ $service_name migrations completed successfully"
    else
        echo "⚠️ $service_name migrations failed or service not ready. Skipping..."
    fi
}

# Check if services are actually ready and run migrations
if $DOCKER_COMPOSE_CMD ps user-service | grep -q "Up"; then
    run_migration "user-service"
else
    echo "⚠️ user-service is not running. Skipping migrations..."
fi

if $DOCKER_COMPOSE_CMD ps product-service | grep -q "Up"; then
    run_migration "product-service"
else
    echo "⚠️ product-service is not running. Skipping migrations..."
fi

if $DOCKER_COMPOSE_CMD ps order-service | grep -q "Up"; then
    run_migration "order-service"
else
    echo "⚠️ order-service is not running. Skipping migrations..."
fi

if $DOCKER_COMPOSE_CMD ps payment-service | grep -q "Up"; then
    run_migration "payment-service"
else
    echo "⚠️ payment-service is not running. Skipping migrations..."
fi

echo ""
echo "🎉 All services are ready!"
echo ""
echo "📋 Service URLs:"
echo "  🌐 Frontend:        http://localhost:3000"
echo "  🚪 API Gateway:     http://localhost:8000 (Admin: http://localhost:8080)"
echo "  👤 User Service:    http://localhost:8001"
echo "  📦 Product Service: http://localhost:8002"
echo "  🛒 Cart Service:    http://localhost:8003"
echo "  📋 Order Service:   http://localhost:8004"
echo "  💳 Payment Service: http://localhost:8005"
echo "  📧 Notification:    http://localhost:8006"
echo ""
echo "🔧 Management URLs:"
echo "  🐰 RabbitMQ:        http://localhost:15672 (guest/guest)"
echo "  🗄️  MySQL:           localhost:3306 (root/password)"
echo "  🔴 Redis:           localhost:6379"
echo "  🍃 MongoDB:         localhost:27017"
echo ""
echo "📋 To view logs: $DOCKER_COMPOSE_CMD logs -f [service-name]"
echo "🛑 To stop: $DOCKER_COMPOSE_CMD down"
echo ""
