<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\Course;
use App\Services\FedaPayService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PaymentStatus extends Component
{
    public $payment;
    public $course;
    public $status = 'pending';
    public $message = '';
    public $checkInterval = 3000; // 3 secondes

    protected $listeners = ['refreshPaymentStatus'];

    public function mount($paymentId)
    {
        $this->payment = Payment::with(['enrollment.course'])->findOrFail($paymentId);
        $this->course = $this->payment->enrollment->course;
        $this->status = $this->payment->status;
        $this->updateMessage();
    }

    public function render()
    {
        return view('livewire.payment-status');
    }

    public function checkPaymentStatus()
    {
        $this->payment->refresh();
        $oldStatus = $this->status;
        $this->status = $this->payment->status;

        if ($oldStatus !== $this->status) {
            $this->updateMessage();
            
            if ($this->status === 'completed') {
                $this->dispatch('payment-completed');
                // Rediriger vers le cours après un délai
                $this->dispatch('redirect-to-course', route('apprenant.course.access', ['courseId' => $this->course->id]));
            } elseif ($this->status === 'failed') {
                $this->dispatch('payment-failed');
            }
        }

        return $this->status;
    }

    public function retryPayment()
    {
        if ($this->payment->status === 'failed') {
            // Créer un nouveau paiement
            $fedaPayService = new FedaPayService();
            $result = $fedaPayService->createTransaction(
                $this->payment->enrollment,
                $this->course->price,
                "Nouvelle tentative - Achat du cours: {$this->course->title}"
            );

            if ($result['success']) {
                $this->payment = $result['payment'];
                $this->status = 'pending';
                $this->updateMessage();
                
                // Rediriger vers la nouvelle page de paiement
                return redirect()->route('enrollment.show', $this->course);
            } else {
                session()->flash('error', 'Erreur lors de la création du nouveau paiement.');
            }
        }
    }

    private function updateMessage()
    {
        switch ($this->status) {
            case 'pending':
                $this->message = 'Paiement en cours de traitement...';
                break;
            case 'processing':
                $this->message = 'Validation du paiement en cours...';
                break;
            case 'completed':
                $this->message = 'Paiement réussi ! Redirection vers votre cours...';
                break;
            case 'failed':
                $this->message = 'Le paiement a échoué. Vous pouvez réessayer.';
                break;
            case 'cancelled':
                $this->message = 'Paiement annulé.';
                break;
            default:
                $this->message = 'Statut inconnu.';
        }
    }

    public function refreshPaymentStatus()
    {
        $this->checkPaymentStatus();
    }
}
