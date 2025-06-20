// AfriCode Visibility Validator
// Script de validation automatique des corrections de visibilité

class AfriCodeVisibilityValidator {
    constructor() {
        this.errors = [];
        this.warnings = [];
        this.passed = [];
        this.testElements = [
            '.btn-modern',
            '.btn-outline-modern',
            '.progress-bar-modern',
            '.progress-modern',
            '.badge-modern',
            '.sidebar-toggle-btn',
            '.mobile-toggle',
            '.action-card',
            '.course-card',
            '.modern-card',
            '.module-item.active',
            '.lesson-link.active',
            '.course-status'
        ];
    }

    log(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const icon = {
            'error': '❌',
            'warning': '⚠️',
            'success': '✅',
            'info': 'ℹ️'
        }[type] || 'ℹ️';
        
        console.log(`[${timestamp}] ${icon} ${message}`);
    }

    checkElementVisibility(selector) {
        const elements = document.querySelectorAll(selector);
        
        if (elements.length === 0) {
            this.warnings.push(`Aucun élément trouvé pour: ${selector}`);
            return { found: false, visible: 0, total: 0 };
        }

        let visibleCount = 0;
        elements.forEach((el, index) => {
            const style = window.getComputedStyle(el);
            const rect = el.getBoundingClientRect();
            
            const isVisible = (
                style.opacity !== '0' &&
                style.visibility !== 'hidden' &&
                style.display !== 'none' &&
                rect.width > 0 &&
                rect.height > 0
            );

            if (isVisible) {
                visibleCount++;
            } else {
                this.errors.push(`${selector}[${index}] est invisible`);
            }
        });

        return { found: true, visible: visibleCount, total: elements.length };
    }

    checkAfriCodeColors(selector) {
        const elements = document.querySelectorAll(selector);
        const afriCodeColors = ['#1EA38B', '#FF8E2A', '#E32D31', '#27B371'];
        
        elements.forEach((el, index) => {
            const style = window.getComputedStyle(el);
            const bgColor = style.backgroundColor;
            const color = style.color;
            const borderColor = style.borderColor;
            
            // Convertir rgb en hex pour comparaison
            const hasAfriCodeColor = (
                this.hasAfriCodeColor(bgColor, afriCodeColors) ||
                this.hasAfriCodeColor(color, afriCodeColors) ||
                this.hasAfriCodeColor(borderColor, afriCodeColors)
            );

            if (!hasAfriCodeColor && el.classList.contains('btn-modern')) {
                this.warnings.push(`${selector}[${index}] pourrait ne pas utiliser les couleurs AfriCode`);
            }
        });
    }

    hasAfriCodeColor(color, afriCodeColors) {
        if (!color || color === 'transparent' || color === 'rgba(0, 0, 0, 0)') return false;
        
        // Simple vérification si la couleur contient des valeurs proches des couleurs AfriCode
        return afriCodeColors.some(afriColor => {
            // Conversion basique - en production, utiliser une bibliothèque de couleurs
            return color.includes('30, 163, 139') || // #1EA38B en RGB
                   color.includes('255, 142, 42') || // #FF8E2A en RGB
                   color.includes('227, 45, 49') ||  // #E32D31 en RGB
                   color.includes('39, 179, 113');   // #27B371 en RGB
        });
    }

    checkInteractions() {
        const interactiveElements = document.querySelectorAll('.btn-modern, .btn-outline-modern, .sidebar-toggle-btn');
        
        interactiveElements.forEach((el, index) => {
            // Vérifier les états CSS hover/focus
            const hasHoverStyles = this.hasHoverStyles(el);
            const hasFocusStyles = this.hasFocusStyles(el);
            
            if (!hasHoverStyles) {
                this.warnings.push(`Élément interactif [${index}] sans styles hover détectés`);
            }
            
            if (!hasFocusStyles) {
                this.warnings.push(`Élément interactif [${index}] sans styles focus détectés`);
            }
        });
    }

    hasHoverStyles(element) {
        // Vérification basique des styles hover via les règles CSS
        const sheets = Array.from(document.styleSheets);
        for (let sheet of sheets) {
            try {
                const rules = Array.from(sheet.cssRules || sheet.rules || []);
                for (let rule of rules) {
                    if (rule.selectorText && rule.selectorText.includes(':hover')) {
                        const classes = Array.from(element.classList);
                        if (classes.some(cls => rule.selectorText.includes(cls))) {
                            return true;
                        }
                    }
                }
            } catch (e) {
                // Ignorer les erreurs CORS des feuilles de style externes
            }
        }
        return false;
    }

