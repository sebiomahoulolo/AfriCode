@extends('layouts.layout')

@section('title', 'Conditions d\'utilisation - AfriCode')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header  text-white text-center py-4" style="background-color:  #1EA38B">
                    <h2 class="mb-0">Conditions d'utilisation</h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <h4 class="mb-3 ">1. Présentation de la plateforme</h4>
                    <p>AfriCode est une plateforme de formation numérique dédiée à l'Afrique, proposant des cours, certifications, événements et ressources pour développer les compétences tech de demain.</p>

                    <h4 class="mb-3 ">2. Acceptation des conditions</h4>
                    <p>En créant un compte ou en utilisant nos services, vous acceptez sans réserve les présentes conditions d'utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser la plateforme.</p>

                    <h4 class="mb-3 ">3. Inscription et compte utilisateur</h4>
                    <ul>
                        <li>Vous devez fournir des informations exactes lors de l'inscription.</li>
                        <li>Vous êtes responsable de la confidentialité de votre mot de passe.</li>
                        <li>Vous vous engagez à ne pas usurper l'identité d'autrui.</li>
                    </ul>

                    <h4 class="mb-3 ">4. Utilisation des contenus</h4>
                    <ul>
                        <li>Les contenus (cours, vidéos, quiz, etc.) sont protégés par le droit d'auteur.</li>
                        <li>Vous pouvez utiliser les ressources à des fins personnelles et non commerciales.</li>
                        <li>La reproduction, diffusion ou vente sans autorisation est interdite.</li>
                    </ul>

                    <h4 class="mb-3">5. Comportement sur la plateforme</h4>
                    <ul>
                        <li>Respectez les autres membres et l'équipe AfriCode.</li>
                        <li>Tout propos haineux, discriminatoire ou frauduleux entraînera la suspension du compte.</li>
                        <li>Le spam et la publicité non autorisée sont interdits.</li>
                    </ul>

                    <h4 class="mb-3 ">6. Certifications et attestations</h4>
                    <p>Les certificats délivrés sont soumis à la réussite des évaluations et au respect des règles d'intégrité. Toute tentative de triche entraînera l'annulation du certificat.</p>

                    <h4 class="mb-3 ">7. Données personnelles</h4>
                    <p>Vos données sont traitées conformément à notre <a href="{{ route('pages.privacy') }}" class="text-decoration-underline">politique de confidentialité</a>. Vous pouvez demander la suppression de votre compte à tout moment.</p>

                    <h4 class="mb-3 ">8. Modification des conditions</h4>
                    <p>AfriCode se réserve le droit de modifier les présentes conditions à tout moment. Les utilisateurs seront informés des changements importants par email ou notification sur la plateforme.</p>

                    <h4 class="mb-3 ">9. Contact</h4>
                    <p>Pour toute question, contactez-nous à <a href="mailto:contact@africode.tech">contact@africode.tech</a> ou via le formulaire de contact.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 