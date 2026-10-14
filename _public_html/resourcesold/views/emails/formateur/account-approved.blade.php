<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre compte formateur a été validé – AfriCode</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; }
        .header { background: #0d9488; color: #fff; padding: 24px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 600; }
        .body { padding: 28px 24px; }
        .body p { margin: 0 0 16px; }
        .btn { display: inline-block; background: #0d9488; color: #fff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 8px; }
        .footer { background: #f8fafc; padding: 20px 24px; font-size: 12px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer a { color: #0d9488; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Votre compte formateur a été validé</h1>
        </div>
        <div class="body">
            <p>Bonjour {{ $formateur->first_name }},</p>
            <p>Nous avons le plaisir de vous confirmer que <strong>votre compte formateur AfriCode a été validé</strong> par notre équipe.</p>
            <p>Vous pouvez désormais créer et publier des formations, gérer vos cours et accompagner les apprenants sur la plateforme.</p>
            <p>Bienvenue dans la communauté des formateurs AfriCode </p>
            <p>
                <a href="{{ $dashboardUrl }}" class="btn">Accéder à mon tableau de bord</a>
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
