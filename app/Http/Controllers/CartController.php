<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    /**
     * Display the cart
     */
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.category')->get();
        $subtotal = $cartItems->sum('total');
        $tax = $subtotal * 0.16; // 16% IVA
        $shipping = $subtotal > 1000 ? 0 : 50; // Envío gratis sobre $1000
        $total = $subtotal + $tax + $shipping;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $quantity = $request->quantity;
        $price = $product->current_price;

        // Verificar si el producto ya está en el carrito
        $cartItem = Auth::user()->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Actualizar cantidad
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'No hay suficiente stock disponible.');
            }
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $price
            ]);
        } else {
            // Crear nuevo item
            Auth::user()->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price
            ]);
        }

        return back()->with('success', 'Producto agregado al carrito.');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock
        ]);

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Carrito actualizado.');
    }

    /**
     * Remove item from cart
     */
    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return back()->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Auth::user()->cartItems()->delete();
        return back()->with('success', 'Carrito vaciado.');
    }
}
