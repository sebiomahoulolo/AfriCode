@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .header {
      background-color: #343a40;
      color: #fff;
      padding: 40px 0;
      text-align: center;
    }
    .section-heading {
      margin-top: 40px;
      margin-bottom: 20px;
      color: #343a40;
    }
    .card {
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

  <!-- En-tête de la page -->
<header class="header">
  <div class="container">
    <h1>Découvrez nos cours</h1>
    <p>Des contenus adaptés aux débutants</p>
  
      <div class="images-container">
    <img src="{{ asset('assets/images/OIP (4).jpeg') }}" class="header-img" alt="Image 1">
    <img src="{{ asset('assets/images/speed-website.jpg') }}" class="header-img" alt="Image 2">
    <img src="{{ asset('assets/images/website-loading-speed-optimization-illustration-vector.jpg') }}" class="header-img" alt="Image 3">
    <img src="{{ asset('assets/images/chargement-web.jpg') }}" class="header-img" alt="Image 4">
    <img src="{{ asset('assets/images/th.jpeg') }}" class="header-img" alt="Image 5">
  
  </div>
</header>

<style>
  .header {
    text-align: center;
    position: relative;
    padding: 30px 0; /* Réduit la hauteur du header */
    background-color:rgb(175, 175, 175); /* Ajoute une couleur de fond claire */
  }

  .header .container {
    position: relative;
    z-index: 2;
  }

  .header h1 {
    font-size: 2rem; /* Réduit la taille du titre */
    margin-bottom: 10px; /* Réduit l'espace sous le titre */
  }

  .header p {
    font-size: 1rem; /* Réduit la taille du sous-titre */
    margin-bottom: 20px; /* Réduit l'espace sous le sous-titre */
  }

  .images-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    overflow: hidden;
  }

  .header-img {
    width: 200px; /* Réduit la taille des images */
    height: auto;
    margin: 0 10px; /* Réduit l'espace entre les images */
    opacity: 0;
    animation: fadeIn 10s infinite;
  }

  /* Animation pour les images */
  @keyframes fadeIn {
    0%, 20%, 100% {
      opacity: 0;
    }
    10%, 30%, 50%, 70%, 90% {
      opacity: 1;
    }
  }

  .header-img:nth-child(1) {
    animation-delay: 0s;
  }

  .header-img:nth-child(2) {
    animation-delay: 3s;
  }

  .header-img:nth-child(3) {
    animation-delay: 6s;
  }

.header-img:nth-child(4) {
    animation-delay: 9s;
  }
  .header-img:nth-child(5) {
    animation-delay: 12s;
  }
</style>


  
  <div class="container my-5">
    
  <!-- Section pour Débutants -->
<section id="debutants">
    <hr>
    <div class="row">
        <!-- Carte de cours n°1 pour débutants -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/OIP (23).jpeg') }}" class="card-img-top" alt="Introduction à la programmation">
                <div class="card-body">
                    <h5 class="card-title">Introduction à la programmation</h5>
                    <p class="card-text">Un cours qui vous présente les bases de la programmation et la logique algorithmique de façon claire et progressive.</p>
                    <p class="text-success"><strong>Gratuit</strong> | Certificat inclus | <span class="text-danger">8h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>

        <!-- Carte de cours n°2 pour débutants -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/maxresdefault.jpg') }}" class="card-img-top" alt="Les bases du Web">
                <div class="card-body">
                    <h5 class="card-title">Les bases du Web</h5>
                    <p class="card-text">Apprenez HTML, CSS et JavaScript pour construire vos premières pages web de manière interactive.</p>
                    <p class="text-success"><strong>Gratuit</strong> | Pas de certificat | <span class="text-danger">12h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>

        <!-- Carte de cours n°3 pour débutants -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/Laravel.jpeg') }}" class="card-img-top" alt="Initiation à Laravel">
                <div class="card-body">
                    <h5 class="card-title">Initiation à Laravel</h5>
                    <p class="card-text">Découvrez ce framework PHP puissant et facile à apprendre pour développer des applications web.</p>
                    <p class="text-success"><strong>25$</strong> | Certificat inclus | <span class="text-danger">20h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</section>

   <hr> 
<!-- Section pour Débutants -->
<section id="debutants" class="mt-5">
    <div class="row mb-4"> 
        @foreach ([
            ['Algorithmique et Logique', "Apprenez les fondamentaux de l'algorithmique et développez votre logique.", 'introduction-l-algorithmique1-l.jpg', 'Gratuit', 'Certificat inclus', '12h'],
            ['Git et GitHub', 'Maîtrisez le versionnement de code avec Git et GitHub.', 'git.jpg', 'Gratuit', 'Pas de certificat', '8h'],
            ['Introduction au Réseau Informatique', "Découvrez les bases du réseau et des communications entre machines.", 'course_dereseaux.jpg', '15$', 'Certificat inclus', '6h'],
            ['Programmation Python', 'Apprenez la programmation avec Python, un langage polyvalent.', 'pyton.jpg', '20$', 'Certificat inclus', '25h'],
            ['Bases de la base de données', 'Introduction à MySQL et à la gestion des bases de données.', 'lesbases.jpg', 'Gratuit', 'Pas de certificat', '45h'],
            ['Développement Frontend', 'Maîtrisez Bootstrap et Tailwind CSS pour concevoir des interfaces modernes.', 'frontend2.jpeg', '25$', 'Certificat inclus', '80h'],
            ['Cours de Word', 'Apprenez à maîtriser Microsoft Word pour la bureautique.', 'OIP (5).jpeg', '18$', 'Certificat inclus', '9h'],
            ['Cours d’Excel', 'Développez vos compétences en analyse de données avec Excel.', 'maxresdefault (5).jpg', '15$', 'Certificat inclus', '16h']
        ] as $cours)
        <div class="col-md-3 mb-4"> 
            <div class="card h-100 shadow-sm" style="height: 100%;"> 
                <img src="{{ asset('assets/images/' . $cours[2]) }}" class="card-img-top" alt="{{ $cours[0] }}" style="height: 120px; object-fit: cover;"> 
                <div class="card-body d-flex flex-column" style="height: 100%;"> 
                    <h5 class="card-title" style="font-size: 1.1rem; height: 50px; overflow: hidden;">{{ $cours[0] }}</h5> 
                    <p class="card-text" style="font-size: 0.9rem; height: 60px; overflow: hidden;;" title="{{ $cours[1] }}">{{ $cours[1] }}</p> 
                    <p class="text-success" style="font-size: 1rem;"><strong>{{ $cours[3] }}</strong> | {{ $cours[4] }} | <span class="text-danger"> {{ $cours[5] }}</span></p>
                    <a href="#" class="btn btn-primary btn-hover mt-auto" style="font-size: 0.9rem;">S'inscrire</a> 
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
</div>  
@endsection

<style>
    .btn-hover:hover {
        background-color: yellow !important;
        color: black !important;
    }
    .card {
        width: 100%; 
        max-width: 400px; 
        margin: 0 auto;
        overflow: hidden; 
    }
    .card-body {
        padding: 1rem; 
        display: flex;
        flex-direction: column;
        justify-content: space-between; 
    }
   
</style>




  
   </div>
  
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
