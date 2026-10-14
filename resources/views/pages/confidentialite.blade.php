@extends('layouts.layout')

@section('title', 'Déclaration de confidentialité — AfriCode')

@section('content')
<div class="legal-page">

    <div class="legal-hero" style="background:linear-gradient(135deg,#1EA38B,#27B371);">
        <div class="container">
            <div class="legal-hero-inner">
                <div class="legal-hero-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <h1 class="legal-hero-title">Déclaration de confidentialité</h1>
                    <p class="legal-hero-sub">
                        Dernière mise à jour : <strong>{{ date('d/m/Y') }}</strong> &nbsp;·&nbsp;
                        <a href="{{ route('parametres.cookies') }}" class="legal-link">Paramètres cookies</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container legal-container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="legal-summary">
                    <div class="legal-summary-icon"><i class="fas fa-info-circle"></i></div>
                    <div>
                        <strong>En résumé</strong>
                        <p>AfriCode collecte uniquement les données nécessaires à son fonctionnement. Vos données ne sont jamais vendues. Vous pouvez exercer vos droits à tout moment.</p>
                    </div>
                </div>

                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-user-circle"></i></div>
                        <h2>1. Données collectées</h2>
                    </div>
                    <div class="legal-body">
                        <ul class="legal-list">
                            <li><i class="fas fa-check-circle"></i> Informations d'inscription (nom, adresse e-mail, etc.)</li>
                            <li><i class="fas fa-check-circle"></i> Données de navigation et d'utilisation de la plateforme</li>
                            <li><i class="fas fa-check-circle"></i> Informations de paiement lors de l'achat de cours (aucune donnée bancaire stockée)</li>
                            <li><i class="fas fa-check-circle"></i> Parcours d'apprentissage, progression, certifications obtenues</li>
                        </ul>
                    </div>
                </div>

                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-cogs"></i></div>
                        <h2>2. Utilisation des données</h2>
                    </div>
                    <div class="legal-body">
                        <ul class="legal-list">
                            <li><i class="fas fa-check-circle"></i> Fournir l'accès aux cours et services</li>
                            <li><i class="fas fa-check-circle"></i> Améliorer l'expérience utilisateur</li>
                            <li><i class="fas fa-check-circle"></i> Gérer les paiements et la facturation</li>
                            <li><i class="fas fa-check-circle"></i> Envoyer des notifications importantes</li>
                            <li><i class="fas fa-times-circle"></i> <strong>Jamais</strong> à des fins commerciales sans votre consentement explicite</li>
                        </ul>
                    </div>
                </div>

                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-share-alt"></i></div>
                        <h2>3. Partage des données</h2>
                    </div>
                    <div class="legal-body">
                        <p>Vos données ne sont jamais vendues à des tiers. Elles peuvent être partagées uniquement avec des partenaires de confiance pour le bon fonctionnement du service (prestataires de paiement, hébergement) dans le respect de la législation en vigueur.</p>
                    </div>
                </div>

                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-lock"></i></div>
                        <h2>4. Sécurité</h2>
                    </div>
                    <div class="legal-body">
                        <p>Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles (chiffrement SSL/TLS, mots de passe hashés, accès restreint) pour protéger vos informations contre tout accès non autorisé, perte ou divulgation.</p>
                    </div>
                </div>

                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-user-shield"></i></div>
                        <h2>5. Vos droits</h2>
                    </div>
                    <div class="legal-body">
                        <p>Vous disposez d'un droit d'accès, de rectification et de suppression de vos données personnelles. Pour toute demande, contactez-nous à <a href="mailto:privacy@support.africode.tech" class="legal-link-dark">privacy@support.africode.tech</a>. Réponse sous <strong>30 jours</strong>.</p>
                    </div>
                </div>

                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-cookie-bite"></i></div>
                        <h2>6. Cookies</h2>
                    </div>
                    <div class="legal-body">
                        <p>Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez <a href="{{ route('parametres.cookies') }}" class="legal-link-dark">modifier vos paramètres de cookies ici</a>.</p>
                    </div>
                </div>

                <div class="legal-footer-card" style="background:linear-gradient(135deg,#1EA38B,#27B371);">
                    <i class="fas fa-envelope-open-text"></i>
                    <div>
                        <strong>Des questions sur vos données personnelles ?</strong>
                        <p>Contactez notre équipe à <a href="mailto:privacy@support.africode.tech" class="legal-link">privacy@support.africode.tech</a> — Réponse sous 48h.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.legal-page { background:#f8fafc; min-height:100vh; padding-bottom:4rem; }
.legal-hero { color:white; padding:3rem 0 2.5rem; }
.legal-hero-inner { display:flex; align-items:center; gap:1.5rem; }
.legal-hero-icon { width:64px; height:64px; background:rgba(255,255,255,.15); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0; }
.legal-hero-title { font-size:2rem; font-weight:800; margin:0 0 .35rem; color:white; }
.legal-hero-sub { font-size:.9rem; color:rgba(255,255,255,.8); margin:0; }
.legal-link { color:#a7f3d0; text-decoration:none; font-weight:600; }
.legal-link:hover { text-decoration:underline; color:white; }
.legal-link-dark { color:#1EA38B; font-weight:600; text-decoration:none; }
.legal-link-dark:hover { text-decoration:underline; }
.legal-container { padding-top:2.5rem; }
.legal-summary { display:flex; gap:1rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:1.25rem 1.5rem; margin-bottom:2rem; }
.legal-summary-icon { font-size:1.4rem; color:#1EA38B; flex-shrink:0; margin-top:.1rem; }
.legal-summary p { margin:0; font-size:.88rem; color:#374151; }
.legal-section { background:white; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:1.25rem; overflow:hidden; }
.legal-section-header { display:flex; align-items:center; gap:.9rem; padding:1.1rem 1.5rem; border-bottom:1px solid #f3f4f6; }
.legal-icon-wrap { width:38px; height:38px; background:linear-gradient(135deg,rgba(30,163,139,.12),rgba(39,179,113,.18)); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#1EA38B; font-size:.95rem; flex-shrink:0; }
.legal-section-header h2 { font-size:1rem; font-weight:700; color:#1a1a2e; margin:0; }
.legal-body { padding:1.25rem 1.5rem; font-size:.9rem; color:#374151; line-height:1.7; }
.legal-body p { margin-bottom:.75rem; }
.legal-body p:last-child { margin-bottom:0; }
.legal-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.5rem; }
.legal-list li { display:flex; align-items:flex-start; gap:.65rem; font-size:.88rem; }
.legal-list li i { margin-top:.15rem; flex-shrink:0; color:#1EA38B; }
.legal-list li i.fa-times-circle { color:#ef4444; }
.legal-footer-card { display:flex; align-items:center; gap:1rem; color:white; border-radius:14px; padding:1.5rem 2rem; margin-top:2rem; }
.legal-footer-card i { font-size:2rem; opacity:.9; flex-shrink:0; }
.legal-footer-card strong { display:block; font-size:1rem; margin-bottom:.25rem; }
.legal-footer-card p { margin:0; font-size:.88rem; opacity:.9; }
</style>
@endsection
