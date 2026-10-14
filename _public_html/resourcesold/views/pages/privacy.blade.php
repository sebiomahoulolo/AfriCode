@extends('layouts.layout')

@section('title', 'Politique de confidentialité — AfriCode')

@section('content')
<div class="legal-page">

    {{-- ─── Hero ─── --}}
    <div class="legal-hero">
        <div class="container">
            <div class="legal-hero-inner">
                <div class="legal-hero-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h1 class="legal-hero-title">Politique de confidentialité</h1>
                    <p class="legal-hero-sub">
                        Dernière mise à jour : <strong>1er juin 2025</strong> &nbsp;·&nbsp;
                        <a href="{{ route('pages.terms') }}" class="legal-link">Voir les CGU</a>
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
                        <p>AfriCode collecte uniquement les données nécessaires à son fonctionnement. Nous ne vendons jamais vos données. Vous pouvez exercer vos droits à tout moment à <a href="mailto:privacy@africode.tech" class="legal-link">privacy@africode.tech</a>.</p>
                    </div>
                </div>

                {{-- 1. Responsable --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-building"></i></div>
                        <h2>1. Responsable du traitement</h2>
                    </div>
                    <div class="legal-body">
                        <p><strong>AfriCode</strong> est responsable du traitement de vos données personnelles. Pour toute question relative à la protection des données :</p>
                        <div class="legal-contact-card">
                            <div class="lcc-item"><i class="fas fa-envelope"></i> <a href="mailto:privacy@africode.tech" class="legal-link">privacy@africode.tech</a> <span class="lcc-badge">DPO / Responsable données</span></div>
                            <div class="lcc-item"><i class="fas fa-globe"></i> africode.tech</div>
                        </div>
                    </div>
                </div>

                {{-- 2. Données collectées --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-database"></i></div>
                        <h2>2. Données collectées</h2>
                    </div>
                    <div class="legal-body">
                        <p>Nous collectons les données suivantes :</p>
                        <div class="data-grid">
                            <div class="data-card">
                                <i class="fas fa-user-circle"></i>
                                <strong>Identité</strong>
                                <span>Prénom, nom, adresse email, mot de passe (hashé), rôle (apprenant/formateur)</span>
                            </div>
                            <div class="data-card">
                                <i class="fas fa-graduation-cap"></i>
                                <strong>Parcours</strong>
                                <span>Cours suivis, progression, quiz, certifications obtenues, notes de forum</span>
                            </div>
                            <div class="data-card">
                                <i class="fas fa-credit-card"></i>
                                <strong>Paiements</strong>
                                <span>Historique des transactions (montant, date, statut). Aucune donnée bancaire n'est stockée sur nos serveurs.</span>
                            </div>
                            <div class="data-card">
                                <i class="fas fa-laptop"></i>
                                <strong>Technique</strong>
                                <span>Adresse IP, type de navigateur, système d'exploitation, pages visitées, durée de session, logs d'erreurs</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Utilisation --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-cogs"></i></div>
                        <h2>3. Utilisation des données</h2>
                    </div>
                    <div class="legal-body">
                        <ul class="legal-list">
                            <li><i class="fas fa-check-circle"></i> Fournir et améliorer nos services de formation</li>
                            <li><i class="fas fa-check-circle"></i> Personnaliser votre expérience d'apprentissage</li>
                            <li><i class="fas fa-check-circle"></i> Délivrer vos certificats et attestations</li>
                            <li><i class="fas fa-check-circle"></i> Vous envoyer des communications liées à votre compte (avec votre accord)</li>
                            <li><i class="fas fa-check-circle"></i> Analyser l'audience pour améliorer la plateforme</li>
                            <li><i class="fas fa-times-circle text-danger"></i> <strong>Jamais</strong> à des fins commerciales sans votre consentement explicite</li>
                        </ul>
                    </div>
                </div>

                {{-- 4. Partage --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-share-alt"></i></div>
                        <h2>4. Partage des données</h2>
                    </div>
                    <div class="legal-body">
                        <p>Vos données ne sont jamais vendues. Elles peuvent être partagées uniquement avec :</p>
                        <ul class="legal-list">
                            <li><i class="fas fa-server"></i> <strong>Prestataires techniques</strong> : hébergement, envoi d'emails, passerelle de paiement (Moneroo) — liés par des accords de confidentialité stricts</li>
                            <li><i class="fas fa-briefcase"></i> <strong>Partenaires emploi</strong> : uniquement avec votre accord explicite et limité aux informations de certification</li>
                            <li><i class="fas fa-gavel"></i> <strong>Autorités légales</strong> : uniquement sur réquisition judiciaire</li>
                        </ul>
                    </div>
                </div>

                {{-- 5. Cookies --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-cookie-bite"></i></div>
                        <h2>5. Politique des cookies</h2>
                    </div>
                    <div class="legal-body">
                        <p>Nous utilisons trois catégories de cookies :</p>
                        <div class="cookie-table">
                            <div class="ct-header">
                                <span>Catégorie</span><span>Description</span><span>Contrôle</span>
                            </div>
                            <div class="ct-row">
                                <span><i class="fas fa-shield-alt" style="color:#11c06d;"></i> Nécessaires</span>
                                <span>Session, authentification, sécurité CSRF, préférences UI. Indispensables au fonctionnement.</span>
                                <span class="ct-badge ct-always">Toujours actifs</span>
                            </div>
                            <div class="ct-row">
                                <span><i class="fas fa-chart-bar" style="color:#11c06d;"></i> Analyse</span>
                                <span>Mesure d'audience anonymisée (pages vues, parcours, taux de complétion des cours).</span>
                                <span class="ct-badge ct-opt">Optionnel</span>
                            </div>
                            <div class="ct-row">
                                <span><i class="fas fa-bullhorn" style="color:#f59e0b;"></i> Marketing</span>
                                <span>Personnalisation des recommandations de cours et communications ciblées.</span>
                                <span class="ct-badge ct-opt">Optionnel</span>
                            </div>
                        </div>
                        <p class="mt-3">
                            <button onclick="window.openCookiePreferences && window.openCookiePreferences()" class="btn-legal-action">
                                <i class="fas fa-sliders-h me-2"></i>Gérer mes préférences cookies
                            </button>
                        </p>
                    </div>
                </div>

                {{-- 6. Sécurité --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-lock"></i></div>
                        <h2>6. Sécurité des données</h2>
                    </div>
                    <div class="legal-body">
                        <div class="security-grid">
                            <div class="sg-item"><i class="fas fa-key"></i> Mots de passe hashés (bcrypt)</div>
                            <div class="sg-item"><i class="fas fa-network-wired"></i> Connexions chiffrées SSL/TLS</div>
                            <div class="sg-item"><i class="fas fa-user-lock"></i> Accès restreint aux données</div>
                            <div class="sg-item"><i class="fas fa-database"></i> Sauvegardes régulières chiffrées</div>
                        </div>
                    </div>
                </div>

                {{-- 7. Transferts internationaux --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-globe-africa"></i></div>
                        <h2>7. Transferts internationaux</h2>
                    </div>
                    <div class="legal-body">
                        <p>Nos serveurs sont principalement hébergés en Afrique et en Europe. En cas de transfert hors d'Afrique, nous nous assurons que les prestataires respectent des garanties équivalentes au RGPD (clauses contractuelles types ou certification Privacy Shield).</p>
                    </div>
                </div>

                {{-- 8. Vos droits --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-user-shield"></i></div>
                        <h2>8. Vos droits</h2>
                    </div>
                    <div class="legal-body">
                        <div class="rights-grid">
                            <div class="right-item"><i class="fas fa-eye"></i><strong>Accès</strong><span>Obtenir une copie de vos données</span></div>
                            <div class="right-item"><i class="fas fa-edit"></i><strong>Rectification</strong><span>Corriger des données inexactes</span></div>
                            <div class="right-item"><i class="fas fa-trash"></i><strong>Effacement</strong><span>Demander la suppression de votre compte</span></div>
                            <div class="right-item"><i class="fas fa-ban"></i><strong>Opposition</strong><span>Vous opposer à certains traitements</span></div>
                            <div class="right-item"><i class="fas fa-download"></i><strong>Portabilité</strong><span>Recevoir vos données dans un format lisible</span></div>
                            <div class="right-item"><i class="fas fa-pause-circle"></i><strong>Limitation</strong><span>Limiter le traitement de vos données</span></div>
                        </div>
                        <div class="legal-cta-box mt-4">
                            <p><strong>Pour exercer vos droits :</strong></p>
                            <p>Envoyez votre demande à <a href="mailto:privacy@africode.tech" class="legal-link">privacy@africode.tech</a> en précisant votre nom, email et la nature de votre demande. Réponse sous <strong>30 jours</strong>.</p>
                        </div>
                    </div>
                </div>

                {{-- 9. Conservation --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-calendar-alt"></i></div>
                        <h2>9. Durée de conservation</h2>
                    </div>
                    <div class="legal-body">
                        <ul class="legal-list">
                            <li><i class="fas fa-clock"></i> <strong>Données de compte actif</strong> : conservées pendant toute la durée d'utilisation</li>
                            <li><i class="fas fa-clock"></i> <strong>Après suppression de compte</strong> : anonymisation sous 30 jours, sauf obligation légale</li>
                            <li><i class="fas fa-clock"></i> <strong>Logs techniques</strong> : 12 mois maximum</li>
                            <li><i class="fas fa-clock"></i> <strong>Données de paiement</strong> : 5 ans (obligation comptable)</li>
                        </ul>
                    </div>
                </div>

                {{-- 10. Modifications --}}
                <div class="legal-section">
                    <div class="legal-section-header">
                        <div class="legal-icon-wrap"><i class="fas fa-file-alt"></i></div>
                        <h2>10. Modifications de cette politique</h2>
                    </div>
                    <div class="legal-body">
                        <p>Cette politique peut être mise à jour. En cas de changement important, vous serez informé par email et via une notification sur la plateforme au moins <strong>15 jours avant</strong> l'entrée en vigueur.</p>
                    </div>
                </div>

                {{-- Contact --}}
                <div class="legal-footer-card">
                    <i class="fas fa-envelope-open-text"></i>
                    <div>
                        <strong>Des questions sur vos données personnelles ?</strong>
                        <p>Contactez notre équipe Confidentialité à <a href="mailto:privacy@africode.tech" class="legal-link">privacy@africode.tech</a></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* ─── Hero ──────────────────────────────── */
.legal-page { background:#f8fafc; min-height:100vh; padding-bottom:4rem; }
.legal-hero { background:linear-gradient(135deg,#0d7a63,#11c06d); color:white; padding:3rem 0 2.5rem; }
.legal-hero-inner { display:flex; align-items:center; gap:1.5rem; }
.legal-hero-icon { width:64px; height:64px; background:rgba(255,255,255,.15); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0; }
.legal-hero-title { font-size:2rem; font-weight:800; margin:0 0 .35rem; color:white; }
.legal-hero-sub { font-size:.9rem; color:rgba(255,255,255,.8); margin:0; }
.legal-link { color:#a7f3d0; text-decoration:none; font-weight:600; }
.legal-link:hover { text-decoration:underline; color:white; }

/* ─── Container ─────────────────────────── */
.legal-container { padding-top:2.5rem; }

/* ─── Résumé ────────────────────────────── */
.legal-summary { display:flex; gap:1rem; background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px; padding:1.25rem 1.5rem; margin-bottom:2rem; }
.legal-summary-icon { font-size:1.4rem; color:#11c06d; flex-shrink:0; margin-top:.1rem; }
.legal-summary p { margin:0; font-size:.88rem; color:#374151; }

/* ─── Sections ──────────────────────────── */
.legal-section { background:white; border-radius:14px; box-shadow:0 2px 12px rgba(0,0,0,.06); margin-bottom:1.25rem; overflow:hidden; }
.legal-section-header { display:flex; align-items:center; gap:.9rem; padding:1.1rem 1.5rem; border-bottom:1px solid #f3f4f6; }
.legal-icon-wrap { width:38px; height:38px; background:linear-gradient(135deg,#11c06d22,#239e7933); border-radius:10px; display:flex; align-items:center; justify-content:center; color:#11c06d; font-size:.95rem; flex-shrink:0; }
.legal-section-header h2 { font-size:1rem; font-weight:700; color:#1a1a2e; margin:0; }
.legal-body { padding:1.25rem 1.5rem; font-size:.9rem; color:#374151; line-height:1.7; }
.legal-body p { margin-bottom:.75rem; }
.legal-body p:last-child { margin-bottom:0; }

/* ─── Contact card ──────────────────────── */
.legal-contact-card { background:#f8fafc; border-radius:10px; padding:1rem 1.25rem; margin-top:.75rem; }
.lcc-item { display:flex; align-items:center; gap:.75rem; font-size:.88rem; color:#374151; margin-bottom:.4rem; }
.lcc-item:last-child { margin-bottom:0; }
.lcc-item i { color:#11c06d; width:16px; }
.lcc-badge { background:#dcfce7; color:#16a34a; font-size:.72rem; font-weight:700; padding:2px 8px; border-radius:20px; }

/* ─── Data grid ─────────────────────────── */
.data-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; margin-top:.5rem; }
@media(max-width:576px){ .data-grid { grid-template-columns:1fr; } }
.data-card { background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:1rem; display:flex; flex-direction:column; gap:.35rem; }
.data-card i { font-size:1.3rem; color:#11c06d; }
.data-card strong { font-size:.88rem; color:#1a1a2e; }
.data-card span { font-size:.8rem; color:#6b7280; line-height:1.4; }

/* ─── Legal list ────────────────────────── */
.legal-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.5rem; }
.legal-list li { display:flex; align-items:flex-start; gap:.65rem; font-size:.88rem; }
.legal-list li i { margin-top:.15rem; flex-shrink:0; color:#11c06d; }
.legal-list li i.fa-times-circle { color:#ef4444; }
.legal-list li i.fa-gavel, .legal-list li i.fa-server, .legal-list li i.fa-briefcase { color:#6b7280; }
.legal-list li i.fa-clock { color:#11c06d; }

/* ─── Cookie table ──────────────────────── */
.cookie-table { border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; font-size:.83rem; }
.ct-header { display:grid; grid-template-columns:150px 1fr 110px; background:#f8fafc; padding:.7rem 1rem; font-weight:700; color:#6b7280; font-size:.78rem; text-transform:uppercase; letter-spacing:.5px; gap:1rem; }
.ct-row { display:grid; grid-template-columns:150px 1fr 110px; padding:.85rem 1rem; border-top:1px solid #e5e7eb; color:#374151; align-items:center; gap:1rem; }
.ct-row:nth-child(even){ background:#fafafa; }
@media(max-width:576px){
    .ct-header { grid-template-columns:1fr; }
    .ct-row { grid-template-columns:1fr; }
    .ct-header span:not(:first-child){ display:none; }
}
.ct-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.ct-always { background:#dcfce7; color:#16a34a; }
.ct-opt { background:#fef3c7; color:#92400e; }

/* ─── Btn préférences ───────────────────── */
.btn-legal-action { background:linear-gradient(135deg,#11c06d,#239e79); color:white; border:none; padding:.6rem 1.25rem; border-radius:8px; font-weight:600; font-size:.85rem; cursor:pointer; transition:all .2s; }
.btn-legal-action:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(17,192,109,.3); }

/* ─── Security grid ─────────────────────── */
.security-grid { display:grid; grid-template-columns:1fr 1fr; gap:.6rem; }
@media(max-width:480px){ .security-grid { grid-template-columns:1fr; } }
.sg-item { display:flex; align-items:center; gap:.65rem; background:#f8fafc; border-radius:8px; padding:.65rem .9rem; font-size:.85rem; color:#374151; }
.sg-item i { color:#11c06d; width:16px; }

/* ─── Rights grid ───────────────────────── */
.rights-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:.75rem; }
@media(max-width:576px){ .rights-grid { grid-template-columns:1fr 1fr; } }
.right-item { background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; padding:.9rem; display:flex; flex-direction:column; align-items:center; text-align:center; gap:.35rem; }
.right-item i { font-size:1.3rem; color:#11c06d; }
.right-item strong { font-size:.85rem; color:#1a1a2e; }
.right-item span { font-size:.76rem; color:#6b7280; line-height:1.3; }

/* ─── CTA box ───────────────────────────── */
.legal-cta-box { background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:10px; padding:1rem 1.25rem; font-size:.88rem; }
.legal-cta-box p { margin-bottom:.3rem; }
.legal-cta-box p:last-child { margin-bottom:0; }

/* ─── Footer card ───────────────────────── */
.legal-footer-card { display:flex; align-items:center; gap:1rem; background:linear-gradient(135deg,#11c06d,#239e79); color:white; border-radius:14px; padding:1.5rem 2rem; margin-top:2rem; }
.legal-footer-card i { font-size:2rem; opacity:.9; flex-shrink:0; }
.legal-footer-card strong { display:block; font-size:1rem; margin-bottom:.25rem; }
.legal-footer-card p { margin:0; font-size:.88rem; opacity:.9; }
.legal-footer-card .legal-link { color:#a7f3d0; }
</style>
@endsection
