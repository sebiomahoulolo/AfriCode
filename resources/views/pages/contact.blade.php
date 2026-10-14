@extends('layouts.layout')

@section('title', 'Contactez-nous — AfriCode | Hub Numérique Africain')
@section('meta_description', 'Contactez l\'équipe AfriCode pour vos projets de formation, de développement numérique ou de partenariat. Nous sommes à votre écoute.')
@section('meta_keywords', 'contact AfriCode, support AfriCode, partenariat numérique Afrique, nous contacter, contact@africode.tech')
@section('og_title', 'Contactez AfriCode — Hub Numérique Africain')
@section('og_description', 'Une question sur nos formations ou services ? Contactez l\'équipe AfriCode. Réponse sous 24h.')
@section('og_image', asset('images/africode-og.jpg'))

@push('meta')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "Contact AfriCode",
  "url": "{{ url()->current() }}",
  "mainEntity": {
    "@type": "Organization",
    "name": "AfriCode",
    "email": "contact@africode.tech",
    "url": "{{ url('/') }}"
  }
}
</script>
@endpush

@section('content')

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-WJFL3W2W');</script>
    <!-- End Google Tag Manager -->
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WJFL3W2W"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<!-- Hero Section pour Contact -->
<section class="contact-hero py-5">
  <div class="container">
    <div class="row align-items-center min-vh-50">
      <div class="col-lg-6">
        <div class="hero-content">
          <h1 class="hero-title mb-4">Contactez <span class="text-gradient">AfriCode</span></h1>
          <p class="hero-subtitle mb-4">Une question, une collaboration ou une idée à partager ? L'équipe AfriCode est à votre écoute pour transformer vos projets en réalité.</p>
          <div class="floating-badges">
            <span class="tech-badge">Support</span>
            <span class="tech-badge">Collaboration</span>
            <span class="tech-badge">Innovation</span>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-image-container">
          <div class="organic-container main-container">
            <img src="https://images.unsplash.com/photo-1423666639041-f56000c27a9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Contact AfriCode" class="hero-main-image">
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

<!-- Section principale de contact -->
<section class="main-contact-section py-5">
  <div class="container">
    <div class="row g-5">
      <!-- Informations de contact -->
      <div class="col-lg-5">
        <div class="contact-info-container">
          <h3 class="section-title mb-4">Restons <span class="text-gradient">connectés</span></h3>
          <p class="text-content mb-4">Nous répondons généralement sous 24h. N'hésitez pas à nous écrire pour toute question ou collaboration !</p>

          <div class="contact-cards">
            <div class="contact-card">
              <div class="contact-icon"><i class="fas fa-envelope"></i></div>
              <div class="contact-details">
                <h5>Email</h5>
                <p>contact@africode.tech</p>
              </div>
            </div>

            <div class="contact-card">
              <div class="contact-icon"><i class="fas fa-mobile-alt"></i></div>
              <div class="contact-details">
                <h5>Téléphone</h5>
                <p>+229 01 65 60 30 40</p>
              </div>
            </div>

            <div class="contact-card">
              <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
              <div class="contact-details">
                <h5>Adresse</h5>
                <p>Cotonou, Bénin</p>
              </div>
            </div>

            <div class="contact-card">
              <div class="contact-icon"><i class="fas fa-clock"></i></div>
              <div class="contact-details">
                <h5>Horaires</h5>
                <p>Lun - Ven : 8h - 18h</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Formulaire de contact -->
      <div class="col-lg-7">
        <div class="contact-form-container">
          <div class="organic-container form-container">
            <h4 class="form-title mb-4">Envoyez-nous un message</h4>
            <form action="{{ route('contact.submit') }}" method="POST" class="modern-form" id="contactForm" enctype="multipart/form-data">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="firstName" class="form-label">Prénom</label>
                    <input type="text" class="form-control modern-input" id="firstName" name="firstName" placeholder="Votre prénom" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="lastName" class="form-label">Nom</label>
                    <input type="text" class="form-control modern-input" id="lastName" name="lastName" placeholder="Votre nom" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="form-label">Adresse e-mail</label>
                <input type="email" class="form-control modern-input" id="email" name="email" placeholder="exemple@domaine.com" required>
              </div>

              <div class="form-group">
                <label for="subject" class="form-label">Sujet</label>
                <select class="form-control modern-input" id="subject" name="subject" required>
                  <option value="">Choisissez un sujet</option>
                  <option value="formation">Question sur les formations</option>
                  <option value="collaboration">Proposition de collaboration</option>
                  <option value="support">Support technique</option>
                  <option value="partenariat">Partenariat</option>
                  <option value="autre">Autre</option>
                </select>
              </div>

              <div class="form-group">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control modern-input" id="message" name="message" rows="5" placeholder="Décrivez votre projet, question ou demande..." required></textarea>
              </div>

              <!-- Pièce jointe (optionnelle) -->
              <div class="form-group">
                <label class="form-label">Pièce jointe <span class="badge-optional">Optionnel</span></label>
                <div class="file-drop-zone" id="fileDropZone">
                  <input type="file" class="file-input-hidden" id="attachment" name="attachment"
                         accept=".pdf,.jpg,.jpeg,.png,.gif,.webp">
                  <div class="file-drop-content" id="fileDropContent">
                    <div class="file-drop-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <p class="file-drop-label">Glissez un fichier ici ou <span class="file-browse-link">parcourez</span></p>
                    <p class="file-drop-hint">PDF, JPG, PNG, GIF, WEBP — max 5 Mo</p>
                  </div>
                  <div class="file-selected-info" id="fileSelectedInfo" style="display:none;">
                    <i class="fas fa-paperclip me-2"></i>
                    <span id="fileSelectedName"></span>
                    <button type="button" class="file-remove-btn" id="fileRemoveBtn" title="Supprimer">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
                <div id="fileError" class="file-error-msg" style="display:none;"></div>
              </div>

              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="newsletter" name="newsletter">
                  <label class="form-check-label" for="newsletter">
                    Je souhaite recevoir la newsletter AfriCode
                  </label>
                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn modern-btn" id="submitBtn">
                  <span>Envoyer le message</span>
                  <i class="fas fa-paper-plane ms-2"></i>
                </button>
              </div>

              <!-- Messages de succès/erreur -->
              <div id="contactMessage" class="mt-3" style="display: none;"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Section réseaux sociaux -->
