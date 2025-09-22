<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    /**
     * Display user orders
     */
    public function index()
    {
        $orders = Auth::user()->orders()->with('orderItems.product')->orderBy('created_at', 'desc')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show checkout form
     */
    public function checkout()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = $cartItems->sum('total');
        $tax = $subtotal * 0.16;
        $shipping = $subtotal > 1000 ? 0 : 50;
        $total = $subtotal + $tax + $shipping;

        return view('orders.checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Process order
     */
    public function store(Request $request)
    {
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'payment_method' => 'required|string|in:credit_card,debit_card,paypal',
        ]);

        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        DB::beginTransaction();
        
        try {
            $subtotal = $cartItems->sum('total');
            $tax = $subtotal * 0.16;
            $shipping = $subtotal > 1000 ? 0 : 50;
            $total = $subtotal + $tax + $shipping;

            // Crear la orden
            $order = Auth::user()->orders()->create([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'billing_address' => [
                    'name' => $request->billing_name,
                    'email' => $request->billing_email,
                    'phone' => $request->billing_phone,
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'state' => $request->billing_state,
                    'postal_code' => $request->billing_postal_code,
                ],
                'shipping_address' => [
                    'name' => $request->shipping_name,
                    'phone' => $request->shipping_phone,
                    'address' => $request->shipping_address,
                    'city' => $request->shipping_city,
                    'state' => $request->shipping_state,
                    'postal_code' => $request->shipping_postal_code,
                ],
                'notes' => $request->notes,
            ]);

            // Crear los items de la orden
            foreach ($cartItems as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->total,
                ]);

                // Reducir stock
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            // Limpiar carrito
            Auth::user()->cartItems()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Orden creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar la orden. Inténtalo de nuevo.');
        }
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');
        return view('orders.show', compact('order'));
    }
}
