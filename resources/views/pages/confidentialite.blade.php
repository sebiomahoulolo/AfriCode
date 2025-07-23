@extends('layouts.layout')

@section('title', 'Déclaration de confidentialité')

@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-4">Déclaration de confidentialité</h1>
        <p class="mb-4">
            Chez AfriCode, la protection de vos données personnelles est une priorité. Cette déclaration de confidentialité explique comment nous collectons, utilisons, stockons et protégeons vos informations lorsque vous utilisez notre plateforme éducative.
        </p>
        <h2 class="text-xl font-semibold mt-6 mb-2">1. Données collectées</h2>
        <ul class="list-disc ml-6 mb-4">
            <li>Informations d’inscription (nom, adresse e-mail, etc.)</li>
            <li>Données de navigation et d’utilisation de la plateforme</li>
            <li>Informations de paiement lors de l’achat de cours</li>
        </ul>
        <h2 class="text-xl font-semibold mt-6 mb-2">2. Utilisation des données</h2>
        <ul class="list-disc ml-6 mb-4">
            <li>Fournir l’accès aux cours et services</li>
            <li>Améliorer l’expérience utilisateur</li>
            <li>Gérer les paiements et la facturation</li>
            <li>Envoyer des notifications importantes</li>
        </ul>
        <h2 class="text-xl font-semibold mt-6 mb-2">3. Partage des données</h2>
        <p class="mb-4">
            Vos données ne sont jamais vendues à des tiers. Elles peuvent être partagées uniquement avec des partenaires de confiance pour le bon fonctionnement du service (ex : prestataires de paiement), dans le respect de la législation en vigueur.
        </p>
        <h2 class="text-xl font-semibold mt-6 mb-2">4. Sécurité</h2>
        <p class="mb-4">
            Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos informations contre tout accès non autorisé, perte ou divulgation.
        </p>
        <h2 class="text-xl font-semibold mt-6 mb-2">5. Vos droits</h2>
        <p class="mb-4">
            Vous disposez d’un droit d’accès, de rectification et de suppression de vos données personnelles. Pour toute demande, contactez-nous à l’adresse : <a href="mailto:contact@africode.com" class="text-blue-600 underline">contact@africode.com</a>.
        </p>
        <h2 class="text-xl font-semibold mt-6 mb-2">6. Cookies</h2>
        <p class="mb-4">
            Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez <a href="{{ route('parametres.cookies') }}" class="text-blue-600 underline">modifier vos paramètres de cookies ici</a>.
        </p>
        <p class="mt-8 text-sm text-gray-500">Dernière mise à jour : {{ date('d/m/Y') }}</p>
    </div>
@endsection 