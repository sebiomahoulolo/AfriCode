@extends('apprenants.layouts.app')

@section('title', $lesson->title . ' | AfriCode')
@section('page-title', $lesson->title)

@push('styles')
<style>
    /* Lesson page specific styles */
    .lesson-container {
        display: grid;
        grid-template-columns: 300px 1fr; /* barre "Structure du cours" un peu plus compacte */
        gap: 1.5rem;
        height: calc(100vh - 120px);
        transition: grid-template-columns 0.3s ease;
    }

    .lesson-container.sidebar-hidden {
        grid-template-columns: 0 1fr;
    }

    .lesson-sidebar {
        background: white;
        border-radius: var(--border-radius);
        padding: 0.75rem; /* encore un peu moins de padding pour compacter la barre */
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        overflow-y: auto;
        transition: all 0.3s ease;
        font-size: 0.9rem; /* texte légèrement plus petit */
    }

    .lesson-sidebar h6 {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .lesson-sidebar.hidden {
        transform: translateX(-100%);
        opacity: 0;
        pointer-events: none;
    }

    .sidebar-toggle {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        color: white !important;
        border: 2px solid white !important;
        border-radius: 8px !important;
        width: 45px !important;
        height: 45px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.1rem !important;
        box-shadow: 0 4px 15px rgba(30, 163, 139, 0.3) !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        opacity: 1 !important;
        visibility: visible !important;
        z-index: 1000 !important;
    }

    .sidebar-toggle:hover {
        transform: scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(30, 163, 139, 0.4) !important;
        background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%) !important;
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
    }

    .lesson-header {
        padding: 1rem 2rem;
        border-bottom: 1px solid var(--gray-200);
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    }

    .lesson-body {
        flex: 1;
        padding: 1.5rem 2rem;
        overflow-y: auto;
        max-height: calc(100vh - 220px);
    }

    /* En mode focus leçon (sans header global), remonter la page et réduire les espaces */
    .lesson-view .lesson-container {
        height: auto;
        min-height: auto;
        padding-top: 0.5rem;
    }

    .lesson-view .lesson-body {
        max-height: none;
        padding: 1rem 1.5rem;
    }

    .lesson-view .lesson-header {
        padding: 0.5rem 1.5rem;
    }

    .lesson-view .lesson-footer {
        padding: 1rem 1.5rem;
    }

    .lesson-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--gray-200);
        background: var(--gray-50);
    }

    .module-group {
        margin-bottom: 0.6rem; /* réduire l'espace entre les groupes */
    }

    .module-header {
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%) !important;
        color: white !important;
        padding: 0.7rem 1rem !important; /* header plus fin */
        border-radius: var(--africode-border-radius-sm) !important;
        cursor: pointer !important;
        transition: var(--africode-transition) !important;
        font-weight: 600 !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin-bottom: 0.35rem !important;
        box-shadow: var(--africode-shadow-sm) !important;
        border: 1px solid rgba(30, 163, 139, 0.3) !important;
    }

    .module-header:hover {
        background: linear-gradient(135deg, #17896E 0%, #229A63 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: var(--africode-shadow-md) !important;
    }

    .module-lessons {
        padding: 0.5rem 0 !important;
        background: rgba(30, 163, 139, 0.02) !important;
        border-radius: 0 0 var(--africode-border-radius-sm) var(--africode-border-radius-sm) !important;
        margin-bottom: 0.75rem !important;
        border: 1px solid rgba(30, 163, 139, 0.1) !important;
        border-top: none !important;
    }

    .lesson-item {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.45rem 0.75rem !important; /* cartes de leçon encore plus compactes */
        margin: 0.15rem 0.3rem !important;
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
        background: rgba(30, 163, 139, 0.08) !important;
        color: #1EA38B !important;
        transform: translateX(4px) !important;
        text-decoration: none !important;
        box-shadow: var(--africode-shadow-sm) !important;
        border-color: rgba(30, 163, 139, 0.2) !important;
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
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.15) 0%, rgba(39, 179, 113, 0.15) 100%) !important;
        color: #1EA38B !important;
        border-color: #1EA38B !important;
        font-weight: 600 !important;
        box-shadow: var(--africode-shadow-md) !important;
        border-left: 4px solid #1EA38B !important;
    }

    .lesson-item.completed {
        color: #27B371 !important;
        border-left: 3px solid #27B371 !important;
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
        border-left: 3px solid #27B371 !important;
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
        max-width: 180px;
        padding: 0.5rem 1rem;   /* boutons de navigation plus fins */
        font-size: 0.9rem;
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
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .watch-progress-fill.complete {
        background: linear-gradient(135deg, #27B371 0%, #1EA38B 100%);
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
        background: linear-gradient(135deg, #1EA38B 0%, #27B371 100%);
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
        background: rgba(30, 163, 139, 0.05);
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
        border-left: 4px solid #1EA38B;
        padding-left: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: var(--gray-600);
        background: rgba(30, 163, 139, 0.05);
        padding: 1rem 1.5rem;
        border-radius: 0 var(--border-radius-sm) var(--border-radius-sm) 0;
    }

    /* Styles pour les liens */
    .text-content a {
        color: #1EA38B;
        text-decoration: none;
        border-bottom: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .text-content a:hover {
        color: #27B371;
        border-bottom-color: #27B371;
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
        background: linear-gradient(135deg, rgba(30, 163, 139, 0.05), rgba(39, 179, 113, 0.05));
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid rgba(30, 163, 139, 0.2);
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
        height: 50vh;          /* réduire la hauteur du conteneur vidéo */
        min-height: 320px;     /* hauteur minimale réduite */
        background: var(--gray-900);
        border-radius: var(--border-radius-sm);
        margin-bottom: 1rem;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .video-container-large iframe {
        width: 90%;      /* taille de la vidéo dans le cadre */
        height: 90%;
        border: none;
        display: block;
        margin: auto;    /* centrer la vidéo dans le cadre */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: var(--border-radius-sm);
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
        }

        .sidebar-toggle::after {
            content: attr(data-tooltip);
            position: absolute;
            right: 65px;
            top: 50%;
            transform: translateY(-50%) scale(0.8);
            background: var(--gray-800);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar-toggle::before {
            content: '';
            position: absolute;
            right: 57px;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-left-color: var(--gray-800);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1000;
        }

        .sidebar-toggle:hover::after,
        .sidebar-toggle:hover::before {
            opacity: 1;
        }

        .lesson-header-top {
            margin-bottom: 1rem;
        }

        .lesson-controls {
            display: none;
        }

        .sidebar-control-hint {
            display: none;
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

        .video-wrapper {
            padding-bottom: 56.25%; /* Maintenir le ratio 16:9 sur mobile */
        }

        .video-container-large {
            height: 50vh;      /* hauteur d'origine sur mobile */
            min-height: 350px;
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
     

    <!-- Main Lesson Container -->
    <div class="lesson-container" id="lessonContainer" data-aos="fade-up" data-aos-delay="100">
        <!-- Sidebar Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- Sidebar with Course Structure -->
        <div class="lesson-sidebar" id="lessonSidebar">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 fw-bold" style="color:#1EA38B;">
                    <i class="fas fa-list me-2"></i>
                    Structure du cours
                </h6>
                <button class="btn btn-sm d-lg-none" style="color:#1EA38B;border:1px solid #1EA38B;" onclick="closeSidebar()">
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
                            $isLocked = $lessonData['status'] === 'locked';
                            $url = $isLocked ? '#' : route('apprenant.lesson', ['lessonId' => $l->id]);
                            $class = 'lesson-item';
                            if ($l->id === $lesson->id) $class .= ' active';
                            if ($lessonData['is_completed']) $class .= ' completed';
                            if ($isLocked) $class .= ' disabled'; // CSS `disabled` class might be needed
                        @endphp
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                <i class="lesson-icon fas {{ $isLocked ? 'fa-lock text-muted' : ($lessonData['is_completed'] ? 'fa-check-circle text-success' : ($l->id === $lesson->id ? 'fa-play-circle' : 'fa-circle')) }}"></i>
                                <span>{{ $l->title }}</span>
                            </div>
                            @if(!$isLocked)
                                <small class="text-muted">{{ $l->duration_minutes }} min</small>
                            @endif
                        </a>
                    @endforeach

                    {{-- Afficher le quiz du module s'il existe --}}
                    @if($moduleData['quiz'])
                        @php
                            $q = $moduleData['quiz']['item'];
                            $isLocked = $moduleData['quiz']['status'] === 'locked';
                            $url = $isLocked ? '#' : route('apprenant.quiz.show', ['quizId' => $q->id]);
                            $class = 'lesson-item quiz-item';
                            if ($moduleData['quiz']['is_completed']) $class .= ' completed';
                            if ($isLocked) $class .= ' disabled';
                            if ($q->is_required) $class .= ' required-quiz';
                        @endphp
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                <i class="lesson-icon fas {{ $isLocked ? 'fa-lock text-muted' : ($moduleData['quiz']['is_completed'] ? 'fa-check-circle text-success' : 'fa-question-circle') }}" style="color:#1EA38B;"></i>
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
                    $isLocked = $courseStructure['final_quiz']['status'] === 'locked';
                    $url = $isLocked ? '#' : route('apprenant.quiz.show', ['quizId' => $q->id]);
                    $class = 'lesson-item quiz-item final-quiz'; // a new class for styling if needed
                    if ($courseStructure['final_quiz']['is_completed']) $class .= ' completed';
                    if ($isLocked) $class .= ' disabled';
                @endphp
                 <div class="module-group mb-3">
                    <div class="module-header">
                        <span>Examen Final</span>
                    </div>
                    <div class="module-lessons">
                        <a href="{{ $url }}" class="{{ $class }}">
                            <div class="d-flex align-items-center">
                                 <i class="lesson-icon fas {{ $isLocked ? 'fa-lock text-muted' : ($courseStructure['final_quiz']['is_completed'] ? 'fa-award text-success' : 'fa-flag-checkered') }}" style=" color: #FF8E2A;"></i>
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
                        <span><i class="fas fa-certificate me-2" ></i>Certificat</span>
                    </div>
                    <div class="module-lessons">
                        @if($certification)
                            <div class="p-3 text-center">
                                <i class="fas fa-award fa-2x text-warning mb-2" style=" color: #FF8E2A;"></i>
                                <div class="fw-bold mb-2">Certificat obtenu !</div>
                                <a href="{{ route('apprenant.certification.download', $certification->id) }}" class="btn text-white w-100 mb-2" style="background:linear-gradient(135deg,#27B371,#1EA38B);border:none;">
                                    <i class="fas fa-file-pdf me-2" style=" color: #FF8E2A;"></i>Voir / Télécharger
                                </a>
                                <div class="small text-muted">Délivré le {{ $certification->issued_at->format('d/m/Y') }}</div>
                                <div class="small mt-1">ID : <span class="fw-bold">{{ $certification->certificate_identifier }}</span></div>
                            </div>
                        @else
                            <div class="p-3 text-center">
                                <i class="fas fa-lock fa-2x text-muted mb-2" style=" color: #FF8E2A;"></i>
                                <div class="fw-bold mb-2">Certificat verrouillé</div>
                                <div class="small text-muted">Complétez toutes les étapes et réussissez l'examen final pour débloquer votre certificat.</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <!-- Main Content -->
        <div class="lesson-content">
            <!-- En-tête local de la leçon : bouton retour + contrôle du menu structure -->
            <div class="lesson-header">
                <div class="lesson-header-top">
                    <div class="lesson-title-section">
                        <a href="{{ route('apprenant.courses') }}" class="btn btn-outline-modern">
                          Aller à mes cours
                        </a>
                    </div>
                    <div class="lesson-controls">
                            <button class="sidebar-toggle" id="sidebarToggleDesktop" onclick="toggleSidebar()" 
                                    data-tooltip="Masquer la structure du cours">       
                                <div class="sidebar-control-hint">
                                <span id="sidebarControlText" style="color: #FF8E2A;">Masquer</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <div class="lesson-body">
                @if($lesson->video_url)
                    @php
                        // Supporte YouTube, Vimeo et fichiers video directs (mp4/webm/ogg)
                        $videoUrl = $lesson->video_url;
                        $isAbsoluteUrl = preg_match('~^https?://~i', $videoUrl) === 1;
                        $videoSrc = $isAbsoluteUrl ? $videoUrl : asset($videoUrl);

                        $youtubeId = '';
                        $vimeoId = '';
                        $path = parse_url($videoUrl, PHP_URL_PATH) ?? '';

                        if (strpos($videoUrl, 'youtube.com') !== false) {
                            parse_str(parse_url($videoUrl, PHP_URL_QUERY), $params);
                            $youtubeId = $params['v'] ?? '';
                        } elseif (strpos($videoUrl, 'youtu.be') !== false) {
                            $youtubeId = ltrim(parse_url($videoUrl, PHP_URL_PATH) ?? '', '/');
                        } elseif (strpos($videoUrl, 'vimeo.com') !== false) {
                            $vimeoId = ltrim(parse_url($videoUrl, PHP_URL_PATH) ?? '', '/');
                        }

                        $isDirectVideo = preg_match('/\.(mp4|webm|ogg)$/i', $path) === 1;
                    @endphp

                    @if($youtubeId)
                        <div class="video-container-large">
                            <iframe
                                src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @elseif($vimeoId)
                        <div class="video-container-large">
                            <iframe
                                src="https://player.vimeo.com/video/{{ $vimeoId }}"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @elseif($isDirectVideo)
                        <div class="video-container-large">
                            <video src="{{ $videoSrc }}" controls style="width: 100%; height: auto; border-radius: 8px;">
                                Votre navigateur ne supporte pas la lecture video.
                            </video>
                        </div>
                    @else
                        <div class="video-container">
                            <div class="video-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle mb-3" style=" color: #FF8E2A;"></i>
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
                                <i class="fas fa-images me-2" style="color: #1EA38B;"></i>
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
                                
                                // Convertir les oEmbed YouTube en vidéos intégrées
                                const oembeds = textContent.querySelectorAll('oembed[url*="youtube.com"], oembed[url*="youtu.be"]');
                                oembeds.forEach(function(oembed) {
                                    const url = oembed.getAttribute('url') || '';
                                    let videoId = '';

                                    if (url.includes('youtube.com/watch?v=')) {
                                        videoId = url.split('v=')[1].split('&')[0];
                                    } else if (url.includes('youtu.be/')) {
                                        videoId = url.split('youtu.be/')[1].split('?')[0];
                                    }

                                    if (videoId) {
                                        const videoContainer = document.createElement('div');
                                        videoContainer.className = 'youtube-video-container';
                                        videoContainer.style.margin = '1.5rem 0';
                                        videoContainer.style.borderRadius = '8px';
                                        videoContainer.style.overflow = 'hidden';
                                        videoContainer.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
                                        videoContainer.style.position = 'relative';
                                        videoContainer.style.paddingBottom = '56.25%';
                                        videoContainer.style.height = '0';

                                        const iframe = document.createElement('iframe');
                                        iframe.src = 'https://www.youtube.com/embed/' + videoId + '?rel=0&modestbranding=1&showinfo=0';
                                        iframe.style.position = 'absolute';
                                        iframe.style.top = '0';
                                        iframe.style.left = '0';
                                        iframe.style.width = '90%';
                                        iframe.style.height = '80%';
                                        iframe.frameBorder = '0';
                                        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                                        iframe.allowFullscreen = true;

                                        videoContainer.appendChild(iframe);
                                        oembed.parentNode.replaceChild(videoContainer, oembed);
                                    }
                                });

                                // Convertir les liens YouTube en vidéos intégrées
                                const links = textContent.querySelectorAll('a[href*="youtube.com"], a[href*="youtu.be"]');

                                links.forEach(function(link) {
                                    const url = link.href;
                                    let videoId = '';

                                    // Extraire l'ID de la vidéo
                                    if (url.includes('youtube.com/watch?v=')) {
                                        videoId = url.split('v=')[1].split('&')[0];
                                    } else if (url.includes('youtu.be/')) {
                                        videoId = url.split('youtu.be/')[1].split('?')[0];
                                    }

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

                                        // Créer l'iframe YouTube
                                        const iframe = document.createElement('iframe');
                                        iframe.src = 'https://www.youtube.com/embed/' + videoId + '?rel=0&modestbranding=1&showinfo=0';
                                        iframe.style.position = 'absolute';
                                        iframe.style.top = '0';
                                        iframe.style.left = '0';
                                        iframe.style.width = '90%';
                                        iframe.style.height = '80%';
                                        iframe.frameBorder = '0';
                                        iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                                        iframe.allowFullscreen = true;

                                        videoContainer.appendChild(iframe);

                                        // Remplacer le lien par la vidéo
                                        link.parentNode.replaceChild(videoContainer, link);
                                    }
                                });
                            }
                        });
                    </script>
                @endif

                @if($lesson->content_type === 'pdf' && $lesson->pdf_path)
                    <div class="text-center mb-4">
                        <a href="{{ asset($lesson->pdf_path) }}" class="btn btn-modern" target="_blank">
                            <i class="fas fa-file-pdf me-2" style=" color: #FF8E2A;"></i> Ouvrir le PDF
                        </a>
                    </div>
                    <embed src="{{ asset($lesson->pdf_path) }}" type="application/pdf" width="100%" height="600px" 
                           style="border-radius: var(--border-radius-sm);" />
                @endif
                
                @if($lesson->content_type === 'external' && $lesson->external_url)
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-external-link-alt display-3" style=" color: #FF8E2A;"></i>
                        </div>
                        <h5 class="mb-3">Contenu externe</h5>
                        <p class="text-muted mb-4">Cette leçon est hébergée sur une plateforme externe.</p>
                        <a href="{{ $lesson->external_url }}" class="btn btn-modern" target="_blank">
                            <i class="fas fa-external-link-alt me-2" style=" color: #FF8E2A;"></i> Accéder au contenu
                        </a>
                    </div>
                @endif
                
                @if(isset($resources) && $resources->isNotEmpty())
                    <div class="resources-section">
                        <h5 class="mb-3">
                            <i class="fas fa-download me-2" style=" color: #FF8E2A;"></i>
                            Ressources supplémentaires
                        </h5>
                        
                        @foreach($resources as $resource)
                        <div class="resource-item">
                            <div class="resource-icon">
                                @switch($resource->type)
                                    @case('pdf')
                                        <i class="fas fa-file-pdf" style=" color: #FF8E2A;"></i>
                                        @break
                                    @case('archive')
                                        <i class="fas fa-file-archive" style=" color: #FF8E2A;"></i>
                                        @break
                                    @case('image')
                                        <i class="fas fa-file-image" style=" color: #FF8E2A;"></i>
                                        @break
                                    @case('code')
                                        <i class="fas fa-file-code" style=" color: #FF8E2A;"></i>
                                        @break
                                    @default
                                        <i class="fas fa-file" style=" color: #FF8E2A;"></i>
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
            </div>

            <div class="lesson-footer">
                <div class="navigation-buttons">
                    @if(isset(
                        $previousLesson
                    ))
                    <a href="{{ route('apprenant.lesson', ['lessonId' => $previousLesson->id]) }}" 
                       class="btn btn-outline-modern btn-nav">
                        <i class="fas fa-arrow-left me-2" style=" color: #FF8E2A;"></i>Précédent
                    </a>
                    @else
                    <div></div>
                    @endif
                    
                    <div class="d-flex gap-2">
                        @if(!$isCompleted)
                        <form action="{{ route('apprenant.lesson.complete', ['lessonId' => $lesson->id]) }}" method="POST" class="d-inline" id="completeLessonForm" style="display: {{ in_array($lesson->content_type, ['text', 'pdf', 'external']) ? 'inline-block' : 'none' }};">
                            @csrf
                            <button type="submit" class="btn btn-modern" id="completeLessonBtn">
                                <i class="fas fa-check-circle me-2"  style=" color: #FF8E2A;" ></i>Marquer terminée
                            </button>
                        </form>
                        <div id="watchProgressInfo" class="d-flex align-items-center" style="display: {{ in_array($lesson->content_type, ['text', 'pdf', 'external']) ? 'none' : 'flex' }};">
                            <div class="watch-progress-container me-3">
                                <div class="watch-progress-bar">
                                    <div class="watch-progress-fill" id="watchProgressFill" style="width: 0%"></div>
                                </div>
                                <small class="text-muted" id="watchProgressText">Visionnage: 0%</small>
                            </div>
                            {{-- <div id="watchTimeRemaining" class="text-muted small">
                                Continuez à regarder pour débloquer le bouton de validation
                            </div> --}}
                        </div>
                        @endif
                        
                        {{-- Blocage navigation module suivant --}}
                        @if($isLastLessonOfModule && $moduleQuiz)
                            @if(!$moduleQuizPassed)
                                <a href="{{ route('apprenant.quiz.show', ['quizId' => $moduleQuiz->id]) }}" class="btn btn-modern btn-nav">
                                    <i class="fas fa-question-circle me-2" style=" color: #FF8E2A;" ></i>Passer le quiz du module
                                </a>
                            @else
                                @if($firstLessonNextModule)
                                    <a href="{{ route('apprenant.lesson', ['lessonId' => $firstLessonNextModule->id]) }}" class="btn btn-modern btn-nav">
                                        Module suivant <i class="fas fa-arrow-right ms-2" style=" color: #FF8E2A;"></i>
                                    </a>
                                @else
                                    <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-modern btn-nav">
                                        <i class="fas fa-home me-2" style=" color: #FF8E2A;"></i>Tableau de bord
                                    </a>
                                @endif
                            @endif
                        @else
                            @if(isset($nextLesson))
                                <a href="{{ route('apprenant.lesson', ['lessonId' => $nextLesson->id]) }}" 
                                   class="btn btn-modern btn-nav">
                                    Suivant<i class="fas fa-arrow-right ms-2" style=" color: #FF8E2A;"></i>
                                </a>
                            @else
                                <a href="{{ route('apprenant.dashboard') }}" class="btn btn-outline-modern btn-nav">
                                    <i class="fas fa-home me-2" style=" color: #FF8E2A;"></i>Tableau de bord
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
    let sidebarVisible = true;
    
    // Variables pour le suivi du temps de visionnage
    let watchStartTime = null;
    let totalWatchTime = {{ $watchTimeSeconds }};
    let canComplete = {{ $canComplete ? 'true' : 'false' }};
    let lessonDuration = {{ $lesson->duration_minutes * 60 }};
    let watchInterval = null;
    let isVideoPlaying = false;
    let lastUpdateTime = Date.now();
    let lessonContentType = '{{ $lesson->content_type }}';
    let isTextBasedLesson = ['text', 'pdf', 'external'].includes(lessonContentType);
    
    
    // Initialiser le suivi du temps
    function initWatchTimeTracking() {
        // Pour les leçons basées sur du texte, afficher le bouton immédiatement
        if (isTextBasedLesson) {
            showCompleteButton();
            return;
        }
        
        // Pour les leçons vidéo, afficher la barre de progression
        showProgressBar();
        
        // Démarrer le suivi du temps
        startWatchTimeTracking();
        
        // Mettre à jour l'affichage initial
        updateWatchProgress();
        
        // Vérifier si le bouton doit être affiché
        if (canComplete) {
            showCompleteButton();
        }
    }
    
    // Démarrer le suivi du temps
    function startWatchTimeTracking() {
        // Pour les leçons de type texte, ne pas démarrer le suivi
        if (isTextBasedLesson) {
            return;
        }
        
        if (watchStartTime === null) {
            watchStartTime = Date.now();
        }
        
        // Mettre à jour toutes les 5 secondes
        watchInterval = setInterval(updateWatchTime, 5000);
        
        // Écouter les événements de la vidéo si c'est une vidéo YouTube
        const videoContainer = document.querySelector('.video-container-large iframe');
        if (videoContainer) {
            // Pour les vidéos YouTube, on ne peut pas détecter directement la lecture
            // On assume que l'utilisateur regarde s'il est sur la page
            isVideoPlaying = true;
        }
    }
    
    // Arrêter le suivi du temps
    function stopWatchTimeTracking() {
        // Pour les leçons de type texte, ne pas arrêter le suivi
        if (isTextBasedLesson) {
            return;
        }
        
        if (watchInterval) {
            clearInterval(watchInterval);
            watchInterval = null;
        }
    }
    
    // Mettre à jour le temps de visionnage
    function updateWatchTime() {
        // Pour les leçons de type texte, ne pas mettre à jour le temps
        if (isTextBasedLesson || !isVideoPlaying) return;
        
        const currentTime = Date.now();
        const timeDiff = (currentTime - lastUpdateTime) / 1000; // en secondes
        lastUpdateTime = currentTime;
        
        totalWatchTime += timeDiff;
        
        // Envoyer la mise à jour au serveur
        updateWatchTimeOnServer();
        
        // Mettre à jour l'affichage
        updateWatchProgress();
    }
    
    // Mettre à jour le temps sur le serveur
    function updateWatchTimeOnServer() {
        // Pour les leçons de type texte, ne pas envoyer de mise à jour
        if (isTextBasedLesson) {
            return;
        }
        
        fetch('{{ route("apprenant.lesson.watch-time", ["lessonId" => $lesson->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                watch_time_seconds: Math.floor(totalWatchTime)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                canComplete = data.can_complete;
                if (canComplete && !document.getElementById('completeLessonForm').style.display) {
                    showCompleteButton();
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour du temps:', error);
        });
    }
    
    // Mettre à jour l'affichage de la progression
    function updateWatchProgress() {
        // Pour les leçons de type texte, ne pas afficher la progression
        if (isTextBasedLesson) {
            return;
        }
        
        const percentage = lessonDuration > 0 ? (totalWatchTime / lessonDuration) * 100 : 0;
        const progressFill = document.getElementById('watchProgressFill');
        const progressText = document.getElementById('watchProgressText');
        const timeRemaining = document.getElementById('watchTimeRemaining');
        
        if (progressFill) {
            progressFill.style.width = Math.min(percentage, 100) + '%';
            if (percentage >= 75) {
                progressFill.classList.add('complete');
            }
        }
        
        if (progressText) {
            progressText.textContent = `Visionnage: ${Math.round(percentage)}%`;
        }
        
        if (timeRemaining && percentage < 75) {
            const remainingSeconds = Math.max(0, (lessonDuration * 0.75) - totalWatchTime);
            const remainingMinutes = Math.ceil(remainingSeconds / 60);
            timeRemaining.textContent = `Encore ${remainingMinutes} min pour débloquer le bouton`;
        } else if (timeRemaining && percentage >= 75) {
            timeRemaining.textContent = 'Vous pouvez maintenant marquer cette leçon comme terminée';
        }
    }
    
    // Afficher le bouton de validation
    function showCompleteButton() {
        const form = document.getElementById('completeLessonForm');
        const progressInfo = document.getElementById('watchProgressInfo');
        
        if (form) {
            form.style.display = 'inline-block';
        }
        
        if (progressInfo) {
            progressInfo.style.display = 'none';
        }
    }
    
    // Afficher la barre de progression (pour les leçons vidéo)
    function showProgressBar() {
        const progressInfo = document.getElementById('watchProgressInfo');
        const form = document.getElementById('completeLessonForm');
        
        if (progressInfo) {
            progressInfo.style.display = 'flex';
        }
        
        if (form) {
            form.style.display = 'none';
        }
    }
    
    // Détecter si l'utilisateur quitte la page
    window.addEventListener('beforeunload', function() {
        stopWatchTimeTracking();
        if (totalWatchTime > 0 && !isTextBasedLesson) {
            // Envoyer une dernière mise à jour seulement pour les leçons vidéo
            navigator.sendBeacon('{{ route("apprenant.lesson.watch-time", ["lessonId" => $lesson->id]) }}', 
                JSON.stringify({
                    watch_time_seconds: Math.floor(totalWatchTime),
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                })
            );
        }
    });
    
    // Détecter si l'utilisateur change d'onglet
    document.addEventListener('visibilitychange', function() {
        // Pour les leçons de type texte, ne pas gérer le changement d'onglet
        if (isTextBasedLesson) {
            return;
        }
        
        if (document.hidden) {
            stopWatchTimeTracking();
        } else {
            startWatchTimeTracking();
        }
    });

    function toggleSidebar() {
        const container = document.getElementById('lessonContainer');
        const sidebar = document.getElementById('lessonSidebar');
        const toggleIconDesktop = document.getElementById('toggleIconDesktop');
        const toggleIcon = document.getElementById('toggleIcon');
        const overlay = document.getElementById('sidebarOverlay');
        const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
        const sidebarControlText = document.getElementById('sidebarControlText');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        sidebarVisible = !sidebarVisible;
        
        if (sidebarVisible) {
            // Show sidebar
            container.classList.remove('sidebar-hidden');
            sidebar.classList.remove('hidden');
            
            if (toggleIconDesktop && sidebarToggleDesktop && sidebarControlText) {
                toggleIconDesktop.classList.remove('fa-eye');
                toggleIconDesktop.classList.add('fa-eye-slash');
                sidebarToggleDesktop.setAttribute('data-tooltip', 'Masquer la structure du cours');
                sidebarControlText.textContent = 'Masquer le menu';
            }
            
            // Mobile specific
            if (window.innerWidth <= 1024) {
                sidebar.classList.add('visible');
                overlay.classList.add('visible');
                if (toggleIcon && sidebarToggle) {
                    toggleIcon.classList.remove('fa-times');
                    toggleIcon.classList.add('fa-list');
                    sidebarToggle.setAttribute('data-tooltip', 'Voir la structure du cours');
                }
            }
        } else {
            // Hide sidebar
            container.classList.add('sidebar-hidden');
            sidebar.classList.add('hidden');
            
            if (toggleIconDesktop && sidebarToggleDesktop && sidebarControlText) {
                toggleIconDesktop.classList.remove('fa-eye-slash');
                toggleIconDesktop.classList.add('fa-eye');
                sidebarToggleDesktop.setAttribute('data-tooltip', 'Structure du cours');
                sidebarControlText.textContent = 'Afficher';
            }
            
            // Mobile specific
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('visible');
                overlay.classList.remove('visible');
                if (toggleIcon && sidebarToggle) {
                    toggleIcon.classList.remove('fa-list');
                    toggleIcon.classList.add('fa-times');
                    sidebarToggle.setAttribute('data-tooltip', 'Fermer le menu');
                }
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
            const moduleId = activeLesson.closest('.module-group').querySelector('.module-header').getAttribute('onclick').match(/\d+/)[0];
            const lessons = document.getElementById('lessons-' + moduleId);
            const icon = document.getElementById('icon-' + moduleId);
            
            lessons.style.display = 'block';
            icon.classList.remove('fa-chevron-right');
            icon.classList.add('fa-chevron-down');
        }

        // Initialize sidebar state for mobile
        if (window.innerWidth <= 1024) {
            const container = document.getElementById('lessonContainer');
            const sidebar = document.getElementById('lessonSidebar');
            container.classList.add('sidebar-hidden');
            sidebar.classList.add('hidden');
            sidebarVisible = false;
            
            const toggleIcon = document.getElementById('toggleIcon');
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (toggleIcon && sidebarToggle) {
                toggleIcon.classList.remove('fa-list');
                toggleIcon.classList.add('fa-times');
                sidebarToggle.setAttribute('data-tooltip', 'Fermer la structure du cours');
            }
        }
        
        // Initialiser le suivi du temps de visionnage
        initWatchTimeTracking();
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
