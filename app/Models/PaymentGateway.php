<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'display_name',
        'description',
        'icon',
        'is_active',
        'is_default',
        'supported_currencies',
        'configuration',
        'test_mode',
        'order_priority',
        'min_amount',
        'max_amount',
        'fees_percentage',
        'fees_fixed',
        'success_message',
        'error_message'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'test_mode' => 'boolean',
        'supported_currencies' => 'array',
        'configuration' => 'array',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'fees_percentage' => 'decimal:2',
        'fees_fixed' => 'decimal:2',
    ];

    // Relations
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeForCurrency($query, $currency)
    {
        return $query->whereJsonContains('supported_currencies', $currency);
    }

    public function scopeForAmount($query, $amount)
    {
        return $query->where(function ($q) use ($amount) {
            $q->where('min_amount', '<=', $amount)
              ->where(function ($subQ) use ($amount) {
                  $subQ->whereNull('max_amount')
                       ->orWhere('max_amount', '>=', $amount);
              });
        });
    }

    // Méthodes utilitaires
    public function isAvailableForCurrency($currency)
    {
        return in_array($currency, $this->supported_currencies ?? []);
    }

    public function isAvailableForAmount($amount)
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }
        
        return true;
    }

    public function calculateFees($amount)
    {
        $percentageFee = ($amount * $this->fees_percentage) / 100;
        $totalFees = $percentageFee + $this->fees_fixed;
        
        return round($totalFees, 2);
    }

    public function getConfigValue($key, $default = null)
    {
        return $this->configuration[$key] ?? $default;
    }

    public function setConfigValue($key, $value)
    {
        $config = $this->configuration ?? [];
        $config[$key] = $value;
        $this->configuration = $config;
        return $this;
    }

    // Méthodes statiques pour les gateways courants
    public static function getAvailableGateways()
    {
        return [
            'stripe' => [
                'name' => 'Stripe',
                'description' => 'Paiements par carte bancaire international',
                'icon' => 'fab fa-stripe',
                'currencies' => ['USD', 'EUR', 'XOF'],
                'config_fields' => [
                    'publishable_key' => 'Clé publique',
                    'secret_key' => 'Clé secrète',
                    'webhook_secret' => 'Secret webhook'
                ]
            ],
            'paypal' => [
                'name' => 'PayPal',
                'description' => 'Paiements PayPal et cartes bancaires',
                'icon' => 'fab fa-paypal',
                'currencies' => ['USD', 'EUR'],
                'config_fields' => [
                    'client_id' => 'Client ID',
                    'client_secret' => 'Client Secret',
                    'webhook_id' => 'Webhook ID'
                ]
            ],
            'orange_money' => [
                'name' => 'Orange Money',
                'description' => 'Paiements mobiles Orange Money',
                'icon' => 'fas fa-mobile-alt',
                'currencies' => ['XOF'],
                'config_fields' => [
                    'merchant_key' => 'Clé marchand',
                    'merchant_secret' => 'Secret marchand',
                    'api_url' => 'URL API'
                ]
            ],
            'mtn_momo' => [
                'name' => 'MTN Mobile Money',
                'description' => 'Paiements mobiles MTN',
                'icon' => 'fas fa-mobile-alt',
                'currencies' => ['XOF'],
                'config_fields' => [
                    'collection_id' => 'Collection ID',
                    'api_key' => 'Clé API',
                    'api_secret' => 'Secret API'
                ]
            ],
            'moov_money' => [
                'name' => 'Moov Money',
                'description' => 'Paiements mobiles Moov',
                'icon' => 'fas fa-mobile-alt',
                'currencies' => ['XOF'],
                'config_fields' => [
                    'merchant_id' => 'ID Marchand',
                    'api_key' => 'Clé API',
                    'secret_key' => 'Clé secrète'
                ]
            ],
            'wave' => [
                'name' => 'Wave',
                'description' => 'Paiements mobiles Wave',
                'icon' => 'fas fa-wave-square',
                'currencies' => ['XOF'],
                'config_fields' => [
                    'api_key' => 'Clé API',
                    'secret_key' => 'Clé secrète',
                    'webhook_secret' => 'Secret webhook'
                ]
            ],
            'cinetpay' => [
                'name' => 'CinetPay',
                'description' => 'Paiements mobiles et bancaires',
                'icon' => 'fas fa-credit-card',
                'currencies' => ['XOF', 'USD', 'EUR'],
                'config_fields' => [
                    'api_key' => 'Clé API',
                    'site_id' => 'Site ID',
                    'secret_key' => 'Clé secrète'
                ]
            ],
            'fedapay' => [
                'name' => 'FedaPay',
                'description' => 'Paiements mobiles et bancaires en Afrique',
                'icon' => 'fas fa-university',
                'currencies' => ['XOF'],
                'config_fields' => [
                    'public_key' => 'Clé publique',
                    'secret_key' => 'Clé secrète',
                    'environment' => 'Environnement'
                ]
            ]
        ];
    }
}
