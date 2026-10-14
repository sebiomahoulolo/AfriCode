@extends('layouts.layout')

@section('title', 'À propos - AfriCode')

@section('content')

<section class="about-section py-5 bg-white" id="about">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">À propos d'AfriCode</h2>
      <p class="text-muted">Découvrez notre mission, notre histoire, notre vision et nos objectifs pour un avenir meilleur.</p>
    </div>

    <div class="row align-items-center mb-5">
      <div class="col-md-6">
        <img src="{{ asset('assets/images/mission-impossible-concept-illustration_114360-569.jpg') }}" alt="Développeurs Africains" class="img-fluid rounded shadow-lg">
      </div>
      <div class="col-md-6">
        <h4 class="fw-semibold text-primary">Notre Mission</h4>
        <p>AfriCode est une initiative née de la volonté de promouvoir l’apprentissage du numérique, du codage et de l'entrepreneuriat digital en Afrique. Nous croyons que chaque jeune africain mérite l’opportunité de maîtriser les outils de demain.</p>
        <p>Grâce à des ressources gratuites, une communauté active et des projets concrets, AfriCode veut inspirer, former et connecter les futurs talents du continent.</p>
      </div>
    </div>

    <div class="row align-items-center mb-5">
      <div class="col-md-6 order-md-2">
        <img src="{{ asset('assets/images/OIP (4) - Copie.jpeg') }}" alt="Équipe AfriCode" class="img-fluid rounded shadow-lg">
      </div>
      <div class="col-md-6 order-md-1">
        <h4 class="fw-semibold text-primary">Notre Vision</h4>
        <p>Nous imaginons un continent africain où la technologie et le codage sont des outils essentiels pour résoudre des problèmes locaux, stimuler l'innovation et transformer les communautés. Notre vision est de bâtir une Afrique autonome, connectée et leader mondial dans l'écosystème numérique.</p>
      </div>
    </div>

    <div class="row align-items-center mb-5">
      <div class="col-md-12 text-center">
        <h4 class="fw-semibold text-primary">Nos Objectifs</h4>
        <p class="text-muted">AfriCode aspire à atteindre les objectifs suivants :</p>
        <ul class="list-unstyled text-start text-muted mx-auto" style="max-width: 800px;">
          <li>✔ Former plus de 100,000 jeunes africains aux outils numériques d'ici 2030.</li>
          <li>✔ Créer un réseau de communautés technologiques dynamiques sur tout le continent.</li>
          <li>✔ Encourager l'innovation locale à travers des hackathons et des projets concrets.</li>
          <li>✔ Favoriser l'accès aux opportunités globales pour les talents africains dans le domaine numérique.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- CSS Design personnalisé -->
<style>
.about-section {
  background-color: #f9f9f9;
}

.about-section h2 {
  font-size: 2.5rem;
  color: #1d3557;
}

.about-section h4 {
  font-size: 1.5rem;
  color: #1d3557;
  font-weight: 600;
}

.about-section p {
  font-size: 1.1rem;
  color: #444;
  line-height: 1.8;
}

.about-section ul {
  font-size: 1.1rem;
  color: #6c757d;
  line-height: 1.8;
}

