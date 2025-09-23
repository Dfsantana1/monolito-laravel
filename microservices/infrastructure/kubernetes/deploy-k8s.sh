#!/bin/bash

echo "🚀 Deploying Microservices to Kubernetes"
echo "========================================"

# Check if kubectl is available
if ! command -v kubectl &> /dev/null; then
    echo "❌ kubectl is not installed. Please install kubectl first."
    exit 1
fi

# Check if cluster is accessible
if ! kubectl cluster-info &> /dev/null; then
    echo "❌ Kubernetes cluster is not accessible. Please check your kubeconfig."
    exit 1
fi

echo "📦 Creating namespace..."
kubectl apply -f namespaces/microservices-namespace.yaml

echo "🔧 Creating ConfigMaps and Secrets..."
kubectl apply -f configmaps/app-config.yaml
kubectl apply -f secrets/app-secrets.yaml

echo "💾 Creating Persistent Volumes..."
kubectl apply -f persistent-volumes/mysql-pvc.yaml

echo "🗄️ Deploying MySQL..."
kubectl apply -f deployments/mysql-deployment.yaml
kubectl apply -f services/mysql-service.yaml

echo "⏳ Waiting for MySQL to be ready..."
kubectl wait --for=condition=ready pod -l app=mysql -n microservices --timeout=300s

echo "👤 Deploying User Service..."
kubectl apply -f deployments/user-service-deployment.yaml
kubectl apply -f services/user-service-service.yaml

echo "📦 Deploying Product Service..."
kubectl apply -f deployments/product-service-deployment.yaml
kubectl apply -f services/product-service-service.yaml

echo "🛒 Deploying Cart Service..."
kubectl apply -f deployments/cart-service-deployment.yaml
kubectl apply -f services/cart-service-service.yaml

echo "📋 Deploying Order Service..."
kubectl apply -f deployments/order-service-deployment.yaml
kubectl apply -f services/order-service-service.yaml

echo "💳 Deploying Payment Service..."
kubectl apply -f deployments/payment-service-deployment.yaml
kubectl apply -f services/payment-service-service.yaml

echo "📧 Deploying Notification Service..."
kubectl apply -f deployments/notification-service-deployment.yaml
kubectl apply -f services/notification-service-service.yaml

echo "🔴 Deploying Redis..."
kubectl apply -f deployments/redis-deployment.yaml
kubectl apply -f services/redis-service.yaml

echo "🍃 Deploying MongoDB..."
kubectl apply -f deployments/mongo-deployment.yaml
kubectl apply -f services/mongo-service.yaml

echo "🐰 Deploying RabbitMQ..."
kubectl apply -f deployments/rabbitmq-deployment.yaml
kubectl apply -f services/rabbitmq-service.yaml

echo "🌐 Deploying Frontend..."
kubectl apply -f deployments/frontend-deployment.yaml
kubectl apply -f services/frontend-service.yaml

echo "🌍 Creating Ingress..."
kubectl apply -f ingress/api-ingress.yaml

echo "⏳ Waiting for all pods to be ready..."
kubectl wait --for=condition=ready pod -l app=ecommerce-microservices -n microservices --timeout=300s

echo ""
echo "🎉 Deployment completed!"
echo ""
echo "📋 Service Status:"
kubectl get pods -n microservices
echo ""
echo "🌐 Services:"
kubectl get services -n microservices
echo ""
echo "🌍 Ingress:"
kubectl get ingress -n microservices
echo ""
echo "📊 To view logs: kubectl logs -f deployment/[service-name] -n microservices"
echo "🛑 To delete: kubectl delete namespace microservices"
echo ""
