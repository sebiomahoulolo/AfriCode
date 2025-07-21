@extends('layouts.layout')
@section('title', 'Paramètres des cookies')

@section('content')
<br>
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4">Paramètres des cookies</h1>
        <p class="mb-4">
            Gérez vos préférences concernant l’utilisation des cookies sur AfriCode. Vous pouvez choisir les types de cookies que vous souhaitez autoriser.
        </p>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('parametres.cookies.save') }}">
            @csrf
            <div class="mb-4">
                <label class="font-semibold">
                    <input type="checkbox" name="cookies_essentiels" checked disabled>
                    Cookies essentiels (toujours activés)
                </label>
                <p class="text-sm text-gray-600 ml-6">Nécessaires au fonctionnement du site.</p>
            </div>
            <div class="mb-4">
                <label class="font-semibold">
                    <input type="checkbox" name="cookies_analytiques">
                    Cookies analytiques
                </label>
                <p class="text-sm text-gray-600 ml-6">Nous aident à améliorer la plateforme grâce à des statistiques d’utilisation.</p>
            </div>
            <div class="mb-4">
                <label class="font-semibold">
                    <input type="checkbox" name="cookies_marketing">
                    Cookies marketing
                </label>
                <p class="text-sm text-gray-600 ml-6">Utilisés pour personnaliser la publicité et les offres.</p>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded"  style="background-color:  #1EA38B">Enregistrer mes préférences</button>
        </form>
        <p class="mt-8 text-sm text-gray-500">Vous pouvez modifier vos choix à tout moment.</p>
    </div>
@endsection 