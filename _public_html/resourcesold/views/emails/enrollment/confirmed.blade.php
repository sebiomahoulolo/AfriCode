<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription confirmée</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f4f7f9; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0d9488 0%, #11c06d 100%); padding: 36px 40px; text-align: center; }
        .header img { height: 44px; margin-bottom: 16px; }
        .header .icon { font-size: 52px; display: block; margin-bottom: 12px; }
        .header h1 { color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; }
        .header p { color: rgba(255,255,255,0.85); font-size: 14px; margin: 8px 0 0; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 17px; font-weight: 600; color: #1a202c; margin-bottom: 12px; }
        .text { font-size: 15px; color: #4a5568; line-height: 1.7; margin-bottom: 24px; }
        .course-card { background: #f0fdf9; border: 1px solid #99f6e4; border-radius: 10px; padding: 20px 24px; margin-bottom: 24px; }
        .course-title { font-size: 17px; font-weight: 700; color: #0d9488; margin: 0 0 12px; }
        .course-row { display: flex; align-items: center; margin-bottom: 8px; }
        .course-row:last-child { margin-bottom: 0; }
        .course-label { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; min-width: 110px; }
        .course-value { font-size: 14px; color: #1a202c; }
        .badge-free { background: #dcfce7; color: #15803d; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
        .badge-paid { background: #fef3c7; color: #b45309; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
        .steps { margin-bottom: 28px; }
        .steps h3 { font-size: 15px; font-weight: 700; color: #1a202c; margin: 0 0 16px; }
        .step { display: flex; align-items: flex-start; margin-bottom: 12px; }
        .step-num { background: #0d9488; color: #fff; font-size: 12px; font-weight: 700; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-right: 12px; margin-top: 1px; }
        .step-text { font-size: 14px; color: #4a5568; line-height: 1.6; }
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
                <svg width="32" height="32" viewBox="0 0 24 24" fill="white"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </span>
        </span>
        <h1>Inscription confirmée !</h1>
        <p>Vous êtes prêt(e) à commencer votre formation</p>
    </div>
    <div class="body">
        <p class="greeting">Félicitations {{ $user->first_name }} !</p>
        <p class="text">
            Votre inscription au cours a bien été confirmée. Vous pouvez dès maintenant accéder à l'ensemble du contenu de la formation.
        </p>

        <div class="course-card">
            <p class="course-title">{{ $course->title }}</p>
            <div class="course-row">
                <span class="course-label">Formateur</span>
                <span class="course-value">{{ $course->formateur->first_name ?? '' }} {{ $course->formateur->last_name ?? '' }}</span>
            </div>
            <div class="course-row">
                <span class="course-label">Niveau</span>
                <span class="course-value">
                    @php
                        $levels = ['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé'];
                    @endphp
                    {{ $levels[$course->level] ?? ucfirst($course->level) }}
                </span>
            </div>
            <div class="course-row">
                <span class="course-label">Accès</span>
                <span class="course-value">
                    @if($isFree)
                        <span class="badge-free">Gratuit</span>
                    @else
                        <span class="badge-paid">Payant — {{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency ?? 'XOF' }}</span>
                    @endif
                </span>
            </div>
            <div class="course-row">
                <span class="course-label">Inscrit le</span>
                <span class="course-value">{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="steps">
            <h3>Par où commencer ?</h3>
            <div class="step"><div class="step-num">1</div><div class="step-text">Accédez au cours et parcourez la présentation générale</div></div>
            <div class="step"><div class="step-num">2</div><div class="step-text">Suivez les modules dans l'ordre recommandé par le formateur</div></div>
            <div class="step"><div class="step-num">3</div><div class="step-text">Validez chaque leçon et participez aux quiz pour tester vos connaissances</div></div>
            <div class="step"><div class="step-num">4</div><div class="step-text">Posez vos questions dans le forum du cours si vous bloquez</div></div>
        </div>

        <div class="btn-wrapper">
            <a href="{{ $courseUrl }}" class="btn">Commencer le cours maintenant</a>
        </div>

        <p class="text" style="font-size: 13px; color: #94a3b8; text-align: center;">
            Besoin d'aide ? Contactez-nous à <a href="mailto:contact@africode.tech" style="color:#0d9488;">contact@africode.tech</a>
        </p>
    </div>
    <div class="footer">
        <p>© {{ date('Y') }} <a href="https://africode.tech">AfriCode</a> — Plateforme d'apprentissage numérique</p>
        <p>Cotonou, Bénin · <a href="mailto:contact@africode.tech">contact@africode.tech</a></p>
    </div>
</div>
</body>
</html>
