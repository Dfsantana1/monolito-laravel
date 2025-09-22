<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show payment form
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', 'Esta orden ya ha sido procesada.');
        }

        return view('payments.show', compact('order'));
    }

    /**
     * Process payment
     */
    public function process(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->route('orders.show', $order)->with('error', 'Esta orden ya ha sido procesada.');
        }

        $request->validate([
            'card_number' => 'required|string|size:16',
            'card_expiry' => 'required|string|regex:/^\d{2}\/\d{2}$/',
            'card_cvv' => 'required|string|size:3',
            'card_name' => 'required|string|max:255',
        ]);

        // Simular procesamiento de pago
        $paymentResult = $this->simulatePayment($request->all(), $order);

        if ($paymentResult['success']) {
            $order->update([
                'payment_status' => 'completed',
                'status' => 'processing'
            ]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pago procesado exitosamente. Tu orden está siendo procesada.');
        } else {
            return back()->with('error', $paymentResult['message']);
        }
    }

    /**
     * Simulate payment processing
     */
    private function simulatePayment($paymentData, Order $order)
    {
        // Simular diferentes escenarios de pago
        $cardNumber = $paymentData['card_number'];
        
        // Tarjetas que fallan para simular errores
        $declinedCards = ['1111111111111111', '2222222222222222', '3333333333333333'];
        
        if (in_array($cardNumber, $declinedCards)) {
            return [
                'success' => false,
                'message' => 'Tu tarjeta fue declinada. Por favor, verifica los datos o usa otra tarjeta.'
            ];
        }

        // Simular tarjetas con fondos insuficientes
        if (str_starts_with($cardNumber, '4444')) {
            return [
                'success' => false,
                'message' => 'Fondos insuficientes. Por favor, usa otra tarjeta.'
            ];
        }

        // Simular tarjetas expiradas
        if (str_starts_with($cardNumber, '5555')) {
            return [
                'success' => false,
                'message' => 'Tarjeta expirada. Por favor, usa otra tarjeta.'
            ];
        }

        // Simular demora en el procesamiento
        sleep(2);

        // Pago exitoso
        return [
            'success' => true,
            'message' => 'Pago procesado exitosamente.',
            'transaction_id' => 'TXN-' . strtoupper(uniqid())
        ];
    }

    /**
     * Show payment success page
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.success', compact('order'));
    }

    /**
     * Show payment failure page
     */
    public function failure(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.failure', compact('order'));
    }
}
