<header class="admin-header">
    <div class="header-content">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="page-title">@yield('page-title', 'Tableau de bord')</h1>
        </div>
        
        <div class="header-right">
            <div class="header-notifications">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
            </div>
            
            <div class="header-user">
                <div class="user-info">
                    <img src="{{ Auth::user()->avatar ?? '/images/default-avatar.png' }}" alt="Avatar" class="user-avatar">
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">Administrateur</span>
                    </div>
                </div>
                
                <div class="user-dropdown">
                    <button class="dropdown-toggle">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user"></i> Mon profil
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i> Paramètres
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-btn">
                                <i class="fas fa-sign-out-alt"></i> Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
.admin-header {
    background: white;
    border-bottom: 1px solid #e0e0e0;
    padding: 0 20px;
    position: sticky;
    top: 0;
    z-index: 999;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 60px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.sidebar-toggle {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #666;
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.sidebar-toggle:hover {
    background: #f0f0f0;
}

.page-title {
    margin: 0;
    font-size: 1.5rem;
    color: #333;
    font-weight: 600;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.notification-btn {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #666;
    cursor: pointer;
    position: relative;
    padding: 8px;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.notification-btn:hover {
    background: #f0f0f0;
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #e74c3c;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-user {
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #333;
    font-size: 0.9rem;
}

.user-role {
    font-size: 0.8rem;
    color: #666;
}

.user-dropdown {
    position: relative;
}

.dropdown-toggle {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.dropdown-toggle:hover {
    background: #f0f0f0;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    min-width: 180px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
}

.user-dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    transition: background 0.3s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 0.9rem;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

.dropdown-divider {
    height: 1px;
    background: #e0e0e0;
    margin: 5px 0;
}

.logout-btn {
    color: #e74c3c;
}

.logout-btn:hover {
    background: #fef2f2;
}

@media (max-width: 768px) {
    .header-content {
        padding: 0 10px;
    }
    
    .user-details {
        display: none;
    }
    
    .page-title {
        font-size: 1.2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
    }
});
</script>
