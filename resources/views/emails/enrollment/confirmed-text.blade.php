AfriCode — Inscription confirmée !
====================================

Félicitations {{ $user->first_name }} !

Votre inscription au cours a bien été confirmée.

DÉTAILS DU COURS
-----------------
Titre        : {{ $course->title }}
Formateur    : {{ $course->formateur->first_name ?? '' }} {{ $course->formateur->last_name ?? '' }}
Accès        : @if($isFree)Gratuit@else{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency ?? 'XOF' }}@endif
Inscrit le   : {{ now()->format('d/m/Y') }}

PAR OÙ COMMENCER ?
--------------------
1. Accédez au cours et parcourez la présentation générale
2. Suivez les modules dans l'ordre recommandé par le formateur
3. Validez chaque leçon et participez aux quiz
4. Posez vos questions dans le forum du cours si vous bloquez

Accéder au cours : {{ $courseUrl }}

Besoin d'aide ? contact@africode.tech

---
© {{ date('Y') }} AfriCode — africode.tech
