const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
require('dotenv').config();

const cartRoutes = require('./routes/cart');
const { connectRedis } = require('./config/redis');

const app = express();
const PORT = process.env.PORT || 8003;

// Middleware
app.use(helmet());
app.use(cors());
app.use(morgan('combined'));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Health check
app.get('/', (req, res) => {
    res.json({
        service: 'Cart Service',
        version: '1.0.0',
        status: 'healthy'
    });
});

app.get('/health', (req, res) => {
    res.json({
        service: 'Cart Service',
        status: 'healthy',
        timestamp: new Date().toISOString()
    });
});

// Routes
app.use('/cart', cartRoutes);

// Error handling middleware
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({
        success: false,
        message: 'Something went wrong!',
        error: process.env.NODE_ENV === 'development' ? err.message : 'Internal server error'
    });
});

// 404 handler
app.use('*', (req, res) => {
    res.status(404).json({
        success: false,
        message: 'Route not found'
    });
});

// Connect to Redis and start server
connectRedis().then(() => {
    app.listen(PORT, () => {
        console.log(`Cart Service running on port ${PORT}`);
    });
}).catch(err => {
    console.error('Failed to connect to Redis:', err);
    process.exit(1);
});

module.exports = app;
