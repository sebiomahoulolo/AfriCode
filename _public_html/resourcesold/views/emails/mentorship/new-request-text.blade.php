AfriCode — Nouvelle demande de mentorat
========================================

Bonjour {{ $formateur->first_name }} {{ $formateur->last_name }},

Vous avez reçu une nouvelle demande de mentorat.

DÉTAILS DE LA DEMANDE
----------------------
Apprenant    : {{ $mentee->first_name }} {{ $mentee->last_name }}
Email        : {{ $mentee->email }}
Domaine      : {{ $mentorship->specialization_area }}
Date         : {{ $mentorship->requested_at->format('d/m/Y à H\hi') }}
@if($mentorship->message)

Message de l'apprenant :
{{ $mentorship->message }}
@endif

Répondre à la demande : {{ $requestsUrl }}

---
© {{ date('Y') }} AfriCode — africode.tech — contact@africode.tech
