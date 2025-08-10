<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    private PaymentManager $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    /**
     * Afficher la liste des passerelles de paiement
     */
    public function index()
    {
        $gateways = PaymentGateway::orderBy('order_priority')->get();
        $availableGateways = PaymentGateway::getAvailableGateways();
        $stats = $this->paymentManager->getPaymentStats();

        return view('admin.payment-gateways.index', compact('gateways', 'availableGateways', 'stats'));
    }

    /**
     * Afficher le formulaire de création d'une passerelle
     */
    public function create()
    {
        $availableGateways = PaymentGateway::getAvailableGateways();
        $existingGateways = PaymentGateway::pluck('name')->toArray();
        
        // Filtrer les passerelles déjà créées
        $newGateways = array_diff_key($availableGateways, array_flip($existingGateways));

        return view('admin.payment-gateways.create', compact('newGateways'));
    }

    /**
     * Créer une nouvelle passerelle de paiement
     */
    public function store(Request $request)
    {
        $request->validate([
            'gateway_type' => 'required|string',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'test_mode' => 'boolean',
            'order_priority' => 'integer|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'fees_percentage' => 'nullable|numeric|min:0|max:100',
            'fees_fixed' => 'nullable|numeric|min:0',
            'supported_currencies' => 'array',
            'supported_currencies.*' => 'string|in:XOF,USD,EUR'
        ]);

        $availableGateways = PaymentGateway::getAvailableGateways();
        $gatewayConfig = $availableGateways[$request->gateway_type] ?? null;

        if (!$gatewayConfig) {
            return back()->withErrors(['gateway_type' => 'Type de passerelle invalide']);
        }

        // Valider la configuration spécifique à la passerelle
        $configFields = $this->paymentManager->getGatewayConfigFields($request->gateway_type);
        $configuration = [];
        
        foreach ($configFields as $field => $config) {
            $value = $request->input("config.{$field}");
            
            if ($config['required'] && empty($value)) {
                return back()->withErrors(["config.{$field}" => "Le champ {$config['label']} est requis"]);
            }
            
            if (!empty($value)) {
                $configuration[$field] = $value;
            }
        }

        $gateway = PaymentGateway::create([
            'name' => $request->gateway_type,
            'slug' => \Str::slug($request->display_name),
            'display_name' => $request->display_name,
            'description' => $request->description,
            'icon' => $gatewayConfig['icon'],
            'is_active' => $request->boolean('is_active'),
            'test_mode' => $request->boolean('test_mode', true),
            'order_priority' => $request->integer('order_priority', 0),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'fees_percentage' => $request->input('fees_percentage', 0),
            'fees_fixed' => $request->input('fees_fixed', 0),
            'supported_currencies' => $request->input('supported_currencies', $gatewayConfig['currencies']),
            'configuration' => $configuration
        ]);

        // Tester la configuration si la passerelle est activée
        if ($gateway->is_active) {
            $isValid = $this->paymentManager->validateGatewayConfiguration($gateway);
            
            if (!$isValid) {
                $gateway->update(['is_active' => false]);
                return redirect()->route('admin.payment-gateways.index')
                    ->with('warning', 'Passerelle créée mais désactivée car la configuration est invalide');
            }
        }

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle de paiement créée avec succès');
    }

    /**
     * Afficher les détails d'une passerelle
     */
    public function show(PaymentGateway $paymentGateway)
    {
        $stats = [
            'total_payments' => $paymentGateway->payments()->count(),
            'successful_payments' => $paymentGateway->payments()->successful()->count(),
            'total_amount' => $paymentGateway->payments()->successful()->sum('amount'),
            'total_fees' => $paymentGateway->payments()->successful()->sum('fees'),
        ];

        $recentPayments = $paymentGateway->payments()
            ->with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.payment-gateways.show', compact('paymentGateway', 'stats', 'recentPayments'));
    }

    /**
     * Afficher le formulaire d'édition d'une passerelle
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        $configFields = $this->paymentManager->getGatewayConfigFields($paymentGateway->name);
        $availableGateways = PaymentGateway::getAvailableGateways();
        $gatewayInfo = $availableGateways[$paymentGateway->name] ?? null;

        return view('admin.payment-gateways.edit', compact('paymentGateway', 'configFields', 'gatewayInfo'));
    }

    /**
     * Mettre à jour une passerelle de paiement
     */
    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'test_mode' => 'boolean',
            'order_priority' => 'integer|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'fees_percentage' => 'nullable|numeric|min:0|max:100',
            'fees_fixed' => 'nullable|numeric|min:0',
            'supported_currencies' => 'array',
            'supported_currencies.*' => 'string|in:XOF,USD,EUR'
        ]);

        // Valider la configuration spécifique à la passerelle
        $configFields = $this->paymentManager->getGatewayConfigFields($paymentGateway->name);
        $configuration = $paymentGateway->configuration ?? [];
        
        foreach ($configFields as $field => $config) {
            $value = $request->input("config.{$field}");
            
            if ($config['required'] && empty($value) && empty($configuration[$field])) {
                return back()->withErrors(["config.{$field}" => "Le champ {$config['label']} est requis"]);
            }
            
            if (!empty($value)) {
                $configuration[$field] = $value;
            }
        }

        $wasActive = $paymentGateway->is_active;
        $willBeActive = $request->boolean('is_active');

        $paymentGateway->update([
            'display_name' => $request->display_name,
            'description' => $request->description,
            'is_active' => $willBeActive,
            'test_mode' => $request->boolean('test_mode'),
            'order_priority' => $request->integer('order_priority'),
            'min_amount' => $request->input('min_amount'),
            'max_amount' => $request->input('max_amount'),
            'fees_percentage' => $request->input('fees_percentage'),
            'fees_fixed' => $request->input('fees_fixed'),
            'supported_currencies' => $request->input('supported_currencies'),
            'configuration' => $configuration
        ]);

        // Tester la configuration si la passerelle est activée
        if ($willBeActive && (!$wasActive || $request->has('config'))) {
            $isValid = $this->paymentManager->validateGatewayConfiguration($paymentGateway);
            
            if (!$isValid) {
                $paymentGateway->update(['is_active' => false]);
                return back()->with('error', 'Configuration invalide. La passerelle a été désactivée.');
            }
        }

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle mise à jour avec succès');
    }

    /**
     * Supprimer une passerelle de paiement
     */
    public function destroy(PaymentGateway $paymentGateway)
    {
        // Vérifier s'il y a des paiements associés
        if ($paymentGateway->payments()->exists()) {
            return back()->with('error', 'Impossible de supprimer une passerelle ayant des paiements associés');
        }

        $paymentGateway->delete();

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelle supprimée avec succès');
    }

    /**
     * Tester la configuration d'une passerelle
     */
    public function test(PaymentGateway $paymentGateway)
    {
        try {
            $isValid = $this->paymentManager->validateGatewayConfiguration($paymentGateway);
            
            if ($isValid) {
                return response()->json([
                    'success' => true,
                    'message' => 'Configuration valide'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration invalide'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Basculer l'état actif/inactif d'une passerelle
     */
    public function toggle(PaymentGateway $paymentGateway)
    {
        $newStatus = !$paymentGateway->is_active;
        
        // Si on active la passerelle, tester d'abord la configuration
        if ($newStatus) {
            $isValid = $this->paymentManager->validateGatewayConfiguration($paymentGateway);
            
            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible d\'activer: configuration invalide'
                ]);
            }
        }

        $paymentGateway->update(['is_active' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => $newStatus ? 'Passerelle activée' : 'Passerelle désactivée',
            'is_active' => $newStatus
        ]);
    }

    /**
     * Définir une passerelle comme passerelle par défaut
     */
    public function setDefault(PaymentGateway $paymentGateway)
    {
        if (!$paymentGateway->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de définir comme défaut: passerelle inactive'
            ]);
        }

        // Désactiver toutes les autres passerelles par défaut
        PaymentGateway::where('is_default', true)->update(['is_default' => false]);
        
        // Activer celle-ci comme défaut
        $paymentGateway->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Passerelle définie comme défaut'
        ]);
    }

    /**
     * Installer les passerelles par défaut
     */
    public function installDefaults()
    {
        $this->paymentManager->installDefaultGateways();

        return redirect()->route('admin.payment-gateways.index')
            ->with('success', 'Passerelles par défaut installées');
    }
}