    hasFocusStyles(element) {
        // Similaire à hasHoverStyles mais pour :focus
        const sheets = Array.from(document.styleSheets);
        for (let sheet of sheets) {
            try {
                const rules = Array.from(sheet.cssRules || sheet.rules || []);
                for (let rule of rules) {
                    if (rule.selectorText && rule.selectorText.includes(':focus')) {
                        const classes = Array.from(element.classList);
                        if (classes.some(cls => rule.selectorText.includes(cls))) {
                            return true;
                        }
                    }
                }
            } catch (e) {
                // Ignorer les erreurs CORS
            }
        }
        return false;
    }

    runAllTests() {
        this.log('🚀 Démarrage de la validation AfriCode...', 'info');
        this.log('🔍 Vérification des éléments critiques...', 'info');

        // Test de visibilité pour tous les éléments critiques
        this.testElements.forEach(selector => {
            const result = this.checkElementVisibility(selector);
            if (result.found) {
                if (result.visible === result.total) {
                    this.passed.push(`${selector}: ${result.visible}/${result.total} visibles`);
                    this.log(`${selector}: ${result.visible}/${result.total} visibles`, 'success');
                } else {
                    this.log(`${selector}: ${result.visible}/${result.total} visibles`, 'error');
                }
            } else {
                this.log(`${selector}: aucun élément trouvé`, 'warning');
            }
        });

        // Test des couleurs AfriCode
        this.log('🎨 Vérification des couleurs AfriCode...', 'info');
        ['.btn-modern', '.progress-bar-modern', '.badge-modern'].forEach(selector => {
            this.checkAfriCodeColors(selector);
        });

        // Test des interactions
        this.log('🖱️ Vérification des interactions...', 'info');
        this.checkInteractions();

        // Rapport final
        this.generateReport();
    }

    generateReport() {
        console.log('\n' + '='.repeat(60));
        console.log('📊 RAPPORT DE VALIDATION AFRICODE');
        console.log('='.repeat(60));
        
        console.log(`\n✅ Tests réussis: ${this.passed.length}`);
        this.passed.forEach(test => console.log(`   ✓ ${test}`));
        
        if (this.warnings.length > 0) {
            console.log(`\n⚠️ Avertissements: ${this.warnings.length}`);
            this.warnings.forEach(warning => console.log(`   ⚠ ${warning}`));
        }
        
        if (this.errors.length > 0) {
            console.log(`\n❌ Erreurs: ${this.errors.length}`);
            this.errors.forEach(error => console.log(`   ✗ ${error}`));
        }

        // Score global
        const totalTests = this.passed.length + this.errors.length;
        const score = totalTests > 0 ? Math.round((this.passed.length / totalTests) * 100) : 0;
        
        console.log(`\n🎯 Score de visibilité: ${score}%`);
        
        if (score >= 95) {
            console.log('🎉 EXCELLENT! Toutes les corrections AfriCode fonctionnent parfaitement!');
        } else if (score >= 80) {
            console.log('👍 BIEN! La plupart des corrections fonctionnent, quelques ajustements mineurs.');
        } else if (score >= 60) {
            console.log('⚠️ MOYEN! Plusieurs éléments nécessitent attention.');
        } else {
            console.log('❌ PROBLÈME! Des corrections importantes sont nécessaires.');
        }

        console.log('\n' + '='.repeat(60));
        
        return {
            score,
            passed: this.passed.length,
            warnings: this.warnings.length,
            errors: this.errors.length,
            total: totalTests
        };
    }

    // Méthode pour tester depuis la console
    static quickTest() {
        const validator = new AfriCodeVisibilityValidator();
        return validator.runAllTests();
    }
}

// Auto-exécution si on est dans un environnement browser
if (typeof window !== 'undefined') {
    // Attendre que le DOM soit chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => AfriCodeVisibilityValidator.quickTest(), 1000);
        });
    } else {
        setTimeout(() => AfriCodeVisibilityValidator.quickTest(), 1000);
    }
    
    // Exposer globalement pour utilisation manuelle
    window.AfriCodeValidator = AfriCodeVisibilityValidator;
}

// Export pour utilisation en module (Node.js)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AfriCodeVisibilityValidator;
}
