@extends('layouts.layout')

@section('title', 'Nos Services — AfriCode | Formation · Développement · Cybersécurité · SaaS')
@section('meta_description', 'Découvrez les services AfriCode : formations numériques, développement de solutions sur mesure, cybersécurité, SaaS et conseil stratégique pour les organisations africaines.')
@section('meta_keywords', 'services AfriCode, formation numérique Afrique, développement logiciel, cybersécurité Afrique, SaaS Afrique, conseil IT, hub numérique africain')
@section('og_type', 'website')
@section('og_title', 'Nos Services — AfriCode | Hub Numérique Africain')
@section('og_description', 'Formation · Développement · Cybersécurité · SaaS · Conseil — Les services AfriCode pour accompagner la transformation numérique en Afrique.')
@section('og_image', asset('images/africode-og.jpg'))

@push('meta')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Services AfriCode",
  "provider": { "@type": "Organization", "name": "AfriCode", "url": "{{ url('/') }}" },
  "serviceType": ["Formation numérique", "Développement logiciel", "Cybersécurité", "Conseil SaaS"],
  "areaServed": "Afrique",
  "url": "{{ url('/nos-services') }}"
}
</script>
@endpush

@section('content')
<div class="spg">

{{-- ══════════════════════════════════════════════
     HERO
══════════════════════════════════════════════ --}}
<section class="spg-hero">
    <div class="spg-hero-bg">
        <div class="spg-orb spg-orb1"></div>
        <div class="spg-orb spg-orb2"></div>
        <div class="spg-orb spg-orb3"></div>
    </div>
    <div class="container spg-hero-wrap">
        <div class="spg-hero-left">
            <div class="spg-pill">
                <span class="spg-pill-dot"></span>
                <i class="fas fa-globe-africa me-2"></i>Hub Numérique Africain
            </div>
            <h1 class="spg-h1">
                Construire<br>
                <span class="spg-h1-accent">l'Afrique numérique</span><br>
                de demain.
            </h1>
            <p class="spg-hero-lead">
                AfriCode accompagne les organisations, entreprises et institutions dans leur
                <strong>transformation numérique</strong> grâce à un écosystème unique combinant
                formation, développement, cybersécurité, SaaS et conseil.
            </p>
            <div class="spg-checks">
                <div class="spg-check"><i class="fas fa-check-circle"></i><span>Formation des talents</span></div>
                <div class="spg-check"><i class="fas fa-check-circle"></i><span>Développement de solutions</span></div>
                <div class="spg-check"><i class="fas fa-check-circle"></i><span>Cybersécurité</span></div>
                <div class="spg-check"><i class="fas fa-check-circle"></i><span>SaaS & Plateformes métiers</span></div>
                <div class="spg-check"><i class="fas fa-check-circle"></i><span>Conseil technologique</span></div>
                <div class="spg-check"><i class="fas fa-check-circle"></i><span>Recrutement Tech</span></div>
            </div>
            <div class="spg-hero-ctas">
                <a href="{{ route('courses.index') }}" class="spg-cta-green">
                    <i class="fas fa-graduation-cap me-2"></i>Découvrir nos formations
                </a>
                <button type="button" class="spg-cta-outline" onclick="spgOpenModal('Renseignement général')">
                    <i class="fas fa-paper-plane me-2"></i>Parler de votre projet
                </button>
            </div>
        </div>
        <div class="spg-hero-right" aria-hidden="true">
            <div class="spg-hub">
                <div class="spg-hub-ring spg-hub-ring-outer"></div>
                <div class="spg-hub-ring spg-hub-ring-inner"></div>
                <div class="spg-hub-center">
                    <i class="fas fa-layer-group"></i>
                    <span>AfriCode</span>
                </div>
                <div class="spg-hub-node spg-hn1"><i class="fas fa-graduation-cap"></i><span>Academy</span></div>
                <div class="spg-hub-node spg-hn2"><i class="fas fa-code"></i><span>Dev</span></div>
                <div class="spg-hub-node spg-hn3"><i class="fas fa-shield-alt"></i><span>Cyber</span></div>
                <div class="spg-hub-node spg-hn4"><i class="fas fa-cloud"></i><span>SaaS</span></div>
                <div class="spg-hub-node spg-hn5"><i class="fas fa-users"></i><span>Talent</span></div>
                <div class="spg-hub-node spg-hn6"><i class="fas fa-chart-line"></i><span>Conseil</span></div>
            </div>
        </div>
    </div>
    <div class="spg-stats-bar">
        <div class="container">
            <div class="spg-stats">
                <div class="spg-stat"><strong data-count="200">0</strong><span>Apprenants</span></div>
                <div class="spg-stat-sep"></div>
                <div class="spg-stat"><strong data-count="60">0</strong><span>Cours</span></div>
                <div class="spg-stat-sep"></div>
                <div class="spg-stat"><strong data-count="15">0</strong><span>Formateurs experts</span></div>
                <div class="spg-stat-sep"></div>
                <div class="spg-stat"><strong data-count="10">0</strong><span>Pays couverts</span></div>
                <div class="spg-stat-sep"></div>
                <div class="spg-stat"><strong>🌍</strong><span>Vision continentale</span></div>
            </div>
        </div>
    </div>
    <div class="spg-hero-wave">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none"><path d="M0,30C360,60 1080,0 1440,30L1440,60L0,60Z" fill="#f8fafc"/></svg>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     STICKY NAV
══════════════════════════════════════════════ --}}
<nav class="spg-nav" id="spgNav">
    <div class="container">
        <div class="spg-nav-inner">
            <a class="spg-nav-item active" href="#academy"    data-target="academy"><i class="fas fa-graduation-cap"></i><span>Academy</span></a>
            <a class="spg-nav-item"        href="#enterprise" data-target="enterprise"><i class="fas fa-code"></i><span>Enterprise</span></a>
            <a class="spg-nav-item"        href="#cyber"      data-target="cyber"><i class="fas fa-shield-alt"></i><span>CyberSecurity</span></a>
            <a class="spg-nav-item"        href="#digital"    data-target="digital"><i class="fas fa-sync-alt"></i><span>Digital</span></a>
            <a class="spg-nav-item"        href="#saas"       data-target="saas"><i class="fas fa-cloud"></i><span>SaaS</span></a>
            <a class="spg-nav-item"        href="#talent"     data-target="talent"><i class="fas fa-users"></i><span>Talent</span></a>
            <a class="spg-nav-item"        href="#consulting" data-target="consulting"><i class="fas fa-chart-line"></i><span>Consulting</span></a>
        </div>
    </div>
</nav>

<div class="spg-body">

{{-- ══════════════════════════════════════════════
     01 · AFRICODE ACADEMY
══════════════════════════════════════════════ --}}
<section id="academy" class="spg-section">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-green"><i class="fas fa-graduation-cap me-2"></i>Service 01</div>
            <h2 class="spg-sec-title">AfriCode <span class="spg-hl">Academy</span></h2>
            <p class="spg-sec-sub">Former les talents numériques africains. Notre plateforme de formation professionnelle permet aux étudiants, professionnels et organisations d'acquérir les compétences les plus recherchées du marché.</p>
        </div>

        <div class="spg-domains spg-reveal">
            <div class="spg-domain spg-dom-green">
                <div class="spg-dom-icon"><i class="fas fa-code"></i></div>
                <h4>Développement Logiciel</h4>
                <ul><li>Laravel / PHP</li><li>JavaScript &amp; React</li><li>Flutter Mobile</li><li>APIs REST</li><li>Architecture logicielle</li></ul>
            </div>
            <div class="spg-domain spg-dom-purple">
                <div class="spg-dom-icon"><i class="fas fa-brain"></i></div>
                <h4>Intelligence Artificielle</h4>
                <ul><li>IA Générative</li><li>Machine Learning</li><li>Data Science</li><li>Analyse de données</li></ul>
            </div>
            <div class="spg-domain spg-dom-blue">
                <div class="spg-dom-icon"><i class="fas fa-cloud"></i></div>
                <h4>Cloud &amp; DevOps</h4>
                <ul><li>AWS · Azure · GCP</li><li>Docker · Kubernetes</li><li>CI/CD pipelines</li><li>Infrastructure as Code</li></ul>
            </div>
            <div class="spg-domain spg-dom-red">
                <div class="spg-dom-icon"><i class="fas fa-shield-alt"></i></div>
                <h4>Cybersécurité</h4>
                <ul><li>Ethical Hacking</li><li>Pentest applicatif</li><li>Sécurité réseau</li><li>Gouvernance SSI</li></ul>
            </div>
            <div class="spg-domain spg-dom-orange">
                <div class="spg-dom-icon"><i class="fas fa-briefcase"></i></div>
                <h4>Compétences Pro</h4>
                <ul><li>Gestion de projets</li><li>Leadership tech</li><li>Entrepreneuriat digital</li><li>Communication</li></ul>
            </div>
        </div>

        <div class="spg-audience spg-reveal">
            <h5><i class="fas fa-users me-2"></i>Pour qui ?</h5>
            <div class="spg-audience-tags">
                <span><i class="fas fa-user-graduate me-1"></i>Étudiants</span>
                <span><i class="fas fa-user-tie me-1"></i>Professionnels</span>
                <span><i class="fas fa-lightbulb me-1"></i>Entrepreneurs</span>
                <span><i class="fas fa-landmark me-1"></i>Administrations</span>
                <span><i class="fas fa-building me-1"></i>Entreprises</span>
                <span><i class="fas fa-university me-1"></i>Universités</span>
            </div>
        </div>

        <div class="spg-cta-row spg-reveal">
            <a href="{{ route('courses.index') }}" class="spg-btn-green"><i class="fas fa-play me-2"></i>Explorer les formations</a>
            <a href="{{ route('register') }}"      class="spg-btn-ghost"><i class="fas fa-user-plus me-2"></i>S'inscrire gratuitement</a>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     02 · AFRICODE ENTERPRISE
