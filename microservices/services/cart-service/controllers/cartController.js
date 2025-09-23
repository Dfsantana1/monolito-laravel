const { getRedisClient } = require('../config/redis');
const productService = require('../services/productService');
const { v4: uuidv4 } = require('uuid');

class CartController {
    async getCart(req, res) {
        try {
            const { userId } = req.params;
            const redis = getRedisClient();
            
            const cartKey = `cart:${userId}`;
            const cartData = await redis.get(cartKey);
            
            if (!cartData) {
                return res.json({
                    success: true,
                    message: 'Cart retrieved successfully',
                    data: {
                        cart: {
                            items: [],
                            total: 0,
                            itemCount: 0
                        }
                    }
                });
            }

            const cart = JSON.parse(cartData);
            
            // Calculate totals
            let total = 0;
            let itemCount = 0;
            
            for (const item of cart.items) {
                total += item.price * item.quantity;
                itemCount += item.quantity;
            }

            res.json({
                success: true,
                message: 'Cart retrieved successfully',
                data: {
                    cart: {
                        ...cart,
                        total: total.toFixed(2),
                        itemCount
                    }
                }
            });

        } catch (error) {
            console.error('Error getting cart:', error);
            res.status(500).json({
                success: false,
                message: 'Could not retrieve cart',
                error: error.message
            });
        }
    }

    async addItem(req, res) {
        try {
            const { userId } = req.params;
            const { productId, quantity = 1 } = req.body;

            if (!productId || quantity <= 0) {
                return res.status(400).json({
                    success: false,
                    message: 'Product ID and valid quantity are required'
                });
            }

            // Validate product
            const product = await productService.validateProduct(productId);
            
            if (product.stock < quantity) {
                return res.status(400).json({
                    success: false,
                    message: 'Insufficient stock'
                });
            }

            const redis = getRedisClient();
            const cartKey = `cart:${userId}`;
            
            // Get existing cart
            let cart = await redis.get(cartKey);
            if (cart) {
                cart = JSON.parse(cart);
            } else {
                cart = {
                    id: uuidv4(),
                    userId,
                    items: [],
                    createdAt: new Date().toISOString(),
                    updatedAt: new Date().toISOString()
                };
            }

            // Check if item already exists
            const existingItemIndex = cart.items.findIndex(item => item.productId === productId);
            
            if (existingItemIndex > -1) {
                // Update existing item
                cart.items[existingItemIndex].quantity += quantity;
                cart.items[existingItemIndex].price = product.current_price || product.price;
            } else {
                // Add new item
                cart.items.push({
                    id: uuidv4(),
                    productId,
                    name: product.name,
                    price: product.current_price || product.price,
                    quantity,
                    image: product.images ? product.images[0] : null
                });
            }

            cart.updatedAt = new Date().toISOString();

            // Save to Redis
            await redis.setEx(cartKey, 86400, JSON.stringify(cart)); // 24 hours TTL

            res.json({
                success: true,
                message: 'Item added to cart successfully',
                data: {
                    cart
                }
            });

        } catch (error) {
            console.error('Error adding item to cart:', error);
            res.status(500).json({
                success: false,
                message: 'Could not add item to cart',
                error: error.message
            });
        }
    }

    async updateItem(req, res) {
        try {
            const { userId, itemId } = req.params;
            const { quantity } = req.body;

            if (quantity <= 0) {
                return res.status(400).json({
                    success: false,
                    message: 'Quantity must be greater than 0'
                });
            }

            const redis = getRedisClient();
            const cartKey = `cart:${userId}`;
            
            const cartData = await redis.get(cartKey);
            if (!cartData) {
                return res.status(404).json({
                    success: false,
                    message: 'Cart not found'
                });
            }

            const cart = JSON.parse(cartData);
            const itemIndex = cart.items.findIndex(item => item.id === itemId);
            
            if (itemIndex === -1) {
                return res.status(404).json({
                    success: false,
                    message: 'Item not found in cart'
                });
            }

            // Validate product stock
            const product = await productService.validateProduct(cart.items[itemIndex].productId);
            if (product.stock < quantity) {
                return res.status(400).json({
                    success: false,
                    message: 'Insufficient stock'
                });
            }

            cart.items[itemIndex].quantity = quantity;
            cart.items[itemIndex].price = product.current_price || product.price;
            cart.updatedAt = new Date().toISOString();

            await redis.setEx(cartKey, 86400, JSON.stringify(cart));

            res.json({
                success: true,
                message: 'Item updated successfully',
                data: {
                    cart
                }
            });

        } catch (error) {
            console.error('Error updating cart item:', error);
            res.status(500).json({
                success: false,
                message: 'Could not update cart item',
                error: error.message
            });
        }
    }

    async removeItem(req, res) {
        try {
            const { userId, itemId } = req.params;
            const redis = getRedisClient();
            const cartKey = `cart:${userId}`;
            
            const cartData = await redis.get(cartKey);
            if (!cartData) {
                return res.status(404).json({
                    success: false,
                    message: 'Cart not found'
                });
            }

            const cart = JSON.parse(cartData);
            const itemIndex = cart.items.findIndex(item => item.id === itemId);
            
            if (itemIndex === -1) {
                return res.status(404).json({
                    success: false,
                    message: 'Item not found in cart'
                });
            }

            cart.items.splice(itemIndex, 1);
            cart.updatedAt = new Date().toISOString();

            await redis.setEx(cartKey, 86400, JSON.stringify(cart));

            res.json({
                success: true,
                message: 'Item removed from cart successfully',
                data: {
                    cart
                }
            });

        } catch (error) {
            console.error('Error removing cart item:', error);
            res.status(500).json({
                success: false,
                message: 'Could not remove cart item',
                error: error.message
            });
        }
    }

    async clearCart(req, res) {
        try {
            const { userId } = req.params;
            const redis = getRedisClient();
            const cartKey = `cart:${userId}`;
            
            await redis.del(cartKey);

            res.json({
                success: true,
                message: 'Cart cleared successfully'
            });

        } catch (error) {
            console.error('Error clearing cart:', error);
            res.status(500).json({
                success: false,
                message: 'Could not clear cart',
                error: error.message
            });
        }
    }
}

module.exports = new CartController();
