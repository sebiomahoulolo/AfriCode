/**
 * AfriCode - Scripts responsives pour l'espace Formateur
 * Améliore l'expérience utilisateur sur mobile et tablette
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Détection de la taille d'écran
    const isMobile = window.innerWidth <= 768;
    const isTablet = window.innerWidth <= 992 && window.innerWidth > 768;
    
    // ===== AMÉLIORATION DU SIDEBAR MOBILE =====
    function initMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const mobileToggle = document.querySelector('.mobile-toggle');
        
        if (sidebar && overlay) {
            // Fermeture automatique lors du redimensionnement
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                }
            });
            
            // Fermeture par swipe (glissement vers la gauche)
            let startX, currentX;
            sidebar.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
            });
            
            sidebar.addEventListener('touchmove', function(e) {
                currentX = e.touches[0].clientX;
                const diff = startX - currentX;
                
                if (diff > 50) { // Swipe vers la gauche de plus de 50px
                    closeSidebar();
                }
            });
        }
    }
    
    // ===== AMÉLIORATION DES TABLES RESPONSIVE =====
    function enhanceTablesResponsive() {
        const tables = document.querySelectorAll('.table:not(.table-responsive *)');
        
        tables.forEach(table => {
            if (!table.closest('.table-responsive')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'table-responsive';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
            
            // Ajout d'attributs data pour mobile
            if (isMobile) {
                const headers = table.querySelectorAll('th');
                const rows = table.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    cells.forEach((cell, index) => {
                        if (headers[index]) {
                            cell.setAttribute('data-label', headers[index].textContent.trim());
                        }
                    });
                });
            }
        });
    }
    
    // ===== AMÉLIORATION DES FORMULAIRES =====
    function enhanceFormsResponsive() {
        // Ajustement automatique de la hauteur des textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });
        
        // Amélioration des champs de fichier sur mobile
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            if (isMobile) {
                input.addEventListener('change', function() {
                    const fileName = this.files[0]?.name;
                    if (fileName) {
                        // Affichage du nom du fichier
                        let display = this.nextElementSibling;
                        if (!display || !display.classList.contains('file-name-display')) {
                            display = document.createElement('small');
                            display.className = 'file-name-display text-muted mt-1 d-block';
                            this.parentNode.insertBefore(display, this.nextSibling);
                        }
                        display.textContent = `Fichier sélectionné: ${fileName}`;
                    }
                });
            }
        });
    }
    
    // ===== AMÉLIORATION DES MODALS =====
    function enhanceModalsResponsive() {
        const modals = document.querySelectorAll('.modal');
        
        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                // Focus automatique sur le premier champ de saisie sur desktop
                if (!isMobile) {
                    const firstInput = this.querySelector('input, textarea, select');
                    if (firstInput) {
                        firstInput.focus();
                    }
                }
                
                // Ajustement de la hauteur sur mobile
                if (isMobile) {
                    const modalDialog = this.querySelector('.modal-dialog');
                    if (modalDialog) {
                        modalDialog.style.margin = '0.5rem';
                        modalDialog.style.maxHeight = 'calc(100vh - 1rem)';
                    }
                }
            });
        });
    }
    
    // ===== AMÉLIORATION DES TOOLTIPS ET POPOVERS =====
    function enhanceTooltipsResponsive() {
        // Désactiver les tooltips sur mobile pour éviter les problèmes de touch
        if (isMobile) {
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltips.forEach(element => {
                element.removeAttribute('data-bs-toggle');
                element.removeAttribute('title');
            });
        }
    }
    
    // ===== AMÉLIORATION DES CARDS AVEC ACTIONS =====
    function enhanceCardsResponsive() {
        const cards = document.querySelectorAll('.card-modern, .course-card-modern');
        
        cards.forEach(card => {
            if (isMobile) {
                // Rendre les cards plus accessibles au touch
                card.style.minHeight = '44px';
                
                // Gérer les actions de carte
                const actionButtons = card.querySelectorAll('.btn, .nav-link');
                actionButtons.forEach(button => {
                    button.style.minHeight = '44px';
                    button.style.minWidth = '44px';
                });
            }
        });
    }
    
    // ===== AMÉLIORATION DE LA NAVIGATION =====
    function enhanceNavigationResponsive() {
        // Amélioration des onglets sur mobile
        const navTabs = document.querySelectorAll('.nav-tabs');
        navTabs.forEach(nav => {
            if (isMobile) {
                nav.classList.add('flex-column');
                const links = nav.querySelectorAll('.nav-link');
                links.forEach(link => {
                    link.style.textAlign = 'left';
                    link.style.borderRadius = '0.375rem';
                    link.style.marginBottom = '0.25rem';
                });
            }
        });
    }
    
    // ===== AMÉLIORATION DES DRAG & DROP =====
    function enhanceDragDropResponsive() {
        // Désactiver le drag & drop sur mobile et remplacer par des boutons
        if (isMobile) {
            const dragElements = document.querySelectorAll('[draggable="true"]');
            dragElements.forEach(element => {
                element.removeAttribute('draggable');
                
                // Ajouter des boutons de réorganisation si nécessaire
                const moveUpBtn = document.createElement('button');
                moveUpBtn.className = 'btn btn-sm btn-outline-secondary me-1';
                moveUpBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
                moveUpBtn.title = 'Déplacer vers le haut';
                
                const moveDownBtn = document.createElement('button');
                moveDownBtn.className = 'btn btn-sm btn-outline-secondary';
                moveDownBtn.innerHTML = '<i class="fas fa-arrow-down"></i>';
                moveDownBtn.title = 'Déplacer vers le bas';
                
                const actionContainer = element.querySelector('.actions, .btn-group');
                if (actionContainer) {
                    actionContainer.prepend(moveUpBtn);
                    actionContainer.prepend(moveDownBtn);
                }
            });
        }
    }
    
    // ===== AMÉLIORATION DE L'ACCESSIBILITÉ =====
    function enhanceAccessibility() {
        // Améliorer la navigation au clavier
        const focusableElements = document.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        focusableElements.forEach(element => {
            element.addEventListener('focus', function() {
                this.style.outline = '2px solid var(--africode-primary)';
                this.style.outlineOffset = '2px';
            });
            
            element.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
            });
        });
        
        // Améliorer les contrastes pour l'accessibilité
        if (window.matchMedia && window.matchMedia('(prefers-contrast: high)').matches) {
            document.body.classList.add('high-contrast');
        }
    }
    
    // ===== AMÉLIORATION DES ANIMATIONS =====
    function enhanceAnimationsResponsive() {
        // Réduire les animations sur mobile pour économiser la batterie
        if (isMobile || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            const style = document.createElement('style');
            style.textContent = `
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // ===== GESTION DES ERREURS DE RÉSEAU =====
    function handleNetworkErrors() {
        // Gestion des formulaires en cas de perte de connexion
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!navigator.onLine) {
                    e.preventDefault();
                    alert('Connexion internet requise pour envoyer le formulaire. Veuillez vérifier votre connexion.');
                    return false;
                }
            });
        });
        
        // Indicateur de statut de connexion
        function updateOnlineStatus() {
            const statusIndicator = document.getElementById('connection-status');
            if (statusIndicator) {
                if (navigator.onLine) {
                    statusIndicator.className = 'alert alert-success d-none';
                    statusIndicator.textContent = 'Connexion rétablie';
                } else {
                    statusIndicator.className = 'alert alert-warning';
                    statusIndicator.textContent = 'Connexion internet perdue';
                }
            }
        }
        
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
    }
    
    // ===== INITIALISATION =====
    function init() {
        initMobileSidebar();
        enhanceTablesResponsive();
        enhanceFormsResponsive();
        enhanceModalsResponsive();
        enhanceTooltipsResponsive();
        enhanceCardsResponsive();
        enhanceNavigationResponsive();
        enhanceDragDropResponsive();
        enhanceAccessibility();
        enhanceAnimationsResponsive();
        handleNetworkErrors();
        
        console.log('AfriCode Formateur - Améliorations responsive initialisées');
    }
    
    // Initialiser
    init();
    
    // Réinitialiser lors du redimensionnement
    window.addEventListener('resize', function() {
        // Débounce pour éviter trop d'appels
        clearTimeout(window.resizeTimeout);
        window.resizeTimeout = setTimeout(init, 250);
    });
});

// ===== FONCTIONS GLOBALES UTILES =====

// Fonction pour détecter le type d'appareil
window.AfriCode = window.AfriCode || {};
window.AfriCode.device = {
    isMobile: () => window.innerWidth <= 768,
    isTablet: () => window.innerWidth <= 992 && window.innerWidth > 768,
    isDesktop: () => window.innerWidth > 992,
    hasTouch: () => 'ontouchstart' in window || navigator.maxTouchPoints > 0
};

// Fonction pour basculer en mode sombre (bonus)
window.AfriCode.toggleDarkMode = function() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
};

// Restaurer le mode sombre si activé
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark-mode');
}