.about-section .img-fluid {
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
</style>


    <hr class="my-5">
<section class="values-section py-5 bg-light">
  <div class="container">
    <h3 class="text-center text-primary fw-bold mb-4">Nos Valeurs</h3>
    <div class="row g-4">
      <!-- Accessibilité pour tous -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <i class="bi bi-universal-access text-primary mb-3" style="font-size: 3rem;"></i>
          <h5 class="mb-2">Accessibilité</h5>
          <p class="text-muted">Nous veillons à ce que la technologie soit accessible à tous, sans barrière.</p>
        </div>
      </div>
      <!-- Partage de connaissances -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <i class="bi bi-book-fill text-primary mb-3" style="font-size: 3rem;"></i>
          <h5 class="mb-2">Partage</h5>
          <p class="text-muted">Nous encourageons l'échange de connaissances pour grandir ensemble.</p>
        </div>
      </div>
      <!-- Innovation locale et durable -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <i class="bi bi-lightbulb-fill text-primary mb-3" style="font-size: 3rem;"></i>
          <h5 class="mb-2">Innovation</h5>
          <p class="text-muted">Nous croyons au pouvoir de l'innovation pour relever les défis locaux.</p>
        </div>
      </div>
      <!-- Communauté -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <i class="bi bi-people-fill text-primary mb-3" style="font-size: 3rem;"></i>
          <h5 class="mb-2">Communauté</h5>
          <p class="text-muted">Nous construisons une communauté solidaire, où les talents collaborent pour résoudre les défis de demain.</p>
        </div>
      </div>
      <!-- Impact global -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <i class="bi bi-globe text-primary mb-3" style="font-size: 3rem;"></i>
          <h5 class="mb-2">Impact global</h5>
          <p class="text-muted">Nous aspirons à transformer le continent et le monde grâce à des solutions durables et inclusives.</p>
        </div>
      </div>
      <!-- Leadership jeune et responsable -->
      <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100 text-center p-4">
          <i class="bi bi-award-fill text-primary mb-3" style="font-size: 3rem;"></i>
          <h5 class="mb-2">Leadership</h5>
          <p class="text-muted">Nous valorisons les jeunes leaders pour un avenir responsable et durable.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS Design personnalisé -->
<style>
.values-section h3 {
  font-size: 2rem;
  font-weight: 700;
}

.values-section i {
  font-size: 3rem; /* Taille uniforme des icônes */
  color: #1d3557;
}

.values-section h5 {
  font-weight: 600;
  color: #1d3557;
}

.values-section p {
  color: #444;
  line-height: 1.6;
}

.values-section .card {
  border-radius: 15px;
  background: #ffffff;
}
</style>
</section>
<!-- CSS Design personnalisé -->
<style>
.about-section {
  background: #f8f9fa;
}

.about-section h2 {
  font-weight: 700;
  color: #1d3557;
}

.about-section .card {
  border-radius: 15px;
  overflow: hidden;
}

.about-section img {
  max-width: 100%;
  height: auto;
}

.about-section i {
  font-size: 2.5rem;
  color: #1d3557;
}

.about-section h5 {
  font-weight: 600;
  color: #1d3557;
}

.about-section p {
  line-height: 1.6;
}
</style>



<section class="testimonials-section py-5 bg-light">
  <div class="container">
    <h3 class="text-center text-primary mb-5">Ce qu'ils disent de nous</h3>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 shadow-sm h-100 text-center">
          <p class="text-muted">"AfriCode m'a donné les outils nécessaires pour lancer ma start-up digitale."</p>
          <h5 class="fw-bold text-primary">Jean K.</h5>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 shadow-sm h-100 text-center">
          <p class="text-muted">"Une expérience transformatrice qui m'a appris à coder et à collaborer."</p>
          <h5 class="fw-bold text-primary">Fatou D.</h5>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 shadow-sm h-100 text-center">
          <p class="text-muted">"Le soutien d'AfriCode est incroyable, je me sens prêt à conquérir le monde digital."</p>
          <h5 class="fw-bold text-primary">Youssouf B.</h5>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="faq-section py-5 bg-white" id="faq">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold text-primary">Foire aux Questions (FAQ)</h2>
      <p class="text-muted">Trouvez rapidement des réponses à vos questions les plus fréquentes.</p>
    </div>

    <div class="accordion" id="faqAccordion">
      <!-- Question 1 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading1">
          <button class="accordion-button fw-bold text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="true" aria-controls="faqCollapse1">
            Qu'est-ce qu'AfriCode et que proposez-vous ?
          </button>
        </h2>
        <div id="faqCollapse1" class="accordion-collapse collapse show" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            AfriCode est une initiative visant à promouvoir l'apprentissage du numérique, du codage et de l'entrepreneuriat digital en Afrique. Nous proposons des formations, des ressources gratuites et des hackathons pour inspirer et connecter les talents du continent.
          </div>
        </div>
      </div>

      <!-- Question 2 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading2">
          <button class="accordion-button fw-bold text-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
            Qui peut rejoindre AfriCode ?
          </button>
        </h2>
        <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            AfriCode est ouvert à tous ceux qui souhaitent apprendre, enseigner ou innover dans le domaine du numérique, quel que soit leur niveau. Que vous soyez débutant, étudiant ou professionnel, vous êtes le bienvenu.
          </div>
        </div>
      </div>

      <!-- Question 3 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading3">
          <button class="accordion-button fw-bold text-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
            Quels types de formations proposez-vous ?
          </button>
        </h2>
        <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Nous proposons des formations en programmation, développement web, intelligence artificielle, entrepreneuriat digital et bien plus encore. Nos formations sont adaptées aux besoins du marché et conçues pour aider les participants à acquérir des compétences pratiques.
          </div>
        </div>
      </div>

      <!-- Question 4 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading4">
          <button class="accordion-button fw-bold text-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
            Comment puis-je m'inscrire à une formation ?
          </button>
        </h2>
        <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Vous pouvez vous inscrire via notre site web en visitant la page <a href="/inscription" class="text-primary">Inscription</a>. Remplissez le formulaire et choisissez la formation qui vous intéresse. Nous vous contacterons pour finaliser votre inscription.
          </div>
        </div>
      </div>

      <!-- Question 5 -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="faqHeading5">
          <button class="accordion-button fw-bold text-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
            Organisez-vous des événements ou des hackathons ?
          </button>
        </h2>
        <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordion">
          <div class="accordion-body">
            Oui, nous organisons régulièrement des événements, des ateliers et des hackathons pour stimuler l'innovation et permettre aux participants de mettre en pratique leurs compétences. Consultez notre section <a href="/evenements" class="text-primary">Événements</a> pour connaître les prochaines dates.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS Design personnalisé -->
<style>
.faq-section h2 {
  font-size: 2.5rem;
  color: #1d3557;
  font-weight: 700;
}

.accordion-button {
  font-size: 1.2rem;
}

.accordion-body {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #444;
}

.about-section {
  background-color: #fff;
}

.about-section h2 {
  font-size: 2.5rem;
  color: #1d3557;
}

.about-section h4, .stats-section h3, .testimonials-section h3 {
  color: #1d3557;
}

.about-section p, .about-section li {
  color: #444;
  font-size: 1.05rem;
}

.about-section ul {
  padding-left: 1.2rem;
}

.stats-section h4 {
  font-size: 2rem;
  font-weight: bold;
}

img.shadow {
  box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
}

.testimonials-section p {
  font-style: italic;
}

.video-section video {
  max-width: 100%;
  height: auto;
}
</style>

<!-- Intégration AOS pour les animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<!-- Bootstrap Accordion -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  AOS.init({
    duration: 1200,
    once: true,
  });
</script>

@endsection
