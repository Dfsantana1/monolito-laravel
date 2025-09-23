const axios = require('axios');

class ProductService {
    constructor() {
        this.baseURL = process.env.PRODUCT_SERVICE_URL || 'http://localhost:8002';
    }

    async getProduct(productId) {
        try {
            const response = await axios.get(`${this.baseURL}/products/${productId}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching product:', error.message);
            throw new Error('Product not found');
        }
    }

    async validateProduct(productId) {
        try {
            const product = await this.getProduct(productId);
            if (!product.data.product.is_active) {
                throw new Error('Product is not available');
            }
            return product.data.product;
        } catch (error) {
            throw error;
        }
    }
}

module.exports = new ProductService();
