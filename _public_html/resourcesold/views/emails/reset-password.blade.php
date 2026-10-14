<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Réinitialisation de mot de passe — AfriCode</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:#f0f2f5; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
        .wrapper { width:100%; background:#f0f2f5; padding:32px 16px; }
        .container { max-width:560px; margin:0 auto; }

        /* Header */
        .header {
            background:linear-gradient(135deg,#0f3460 0%,#1a1a2e 100%);
            border-radius:16px 16px 0 0;
            padding:32px 40px 28px;
            text-align:center;
        }
        .logo-bars { display:inline-flex; align-items:flex-end; gap:4px; height:28px; margin-bottom:12px; }
        .bar { display:block; width:7px; border-radius:3px; }
        .bar-1 { height:15px; background:#fea837; }
        .bar-2 { height:22px; background:#fa1c39; }
        .bar-3 { height:28px; background:#239e79; }
        .logo-text { font-size:18px; font-weight:900; letter-spacing:.14em; color:#fff; display:block; margin-top:4px; }

        /* Card */
        .card {
            background:#ffffff;
            padding:40px 40px 32px;
            border-left:1px solid #e5e7eb;
            border-right:1px solid #e5e7eb;
        }

        .badge {
            display:inline-flex; align-items:center; gap:6px;
            background:#fff7ed; color:#c2410c;
            border:1px solid #fed7aa;
            border-radius:20px; padding:5px 14px;
            font-size:12px; font-weight:700; letter-spacing:.04em;
            margin-bottom:24px;
        }
        .badge-dot { width:6px; height:6px; border-radius:50%; background:#f97316; animation:pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }

        h1 { font-size:22px; font-weight:800; color:#111827; margin-bottom:12px; line-height:1.3; }
        .greeting { font-size:15px; color:#374151; margin-bottom:16px; line-height:1.6; }
        .message { font-size:14px; color:#6b7280; line-height:1.7; margin-bottom:28px; }

        /* Button */
        .btn-wrap { text-align:center; margin-bottom:28px; }
        .btn {
            display:inline-block;
            background:linear-gradient(135deg,#11c06d,#239e79);
            color:#ffffff !important;
            text-decoration:none;
            padding:15px 36px;
            border-radius:12px;
            font-size:15px;
            font-weight:700;
            letter-spacing:.02em;
            box-shadow:0 6px 20px rgba(17,192,109,.35);
        }

        /* Expiry notice */
        .expiry {
            display:flex; align-items:center; gap:10px;
            background:#fefce8; border:1px solid #fef08a;
            border-radius:10px; padding:12px 16px;
            margin-bottom:24px;
        }
        .expiry-icon { font-size:16px; flex-shrink:0; }
        .expiry p { font-size:13px; color:#713f12; line-height:1.5; margin:0; }
        .expiry strong { color:#854d0e; }

        /* URL fallback */
        .url-box {
            background:#f8f9fa; border:1px solid #e5e7eb;
            border-radius:10px; padding:14px 16px;
            margin-bottom:28px;
        }
        .url-box p { font-size:12px; color:#6b7280; margin-bottom:6px; }
        .url-box a { font-size:11px; color:#11c06d; word-break:break-all; text-decoration:none; font-family:monospace; }

        hr { border:none; border-top:1px solid #f3f4f6; margin:24px 0; }
        .secure { font-size:12px; color:#9ca3af; line-height:1.6; text-align:center; }
        .secure a { color:#11c06d; text-decoration:none; }

        /* Footer */
        .footer {
            background:#f8f9fa;
            border:1px solid #e5e7eb; border-top:none;
            border-radius:0 0 16px 16px;
            padding:20px 40px;
            text-align:center;
        }
        .footer p { font-size:12px; color:#9ca3af; line-height:1.6; }
        .footer strong { color:#11c06d; }
        .footer a { color:#11c06d; text-decoration:none; }

        /* Responsive */
        @media (max-width:520px) {
            .card, .footer { padding-left:24px; padding-right:24px; }
            .header { padding:24px 24px 20px; }
            h1 { font-size:19px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">

        <!-- Header -->
        <div class="header">
            <div class="logo-bars">
                <span class="bar bar-1"></span>
                <span class="bar bar-2"></span>
                <span class="bar bar-3"></span>
            </div>
            <span class="logo-text">AFRICODE</span>
        </div>

        <!-- Card -->
        <div class="card">

            <div class="badge">
                <span class="badge-dot"></span>
                Sécurité du compte
            </div>

            <h1>Réinitialisation de<br>votre mot de passe</h1>

            <p class="greeting">
                Bonjour {{ $firstName }},
            </p>

            <p class="message">
                Vous recevez cet email car nous avons reçu une demande de réinitialisation du mot de passe de votre compte <strong>AfriCode</strong>.
                Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe sécurisé.
            </p>

            <div class="btn-wrap">
                <a href="{{ $url }}" class="btn" target="_blank">
                    &nbsp;&nbsp;Réinitialiser mon mot de passe
                </a>
            </div>

            <!-- Expiry -->
            <div class="expiry">
                <span class="expiry-icon">⏱️</span>
                <p>Ce lien expire dans <strong>60 minutes</strong>. Après ce délai, vous devrez faire une nouvelle demande de réinitialisation.</p>
            </div>

            <!-- URL fallback -->
            <div class="url-box">
                <p>Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
                <a href="{{ $url }}" target="_blank">{{ $url }}</a>
            </div>

            <hr>

            <p class="secure">
                Si vous n'avez pas demandé de réinitialisation, ignorez cet email — votre compte reste sécurisé.<br>
                Pour toute question : <a href="mailto:support@africode.tech">support@africode.tech</a> ou <a href="mailto:contact@africode.tech">contact@africode.tech</a>
            </p>

        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Cet email a été envoyé par <strong>AfriCode</strong> · Hub Numérique Africain<br>
                <a href="{{ url('/') }}">africode.tech</a> &nbsp;·&nbsp;
                <a href="{{ url('/Contactez-nous') }}">Nous contacter</a> &nbsp;·&nbsp;
                <a href="{{ url('/profile') }}">Mon compte</a>
            </p>
            <p style="margin-top:8px;font-size:11px;">
                No Code, No Future &nbsp;·&nbsp; © {{ date('Y') }} AfriCode. Tous droits réservés.
            </p>
        </div>

    </div>
</div>
</body>
</html>
