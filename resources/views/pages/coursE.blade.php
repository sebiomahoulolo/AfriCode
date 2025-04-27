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
    <p>Des contenus adaptés aux Expert</p>

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
        <!-- Nouveau cours n°7 : Développement de Jeux Vidéo -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/game_dev.jpg') }}" class="card-img-top" alt="Développement de Jeux Vidéo">
                <div class="card-body">
                    <h5 class="card-title">Développement de Jeux Vidéo</h5>
                    <p class="card-text">Apprenez à créer des jeux en 2D et 3D avec Unity et Unreal Engine. Comprenez les bases du game design et de la programmation interactive.</p>
                    <p class="text-success"><strong>50$</strong> | Certificat inclus | <span class="text-danger">25h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>

        <!-- Nouveau cours n°8 : Marketing Digital -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/digital_marketing.jpg') }}" class="card-img-top" alt="Marketing Digital">
                <div class="card-body">
                    <h5 class="card-title">Marketing Digital</h5>
                    <p class="card-text">Découvrez les stratégies pour réussir dans le marketing en ligne, la gestion des réseaux sociaux, et l’optimisation SEO.</p>
                    <p class="text-success"><strong>40$</strong> | Certificat inclus | <span class="text-danger">20h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>

        <!-- Nouveau cours n°9 : Création de Contenu Vidéo -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/content_creation.jpg') }}" class="card-img-top" alt="Création de Contenu Vidéo">
                <div class="card-body">
                    <h5 class="card-title">Création de Contenu Vidéo</h5>
                    <p class="card-text">Apprenez à créer et éditer des vidéos engageantes pour YouTube, TikTok, et d’autres plateformes avec des outils comme Premiere Pro.</p>
                    <p class="text-success"><strong>45$</strong> | Certificat inclus | <span class="text-danger">18h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</section>
  


   <hr> 
<!-- Section pour Experts -->
<!-- Section pour Débutants -->
<section id="debutants" class="mt-5">
    <div class="row mb-4"> 
        @foreach ([
            ['Architecture Logicielle Avancée', 'Concevez des systèmes logiciels complexes en utilisant des modèles comme DDD, CQRS et Clean Architecture.', 'architecture_logicielle.jpg', '120$', 'Certificat inclus', '85h'],
            ['Deep Learning', 'Explorez les réseaux neuronaux profonds et construisez des modèles avec TensorFlow et PyTorch.', 'deep_learning.jpg', '150$', 'Certificat inclus', '120h'],
            ['Sécurité Informatique et Ethical Hacking', 'Apprenez à effectuer des tests d’intrusion avancés et renforcer la sécurité de vos systèmes critiques.', 'cyber_security.jpg', '140$', 'Certificat inclus', '95h'],
            ['Développement Fullstack Avancé', "Maîtrisez Next.js, Nest.js et MongoDB pour des applications fullstack performantes.", 'fullstack.jpg', '100$', 'Certificat inclus', '80h'],
            ['Big Data et Traitement Massif', 'Découvrez Hadoop et Spark pour gérer de grandes quantités de données avec efficacité.', 'big_data.jpg', '135$', 'Certificat inclus', '110h'],
            ['Blockchain Avancée', 'Créez des applications décentralisées et développez des smart contracts complexes.', 'blockchain.jpg', '125$', 'Certificat inclus', '90h'],
            ['Cloud Computing et Kubernetes', 'Maîtrisez le déploiement d’applications dans le cloud avec Kubernetes et Terraform.', 'cloud_computing.jpg', '130$', 'Certificat inclus', '100h'],
            ['Gestion de Projets IT', "Optimisez vos compétences en gestion d'équipes et projets technologiques avec des outils modernes.", 'project_management.jpg', '95$', 'Certificat inclus', '70h'],
            ['Développement Mobile Avancé', 'Apprenez à créer des applications mobiles natives et hybrides avec Flutter et Kotlin.', 'mobile_advanced.jpg', '110$', 'Certificat inclus', '85h'],
            ['AI et Machine Learning Avancé', 'Maîtrisez l’implémentation d’algorithmes d’apprentissage supervisé et non supervisé.', 'ai_advanced.jpg', '160$', 'Certificat inclus', '130h'],
            ['DevOps Expérimenté', 'Automatisez vos déploiements avec Jenkins, Docker et CI/CD avancé.', 'devops.jpg', '145$', 'Certificat inclus', '95h'],
            ['Visualisation Avancée des Données', 'Créez des tableaux interactifs et percutants avec Tableau, Power BI et D3.js.', 'data_visualization.jpg', '90$', 'Certificat inclus', '75h']
        ] as $cours)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset('assets/images/' . $cours[2]) }}" class="card-img-top" alt="{{ $cours[0] }}" style="height: 140px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title" style="font-size: 1.1rem; height: 50px; overflow: hidden;">{{ $cours[0] }}</h5>
                    <p class="card-text" style="font-size: 0.9rem; height: 60px; overflow: hidden;" title="{{ $cours[1] }}">{{ $cours[1] }}</p>
                    <p class="text-success" style="font-size: 1rem;"><strong>{{ $cours[3] }}</strong> | {{ $cours[4] }} | <span class="text-danger">{{ $cours[5] }}</span></p>
                    <a href="#" class="btn btn-primary btn-hover mt-auto" style="font-size: 0.9rem;">S'inscrire</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>


  </div>

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
</body>
</html>
