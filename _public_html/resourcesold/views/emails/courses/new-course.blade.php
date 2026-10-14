<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau cours disponible – AfriCode</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 560px; margin: 0 auto; background: #fff; }
        .header { background: #0d9488; color: #fff; padding: 24px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 600; }
        .body { padding: 28px 24px; }
        .body p { margin: 0 0 16px; }
        .course-title { font-size: 18px; font-weight: 700; color: #0d9488; margin: 16px 0 8px; }
        .btn { display: inline-block; background: #0d9488; color: #fff !important; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 16px 0; }
        .footer { background: #f8fafc; padding: 20px 24px; font-size: 12px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer a { color: #0d9488; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="text-align:center;margin-bottom:14px;">
                <span style="display:inline-block;width:56px;height:56px;background:rgba(255,255,255,0.18);border-radius:50%;padding-top:12px;box-sizing:border-box;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
                </span>
            </div>
            <h1>Nouveau cours disponible sur AfriCode !</h1>
        </div>
        <div class="body">
            <p>Bonjour {{ $user->first_name }},</p>
            <p>Un nouveau cours vient d'être publié sur AfriCode !</p>
            <p class="course-title">« {{ $course->title }} »</p>
            <p>C'est une excellente opportunité pour développer vos compétences et progresser dans votre parcours.</p>
            <p>
                <a href="{{ $courseUrl }}" class="btn">Découvrir le cours sur africode.tech →</a>
            </p>
            <p>Ne manquez pas cette nouvelle opportunité d'apprentissage !</p>
        </div>
        <div class="footer">
            <p>À très bientôt,<br><strong>L'équipe AfriCode</strong></p>
            <p style="margin-top: 12px;">
                <a href="https://www.africode.tech">www.africode.tech</a><br>
                <a href="mailto:contact@africode.tech">contact@africode.tech</a>
            </p>
            <p style="margin-top: 12px; font-size: 11px;">
                <a href="{{ $unsubscribeUrl }}">Se désabonner</a> · <a href="{{ route('courses.index') }}">Voir tous les cours</a>
            </p>
        </div>
    </div>
</body>
</html>
