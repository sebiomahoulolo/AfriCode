/**
 * AfriCode - JavaScript pour l'espace Formateur
 */

class FormateurInterface {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.toggleIcon = document.getElementById('toggleIcon');
        this.sidebarOverlay = document.getElementById('sidebarOverlay');
        this.init();
    }

    init() {
        this.initSidebar();
        this.initMobileEvents();
        this.initAOS();
        this.restoreSidebarState();
    }

    initAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 600,
                easing: 'ease-out-cubic',
                once: true
            });
        }
    }

    initSidebar() {
        const toggleButton = document.querySelector('.sidebar-toggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', () => this.toggleSidebar());
        }
    }

    toggleSidebar() {
        if (!this.sidebar || !this.toggleIcon) return;

        const body = document.body;
        this.sidebar.classList.toggle('collapsed');
        
        // Gérer l'icône et la position du toggle
        if (this.sidebar.classList.contains('collapsed')) {
            body.classList.add('sidebar-collapsed');
            this.toggleIcon.classList.remove('fa-chevron-left');
            this.toggleIcon.classList.add('fa-chevron-right');
        } else {
            body.classList.remove('sidebar-collapsed');
            this.toggleIcon.classList.remove('fa-chevron-right');
            this.toggleIcon.classList.add('fa-chevron-left');
        }
        
        // Sauvegarder l'état
        localStorage.setItem('sidebarCollapsed', this.sidebar.classList.contains('collapsed'));
    }

    restoreSidebarState() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed && this.sidebar && this.toggleIcon) {
            const body = document.body;
            
            this.sidebar.classList.add('collapsed');
            body.classList.add('sidebar-collapsed');
            this.toggleIcon.classList.remove('fa-chevron-left');
            this.toggleIcon.classList.add('fa-chevron-right');
        }
    }

    openSidebar() {
        if (this.sidebar && this.sidebarOverlay) {
            this.sidebar.classList.add('show');
            this.sidebarOverlay.classList.add('show');
        }
    }

    closeSidebar() {
        if (this.sidebar && this.sidebarOverlay) {
            this.sidebar.classList.remove('show');
            this.sidebarOverlay.classList.remove('show');
        }
    }

    initMobileEvents() {
        // Mobile toggle button
        const mobileToggle = document.querySelector('.mobile-toggle');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => this.openSidebar());
        }

        // Overlay click
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', () => this.closeSidebar());
        }

        // Fermer sidebar au clic sur un lien (mobile)
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    this.closeSidebar();
                }
            });
        });

        // Auto-close sidebar on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                this.closeSidebar();
            }
        });
    }
}

// Fonctions globales pour compatibilité
function toggleSidebar() {
    if (window.formateurInterface) {
        window.formateurInterface.toggleSidebar();
    }
}

function openSidebar() {
    if (window.formateurInterface) {
        window.formateurInterface.openSidebar();
    }
}

function closeSidebar() {
    if (window.formateurInterface) {
        window.formateurInterface.closeSidebar();
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    window.formateurInterface = new FormateurInterface();
});