<section class="social-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="section-title">Suivez-nous sur les <span class="text-gradient">réseaux</span></h3>
      <p class="text-content">Rejoignez notre communauté et restez informés de nos dernières actualités</p>
    </div>

    <div class="social-cards-container">
      <div class="social-card">
        <div class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></div>
        <h5>LinkedIn</h5>
        <p>Actualités professionnelles</p>
        <a href="https://www.linkedin.com/company/africode-no-code-no-future/" class="social-link">Suivre</a>
      </div>

      <!-- <div class="social-card">
        <div class="social-icon twitter"><i class="fab fa-twitter"></i></div>
        <h5>Twitter</h5>
        <p>Actualités en temps réel</p>
        <a href="#" class="social-link">Suivre</a>
      </div> -->

      <div class="social-card">
        <div class="social-icon youtube"><i class="fab fa-youtube"></i></div>
        <h5>YouTube</h5>
        <p>Tutoriels et formations</p>
        <a href="https://youtube.com/@africodetechsm?si=k8Y49FJR3tib1KNc" class="social-link">S'abonner</a>
      </div>

      <div class="social-card">
        <div class="social-icon github"><i class="fab fa-facebook-f" ></i></div>
        <h5>Facebook</h5>
        <p>Actualités en temps réel</p>
        <a href="https://www.facebook.com/profile.php?id=61574681464943" class="social-link">Suivre</a>
      </div>

      <div class="social-card">
        <div class="social-icon tiktok"><i class="fab fa-tiktok"></i></div>
        <h5>TikTok</h5>
        <p>Vidéos et actualités</p>
        <a href="https://www.tiktok.com/@africodetech?_r=1&_t=ZS-93v9AuqVviq" class="social-link" target="_blank" rel="noopener noreferrer">Suivre</a>
      </div>
    </div>
  </div>
</section>

