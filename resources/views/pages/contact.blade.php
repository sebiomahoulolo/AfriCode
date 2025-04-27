@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')

<section class="contact-section py-5" id="contact">
  <div class="container">
    <h2 class="text-center mb-4">Contactez-nous</h2>
    <p class="text-center mb-5 text-muted">Une question, une collaboration ou une idée à partager ? L’équipe AfriCode est à votre écoute.</p>

    <div class="row g-4">
      <!-- Informations de contact -->
      <div class="col-md-5">
        <div class="card h-100 shadow-sm border-0 p-4">
          <h5 class="mb-3">Informations de contact</h5>
          <p><i class="bi bi-envelope-fill text-primary me-2"></i> contact@africode.org</p>
          <p><i class="bi bi-telephone-fill text-primary me-2"></i> +229 90 00 00 00</p>
          <p><i class="bi bi-geo-alt-fill text-primary me-2"></i> Parakou, Bénin</p>
          <hr>
          <p class="text-muted">Nous répondons généralement sous 24h. N’hésitez pas à nous écrire !</p>
        </div>
      </div>

      <!-- Formulaire de contact -->
      <div class="col-md-7">
        <div class="card shadow-sm border-0 p-4">
          <form action="traitement_contact.php" method="POST">
            <div class="mb-3">
              <label for="name" class="form-label" aria-label="Entrez votre nom complet">Nom complet</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Votre nom" required>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label" aria-label="Entrez votre adresse e-mail">Adresse e-mail</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="exemple@domaine.com" required>
              <small class="form-text text-muted">Nous ne partagerons jamais votre e-mail avec des tiers.</small>
            </div>

            <div class="mb-3">
              <label for="subject" class="form-label" aria-label="Entrez le sujet de votre message">Sujet</label>
              <input type="text" class="form-control" id="subject" name="subject" placeholder="Sujet de votre message" required>
            </div>

            <div class="mb-3">
              <label for="message" class="form-label" aria-label="Entrez votre message">Message</label>
              <textarea class="form-control" id="message" name="message" rows="5" placeholder="Votre message..." required></textarea>
            </div>

            <!-- Ajout d'un CAPTCHA -->
            <div class="mb-3">
              <div class="g-recaptcha" data-sitekey="votre_site_key"></div>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">Envoyer le message</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Bouton Retour en haut -->
<a href="#top" class="btn btn-secondary back-to-top" style="display: none;">⬆ Retour en haut</a>

<!-- CSS Design personnalisé -->
<style>
.contact-section {
  background: #f8f9fa;
}

.contact-section h2 {
  font-weight: 700;
  color: #1d3557;
}

.contact-section .card {
  border-radius: 15px;
}

.contact-section .form-control {
  border-radius: 10px;
}

.contact-section .btn-primary {
  background-color: #1d3557;
  border: none;
  transition: all 0.3s ease-in-out;
}

.contact-section .btn-primary:hover {
  background-color: #e8b100;
  color: #000;
}

.contact-section i {
  font-size: 1.1rem;
}

.back-to-top {
  position: fixed;
  bottom: 20px;
  right: 20px;
  border-radius: 50%;
  padding: 10px 15px;
  background: #1d3557;
  color: #fff;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: background 0.3s ease;
}

.back-to-top:hover {
  background: #e8b100;
}

/* Animation pour le bouton Retour en haut */
.back-to-top {
  display: none;
}

.back-to-top.show {
  display: block;
}
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Script pour le bouton Retour en haut -->
<script>
document.addEventListener("scroll", () => {
  const backToTopButton = document.querySelector(".back-to-top");
  if (window.scrollY > 200) {
    backToTopButton.classList.add("show");
  } else {
    backToTopButton.classList.remove("show");
  }
});
</script>

@endsection
