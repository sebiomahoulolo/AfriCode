<div class="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-graduation-cap"></i> AfriCode Admin</h3>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link has-submenu" data-toggle="submenu">
                    <i class="fas fa-book"></i>
                    <span>Cours</span>
                    <i class="fas fa-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#">Tous les cours</a></li>
                    <li><a href="#">Catégories</a></li>
                    <li><a href="#">Compétences</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link has-submenu" data-toggle="submenu">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                    <i class="fas fa-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#">Étudiants</a></li>
                    <li><a href="#">Formateurs</a></li>
                    <li><a href="#">Administrateurs</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link has-submenu" data-toggle="submenu">
                    <i class="fas fa-credit-card"></i>
                    <span>Paiements</span>
                    <i class="fas fa-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('admin.payment-gateways.index') }}">Passerelles de paiement</a></li>
                    <li><a href="{{ route('admin.payment.test') }}">Test du système</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link has-submenu" data-toggle="submenu">
                    <i class="fas fa-chart-bar"></i>
                    <span>Rapports</span>
                    <i class="fas fa-chevron-down submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="#">Statistiques générales</a></li>
                    <li><a href="#">Revenus</a></li>
                    <li><a href="#">Inscriptions</a></li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<style>
.sidebar {
    width: 250px;
    height: 100vh;
    background: #2c3e50;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
    transition: all 0.3s ease;
}

.sidebar-header {
    padding: 20px;
    background: #34495e;
    color: white;
    text-align: center;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 1.2rem;
}

.sidebar-nav {
    padding: 20px 0;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 5px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #bdc3c7;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link:hover,
.nav-link.active {
    background: #3498db;
    color: white;
}

.nav-link i {
    width: 20px;
    margin-right: 10px;
    text-align: center;
}

.submenu-arrow {
    margin-left: auto !important;
    margin-right: 0 !important;
    transition: transform 0.3s ease;
}

.submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    background: #1a252f;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.nav-item.open .submenu {
    max-height: 200px;
}

.nav-item.open .submenu-arrow {
    transform: rotate(180deg);
}

.submenu a {
    display: block;
    padding: 10px 50px;
    color: #95a5a6;
    text-decoration: none;
    transition: all 0.3s ease;
}

.submenu a:hover {
    background: #2980b9;
    color: white;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 100vh;
    background: #f8f9fa;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer les sous-menus
    const hasSubmenuLinks = document.querySelectorAll('.has-submenu');
    
    hasSubmenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const navItem = this.parentElement;
            navItem.classList.toggle('open');
        });
    });
    
    // Fermer les autres sous-menus quand on en ouvre un
    hasSubmenuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const currentNavItem = this.parentElement;
            
            hasSubmenuLinks.forEach(otherLink => {
                const otherNavItem = otherLink.parentElement;
                if (otherNavItem !== currentNavItem) {
                    otherNavItem.classList.remove('open');
                }
            });
        });
    });
});
</script>
