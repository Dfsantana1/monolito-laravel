export interface User {
  id: number;
  name: string;
  email: string;
  phone?: string;
  address?: string;
  city?: string;
  state?: string;
  zip_code?: string;
  country?: string;
  is_active: boolean;
  created_at: string;
  updated_at: string;
}

export interface Product {
  id: number;
  category_id: number;
  name: string;
  slug: string;
  description?: string;
  price: number;
  sale_price?: number;
  stock: number;
  sku: string;
  images?: string[];
  is_active: boolean;
  is_featured: boolean;
  current_price: number;
  discount_percentage: number;
  category?: Category;
  created_at: string;
  updated_at: string;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  description?: string;
  image?: string;
  is_active: boolean;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface CartItem {
  id: string;
  productId: number;
  name: string;
  price: number;
  quantity: number;
  image?: string;
}

export interface Cart {
  id: string;
  userId: number;
  items: CartItem[];
  total: string;
  itemCount: number;
  createdAt: string;
  updatedAt: string;
}

export interface Order {
  id: number;
  order_number: string;
  user_id: number;
  status: string;
  subtotal: number;
  tax: number;
  shipping: number;
  total: number;
  payment_status: string;
  payment_method?: string;
  billing_address?: any;
  shipping_address?: any;
  notes?: string;
  created_at: string;
  updated_at: string;
}

export interface AuthResponse {
  success: boolean;
  message: string;
  data: {
    user: User;
    token: string;
    token_type: string;
    expires_in: number;
  };
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}
