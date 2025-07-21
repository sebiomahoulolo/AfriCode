@extends('layouts.layout')

@section('title', 'Contact - AfriCode')

@section('content')

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
              <div class="contact-icon">📧</div>
              <div class="contact-details">
                <h5>Email</h5>
                <p>contact@africode.tech</p>
              </div>
            </div>
            
            <div class="contact-card">
              <div class="contact-icon">📱</div>
              <div class="contact-details">
                <h5>Téléphone</h5>
                <p>+229 90 00 00 00</p>
              </div>
            </div>
            
            <div class="contact-card">
              <div class="contact-icon">📍</div>
              <div class="contact-details">
                <h5>Adresse</h5>
                <p>Parakou, Bénin</p>
              </div>
            </div>
            
            <div class="contact-card">
              <div class="contact-icon">⏰</div>
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
            <form action="#" method="POST" class="modern-form">
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

              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="newsletter">
                  <label class="form-check-label" for="newsletter">
                    Je souhaite recevoir la newsletter AfriCode
                  </label>
                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn modern-btn">
                  <span>Envoyer le message</span>
                  <i class="fas fa-paper-plane ms-2"></i>
                </button>
              </div>
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
        <div class="social-icon linkedin">💼</div>
        <h5>LinkedIn</h5>
        <p>Actualités professionnelles</p>
        <a href="#" class="social-link">Suivre</a>
      </div>
      
      <div class="social-card">
        <div class="social-icon twitter">🐦</div>
        <h5>Twitter</h5>
        <p>Actualités en temps réel</p>
        <a href="#" class="social-link">Suivre</a>
      </div>
      
      <div class="social-card">
        <div class="social-icon youtube">📺</div>
        <h5>YouTube</h5>
        <p>Tutoriels et formations</p>
        <a href="#" class="social-link">S'abonner</a>
      </div>
      
      <div class="social-card">
        <div class="social-icon github">💻</div>
        <h5>GitHub</h5>
        <p>Projets open source</p>
        <a href="#" class="social-link">Explorer</a>
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
          <h5>🚀 Comment rejoindre AfriCode ?</h5>
          <p>Inscrivez-vous simplement sur notre plateforme et choisissez les formations qui vous intéressent.</p>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="faq-quick-card">
          <h5>💰 Les formations sont-elles gratuites ?</h5>
          <p>Oui, la plupart de nos formations de base sont entièrement gratuites pour tous les participants.</p>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="faq-quick-card">
          <h5>🌍 Où se déroulent les formations ?</h5>
          <p>Nos formations sont disponibles en ligne et en présentiel dans plusieurs villes d'Afrique.</p>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="faq-quick-card">
          <h5>📜 Y a-t-il des certifications ?</h5>
          <p>Oui, nous délivrons des certifications reconnues à la fin de chaque parcours de formation.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS moderne unifié -->
<style>
/* Variables CSS pour consistance */
:root {
  --primary-blue: #1d3557;
  --secondary-orange: #e8b100;
  --light-blue: #a8dadc;
  --dark-blue: #2e4057;
  --gradient: linear-gradient(135deg, #1d3557 0%, #2e4057 50%, #e8b100 100%);
  --shadow: 0 15px 35px rgba(29, 53, 87, 0.1);
  --border-radius: 25px;
}

/* Hero Section */
.contact-hero {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  position: relative;
  overflow: hidden;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  color: var(--primary-blue);
  line-height: 1.2;
}

.text-gradient {
  background: var(--gradient);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.hero-subtitle {
  font-size: 1.3rem;
  color: #6c757d;
  line-height: 1.6;
}

.floating-badges {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.tech-badge {
  background: rgba(29, 53, 87, 0.1);
  color: var(--primary-blue);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
  border: 1px solid rgba(29, 53, 87, 0.2);
  transition: all 0.3s ease;
}

.tech-badge:hover {
  background: var(--primary-blue);
  color: white;
  transform: translateY(-2px);
}

/* Conteneurs organiques */
.hero-image-container {
  position: relative;
  height: 100%;
}

.organic-container {
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
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
  background: radial-gradient(circle, var(--primary-blue) 2px, transparent 2px);
  background-size: 20px 20px;
  opacity: 0.3;
}

.wavy-circle {
  position: absolute;
  bottom: 30px;
  right: 30px;
  width: 80px;
  height: 80px;
  border: 3px solid var(--secondary-orange);
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
    var(--light-blue) 10px,
    var(--light-blue) 12px
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
  color: var(--primary-blue);
  margin-bottom: 1.5rem;
}

.text-content {
  font-size: 1.1rem;
  color: #6c757d;
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
  background: linear-gradient(135deg, #f8f9fa 0%, white 100%);
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
  border: 1px solid rgba(29, 53, 87, 0.1);
}

.contact-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(29, 53, 87, 0.15);
}

.contact-icon {
  font-size: 2.5rem;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--gradient);
  border-radius: 50%;
  flex-shrink: 0;
}

.contact-details h5 {
  color: var(--primary-blue);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.contact-details p {
  color: #6c757d;
  margin: 0;
  font-size: 1rem;
}

/* Formulaire moderne */
.contact-form-container {
  position: relative;
}

.form-title {
  color: var(--primary-blue);
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
  color: var(--primary-blue);
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.modern-input {
  padding: 1rem;
  border: 2px solid rgba(29, 53, 87, 0.1);
  border-radius: 15px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: rgba(248, 249, 250, 0.5);
}

.modern-input:focus {
  outline: none;
  border-color: var(--primary-blue);
  box-shadow: 0 0 0 3px rgba(29, 53, 87, 0.1);
  background: white;
}

.modern-btn {
  background: var(--gradient);
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 25px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  align-self: flex-start;
}

.modern-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 30px rgba(29, 53, 87, 0.3);
}

/* Section réseaux sociaux */
.social-section {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.social-cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
  max-width: 800px;
  margin: 0 auto;
}

.social-card {
  background: white;
  padding: 2rem;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  text-align: center;
  transition: all 0.3s ease;
  border: 1px solid rgba(29, 53, 87, 0.1);
}

.social-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 25px 50px rgba(29, 53, 87, 0.15);
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
  background: var(--gradient);
}

.social-card h5 {
  color: var(--primary-blue);
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.social-card p {
  color: #6c757d;
  margin-bottom: 1.5rem;
}

.social-link {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: rgba(29, 53, 87, 0.1);
  color: var(--primary-blue);
  text-decoration: none;
  border-radius: 20px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.social-link:hover {
  background: var(--primary-blue);
  color: white;
  transform: translateY(-2px);
}

/* Section FAQ rapide */
.quick-faq-section {
  background: white;
}

.faq-quick-card {
  background: linear-gradient(135deg, #f8f9fa 0%, white 100%);
  padding: 2rem;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
  border: 1px solid rgba(29, 53, 87, 0.1);
  height: 100%;
}

.faq-quick-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(29, 53, 87, 0.15);
}

.faq-quick-card h5 {
  color: var(--primary-blue);
  font-weight: 600;
  margin-bottom: 1rem;
}

.faq-quick-card p {
  color: #6c757d;
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
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
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

@endsection
