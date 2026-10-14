<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'AfriCode')</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:#f0f2f5; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
        .wrapper { width:100%; background:#f0f2f5; padding:32px 16px; }
        .container { max-width:580px; margin:0 auto; }

        /* ── Header ── */
        .header { background:linear-gradient(135deg,#1EA38B 0%,#27B371 100%); border-radius:16px 16px 0 0; padding:28px 40px 24px; text-align:center; }
        .logo-bars { display:inline-flex; align-items:flex-end; gap:5px; height:30px; margin-bottom:8px; }
        .bar { display:inline-block; border-radius:3px; }
        .bar-1 { width:7px; height:15px; background:#FF8E2A; }
        .bar-2 { width:7px; height:22px; background:#E32D31; }
        .bar-3 { width:7px; height:30px; background:#4ade80; }
        .logo-text { font-size:15px; font-weight:900; letter-spacing:.18em; color:#fff; display:block; margin-bottom:18px; }
        .header-icon { display:inline-flex; align-items:center; justify-content:center; width:56px; height:56px; background:rgba(255,255,255,.2); border-radius:50%; margin-bottom:12px; }
        .header-title { color:#fff; font-size:21px; font-weight:800; margin:0 0 6px; line-height:1.3; }
        .header-subtitle { color:rgba(255,255,255,.85); font-size:13px; margin:0; }

        /* ── Card ── */
        .card { background:#fff; padding:36px 40px 28px; border-left:1px solid #e5e7eb; border-right:1px solid #e5e7eb; }

        /* ── Typography ── */
        .greeting { font-size:16px; font-weight:600; color:#1a202c; margin-bottom:14px; }
        .text { font-size:14px; color:#4a5568; line-height:1.75; margin-bottom:20px; }

        /* ── Info card ── */
        .info-card { background:#f0fdf9; border:1px solid #99f6e4; border-radius:10px; padding:20px 24px; margin-bottom:24px; }
        .info-card-title { font-size:15px; font-weight:700; color:#1EA38B; margin:0 0 14px; }
        .info-row { display:flex; align-items:flex-start; margin-bottom:10px; }
        .info-row:last-child { margin-bottom:0; }
        .info-label { font-size:12px; font-weight:700; color:#1EA38B; text-transform:uppercase; letter-spacing:.5px; min-width:130px; flex-shrink:0; }
        .info-value { font-size:14px; color:#1a202c; }

        /* ── Steps ── */
        .steps-title { font-size:15px; font-weight:700; color:#1a202c; margin:0 0 14px; }
        .step { display:flex; align-items:flex-start; margin-bottom:12px; }
        .step-num { background:linear-gradient(135deg,#1EA38B,#27B371); color:#fff; font-size:12px; font-weight:700; width:24px; height:24px; min-width:24px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-right:12px; margin-top:1px; }
        .step-text { font-size:14px; color:#4a5568; line-height:1.6; }

        /* ── Highlight block ── */
        .highlight { background:#f0fdf9; border-left:4px solid #1EA38B; border-radius:0 8px 8px 0; padding:14px 18px; margin-bottom:20px; }
        .highlight p { font-size:14px; color:#1a202c; margin:0 0 8px; }
        .highlight p:last-child { margin:0; }

        /* ── Tip / Warning boxes ── */
        .tip-box { background:#fff7ed; border:1px solid #fed7aa; border-radius:10px; padding:16px 20px; margin-bottom:24px; }
        .tip-box-title { font-size:14px; font-weight:700; color:#c2410c; margin:0 0 10px; }
        .tip-box ul { margin:0; padding-left:18px; }
        .tip-box li { font-size:14px; color:#9a3412; line-height:1.8; }
        .warn-box { background:#fefce8; border:1px solid #fef08a; border-radius:10px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:flex-start; gap:10px; }
        .warn-box p { font-size:13px; color:#713f12; line-height:1.5; margin:0; }

        /* ── Next steps box ── */
        .next-box { background:#f0fdf9; border:1px solid #99f6e4; border-radius:10px; padding:16px 20px; margin-bottom:24px; }
        .next-box-title { font-size:14px; font-weight:700; color:#1EA38B; margin:0 0 10px; }
        .next-box ul { margin:0; padding-left:18px; }
        .next-box li { font-size:14px; color:#134e4a; line-height:1.8; }

        /* ── Badges ── */
        .badge-success { display:inline-block; background:#27B371; color:#fff; padding:4px 12px; border-radius:20px; font-weight:700; font-size:13px; }
        .badge-free { display:inline-block; background:#dcfce7; color:#15803d; padding:3px 10px; border-radius:20px; font-weight:700; font-size:12px; }
        .badge-paid { display:inline-block; background:#fef3c7; color:#b45309; padding:3px 10px; border-radius:20px; font-weight:700; font-size:12px; }
        .badge-status { display:inline-flex; align-items:center; gap:6px; background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; border-radius:20px; padding:5px 14px; font-size:12px; font-weight:700; letter-spacing:.04em; margin-bottom:20px; }
        .badge-dot { width:6px; height:6px; min-width:6px; border-radius:50%; background:#f97316; }

        /* ── URL fallback ── */
        .url-box { background:#f8f9fa; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px; margin-bottom:20px; }
        .url-box p { font-size:12px; color:#6b7280; margin-bottom:6px; }
        .url-box a { font-size:11px; color:#1EA38B; word-break:break-all; text-decoration:none; font-family:monospace; }

        /* ── Buttons ── */
        .btn-wrap { text-align:center; margin:24px 0; }
        .btn { display:inline-block; background:linear-gradient(135deg,#1EA38B,#27B371); color:#ffffff !important; text-decoration:none; padding:14px 32px; border-radius:10px; font-size:15px; font-weight:700; letter-spacing:.02em; box-shadow:0 4px 14px rgba(30,163,139,.3); }
        .btn-secondary { display:inline-block; background:#64748b; color:#fff !important; text-decoration:none; padding:14px 24px; border-radius:10px; font-size:15px; font-weight:600; margin-left:8px; }

        /* ── Divider & note ── */
        hr { border:none; border-top:1px solid #f3f4f6; margin:22px 0; }
        .note { font-size:12px; color:#9ca3af; line-height:1.6; text-align:center; }
        .note a { color:#1EA38B; text-decoration:none; }

        /* ── Footer ── */
        .footer { background:#f8f9fa; border:1px solid #e5e7eb; border-top:none; border-radius:0 0 16px 16px; padding:20px 40px; text-align:center; }
        .footer p { font-size:12px; color:#9ca3af; line-height:1.8; }
        .footer strong { color:#1EA38B; }
        .footer a { color:#1EA38B; text-decoration:none; }

        /* ── Responsive ── */
        @media (max-width:520px) {
            .card, .footer { padding-left:20px; padding-right:20px; }
            .header { padding:22px 20px 18px; }
            .header-title { font-size:18px; }
            .info-label { min-width:90px; }
        }

        @yield('styles')
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <div class="logo-bars">
                <span class="bar bar-1"></span>
                <span class="bar bar-2"></span>
                <span class="bar bar-3"></span>
            </div>
            <span class="logo-text">AFRICODE</span>
            @yield('header_content')
        </div>

        {{-- Body --}}
        <div class="card">
            @yield('content')
        </div>

        {{-- Footer --}}
        <div class="footer">
            @yield('footer_extra')
            <p>
                Envoyé par <strong>AfriCode</strong> · Hub Numérique Africain<br>
                <a href="{{ url('/') }}">africode.tech</a>&nbsp;·&nbsp;
                <a href="{{ url('/Contactez-nous') }}">Nous contacter</a>&nbsp;·&nbsp;
                <a href="mailto:contact@africode.tech">contact@africode.tech</a>
            </p>
            <p style="margin-top:6px;font-size:11px;">
                No Code, No Future &nbsp;·&nbsp; © {{ date('Y') }} AfriCode. Tous droits réservés.
            </p>
        </div>

    </div>
</div>
</body>
</html>
