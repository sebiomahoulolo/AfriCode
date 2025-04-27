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
    <p>Des contenus adaptés aux intermédiaires</p>

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
        <!-- Carte de cours n°4 : Excel Avancé -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/excel_advanced.jpg') }}" class="card-img-top" alt="Excel Avancé">
                <div class="card-body">
                    <h5 class="card-title">Excel Avancé</h5>
                    <p class="card-text">Maîtrisez les fonctionnalités avancées d’Excel comme les tableaux croisés dynamiques, les macros et les graphiques interactifs.</p>
                    <p class="text-success"><strong>20$</strong> | Certificat inclus | <span class="text-danger">15h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>

        <!-- Carte de cours n°5 : Word Avancé -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/word_advanced.jpg') }}" class="card-img-top" alt="Word Avancé">
                <div class="card-body">
                    <h5 class="card-title">Word Avancé</h5>
                    <p class="card-text">Apprenez les outils avancés de Word comme la gestion des styles, les sommaires automatiques et les options de mise en page complexes.</p>
                    <p class="text-success"><strong>18$</strong> | Certificat inclus | <span class="text-danger">10h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>

        <!-- Carte de cours n°6 : Cours pour Freelancers -->
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="{{ asset('assets/images/freelancer_course.jpg') }}" class="card-img-top" alt="Cours pour Freelancers">
                <div class="card-body">
                    <h5 class="card-title">Cours pour Freelancers</h5>
                    <p class="card-text">Découvrez comment réussir en tant que freelance, gérer des projets, communiquer efficacement avec des clients et développer une carrière autonome.</p>
                    <p class="text-success"><strong>30$</strong> | Certificat inclus | <span class="text-danger">20h</span></p>
                    <a href="#" class="btn btn-primary btn-hover">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</section>


   <hr> 
<!-- Section pour Intermédiaires -->
<section id="intermediaires" class="mt-5">
    
    <div class="row mb-4"> 
        @foreach ([
            ['Développement Backend', 'Approfondissez vos connaissances en PHP et Node.js pour concevoir des systèmes robustes.', 'backend.jpg', '30$', 'Certificat inclus', '40h'],
            ['Développement d’API RESTful', 'Créez des APIs sécurisées et performantes avec Laravel et Express.js.', 'api.jpg', '35$', 'Certificat inclus', '25h'],
            ['Réseaux et Sécurité', "Apprenez à sécuriser les systèmes et à détecter les failles de sécurité.", 'reseau_securite.jpg', '45$', 'Certificat inclus', '30h'],
            ['Programmation avec Java', 'Maîtrisez Java pour développer des applications orientées objet.', 'java.jpg', '40$', 'Certificat inclus', '50h'],
            ['Analyse de données', "Explorez les bases de l'analyse de données avec Python et Pandas.", 'data_analysis.jpg', '50$', 'Certificat inclus', '35h'],
            ['Développement Web Frontend', 'Apprenez React.js et Angular pour créer des interfaces utilisateur modernes et réactives.', 'frontend.jpg', '25$', 'Certificat inclus', '20h'],
            ['Développement Mobile', 'Maîtrisez Flutter et React Native pour créer des applications mobiles multiplateformes.', 'mobile_dev.jpg', '40$', 'Certificat inclus', '35h'],
            ['Cloud Computing', "Découvrez AWS et Azure pour le déploiement d'applications dans le Cloud.", 'cloud.jpg', '50$', 'Certificat inclus', '30h'],
            ['Intelligence Artificielle', "Explorez les algorithmes d'apprentissage machine et les modèles d'IA.", 'ai.jpg', '60$', 'Certificat inclus', '40h'],
            ['Sécurité Web', "Apprenez à protéger vos applications web contre les attaques courantes.", 'web_security.jpg', '35$', 'Certificat inclus', '20h'],
        ['DevOps et Automatisation', "Maîtrisez les outils de CI/CD comme Jenkins et Docker pour déployer efficacement.", 'devops.jpg', '55$', 'Certificat inclus', '45h'],
            ['Blockchain et Cryptomonnaies', "Découvrez les bases de la blockchain et développez des contrats intelligents avec Ethereum.", 'blockchain.jpg', '70$', 'Certificat inclus', '50h']

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
