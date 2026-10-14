<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>@yield('error_title', 'Erreur') — AfriCode</title>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
    --green:#1EA38B;--green2:#27B371;--orange:#FF8E2A;
    --navy:#0d1a18;--navy2:#0a2a20;--red:#E32D31;
}
body{
    font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
    background:linear-gradient(135deg,#0d1a18,#0a2a20);
    min-height:100vh;display:flex;flex-direction:column;
    align-items:center;justify-content:center;padding:24px;color:#fff;
    overflow:hidden;
}
/* Background grid */
body::before{
    content:'';position:fixed;inset:0;
    background-image:
        linear-gradient(rgba(30,163,139,.05) 1px,transparent 1px),
        linear-gradient(90deg,rgba(30,163,139,.05) 1px,transparent 1px);
    background-size:50px 50px;pointer-events:none;
}
/* Glow blobs */
.glow-1,.glow-2{
    position:fixed;border-radius:50%;filter:blur(80px);
    pointer-events:none;opacity:.3;
}
.glow-1{width:500px;height:500px;background:var(--green);top:-150px;right:-100px;}
.glow-2{width:400px;height:400px;background:var(--orange);bottom:-100px;left:-80px;}

.err-card{
    position:relative;z-index:1;
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.1);
    backdrop-filter:blur(20px);
    border-radius:24px;
    padding:52px 48px;
    max-width:560px;width:100%;
    text-align:center;
    box-shadow:0 32px 80px rgba(0,0,0,.4);
}

/* Logo bar */
.err-logo{
    display:flex;align-items:center;justify-content:center;gap:10px;
    margin-bottom:36px;
}
.err-logo-bars{display:flex;gap:3px;align-items:flex-end;}
.err-logo-bars span{
    display:block;width:5px;border-radius:3px;
}
.err-logo-bars span:nth-child(1){height:14px;background:var(--orange);}
.err-logo-bars span:nth-child(2){height:20px;background:var(--red);}
.err-logo-bars span:nth-child(3){height:26px;background:var(--green);}
.err-logo-text{font-size:1.2rem;font-weight:800;letter-spacing:.5px;}
.err-logo-text span{color:var(--green);}

/* Code */
.err-code{
    font-size:5.5rem;font-weight:900;line-height:1;
    background:linear-gradient(135deg,var(--green),var(--green2));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;
    background-clip:text;margin-bottom:8px;
    text-shadow:none;
}
.err-code-badge{
    display:inline-block;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.12);
    border-radius:20px;padding:4px 14px;
    font-size:.75rem;font-weight:700;color:rgba(255,255,255,.6);
    letter-spacing:.08em;text-transform:uppercase;
    margin-bottom:20px;
}
.err-title{
    font-size:1.45rem;font-weight:800;color:#fff;
    margin-bottom:12px;line-height:1.3;
}
.err-desc{
    font-size:.9rem;color:rgba(255,255,255,.6);
    line-height:1.7;margin-bottom:32px;
}
.err-desc code{
    background:rgba(255,255,255,.1);padding:1px 7px;
    border-radius:5px;font-size:.85rem;color:rgba(255,255,255,.85);
}

/* Actions */
.err-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;}
.err-btn{
    display:inline-flex;align-items:center;gap:8px;
    padding:12px 24px;border-radius:12px;
    font-size:.88rem;font-weight:700;
    text-decoration:none;border:none;cursor:pointer;
    transition:transform .15s,opacity .15s;
}
.err-btn:hover{transform:translateY(-2px);opacity:.9;}
.err-btn-primary{
    background:linear-gradient(135deg,var(--green),var(--green2));
    color:#fff;
}
.err-btn-ghost{
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.15);
    color:rgba(255,255,255,.8);
}
.err-btn svg{width:16px;height:16px;flex-shrink:0;}

/* Divider */
.err-divider{
    height:1px;background:rgba(255,255,255,.08);
    margin:28px 0;
}
.err-contact{
    font-size:.78rem;color:rgba(255,255,255,.4);
}
.err-contact a{color:rgba(30,163,139,.8);text-decoration:none;}
.err-contact a:hover{color:var(--green);}

/* Icon circle */
.err-icon-wrap{
    width:72px;height:72px;border-radius:20px;
    display:flex;align-items:center;justify-content:center;
    margin:0 auto 24px;font-size:1.8rem;
}

@media(max-width:480px){
    .err-card{padding:36px 24px;}
    .err-code{font-size:4rem;}
    .err-title{font-size:1.25rem;}
    .err-actions{flex-direction:column;}
    .err-btn{justify-content:center;}
}
</style>
</head>
<body>
<div class="glow-1"></div>
<div class="glow-2"></div>

<div class="err-card">
    <div class="err-logo">
        <div class="err-logo-bars">
            <span></span><span></span><span></span>
        </div>
        <div class="err-logo-text">Afri<span>Code</span></div>
    </div>

    @yield('error_content')

    <div class="err-divider"></div>
    <p class="err-contact">
        Besoin d'aide ? Contactez-nous —
        <a href="mailto:support@africode.tech">support@africode.tech</a>
    </p>
</div>
</body>
</html>