<!-- Section FAQ rapide -->
<section class="quick-faq-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h3 class="section-title">Questions <span class="text-gradient">rapides</span></h3>
      <p class="text-content">Les réponses aux questions les plus fréquentes</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="faq-quick-card">
          <h5>Comment rejoindre AfriCode ?</h5>
          <p>Inscrivez-vous simplement sur notre plateforme et choisissez les formations qui vous intéressent.</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="faq-quick-card">
          <h5> Les formations sont-elles gratuites ?</h5>
          <p>Oui, la plupart de nos formations de base sont entièrement gratuites pour tous les participants.</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="faq-quick-card">
          <h5> Où se déroulent les formations ?</h5>
          <p>Nos formations sont disponibles en ligne et en présentiel dans plusieurs villes d'Afrique.</p>
        </div>
      </div>

      <div class="col-md-6">
        <div class="faq-quick-card">
          <h5> Y a-t-il des certifications ?</h5>
          <p>Oui, nous délivrons des certifications reconnues à la fin de chaque parcours de formation.</p>
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
  --africode-secondary: #fea837;
  --africode-accent-red: #fa1c39;
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
.contact-hero {
  background: linear-gradient(135deg,
    rgba(30, 163, 139, 0.08) 0%,
    rgba(254, 168, 55, 0.05) 50%,
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
  transform: rotate(-3deg);
}

.form-container {
  background: white;
  padding: 3rem;
  transform: rotate(1deg);
}

.hero-main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.organic-container:hover .hero-main-image {
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

/* Section principale de contact */
.main-contact-section {
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

/* Cartes d'informations de contact */
.contact-cards {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.contact-card {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, var(--africode-gray-light) 0%, white 100%);
  border-radius: var(--africode-border-radius);
  box-shadow: var(--africode-shadow-lg);
  transition: var(--africode-transition);
  border: 1px solid rgba(30, 163, 139, 0.1);
}

.contact-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(30, 163, 139, 0.15);
}

.contact-icon {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  background: linear-gradient(135deg, #1EA38B, #27B371);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  color: white;
  flex-shrink: 0;
}

.contact-details h5 {
  color: var(--africode-primary);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.contact-details p {
  color: var(--africode-gray-dark);
  margin: 0;
  font-size: 1rem;
}

/* Formulaire moderne */
.contact-form-container {
  position: relative;
}

.form-title {
  color: var(--africode-primary);
  font-weight: 600;
  text-align: center;
}

.modern-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  color: var(--africode-primary);
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.modern-input {
  padding: 1rem;
  border: 2px solid rgba(30, 163, 139, 0.1);
  border-radius: 15px;
  font-size: 1rem;
  transition: var(--africode-transition);
  background: rgba(248, 249, 250, 0.5);
}

.modern-input:focus {
  outline: none;
  border-color: var(--africode-primary);
  box-shadow: 0 0 0 3px rgba(30, 163, 139, 0.1);
  background: white;
}

.modern-btn {
  background: var(--africode-gradient-primary);
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 25px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--africode-transition);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  align-self: flex-start;
}

.modern-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 30px rgba(30, 163, 139, 0.3);
}

/* Section réseaux sociaux */
.social-section {
  background: linear-gradient(135deg, var(--africode-gray-light) 0%, var(--africode-gray-medium) 100%);
}

/* Cadres réseaux sociaux - même ligne */
.social-cards-container {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin: 0 auto;
}

.social-card {
  background: white;
  padding: 2rem;
  border-radius: var(--africode-border-radius);
  box-shadow: var(--africode-shadow-lg);
  text-align: center;
  transition: var(--africode-transition);
  border: 1px solid rgba(30, 163, 139, 0.1);
}

.social-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 25px 50px rgba(30, 163, 139, 0.15);
}

.social-icon {
  font-size: 3rem;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  color: white;
}

.social-icon.linkedin {
  background: #0A66C2;
}

.social-icon.youtube {
  background: #FF0000;
}

.social-icon.github {
  background: #1877F2;
}

.social-icon.tiktok {
  background: #000000;
}

.social-card h5 {
  color: var(--africode-primary);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.social-card p {
  color: var(--africode-gray-dark);
  margin-bottom: 1.5rem;
}

.social-link {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: rgba(30, 163, 139, 0.1);
  color: var(--africode-primary);
  text-decoration: none;
  border-radius: 20px;
  font-weight: 500;
  transition: var(--africode-transition);
}

.social-link:hover {
  background: var(--africode-primary);
  color: white;
  transform: translateY(-2px);
}

/* Section FAQ rapide */
.quick-faq-section {
  background: white;
}

.faq-quick-card {
  background: linear-gradient(135deg, var(--africode-gray-light) 0%, white 100%);
  padding: 2rem;
  border-radius: var(--africode-border-radius);
  box-shadow: var(--africode-shadow-lg);
  transition: var(--africode-transition);
  border: 1px solid rgba(30, 163, 139, 0.1);
  height: 100%;
}

.faq-quick-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(30, 163, 139, 0.15);
}

.faq-quick-card h5 {
  color: var(--africode-primary);
  font-weight: 600;
  margin-bottom: 1rem;
}

.faq-quick-card p {
  color: var(--africode-gray-dark);
  line-height: 1.6;
  margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }

  .section-title {
    font-size: 2rem;
  }

  .organic-container {
    transform: none !important;
  }

  .main-container {
    height: 250px;
  }

  .form-container {
    padding: 2rem;
  }

  .contact-cards {
    gap: 1rem;
  }

  .contact-card {
    padding: 1rem;
  }

  .social-cards-container {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
  }
}

@media (max-width: 576px) {
  .social-cards-container {
    grid-template-columns: 1fr;
  }
}

/* Zone upload fichier */
.badge-optional {
  background: rgba(30, 163, 139, 0.12);
  color: var(--africode-primary);
  font-size: 0.72rem;
  font-weight: 500;
  padding: 0.15rem 0.55rem;
  border-radius: 10px;
  vertical-align: middle;
  border: 1px solid rgba(30, 163, 139, 0.2);
}

