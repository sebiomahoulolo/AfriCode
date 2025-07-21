@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')

<!-- Hero Section avec design organique -->
<section class="about-hero py-5">
  <div class="container">
    <div class="row align-items-center min-vh-50">
      <div class="col-lg-6">
        <div class="hero-content">
          <h1 class="hero-title mb-4">À propos d'<span class="text-gradient">AfriCode</span></h1>
          <p class="hero-subtitle mb-4">Découvrez notre mission, notre vision et notre engagement pour transformer l'écosystème numérique africain.</p>
          <div class="floating-badges">
            <span class="tech-badge">Mission</span>
            <span class="tech-badge">Vision</span>
            <span class="tech-badge">Impact</span>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-image-container">
          <div class="organic-container main-container">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Équipe africaine en développement" class="hero-main-image">
          </div>
          <div class="decorative-elements">
            <div class="blue-dots"></div>
            <div class="wavy-circle"></div>
            <div class="diagonal-lines"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section Mission avec conteneurs organiques -->
<section class="mission-section py-5">
  <div class="container">
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="organic-container secondary-container">
          <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Développeurs Africains" class="content-image">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="content-text">
          <h3 class="section-title mb-4">Notre <span class="text-gradient">Mission</span></h3>
          <p class="text-content mb-4">AfriCode est une initiative née de la volonté de promouvoir l'apprentissage du numérique, du codage et de l'entrepreneuriat digital en Afrique. Nous croyons que chaque jeune africain mérite l'opportunité de maîtriser les outils de demain.</p>
          <p class="text-content">Grâce à des ressources gratuites, une communauté active et des projets concrets, AfriCode veut inspirer, former et connecter les futurs talents du continent.</p>
        </div>
      </div>
    </div>

    <div class="row align-items-center mb-5">
      <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
        <div class="organic-container tertiary-container">
          <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Innovation en Afrique" class="content-image">
        </div>
      </div>
      <div class="col-lg-6 order-lg-1">
        <div class="content-text">
          <h3 class="section-title mb-4">Notre <span class="text-gradient">Vision</span></h3>
          <p class="text-content">Nous imaginons un continent africain où la technologie et le codage sont des outils essentiels pour résoudre des problèmes locaux, stimuler l'innovation et transformer les communautés. Notre vision est de bâtir une Afrique autonome, connectée et leader mondial dans l'écosystème numérique.</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 text-center">
        <h3 class="section-title mb-4">Nos <span class="text-gradient">Objectifs</span></h3>
        <p class="text-content mb-5">AfriCode aspire à atteindre les objectifs suivants :</p>
        <div class="objectives-grid">
          <div class="objective-card">
            <div class="objective-icon">🎯</div>
            <p>Former plus de 100,000 jeunes africains aux outils numériques d'ici 2030</p>
          </div>
          <div class="objective-card">
            <div class="objective-icon">🌍</div>
            <p>Créer un réseau de communautés technologiques dynamiques sur tout le continent</p>
          </div>
          <div class="objective-card">
            <div class="objective-icon">💡</div>
            <p>Encourager l'innovation locale à travers des hackathons et des projets concrets</p>
          </div>
          <div class="objective-card">
            <div class="objective-icon">🚀</div>
            <p>Favoriser l'accès aux opportunités globales pour les talents africains</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section Valeurs modernisée -->
<section class="values-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="section-title">Nos <span class="text-gradient">Valeurs</span></h3>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="value-card">
          <div class="value-icon">♿</div>
          <h5>Accessibilité</h5>
          <p>Nous veillons à ce que la technologie soit accessible à tous, sans barrière.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="value-card">
          <div class="value-icon">📚</div>
          <h5>Partage</h5>
          <p>Nous encourageons l'échange de connaissances pour grandir ensemble.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="value-card">
          <div class="value-icon">💡</div>
          <h5>Innovation</h5>
          <p>Nous croyons au pouvoir de l'innovation pour relever les défis locaux.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="value-card">
          <div class="value-icon">👥</div>
          <h5>Communauté</h5>
          <p>Nous construisons une communauté solidaire, où les talents collaborent.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="value-card">
          <div class="value-icon">🌍</div>
          <h5>Impact global</h5>
          <p>Nous aspirons à transformer le continent grâce à des solutions durables.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="value-card">
          <div class="value-icon">🏆</div>
          <h5>Leadership</h5>
          <p>Nous valorisons les jeunes leaders pour un avenir responsable.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section Témoignages modernisée -->