══════════════════════════════════════════════ --}}
<section id="enterprise" class="spg-section spg-section-alt">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-orange"><i class="fas fa-code me-2"></i>Service 02</div>
            <h2 class="spg-sec-title">AfriCode <span class="spg-hl-or">Enterprise</span></h2>
            <p class="spg-sec-sub">Solutions numériques sur mesure pour entreprises et institutions. Nous créons vos outils numériques — du site vitrine à la transformation digitale complète.</p>
        </div>

        <div class="spg-ent-cards spg-reveal">
            <div class="spg-ent-card">
                <div class="spg-ent-icon spg-ei-green"><i class="fas fa-globe"></i></div>
                <h4>Sites Professionnels</h4>
                <p>Votre présence en ligne à la hauteur de vos ambitions.</p>
                <ul class="spg-flist">
                    <li><i class="fas fa-check"></i>Site vitrine &amp; institutionnel</li>
                    <li><i class="fas fa-check"></i>E-commerce &amp; Marketplace</li>
                    <li><i class="fas fa-check"></i>Portail client &amp; B2B</li>
                    <li><i class="fas fa-check"></i>Landing pages optimisées</li>
                </ul>
            </div>
            <div class="spg-ent-card spg-ent-featured">
                <div class="spg-ent-badge">Le plus demandé</div>
                <div class="spg-ent-icon spg-ei-orange"><i class="fas fa-cogs"></i></div>
                <h4>Applications Métiers</h4>
                <p>Des logiciels qui automatisent et optimisent vos processus opérationnels.</p>
                <ul class="spg-flist">
                    <li><i class="fas fa-check"></i>ERP · CRM · Gestion RH</li>
                    <li><i class="fas fa-check"></i>Gestion scolaire &amp; académique</li>
                    <li><i class="fas fa-check"></i>Gestion immobilière &amp; financière</li>
                    <li><i class="fas fa-check"></i>Plateformes collaboratives</li>
                </ul>
            </div>
            <div class="spg-ent-card">
                <div class="spg-ent-icon spg-ei-purple"><i class="fas fa-mobile-alt"></i></div>
                <h4>Applications Mobiles</h4>
                <p>Android &amp; iOS — touchez vos utilisateurs où qu'ils soient.</p>
                <ul class="spg-flist">
                    <li><i class="fas fa-check"></i>Applications Fintech</li>
                    <li><i class="fas fa-check"></i>Apps éducatives &amp; santé</li>
                    <li><i class="fas fa-check"></i>Apps commerciales</li>
                    <li><i class="fas fa-check"></i>Flutter · React Native</li>
                </ul>
            </div>
        </div>

        <div class="spg-tech spg-reveal">
            <span class="spg-tech-chip">Laravel</span>
            <span class="spg-tech-chip">React</span>
            <span class="spg-tech-chip">Flutter</span>
            <span class="spg-tech-chip">Node.js</span>
            <span class="spg-tech-chip">Vue.js</span>
            <span class="spg-tech-chip">AWS</span>
            <span class="spg-tech-chip">MySQL</span>
            <span class="spg-tech-chip">PostgreSQL</span>
            <span class="spg-tech-chip">Docker</span>
        </div>

        <div class="spg-cta-row spg-reveal">
            <button type="button" class="spg-btn-orange" onclick="spgOpenModal('Enterprise — Développement de solutions')"><i class="fas fa-rocket me-2"></i>Parler de mon projet</button>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     03 · CYBERSECURITY
══════════════════════════════════════════════ --}}
<section id="cyber" class="spg-section">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-red"><i class="fas fa-shield-alt me-2"></i>Service 03</div>
            <h2 class="spg-sec-title">AfriCode <span class="spg-hl-red">CyberSecurity</span></h2>
            <p class="spg-sec-sub">La cybersécurité est devenue un enjeu stratégique pour les entreprises africaines. Nos experts vous accompagnent de l'audit à la protection continue.</p>
        </div>

        <div class="spg-cyber-grid spg-reveal">
            <div class="spg-cyber-card">
                <div class="spg-cyber-num">01</div>
                <div class="spg-cyber-head">
                    <i class="fas fa-search"></i>
                    <h4>Audit de Sécurité</h4>
                </div>
                <p>Analyse complète de vos systèmes — sites, applications, réseaux, serveurs, bases de données.</p>
                <div class="spg-cyber-items">
                    <span>Rapport de vulnérabilités</span>
                    <span>Plan de correction</span>
                    <span>Recommandations priorisées</span>
                </div>
            </div>
            <div class="spg-cyber-card">
                <div class="spg-cyber-num">02</div>
                <div class="spg-cyber-head">
                    <i class="fas fa-lock"></i>
                    <h4>Sécurité Applicative</h4>
                </div>
                <p>Protection de vos applications web, APIs, données utilisateurs et transactions numériques.</p>
                <div class="spg-cyber-items">
                    <span>Tests d'intrusion</span>
                    <span>Correction des failles</span>
                    <span>Monitoring continu</span>
                </div>
            </div>
            <div class="spg-cyber-card">
                <div class="spg-cyber-num">03</div>
                <div class="spg-cyber-head">
                    <i class="fas fa-cloud"></i>
                    <h4>Sécurité Cloud</h4>
                </div>
                <p>Configuration sécurisée et surveillance de vos environnements AWS, Azure, Google Cloud.</p>
                <div class="spg-cyber-items">
                    <span>Config sécurisée</span>
                    <span>Surveillance 24/7</span>
                    <span>Optimisation coûts</span>
                </div>
            </div>
            <div class="spg-cyber-card">
                <div class="spg-cyber-num">04</div>
                <div class="spg-cyber-head">
                    <i class="fas fa-users"></i>
                    <h4>Formation Équipes</h4>
                </div>
                <p>Former vos collaborateurs à la cybersécurité — sensibilisation, protection des données, réponse aux incidents.</p>
                <div class="spg-cyber-items">
                    <span>Ateliers pratiques</span>
                    <span>Certifications</span>
                    <span>Plans de réponse</span>
                </div>
            </div>
        </div>

        <div class="spg-cta-row spg-reveal">
            <button type="button" class="spg-btn-red" onclick="spgOpenModal('CyberSecurity — Audit de sécurité')"><i class="fas fa-shield-alt me-2"></i>Demander un audit de sécurité</button>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     04 · DIGITAL TRANSFORMATION
══════════════════════════════════════════════ --}}
<section id="digital" class="spg-section spg-section-alt">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-blue"><i class="fas fa-sync-alt me-2"></i>Service 04</div>
            <h2 class="spg-sec-title">Digital <span class="spg-hl-blue">Transformation</span></h2>
            <p class="spg-sec-sub">Nous aidons les organisations à passer du papier au numérique — et les entreprises numériques à franchir le prochain palier de croissance.</p>
        </div>

        <div class="spg-dt-grid spg-reveal">
            <div class="spg-dt-card">
                <div class="spg-dt-icon"><i class="fas fa-file-alt"></i></div>
                <h4>Digitalisation Administrative</h4>
                <ul class="spg-flist">
                    <li><i class="fas fa-check"></i>Gestion électronique des documents</li>
                    <li><i class="fas fa-check"></i>Signature électronique</li>
                    <li><i class="fas fa-check"></i>Archivage numérique sécurisé</li>
                    <li><i class="fas fa-check"></i>Workflow automatique</li>
                </ul>
            </div>
            <div class="spg-dt-card">
                <div class="spg-dt-icon"><i class="fas fa-robot"></i></div>
                <h4>Automatisation des Processus</h4>
                <p>Éliminez les tâches répétitives grâce aux logiciels métiers, à l'IA et aux automatisations intelligentes.</p>
                <div class="spg-dt-examples">
                    <div><i class="fas fa-arrow-right"></i>Validation automatique de documents</div>
                    <div><i class="fas fa-arrow-right"></i>Gestion des demandes clients</div>
                    <div><i class="fas fa-arrow-right"></i>Reporting automatique</div>
                    <div><i class="fas fa-arrow-right"></i>Facturation et paiements</div>
                </div>
            </div>
            <div class="spg-dt-card">
                <div class="spg-dt-icon"><i class="fas fa-chart-bar"></i></div>
                <h4>Stratégie Digitale</h4>
                <p>De l'audit à l'exécution — nous vous accompagnons dans chaque étape de votre transformation.</p>
                <ul class="spg-flist">
                    <li><i class="fas fa-check"></i>Audit de maturité numérique</li>
                    <li><i class="fas fa-check"></i>Feuille de route digitale</li>
                    <li><i class="fas fa-check"></i>Pilotage du changement</li>
                </ul>
            </div>
        </div>

        <div class="spg-cta-row spg-reveal">
            <button type="button" class="spg-btn-blue" onclick="spgOpenModal('Transformation digitale')"><i class="fas fa-sync-alt me-2"></i>Lancer ma transformation digitale</button>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     05 · SAAS
══════════════════════════════════════════════ --}}
<section id="saas" class="spg-section">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-green"><i class="fas fa-cloud me-2"></i>Service 05</div>
            <h2 class="spg-sec-title">AfriCode <span class="spg-hl">SaaS</span></h2>
            <p class="spg-sec-sub">Vos logiciels métiers disponibles en abonnement. AfriCode développe des plateformes SaaS adaptées aux réalités africaines — pour écoles, entreprises et centres de formation.</p>
        </div>

        <div class="spg-saas-grid spg-reveal">
            <div class="spg-saas-card">
                <div class="spg-saas-head spg-ssh-green">
                    <i class="fas fa-university"></i>
                    <div><h4>AfriCode Campus</h4><span>Écoles &amp; Universités</span></div>
                </div>
                <div class="spg-saas-body">
                    <p>Une plateforme complète de gestion académique et de formation en ligne.</p>
                    <ul class="spg-flist">
                        <li><i class="fas fa-check"></i>Gestion des étudiants</li>
                        <li><i class="fas fa-check"></i>Cours en ligne &amp; examens</li>
                        <li><i class="fas fa-check"></i>Paiements &amp; Certifications</li>
                        <li><i class="fas fa-check"></i>Bibliothèque numérique</li>
                        <li><i class="fas fa-check"></i>Reporting &amp; Analytics</li>
                    </ul>
                    <div class="spg-saas-contact">
                        <i class="fas fa-envelope me-2"></i>Tarification sur mesure — contactez-nous
                    </div>
                </div>
            </div>

            <div class="spg-saas-card">
                <div class="spg-saas-head spg-ssh-orange">
                    <i class="fas fa-building"></i>
                    <div><h4>AfriCode Business Suite</h4><span>Entreprises &amp; Startups</span></div>
                </div>
                <div class="spg-saas-body">
                    <p>Une suite complète pour gérer, former et faire croître votre organisation.</p>
                    <ul class="spg-flist">
                        <li><i class="fas fa-check"></i>Gestion RH &amp; paie</li>
                        <li><i class="fas fa-check"></i>Formation interne des équipes</li>
                        <li><i class="fas fa-check"></i>Gestion de projets Agile</li>
                        <li><i class="fas fa-check"></i>CRM &amp; pipeline commercial</li>
                        <li><i class="fas fa-check"></i>Collaboration d'équipe</li>
                    </ul>
                    <div class="spg-saas-contact">
                        <i class="fas fa-envelope me-2"></i>Tarification sur mesure — contactez-nous
                    </div>
                </div>
            </div>

            <div class="spg-saas-card">
                <div class="spg-saas-head spg-ssh-purple">
                    <i class="fas fa-layer-group"></i>
                    <div><h4>LMS White Label</h4><span>Centres de formation</span></div>
                </div>
                <div class="spg-saas-body">
                    <p>Votre propre plateforme de formation sous votre marque, hébergée sur l'infrastructure AfriCode.</p>
                    <div class="spg-wl-domain"><i class="fas fa-link me-2"></i><code>ecole.africode.tech</code></div>
                    <ul class="spg-flist">
                        <li><i class="fas fa-check"></i>Logo &amp; couleurs personnalisés</li>
                        <li><i class="fas fa-check"></i>Vos formations exclusives</li>
                        <li><i class="fas fa-check"></i>Gestion de vos étudiants</li>
                        <li><i class="fas fa-check"></i>Paiements intégrés</li>
                    </ul>
                    <div class="spg-saas-contact">
                        <i class="fas fa-phone me-2"></i>Demandez une démo gratuite
                    </div>
                </div>
            </div>
        </div>

        <div class="spg-cta-row spg-reveal">
            <button type="button" class="spg-btn-green" onclick="spgOpenModal('SaaS — Demande de démonstration')"><i class="fas fa-cloud me-2"></i>Demander une démonstration</button>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     06 · TALENT
