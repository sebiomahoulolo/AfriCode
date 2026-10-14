@extends('layouts.layout')

@section('title', 'Conditions d\'utilisation — AfriCode')

@section('content')
<div class="legal-page">

    {{-- ─── Hero ─── --}}
    <div class="legal-hero" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
        <div class="container">
            <div class="legal-hero-inner">
                <div class="legal-hero-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div>
                    <h1 class="legal-hero-title">Conditions d'utilisation</h1>
                    <p class="legal-hero-sub">
                        Dernière mise à jour : <strong>1er juin 2025</strong> &nbsp;·&nbsp;
                        <a href="{{ route('pages.privacy') }}" class="legal-link">Politique de confidentialité</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container legal-container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                {{-- Résumé --}}
                <div class="legal-summary">
                    <div class="legal-summary-icon"><i class="fas fa-info-circle"></i></div>
                    <div>
                        <strong>En résumé</strong>
                        <p>En utilisant AfriCode, vous vous engagez à respecter ces conditions. Les contenus sont protégés. Les certifications sont délivrées sur la base de l'intégrité académique. Vous pouvez supprimer votre compte à tout moment.</p>
                    </div>
                </div>

                {{-- 1. La plateforme --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-laptop-code"></i></div>
                        <h2>1. Présentation de la plateforme</h2>
                    </div>
                    <div class="legal-body">
                        <p><strong>AfriCode</strong> est une plateforme de formation numérique dédiée à l'Afrique, proposant des cours certifiants, des événements tech, des sessions de mentorat et des ressources pour développer les compétences numériques de demain sur le continent africain.</p>
                        <p>AfriCode est accessible à toute personne disposant d'une connexion internet, sans discrimination de genre, d'origine ou de localisation géographique sur le continent africain et au-delà.</p>
                    </div>
                </div>

                {{-- 2. Acceptation --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-handshake"></i></div>
                        <h2>2. Acceptation des conditions</h2>
                    </div>
                    <div class="legal-body">
                        <p>En créant un compte ou en utilisant nos services, vous acceptez sans réserve les présentes conditions d'utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser la plateforme.</p>
                        <div class="legal-warning-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Ces conditions peuvent être mises à jour. La poursuite de l'utilisation de la plateforme après notification des changements vaut acceptation des nouvelles conditions.</span>
                        </div>
                    </div>
                </div>

                {{-- 3. Compte --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-user-circle"></i></div>
                        <h2>3. Inscription et compte utilisateur</h2>
                    </div>
                    <div class="legal-body">
                        <ul class="legal-list">
                            <li><i class="fas fa-check-circle"></i> Vous devez fournir des informations exactes, complètes et à jour lors de l'inscription.</li>
                            <li><i class="fas fa-check-circle"></i> Vous êtes responsable de la confidentialité de votre mot de passe et des actions effectuées depuis votre compte.</li>
                            <li><i class="fas fa-check-circle"></i> Un seul compte par personne est autorisé.</li>
                            <li><i class="fas fa-times-circle"></i> L'usurpation d'identité entraîne la suspension immédiate et définitive du compte.</li>
                            <li><i class="fas fa-times-circle"></i> Le partage de compte est interdit.</li>
                        </ul>
                    </div>
                </div>

                {{-- 4. Contenus --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-book-open"></i></div>
                        <h2>4. Propriété intellectuelle et utilisation des contenus</h2>
                    </div>
                    <div class="legal-body">
                        <p>Tous les contenus disponibles sur AfriCode (cours, vidéos, quiz, exercices pratiques, certifications, visuels) sont protégés par le droit d'auteur et appartiennent à AfriCode ou à ses formateurs partenaires.</p>
                        <div class="terms-permissions">
                            <div class="perm-card perm-ok">
                                <div class="perm-header"><i class="fas fa-check"></i> Autorisé</div>
                                <ul>
                                    <li>Usage personnel et non commercial des contenus</li>
                                    <li>Prise de notes et résumés personnels</li>
                                    <li>Partage de votre certificat obtenu</li>
                                </ul>
                            </div>
                            <div class="perm-card perm-no">
                                <div class="perm-header"><i class="fas fa-times"></i> Interdit</div>
                                <ul>
                                    <li>Reproduction, diffusion ou vente sans autorisation</li>
                                    <li>Téléchargement de vidéos ou contenus protégés</li>
                                    <li>Utilisation commerciale des ressources pédagogiques</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. Comportement --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-users"></i></div>
                        <h2>5. Comportement sur la plateforme</h2>
                    </div>
                    <div class="legal-body">
                        <ul class="legal-list">
                            <li><i class="fas fa-check-circle"></i> Respectez les autres membres, les formateurs et l'équipe AfriCode.</li>
                            <li><i class="fas fa-times-circle"></i> Tout propos haineux, discriminatoire, harcelant ou frauduleux entraîne la suspension immédiate du compte.</li>
                            <li><i class="fas fa-times-circle"></i> Le spam, la publicité non autorisée et le démarchage commercial sont strictement interdits.</li>
                            <li><i class="fas fa-times-circle"></i> La publication de contenus illégaux, offensants ou portant atteinte aux droits de tiers est prohibée.</li>
                        </ul>
                    </div>
                </div>

                {{-- 6. Certifications --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap" style="background:linear-gradient(135deg,#fea83722,#fea83733); color:#fea837;"><i class="fas fa-certificate"></i></div>
                        <h2>6. Certifications et attestations</h2>
                    </div>
                    <div class="legal-body">
                        <div class="cert-info-grid">
                            <div class="ci-item"><i class="fas fa-tasks"></i><div><strong>Conditions d'obtention</strong><p>Réussite de toutes les évaluations requises (quiz, projets pratiques) avec le score minimum défini par chaque formation.</p></div></div>
                            <div class="ci-item"><i class="fas fa-shield-alt"></i><div><strong>Intégrité académique</strong><p>Toute tentative de triche, d'utilisation d'outils non autorisés ou d'usurpation lors des évaluations entraîne l'annulation immédiate du certificat.</p></div></div>
                            <div class="ci-item"><i class="fas fa-qrcode"></i><div><strong>Vérification par les employeurs</strong><p>Chaque certificat AfriCode est doté d'un code de vérification unique. Les employeurs et partenaires peuvent vérifier l'authenticité sur notre <a href="{{ route('certificate.verification') }}" class="legal-link-dark">page de vérification</a>.</p></div></div>
                            <div class="ci-item"><i class="fas fa-ban"></i><div><strong>Révocation</strong><p>AfriCode se réserve le droit de révoquer tout certificat en cas de fraude avérée ou de violation grave de ces conditions.</p></div></div>
                        </div>
                    </div>
                </div>

                {{-- 7. Paiements --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-credit-card"></i></div>
                        <h2>7. Paiements et remboursements</h2>
                    </div>
                    <div class="legal-body">
                        <ul class="legal-list">
                            <li><i class="fas fa-check-circle"></i> Les paiements sont traités via Moneroo (Mobile Money, Visa, Mastercard) de façon sécurisée.</li>
                            <li><i class="fas fa-check-circle"></i> Aucune donnée bancaire n'est stockée sur nos serveurs.</li>
                            <li><i class="fas fa-check-circle"></i> En cas de problème technique lors du paiement, contactez <a href="mailto:support@africode.tech" class="legal-link-dark">support@africode.tech</a>.</li>
                            <li><i class="fas fa-info-circle" style="color:#11c06d;"></i> Les demandes de remboursement sont examinées au cas par cas dans les 7 jours suivant l'achat.</li>
                        </ul>
                    </div>
                </div>

                {{-- 8. Données --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-user-shield"></i></div>
                        <h2>8. Données personnelles</h2>
                    </div>
                    <div class="legal-body">
                        <p>Vos données sont traitées conformément à notre <a href="{{ route('pages.privacy') }}" class="legal-link-dark">politique de confidentialité</a>, conforme aux principes du RGPD et à la Convention de Malabo sur la cybersécurité et la protection des données personnelles en Afrique.</p>
                        <p>Vous pouvez demander la suppression complète de votre compte et de vos données à tout moment depuis votre espace personnel ou en contactant <a href="mailto:privacy@africode.tech" class="legal-link-dark">privacy@africode.tech</a>.</p>
                    </div>
                </div>

                {{-- 9. Modification --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-sync-alt"></i></div>
                        <h2>9. Modification des conditions</h2>
                    </div>
                    <div class="legal-body">
                        <p>AfriCode se réserve le droit de modifier les présentes conditions à tout moment. Les utilisateurs seront informés des changements importants par email et notification sur la plateforme, au moins <strong>15 jours avant</strong> l'entrée en vigueur des modifications.</p>
                    </div>
                </div>

                {{-- 10. Contact --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-envelope"></i></div>
                        <h2>10. Contact et réclamations</h2>
                    </div>
                    <div class="legal-body">
                        <div class="legal-contact-card">
                            <div class="lcc-item"><i class="fas fa-envelope"></i> Questions générales : <a href="mailto:contact@africode.tech" class="legal-link-dark">contact@africode.tech</a></div>
                            <div class="lcc-item"><i class="fas fa-headset"></i> Support technique : <a href="mailto:support@africode.tech" class="legal-link-dark">support@africode.tech</a></div>
                            <div class="lcc-item"><i class="fas fa-shield-alt"></i> Données personnelles : <a href="mailto:privacy@africode.tech" class="legal-link-dark">privacy@africode.tech</a></div>
                        </div>
                    </div>
                </div>

                {{-- Footer card --}}
                <div class="legal-footer-card" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
                    <i class="fas fa-file-contract"></i>
                    <div>
                        <strong>Vous avez des questions sur ces conditions ?</strong>
                        <p>Notre équipe répond dans les <strong>48h ouvrées</strong>. <a href="{{ route('pages.contact') }}" class="legal-link">Contactez-nous</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* Styles partagés avec privacy.blade.php */
.legal-page { background:#f8fafc; min-height:100vh; padding-bottom:4rem; }
.legal-hero { color:white; padding:3rem 0 2.5rem; }
.legal-hero-inner { display:flex; align-items:center; gap:1.5rem; }
.legal-hero-icon { width:64px; height:64px; background:rgba(255,255,255,.15); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0; }
.legal-hero-title { font-size:2rem; font-weight:800; margin:0 0 .35rem; color:white; }
.legal-hero-sub { font-size:.9rem; color:rgba(255,255,255,.75); margin:0; }
.legal-link { color:#a7f3d0; text-decoration:none; font-weight:600; }
.legal-link:hover { text-decoration:underline; color:white; }
.legal-link-dark { color:#11c06d; font-weight:600; text-decoration:none; }
.legal-link-dark:hover { text-decoration:underline; }
.legal-container { padding-top:2.5rem; }
.legal-summary { display:flex; gap:1rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:1.25rem 1.5rem; margin-bottom:2rem; }
.legal-summary-icon { font-size:1.4rem; color:#11c06d; flex-shrink:0; margin-top:.1rem; }
.legal-summary p { margin:0; font-size:.88rem; color:#374151; }
.legal-section { background:white; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:1.25rem; overflow:hidden; }
.legal-section-header { display:flex; align-items:center; gap:.9rem; padding:1.1rem 1.5rem; border-bottom:1px solid #f3f4f6; }
.legal-icon-wrap { width:38px; height:38px; background:linear-gradient(135deg,#11c06d22,#239e7933); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#11c06d; font-size:.95rem; flex-shrink:0; }
.legal-section-header h2 { font-size:1rem; font-weight:700; color:#1a1a2e; margin:0; }
.legal-body { padding:1.25rem 1.5rem; font-size:.9rem; color:#374151; line-height:1.7; }
.legal-body p { margin-bottom:.75rem; }
.legal-body p:last-child { margin-bottom:0; }
.legal-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.5rem; }
.legal-list li { display:flex; align-items:flex-start; gap:.65rem; font-size:.88rem; }
.legal-list li i { margin-top:.15rem; flex-shrink:0; color:#11c06d; }
.legal-list li i.fa-times-circle { color:#ef4444; }
.legal-contact-card { background:#f8fafc; border-radius:10px; padding:1rem 1.25rem; }
.lcc-item { display:flex; align-items:center; gap:.75rem; font-size:.88rem; color:#374151; margin-bottom:.5rem; }
.lcc-item:last-child { margin-bottom:0; }
.lcc-item i { color:#11c06d; width:16px; }

/* Avertissement */
.legal-warning-box { display:flex; align-items:flex-start; gap:.75rem; background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:1rem; margin-top:.75rem; font-size:.85rem; color:#92400e; }
.legal-warning-box i { color:#f59e0b; flex-shrink:0; margin-top:.1rem; }

/* Permissions */
.terms-permissions { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-top:.75rem; }
@media(max-width:576px){ .terms-permissions { grid-template-columns:1fr; } }
.perm-card { border-radius:10px; padding:1rem; }
.perm-ok { background:#f0fdf4; border:1.5px solid #bbf7d0; }
.perm-no { background:#fef2f2; border:1.5px solid #fecaca; }
.perm-header { display:flex; align-items:center; gap:.5rem; font-weight:700; font-size:.88rem; margin-bottom:.6rem; }
.perm-ok .perm-header { color:#16a34a; }
.perm-no .perm-header { color:#dc2626; }
.perm-card ul { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.35rem; }
.perm-card ul li { font-size:.82rem; color:#374151; padding-left:.25rem; }
.perm-card ul li::before { content:'—'; margin-right:.4rem; opacity:.4; }

/* Certifications */
.cert-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
@media(max-width:576px){ .cert-info-grid { grid-template-columns:1fr; } }
.ci-item { display:flex; gap:.75rem; background:#f8fafc; border-radius:10px; padding:.9rem; align-items:flex-start; }
.ci-item i { font-size:1.2rem; color:#fea837; flex-shrink:0; margin-top:.1rem; }
.ci-item strong { display:block; font-size:.87rem; color:#1a1a2e; margin-bottom:.25rem; }
.ci-item p { font-size:.8rem; color:#6b7280; margin:0; line-height:1.4; }

/* Footer card */
.legal-footer-card { display:flex; align-items:center; gap:1rem; color:white; border-radius:14px; padding:1.5rem 2rem; margin-top:2rem; }
.legal-footer-card i { font-size:2rem; opacity:.9; flex-shrink:0; }
.legal-footer-card strong { display:block; font-size:1rem; margin-bottom:.25rem; }
.legal-footer-card p { margin:0; font-size:.88rem; opacity:.9; }
</style>
@endsection