<section class="testimonials-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="section-title">Ce qu'ils disent de <span class="text-gradient">nous</span></h3>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p>AfriCode m'a donné les outils nécessaires pour lancer ma start-up digitale.</p>
          <div class="author">
            <h6>Jean K.</h6>
            <span>Entrepreneur</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p>Une expérience transformatrice qui m'a appris à coder et à collaborer.</p>
          <div class="author">
            <h6>Fatou D.</h6>
            <span>Développeuse</span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="quote-icon">"</div>
          <p>Le soutien d'AfriCode est incroyable, je me sens prêt à conquérir le monde digital.</p>
          <div class="author">
            <h6>Youssouf B.</h6>
            <span>Étudiant</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section FAQ modernisée -->
<section class="faq-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="section-title">Questions <span class="text-gradient">Fréquentes</span></h3>
      <p class="text-content">Trouvez rapidement des réponses à vos questions</p>
    </div>

    <div class="accordion modern-accordion" id="faqAccordion">
      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading1">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
            Qu'est-ce qu'AfriCode et que proposez-vous ?
          </button>
        </h2>
        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            AfriCode est une plateforme de formation tech africaine qui démocratise l'accès à l'éducation numérique. Nous proposons des formations certifiantes en programmation, développement web, cybersécurité, intelligence artificielle et data science. Notre mission est de former la prochaine génération de talents tech africains avec plus de 80% de nos diplômés qui trouvent un emploi dans les 6 mois.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
            Y a-t-il des cours gratuits disponibles ?
          </button>
        </h2>
        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Oui ! Nous proposons une large gamme de cours gratuits pour permettre à tous d'accéder à l'éducation tech. Nos formations de base en HTML/CSS, JavaScript, Python et introduction à la programmation et autres sont entièrement gratuites. Nous croyons que l'éducation tech doit être accessible à tous, quel que soit le niveau de revenu.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading3">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
            Comment obtenir un certificat AfriCode ?
          </button>
        </h2>
        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Pour obtenir un certificat AfriCode, vous devez :<br>
            • Suivre complètement le parcours de formation<br>
            • Réussir tous les quiz et évaluations (minimum 70%)<br>
            • Compléter le projet final de certification<br>
            • Participer aux sessions pratiques et workshops<br><br>
            Nos certificats sont reconnus par les entreprises tech et incluent un code de vérification unique pour authentifier leur validité sur notre site.
          </div>
        </div>
      </div>

      <!-- <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading4">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
            Quels sont les prix des formations payantes ?
          </button>
        </h2>
        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Nos formations payantes sont accessibles à des prix adaptés au marché africain :<br>
            • Formations intermédiaires : 25,000 - 50,000 FCFA<br>
            • Formations avancées : 75,000 - 150,000 FCFA<br>
            • Parcours complets avec certification : 200,000 - 350,000 FCFA<br><br>
            Nous proposons des facilités de paiement (échelonnement sur 3-6 mois) et des bourses pour les étudiants méritants. Contactez-nous pour un devis personnalisé.
          </div>
        </div>
      </div> -->

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading5">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
            Combien de temps durent les formations ?
          </button>
        </h2>
        <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            La durée varie selon le niveau et le type de formation :<br>
            • Cours gratuits : 2-4 semaines<br>
            • Formations intermédiaires : 6-8 semaines<br>
            • Formations avancées : 3-6 mois<br>
            • Parcours complets : 6-8 mois<br><br>
            Vous pouvez suivre à votre rythme avec un accès illimité au contenu. Nos formations sont conçues pour s'adapter à votre emploi du temps.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading6">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
            Quel support technique proposez-vous ?
          </button>
        </h2>
        <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Nous offrons un support complet :<br>
            • Forum communautaire pour échanger entre apprenants<br>
            • Sessions de mentorat avec des experts<br>
            • Support technique par email  contact@africode.tech<br><br>
            Notre équipe répond sous 24h maximum à toutes vos questions.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading7">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
            Les formations sont-elles en ligne ou en présentiel ?
          </button>
        </h2>
        <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Nous proposons les deux formats :<br>
            • <strong>Formations en ligne</strong> : Accessibles 24h/24, partout <br>
            • <strong>Formations hybrides</strong> : En ligne + sessions pratiques en présentiel<br>
            • <strong>Bootcamps intensifs</strong> : 100% en présentiel dans nos centres<br><br>
            <!-- Nos centres sont situés à Parakou (Bénin) et nous prévoyons d'ouvrir dans d'autres villes . Les formations en ligne sont identiques en qualité aux formations présentielles.
            -->
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading8">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse8" aria-expanded="false" aria-controls="faqCollapse8">
            Comment AfriCode aide-t-il à trouver un emploi ?
          </button>
        </h2>
        <div id="faqCollapse8" class="accordion-collapse collapse" aria-labelledby="faqHeading8" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
          Oui, Notre programme d'accompagnement emploi comprend :<br>
            • Mise en relation avec nos partenaires entreprises<br>
            • Réseau d'anciens élèves pour le networking<br>
            • Compétitions et hackathons pour se faire remarquer<br><br>
            80% de nos diplômés trouvent un emploi dans les 6 mois suivant leur formation.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading9">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse9" aria-expanded="false" aria-controls="faqCollapse9">
            Quels sont les prérequis pour s'inscrire ?
          </button>
        </h2>
        <div id="faqCollapse9" class="accordion-collapse collapse" aria-labelledby="faqHeading9" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Nos formations sont accessibles à tous :<br>
            • <strong>Débutants</strong> : Aucun prérequis, nous commençons depuis zéro<br>
            • <strong>Intermédiaires</strong> : Notions de base en informatique<br>
            • <strong>Avancés</strong> : Connaissances en programmation requises<br><br>
            Nous proposons un test de niveau gratuit pour vous orienter vers la formation la plus adaptée. L'âge minimum est de 16 ans, sans limite d'âge supérieure <a href="{{ route('register') }}">S'inscrire</a>.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading10">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse10" aria-expanded="false" aria-controls="faqCollapse10">
            Comment puis-je contacter AfriCode ?
          </button>
        </h2>
        <div id="faqCollapse10" class="accordion-collapse collapse" aria-labelledby="faqHeading10" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Plusieurs moyens de nous contacter :<br>
            • <strong>Email</strong> : contact@africode.tech<br>
            • <strong>WhatsApp</strong> : +229 01 65 60 30 40<br>
            {{-- • <strong>Adresse</strong> : Parakou, Bénin<br> --}}
            • <strong>Réseaux sociaux</strong> : Facebook, Instagram, LinkedIn, YouTube<br>
            • <strong>Formulaire de contact</strong> sur notre site web<br><br>
            Horaires : Lundi - Vendredi, 8h - 18h (GMT+1)
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS moderne unifié -->
<style>
/* Variables CSS AfriCode - Cohérentes avec la page d'accueil */
:root {
  --africode-primary: #1EA38B;
  --africode-secondary: #FF8E2A;
  --africode-accent-red: #E32D31;
  --africode-highlight-green: #27B371;
  --africode-white: #FFFFFF;
  --africode-dark-text: #333333;
  --africode-gray-light: #F8F9FA;
  --africode-gray-medium: #E9ECEF;
  --africode-gray-dark: #6C757D;
  --africode-gradient-primary: linear-gradient(135deg, var(--africode-primary) 0%, var(--africode-highlight-green) 100%);
  --africode-gradient-accent: linear-gradient(135deg, var(--africode-secondary) 0%, #FFB366 100%);
  --africode-shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
  --africode-shadow-md: 0 4px 8px rgba(0, 0, 0, 0.15);
  --africode-shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.15);
  --africode-border-radius: 12px;
  --africode-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hero Section */
.about-hero {
  background: linear-gradient(135deg, 
    rgba(30, 163, 139, 0.08) 0%, 
    rgba(255, 142, 42, 0.05) 50%, 
    rgba(39, 179, 113, 0.08) 100%);
  position: relative;
  overflow: hidden;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  color: var(--africode-primary);
  line-height: 1.2;
}

.text-gradient {
  background: var(--africode-gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-subtitle {
  font-size: 1.3rem;
  color: var(--africode-gray-dark);
  line-height: 1.6;
}

.floating-badges {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.tech-badge {
  background: rgba(30, 163, 139, 0.1);
  color: var(--africode-primary);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
  border: 1px solid rgba(30, 163, 139, 0.2);
  transition: var(--africode-transition);
}

.tech-badge:hover {
  background: var(--africode-primary);
  color: white;
  transform: translateY(-2px);
}

/* Conteneurs organiques */
.hero-image-container {
  position: relative;
  height: 100%;
}

.organic-container {
  border-radius: var(--africode-border-radius);
  overflow: hidden;
  box-shadow: var(--africode-shadow-lg);
  transition: var(--africode-transition);
  position: relative;
}

.main-container {
  width: 100%;
  height: 400px;
  transform: rotate(-5deg);
}

.secondary-container {
  width: 100%;
  height: 300px;
  transform: rotate(3deg);
}

.tertiary-container {
  width: 100%;
  height: 300px;
  transform: rotate(-2deg);
}

.hero-main-image,
.content-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.organic-container:hover .hero-main-image,
.organic-container:hover .content-image {
  transform: scale(1.1);
}

/* Éléments décoratifs */
.decorative-elements {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.blue-dots {
  position: absolute;
  top: 20px;
  left: 20px;
  width: 100px;
  height: 100px;
  background: radial-gradient(circle, var(--africode-primary) 2px, transparent 2px);
  background-size: 20px 20px;
  opacity: 0.3;
}

.wavy-circle {
  position: absolute;
  bottom: 30px;
  right: 30px;
  width: 80px;
  height: 80px;
  border: 3px solid var(--africode-secondary);
  border-radius: 50%;
  opacity: 0.7;
}

.diagonal-lines {
  position: absolute;
  top: 50%;
  right: -50px;
  width: 100px;
  height: 100px;
  background: repeating-linear-gradient(
    45deg,
    transparent,
    transparent 10px,
    var(--africode-highlight-green) 10px,
    var(--africode-highlight-green) 12px
  );
  opacity: 0.5;
}

/* Sections de contenu */
.mission-section {
  background: white;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--africode-primary);
  margin-bottom: 1.5rem;
}

.text-content {
  font-size: 1.1rem;
  color: var(--africode-gray-dark);
  line-height: 1.8;
}

.content-text {
  padding: 2rem 0;
}

/* Grille des objectifs */
.objectives-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 2rem;
  margin-top: 3rem;
}

.objective-card {
  background: white;
  padding: 2rem;
  border-radius: var(--africode-border-radius);
  box-shadow: var(--africode-shadow-lg);
  text-align: center;
  transition: var(--africode-transition);
  border: 1px solid rgba(30, 163, 139, 0.1);
}

.objective-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 25px 50px rgba(30, 163, 139, 0.15);
}

.objective-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

/* Section Valeurs */
.values-section {
  background: linear-gradient(135deg, var(--africode-gray-light) 0%, var(--africode-gray-medium) 100%);
}

.value-card {
  background: white;
  padding: 2rem;
  border-radius: var(--africode-border-radius);
  box-shadow: var(--africode-shadow-lg);
  text-align: center;
  transition: var(--africode-transition);
  height: 100%;
  border: 1px solid rgba(30, 163, 139, 0.1);
}

.value-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(30, 163, 139, 0.15);
}

.value-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
}

