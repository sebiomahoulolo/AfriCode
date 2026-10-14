Cours soumis pour validation – AfriCode

Un formateur a soumis un cours pour validation sur la plateforme AfriCode.

Cours : {{ $course->title }}
Formateur : {{ $formateurName }}
@if($course->submitted_for_validation_at)
Soumis le : {{ $course->submitted_for_validation_at->format('d/m/Y à H:i') }}
@endif

Merci d’examiner ce cours et de le valider, demander des révisions ou le rejeter depuis l’interface d’administration.

Voir le cours en attente : {{ $courseUrl }}

—
Cordialement,
L'équipe AfriCode
www.africode.tech | contact@africode.tech
