#!/bin/bash

echo "🐳 Iniciando aplicación Laravel dockerizada..."

# Verificar si Docker está ejecutándose
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker no está ejecutándose. Por favor, inicia Docker."
    exit 1
fi

# Construir y ejecutar contenedores
echo "📦 Construyendo contenedores..."
sudo docker compose build

echo "🚀 Iniciando contenedores..."
sudo docker compose up -d

# Esperar un momento para que los contenedores se inicien
sleep 5

# Verificar que los contenedores estén ejecutándose
echo "🔍 Verificando estado de los contenedores..."
sudo docker compose ps

# Ejecutar migraciones y seeders
echo "🗄️ Ejecutando migraciones y seeders..."
sudo docker compose exec app php artisan migrate:fresh --seed --force

echo ""
echo "✅ ¡Aplicación iniciada exitosamente!"
echo ""
echo "🌐 URL de la aplicación: http://localhost:8080"
echo "📊 Catálogo de productos: http://localhost:8080/products"
echo ""
echo "👤 Usuario de prueba:"
echo "   Email: test@example.com"
echo "   Password: password"
echo ""
echo "🔧 Comandos útiles:"
echo "   • Detener: sudo docker compose down"
echo "   • Ver logs: sudo docker compose logs -f"
echo "   • Artisan: sudo docker compose exec app php artisan [comando]"
echo ""