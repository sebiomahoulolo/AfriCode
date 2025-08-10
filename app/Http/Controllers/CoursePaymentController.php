<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CoursePaymentController extends Controller
{
    private PaymentManager $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * Afficher la page d'achat d'un cours
     */
    public function showCheckout(Course $course)
    {
        // Vérifier si l'utilisateur est déjà inscrit
        if (Auth::user()->courses()->where('course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course)
                ->with('info', 'Vous êtes déjà inscrit à ce cours.');
        }

        // Vérifier si le cours est gratuit
        if ($course->price <= 0) {
            return $this->enrollFreeCourse($course);
        }

        // Obtenir les passerelles disponibles
        $availableGateways = $this->paymentManager->getAvailableGateways(
            $course->price, 
            $course->currency ?? 'XOF'
        );

        if ($availableGateways->isEmpty()) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Aucun moyen de paiement disponible pour ce cours.');
        }

        return view('courses.checkout', compact('course', 'availableGateways'));
    }

    /**
     * Traiter l'inscription à un cours gratuit
     */
    private function enrollFreeCourse(Course $course)
    {
        Auth::user()->courses()->syncWithoutDetaching([
            $course->id => [
                'enrolled_at' => now(),
                'progress_percentage' => 0
            ]
        ]);

        return redirect()->route('courses.show', $course)
            ->with('success', 'Inscription réussie ! Vous pouvez maintenant accéder au cours.');
    }

    /**
     * Initier un paiement
     */
    public function initiatePayment(Request $request, Course $course)
    {
        $request->validate([
            'payment_gateway' => 'required|string|exists:payment_gateways,name',
        ]);

        // Vérifier si l'utilisateur est déjà inscrit
        if (Auth::user()->courses()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous êtes déjà inscrit à ce cours.'
            ]);
        }

        try {
            // Créer le paiement
            $payment = $this->paymentManager->createPayment(
                Auth::user(),
                $course,
                $request->payment_gateway,
                $course->price,
                $course->currency ?? 'XOF'
            );

            // Initier le paiement avec la passerelle
            $result = $this->paymentManager->initiatePayment($payment);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'payment_id' => $payment->id,
                    'data' => $result['data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur initiation paiement', [
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'initiation du paiement.'
            ]);
        }
    }

    /**
     * Vérifier le statut d'un paiement
     */
    public function checkPaymentStatus(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur connecté
        if ($payment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 403);
        }

        try {
            $result = $this->paymentManager->checkPaymentStatus($payment);

            return response()->json([
                'success' => true,
                'status' => $payment->fresh()->status,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur vérification paiement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du paiement'
            ]);
        }
    }

    /**
     * Page de succès après paiement
     */
    public function paymentSuccess(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur connecté
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier le statut final du paiement
        $this->paymentManager->checkPaymentStatus($payment);
        $payment->refresh();

        if ($payment->isSuccessful()) {
            // S'assurer que l'inscription est active
            if ($payment->course) {
                Auth::user()->courses()->syncWithoutDetaching([
                    $payment->course->id => [
                        'enrolled_at' => now(),
                        'progress_percentage' => 0
                    ]
                ]);
            }

            return view('courses.payment-success', compact('payment'));
        }

        return redirect()->route('courses.payment-failed', $payment);
    }

    /**
     * Page d'échec de paiement
     */
    public function paymentFailed(Payment $payment)
    {
        // Vérifier que le paiement appartient à l'utilisateur connecté
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('courses.payment-failed', compact('payment'));
    }

    /**
     * Obtenir les détails d'une passerelle pour le frontend
     */
    public function getGatewayDetails(PaymentGateway $gateway)
    {
        if (!$gateway->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Cette passerelle n\'est pas disponible'
            ]);
        }

        try {
            $service = $this->paymentManager->getService($gateway->name);
            
            return response()->json([
                'success' => true,
                'gateway' => [
                    'id' => $gateway->id,
                    'name' => $gateway->name,
                    'display_name' => $gateway->display_name,
                    'description' => $gateway->description,
                    'icon' => $gateway->icon,
                    'supported_currencies' => $gateway->supported_currencies,
                    'fees_percentage' => $gateway->fees_percentage,
                    'fees_fixed' => $gateway->fees_fixed,
                    'test_mode' => $gateway->test_mode,
                    'config' => $service->getPublicConfig()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des détails'
            ]);
        }
    }

    /**
     * Calculer les frais pour un montant et une passerelle
     */
    public function calculateFees(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'gateway_id' => 'required|exists:payment_gateways,id'
        ]);

        $gateway = PaymentGateway::findOrFail($request->gateway_id);
        $amount = $request->amount;
        $fees = $gateway->calculateFees($amount);
        $total = $amount + $fees;

        return response()->json([
            'success' => true,
            'amount' => $amount,
            'fees' => $fees,
            'total' => $total,
            'currency' => $request->currency ?? 'XOF'
        ]);
    }

    /**
     * Historique des paiements de l'utilisateur
     */
    public function paymentHistory()
    {
        $payments = Auth::user()->payments()
            ->with(['course', 'paymentGateway'])
            ->latest()
            ->paginate(10);

        return view('user.payment-history', compact('payments'));
    }
}
