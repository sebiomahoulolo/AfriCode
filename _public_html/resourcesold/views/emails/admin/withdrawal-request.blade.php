<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de retrait formateur – AfriCode</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; }
        .header { background: #11c06d; color: #fff; padding: 24px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 600; }
        .body { padding: 28px 24px; }
        .body p { margin: 0 0 16px; }
        .highlight { background: #f0fdfa; border-left: 4px solid #11c06d; padding: 12px 16px; margin: 16px 0; }
        .amount { font-size: 1.25rem; font-weight: 700; color: #11c06d; }
        .btn { display: inline-block; background: #11c06d; color: #fff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 8px; }
        .footer { background: #f8fafc; padding: 20px 24px; font-size: 12px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer a { color: #11c06d; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Demande de retrait formateur</h1>
        </div>
        <div class="body">
            <p>Un formateur a déposé une <strong>demande de retrait</strong> sur la plateforme AfriCode.</p>
            <div class="highlight">
                <p style="margin: 0 0 8px;"><strong>Formateur :</strong> {{ $formateurName }}</p>
                @if($formateur && $formateur->email)
                    <p style="margin: 0 0 8px;"><strong>Email :</strong> {{ $formateur->email }}</p>
                @endif
                <p style="margin: 0 0 8px;"><strong>Montant :</strong> <span class="amount">{{ $amountFormatted }}</span></p>
                <p style="margin: 0 0 8px;"><strong>Méthode :</strong> {{ ucfirst(str_replace('_', ' ', $withdrawal->payment_method ?? '')) }}</p>
                <p style="margin: 0;"><strong>Compte / Référence :</strong> {{ $withdrawal->payment_account ?? '—' }}</p>
            </div>
            <p>Merci de traiter cette demande depuis l’interface d’administration (retraits formateurs).</p>
            <p>
                <a href="{{ $withdrawalsUrl }}" class="btn">Voir les demandes de retrait</a>
            </p>
        </div>
        <div class="footer">
            <p>Cordialement,<br><strong>L'équipe AfriCode</strong></p>
            <p style="margin-top: 12px;">
                <a href="https://www.africode.tech">www.africode.tech</a><br>
                <a href="mailto:contact@africode.tech">contact@africode.tech</a>
            </p>
        </div>
    </div>
</body>
</html>
