@extends('apprenants.layouts.app')

@section('title', $lesson->title . ' | AfriCode')
@section('page-title', $lesson->title)

@push('styles')
<style>
    /* Variables CSS pour compatibilité avec les styles de la leçon - exactement comme apprenant */
    :root {
        --border-radius: 12px;
        --border-radius-sm: 8px;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --gray-50: #F7FAFC;
        --gray-100: #EDF2F7;
        --gray-200: #E2E8F0;
        --gray-400: #A0AEC0;
        --gray-500: #718096;
        --gray-600: #4A5568;
        --gray-700: #2D3748;
        --gray-800: #1A202C;
        --gray-900: #171923;
        --primary-color: #11c06d;
        --secondary-color: #239e79;
        /* Variables AfriCode pour compatibilité totale */
        --africode-background: #f7fbff;
        --africode-primary: #11c06d;
        --africode-primary-dark: #198572;
        --africode-secondary: #239e79;
        --africode-accent: #fea837;
        --africode-danger: #fa1c39;
        --africode-text-primary: #2D3748;
        --africode-text-secondary: #718096;
        --africode-border: #E2E8F0;
        --africode-surface: #FFFFFF;
        --africode-hover-bg: #F7FAFC;
        --africode-gradient-primary: linear-gradient(135deg, #11c06d 0%, #239e79 100%);
        --africode-border-radius: 12px;
        --africode-border-radius-sm: 8px;
        --africode-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --africode-shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --africode-shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --africode-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Lesson page specific styles */
    /* Masquer le header avec le menu et le titre */
    .top-header {
        display: none !important;
    }

    /* Masquer le sidebar principal (menu de navigation) */
    .app-sidebar {
        display: none !important;
    }

    /* Note: L'overlay du sidebar du cours est géré par les styles responsive */

    /* Ajuster le contenu principal pour prendre toute la largeur */
    .main-content {
        margin-left: 0 !important;
        width: 100% !important;
    }

    .main-content.sidebar-collapsed {
        margin-left: 0 !important;
    }

    .content-wrapper {
        padding-top: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .lesson-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
        height: 100vh;
        transition: grid-template-columns 0.3s ease;
    }

    .lesson-container.sidebar-hidden {
        grid-template-columns: 0 1fr;
    }

    .lesson-sidebar {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        overflow-y: auto;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .lesson-sidebar.hidden {
        transform: translateX(-100%);
        opacity: 0;
        pointer-events: none;
    }

    .sidebar-toggle {
        background: linear-gradient(135deg, #11c06d 0%, #239e79 100%) !important;
        color: white !important;
        border: 2px solid white !important;
        border-radius: 8px !important;
        width: 45px !important;
        height: 45px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.1rem !important;
        box-shadow: 0 4px 15px rgba(17, 192, 109, 0.3) !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        opacity: 1 !important;
        visibility: visible !important;
        z-index: 1000 !important;
    }

    .sidebar-toggle:hover {
        transform: scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(17, 192, 109, 0.4) !important;
        background: linear-gradient(135deg, #239e79 0%, #11c06d 100%) !important;
    }

    .sidebar-toggle:hover::after {
        opacity: 1 !important;
        transform: translateY(-50%) scale(1) !important;
    }

    .sidebar-toggle::after {
        content: attr(data-tooltip) !important;
        position: absolute !important;
        left: 55px !important;
        top: 50% !important;
        transform: translateY(-50%) scale(0.8) !important;
        background: var(--gray-800) !important;
        color: white !important;
        padding: 0.5rem 0.75rem !important;
        border-radius: 6px !important;
        font-size: 0.75rem !important;
        white-space: nowrap !important;
        opacity: 0 !important;
        pointer-events: none !important;
        transition: all 0.3s ease !important;
        z-index: 1001 !important;
    }

    .sidebar-toggle::before {
        content: '';
        position: absolute;
        left: 42px;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: var(--gray-800);
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 1000;
    }

    .sidebar-toggle:hover::before {
        opacity: 1;
    }

    .lesson-header-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .lesson-title-section {
        flex: 1;
    }

    .lesson-controls {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        margin-top: 0.25rem;
    }

    .sidebar-control-hint {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .lesson-content {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    .lesson-header {
        padding: 1rem 2rem;
        border-bottom: 1px solid var(--gray-200);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    }

    .btn-back-to-courses:hover {
        background: linear-gradient(135deg, #239e79 0%, #11c06d 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(17, 192, 109, 0.4) !important;
        text-decoration: none;
        color: white !important;
    }

    .lesson-body {
        flex: 1;
        padding: 0 2rem 1.5rem 2rem;
        overflow-y: auto;
        max-height: calc(100vh - 220px);
    }
    
    /* S'assurer que la vidéo est bien centrée et sans espace blanc en haut */
    .lesson-body > .video-container-large:first-child {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    .lesson-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--gray-200);
        background: var(--gray-50);
    }

    .module-group {
        margin-bottom: 1rem;
    }

    .module-header {
        background: linear-gradient(135deg, #11c06d 0%, #239e79 100%) !important;
        color: white !important;
        padding: 1rem 1.25rem !important;
        border-radius: var(--africode-border-radius-sm) !important;
        cursor: pointer !important;
        transition: var(--africode-transition) !important;
        font-weight: 600 !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 0.5rem !important;
        box-shadow: var(--africode-shadow-sm) !important;
        border: 1px solid rgba(17, 192, 109, 0.3) !important;
    }

    .module-header:hover {
        background: linear-gradient(135deg, #17896E 0%, #229A63 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: var(--africode-shadow-md) !important;
    }

    .module-lessons {
        padding: 0.75rem 0 !important;
        background: rgba(17, 192, 109, 0.02) !important;
        border-radius: 0 0 var(--africode-border-radius-sm) var(--africode-border-radius-sm) !important;
        margin-bottom: 1rem !important;
        border: 1px solid rgba(17, 192, 109, 0.1) !important;
        border-top: none !important;
    }

    .lesson-item {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.875rem 1.25rem !important;
        margin: 0.25rem 0.5rem !important;
        border-radius: var(--africode-border-radius-sm) !important;
        transition: var(--africode-transition) !important;
        cursor: pointer !important;
        text-decoration: none !important;
        color: var(--africode-text-primary) !important;
        border: 1px solid transparent !important;
        background: white !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
    }

    .lesson-item:hover {
        background: rgba(17, 192, 109, 0.08) !important;
        color: #11c06d !important;
        transform: translateX(4px) !important;
        text-decoration: none !important;
        box-shadow: var(--africode-shadow-sm) !important;
        border-color: rgba(17, 192, 109, 0.2) !important;
    }

    .lesson-item.disabled {
        cursor: not-allowed !important;
        background-color: var(--gray-100) !important;
        color: var(--gray-500) !important;
        opacity: 0.8 !important;
        pointer-events: none !important;
    }
    .lesson-item.disabled:hover {
        background: var(--gray-100) !important;
        transform: none !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        border-color: transparent !important;
    }

    .lesson-item.active {
        background: linear-gradient(135deg, rgba(17, 192, 109, 0.15) 0%, rgba(35, 158, 121, 0.15) 100%) !important;
        color: #11c06d !important;
        border-color: #11c06d !important;
        font-weight: 600 !important;
        box-shadow: var(--africode-shadow-md) !important;
        border-left: 4px solid #11c06d !important;
    }

    .lesson-item.completed {
        color: #239e79 !important;
        border-left: 3px solid #239e79 !important;
    }

    .lesson-item.attempted {
        color: #f0ad4e !important;
        border-left: 3px solid #f0ad4e !important;
    }

    .quiz-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        font-size: 0.6rem !important;
        padding: 2px 6px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
    }

    .quiz-badges {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin-left: auto;
    }

    .certification-badge {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
        color: white !important;
        font-size: 0.65rem !important;
        padding: 3px 6px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        display: inline-flex;
        align-items: center;
        animation: pulse-golden 2s infinite;
    }

    .required-quiz {
        border-left: 3px solid #f39c12 !important;
    }

    .required-quiz.completed {
        border-left: 3px solid #239e79 !important;
    }

    .required-indicator {
        color: #f39c12 !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
    }

    @keyframes pulse-golden {
        0%, 100% {
            box-shadow: 0 0 5px rgba(243, 156, 18, 0.3);
        }
        50% {
            box-shadow: 0 0 15px rgba(243, 156, 18, 0.6);
        }
    }

    .lesson-icon {
        width: 24px;
        margin-right: 0.75rem;
        text-align: center;
    }

    .video-container {
        position: relative;
        width: 100%;
        height: 60vh;
        min-height: 450px;
        max-height: 600px;
        background: var(--gray-900);
        border-radius: var(--border-radius-sm);
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .video-container iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: var(--border-radius-sm);
    }

    .video-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: white;
        font-size: 4rem;
        background: linear-gradient(135deg, var(--gray-800), var(--gray-900));
    }

    .lesson-meta {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 0;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .lesson-progress {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(102, 126, 234, 0.2);
    }

    .breadcrumb-modern {
        background: none;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "→";
        color: var(--gray-400);
    }

    .navigation-buttons {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
    }

    .btn-nav {
        flex: 1;
        max-width: 200px;
    }

    .watch-progress-container {
        min-width: 200px;
    }

    .watch-progress-bar {
        width: 100%;
        height: 8px;
        background: var(--gray-200);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }

    .watch-progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #11c06d 0%, #239e79 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .watch-progress-fill.complete {
        background: linear-gradient(135deg, #239e79 0%, #11c06d 100%);
    }

    .resources-section {
        background: var(--gray-50);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .resource-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 0.5rem;
        background: white;
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .resource-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .resource-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 50%;
        margin-right: 1rem;
        font-size: 1.2rem;
    }

    .text-content {
        line-height: 1.8;
        font-size: 1.1rem;
        color: var(--gray-700);
    }

    .text-content h1, .text-content h2, .text-content h3, .text-content h4, .text-content h5, .text-content h6 {
        color: var(--gray-800);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .text-content p {
        margin-bottom: 1.5rem;
    }

    .text-content code {
        background: var(--gray-100);
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-family: 'Monaco', 'Consolas', monospace;
    }

    .text-content pre {
        background: var(--gray-900);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius-sm);
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    /* Styles pour les images */
    .text-content img {
        max-width: 100%;
        height: auto;
        border-radius: var(--border-radius-sm);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 1.5rem 0;
        display: block;
    }

    .text-content img[style*="float: left"] {
        float: left;
        margin-right: 1rem;
        margin-bottom: 1rem;
    }

    .text-content img[style*="float: right"] {
        float: right;
        margin-left: 1rem;
        margin-bottom: 1rem;
    }

    /* Styles pour les tableaux */
    .text-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        background: white;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .text-content table th,
    .text-content table td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--gray-200);
    }

    .text-content table th {
        background: linear-gradient(135deg, #11c06d 0%, #239e79 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }

    .text-content table tr:nth-child(even) {
        background: var(--gray-50);
    }

    .text-content table tr:hover {
        background: rgba(17, 192, 109, 0.05);
    }

    /* Styles pour les listes */
    .text-content ul,
    .text-content ol {
        margin: 1rem 0;
        padding-left: 2rem;
    }

    .text-content li {
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }

    /* Styles pour les citations */
    .text-content blockquote {
        border-left: 4px solid #11c06d;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: var(--gray-600);
        background: rgba(17, 192, 109, 0.05);
        padding: 1rem 1.5rem;
        border-radius: 0 var(--border-radius-sm) var(--border-radius-sm) 0;
    }

    /* Styles pour les liens */
    .text-content a {
        color: #11c06d;
        text-decoration: none;
        border-bottom: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .text-content a:hover {
        color: #239e79;
        border-bottom-color: #239e79;
    }

    /* Styles pour les éléments inline */
    .text-content strong {
        font-weight: 600;
        color: var(--gray-800);
    }

    .text-content em {
        font-style: italic;
        color: var(--gray-600);
    }

    /* Styles pour les vidéos YouTube */
    .youtube-video-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        margin: 1.5rem 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .youtube-video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 8px;
    }

    /* Styles pour les images de la leçon */
    .lesson-images-section {
        background: linear-gradient(135deg, rgba(17, 192, 109, 0.05), rgba(35, 158, 121, 0.05));
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(17, 192, 109, 0.2);
        margin-top: 2rem;
    }

    .lesson-image-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .lesson-image-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .lesson-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .lesson-image:hover {
        transform: scale(1.02);
    }

    .image-info {
        padding: 0.5rem 0;
    }

    .image-info small {
        font-size: 0.75rem;
        color: var(--gray-600);
    }

    /* Modal pour afficher les images en grand */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        animation: fadeIn 0.3s ease;
    }

    .image-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        animation: zoomIn 0.3s ease;
    }

    .image-modal img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .image-modal-close {
        position: absolute;
        top: -40px;
        right: 0;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .image-modal-close:hover {
        background: rgba(255, 0, 0, 0.7);
        transform: scale(1.1);
    }

    .image-modal-title {
        position: absolute;
        bottom: -40px;
        left: 0;
        color: white;
        background: rgba(0, 0, 0, 0.7);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes zoomIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    /* Responsive pour les images et tableaux */
    @media (max-width: 768px) {
        .text-content img {
            margin: 1rem 0;
        }

        .text-content table {
            font-size: 0.875rem;
        }

        .text-content table th,
        .text-content table td {
            padding: 0.5rem 0.75rem;
        }

        /* Amélioration pour les tableaux sur mobile */
        .text-content table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        /* Vidéos YouTube sur mobile */
        .youtube-video-container {
            margin: 1rem 0;
        }
    }

    /* Video responsive styles */
    .video-wrapper {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        margin-bottom: 2rem;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .video-container-large {
        position: relative;
        width: 100%;
        max-width: 900px;
        margin: 0 auto 1.5rem;
        padding: 0;
        background: var(--gray-900);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        aspect-ratio: 16 / 9; /* Format 16:9 pour YouTube */
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* S'assurer qu'il n'y a pas d'espace blanc en haut de la vidéo */
    .lesson-body > .video-container-large:first-child {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    .youtube-wrapper-clean {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        /* Bloquer le clic droit */
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        /* Permettre les interactions avec l'iframe */
        pointer-events: none;
    }
    
    .youtube-wrapper-clean iframe {
        pointer-events: auto !important;
    }

    .youtube-iframe-clean {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        pointer-events: auto !important;
        /* Empêcher le menu contextuel */
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        /* Bloquer le déplacement de la vidéo */
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
        /* Empêcher la sélection */
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        z-index: 10 !important;
    }

    .video-container-large {
        /* Empêcher le déplacement du conteneur */
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
        /* Empêcher la sélection */
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .youtube-wrapper-clean {
        /* Empêcher le déplacement du wrapper */
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
        user-drag: none;
    }


    @media (max-width: 1024px) {
        .lesson-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .lesson-container.sidebar-hidden {
            grid-template-columns: 1fr;
        }

        .lesson-sidebar {
            order: 2;
            max-height: 300px;
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            height: 100vh;
            z-index: 999;
            transform: translateX(-100%);
        }

        .lesson-sidebar.visible {
            transform: translateX(-100%);
        }

        .lesson-content {
            order: 1;
        }

        .sidebar-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.visible {
            opacity: 0;
            pointer-events: none;
        }

        .lesson-content {
            margin-top: 0;
        }

        .lesson-header {
            padding: 1rem !important;
        }

        .lesson-header h4 {
            font-size: 1.25rem;
        }

        .lesson-header .btn-back-to-courses {
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 768px) {
        .lesson-header,
        .lesson-body,
        .lesson-footer {
            padding: 1rem;
        }

        .lesson-content {
            margin-top: 0;
        }

        .lesson-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start !important;
        }

        .lesson-header h4 {
            font-size: 1.1rem;
        }

        .lesson-header .btn-back-to-courses {
            width: 100%;
            justify-content: center;
        }

        .lesson-header .btn-back-to-courses span {
            display: inline;
        }

        .video-wrapper {
            padding-bottom: 56.25%; /* Maintenir le ratio 16:9 sur mobile */
        }

        .video-container-large {
            max-width: 100%;
            aspect-ratio: 16 / 9; /* Format 16:9 sur mobile aussi */
        }

        .lesson-body {
            max-height: calc(100vh - 160px);
        }

        .lesson-meta {
            gap: 1rem;
        }

        .navigation-buttons {
            flex-direction: column;
        }

        .btn-nav {
            max-width: none;
        }
    }
</style>
@endpush

@section('content')
     <!-- Breadcrumb -->
    <!--<nav aria-label="breadcrumb" class="breadcrumb-modern mb-3" data-aos="fade-right">-->
    <!--    <ol class="breadcrumb">-->
    <!--        <li class="breadcrumb-item">-->
    <!--            <a href="" class="text-decoration-none">-->
    <!--                <i class="fas fa-home me-1"></i>Tableau de bord-->
    <!--            </a>-->
    <!--        </li>-->
    <!--        <li class="breadcrumb-item">-->
    <!--            <a href="#" class="text-decoration-none"></a>-->
    <!--        </li>-->
    <!--        <li class="breadcrumb-item active" aria-current="page"></li>-->
    <!--    </ol>-->
    <!--</nav> -->


    <!-- Main Lesson Container -->
    <div class="lesson-container" id="lessonContainer" data-aos="fade-up" data-aos-delay="100">
        <!-- Sidebar Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- Sidebar with Course Structure -->
        <div class="lesson-sidebar" id="lessonSidebar">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 text-primary fw-bold">
                    <i class="fas fa-list me-2"></i>
                    Structure du cours
                </h6>
                <button class="btn btn-sm btn-outline-primary d-lg-none" onclick="closeSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            @foreach($courseStructure['modules'] as $moduleData)
            <div class="module-group mb-3">
                <div class="module-header" onclick="toggleModule({{ $moduleData['module']->id }})">
                    <span>{{ $moduleData['module']->title }}</span>
                    <i class="fas fa-chevron-down" id="icon-{{ $moduleData['module']->id }}"></i>
                </div>
                <div class="module-lessons" id="lessons-{{ $moduleData['module']->id }}">
                    {{-- Afficher les leçons du module --}}
                    @foreach($moduleData['lessons'] as $lessonData)
                        @php
                            $l = $lessonData['item'];
                            $url = route('formateur.view.lesson', ['courseId' => $course->id, 'lessonId' => $l->id]);
                            $class = 'lesson-item';
                            if ($l->id === $lesson->id) $class .= ' active';
                        @endphp
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                <i class="lesson-icon fas {{ $l->id === $lesson->id ? 'fa-play-circle' : 'fa-circle' }}"></i>
                                <span>{{ $l->title }}</span>
                            </div>
                            <small class="text-muted">{{ $l->duration_minutes }} min</small>
                        </a>
                    @endforeach

                    {{-- Afficher le quiz du module s'il existe --}}
                    @if($moduleData['quiz'])
                        @php
                            $q = $moduleData['quiz']['item'];
                            $url = route('formateur.quizzes.edit', ['quizId' => $q->id]);
                            $class = 'lesson-item quiz-item';
                            if ($q->is_required) $class .= ' required-quiz';
                        @endphp
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                <i class="lesson-icon fas fa-question-circle text-primary"></i>
                                <span>{{ $q->title }}</span>
                                <div class="quiz-badges">
                                    <span class="quiz-badge">QUIZ</span>
                                    @if($q->is_required)
                                        <span class="certification-badge" title="Obligatoire pour le certificat"><i class="fas fa-star"></i></span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            @endforeach

            {{-- Afficher le quiz final s'il existe --}}
            @if($courseStructure['final_quiz'])
                @php
                    $q = $courseStructure['final_quiz']['item'];
                    $url = route('formateur.quizzes.edit', ['quizId' => $q->id]);
                    $class = 'lesson-item quiz-item final-quiz';
                @endphp
                 <div class="module-group mb-3">
                    <div class="module-header">
                        <span>Examen Final</span>
                    </div>
                    <div class="module-lessons">
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                 <i class="lesson-icon fas fa-flag-checkered text-primary" style="color: #fea837;"></i>
                                <span>{{ $q->title }}</span>
                                <div class="quiz-badges">
                                    <span class="quiz-badge" style="background: linear-gradient(135deg, #d35400 0%, #e67e22 100%) !important;">EXAMEN</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

            {{-- Section Certificat --}}
            @if($course->is_certifying)
                <div class="module-group mb-3">
                    <div class="module-header" style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important; color: white;">
                        <span><i class="fas fa-certificate me-2"></i>Certificat</span>
                    </div>
                    <div class="module-lessons">
                        <div class="p-3 text-center">
                            <i class="fas fa-lock fa-2x text-muted mb-2" style="color: #fea837;"></i>
                            <div class="fw-bold mb-2">Certificat verrouillé</div>
                            <div class="small text-muted">Complétez toutes les étapes et réussissez l'examen final pour débloquer votre certificat.</div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- Main Content -->
        <div class="lesson-content">
            <div class="lesson-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <button id="sidebarToggleDesktop" onclick="toggleSidebar()" class="sidebar-toggle" data-tooltip="Masquer la structure du cours">
                            <i class="fas fa-bars"></i>
    </button>
                        <h4 class="mb-0 fw-bold" style="color: #11c06d;">{{ $lesson->title }}</h4>
</div>
                    <a href="{{ route('formateur.courses.index') }}" class="btn-back-to-courses" style="background: linear-gradient(135deg, #11c06d 0%, #239e79 100%); border: none; color: white; padding: 0.5rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(17, 192, 109, 0.3);">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour à mes cours</span>
                    </a>
                    </div>
            </div>

            <div class="lesson-body">
                @if($lesson->content_type === 'video' && ($lesson->video_url || (isset($lesson->youtube_id) && $lesson->youtube_id)))
                    @php
                        // Obtenir l'ID de la vidéo YouTube
                        $videoId = '';
                        if (isset($lesson->youtube_id) && !empty($lesson->youtube_id)) {
                            $videoId = $lesson->youtube_id;
                        } elseif (isset($lesson->video_url) && !empty($lesson->video_url)) {
                            // Traitement des URL YouTube pour obtenir l'ID de la vidéo
                        if (strpos($lesson->video_url, 'youtube.com') !== false) {
                            parse_str(parse_url($lesson->video_url, PHP_URL_QUERY), $params);
                            $videoId = $params['v'] ?? '';
                        } elseif (strpos($lesson->video_url, 'youtu.be') !== false) {
                            $videoId = substr(parse_url($lesson->video_url, PHP_URL_PATH), 1);
                            }
                        }
                    @endphp

                    @if($videoId)
                        <div class="video-container-large" draggable="false" id="videoWrapper">
                            <div class="youtube-wrapper-clean" draggable="false" id="youtubeWrapper">
                                <iframe 
                                    src="https://www.youtube-nocookie.com/embed/{{ $videoId }}?rel=0&modestbranding=1&controls=1&showinfo=0&fs=1&iv_load_policy=3&cc_load_policy=0&playsinline=1&disablekb=1&origin={{ urlencode(request()->getSchemeAndHttpHost()) }}&autohide=1&loop=0&enablejsapi=1"
                                    title="Cours vidéo AfriCode" 
                                    style="width: 100%; height: 100%; border: none; border-radius: 10px; position: absolute; top: 0; left: 0;"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                                    allowfullscreen
                                    frameborder="0"
                                    class="youtube-iframe-clean"
                                    draggable="false"
                                    id="youtubeIframe">
                                </iframe>
                            </div>
                        </div>
                    @else
                        <div class="video-container">
                            <div class="video-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle mb-3" style=" color: #fea837;"></i>
                                    <p class="mb-0">Vidéo non disponible</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                
                @if($lesson->content_type === 'text' || $lesson->text_content)
                    <div class="text-content">
                        {!! $lesson->text_content !!}
                    </div>
                    
                    {{-- Section pour afficher les images de la leçon --}}
                    @if($lesson->images && count($lesson->images) > 0)
                         {{--<div class="lesson-images-section mt-4">
                            <h5 class="mb-3">
                                <i class="fas fa-images me-2" style="color: #11c06d;"></i>
                                Images de la leçon
                            </h5> 
                            <div class="row">
                                @foreach($lesson->images as $image)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="lesson-image-card">
                                            <img src="{{ $image['url'] }}" 
                                                 alt="{{ $image['original_name'] }}" 
                                                 class="img-fluid lesson-image"
                                                 style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); cursor: pointer;"
                                                 onclick="openImageModal('{{ $image['url'] }}', '{{ $image['original_name'] }}')">
                                            <div class="image-info mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-file-image me-1"></i>
                                                    {{ $image['original_name'] }}
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-weight-hanging me-1"></i>
                                                    {{ number_format($image['file_size'] / 1024, 1) }} KB
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>--}}
                    @endif 
                    
                    <!-- Script pour corriger les URLs des images et convertir les vidéos -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const textContent = document.querySelector('.text-content');
                            if (textContent) {
                                // Corriger les URLs des images SANS casser les URLs absolues
                                const images = textContent.querySelectorAll('img');
                                images.forEach(function(img) {
                                    const originalSrc = img.getAttribute('src') || '';
                                    const isAbsolute = /^https?:\/\//i.test(originalSrc);
                                    const baseUrl = window.location.origin;

                                    // Si l'attribut src est relatif, le convertir en absolu
                                    if (!isAbsolute) {
                                        if (originalSrc.startsWith('/')) {
                                            img.src = baseUrl + originalSrc; // ex: /storage/... ou /formateur/lesson-images/...
                                        } else {
                                            // src relatif au document, le normaliser
                                            img.src = baseUrl + '/' + originalSrc.replace(/^\.\//, '');
                                        }
                                    }

                                    // Gestionnaire d'erreur: basculer vers la route de secours avec le nom de fichier
                                    img.onerror = function() {
                                        const filename = (this.getAttribute('src') || '').split('/').pop();
                                        if (filename) {
                                            this.src = baseUrl + '/formateur/lesson-images/' + filename;
                                        }
                                    };
                                });
                                
                                // Convertir les liens YouTube en vidéos intégrées
                                const links = textContent.querySelectorAll('a[href*="youtube.com"], a[href*="youtu.be"]');
                                console.log('Liens YouTube trouvés:', links.length);
                                
                                links.forEach(function(link) {
                                    const url = link.href;
                                    let videoId = '';
                                    
                                    console.log('Traitement du lien:', url);
                                    
                                    // Extraire l'ID de la vidéo
                                    if (url.includes('youtube.com/watch?v=')) {
                                        videoId = url.split('v=')[1].split('&')[0];
                                    } else if (url.includes('youtu.be/')) {
                                        videoId = url.split('youtu.be/')[1].split('?')[0];
                                    }
                                    
                                    console.log('ID vidéo extrait:', videoId);
                                    
                                    if (videoId) {
                                        // Créer un conteneur pour la vidéo
                                        const videoContainer = document.createElement('div');
                                        videoContainer.className = 'youtube-video-container';
                                        videoContainer.style.margin = '1.5rem 0';
                                        videoContainer.style.borderRadius = '8px';
                                        videoContainer.style.overflow = 'hidden';
                                        videoContainer.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                                        videoContainer.style.position = 'relative';
                                        videoContainer.style.paddingBottom = '56.25%'; // 16:9 aspect ratio
                                        videoContainer.style.height = '0';
                                        
                                        // Créer l'iframe YouTube (sans overlay)
                                        const iframe = document.createElement('iframe');
                                        iframe.src = 'https://www.youtube.com/embed/' + videoId + '?rel=0&modestbranding=1&showinfo=0&controls=0&fs=0&iv_load_policy=3&cc_load_policy=0&disablekb=0&playsinline=1';
                                        iframe.style.position = 'absolute';
                                        iframe.style.top = '0';
                                        iframe.style.left = '0';
                                        iframe.style.width = '100%';
                                        iframe.style.height = '100%';
                                        iframe.style.border = 'none';
                                        iframe.frameBorder = '0';
                                        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen';
                                        iframe.allowFullscreen = true;
                                        
                                        videoContainer.appendChild(iframe);
                                        
                                        // Remplacer le lien par la vidéo
                                        link.parentNode.replaceChild(videoContainer, link);
                                        console.log('Vidéo YouTube intégrée avec succès');
                                    }
                                });
                            }
                        });
                    </script>
                @endif

                @if($lesson->content_type === 'pdf' && $lesson->pdf_path)
                    <div class="text-center mb-4">
                        <a href="{{ asset($lesson->pdf_path) }}" class="btn btn-modern" target="_blank">
                            <i class="fas fa-file-pdf me-2" style=" color: #fea837;"></i> Ouvrir le PDF
                        </a>
                    </div>
                    <embed src="{{ asset($lesson->pdf_path) }}" type="application/pdf" width="100%" height="600px" 
                           style="border-radius: var(--border-radius-sm);" />
                @endif
                
                @if($lesson->content_type === 'external' && $lesson->external_url)
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-external-link-alt display-3 text-primary" style=" color: #fea837;"></i>
                        </div>
                        <h5 class="mb-3">Contenu externe</h5>
                        <p class="text-muted mb-4">Cette leçon est hébergée sur une plateforme externe.</p>
                        <a href="{{ $lesson->external_url }}" class="btn btn-modern" target="_blank">
                            <i class="fas fa-external-link-alt me-2" style=" color: #fea837;"></i> Accéder au contenu
                        </a>
                    </div>
                @endif
                
                @if(isset($resources) && $resources->isNotEmpty())
                    <div class="resources-section">
                        <h5 class="mb-3">
                            <i class="fas fa-download me-2" style=" color: #fea837;"></i>
                            Ressources supplémentaires
                        </h5>
                        
                        @foreach($resources as $resource)
                        <div class="resource-item">
                            <div class="resource-icon">
                                @switch($resource->type)
                                    @case('pdf')
                                        <i class="fas fa-file-pdf" style=" color: #fea837;"></i>
                                        @break
                                    @case('archive')
                                        <i class="fas fa-file-archive" style=" color: #fea837;"></i>
                                        @break
                                    @case('image')
                                        <i class="fas fa-file-image" style=" color: #fea837;"></i>
                                        @break
                                    @case('code')
                                        <i class="fas fa-file-code" style=" color: #fea837;"></i>
                                        @break
                                    @default
                                        <i class="fas fa-file" style=" color: #fea837;"></i>
                                @endswitch
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $resource->title }}</h6>
                                <p class="mb-0 text-muted small">{{ $resource->description }}</p>
                            </div>
                            {{-- <a href="{{ asset($resource->file_path) }}" class="btn btn-outline-modern btn-sm" target="_blank">
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a> --}}
                        </div>
                        @endforeach
                    </div>
                @endif
                
                {{-- Afficher le quiz du module après la dernière leçon --}}
                @if($isLastLessonOfModule && $moduleQuiz)
                    <div class="quiz-section mt-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 12px; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <div class="d-flex align-items-center mb-3">
                            <div class="quiz-icon me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #11c06d 0%, #239e79 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-question-circle text-white" style="font-size: 2rem;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="mb-1" style="color: #11c06d; font-weight: 600;">{{ $moduleQuiz->title }}</h4>
                                @if($moduleQuiz->description)
                                    <p class="mb-0 text-muted">{{ $moduleQuiz->description }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="quiz-info mb-4" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
                            <div class="info-item" style="background: white; padding: 1rem; border-radius: 8px; text-align: center;">
                                <div class="text-muted small mb-1">Questions</div>
                                <div class="fw-bold" style="color: #11c06d; font-size: 1.25rem;">{{ $moduleQuiz->questions->count() }}</div>
                            </div>
                            <div class="info-item" style="background: white; padding: 1rem; border-radius: 8px; text-align: center;">
                                <div class="text-muted small mb-1">Score minimum</div>
                                <div class="fw-bold" style="color: #11c06d; font-size: 1.25rem;">{{ $moduleQuiz->passing_score }}%</div>
                            </div>
                            @if($moduleQuiz->time_limit)
                            <div class="info-item" style="background: white; padding: 1rem; border-radius: 8px; text-align: center;">
                                <div class="text-muted small mb-1">Temps limité</div>
                                <div class="fw-bold" style="color: #11c06d; font-size: 1.25rem;">{{ $moduleQuiz->time_limit }} min</div>
                            </div>
                            @endif
                            @if($moduleQuiz->is_required)
                            <div class="info-item" style="background: linear-gradient(135deg, #fea837 0%, #FFA64D 100%); padding: 1rem; border-radius: 8px; text-align: center; color: white;">
                                <div class="small mb-1">Obligatoire</div>
                                <div class="fw-bold" style="font-size: 1.25rem;"><i class="fas fa-star"></i></div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-center">
                            <a href="{{ route('formateur.quizzes.edit', ['quizId' => $moduleQuiz->id]) }}" 
                               class="btn btn-modern" 
                               style="background: linear-gradient(135deg, #11c06d 0%, #239e79 100%); border: none; color: white; padding: 0.75rem 2rem; font-size: 1.1rem; font-weight: 600; border-radius: 8px; box-shadow: 0 4px 6px rgba(17, 192, 109, 0.3);">
                                <i class="fas fa-edit me-2"></i>Voir / Modifier le quiz
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="lesson-footer">
                <div class="navigation-buttons">
                    @if(isset($previousLesson))
                    <a href="{{ route('formateur.view.lesson', ['courseId' => $course->id, 'lessonId' => $previousLesson->id]) }}" 
                       class="btn btn-outline-modern btn-nav">
                        <i class="fas fa-arrow-left me-2" style="color: #fea837;"></i>Précédent
                    </a>
                    @else
                    <div></div>
                    @endif
                    
                    <div class="d-flex gap-2">
                        {{-- Navigation module suivant --}}
                        @if($isLastLessonOfModule && $moduleQuiz)
                            @if($firstLessonNextModule)
                                <a href="{{ route('formateur.view.lesson', ['courseId' => $course->id, 'lessonId' => $firstLessonNextModule->id]) }}" class="btn btn-modern btn-nav">
                                    Module suivant <i class="fas fa-arrow-right ms-2" style="color: #fea837;"></i>
                                </a>
                            @else
                                <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-modern btn-nav">
                                    <i class="fas fa-home me-2" style="color: #fea837;"></i>Retour à la gestion
                                </a>
                            @endif
                        @else
                            @if(isset($nextLesson))
                                <a href="{{ route('formateur.view.lesson', ['courseId' => $course->id, 'lessonId' => $nextLesson->id]) }}" 
                                   class="btn btn-modern btn-nav">
                                    Suivant<i class="fas fa-arrow-right ms-2" style="color: #fea837;"></i>
                                </a>
                            @else
                                <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-modern btn-nav">
                                    <i class="fas fa-home me-2" style="color: #fea837;"></i>Retour à la gestion
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les images en grand -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <div class="image-modal-content" onclick="event.stopPropagation()">
            <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" alt="">
            <div class="image-modal-title" id="modalTitle"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Bloquer complètement le clic droit et masquer tous les éléments YouTube
    document.addEventListener('DOMContentLoaded', function() {
        const videoContainer = document.querySelector('#youtubeWrapper');
        const videoIframe = document.querySelector('#youtubeIframe');
        const videoWrapper = document.querySelector('#videoWrapper');
        
        // Bloquer le clic droit partout sur la page (mode strict avec plusieurs méthodes)
        function blockContextMenu(e) {
                e.preventDefault();
                e.stopPropagation();
            e.stopImmediatePropagation();
                return false;
        }
        
        // Multiples écouteurs pour bloquer le clic droit
        document.addEventListener('contextmenu', blockContextMenu, true);
        document.addEventListener('contextmenu', blockContextMenu, false);
        window.addEventListener('contextmenu', blockContextMenu, true);
        window.addEventListener('contextmenu', blockContextMenu, false);
        
        // Bloquer aussi sur le body
        document.body.addEventListener('contextmenu', blockContextMenu, true);
        document.body.addEventListener('contextmenu', blockContextMenu, false);
        
        if (videoContainer) {
            // Bloquer le clic droit sur le conteneur
            videoContainer.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }, true);
            
            // Bloquer les raccourcis clavier
            videoContainer.addEventListener('keydown', function(e) {
                if (e.key === 'F12' || 
                    (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J')) ||
                    (e.ctrlKey && e.key === 'U') ||
                    (e.ctrlKey && e.key === 'S') ||
                    (e.ctrlKey && e.key === 'P')) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        if (videoIframe) {
            // Bloquer le drag and drop sur l'iframe
            videoIframe.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            });
            
            videoIframe.addEventListener('drag', function(e) {
                e.preventDefault();
                return false;
            });
            
            // Bloquer le clic droit sur l'iframe
            videoIframe.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }, true);
        }

        if (videoWrapper) {
            // Bloquer le drag and drop sur le conteneur
            videoWrapper.addEventListener('dragstart', function(e) {
                e.preventDefault();
                return false;
            });
            
            videoWrapper.addEventListener('drag', function(e) {
                e.preventDefault();
                return false;
            });
            
            // Bloquer le clic droit sur le wrapper
            videoWrapper.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }, true);
        }
        
        // Masquer tous les éléments YouTube qui pourraient apparaître
        // Note: Les éléments dans l'iframe YouTube ne sont pas accessibles via JavaScript
        // Les overlays CSS masquent visuellement ces éléments
        function hideYouTubeElements() {
            // Masquer les éléments en dehors de l'iframe si présents
            const youtubeButtons = document.querySelectorAll('a[href*="youtube.com"], a[href*="youtu.be"], button[aria-label*="Share"], button[aria-label*="Partager"]');
            youtubeButtons.forEach(function(btn) {
                if (btn.closest('#youtubeWrapper') || btn.closest('#videoWrapper')) {
                    btn.style.display = 'none';
                    btn.style.visibility = 'hidden';
                    btn.style.opacity = '0';
                    btn.style.pointerEvents = 'none';
                }
            });
            
            // Masquer les titres YouTube
            const youtubeTitles = document.querySelectorAll('[title*="YouTube"], [aria-label*="YouTube"], .ytp-title, .ytp-share-button, .ytp-title-text, .ytp-title-link');
            youtubeTitles.forEach(function(title) {
                if (title.closest('#youtubeWrapper') || title.closest('#videoWrapper')) {
                    title.style.display = 'none';
                    title.style.visibility = 'hidden';
                    title.style.opacity = '0';
                    title.style.pointerEvents = 'none';
                }
            });
            
            // Masquer tous les éléments avec des classes YouTube spécifiques
            const youtubeElements = document.querySelectorAll('.ytp-title, .ytp-share-button, .ytp-watermark, .ytp-show-cards-title, .ytp-title-text, .ytp-title-link, .ytp-title-channel, .ytp-title-expanded-heading, .ytp-title-expanded-content');
            youtubeElements.forEach(function(el) {
                if (el.closest('#youtubeWrapper') || el.closest('#videoWrapper')) {
                    el.style.display = 'none';
                    el.style.visibility = 'hidden';
                    el.style.opacity = '0';
                    el.style.pointerEvents = 'none';
                }
            });
        }
        
        // Exécuter immédiatement et ensuite toutes les 25ms pour une détection rapide
        hideYouTubeElements();
        setInterval(hideYouTubeElements, 25);
    });

    let sidebarVisible = true;

    function toggleSidebar() {
        const container = document.getElementById('lessonContainer');
        const sidebar = document.getElementById('lessonSidebar');
        const toggleIconDesktop = document.getElementById('sidebarToggleDesktop');
        const overlay = document.getElementById('sidebarOverlay');
        
        sidebarVisible = !sidebarVisible;
        
        if (sidebarVisible) {
            // Show sidebar
            container.classList.remove('sidebar-hidden');
            sidebar.classList.remove('hidden');
            
            if (toggleIconDesktop) {
                const icon = toggleIconDesktop.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-bars');
                }
                toggleIconDesktop.setAttribute('data-tooltip', 'Masquer la structure du cours');
            }
            
            // Mobile specific
            if (window.innerWidth <= 1024) {
                sidebar.classList.add('visible');
                overlay.classList.add('visible');
            }
        } else {
            // Hide sidebar
            container.classList.add('sidebar-hidden');
            sidebar.classList.add('hidden');
            
            if (toggleIconDesktop) {
                const icon = toggleIconDesktop.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-chevron-right');
                }
                toggleIconDesktop.setAttribute('data-tooltip', 'Afficher la structure du cours');
            }
            
            // Mobile specific
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('visible');
                overlay.classList.remove('visible');
            }
        }
    }

    function closeSidebar() {
        if (window.innerWidth <= 1024 && sidebarVisible) {
            toggleSidebar();
        }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('lessonSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (window.innerWidth > 1024) {
            // Desktop mode
            overlay.classList.remove('visible');
            if (sidebarVisible) {
                sidebar.classList.remove('visible');
            }
        } else {
            // Mobile mode
            if (sidebarVisible) {
                sidebar.classList.add('visible');
                overlay.classList.add('visible');
            }
        }
    });

    function toggleModule(moduleId) {
        const lessons = document.getElementById('lessons-' + moduleId);
        const icon = document.getElementById('icon-' + moduleId);
        
        if (lessons.style.display === 'none') {
            lessons.style.display = 'block';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        } else {
            lessons.style.display = 'none';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-right');
        }
    }

    // Auto-expand the module containing the current lesson
    document.addEventListener('DOMContentLoaded', function() {
        const activeLesson = document.querySelector('.lesson-item.active');
        if (activeLesson) {
            const moduleGroup = activeLesson.closest('.module-group');
            if (moduleGroup) {
                const moduleHeader = moduleGroup.querySelector('.module-header');
                if (moduleHeader && moduleHeader.getAttribute('onclick')) {
                    const moduleId = moduleHeader.getAttribute('onclick').match(/\d+/)[0];
            const lessons = document.getElementById('lessons-' + moduleId);
            const icon = document.getElementById('icon-' + moduleId);
            
                    if (lessons && icon) {
            lessons.style.display = 'block';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
                    }
                }
            }
        }

        // Initialize sidebar state for mobile
        if (window.innerWidth <= 1024) {
            const container = document.getElementById('lessonContainer');
            const sidebar = document.getElementById('lessonSidebar');
            container.classList.add('sidebar-hidden');
            sidebar.classList.add('hidden');
            sidebarVisible = false;
        }
    });

    // Fonctions pour la modal des images
    function openImageModal(imageUrl, imageTitle) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('modalTitle');
        
        modalImage.src = imageUrl;
        modalImage.alt = imageTitle;
        modalTitle.textContent = imageTitle;
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Empêcher le scroll
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.remove('show');
        document.body.style.overflow = 'auto'; // Restaurer le scroll
    }

    // Fermer la modal avec la touche Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endpush
