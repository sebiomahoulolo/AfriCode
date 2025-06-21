<?php

return [
    // General messages
    'welcome' => 'Welcome to AfriCode',
    'login' => 'Login',
    'register' => 'Register',
    'logout' => 'Logout',
    'profile' => 'Profile',
    'settings' => 'Settings',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'search' => 'Search',
    'loading' => 'Loading...',

    // Validation messages
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'min' => 'The :attribute must be at least :min characters.',
    'max' => 'The :attribute may not be greater than :max characters.',
    'unique' => 'This value is already in use.',
    'confirmed' => 'The confirmation does not match.',

    // Success messages
    'success' => 'Operation successful',
    'created' => ':item has been created successfully.',
    'updated' => ':item has been updated successfully.',
    'deleted' => ':item has been deleted successfully.',

    // Error messages
    'error' => 'An error occurred',
    'not_found' => ':item not found',
    'unauthorized' => 'Unauthorized',
    'forbidden' => 'Forbidden',

    // Tutoring messages
    'tutoring' => [
        'session' => [
            'created' => 'Tutoring session created successfully',
            'updated' => 'Tutoring session updated successfully',
            'deleted' => 'Tutoring session deleted successfully',
            'started' => 'Tutoring session started',
            'completed' => 'Tutoring session completed',
            'cancelled' => 'Tutoring session cancelled',
            'not_found' => 'Tutoring session not found',
            'already_started' => 'Session has already started',
            'already_completed' => 'Session is already completed',
            'already_cancelled' => 'Session is already cancelled',
            'cannot_start' => 'Cannot start the session',
            'cannot_complete' => 'Cannot complete the session',
            'cannot_cancel' => 'Cannot cancel the session',
        ],
        'feedback' => [
            'created' => 'Feedback created successfully',
            'updated' => 'Feedback updated successfully',
            'deleted' => 'Feedback deleted successfully',
            'not_found' => 'Feedback not found',
            'already_exists' => 'You have already provided feedback for this session',
            'session_not_completed' => 'Session must be completed before providing feedback',
        ],
        'tutor' => [
            'created' => 'Tutor created successfully',
            'updated' => 'Tutor updated successfully',
            'deleted' => 'Tutor deleted successfully',
            'not_found' => 'Tutor not found',
            'availability_updated' => 'Availability updated successfully',
        ],
    ],

    // Course messages
    'course' => [
        'created' => 'Course created successfully',
        'updated' => 'Course updated successfully',
        'deleted' => 'Course deleted successfully',
        'not_found' => 'Course not found',
        'enrolled' => 'Successfully enrolled in course',
        'unenrolled' => 'Successfully unenrolled from course',
        'already_enrolled' => 'You are already enrolled in this course',
        'not_enrolled' => 'You are not enrolled in this course',
    ],

    // Quiz messages
    'quiz' => [
        'created' => 'Quiz created successfully',
        'updated' => 'Quiz updated successfully',
        'deleted' => 'Quiz deleted successfully',
        'not_found' => 'Quiz not found',
        'started' => 'Quiz started',
        'completed' => 'Quiz completed',
        'already_started' => 'You have already started this quiz',
        'not_started' => 'You have not started this quiz yet',
        'time_expired' => 'Time has expired',
    ],

    // Payment messages
    'payment' => [
        'success' => 'Payment successful',
        'failed' => 'Payment failed',
        'pending' => 'Payment pending',
        'refunded' => 'Payment refunded',
        'not_found' => 'Payment not found',
    ],

    // Notification messages
    'notification' => [
        'created' => 'Notification created successfully',
        'updated' => 'Notification updated successfully',
        'deleted' => 'Notification deleted successfully',
        'not_found' => 'Notification not found',
        'marked_as_read' => 'Notification marked as read',
        'marked_as_unread' => 'Notification marked as unread',
    ],
]; 