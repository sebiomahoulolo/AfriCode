<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accusé de réception – Candidature AfriCode</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; }
        .header { background: linear-gradient(135deg, #11c06d 0%, #239e79 100%); color: #fff; padding: 28px 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; }
        .body { padding: 28px 24px; }
        .body p { margin: 0 0 14px; }
        .info-block { background: #f8fafc; border-left: 4px solid #fea837; padding: 14px 18px; margin: 18px 0; border-radius: 0 8px 8px 0; }
        .info-block strong { color: #1e293b; }
        .score-badge { display: inline-block; background: #239e79; color: #fff; padding: 6px 14px; border-radius: 20px; font-weight: 600; margin: 8px 0; }
        .footer { background: #1e293b; color: #94a3b8; padding: 24px; font-size: 13px; text-align: center; }
        .footer a { color: #fea837; text-decoration: none; }
        .footer .brand { color: #fff; font-weight: 700; font-size: 16px; margin-bottom: 8px; }
        ul { margin: 8px 0; padding-left: 20px; }
        .cours-notif { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 18px 20px; margin: 20px 0; text-align: center; }
        .cours-notif h3 { margin: 0 0 8px; font-size: 16px; color: #1e293b; }
        .cours-notif p { margin: 0 0 14px; font-size: 14px; color: #475569; }
        .btn-cours { display: inline-block; background: #239e79; color: #fff !important; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AfriCode – Candidature bien reçue</h1>
        </div>
        <div class="body">
            <p>Bonjour <strong>{{ $application->nom_prenoms }}</strong>,</p>
            <p>Nous accusons réception de votre candidature pour le poste de <strong>{{ $application->jobPosition->title }}</strong>.</p>

            <div class="info-block">
                <strong>Récapitulatif de votre candidature</strong><br>
                Poste : {{ $application->jobPosition->title }}<br>
                Email : {{ $application->email }}<br>
                Téléphone : {{ $application->telephone ?? '—' }}<br>
                @if($application->sexe)
                Sexe : {{ $application->sexe === 'feminin' ? 'Féminin' : 'Masculin' }}<br>
                @endif
                @if($application->niveau_etudes)
                Niveau d'études : {{ $application->niveau_etudes }}<br>
                @endif
                @if($application->test_score !== null)
                    Score au test QCM : <span class="score-badge">{{ $application->test_score }}/10</span> ({{ ucfirst($application->getScoreLevel()) }})
                @endif
            </div>

            <p><strong>Prochaines étapes :</strong></p>
            <ul>
                <li>Les candidats présélectionnés après le test QCM seront contactés pour la suite du processus.</li>
                <li>Seuls les candidats retenus seront contactés.</li>
                <li>Date limite de dépôt : 31 Mars 2026</li>
            </ul>

            <div class="cours-notif">
                <h3>Les cours sont disponibles sur AfriCode</h3>
                <p>Renforcez votre niveau dans tous les domaines du numérique : développement web, cybersécurité, intelligence artificielle, data, et bien plus.</p>
                <a href="{{ url()->to(route('courses.index')) }}" class="btn-cours">Voir les cours</a>
            </div>

            <p>Nous vous remercions pour l’intérêt que vous portez à AfriCode et vous souhaitons bonne chance.</p>
        </div>
        <div class="footer">
            <p class="brand">AFRICODE</p>
            <p>Centre de formation numérique – Façonnons l'avenir numérique de l'Afrique</p>
            <p style="margin-top: 12px;">
                 <a href="https://www.africode.tech">www.africode.tech</a><br>
                 <a href="mailto:contact@africode.tech">contact@africode.tech</a><br>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="#fea837" style="vertical-align:middle;margin-right:3px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> Cotonou, Bénin ·
            </p>
        </div>
    </div>
</body>
</html>
