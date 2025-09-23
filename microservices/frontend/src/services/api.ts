import axios, { AxiosInstance, AxiosResponse } from 'axios';
import { AuthResponse, ApiResponse, PaginatedResponse, User, Product, Category, Cart, Order } from '../types';

class ApiService {
  private api: AxiosInstance;

  constructor() {
    this.api = axios.create({
      baseURL: process.env.REACT_APP_API_URL || 'http://localhost:8000',
      headers: {
        'Content-Type': 'application/json',
      },
    });

    // Request interceptor to add auth token
    this.api.interceptors.request.use(
      (config) => {
        const token = localStorage.getItem('token');
        if (token) {
          config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
      },
      (error) => {
        return Promise.reject(error);
      }
    );

    // Response interceptor to handle auth errors
    this.api.interceptors.response.use(
      (response) => response,
      (error) => {
        if (error.response?.status === 401) {
          localStorage.removeItem('token');
          localStorage.removeItem('user');
          window.location.href = '/login';
        }
        return Promise.reject(error);
      }
    );
  }

  // Auth endpoints
  async register(userData: { name: string; email: string; password: string; password_confirmation: string }): Promise<AuthResponse> {
    const response: AxiosResponse<AuthResponse> = await this.api.post('/api/auth/register', userData);
    return response.data;
  }

  async login(credentials: { email: string; password: string }): Promise<AuthResponse> {
    const response: AxiosResponse<AuthResponse> = await this.api.post('/api/auth/login', credentials);
    return response.data;
  }

  async logout(): Promise<void> {
    await this.api.post('/api/auth/logout');
  }

  async getProfile(): Promise<ApiResponse<{ user: User }>> {
    const response: AxiosResponse<ApiResponse<{ user: User }>> = await this.api.get('/api/auth/me');
    return response.data;
  }

  // Product endpoints
  async getProducts(params?: {
    page?: number;
    per_page?: number;
    category_id?: number;
    featured?: boolean;
    search?: string;
    min_price?: number;
    max_price?: number;
    sort_by?: string;
    sort_order?: 'asc' | 'desc';
  }): Promise<PaginatedResponse<Product>> {
    const response: AxiosResponse<PaginatedResponse<Product>> = await this.api.get('/api/products', { params });
    return response.data;
  }

  async getProduct(id: number): Promise<ApiResponse<{ product: Product }>> {
    const response: AxiosResponse<ApiResponse<{ product: Product }>> = await this.api.get(`/api/products/${id}`);
    return response.data;
  }

  async getFeaturedProducts(): Promise<ApiResponse<{ products: Product[] }>> {
    const response: AxiosResponse<ApiResponse<{ products: Product[] }>> = await this.api.get('/api/products/featured');
    return response.data;
  }

  // Category endpoints
  async getCategories(): Promise<ApiResponse<{ categories: Category[] }>> {
    const response: AxiosResponse<ApiResponse<{ categories: Category[] }>> = await this.api.get('/api/categories');
    return response.data;
  }

  async getCategory(id: number): Promise<ApiResponse<{ category: Category }>> {
    const response: AxiosResponse<ApiResponse<{ category: Category }>> = await this.api.get(`/api/categories/${id}`);
    return response.data;
  }

  // Cart endpoints
  async getCart(userId: number): Promise<ApiResponse<{ cart: Cart }>> {
    const response: AxiosResponse<ApiResponse<{ cart: Cart }>> = await this.api.get(`/api/cart/${userId}`);
    return response.data;
  }

  async addToCart(userId: number, productId: number, quantity: number = 1): Promise<ApiResponse<{ cart: Cart }>> {
    const response: AxiosResponse<ApiResponse<{ cart: Cart }>> = await this.api.post(`/api/cart/${userId}/items`, {
      productId,
      quantity,
    });
    return response.data;
  }

  async updateCartItem(userId: number, itemId: string, quantity: number): Promise<ApiResponse<{ cart: Cart }>> {
    const response: AxiosResponse<ApiResponse<{ cart: Cart }>> = await this.api.put(`/api/cart/${userId}/items/${itemId}`, {
      quantity,
    });
    return response.data;
  }

  async removeFromCart(userId: number, itemId: string): Promise<ApiResponse<{ cart: Cart }>> {
    const response: AxiosResponse<ApiResponse<{ cart: Cart }>> = await this.api.delete(`/api/cart/${userId}/items/${itemId}`);
    return response.data;
  }

  async clearCart(userId: number): Promise<ApiResponse<void>> {
    const response: AxiosResponse<ApiResponse<void>> = await this.api.delete(`/api/cart/${userId}`);
    return response.data;
  }

  // Order endpoints
  async getOrders(userId: number): Promise<ApiResponse<{ orders: Order[] }>> {
    const response: AxiosResponse<ApiResponse<{ orders: Order[] }>> = await this.api.get(`/api/orders?user_id=${userId}`);
    return response.data;
  }

  async getOrder(id: number): Promise<ApiResponse<{ order: Order }>> {
    const response: AxiosResponse<ApiResponse<{ order: Order }>> = await this.api.get(`/api/orders/${id}`);
    return response.data;
  }

  async createOrder(orderData: any): Promise<ApiResponse<{ order: Order }>> {
    const response: AxiosResponse<ApiResponse<{ order: Order }>> = await this.api.post('/api/orders', orderData);
    return response.data;
  }
}

export default new ApiService();
