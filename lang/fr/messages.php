<?php

return [
    // Messages généraux
    'welcome' => 'AfriCode - Cours en ligne : Apprenez ce que vous voulez, à votre rythme',
    'login' => 'Connexion',
    'register' => 'Inscription',
    'logout' => 'Déconnexion',
    'profile' => 'Profil',
    'settings' => 'Paramètres',
    'save' => 'Enregistrer',
    'cancel' => 'Annuler',
    'delete' => 'Supprimer',
    'edit' => 'Modifier',
    'create' => 'Créer',
    'search' => 'Rechercher',
    'loading' => 'Chargement...',

    // Messages de validation
    'required' => 'Ce champ est obligatoire',
    'email' => 'Veuillez entrer une adresse email valide',
    'min' => 'Ce champ doit contenir au moins :min caractères',
    'max' => 'Ce champ ne peut pas dépasser :max caractères',
    'unique' => 'Cette valeur est déjà utilisée',
    'confirmed' => 'La confirmation ne correspond pas',

    // Messages de succès
    'success' => 'Opération réussie',
    'created' => ':item a été créé avec succès.',
    'updated' => ':item a été mis à jour avec succès.',
    'deleted' => ':item a été supprimé avec succès.',

    // Messages d'erreur
    'error' => 'Une erreur est survenue',
    'not_found' => ':item non trouvé',
    'unauthorized' => 'Non autorisé',
    'forbidden' => 'Accès interdit',

    // Messages de tutorat
    'tutoring' => [
        'session' => [
            'created' => 'Session créée avec succès',
            'updated' => 'Session mise à jour avec succès',
            'deleted' => 'Session supprimée avec succès',
            'not_found' => 'Session non trouvée',
            'already_booked' => 'Ce créneau est déjà réservé',
            'invalid_time' => 'Créneau horaire invalide',
            'past_time' => 'Impossible de sélectionner un créneau dans le passé',
            'duration' => 'La durée doit être entre 30 minutes et 2 heures',
        ],
        'feedback' => [
            'created' => 'Feedback créé avec succès',
            'updated' => 'Feedback mis à jour avec succès',
            'deleted' => 'Feedback supprimé avec succès',
            'not_found' => 'Feedback non trouvé',
            'already_exists' => 'Vous avez déjà laissé un feedback pour cette session',
            'session_not_completed' => 'La session doit être terminée avant de laisser un feedback',
        ],
        'tutor' => [
            'created' => 'Tuteur créé avec succès',
            'updated' => 'Tuteur mis à jour avec succès',
            'deleted' => 'Tuteur supprimé avec succès',
            'not_found' => 'Tuteur non trouvé',
            'availability_updated' => 'Disponibilité mise à jour avec succès',
        ],
    ],

    // Messages de cours
    'course' => [
        'created' => 'Cours créé avec succès',
        'updated' => 'Cours mis à jour avec succès',
        'deleted' => 'Cours supprimé avec succès',
        'not_found' => 'Cours non trouvé',
        'enrolled' => 'Inscription au cours réussie',
        'unenrolled' => 'Désinscription du cours réussie',
        'already_enrolled' => 'Vous êtes déjà inscrit à ce cours',
        'not_enrolled' => 'Vous n\'êtes pas inscrit à ce cours',
    ],

    // Messages de quiz
    'quiz' => [
        'created' => 'Quiz créé avec succès',
        'updated' => 'Quiz mis à jour avec succès',
        'deleted' => 'Quiz supprimé avec succès',
        'not_found' => 'Quiz non trouvé',
        'started' => 'Quiz démarré',
        'completed' => 'Quiz terminé',
        'already_started' => 'Vous avez déjà commencé ce quiz',
        'not_started' => 'Vous n\'avez pas encore commencé ce quiz',
        'time_expired' => 'Le temps est écoulé',
    ],

    // Messages de paiement
    'payment' => [
        'success' => 'Paiement réussi',
        'failed' => 'Paiement échoué',
        'pending' => 'Paiement en attente',
        'refunded' => 'Paiement remboursé',
        'not_found' => 'Paiement non trouvé',
    ],

    // Messages de notification
    'notification' => [
        'created' => 'Notification créée avec succès',
        'updated' => 'Notification mise à jour avec succès',
        'deleted' => 'Notification supprimée avec succès',
        'not_found' => 'Notification non trouvée',
        'marked_as_read' => 'Notification marquée comme lue',
        'marked_as_unread' => 'Notification marquée comme non lue',
    ],

    // Meta tags
    'meta' => [
        'description' => 'AfriCode est une plateforme d\'apprentissage en ligne avec plus de 500 cours. Apprenez la programmation, le développement web, mobile, l\'IA et plus encore.',
        'keywords' => 'cours en ligne, programmation, développement web, afrique, coder, apprendre à coder, formation informatique',
    ],

    // Hero section
    'hero' => [
        'title' => 'Des compétences qui vous donnent confiance',
        'search_placeholder' => 'Que souhaitez-vous apprendre ?',
    ],

    // Statistiques
    'stats' => [
        'active_students' => 'Apprenants actifs',
        'available_courses' => 'Cours disponibles',
        'satisfaction_rate' => 'Taux de satisfaction',
        'expert_instructors' => 'Instructeurs experts',
    ],

    // Messages d'authentification
    'auth' => [
        'login' => [
            'success' => 'Connexion réussie',
            'failed' => 'Échec de la connexion',
            'invalid_credentials' => 'Email ou mot de passe incorrect',
        ],
        'register' => [
            'success' => 'Inscription réussie',
            'failed' => 'Échec de l\'inscription',
        ],
        'logout' => [
            'success' => 'Déconnexion réussie',
        ],
    ],

    // Messages de validation
    'validation' => [
        'required' => 'Le champ :attribute est obligatoire',
        'email' => 'Le champ :attribute doit être une adresse email valide',
        'min' => [
            'string' => 'Le champ :attribute doit contenir au moins :min caractères',
            'numeric' => 'Le champ :attribute doit être au moins :min',
        ],
        'max' => [
            'string' => 'Le champ :attribute ne peut pas dépasser :max caractères',
            'numeric' => 'Le champ :attribute ne peut pas dépasser :max',
        ],
        'unique' => 'Cette valeur est déjà utilisée',
        'confirmed' => 'La confirmation ne correspond pas',
    ],
]; 