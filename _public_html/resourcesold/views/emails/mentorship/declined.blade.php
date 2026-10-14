<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de mentorat</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f7f9; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #475569 0%, #64748b 100%); padding: 36px 40px; text-align: center; }
        .header img { height: 44px; margin-bottom: 16px; }
        .header .icon { font-size: 48px; display: block; margin-bottom: 12px; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; margin: 0; }
        .header p { color: rgba(255,255,255,0.8); font-size: 14px; margin: 8px 0 0; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 17px; font-weight: 600; color: #1a202c; margin-bottom: 12px; }
        .text { font-size: 15px; color: #4a5568; line-height: 1.7; margin-bottom: 24px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 20px 24px; margin-bottom: 24px; }
        .card-row { display: flex; align-items: flex-start; margin-bottom: 10px; }
        .card-row:last-child { margin-bottom: 0; }
        .card-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; min-width: 130px; }
        .card-value { font-size: 14px; color: #1a202c; }
        .next-steps { background: #f0fdf9; border: 1px solid #99f6e4; border-radius: 10px; padding: 20px 24px; margin-bottom: 28px; }
        .next-steps h3 { font-size: 14px; font-weight: 700; color: #0d9488; margin: 0 0 12px; }
        .next-steps ul { margin: 0; padding-left: 18px; }
        .next-steps li { font-size: 14px; color: #134e4a; line-height: 1.8; }
        .btn-wrapper { text-align: center; margin: 28px 0; }
        .btn { display: inline-block; background: #0d9488; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 15px; font-weight: 600; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; margin: 4px 0; }
        .footer a { color: #0d9488; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <span class="icon" style="display:block;font-size:0;line-height:0;margin-bottom:12px;">
            <span style="display:inline-block;width:58px;height:58px;background:rgba(255,255,255,0.15);border-radius:50%;padding-top:13px;box-sizing:border-box;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            </span>
        </span>
        <h1>Réponse à votre demande de mentorat</h1>
        <p>Concernant votre demande auprès de {{ $formateur->first_name }} {{ $formateur->last_name }}</p>
    </div>
    <div class="body">
        <p class="greeting">Bonjour {{ $mentee->first_name }},</p>
        <p class="text">
            Suite à votre demande de mentorat, <strong>{{ $formateur->first_name }} {{ $formateur->last_name }}</strong>
            n'est pas en mesure d'accepter votre demande pour le moment. Cela ne remet pas en question vos compétences —
            les formateurs ont souvent des contraintes de disponibilité.
        </p>

        <div class="card">
            <div class="card-row">
                <span class="card-label">Formateur</span>
                <span class="card-value">{{ $formateur->first_name }} {{ $formateur->last_name }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Domaine</span>
                <span class="card-value">{{ $mentorship->specialization_area }}</span>
            </div>
        </div>

        <div class="next-steps">
            <h3>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="#0d9488" style="vertical-align:middle;margin-right:5px;margin-bottom:2px;"><path d="M12 2a7 7 0 0 1 7 7c0 2.38-1.19 4.47-3 5.74V17a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2.26C6.19 13.47 5 11.38 5 9a7 7 0 0 1 7-7zM9 21v-1h6v1a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zm3-17a5 5 0 0 0-5 5c0 1.96 1.09 3.65 2.7 4.53.19.1.3.3.3.52V16h4v-1.95c0-.22.11-.42.3-.52C15.91 12.65 17 10.96 17 9a5 5 0 0 0-5-5z"/></svg>
                Que faire maintenant ?
            </h3>
            <ul>
                <li>Consultez la liste des autres formateurs disponibles</li>
                <li>Faites une demande à un autre formateur dans votre domaine</li>
                <li>Continuez vos cours — votre progression est déjà excellente !</li>
                <li>Rejoignez les forums de discussion pour obtenir de l'aide</li>
            </ul>
        </div>

        <div class="btn-wrapper">
            <a href="{{ $mentorsUrl }}" class="btn">Trouver un autre mentor</a>
        </div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} <a href="https://africode.tech">AfriCode</a> — Plateforme d'apprentissage numérique</p>
        <p>Cotonou, Bénin · <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
    </div>
</div>
</body>
</html>