.value-card h5 {
  color: var(--africode-primary);
  font-weight: 600;
  margin-bottom: 1rem;
}

.value-card p {
  color: var(--africode-gray-dark);
  line-height: 1.6;
}

/* Section Témoignages */
.testimonials-section {
  background: white;
}

.testimonial-card {
  background: linear-gradient(135deg, var(--africode-gray-light) 0%, white 100%);
  padding: 2rem;
  border-radius: var(--africode-border-radius);
  box-shadow: var(--africode-shadow-lg);
  text-align: center;
  transition: var(--africode-transition);
  height: 100%;
  border: 1px solid rgba(30, 163, 139, 0.1);
  position: relative;
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(30, 163, 139, 0.15);
}

.quote-icon {
  font-size: 4rem;
  color: var(--africode-secondary);
  line-height: 1;
  margin-bottom: 1rem;
}

.testimonial-card p {
  font-style: italic;
  color: var(--africode-gray-dark);
  margin-bottom: 1.5rem;
  line-height: 1.6;
}

.author h6 {
  color: var(--africode-primary);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.author span {
  color: var(--africode-secondary);
  font-size: 0.9rem;
}

/* Section FAQ */
.faq-section {
  background: linear-gradient(135deg, var(--africode-gray-light) 0%, var(--africode-gray-medium) 100%);
}

.modern-accordion .accordion-item {
  border: none;
  margin-bottom: 1rem;
  border-radius: var(--africode-border-radius);
  overflow: hidden;
  box-shadow: var(--africode-shadow-lg);
}

.modern-accordion .accordion-button {
  background: white;
  border: none;
  font-weight: 600;
  color: var(--africode-primary);
  padding: 1.5rem;
  border-radius: var(--africode-border-radius);
}

.modern-accordion .accordion-button:not(.collapsed) {
  background: var(--africode-primary);
  color: white;
}

.modern-accordion .accordion-button:focus {
  box-shadow: none;
  border: none;
}

.modern-accordion .accordion-body {
  background: white;
  color: var(--africode-gray-dark);
  line-height: 1.6;
  padding: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .objectives-grid {
    grid-template-columns: 1fr;
  }
  
  .organic-container {
    transform: none !important;
  }
  
  .main-container {
    height: 250px;
  }
  
  .secondary-container,
  .tertiary-container {
    height: 200px;
  }
}

/* Animations d'entrée */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.hero-content,
.content-text {
  animation: fadeInUp 0.8s ease-out;
}

.organic-container {
  animation: fadeInUp 0.8s ease-out 0.2s both;
}
</style>

@endsection
