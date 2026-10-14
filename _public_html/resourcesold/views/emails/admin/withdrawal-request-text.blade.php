Demande de retrait formateur – AfriCode

Un formateur a déposé une demande de retrait sur la plateforme AfriCode.

Formateur : {{ $formateurName }}
Montant : {{ $amountFormatted }}
Méthode : {{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method ?? '')) }}
Compte / Référence : {{ $withdrawal->payment_account ?? '—' }}

Merci de traiter cette demande depuis l’interface d’administration (retraits formateurs).

Voir les demandes de retrait : {{ $withdrawalsUrl }}

—
Cordialement,
L'équipe AfriCode
www.africode.tech | contact@africode.tech
