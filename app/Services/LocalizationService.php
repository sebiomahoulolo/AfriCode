<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LocalizationService
{
    protected $supportedLocales = [
        'fr' => 'Français',
        'en' => 'English',
        'es' => 'Español',
        'de' => 'Deutsch',
        'ar' => 'العربية'
    ];

    protected $rtlLocales = ['ar'];

    public function getSupportedLocales()
    {
        return $this->supportedLocales;
    }

    public function setLocale($locale)
    {
        if (!array_key_exists($locale, $this->supportedLocales)) {
            throw new \Exception('Langue non supportée');
        }

        App::setLocale($locale);
        session()->put('locale', $locale);

        return $locale;
    }

    public function getCurrentLocale()
    {
        return session()->get('locale', Config::get('app.locale'));
    }

    public function isRTL()
    {
        return in_array($this->getCurrentLocale(), $this->rtlLocales);
    }

    public function translate($key, $replace = [])
    {
        return __($key, $replace);
    }

    public function getAccessibilitySettings($user)
    {
        return Cache::remember("user_accessibility_{$user->id}", 3600, function () use ($user) {
            return [
                'font_size' => $user->settings->font_size ?? 'medium',
                'high_contrast' => $user->settings->high_contrast ?? false,
                'screen_reader' => $user->settings->screen_reader ?? false,
                'reduced_motion' => $user->settings->reduced_motion ?? false,
                'color_blind_mode' => $user->settings->color_blind_mode ?? 'none'
            ];
        });
    }

    public function updateAccessibilitySettings($user, $settings)
    {
        $validated = $this->validateAccessibilitySettings($settings);
        
        $user->settings()->update($validated);
        Cache::forget("user_accessibility_{$user->id}");

        return $validated;
    }

    protected function validateAccessibilitySettings($settings)
    {
        return [
            'font_size' => in_array($settings['font_size'], ['small', 'medium', 'large']) 
                ? $settings['font_size'] 
                : 'medium',
            'high_contrast' => (bool) $settings['high_contrast'],
            'screen_reader' => (bool) $settings['screen_reader'],
            'reduced_motion' => (bool) $settings['reduced_motion'],
            'color_blind_mode' => in_array($settings['color_blind_mode'], ['none', 'protanopia', 'deuteranopia', 'tritanopia']) 
                ? $settings['color_blind_mode'] 
                : 'none'
        ];
    }

    public function getAccessibilityCSS($settings)
    {
        $css = [];

        // Taille de police
        switch ($settings['font_size']) {
            case 'small':
                $css[] = 'body { font-size: 14px; }';
                break;
            case 'large':
                $css[] = 'body { font-size: 18px; }';
                break;
            default:
                $css[] = 'body { font-size: 16px; }';
        }

        // Contraste élevé
        if ($settings['high_contrast']) {
            $css[] = '
                body {
                    background-color: #000 !important;
                    color: #fff !important;
                }
                a {
                    color: #ffff00 !important;
                }
                button, .btn {
                    background-color: #fff !important;
                    color: #000 !important;
                    border: 2px solid #fff !important;
                }
            ';
        }

        // Mode daltonisme
        switch ($settings['color_blind_mode']) {
            case 'protanopia':
                $css[] = '
                    body {
                        filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'protanopia\'><feColorMatrix type=\'matrix\' values=\'0.567,0.433,0,0,0 0.558,0.442,0,0,0 0,0.242,0.758,0,0 0,0,0,1,0\'/></filter></svg>#protanopia");
                    }
                ';
                break;
            case 'deuteranopia':
                $css[] = '
                    body {
                        filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'deuteranopia\'><feColorMatrix type=\'matrix\' values=\'0.625,0.375,0,0,0 0.7,0.3,0,0,0 0,0.3,0.7,0,0 0,0,0,1,0\'/></filter></svg>#deuteranopia");
                    }
                ';
                break;
            case 'tritanopia':
                $css[] = '
                    body {
                        filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'tritanopia\'><feColorMatrix type=\'matrix\' values=\'0.95,0.05,0,0,0 0,0.433,0.567,0,0 0,0.475,0.525,0,0 0,0,0,1,0\'/></filter></svg>#tritanopia");
                    }
                ';
                break;
        }

        // Réduction des animations
        if ($settings['reduced_motion']) {
            $css[] = '
                * {
                    animation: none !important;
                    transition: none !important;
                }
            ';
        }

        return implode("\n", $css);
    }

    public function getAccessibilityAttributes($settings)
    {
        $attributes = [];

        if ($settings['screen_reader']) {
            $attributes['aria-live'] = 'polite';
            $attributes['role'] = 'main';
        }

        if ($settings['high_contrast']) {
            $attributes['data-high-contrast'] = 'true';
        }

        return $attributes;
    }
} 