.file-drop-zone {
  border: 2px dashed rgba(30, 163, 139, 0.35);
  border-radius: 15px;
  background: rgba(248, 249, 250, 0.5);
  padding: 1.5rem 1rem;
  text-align: center;
  cursor: pointer;
  transition: var(--africode-transition);
  position: relative;
}

.file-drop-zone:hover,
.file-drop-zone.drag-over {
  border-color: var(--africode-primary);
  background: rgba(30, 163, 139, 0.06);
}

.file-input-hidden {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

.file-drop-icon {
  font-size: 2rem;
  color: var(--africode-primary);
  margin-bottom: 0.5rem;
}

.file-drop-label {
  margin: 0;
  color: var(--africode-dark-text);
  font-size: 0.95rem;
}

.file-browse-link {
  color: var(--africode-primary);
  font-weight: 600;
  text-decoration: underline;
  cursor: pointer;
}

.file-drop-hint {
  margin: 0.25rem 0 0;
  color: var(--africode-gray-dark);
  font-size: 0.8rem;
}

.file-selected-info {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  color: var(--africode-primary);
  font-weight: 500;
  font-size: 0.95rem;
}

.file-remove-btn {
  background: none;
  border: none;
  color: var(--africode-accent-red);
  cursor: pointer;
  font-size: 1rem;
  padding: 0 0.25rem;
  line-height: 1;
  transition: var(--africode-transition);
}

.file-remove-btn:hover {
  transform: scale(1.2);
}

.file-error-msg {
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 0.35rem;
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
.contact-info-container,
.contact-form-container {
  animation: fadeInUp 0.8s ease-out;
}

.organic-container {
  animation: fadeInUp 0.8s ease-out 0.2s both;
}

.contact-card {
  animation: fadeInUp 0.8s ease-out 0.3s both;
}
</style>

<!-- JavaScript pour le formulaire de contact -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const contactMessage = document.getElementById('contactMessage');
    const submitBtn = document.getElementById('submitBtn');

    // --- Gestion de la zone d'upload ---
    const dropZone     = document.getElementById('fileDropZone');
    const fileInput    = document.getElementById('attachment');
    const dropContent  = document.getElementById('fileDropContent');
    const selectedInfo = document.getElementById('fileSelectedInfo');
    const selectedName = document.getElementById('fileSelectedName');
    const removeBtn    = document.getElementById('fileRemoveBtn');
    const fileError    = document.getElementById('fileError');

    const ALLOWED_TYPES = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    const MAX_SIZE_MB   = 5;

    function showFile(file) {
        fileError.style.display = 'none';
        dropContent.style.display = 'none';
        selectedName.textContent = file.name;
        selectedInfo.style.display = 'flex';
    }

    function clearFile() {
        fileInput.value = '';
        selectedInfo.style.display = 'none';
        dropContent.style.display = 'block';
        fileError.style.display = 'none';
    }

    function validateAndShow(file) {
        if (!file) return;
        if (!ALLOWED_TYPES.includes(file.type)) {
            fileError.textContent = 'Type de fichier non autorisé. Utilisez PDF, JPG, PNG, GIF ou WEBP.';
            fileError.style.display = 'block';
            clearFile();
            return;
        }
        if (file.size > MAX_SIZE_MB * 1024 * 1024) {
            fileError.textContent = 'Le fichier dépasse la taille maximale de 5 Mo.';
            fileError.style.display = 'block';
            clearFile();
            return;
        }
        showFile(file);
    }

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) validateAndShow(this.files[0]);
    });

    removeBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        clearFile();
    });

    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', function() {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            validateAndShow(file);
        }
    });
    // --- Fin gestion upload ---

    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(contactForm);
        const originalText = submitBtn.innerHTML;

        // Désactiver le bouton et changer le texte
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Envoi en cours...</span><i class="fas fa-spinner fa-spin ms-2"></i>';

        // Masquer les messages précédents
        contactMessage.style.display = 'none';
        contactMessage.className = '';

        fetch('{{ route("contact.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                contactMessage.className = 'alert alert-success';
                contactMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + data.message;
                contactForm.reset();
                clearFile();
            } else {
                contactMessage.className = 'alert alert-danger';
                contactMessage.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + data.message;
            }
        })
        .catch(error => {
            contactMessage.className = 'alert alert-danger';
            contactMessage.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.';
        })
        .finally(() => {
            // Réactiver le bouton et restaurer le texte
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            contactMessage.style.display = 'block';

            // Faire défiler vers le message
            contactMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
});
</script>

@endsection
