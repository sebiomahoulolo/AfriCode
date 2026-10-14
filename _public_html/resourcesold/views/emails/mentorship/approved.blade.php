<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentorat approuvé !</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f7f9; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0d9488 0%, #11c06d 100%); padding: 36px 40px; text-align: center; }
        .header img { height: 44px; margin-bottom: 16px; }
        .header .icon { font-size: 48px; display: block; margin-bottom: 12px; }
        .header h1 { color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin: 8px 0 0; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 17px; font-weight: 600; color: #1a202c; margin-bottom: 12px; }
        .text { font-size: 15px; color: #4a5568; line-height: 1.7; margin-bottom: 24px; }
        .card { background: #f0fdf9; border: 1px solid #99f6e4; border-radius: 10px; padding: 20px 24px; margin-bottom: 24px; }
        .card-row { display: flex; align-items: flex-start; margin-bottom: 10px; }
        .card-row:last-child { margin-bottom: 0; }
        .card-label { font-size: 12px; font-weight: 700; color: #0d9488; text-transform: uppercase; letter-spacing: 0.5px; min-width: 130px; }
        .card-value { font-size: 14px; color: #1a202c; }
        .tips { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 20px 24px; margin-bottom: 28px; }
        .tips h3 { font-size: 14px; font-weight: 700; color: #c2410c; margin: 0 0 12px; }
        .tips ul { margin: 0; padding-left: 18px; }
        .tips li { font-size: 14px; color: #9a3412; line-height: 1.8; }
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
            <span style="display:inline-block;width:58px;height:58px;background:rgba(255,255,255,0.2);border-radius:50%;padding-top:13px;box-sizing:border-box;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </span>
        </span>
        <h1>Votre mentorat est approuvé !</h1>
        <p>{{ $formateur->first_name }} {{ $formateur->last_name }} accepte de vous accompagner</p>
    </div>
    <div class="body">
        <p class="greeting">Félicitations {{ $mentee->first_name }} !</p>
        <p class="text">
            Bonne nouvelle ! <strong>{{ $formateur->first_name }} {{ $formateur->last_name }}</strong> a accepté votre demande de mentorat.
            Vous pouvez maintenant commencer votre parcours d'apprentissage ensemble.
        </p>

        <div class="card">
            <div class="card-row">
                <span class="card-label">Votre mentor</span>
                <span class="card-value">{{ $formateur->first_name }} {{ $formateur->last_name }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Email</span>
                <span class="card-value">{{ $formateur->email }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Domaine</span>
                <span class="card-value">{{ $mentorship->specialization_area }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Approuvé le</span>
                <span class="card-value">{{ $mentorship->approved_at->format('d/m/Y à H\hi') }}</span>
            </div>
        </div>

        <div class="tips">
            <h3>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="#c2410c" style="vertical-align:middle;margin-right:5px;margin-bottom:2px;"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                Conseils pour bien démarrer
            </h3>
            <ul>
                <li>Présentez-vous et définissez vos objectifs d'apprentissage</li>
                <li>Préparez vos questions et vos points bloquants</li>
                <li>Soyez régulier et respectez les engagements pris</li>
                <li>N'hésitez pas à partager vos avancées et vos difficultés</li>
            </ul>
        </div>

        <div class="btn-wrapper">
            <a href="{{ $mentorshipUrl }}" class="btn">Accéder à mon mentorat</a>
        </div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} <a href="https://africode.tech">AfriCode</a> — Plateforme d'apprentissage numérique</p>
        <p>Cotonou, Bénin · <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
    </div>
</div>
</body>
</html>
