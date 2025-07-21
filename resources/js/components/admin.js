/**
 * AfriCode - JavaScript pour l'espace Admin
 */

class AdminInterface {
    constructor() {
        this.sidebar = document.getElementById('adminSidebar');
        this.toggleIcon = document.getElementById('adminToggleIcon');
        this.sidebarOverlay = document.getElementById('adminSidebarOverlay');
        this.init();
    }

    init() {
        this.initSidebar();
        this.initMobileEvents();
        this.initAOS();
        this.initCharts();
        this.initNotifications();
        this.initTabs();
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
        const toggleButton = document.querySelector('.admin-sidebar-toggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', () => this.toggleSidebar());
        }
    }

    toggleSidebar() {
        if (!this.sidebar) return;

        const body = document.body;
        const mainContent = document.querySelector('.admin-main-content');
        
        this.sidebar.classList.toggle('collapsed');
        
        // Ajuster le contenu principal
        if (this.sidebar.classList.contains('collapsed')) {
            if (mainContent) {
                mainContent.classList.add('sidebar-collapsed');
            }
            body.classList.add('sidebar-collapsed');
            if (this.toggleIcon) {
                this.toggleIcon.classList.remove('fa-chevron-left');
                this.toggleIcon.classList.add('fa-chevron-right');
            }
        } else {
            if (mainContent) {
                mainContent.classList.remove('sidebar-collapsed');
            }
            body.classList.remove('sidebar-collapsed');
            if (this.toggleIcon) {
                this.toggleIcon.classList.remove('fa-chevron-right');
                this.toggleIcon.classList.add('fa-chevron-left');
            }
        }
        
        // Sauvegarder l'état
        localStorage.setItem('adminSidebarCollapsed', this.sidebar.classList.contains('collapsed'));
    }

    restoreSidebarState() {
        const isCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
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
        const mobileToggle = document.querySelector('.admin-mobile-toggle');
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => this.openSidebar());
        }

        // Overlay click
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', () => this.closeSidebar());
        }

        // Fermer sidebar au clic sur un lien (mobile)
        document.querySelectorAll('.admin-nav-link').forEach(link => {
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

    initCharts() {
        // Initialiser les graphiques Chart.js
        this.initUsersChart();
        this.initCoursesChart();
        this.initRevenueChart();
    }

    initUsersChart() {
        const ctx = document.getElementById('usersChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Nouveaux utilisateurs',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#1EA38B',
                    backgroundColor: 'rgba(30, 163, 139, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#E9ECEF'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    initCoursesChart() {
        const ctx = document.getElementById('coursesChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Publiés', 'Brouillons', 'Archivés'],
                datasets: [{
                    data: [65, 25, 10],
                    backgroundColor: [
                        '#1EA38B',
                        '#FF8E2A',
                        '#6C757D'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    initRevenueChart() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Revenus (€)',
                    data: [1200, 1900, 800, 1500, 2000, 1800],
                    backgroundColor: '#FF8E2A',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#E9ECEF'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    initNotifications() {
        const notificationBtn = document.querySelector('.admin-notifications');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', () => {
                // Simuler l'affichage des notifications
                this.showNotifications();
            });
        }
    }

    showNotifications() {
        // Créer un dropdown de notifications
        const existingDropdown = document.querySelector('.admin-notifications-dropdown');
        if (existingDropdown) {
            existingDropdown.remove();
            return;
        }

        const dropdown = document.createElement('div');
        dropdown.className = 'admin-notifications-dropdown';
        dropdown.innerHTML = `
            <div class="admin-notifications-header">
                <h6>Notifications</h6>
                <span class="admin-notifications-count">3</span>
            </div>
            <div class="admin-notifications-list">
                <div class="admin-notification-item">
                    <div class="admin-notification-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="admin-notification-content">
                        <p>Nouvel utilisateur inscrit</p>
                        <small>Il y a 5 minutes</small>
                    </div>
                </div>
                <div class="admin-notification-item">
                    <div class="admin-notification-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="admin-notification-content">
                        <p>Nouveau cours publié</p>
                        <small>Il y a 1 heure</small>
                    </div>
                </div>
                <div class="admin-notification-item">
                    <div class="admin-notification-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="admin-notification-content">
                        <p>Nouveau paiement reçu</p>
                        <small>Il y a 2 heures</small>
                    </div>
                </div>
            </div>
            <div class="admin-notifications-footer">
                <a href="#">Voir toutes les notifications</a>
            </div>
        `;

        const notificationBtn = document.querySelector('.admin-notifications');
        notificationBtn.parentNode.appendChild(dropdown);

        // Fermer au clic extérieur
        setTimeout(() => {
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.admin-notifications') && !e.target.closest('.admin-notifications-dropdown')) {
                    dropdown.remove();
                }
            }, { once: true });
        }, 100);
    }

    initTabs() {
        const tabButtons = document.querySelectorAll('.admin-tab-btn');
        const tabPanes = document.querySelectorAll('.admin-tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetTab = button.getAttribute('data-tab');
                
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                
                // Add active class to clicked button and corresponding pane
                button.classList.add('active');
                const targetPane = document.getElementById(targetTab);
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            });
        });
    }

    // Méthodes utilitaires
    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `admin-toast admin-toast-${type}`;
        toast.innerHTML = `
            <div class="admin-toast-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            </div>
            <button class="admin-toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        document.body.appendChild(toast);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    confirmAction(message, callback) {
        const modal = document.createElement('div');
        modal.className = 'admin-confirm-modal';
        modal.innerHTML = `
            <div class="admin-confirm-backdrop"></div>
            <div class="admin-confirm-dialog">
                <div class="admin-confirm-header">
                    <h6>Confirmation</h6>
                </div>
                <div class="admin-confirm-body">
                    <p>${message}</p>
                </div>
                <div class="admin-confirm-footer">
                    <button class="admin-btn admin-btn-secondary" onclick="this.closest('.admin-confirm-modal').remove()">
                        Annuler
                    </button>
                    <button class="admin-btn admin-btn-primary" onclick="window.adminConfirmCallback(); this.closest('.admin-confirm-modal').remove()">
                        Confirmer
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        window.adminConfirmCallback = callback;
    }
}

// Fonctions globales pour compatibilité
function toggleAdminSidebar() {
    if (window.adminInterface) {
        window.adminInterface.toggleSidebar();
    }
}

function openAdminSidebar() {
    if (window.adminInterface) {
        window.adminInterface.openSidebar();
    }
}

function closeAdminSidebar() {
    if (window.adminInterface) {
        window.adminInterface.closeSidebar();
    }
}

function showAdminToast(message, type = 'success') {
    if (window.adminInterface) {
        window.adminInterface.showToast(message, type);
    }
}

function confirmAdminAction(message, callback) {
    if (window.adminInterface) {
        window.adminInterface.confirmAction(message, callback);
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    window.adminInterface = new AdminInterface();
});