══════════════════════════════════════════════ --}}
<section id="talent" class="spg-section spg-section-alt">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-orange"><i class="fas fa-users me-2"></i>Service 06</div>
            <h2 class="spg-sec-title">AfriCode <span class="spg-hl-or">Talent</span></h2>
            <p class="spg-sec-sub">Le pont entre les entreprises et les talents numériques africains. Nous formons, certifions et plaçons les meilleurs profils tech du continent.</p>
        </div>

        <div class="spg-bridge spg-reveal">
            <div class="spg-bridge-side">
                <div class="spg-bridge-icon"><i class="fas fa-building"></i></div>
                <h4>Entreprises</h4>
                <p>Recrutez des talents tech vérifiés et opérationnels</p>
            </div>
            <div class="spg-bridge-center">
                <div class="spg-bridge-logo"><i class="fas fa-layer-group"></i></div>
                <strong>AfriCode</strong>
                <div class="spg-bridge-arrows">
                    <i class="fas fa-arrow-left"></i>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
            <div class="spg-bridge-side">
                <div class="spg-bridge-icon"><i class="fas fa-user-graduate"></i></div>
                <h4>Talents Africains</h4>
                <p>Formés, certifiés, prêts à l'emploi</p>
            </div>
        </div>

        <div class="spg-talent-grid spg-reveal">
            <div class="spg-talent-card">
                <h4><i class="fas fa-search me-2"></i>Recrutement Tech Direct</h4>
                <p>Nous fournissons des profils qualifiés, évalués et prêts à intégrer votre équipe.</p>
                <div class="spg-talent-profiles">
                    <span><i class="fas fa-code me-1"></i>Développeurs</span>
                    <span><i class="fas fa-brain me-1"></i>Data Scientists</span>
                    <span><i class="fas fa-shield-alt me-1"></i>Experts Cyber</span>
                    <span><i class="fas fa-cloud me-1"></i>Cloud Engineers</span>
                    <span><i class="fas fa-project-diagram me-1"></i>Chefs de projet</span>
                </div>
            </div>
            <div class="spg-talent-card spg-talent-main">
                <h4><i class="fas fa-route me-2"></i>Former → Certifier → Recruter</h4>
                <p>Votre entreprise finance une promotion. AfriCode gère tout le reste.</p>
                <div class="spg-funnel">
                    <div class="spg-funnel-step"><span>1</span><div>Sélection des candidats</div></div>
                    <div class="spg-funnel-arrow"><i class="fas fa-arrow-down"></i></div>
                    <div class="spg-funnel-step"><span>2</span><div>Formation intensive AfriCode</div></div>
                    <div class="spg-funnel-arrow"><i class="fas fa-arrow-down"></i></div>
                    <div class="spg-funnel-step"><span>3</span><div>Certification officielle</div></div>
                    <div class="spg-funnel-arrow"><i class="fas fa-arrow-down"></i></div>
                    <div class="spg-funnel-step spg-funnel-end"><span>4</span><div>Placement dans votre équipe</div></div>
                </div>
            </div>
        </div>

        <div class="spg-cta-row spg-reveal">
            <button type="button" class="spg-btn-orange" onclick="spgOpenModal('Talent — Recrutement Tech')"><i class="fas fa-users me-2"></i>Recruter des talents AfriCode</button>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     07 · CONSULTING
══════════════════════════════════════════════ --}}
<section id="consulting" class="spg-section">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-blue"><i class="fas fa-chart-line me-2"></i>Service 07</div>
            <h2 class="spg-sec-title">AfriCode <span class="spg-hl-blue">Consulting</span></h2>
            <p class="spg-sec-sub">Conseil stratégique numérique pour gouvernements, grandes entreprises et organisations internationales. Nous alignons votre vision avec les meilleures pratiques tech africaines.</p>
        </div>

        <div class="spg-cons-grid spg-reveal">
            <div class="spg-cons-card"><i class="fas fa-microscope"></i><h4>Audit Numérique</h4><p>Diagnostic complet de votre maturité digitale — systèmes, processus, équipes, sécurité.</p></div>
            <div class="spg-cons-card"><i class="fas fa-map"></i><h4>Stratégie Digitale</h4><p>Feuille de route à 3-5 ans pour positionner votre organisation dans l'économie numérique africaine.</p></div>
            <div class="spg-cons-card"><i class="fas fa-sitemap"></i><h4>Architecture IT</h4><p>Conception de systèmes d'information robustes, scalables et sécurisés pour un contexte africain.</p></div>
            <div class="spg-cons-card"><i class="fas fa-balance-scale"></i><h4>Choix Technologiques</h4><p>Accompagnement dans la sélection et l'évaluation des technologies les mieux adaptées à vos besoins.</p></div>
            <div class="spg-cons-card"><i class="fas fa-tasks"></i><h4>Gestion de Projets</h4><p>Pilotage de vos projets numériques complexes — PMO, Agile, transformation organisationnelle.</p></div>
            <div class="spg-cons-card"><i class="fas fa-handshake"></i><h4>Partenariats Stratégiques</h4><p>Mise en relation avec les acteurs clés de l'écosystème tech africain et international.</p></div>
        </div>

        <div class="spg-cons-targets spg-reveal">
            <div class="spg-ct"><i class="fas fa-landmark"></i><span>Gouvernements</span></div>
            <div class="spg-ct"><i class="fas fa-building"></i><span>Grandes entreprises</span></div>
            <div class="spg-ct"><i class="fas fa-globe"></i><span>ONG Internationales</span></div>
            <div class="spg-ct"><i class="fas fa-university"></i><span>Universités</span></div>
        </div>

        <div class="spg-cta-row spg-reveal">
            <button type="button" class="spg-btn-blue" onclick="spgOpenModal('Consulting — Stratégie')"><i class="fas fa-chart-line me-2"></i>Discuter de votre stratégie</button>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     NOS CLIENTS CIBLES
══════════════════════════════════════════════ --}}
<section class="spg-section spg-section-alt">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-green"><i class="fas fa-handshake me-2"></i>Clients</div>
            <h2 class="spg-sec-title">Qui nous <span class="spg-hl">accompagnons</span></h2>
            <p class="spg-sec-sub">Entreprises, institutions éducatives, gouvernements, ONG — AfriCode adapte chaque solution aux besoins spécifiques de chaque organisation.</p>
        </div>

        <div class="spg-clients-grid spg-reveal">
            <div class="spg-client-card">
                <div class="spg-client-icon spg-ci-green"><i class="fas fa-building"></i></div>
                <h4>Entreprises &amp; Startups</h4>
                <ul class="spg-client-list">
                    <li><i class="fas fa-check-circle"></i>Digitaliser leurs activités</li>
                    <li><i class="fas fa-check-circle"></i>Former leurs équipes tech</li>
                    <li><i class="fas fa-check-circle"></i>Recruter des talents numériques</li>
                    <li><i class="fas fa-check-circle"></i>Créer leurs logiciels métiers</li>
                    <li><i class="fas fa-check-circle"></i>Sécuriser leurs systèmes</li>
                </ul>
            </div>
            <div class="spg-client-card spg-client-featured">
                <div class="spg-client-icon spg-ci-orange"><i class="fas fa-university"></i></div>
                <h4>Écoles &amp; Universités</h4>
                <ul class="spg-client-list">
                    <li><i class="fas fa-check-circle"></i>Plateformes LMS complètes</li>
                    <li><i class="fas fa-check-circle"></i>Formations numériques certifiantes</li>
                    <li><i class="fas fa-check-circle"></i>Infrastructures digitales</li>
                    <li><i class="fas fa-check-circle"></i>Gestion académique intégrée</li>
                    <li><i class="fas fa-check-circle"></i>Certifications reconnues</li>
                </ul>
                <div class="spg-pilot-badge"><i class="fas fa-star me-1"></i>Programme Pilote Gratuit — 3 mois</div>
            </div>
            <div class="spg-client-card">
                <div class="spg-client-icon spg-ci-blue"><i class="fas fa-landmark"></i></div>
                <h4>Gouvernements &amp; ONG</h4>
                <ul class="spg-client-list">
                    <li><i class="fas fa-check-circle"></i>Administration numérique</li>
                    <li><i class="fas fa-check-circle"></i>Cybersécurité publique</li>
                    <li><i class="fas fa-check-circle"></i>Formation des agents publics</li>
                    <li><i class="fas fa-check-circle"></i>Plateformes citoyennes</li>
                    <li><i class="fas fa-check-circle"></i>Transformation des services</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     MODÈLE ÉCONOMIQUE
══════════════════════════════════════════════ --}}
<section class="spg-section">
    <div class="container">
        <div class="spg-sec-head spg-reveal">
            <div class="spg-tag spg-tag-orange"><i class="fas fa-coins me-2"></i>Modèle économique</div>
            <h2 class="spg-sec-title">4 sources de revenus <span class="spg-hl-or">complémentaires</span></h2>
            <p class="spg-sec-sub">Un écosystème pérenne conçu pour croître avec l'Afrique numérique.</p>
        </div>

        <div class="spg-bm-grid spg-reveal">
            <div class="spg-bm-card">
                <div class="spg-bm-num">1</div>
                <h4>Formation B2C</h4>
                <p>Apprenants individuels — achat de cours et abonnements premium.</p>
                <div class="spg-bm-tags"><span>Cours à l'unité</span><span>Premium mensuel</span></div>
            </div>
            <div class="spg-bm-card">
                <div class="spg-bm-num">2</div>
                <h4>Marketplace Formateurs</h4>
                <p>Commission partagée sur les ventes — les formateurs perçoivent la part majoritaire des revenus générés par leurs cours.</p>
                <div class="spg-bm-tags"><span>Partage équitable</span><span>Transparence totale</span></div>
            </div>
            <div class="spg-bm-card">
                <div class="spg-bm-num">3</div>
                <h4>SaaS B2B</h4>
                <p>Abonnements récurrents pour entreprises, écoles et institutions africaines.</p>
                <div class="spg-bm-tags"><span>Abonnement mensuel</span><span>Récurrent</span></div>
            </div>
            <div class="spg-bm-card">
                <div class="spg-bm-num">4</div>
                <h4>Services Tech</h4>
                <p>Développement, transformation digitale, conseil stratégique et recrutement.</p>
                <div class="spg-bm-tags"><span>Sur devis</span><span>Par projet</span></div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════════
     PROGRAMME EXPERT FORMATEUR
