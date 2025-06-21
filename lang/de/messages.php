<?php

return [
    // Allgemeine Nachrichten
    'welcome' => 'Willkommen bei AfriCode',
    'login' => 'Anmelden',
    'register' => 'Registrieren',
    'logout' => 'Abmelden',
    'profile' => 'Profil',
    'settings' => 'Einstellungen',
    'save' => 'Speichern',
    'cancel' => 'Abbrechen',
    'delete' => 'Löschen',
    'edit' => 'Bearbeiten',
    'create' => 'Erstellen',
    'search' => 'Suchen',
    'loading' => 'Laden...',

    // Validierungsnachrichten
    'required' => 'Das Feld :attribute ist erforderlich.',
    'email' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'min' => 'Das Feld :attribute muss mindestens :min Zeichen lang sein.',
    'max' => 'Das Feld :attribute darf nicht länger als :max Zeichen sein.',
    'unique' => 'Dieser Wert wird bereits verwendet.',
    'confirmed' => 'Die Bestätigung stimmt nicht überein.',

    // Erfolgsnachrichten
    'success' => 'Operation erfolgreich',
    'created' => ':item wurde erfolgreich erstellt.',
    'updated' => ':item wurde erfolgreich aktualisiert.',
    'deleted' => ':item wurde erfolgreich gelöscht.',

    // Fehlermeldungen
    'error' => 'Ein Fehler ist aufgetreten',
    'not_found' => ':item nicht gefunden',
    'unauthorized' => 'Nicht autorisiert',
    'forbidden' => 'Zugriff verweigert',

    // Nachhilfe-Nachrichten
    'tutoring' => [
        'session' => [
            'created' => 'Nachhilfestunde erfolgreich erstellt',
            'updated' => 'Nachhilfestunde erfolgreich aktualisiert',
            'deleted' => 'Nachhilfestunde erfolgreich gelöscht',
            'started' => 'Nachhilfestunde gestartet',
            'completed' => 'Nachhilfestunde abgeschlossen',
            'cancelled' => 'Nachhilfestunde abgesagt',
            'not_found' => 'Nachhilfestunde nicht gefunden',
            'already_started' => 'Die Stunde hat bereits begonnen',
            'already_completed' => 'Die Stunde ist bereits abgeschlossen',
            'already_cancelled' => 'Die Stunde ist bereits abgesagt',
            'cannot_start' => 'Die Stunde kann nicht gestartet werden',
            'cannot_complete' => 'Die Stunde kann nicht abgeschlossen werden',
            'cannot_cancel' => 'Die Stunde kann nicht abgesagt werden',
        ],
        'feedback' => [
            'created' => 'Feedback erfolgreich erstellt',
            'updated' => 'Feedback erfolgreich aktualisiert',
            'deleted' => 'Feedback erfolgreich gelöscht',
            'not_found' => 'Feedback nicht gefunden',
            'already_exists' => 'Sie haben bereits Feedback für diese Stunde abgegeben',
            'session_not_completed' => 'Die Stunde muss abgeschlossen sein, bevor Feedback gegeben werden kann',
        ],
        'tutor' => [
            'created' => 'Nachhilfelehrer erfolgreich erstellt',
            'updated' => 'Nachhilfelehrer erfolgreich aktualisiert',
            'deleted' => 'Nachhilfelehrer erfolgreich gelöscht',
            'not_found' => 'Nachhilfelehrer nicht gefunden',
            'availability_updated' => 'Verfügbarkeit erfolgreich aktualisiert',
        ],
    ],

    // Kursnachrichten
    'course' => [
        'created' => 'Kurs erfolgreich erstellt',
        'updated' => 'Kurs erfolgreich aktualisiert',
        'deleted' => 'Kurs erfolgreich gelöscht',
        'not_found' => 'Kurs nicht gefunden',
        'enrolled' => 'Erfolgreich für den Kurs eingeschrieben',
        'unenrolled' => 'Erfolgreich vom Kurs abgemeldet',
        'already_enrolled' => 'Sie sind bereits für diesen Kurs eingeschrieben',
        'not_enrolled' => 'Sie sind nicht für diesen Kurs eingeschrieben',
    ],

    // Quiz-Nachrichten
    'quiz' => [
        'created' => 'Quiz erfolgreich erstellt',
        'updated' => 'Quiz erfolgreich aktualisiert',
        'deleted' => 'Quiz erfolgreich gelöscht',
        'not_found' => 'Quiz nicht gefunden',
        'started' => 'Quiz gestartet',
        'completed' => 'Quiz abgeschlossen',
        'already_started' => 'Sie haben dieses Quiz bereits begonnen',
        'not_started' => 'Sie haben dieses Quiz noch nicht begonnen',
        'time_expired' => 'Die Zeit ist abgelaufen',
    ],

    // Zahlungsnachrichten
    'payment' => [
        'success' => 'Zahlung erfolgreich',
        'failed' => 'Zahlung fehlgeschlagen',
        'pending' => 'Zahlung ausstehend',
        'refunded' => 'Zahlung erstattet',
        'not_found' => 'Zahlung nicht gefunden',
    ],

    // Benachrichtigungsnachrichten
    'notification' => [
        'created' => 'Benachrichtigung erfolgreich erstellt',
        'updated' => 'Benachrichtigung erfolgreich aktualisiert',
        'deleted' => 'Benachrichtigung erfolgreich gelöscht',
        'not_found' => 'Benachrichtigung nicht gefunden',
        'marked_as_read' => 'Benachrichtigung als gelesen markiert',
        'marked_as_unread' => 'Benachrichtigung als ungelesen markiert',
    ],
]; 