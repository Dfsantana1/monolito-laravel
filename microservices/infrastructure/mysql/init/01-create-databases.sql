-- Create databases for microservices
CREATE DATABASE IF NOT EXISTS user_service;
CREATE DATABASE IF NOT EXISTS product_service;
CREATE DATABASE IF NOT EXISTS order_service;
CREATE DATABASE IF NOT EXISTS payment_service;

-- Grant permissions
GRANT ALL PRIVILEGES ON user_service.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON product_service.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON order_service.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON payment_service.* TO 'root'@'%';

FLUSH PRIVILEGES;