══════════════════════════════════════════════ --}}
<section class="spg-section spg-section-alt">
    <div class="container">
        <div class="spg-expert-wrap spg-reveal">
            <div class="spg-expert-left">
                <div class="spg-tag spg-tag-orange"><i class="fas fa-chalkboard-teacher me-2"></i>Formateurs</div>
                <h2 class="spg-sec-title">Programme <span class="spg-hl-or">AfriCode Expert</span></h2>
                <p class="mb-4">Devenez formateur AfriCode et touchez des milliers d'apprenants africains. Monétisez votre expertise, construisez votre audience, laissez votre empreinte numérique.</p>
                <div class="spg-perks">
                    <div class="spg-perk"><i class="fas fa-coins"></i><div><strong>Revenus attractifs</strong><span>Les meilleurs taux du marché africain</span></div></div>
                    <div class="spg-perk"><i class="fas fa-id-badge"></i><div><strong>Badge Expert AfriCode</strong><span>Crédibilité et visibilité renforcées</span></div></div>
                    <div class="spg-perk"><i class="fas fa-bullhorn"></i><div><strong>Promotion marketing</strong><span>Mise en avant sur tous nos canaux</span></div></div>
                    <div class="spg-perk"><i class="fas fa-chart-bar"></i><div><strong>Statistiques détaillées</strong><span>Pilotez votre activité en temps réel</span></div></div>
                    <div class="spg-perk"><i class="fas fa-hands-helping"></i><div><strong>Accompagnement complet</strong><span>Création de cours, mise en ligne, support</span></div></div>
                </div>
                <a href="{{ route('formateur.courses.index') }}" class="spg-btn-orange mt-3">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Devenir formateur AfriCode
                </a>
            </div>
            <div class="spg-expert-right" aria-hidden="true">
                <div class="spg-expert-preview">
                    <div class="spg-ep-header">
                        <div class="spg-ep-avatar"><i class="fas fa-user-tie"></i></div>
                        <div>
                            <strong>Expert AfriCode</strong>
                            <span>Formateur Certifié</span>
                        </div>
                        <div class="spg-ep-verified"><i class="fas fa-check-circle me-1"></i>Vérifié</div>
                    </div>
                    <div class="spg-ep-rows">
                        <div class="spg-ep-row"><span>Cours publiés</span><strong>5</strong></div>
                        <div class="spg-ep-row"><span>Apprenants</span><strong>450</strong></div>
                        <div class="spg-ep-row"><span>Note moyenne</span><strong>4.9 / 5</strong></div>
                        <div class="spg-ep-row spg-ep-highlight"><span>Impact</span><strong>Croissant</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     PROGRAMME CAMPUS PARTNER
══════════════════════════════════════════════ --}}
<section class="spg-section">
    <div class="container">
        <div class="spg-campus-wrap spg-reveal">
            <div class="spg-campus-left" aria-hidden="true">
                <div class="spg-campus-card">
                    <div class="spg-campus-logo"><i class="fas fa-university"></i></div>
                    <div class="spg-campus-domain"><i class="fas fa-link me-2"></i><code>votreecole.africode.tech</code></div>
                    <div class="spg-campus-metrics">
                        <div><strong>100</strong><span>Étudiants inclus</span></div>
                        <div><strong>3</strong><span>Mois gratuits</span></div>
                        <div><strong><i class="fas fa-gift"></i></strong><span>Accès gratuit</span></div>
                    </div>
                </div>
            </div>
            <div class="spg-campus-right">
                <div class="spg-tag spg-tag-blue"><i class="fas fa-university me-2"></i>Institutions</div>
                <h2 class="spg-sec-title">Programme <span class="spg-hl-blue">AfriCode Partner Campus</span></h2>
                <p class="mb-4">Offre pilote gratuite pendant 3 mois pour les universités, écoles et centres de formation. Testez la plateforme, mesurez l'impact, décidez ensuite.</p>
                <div class="spg-campus-offers">
                    <div class="spg-co"><i class="fas fa-check-circle"></i><span>Plateforme LMS complète incluse</span></div>
                    <div class="spg-co"><i class="fas fa-check-circle"></i><span>100 étudiants pris en charge</span></div>
                    <div class="spg-co"><i class="fas fa-check-circle"></i><span>Formation numérique pour les enseignants</span></div>
                    <div class="spg-co"><i class="fas fa-check-circle"></i><span>Support dédié pendant tout le pilote</span></div>
                    <div class="spg-co"><i class="fas fa-check-circle"></i><span>Sous-domaine personnalisé à votre nom</span></div>
                </div>
                <div class="spg-campus-targets">
                    <span><i class="fas fa-graduation-cap me-1"></i>Universités privées</span>
                    <span><i class="fas fa-laptop me-1"></i>Écoles informatiques</span>
                    <span><i class="fas fa-certificate me-1"></i>Centres professionnels</span>
                    <span><i class="fas fa-hands-helping me-1"></i>ONG de formation</span>
                </div>
                <button type="button" class="spg-btn-blue mt-3" onclick="spgOpenModal('Campus Partner — Programme pilote')">
                    <i class="fas fa-university me-2"></i>Rejoindre le programme pilote
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════
     CTA FINAL
══════════════════════════════════════════════ --}}
<section class="spg-final">
    <div class="spg-final-bg" id="spgFinalBg"></div>
    <div class="container">
        <div class="spg-final-inner spg-reveal">
            <h2 class="spg-final-title">Prêt à transformer votre organisation ?</h2>
            <p class="spg-final-sub">Quelle que soit votre ambition — formation, développement, cybersécurité ou transformation digitale — AfriCode a la solution adaptée à votre contexte africain.</p>
            <div class="spg-final-ctas">
                <button type="button" class="spg-fcta-green" onclick="spgOpenModal('Renseignement général')">
                    <i class="fas fa-rocket me-2"></i>Parler à un expert AfriCode
                </button>
                <a href="{{ route('courses.index') }}" class="spg-fcta-outline">
                    <i class="fas fa-graduation-cap me-2"></i>Explorer les formations
                </a>
            </div>
            <div class="spg-final-trust">
                <span><i class="fas fa-map-marker-alt me-1"></i>Cotonou, Bénin</span>
                <span><i class="fas fa-globe-africa me-1"></i>Présence panafricaine</span>
                <span><i class="fas fa-lock me-1"></i>Données sécurisées</span>
                <span><i class="fas fa-certificate me-1"></i>Certifications reconnues</span>
            </div>
        </div>
    </div>
</section>

</div>{{-- .spg-body --}}
</div>{{-- .spg --}}

{{-- ══════════════════════════════════════════════
     MODAL FORMULAIRE DE CONTACT
══════════════════════════════════════════════ --}}
<div id="spgModal" class="spgm-overlay" role="dialog" aria-modal="true" aria-labelledby="spgModalTitle" hidden>
    <div class="spgm-box">
        <button class="spgm-close" onclick="spgCloseModal()" aria-label="Fermer">
            <i class="fas fa-times"></i>
        </button>
        <div class="spgm-header">
            <div class="spgm-icon"><i class="fas fa-paper-plane"></i></div>
            <h3 id="spgModalTitle">Parlez-nous de votre projet</h3>
            <p>Notre équipe vous répond dans les 24 heures.</p>
        </div>
        <form id="spgContactForm" class="spgm-form" novalidate>
            @csrf
            <div class="spgm-row">
                <div class="spgm-field">
                    <label for="spg_firstName">Prénom <span>*</span></label>
                    <input type="text" id="spg_firstName" name="firstName" placeholder="Votre prénom" required>
                    <span class="spgm-err" id="err_firstName"></span>
                </div>
                <div class="spgm-field">
                    <label for="spg_lastName">Nom <span>*</span></label>
                    <input type="text" id="spg_lastName" name="lastName" placeholder="Votre nom" required>
                    <span class="spgm-err" id="err_lastName"></span>
                </div>
            </div>
            <div class="spgm-field">
                <label for="spg_email">Adresse e-mail <span>*</span></label>
                <input type="email" id="spg_email" name="email" placeholder="vous@exemple.com" required>
                <span class="spgm-err" id="err_email"></span>
            </div>
            <div class="spgm-field">
                <label for="spg_subject">Service concerné <span>*</span></label>
                <select id="spg_subject" name="_subject_select" required>
                    <option value="">— Choisissez un service —</option>
                    <option value="Renseignement général">Renseignement général</option>
                    <option value="Academy — Formation professionnelle">Academy — Formation professionnelle</option>
                    <option value="Enterprise — Développement de solutions">Enterprise — Développement de solutions</option>
                    <option value="CyberSecurity — Audit de sécurité">CyberSecurity — Audit de sécurité</option>
                    <option value="Transformation digitale">Transformation digitale</option>
                    <option value="SaaS — Demande de démonstration">SaaS — Demande de démonstration</option>
                    <option value="Talent — Recrutement Tech">Talent — Recrutement Tech</option>
                    <option value="Consulting — Stratégie">Consulting — Stratégie</option>
                    <option value="Campus Partner — Programme pilote">Campus Partner — Programme pilote</option>
                    <option value="__autre__">Autre — préciser ci-dessous</option>
                </select>
                <span class="spgm-err" id="err_subject"></span>
            </div>
            <div class="spgm-field" id="spgm-autre-wrap" style="display:none;margin-top:-6px;">
                <label for="spg_subject_autre">Précisez votre demande <span>*</span></label>
                <input type="text" id="spg_subject_autre" name="subject" placeholder="Ex : Partenariat, Investissement, Presse…" autocomplete="off">
                <span class="spgm-err" id="err_subject_autre"></span>
            </div>
            <input type="hidden" id="spg_subject_hidden" name="subject">
            <div class="spgm-field">
                <label for="spg_message">Message <span>*</span></label>
                <textarea id="spg_message" name="message" rows="4" placeholder="Décrivez votre besoin, votre contexte, vos objectifs..." required></textarea>
                <span class="spgm-err" id="err_message"></span>
            </div>
            <label class="spgm-check">
                <input type="checkbox" name="newsletter" value="1">
                <span>Je souhaite recevoir les actualités AfriCode</span>
            </label>
            <div id="spgm-feedback" class="spgm-feedback" hidden></div>
            <button type="submit" class="spgm-submit" id="spgm-submit-btn">
                <span class="spgm-submit-text"><i class="fas fa-paper-plane me-2"></i>Envoyer le message</span>
                <span class="spgm-submit-loading" hidden><i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours…</span>
            </button>
        </form>
    </div>
</div>

