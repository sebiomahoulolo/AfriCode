<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande de mentorat</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f7f9; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0d9488 0%, #11c06d 100%); padding: 36px 40px; text-align: center; }
        .header img { height: 44px; margin-bottom: 16px; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; margin: 0; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin: 8px 0 0; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 17px; font-weight: 600; color: #1a202c; margin-bottom: 12px; }
        .text { font-size: 15px; color: #4a5568; line-height: 1.7; margin-bottom: 24px; }
        .card { background: #f0fdf9; border: 1px solid #99f6e4; border-radius: 10px; padding: 20px 24px; margin-bottom: 24px; }
        .card-row { display: flex; align-items: flex-start; margin-bottom: 10px; }
        .card-row:last-child { margin-bottom: 0; }
        .card-label { font-size: 12px; font-weight: 700; color: #0d9488; text-transform: uppercase; letter-spacing: 0.5px; min-width: 130px; }
        .card-value { font-size: 14px; color: #1a202c; }
        .message-box { background: #fffbeb; border-left: 4px solid #f59e0b; border-radius: 0 8px 8px 0; padding: 16px 20px; margin-bottom: 28px; }
        .message-box p { font-size: 14px; color: #92400e; font-style: italic; margin: 0; line-height: 1.6; }
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
        <img src="{{ asset('images/logo-white.png') }}" alt="AfriCode" onerror="this.style.display='none'">
        <h1>Nouvelle demande de mentorat</h1>
        <p>Un apprenant souhaite bénéficier de votre accompagnement</p>
    </div>
    <div class="body">
        <p class="greeting">Bonjour {{ $formateur->first_name }} {{ $formateur->last_name }},</p>
        <p class="text">
            Vous avez reçu une nouvelle demande de mentorat sur AfriCode.
            Un apprenant souhaite bénéficier de votre expertise. Vous pouvez l'accepter ou la refuser depuis votre tableau de bord.
        </p>

        <div class="card">
            <div class="card-row">
                <span class="card-label">Apprenant</span>
                <span class="card-value">{{ $mentee->first_name }} {{ $mentee->last_name }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Email</span>
                <span class="card-value">{{ $mentee->email }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Domaine demandé</span>
                <span class="card-value">{{ $mentorship->specialization_area }}</span>
            </div>
            <div class="card-row">
                <span class="card-label">Date de la demande</span>
                <span class="card-value">{{ $mentorship->requested_at->format('d/m/Y à H\hi') }}</span>
            </div>
        </div>

        @if($mentorship->message)
        <div class="message-box">
            <p><strong>Message de l'apprenant :</strong><br>{{ $mentorship->message }}</p>
        </div>
        @endif

        <p class="text">Rendez-vous sur votre tableau de bord pour répondre à cette demande.</p>

        <div class="btn-wrapper">
            <a href="{{ $requestsUrl }}" class="btn">Voir les demandes en attente</a>
        </div>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} <a href="https://africode.tech">AfriCode</a> — Plateforme d'apprentissage numérique</p>
        <p>Cotonou, Bénin · <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
    </div>
</div>
</body>
</html>
