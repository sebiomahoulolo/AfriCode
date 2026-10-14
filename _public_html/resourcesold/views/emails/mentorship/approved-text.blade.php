AfriCode — Votre mentorat est approuvé !
==========================================

Félicitations {{ $mentee->first_name }} !

{{ $formateur->first_name }} {{ $formateur->last_name }} a accepté votre demande de mentorat.

DÉTAILS DU MENTORAT
--------------------
Mentor       : {{ $formateur->first_name }} {{ $formateur->last_name }}
Email mentor : {{ $formateur->email }}
Domaine      : {{ $mentorship->specialization_area }}
Approuvé le  : {{ $mentorship->approved_at->format('d/m/Y à H\hi') }}

CONSEILS POUR BIEN DÉMARRER
-----------------------------
- Présentez-vous et définissez vos objectifs d'apprentissage
- Préparez vos questions et vos points bloquants
- Soyez régulier et respectez les engagements pris
- Partagez vos avancées et vos difficultés

Accéder à votre mentorat : {{ $mentorshipUrl }}

---
© {{ date('Y') }} AfriCode — africode.tech — contact@africode.tech
