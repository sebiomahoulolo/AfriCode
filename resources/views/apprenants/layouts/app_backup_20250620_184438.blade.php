<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AfriCode - Plateforme d\'apprentissage')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            /* Couleurs AfriCode officielles */
            --primary-color: #F53003;
            --primary-dark: #cc2700;
            --secondary-color: #FF750F;
            --tertiary-color: #1EA38B;
            --accent-color: #27B371;
            --highlight-color: #E32D31;
            
            /* Couleurs système */
            --success-color: #48bb78;
            --warning-color: #ed8936;
            --danger-color: #f56565;
            --info-color: #4299e1;
            --dark-color: #2d3748;
            --light-color: #f7fafc;
            
            /* Nuances de gris */
            --gray-100: #f7fafc;
            --gray-200: #edf2f7;
            --gray-300: #e2e8f0;
            --gray-400: #cbd5e0;
            --gray-500: #a0aec0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
            --gray-900: #1a202c;
            
            /* Design tokens */
            --border-radius: 16px;
            --border-radius-sm: 8px;
            --border-radius-lg: 24px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #F53003 0%, #FF750F 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            position: relative;
        }

        .app-sidebar.collapsed .sidebar-brand h3::before {
            content: 'A';
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-item {
            margin: 0.25rem 0.5rem;
        }

        .app-sidebar.collapsed .nav-link {
            justify-content: center;
            position: relative;
            padding: 0.875rem 0.5rem;
            border-radius: 12px;
        }

        .app-sidebar.collapsed .nav-link span {
            opacity: 0;
            visibility: hidden;
            position: absolute;
        }

        .app-sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.25rem;
            width: auto;
            text-align: center;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-700);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
            margin-right: 1rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-toggle-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-toggle-btn:hover::before {
            opacity: 1;
        }

        .sidebar-toggle-btn:hover {
            color: white;
            transform: scale(1.1);
            box-shadow: var(--shadow);
        }

        .sidebar-toggle-btn i {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .sidebar-toggle-btn:hover i {
            transform: rotate(180deg);
        }

        /* Tooltip styles */
        .nav-link[data-bs-toggle="tooltip"] {
            position: relative;
        }

        /* Animations for collapsed state */
        .nav-link span {
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .nav-link:hover::after {
            content: attr(data-title);
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-900);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1001;
            opacity: 1;
            visibility: visible;
            pointer-events: none;
        }

        .app-sidebar.collapsed .nav-link:hover::before {
            content: '';
            position: absolute;
            left: 60px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--gray-900);
            z-index: 1001;
        }

        /* Sidebar footer adjustments */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-sidebar.collapsed .sidebar-footer .btn {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .app-sidebar.collapsed .sidebar-footer .btn span {
            display: none;
        }

        .app-sidebar.collapsed .sidebar-footer .btn i {
            margin: 0;
        }

        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem 0.5rem;
        }

        .sidebar-brand h3 {
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
        }

        .africode-logo {
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .logo-text {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        /* Mode réduit - logo */
        .app-sidebar.collapsed .logo-text {
            display: none;
        }

        .app-sidebar.collapsed .africode-logo {
            width: 35px;
            height: 35px;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            gap: 0;
        }

        /* Amélioration hover effects pour les icônes */
        .nav-link:hover {
            background: linear-gradient(135deg, rgba(245, 48, 3, 0.1), rgba(255, 117, 15, 0.1));
            color: var(--primary-color);
            transform: translateX(4px);
        }

        /* Mode sombre pour la sidebar */
        @media (prefers-color-scheme: dark) {
            .app-sidebar {
                background: rgba(26, 26, 26, 0.95);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .sidebar-brand {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
            
            .nav-link {
                color: #e2e8f0;
            }
            
            .nav-link:hover {
                background: linear-gradient(135deg, rgba(245, 48, 3, 0.2), rgba(255, 117, 15, 0.2));
                color: var(--secondary-color);
            }
        }

        /* Sidebar Styles */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(0);
            display: flex;
            flex-direction: column;
        }

        .app-sidebar.collapsed {
            width: 80px;
        }

        .app-sidebar.collapsed .sidebar-brand {
            padding: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-sidebar.collapsed .sidebar-brand h3 {
            font-size: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
           