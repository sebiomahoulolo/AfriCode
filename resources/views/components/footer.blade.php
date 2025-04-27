<style>
    .footer {
        background-color: #1e3a8a;
    }
    
    
</style>


<footer class=" text-light text-center " style="background-color: #1e3a8a;">
    <div class="container">
        <div class="row">
            <!-- Section Africode -->
            <div class="col-lg-4 col-md-6 mb-4">
<h5 class="text-uppercase fw-bold">
    <i class="fas fa-code" style="margin-right: 8px; margin-left: -5px;"></i>
    AfriCode
</h5>

                <p>
                    Africode est votre plateforme de choix pour apprendre la programmation et devenir un expert en informatique.  
                    Rejoignez notre communauté dès aujourd'hui !
                </p>
            </div>

            <!-- Liens utiles -->
<!-- Liens utiles -->
<div class="col-lg-4 col-md-12 mb-4">
    <h5 class="text-uppercase fw-bold">Liens utiles</h5>
    <ul class="list-unstyled" style="display: flex; gap: 15px; flex-wrap: wrap;">
        <li>
            <a href="{{ route('pages.coursD') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-chalkboard-teacher me-2"></i>Cours débutant
            </a>
        </li>
        <li>
            <a href="{{ route('pages.coursT') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-laptop-code me-2"></i>Cours intermédiaire
            </a>
        </li>
        <li>
            <a href="{{ route('pages.coursE') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-tools me-2"></i>Cours expert
            </a>
        </li>
        <li>
            <a href="#" class="text-light text-decoration-none link-animated">
                <i class="fas fa-graduation-cap me-2"></i>Cours gratuit
            </a>
        </li>
        <li>
            <a href="#" class="text-light text-decoration-none link-animated">
                <i class="fas fa-award me-2"></i>Cours certifiant
            </a>
        </li>
        <li>
            <a href="{{ route('pages.compdisp') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-trophy me-2"></i>Compétitions disponibles
            </a>
        </li>
        <li>
            <a href="{{ route('pages.test') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-clipboard-check me-2"></i>Test de niveau
            </a>
        </li>
        <li>
            <a href="{{ route('pages.verifier') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-certificate me-2"></i>Vérifier un certificat
            </a>
        </li>
        <li>
            <a href="{{ route('pages.forumexp') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-users me-2"></i>Forum des experts
            </a>
        </li>
        <li>
            <a href="{{ route('pages.forumapp') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-user-graduate me-2"></i>Forum des apprenants
            </a>
        </li>
    </ul>
</div>

<style>
/* Animation de soulignement */
.link-animated {
    position: relative;
    display: inline-block;
    padding-bottom: 3px;
    transition: color 0.3s ease;
}

.link-animated::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 100%;
    width: 0;
    height: 2px;
    background-color: yellow;
    transition: all 0.4s ease;
}

.link-animated:hover::after,
.link-animated:focus::after {
    left: 0;
    width: 100%;
}

/* Changement de couleur au survol */
.link-animated:hover {
    color: #ffc107; /* Jaune clair */
}
</style>




 <!-- Réseaux sociaux -->
<div class="col-lg-4 col-md-12 mb-4">
    <h5 class="text-uppercase fw-bold">Suivez-nous</h5>
    <div class="d-flex justify-content-center">
        <a href="#" class="btn btn-outline-light btn-floating m-1" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="btn btn-outline-light btn-floating m-1" title="Twitter"><i class="fab fa-twitter"></i></a>
        <a href="#" class="btn btn-outline-light btn-floating m-1" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="#" class="btn btn-outline-light btn-floating m-1" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        <a href="#" class="btn btn-outline-light btn-floating m-1" title="YouTube"><i class="fab fa-youtube"></i></a> <!-- Ajout du bouton YouTube -->
        <a href="#" class="btn btn-outline-light btn-floating m-1" title="WhatsApp"><i class="fab fa-whatsapp"></i></a> <!-- Ajout du bouton WhatsApp -->
   
    </div>  <br><br>
    <ul>
            <a href="{{ url('/') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-home me-2"></i>Accueil
            </a>
            <a href="{{ route('pages.apropos') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-info-circle me-2"></i>À propos
            </a>
            <a href="{{ route('pages.contact') }}" class="text-light text-decoration-none link-animated">
                <i class="fas fa-envelope me-2"></i>Contact
            </a>
    </ul>
    
</div>
</div>

    <!-- Copyright -->
    <div class="text-center p-3 bg-light text-dark">
        © 2025 AfriCode : No code, No future - Tous droits réservés
    </div>
</footer>

<!-- Bootstrap & Font Awesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
