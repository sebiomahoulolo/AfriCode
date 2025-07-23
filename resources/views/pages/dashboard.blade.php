@extends('layouts.app')

@section('title', 'AfriCode')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-header-modern">
                    <h4><i class="fas fa-chart-line me-2"></i>Tableau de Bord</h4>
                </div>
                <div class="card-body-modern">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="mb-3">Bienvenue sur AfriCode!</h1>
                            <p class="lead">Votre plateforme d'apprentissage moderne.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-primary bg-gradient rounded p-4 text-white">
                                <h5><i class="fas fa-user-check"></i> Connecté</h5>
                                <p class="mb-0">Vous êtes maintenant connecté à votre espace.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
