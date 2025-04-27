@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')
    <!-- Jumbotron avec carrousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up" style="margin-top: -15px;" >
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
    <div class="carousel-item active" style="background-image: url('{{ asset('assets/images/télécharger.jpeg') }}'); background-size: contain;">
        <div class="jumbotron text-center text-white py-5" style="background-color: rgba(83, 105, 87, 0.77); min-height: 250px;">
            <h1 class="display-4 fw-bold">Bienvenue sur AfriCode</h1>
            <p class="lead">Découvrez nos cours de programmation de haute qualité pour devenir un expert en informatique.</p>
            <a class="btn btn-light btn-lg shadow" href="#" role="button">Voir les cours</a>
        </div>
    </div>

    <div class="carousel-item" style="background-image: url('{{ asset('assets/images/th.jpeg') }}'); background-size: contain;">
    <div class="jumbotron text-center text-white py-5" style="background-color: rgba(58, 55, 117, 0.58); min-height: 250px;">
                    <h1 class="display-4 fw-bold">Apprenez avec des experts</h1>
                    <p class="lead">Nos formateurs sont des professionnels du domaine avec des années d'expérience.</p>
                    <a class="btn btn-warning btn-lg shadow" href="#" role="button">Commencer maintenant</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('{{ asset('assets/images/th (4).jpeg') }}'); background-size: contain;">
            <div class="jumbotron text-center text-white py-5" style="background-color: rgba(34, 33, 53, 0.58); min-height: 250px;">
                    <h1 class="display-4 fw-bold">Rejoignez notre communauté</h1>
                    <p class="lead">Des milliers d'étudiants apprennent déjà avec Africode.</p>
                    <a class="btn btn-light btn-lg shadow" href="#" role="button">Inscrivez-vous</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections principales avec effet hover -->
<div class="container my-5">
    <div class="row">
        <div class="col-md-4" data-aos="fade-right">
            <div class="card text-center shadow-lg border-0 hover-effect"> <!-- shadow-lg pour une ombre plus marquée -->
                <div class="card-body">
                    <i class="fas fa-laptop-code fa-3x text-primary mb-3"></i>
                    <h3 class="fw-bold">Cours Populaires</h3>
                    <p>Explorez nos cours populaires et commencez à apprendre.
                    </p>
                    <a class="btn btn-outline-primary" href="#">Voir plus</a>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-up">
            <div class="card text-center shadow-lg border-0 hover-effect"> <!-- shadow-lg pour une ombre plus marquée -->
                <div class="card-body">
                    <i class="fas fa-bell fa-3x text-warning mb-3"></i>
                    <h3 class="fw-bold">Nouveautés</h3>
                    <p>Restez à jour avec les derniers ajouts et mises à jour de cours.</p>
                    <a class="btn btn-outline-warning" href="#">Voir plus</a>
                </div>
            </div>
        </div>

        <div class="col-md-4" data-aos="fade-left">
            <div class="card text-center shadow-lg border-0 hover-effect"> <!-- shadow-lg pour une ombre plus marquée -->
                <div class="card-body">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h3 class="fw-bold">Témoignages</h3>
                    <p>Lisez ce que nos étudiants disent de nos cours et de notre plateforme.</p>
                    <a class="btn btn-outline-success" href="#">Voir plus</a>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Section Pourquoi choisir Africode -->
    <div class="container my-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold" data-aos="fade-up">Pourquoi choisir AfriCode ?</h2>
            <p class="text-muted">Notre engagement pour votre réussite</p>
        </div>
        <div class="row text-center">
            <div class="col-md-3" data-aos="zoom-in">
                <i class="fas fa-certificate fa-3x text-primary"></i>
                <h4 class="mt-2">Certifications</h4>
                <p>Obtenez des certificats reconnus après chaque formation.</p>
                <p style="color: rgb(26, 20, 182);"><span class="counter" data-min="45" data-max="120">45</span>+ étudiants certifiés</p>
            </div>
            <div class="col-md-3" data-aos="zoom-in">
                <i class="fas fa-chalkboard-teacher fa-3x text-success"></i>
                <h4 class="mt-2">Formateurs Experts</h4>
                <p>Apprenez avec des professionnels du domaine.</p>
                <p style="color:rgb(11, 115, 11);" ><span class="counter" data-min="2" data-max="15">15</span>+ formateurs qualifiés</p>

            </div>
            <div class="col-md-3" data-aos="zoom-in">
                <i class="fas fa-users fa-3x text-warning"></i>
                <h4 class="mt-2">Communauté Active</h4>
                <p>Rejoignez un réseau d'apprenants et d'experts.</p>
                <p style="color: rgb(209, 183, 14);"><span class="counter" data-min="90" data-max="325">90</span>+ membres actifs</p>
            </div>
            
            <div class="col-md-3" data-aos="zoom-in">
                <i class="fas fa-handshake fa-3x text-danger"></i>
                <h4 class="mt-2">Accompagnement</h4>
                <p>Un suivi personnalisé pour vous aider à réussir.</p>
                <p style="color: rgb(215, 46, 16);" ><span class="counter" data-min="150" data-max="600">150</span>+ étudiants accompagnés</p>
            </div>
        </div>
    </div>

    <!-- Styles et Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <style>
        .hover-effect:hover {
            transform: translateY(-5px);
            transition: 0.3s ease-in-out;
        }
    </style>
    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const counters = document.querySelectorAll(".counter");
            counters.forEach(counter => {
                const min = parseInt(counter.getAttribute("data-min"));
                const max = parseInt(counter.getAttribute("data-max"));
                let start = min;
                const increment = Math.ceil((max - min) / 100);

                const updateCounter = () => {
                    if (start < max) {
                        start += increment;
                        counter.innerText = start;
                        setTimeout(updateCounter, 20);
                    } else {
                        counter.innerText = max;
                    }
                };
                updateCounter();
            });
        });
    </script>
@endsection
