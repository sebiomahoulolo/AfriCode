<?php

return [
    // Mensajes generales
    'welcome' => 'Bienvenido a AfriCode',
    'login' => 'Iniciar sesión',
    'register' => 'Registrarse',
    'logout' => 'Cerrar sesión',
    'profile' => 'Perfil',
    'settings' => 'Configuración',
    'save' => 'Guardar',
    'cancel' => 'Cancelar',
    'delete' => 'Eliminar',
    'edit' => 'Editar',
    'create' => 'Crear',
    'search' => 'Buscar',
    'loading' => 'Cargando...',

    // Mensajes de validación
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'min' => 'El campo :attribute debe tener al menos :min caracteres.',
    'max' => 'El campo :attribute no puede tener más de :max caracteres.',
    'unique' => 'Este valor ya está en uso.',
    'confirmed' => 'La confirmación no coincide.',

    // Mensajes de éxito
    'success' => 'Operación exitosa',
    'created' => ':item ha sido creado exitosamente.',
    'updated' => ':item ha sido actualizado exitosamente.',
    'deleted' => ':item ha sido eliminado exitosamente.',

    // Mensajes de error
    'error' => 'Ha ocurrido un error',
    'not_found' => ':item no encontrado',
    'unauthorized' => 'No autorizado',
    'forbidden' => 'Acceso denegado',

    // Mensajes de tutoría
    'tutoring' => [
        'session' => [
            'created' => 'Sesión de tutoría creada exitosamente',
            'updated' => 'Sesión de tutoría actualizada exitosamente',
            'deleted' => 'Sesión de tutoría eliminada exitosamente',
            'started' => 'Sesión de tutoría iniciada',
            'completed' => 'Sesión de tutoría completada',
            'cancelled' => 'Sesión de tutoría cancelada',
            'not_found' => 'Sesión de tutoría no encontrada',
            'already_started' => 'La sesión ya ha comenzado',
            'already_completed' => 'La sesión ya está completada',
            'already_cancelled' => 'La sesión ya está cancelada',
            'cannot_start' => 'No se puede iniciar la sesión',
            'cannot_complete' => 'No se puede completar la sesión',
            'cannot_cancel' => 'No se puede cancelar la sesión',
        ],
        'feedback' => [
            'created' => 'Comentario creado exitosamente',
            'updated' => 'Comentario actualizado exitosamente',
            'deleted' => 'Comentario eliminado exitosamente',
            'not_found' => 'Comentario no encontrado',
            'already_exists' => 'Ya has dejado un comentario para esta sesión',
            'session_not_completed' => 'La sesión debe estar completada antes de dejar un comentario',
        ],
        'tutor' => [
            'created' => 'Tutor creado exitosamente',
            'updated' => 'Tutor actualizado exitosamente',
            'deleted' => 'Tutor eliminado exitosamente',
            'not_found' => 'Tutor no encontrado',
            'availability_updated' => 'Disponibilidad actualizada exitosamente',
        ],
    ],

    // Mensajes de cursos
    'course' => [
        'created' => 'Curso creado exitosamente',
        'updated' => 'Curso actualizado exitosamente',
        'deleted' => 'Curso eliminado exitosamente',
        'not_found' => 'Curso no encontrado',
        'enrolled' => 'Inscripción al curso exitosa',
        'unenrolled' => 'Desinscripción del curso exitosa',
        'already_enrolled' => 'Ya estás inscrito en este curso',
        'not_enrolled' => 'No estás inscrito en este curso',
    ],

    // Mensajes de cuestionarios
    'quiz' => [
        'created' => 'Cuestionario creado exitosamente',
        'updated' => 'Cuestionario actualizado exitosamente',
        'deleted' => 'Cuestionario eliminado exitosamente',
        'not_found' => 'Cuestionario no encontrado',
        'started' => 'Cuestionario iniciado',
        'completed' => 'Cuestionario completado',
        'already_started' => 'Ya has comenzado este cuestionario',
        'not_started' => 'Aún no has comenzado este cuestionario',
        'time_expired' => 'El tiempo ha expirado',
    ],

    // Mensajes de pago
    'payment' => [
        'success' => 'Pago exitoso',
        'failed' => 'Pago fallido',
        'pending' => 'Pago pendiente',
        'refunded' => 'Pago reembolsado',
        'not_found' => 'Pago no encontrado',
    ],

    // Mensajes de notificación
    'notification' => [
        'created' => 'Notificación creada exitosamente',
        'updated' => 'Notificación actualizada exitosamente',
        'deleted' => 'Notificación eliminada exitosamente',
        'not_found' => 'Notificación no encontrada',
        'marked_as_read' => 'Notificación marcada como leída',
        'marked_as_unread' => 'Notificación marcada como no leída',
    ],
]; 