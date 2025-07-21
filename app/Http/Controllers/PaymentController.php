<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request, Course $course)
    {
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'payable_id' => $course->id,
            'payable_type' => 'App\\Models\\Course',
            'amount' => $course->price,
            'currency' => 'EUR',
            'payment_method' => $request->payment_method ?? 'manual',
            'status' => 'pending',
            'transaction_id' => Str::uuid(),
            'payment_date' => now(),
        ]);

        // Ici, vous pouvez intégrer votre passerelle de paiement préférée
        // Par exemple, Stripe, PayPal, etc.

        return response()->json([
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'transaction_id' => $payment->transaction_id
        ]);
    }

    public function handleCallback(Request $request)
    {
        $payment = Payment::where('transaction_id', $request->transaction_id)->firstOrFail();
        
        if ($request->status === 'success') {
            $payment->update([
                'status' => 'completed',
                'payment_details' => $request->all()
            ]);

            // Envoyer une notification de succès
            // Mettre à jour le statut d'inscription au cours
        } else {
            $payment->update([
                'status' => 'failed',
                'payment_details' => $request->all()
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function refund(Request $request, Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return response()->json(['error' => 'Payment cannot be refunded'], 400);
        }

        $payment->update([
            'refund_status' => 'processing',
            'refund_date' => now()
        ]);

        // Ici, vous pouvez intégrer la logique de remboursement
        // avec votre passerelle de paiement

        return response()->json(['status' => 'refund_initiated']);
    }

    public function paymentHistory()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with('course')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('payments.history', compact('payments'));
    }
} 