@push('styles')
<style>
:root {
    --sg:  #27B371;   /* AfriCode green */
    --sg2: #1EA38B;   /* AfriCode teal */
    --sor: #FF8E2A;   /* AfriCode orange */
    --snv: #0d1a18;   /* hero dark teal-black */
    --snv2:#0a2a20;   /* secondary dark teal */
    --srd: #E32D31;   /* AfriCode red */
    --sbl: #1EA38B;   /* AfriCode teal for "blue" accents */
    --spu: #FF8E2A;   /* AfriCode orange for secondary accents */
}

/* ─── GLOBAL ─── */
.spg { font-family: 'Inter','Segoe UI',system-ui,sans-serif; color: #333; }
.spg-section     { padding: 52px 0; background: #fff; }
.spg-section-alt { padding: 52px 0; background: #F8F9FA; }
.spg-body { overflow-x: hidden; }

/* ─── HERO ─── */
.spg-hero {
    position: relative;
    background: var(--snv);
    padding: 100px 0 0;
    overflow: hidden;
}
.spg-hero-bg { position: absolute; inset: 0; pointer-events: none; }
.spg-orb {
    position: absolute; border-radius: 50%;
    filter: blur(70px); opacity: .18;
}
.spg-orb1 { width: 500px; height: 500px; background: var(--sg);  top: -120px; left: -100px; }
.spg-orb2 { width: 400px; height: 400px; background: var(--sor); top: 60px; right: -80px; opacity: .12; }
.spg-orb3 { width: 300px; height: 300px; background: var(--spu); bottom: 0; left: 40%; opacity: .1; }

.spg-hero-wrap {
    position: relative; z-index: 1;
    display: flex; align-items: center; gap: 40px;
    padding-bottom: 48px;
}
.spg-hero-left { flex: 1; min-width: 0; }
.spg-hero-right { flex: 0 0 380px; display: flex; justify-content: center; align-items: center; }

.spg-pill {
    display: inline-flex; align-items: center;
    background: rgba(17,192,109,.15); border: 1px solid rgba(17,192,109,.3);
    color: #6ee7d4; border-radius: 999px;
    padding: 6px 16px; font-size: 13px; font-weight: 600;
    margin-bottom: 28px;
}
.spg-pill-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--sg2); margin-right: 10px;
    animation: spgPulse 2s ease-in-out infinite;
}
@keyframes spgPulse { 0%,100%{opacity:1} 50%{opacity:.4} }

.spg-h1 {
    font-size: clamp(2.2rem, 4vw, 3.4rem);
    font-weight: 800; line-height: 1.1;
    color: #fff; margin: 0 0 24px;
}
.spg-h1-accent {
    background: linear-gradient(135deg, var(--sg), var(--sg2), var(--sor));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}

.spg-hero-lead { color: rgba(255,255,255,.75); font-size: 1.05rem; line-height: 1.7; margin-bottom: 28px; max-width: 540px; }
.spg-hero-lead strong { color: rgba(255,255,255,.95); }

.spg-checks { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 20px; margin-bottom: 36px; }
.spg-check { display: flex; align-items: center; gap: 8px; color: rgba(255,255,255,.85); font-size: .9rem; }
.spg-check i { color: var(--sg); font-size: 1rem; flex-shrink: 0; }

.spg-hero-ctas { display: flex; flex-wrap: wrap; gap: 12px; }
.spg-cta-green {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, var(--sg), var(--sg2));
    color: #fff; text-decoration: none; border: none;
    padding: 14px 28px; border-radius: 10px;
    font-weight: 700; font-size: .95rem;
    transition: transform .25s, box-shadow .25s;
    box-shadow: 0 8px 24px rgba(17,192,109,.35);
}
.spg-cta-green:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(17,192,109,.45); color: #fff; }
.spg-cta-outline {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.08); border: 1.5px solid rgba(255,255,255,.25);
    color: rgba(255,255,255,.9); text-decoration: none;
    padding: 13px 24px; border-radius: 10px;
    font-weight: 600; font-size: .95rem;
    transition: background .25s, border-color .25s;
}
.spg-cta-outline:hover { background: rgba(255,255,255,.15); border-color: rgba(255,255,255,.45); color: #fff; }

/* ─── HERO HUB VISUAL ─── */
.spg-hub {
    position: relative; width: 320px; height: 320px;
}
.spg-hub-ring {
    position: absolute; top: 50%; left: 50%;
    border-radius: 50%; border: 1.5px dashed rgba(17,192,109,.5);
    transform: translate(-50%,-50%);
    animation: spgRingPulse 4s ease-in-out infinite alternate;
}
.spg-hub-ring-outer { width: 300px; height: 300px; }
.spg-hub-ring-inner { width: 190px; height: 190px; border-color: rgba(254,168,55,.35); animation-delay: -2s; }
@keyframes spgRingPulse { to { opacity: .2; } }

.spg-hub-center {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    width: 90px; height: 90px;
    background: linear-gradient(135deg, var(--sg), var(--sg2));
    border-radius: 24px; z-index: 2;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 5px; box-shadow: 0 0 0 10px rgba(17,192,109,.2), 0 8px 24px rgba(17,192,109,.4);
}
.spg-hub-center i  { color: #fff; font-size: 1.6rem; }
.spg-hub-center span { color: rgba(255,255,255,.9); font-size: .68rem; font-weight: 800; letter-spacing: .06em; }

.spg-hub-node {
    position: absolute; width: 62px; height: 62px;
    background: rgba(255,255,255,.12); border: 1.5px solid rgba(17,192,109,.5);
    border-radius: 14px; backdrop-filter: blur(8px);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 4px; transition: transform .3s, border-color .3s, background .3s;
    animation: spgFloat 3s ease-in-out infinite;
    box-shadow: 0 4px 12px rgba(0,0,0,.2);
}
.spg-hub-node:hover { transform: scale(1.12) !important; border-color: var(--sg); background: rgba(17,192,109,.2); }
.spg-hub-node i    { color: var(--sg2); font-size: 1rem; }
.spg-hub-node span { color: rgba(255,255,255,.9); font-size: .58rem; font-weight: 700; }

/* 6 nodes at 60° increments, radius 130px from center (160px) */
.spg-hn1 { top: calc(50% - 130px - 31px); left: calc(50% - 31px); animation-delay: 0s; }
.spg-hn2 { top: calc(50% - 65px - 31px); left: calc(50% + 113px - 31px); animation-delay: -.5s; }
.spg-hn3 { top: calc(50% + 65px - 31px); left: calc(50% + 113px - 31px); animation-delay: -1s; }
.spg-hn4 { top: calc(50% + 130px - 31px); left: calc(50% - 31px); animation-delay: -1.5s; }
.spg-hn5 { top: calc(50% + 65px - 31px); left: calc(50% - 113px - 31px); animation-delay: -2s; }
.spg-hn6 { top: calc(50% - 65px - 31px); left: calc(50% - 113px - 31px); animation-delay: -2.5s; }

@keyframes spgFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }

/* ─── STATS BAR ─── */
.spg-stats-bar { background: rgba(0,0,0,.25); border-top: 1px solid rgba(255,255,255,.08); padding: 20px 0; position: relative; z-index: 1; }
.spg-stats { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 8px; }
.spg-stat { text-align: center; padding: 0 20px; }
.spg-stat strong { display: block; font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1; }
.spg-stat span   { font-size: .75rem; color: rgba(255,255,255,.55); margin-top: 3px; display: block; }
.spg-stat-sep { width: 1px; height: 32px; background: rgba(255,255,255,.15); }

.spg-hero-wave { position: relative; z-index: 1; line-height: 0; margin-top: -1px; }
.spg-hero-wave svg { display: block; width: 100%; height: 60px; }

/* ─── STICKY NAV ─── */
.spg-nav {
    position: sticky; top: 0; z-index: 900;
    background: rgba(255,255,255,.97);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid #e2e8f0;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
}
.spg-nav-inner {
    display: flex; align-items: center;
    overflow-x: auto; gap: 0;
    scrollbar-width: none;
}
.spg-nav-inner::-webkit-scrollbar { display: none; }
.spg-nav-item {
    display: flex; align-items: center; gap: 7px;
    padding: 16px 20px; white-space: nowrap;
    color: #64748b; text-decoration: none;
    font-weight: 600; font-size: .85rem;
    border-bottom: 3px solid transparent;
    transition: color .2s, border-color .2s;
}
.spg-nav-item:hover { color: var(--sg); }
.spg-nav-item.active { color: var(--sg); border-bottom-color: var(--sg); }
.spg-nav-item i { font-size: .9rem; }

/* ─── SECTION HEADS ─── */
.spg-sec-head { text-align: center; margin-bottom: 32px; max-width: 700px; margin-left: auto; margin-right: auto; }
.spg-tag {
    display: inline-flex; align-items: center;
    padding: 5px 14px; border-radius: 999px;
    font-size: .78rem; font-weight: 700; letter-spacing: .04em;
    margin-bottom: 16px;
}
.spg-tag-green  { background: rgba(17,192,109,.1);  color: var(--sg); }
.spg-tag-orange { background: rgba(254,168,55,.1);  color: var(--sor); }
.spg-tag-red    { background: rgba(239,68,68,.1);   color: var(--srd); }
.spg-tag-blue   { background: rgba(59,130,246,.1);  color: var(--sbl); }

.spg-sec-title { font-size: clamp(1.5rem, 2.5vw, 2rem); font-weight: 800; margin: 0 0 10px; color: #333; }
.spg-sec-sub   { font-size: .95rem; color: #6C757D; line-height: 1.7; margin: 0; }

.spg-hl     { color: var(--sg); }
.spg-hl-or  { color: var(--sor); }
.spg-hl-red { color: var(--srd); }
.spg-hl-blue{ color: var(--sg); }

/* ─── SCROLL REVEAL ─── */
.spg-reveal { opacity: 0; transform: translateY(28px); transition: opacity .6s ease, transform .6s ease; }
.spg-reveal.revealed { opacity: 1; transform: none; }

/* ─── BUTTONS ─── */
.spg-btn-green,.spg-btn-orange,.spg-btn-red,.spg-btn-blue,.spg-btn-ghost {
    display: inline-flex; align-items: center;
    padding: 13px 28px; border-radius: 10px;
    font-weight: 700; font-size: .95rem; text-decoration: none;
    transition: transform .2s, box-shadow .2s;
}
.spg-btn-green  { background: linear-gradient(135deg,var(--sg),var(--sg2)); color: #fff; box-shadow: 0 6px 20px rgba(17,192,109,.3); }
.spg-btn-orange { background: linear-gradient(135deg,var(--sor),#e86b00); color: #fff; box-shadow: 0 6px 20px rgba(254,168,55,.3); }
.spg-btn-red    { background: linear-gradient(135deg,var(--srd),#b91c1c); color: #fff; box-shadow: 0 6px 20px rgba(239,68,68,.3); }
.spg-btn-blue   { background: linear-gradient(135deg,var(--sbl),#0d7a63); color: #fff; box-shadow: 0 6px 20px rgba(30,163,139,.3); }
.spg-btn-ghost  { background: #f1f5f9; color: #334155; border: 1.5px solid #e2e8f0; box-shadow: none; }
.spg-btn-green:hover,.spg-btn-orange:hover,.spg-btn-red:hover,.spg-btn-blue:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,.2); color: #fff; }
.spg-btn-ghost:hover { background: #e2e8f0; color: #334155; }

.spg-cta-row { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 12px; margin-top: 24px; }

/* ─── FEAT LIST ─── */
.spg-flist { list-style: none; margin: 12px 0 0; padding: 0; }
.spg-flist li { display: flex; align-items: flex-start; gap: 9px; padding: 5px 0; font-size: .9rem; color: #475569; }
.spg-flist i { color: var(--sg); margin-top: 3px; flex-shrink: 0; }

/* ─── ACADEMY — DOMAINS ─── */
.spg-domains {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px; margin-bottom: 20px;
}
.spg-domain {
    padding: 18px 16px; border-radius: 12px;
    border: 1px solid #E9ECEF;
    background: #fff; transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-domain:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
.spg-dom-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 10px; font-size: 1rem;
}
.spg-dom-green  .spg-dom-icon { background: rgba(17,192,109,.1);  color: var(--sg); }
.spg-dom-purple .spg-dom-icon { background: rgba(254,168,55,.12); color: var(--sor); }
.spg-dom-blue   .spg-dom-icon { background: rgba(17,192,109,.08); color: var(--sg); }
.spg-dom-red    .spg-dom-icon { background: rgba(250,28,57,.1);   color: var(--srd); }
.spg-dom-orange .spg-dom-icon { background: rgba(254,168,55,.1);  color: var(--sor); }
.spg-domain h4 { font-size: .85rem; font-weight: 700; color: #333; margin: 0 0 8px; }
.spg-domain ul { list-style: none; margin: 0; padding: 0; }
.spg-domain li { font-size: .8rem; color: #6C757D; padding: 2px 0; }
.spg-domain li::before { content: "·"; margin-right: 5px; color: #adb5bd; }

/* ─── AUDIENCE ─── */
.spg-audience { background: #F8F9FA; border-radius: 12px; padding: 16px 20px; text-align: center; }
.spg-audience h5 { color: #333; font-weight: 700; margin-bottom: 12px; font-size: .9rem; }
.spg-audience-tags { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
.spg-audience-tags span {
    display: inline-flex; align-items: center;
    background: #fff; border: 1.5px solid #e2e8f0;
    color: #475569; font-size: .85rem; font-weight: 600;
    padding: 7px 14px; border-radius: 999px;
    transition: border-color .2s, color .2s;
}
.spg-audience-tags span:hover { border-color: var(--sg); color: var(--sg); }

/* ─── ENTERPRISE — CARDS ─── */
.spg-ent-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 20px; }
.spg-ent-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 22px 18px;
    position: relative; transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-ent-card:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
.spg-ent-featured { border-color: var(--sor); box-shadow: 0 4px 6px -1px rgba(254,168,55,.15); }
.spg-ent-badge {
    position: absolute; top: -12px; left: 50%; transform: translateX(-50%);
    background: linear-gradient(135deg,var(--sor),#e86b00);
    color: #fff; font-size: .73rem; font-weight: 700; letter-spacing: .04em;
    padding: 4px 14px; border-radius: 999px; white-space: nowrap;
}
.spg-ent-icon {
    width: 42px; height: 42px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; margin-bottom: 12px;
}
.spg-ei-green  { background: rgba(17,192,109,.1);  color: var(--sg); }
.spg-ei-orange { background: rgba(254,168,55,.1);  color: var(--sor); }
.spg-ei-purple { background: rgba(17,192,109,.08); color: var(--sg); }
.spg-ent-card h4 { font-size: .95rem; font-weight: 700; color: #333; margin: 0 0 6px; }
.spg-ent-card p  { font-size: .85rem; color: #6C757D; margin: 0 0 10px; }
.spg-price-tag {
    margin-top: 12px; padding: 8px 12px;
    background: #F8F9FA; border-radius: 8px;
    font-size: .82rem; color: #6C757D; border-left: 3px solid var(--sg);
}
.spg-price-tag strong { color: var(--sg); font-size: .9rem; }

.spg-tech { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; }
.spg-tech-chip {
    background: #f1f5f9; border: 1px solid #e2e8f0;
    color: #475569; font-size: .8rem; font-weight: 600;
    padding: 5px 12px; border-radius: 6px;
    transition: background .2s, color .2s;
}
.spg-tech-chip:hover { background: rgba(17,192,109,.08); color: var(--sg); border-color: rgba(17,192,109,.2); }

/* ─── CYBER ─── */
.spg-cyber-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; margin-bottom: 20px; }
.spg-cyber-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 20px;
    position: relative; overflow: hidden;
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-cyber-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); border-color: rgba(250,28,57,.2); }
.spg-cyber-num {
    position: absolute; top: 16px; right: 16px;
    font-size: 2.2rem; font-weight: 900; color: #F8F9FA; line-height: 1;
}
.spg-cyber-head { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.spg-cyber-head i { color: var(--srd); font-size: 1rem; }
.spg-cyber-head h4 { font-size: .9rem; font-weight: 700; color: #333; margin: 0; }
.spg-cyber-card p { font-size: .85rem; color: #6C757D; line-height: 1.6; margin-bottom: 10px; }
.spg-cyber-items { display: flex; flex-wrap: wrap; gap: 6px; }
.spg-cyber-items span {
    background: rgba(250,28,57,.06); border: 1px solid rgba(250,28,57,.15);
    color: var(--srd); font-size: .78rem; font-weight: 600;
    padding: 3px 9px; border-radius: 6px;
}

/* ─── DIGITAL TRANSFORMATION ─── */
.spg-dt-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 20px; }
.spg-dt-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 20px;
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-dt-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
.spg-dt-icon {
    width: 44px; height: 44px; background: rgba(17,192,109,.08);
    color: var(--sg); border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; margin-bottom: 12px;
}
.spg-dt-card h4 { font-size: .9rem; font-weight: 700; color: #333; margin-bottom: 8px; }
.spg-dt-card p  { font-size: .85rem; color: #6C757D; line-height: 1.6; margin-bottom: 10px; }
.spg-dt-examples > div {
    display: flex; align-items: center; gap: 8px;
    font-size: .83rem; color: #555; padding: 3px 0;
}
.spg-dt-examples i { color: var(--sg); font-size: .78rem; flex-shrink: 0; }

/* ─── SAAS ─── */
.spg-saas-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 20px; }
.spg-saas-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; overflow: hidden;
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-saas-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
.spg-saas-head {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 20px; color: #fff;
}
.spg-ssh-green  { background: linear-gradient(135deg,var(--sg),var(--sg2)); }
.spg-ssh-orange { background: linear-gradient(135deg,var(--sor),#e86b00); }
.spg-ssh-purple { background: linear-gradient(135deg,var(--sor),#b45309); }
.spg-saas-head i    { font-size: 1.3rem; opacity: .9; }
.spg-saas-head h4   { font-size: .9rem; font-weight: 700; margin: 0 0 2px; }
.spg-saas-head span { font-size: .75rem; opacity: .8; }
.spg-saas-body { padding: 16px 20px; }
.spg-saas-body p { font-size: .85rem; color: #6C757D; margin-bottom: 10px; }
.spg-wl-domain {
    background: #1a1a2e; color: #94a3b8; font-size: .8rem;
    padding: 8px 12px; border-radius: 8px; margin-bottom: 12px;
}
.spg-wl-domain code { color: var(--sg2); font-size: .8rem; }
.spg-saas-contact {
    margin-top: 12px; font-size: .83rem; color: #6C757D;
    background: #F8F9FA; padding: 9px 12px; border-radius: 8px;
    border: 1px dashed #DEE2E6;
}

/* ─── TALENT ─── */
.spg-bridge {
    display: flex; align-items: center; justify-content: center;
    gap: 16px; margin-bottom: 20px;
    padding: 24px; background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF;
}
.spg-bridge-side { text-align: center; flex: 1; }
.spg-bridge-icon {
    width: 56px; height: 56px; margin: 0 auto 10px;
    background: rgba(17,192,109,.08); color: var(--sg);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
}
.spg-bridge-side h4 { font-size: .9rem; font-weight: 700; color: #333; margin: 0 0 4px; }
.spg-bridge-side p  { font-size: .8rem; color: #6C757D; margin: 0; }
.spg-bridge-center { text-align: center; }
.spg-bridge-logo {
    width: 64px; height: 64px; margin: 0 auto 8px;
    background: linear-gradient(135deg,var(--sg),var(--sg2));
    border-radius: 16px; display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.4rem; box-shadow: 0 6px 20px rgba(17,192,109,.25);
}
.spg-bridge-center strong { display: block; font-size: .85rem; font-weight: 700; color: #333; margin-bottom: 10px; }
.spg-bridge-arrows { display: flex; gap: 8px; justify-content: center; color: var(--sg); font-size: 1rem; }

.spg-talent-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; margin-bottom: 20px; }
.spg-talent-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 20px;
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-talent-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
.spg-talent-main { border-color: var(--sg); }
.spg-talent-card h4 { font-size: .9rem; font-weight: 700; color: #333; margin: 0 0 8px; }
.spg-talent-card p  { font-size: .85rem; color: #6C757D; margin-bottom: 12px; }
.spg-talent-profiles { display: flex; flex-wrap: wrap; gap: 6px; }
.spg-talent-profiles span {
    background: #F8F9FA; border: 1px solid #E9ECEF;
    color: #555; font-size: .8rem; font-weight: 600;
    padding: 4px 10px; border-radius: 6px;
}
.spg-funnel { display: flex; flex-direction: column; align-items: center; gap: 0; }
.spg-funnel-step {
    display: flex; align-items: center; gap: 10px;
    background: #F8F9FA; border-radius: 8px; padding: 9px 14px;
    width: 100%; border: 1px solid #E9ECEF;
}
.spg-funnel-step span {
    width: 22px; height: 22px; border-radius: 50%;
    background: var(--sg); color: #fff;
    font-size: .72rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.spg-funnel-step div { font-size: .83rem; color: #555; font-weight: 600; }
.spg-funnel-end { background: rgba(17,192,109,.06); border-color: rgba(17,192,109,.2); }
.spg-funnel-end div { color: var(--sg); }
.spg-funnel-arrow { color: #CED4DA; font-size: .82rem; padding: 3px 0; }

/* ─── CONSULTING ─── */
.spg-cons-grid {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 14px; margin-bottom: 16px;
}
.spg-cons-card {
    background: #fff; border-radius: 12px; padding: 20px;
    border: 1px solid #E9ECEF; text-align: center;
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-cons-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); border-color: rgba(17,192,109,.2); }
.spg-cons-card i  { color: var(--sg); font-size: 1.4rem; margin-bottom: 10px; display: block; }
.spg-cons-card h4 { font-size: .88rem; font-weight: 700; color: #333; margin: 0 0 6px; }
.spg-cons-card p  { font-size: .82rem; color: #6C757D; margin: 0; line-height: 1.6; }

.spg-cons-targets {
    display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-bottom: 14px;
}
.spg-ct {
    display: flex; align-items: center; gap: 8px;
    background: rgba(17,192,109,.06); border: 1px solid rgba(17,192,109,.15);
    color: var(--sg); font-size: .83rem; font-weight: 700;
    padding: 6px 14px; border-radius: 8px;
}

/* ─── CLIENTS ─── */
.spg-clients-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
.spg-client-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 20px;
    position: relative; transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-client-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
.spg-client-featured { border-color: var(--sor); box-shadow: 0 6px 20px rgba(254,168,55,.1); }
.spg-client-icon {
    width: 46px; height: 46px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 12px;
}
.spg-ci-green  { background: rgba(17,192,109,.08); color: var(--sg); }
.spg-ci-orange { background: rgba(254,168,55,.08); color: var(--sor); }
.spg-ci-blue   { background: rgba(17,192,109,.08); color: var(--sg); }
.spg-client-card h4 { font-size: .92rem; font-weight: 700; color: #333; margin: 0 0 10px; }
.spg-client-list { list-style: none; margin: 0; padding: 0; }
.spg-client-list li { display: flex; align-items: center; gap: 8px; padding: 4px 0; font-size: .84rem; color: #555; }
.spg-client-list i { color: var(--sg); font-size: .8rem; flex-shrink: 0; }
.spg-pilot-badge {
    margin-top: 12px; padding: 7px 12px;
    background: linear-gradient(135deg,var(--sg),var(--sg2));
    color: #fff; border-radius: 8px; font-size: .78rem; font-weight: 700;
    text-align: center;
}

/* ─── BUSINESS MODEL ─── */
.spg-bm-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; }
.spg-bm-card {
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 18px;
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-bm-card:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,.1); }
.spg-bm-num {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg,var(--sg),var(--sg2));
    color: #fff; font-size: .85rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 10px;
}
.spg-bm-card h4 { font-size: .88rem; font-weight: 700; color: #333; margin: 0 0 6px; }
.spg-bm-card p  { font-size: .82rem; color: #6C757D; line-height: 1.6; margin-bottom: 10px; }
.spg-bm-tags { display: flex; flex-wrap: wrap; gap: 5px; }
.spg-bm-tags span {
    background: rgba(17,192,109,.08); color: var(--sg);
    font-size: .72rem; font-weight: 700;
    padding: 3px 8px; border-radius: 5px;
}


/* ─── EXPERT PROGRAM ─── */
.spg-expert-wrap {
    display: flex; align-items: flex-start; gap: 40px;
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 32px;
}
.spg-expert-left { flex: 1; }
.spg-expert-right { flex: 0 0 280px; }
.spg-perks { display: flex; flex-direction: column; gap: 12px; margin: 18px 0 22px; }
.spg-perk { display: flex; align-items: flex-start; gap: 12px; }
.spg-perk > i {
    width: 34px; height: 34px; border-radius: 10px;
    background: rgba(254,168,55,.08); color: var(--sor);
    display: flex; align-items: center; justify-content: center;
    font-size: .88rem; flex-shrink: 0;
}
.spg-perk > div strong { display: block; font-size: .87rem; font-weight: 700; color: #333; }
.spg-perk > div span   { font-size: .8rem; color: #6C757D; }

.spg-expert-preview {
    background: #1a1a2e; border-radius: 12px;
    overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,.12);
}
.spg-ep-header {
    background: linear-gradient(135deg,var(--sg),var(--sg2));
    padding: 16px 18px; display: flex; align-items: center; gap: 10px;
}
.spg-ep-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: .9rem;
}
.spg-ep-header strong { display: block; color: #fff; font-size: .87rem; font-weight: 700; }
.spg-ep-header span   { color: rgba(255,255,255,.7); font-size: .72rem; }
.spg-ep-verified { margin-left: auto; background: rgba(255,255,255,.15); color: #fff; font-size: .7rem; font-weight: 700; padding: 3px 9px; border-radius: 999px; white-space: nowrap; }
.spg-ep-rows { padding: 14px 18px; }
.spg-ep-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-bottom: 1px solid rgba(255,255,255,.06); }
.spg-ep-row span { color: rgba(255,255,255,.5); font-size: .8rem; }
.spg-ep-row strong { color: #fff; font-size: .83rem; font-weight: 700; }
.spg-ep-highlight strong { color: var(--sg2); }

/* ─── CAMPUS PROGRAM ─── */
.spg-campus-wrap {
    display: flex; align-items: center; gap: 40px;
    background: #fff; border-radius: 12px;
    border: 1px solid #E9ECEF; padding: 32px;
}
.spg-campus-left { flex: 0 0 260px; }
.spg-campus-right { flex: 1; }
.spg-campus-card {
    background: var(--snv); border-radius: 12px;
    padding: 22px; text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,.12);
}
.spg-campus-logo {
    width: 56px; height: 56px; margin: 0 auto 12px;
    background: linear-gradient(135deg,var(--sg),var(--sg2));
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.3rem;
}
.spg-campus-domain {
    background: rgba(255,255,255,.06); border-radius: 8px;
    padding: 7px 12px; margin-bottom: 16px;
    color: rgba(255,255,255,.6); font-size: .78rem;
}
.spg-campus-domain code { color: var(--sg2); font-size: .78rem; }
.spg-campus-metrics { display: flex; gap: 10px; justify-content: center; }
.spg-campus-metrics div { text-align: center; }
.spg-campus-metrics strong { display: block; font-size: 1.3rem; font-weight: 800; color: #fff; }
.spg-campus-metrics span  { font-size: .68rem; color: rgba(255,255,255,.5); }
.spg-campus-offers { display: flex; flex-direction: column; gap: 8px; margin: 16px 0 20px; }
.spg-co { display: flex; align-items: center; gap: 10px; font-size: .87rem; color: #555; }
.spg-co i { color: var(--sg); flex-shrink: 0; }
.spg-campus-targets { display: flex; flex-wrap: wrap; gap: 6px; margin: 12px 0 20px; }
.spg-campus-targets span {
    background: rgba(17,192,109,.06); border: 1px solid rgba(17,192,109,.15);
    color: var(--sg); font-size: .8rem; font-weight: 600;
    padding: 4px 10px; border-radius: 6px;
}

/* ─── FINAL CTA ─── */
.spg-final {
    position: relative;
    background: linear-gradient(135deg, var(--snv2), var(--snv));
    padding: 60px 0; text-align: center; overflow: hidden;
}
.spg-final-bg { position: absolute; inset: 0; pointer-events: none; }
.spg-final-inner { position: relative; z-index: 1; }
.spg-final-title {
    font-size: clamp(1.5rem,2.8vw,2.2rem); font-weight: 800;
    color: #fff; margin: 0 0 12px;
}
.spg-final-sub {
    font-size: .98rem; color: rgba(255,255,255,.7);
    max-width: 560px; margin: 0 auto 32px; line-height: 1.7;
}
.spg-final-ctas { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; margin-bottom: 28px; }
.spg-fcta-green {
    display: inline-flex; align-items: center;
    background: linear-gradient(135deg,var(--sg),var(--sg2));
    color: #fff; text-decoration: none; padding: 13px 28px;
    border-radius: 12px; font-weight: 700; font-size: .95rem;
    box-shadow: 0 6px 20px rgba(17,192,109,.35);
    transition: transform .3s cubic-bezier(.4,0,.2,1), box-shadow .3s;
}
.spg-fcta-green:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(17,192,109,.5); color: #fff; }
.spg-fcta-outline {
    display: inline-flex; align-items: center;
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.25);
    color: rgba(255,255,255,.9); text-decoration: none;
    padding: 12px 24px; border-radius: 12px;
    font-weight: 600; font-size: .95rem;
    transition: background .25s;
}
.spg-fcta-outline:hover { background: rgba(255,255,255,.15); color: #fff; }
.spg-final-trust { display: flex; flex-wrap: wrap; justify-content: center; gap: 16px; }
.spg-final-trust span { color: rgba(255,255,255,.5); font-size: .83rem; }
.spg-final-trust i { color: var(--sg); margin-right: 4px; }

/* ─── RESPONSIVE ─── */
@media (max-width: 1100px) {
    .spg-domains { grid-template-columns: repeat(3,1fr); }
}
@media (max-width: 768px) {
    .spg-hero-wrap { flex-direction: column; text-align: center; }
    .spg-hero-right { display: none; }
    .spg-checks { grid-template-columns: 1fr 1fr; max-width: 400px; margin: 0 auto 36px; text-align: left; }
    .spg-hero-ctas { justify-content: center; }
    .spg-ent-cards, .spg-saas-grid, .spg-clients-grid { grid-template-columns: 1fr; max-width: 520px; margin-left: auto; margin-right: auto; }
    .spg-cyber-grid { grid-template-columns: 1fr; }
    .spg-dt-grid { grid-template-columns: 1fr; }
    .spg-talent-grid { grid-template-columns: 1fr; }
    .spg-bm-grid { grid-template-columns: repeat(2,1fr); }
    .spg-cons-grid { grid-template-columns: repeat(2,1fr); }
    .spg-expert-wrap, .spg-campus-wrap { flex-direction: column; padding: 24px; }
    .spg-expert-right, .spg-campus-left { display: none; }
}
@media (max-width: 640px) {
    .spg-section { padding: 60px 0; }
    .spg-domains { grid-template-columns: 1fr 1fr; }
    .spg-stats { flex-wrap: wrap; gap: 12px; }
    .spg-stat-sep { display: none; }
    .spg-bridge { flex-direction: column; gap: 16px; }
    .spg-bm-grid, .spg-cons-grid { grid-template-columns: 1fr; }
    .spg-nav-item span { display: none; }
    .spg-nav-item { padding: 14px 14px; }
}

/* ─── MODAL CONTACT ─── */
.spgm-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(15,52,96,.55);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
    animation: spgmFadeIn .2s ease;
}
.spgm-overlay[hidden] { display: none !important; }
@keyframes spgmFadeIn { from { opacity: 0; } to { opacity: 1; } }

.spgm-box {
    background: #fff; border-radius: 16px;
    width: 100%; max-width: 540px;
    max-height: 90vh; overflow-y: auto;
    position: relative;
    box-shadow: 0 24px 64px rgba(0,0,0,.2);
    animation: spgmSlideUp .25s cubic-bezier(.4,0,.2,1);
}
@keyframes spgmSlideUp { from { transform: translateY(24px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

.spgm-close {
    position: absolute; top: 14px; right: 14px;
    width: 32px; height: 32px; border-radius: 50%;
    background: #F8F9FA; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #6C757D; font-size: .85rem;
    transition: background .2s, color .2s;
    z-index: 1;
}
.spgm-close:hover { background: #E9ECEF; color: #333; }

.spgm-header {
    background: linear-gradient(135deg, #0d1a18, #0a2a20);
    border-radius: 16px 16px 0 0; padding: 28px 28px 24px;
    text-align: center;
}
.spgm-icon {
    width: 52px; height: 52px; border-radius: 14px;
    background: linear-gradient(135deg, var(--sg), var(--sg2));
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px; font-size: 1.2rem; color: #fff;
    box-shadow: 0 6px 18px rgba(17,192,109,.35);
}
.spgm-header h3 { color: #fff; font-size: 1.15rem; font-weight: 800; margin: 0 0 6px; }
.spgm-header p  { color: rgba(255,255,255,.6); font-size: .85rem; margin: 0; }

.spgm-form { padding: 24px 28px; }

.spgm-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.spgm-field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
.spgm-field label { font-size: .82rem; font-weight: 700; color: #333; }
.spgm-field label span { color: var(--sg); }
.spgm-field input,
.spgm-field select,
.spgm-field textarea {
    border: 1.5px solid #E9ECEF; border-radius: 8px;
    padding: 10px 12px; font-size: .88rem; color: #333;
    font-family: inherit; background: #fff;
    transition: border-color .2s, box-shadow .2s;
    outline: none; width: 100%;
}
.spgm-field input:focus,
.spgm-field select:focus,
.spgm-field textarea:focus {
    border-color: var(--sg);
    box-shadow: 0 0 0 3px rgba(17,192,109,.12);
}
.spgm-field input.is-error,
.spgm-field select.is-error,
.spgm-field textarea.is-error { border-color: var(--srd); }
.spgm-field textarea { resize: vertical; min-height: 100px; }
.spgm-err { font-size: .76rem; color: var(--srd); min-height: 1em; }

.spgm-check {
    display: flex; align-items: center; gap: 10px;
    font-size: .83rem; color: #6C757D; cursor: pointer;
    margin-bottom: 18px;
}
.spgm-check input { width: auto; accent-color: var(--sg); }

.spgm-feedback {
    border-radius: 8px; padding: 12px 14px;
    font-size: .85rem; font-weight: 600; margin-bottom: 14px;
}
.spgm-feedback.success { background: rgba(17,192,109,.08); color: var(--sg); border: 1px solid rgba(17,192,109,.2); }
.spgm-feedback.error   { background: rgba(250,28,57,.06); color: var(--srd); border: 1px solid rgba(250,28,57,.15); }

.spgm-submit {
    width: 100%; padding: 13px;
    background: linear-gradient(135deg, var(--sg), var(--sg2));
    color: #fff; border: none; border-radius: 10px;
    font-size: .95rem; font-weight: 700; cursor: pointer;
    font-family: inherit;
    box-shadow: 0 6px 20px rgba(17,192,109,.3);
    transition: transform .2s, box-shadow .2s;
    display: flex; align-items: center; justify-content: center;
}
.spgm-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(17,192,109,.4); }
.spgm-submit:disabled { opacity: .7; cursor: not-allowed; }

@media (max-width: 480px) {
    .spgm-row { grid-template-columns: 1fr; }
    .spgm-form { padding: 20px; }
    .spgm-header { padding: 22px 20px 18px; }
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    /* ── Sticky nav scroll-spy ── */
    const nav = document.getElementById('spgNav');
    const navItems = nav ? nav.querySelectorAll('.spg-nav-item') : [];
    const sections = ['academy','enterprise','cyber','digital','saas','talent','consulting'];

    function updateNav() {
        let cur = sections[0];
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el && window.scrollY >= el.offsetTop - 200) cur = id;
        });
        navItems.forEach(item => item.classList.toggle('active', item.dataset.target === cur));
    }
    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();

    navItems.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const t = document.getElementById(link.dataset.target);
            if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    /* ── Scroll reveal ── */
    const revealEls = document.querySelectorAll('.spg-reveal');
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.classList.add('revealed'); io.unobserve(e.target); }
        });
    }, { threshold: 0.08 });
    revealEls.forEach(el => io.observe(el));

    /* ── Animated counters ── */
    const counters = document.querySelectorAll('[data-count]');
    const cio = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const target = +el.dataset.count;
            const duration = 1600;
            const step = target / (duration / 16);
            let cur = 0;
            const t = setInterval(() => {
                cur = Math.min(cur + step, target);
                const val = Math.round(cur);
                el.textContent = target >= 1000
                    ? val.toLocaleString('fr-FR')
                    : val;
                if (cur >= target) clearInterval(t);
            }, 16);
            cio.unobserve(el);
        });
    }, { threshold: 0.5 });
    counters.forEach(el => cio.observe(el));

    /* ── Star field (vision + final sections) ── */
    function makeStars(el, count) {
        if (!el) return;
        for (let i = 0; i < count; i++) {
            const s = document.createElement('div');
            const sz = (Math.random() * 2.5 + .8).toFixed(1);
            s.style.cssText =
                `position:absolute;border-radius:50%;background:rgba(255,255,255,0.5);`
              + `width:${sz}px;height:${sz}px;`
              + `left:${(Math.random()*100).toFixed(1)}%;`
              + `top:${(Math.random()*100).toFixed(1)}%;`
              + `animation:spgStarBlink ${(Math.random()*3+2).toFixed(1)}s ease-in-out ${(Math.random()*4).toFixed(1)}s infinite alternate;`;
            el.appendChild(s);
        }
    }
    makeStars(document.getElementById('spgFinalBg'), 35);

    const starStyle = document.createElement('style');
    starStyle.textContent = '@keyframes spgStarBlink{from{opacity:.1}to{opacity:.7}}';
    document.head.appendChild(starStyle);
})();

/* ── Modal formulaire de contact ── */
const spgModal      = document.getElementById('spgModal');
const spgForm       = document.getElementById('spgContactForm');
const spgSubjectSel = document.getElementById('spg_subject');
const spgAutreWrap  = document.getElementById('spgm-autre-wrap');
const spgAutreInput = document.getElementById('spg_subject_autre');
const spgSubjectHid = document.getElementById('spg_subject_hidden');
const spgFeedback   = document.getElementById('spgm-feedback');
const spgSubmitBtn  = document.getElementById('spgm-submit-btn');

/* Affiche/masque le champ libre selon la sélection */
spgSubjectSel.addEventListener('change', function () {
    const isAutre = this.value === '__autre__';
    spgAutreWrap.style.display = isAutre ? 'flex' : 'none';
    spgAutreInput.required = isAutre;
    if (!isAutre) {
        spgAutreInput.value = '';
        spgAutreInput.classList.remove('is-error');
        document.getElementById('err_subject_autre').textContent = '';
    }
});

function spgOpenModal(subject) {
    spgModal.hidden = false;
    document.body.style.overflow = 'hidden';
    if (subject && spgSubjectSel) {
        /* cherche une option dont la valeur correspond exactement */
        const opt = Array.from(spgSubjectSel.options).find(o => o.value === subject);
        spgSubjectSel.value = opt ? subject : '__autre__';
        if (!opt) {
            spgAutreWrap.style.display = 'flex';
            spgAutreInput.required = true;
            spgAutreInput.value = subject;
        }
    }
}

function spgCloseModal() {
    spgModal.hidden = true;
    document.body.style.overflow = '';
    spgForm.reset();
    spgAutreWrap.style.display = 'none';
    spgAutreInput.required = false;
    spgFeedback.hidden = true;
    spgFeedback.className = 'spgm-feedback';
    document.querySelectorAll('.spgm-err').forEach(e => e.textContent = '');
    document.querySelectorAll('.is-error').forEach(e => e.classList.remove('is-error'));
    const submitText = spgSubmitBtn.querySelector('.spgm-submit-text');
    const submitLoad = spgSubmitBtn.querySelector('.spgm-submit-loading');
    submitText.hidden = false;
    submitLoad.hidden = true;
    spgSubmitBtn.disabled = false;
}

spgModal.addEventListener('click', function(e) {
    if (e.target === spgModal) spgCloseModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !spgModal.hidden) spgCloseModal();
});

spgForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    /* Reset errors */
    document.querySelectorAll('.spgm-err').forEach(el => el.textContent = '');
    document.querySelectorAll('.is-error').forEach(el => el.classList.remove('is-error'));
    spgFeedback.hidden = true;

    /* Résoudre la valeur finale de subject */
    const isAutre = spgSubjectSel.value === '__autre__';
    const subjectVal = isAutre ? spgAutreInput.value.trim() : spgSubjectSel.value;
    spgSubjectHid.value = subjectVal;

    /* Client-side validation */
    let valid = true;

    if (!spgSubjectSel.value) {
        spgSubjectSel.classList.add('is-error');
        document.getElementById('err_subject').textContent = 'Veuillez choisir un service.';
        valid = false;
    }
    if (isAutre && !spgAutreInput.value.trim()) {
        spgAutreInput.classList.add('is-error');
        document.getElementById('err_subject_autre').textContent = 'Ce champ est requis.';
        valid = false;
    }

    ['firstName','lastName','email','message'].forEach(name => {
        const input = spgForm.querySelector(`[name="${name}"]`);
        const err   = document.getElementById(`err_${name}`);
        if (input && !input.value.trim()) {
            input.classList.add('is-error');
            if (err) err.textContent = 'Ce champ est requis.';
            valid = false;
        }
    });

    const emailInput = document.getElementById('spg_email');
    if (emailInput && emailInput.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value)) {
        emailInput.classList.add('is-error');
        document.getElementById('err_email').textContent = 'Adresse e-mail invalide.';
        valid = false;
    }

    if (!valid) return;

    /* Loading state */
    const submitText = spgSubmitBtn.querySelector('.spgm-submit-text');
    const submitLoad = spgSubmitBtn.querySelector('.spgm-submit-loading');
    submitText.hidden = true;
    submitLoad.hidden = false;
    spgSubmitBtn.disabled = true;

    /* AJAX submit — on envoie subject via le champ caché */
    try {
        const data = new FormData(spgForm);
        /* supprimer _subject_select de l'envoi — le serveur ne le connaît pas */
        data.delete('_subject_select');
        const res  = await fetch('{{ route("contact.submit") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
            body: data
        });
        const json = await res.json();

        spgFeedback.hidden = false;
        if (json.success) {
            spgFeedback.className = 'spgm-feedback success';
            spgFeedback.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + json.message;
            spgForm.reset();
            spgAutreWrap.style.display = 'none';
            setTimeout(() => spgCloseModal(), 4000);
        } else {
            spgFeedback.className = 'spgm-feedback error';
            spgFeedback.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + json.message;
        }
    } catch (err) {
        spgFeedback.hidden = false;
        spgFeedback.className = 'spgm-feedback error';
        spgFeedback.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Une erreur réseau est survenue. Veuillez réessayer.';
    } finally {
        submitText.hidden = false;
        submitLoad.hidden = true;
        spgSubmitBtn.disabled = false;
    }
});
</script>
@endpush

@endsection
