<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre cours a été validé et publié – AfriCode</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; }
        .header { background: #0d9488; color: #fff; padding: 24px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 600; }
        .body { padding: 28px 24px; }
        .body p { margin: 0 0 16px; }
        .course-name { font-weight: 700; color: #0d9488; }
        .btn { display: inline-block; background: #0d9488; color: #fff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; margin-top: 8px; }
        .btn-secondary { background: #64748b; margin-left: 8px; }
        .footer { background: #f8fafc; padding: 20px 24px; font-size: 12px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer a { color: #0d9488; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Votre cours a été validé et publié</h1>
        </div>
        <div class="body">
            <p>Bonjour {{ $formateur->first_name }},</p>
            <p>Nous avons le plaisir de vous informer que votre cours <strong>« {{ $course->title }} »</strong> a été validé avec succès et est désormais <strong>publié sur AfriCode</strong>.</p>
            <p>
                <span style="display:inline-flex;align-items:center;gap:6px;font-weight:600;">
                    <span style="display:inline-block;width:22px;height:22px;background:#0d9488;border-radius:50%;text-align:center;line-height:22px;color:white;font-size:13px;">&#10003;</span>
                    Félicitations pour la qualité de votre travail !
                </span><br>
                Votre expertise contribue à enrichir notre communauté d'apprenants.
            </p>
            <p>Votre cours est maintenant accessible aux étudiants et prêt à recevoir des inscriptions.</p>
            <p>Nous vous remercions pour votre confiance et votre engagement.</p>
            <p>
                <a href="{{ $courseManageUrl }}" class="btn">Voir et gérer le cours</a>
                <a href="{{ $dashboardUrl }}" class="btn btn-secondary">Tableau de bord</a>
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
