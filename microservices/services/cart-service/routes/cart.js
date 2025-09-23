const express = require('express');
const cartController = require('../controllers/cartController');

const router = express.Router();

// Get user's cart
router.get('/:userId', cartController.getCart);

// Add item to cart
router.post('/:userId/items', cartController.addItem);

// Update cart item
router.put('/:userId/items/:itemId', cartController.updateItem);

// Remove item from cart
router.delete('/:userId/items/:itemId', cartController.removeItem);

// Clear cart
router.delete('/:userId', cartController.clearCart);

module.exports = router;
