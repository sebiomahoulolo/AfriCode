<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certification; 
use Illuminate\Support\Facades\Validator;

class CertificateVerificationController extends Controller
{
    /**
     * Affiche le formulaire de vérification et traite la vérification.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null $verification_code
     * @return \Illuminate\View\View
     */
    public function verifyCertificate(Request $request, $verification_code = null)
    {
        // On récupère le code soumis, qu'il vienne de l'URL (GET) ou du formulaire (POST)
        $submitted_code = $request->isMethod('post') 
            ? $request->input('verification_code') 
            : $verification_code;

        $certification = null;
        $error = null;
        $input = ['verification_code' => $submitted_code];

        // On n'effectue la recherche que si un code a été soumis
        if ($submitted_code) {
            // Validation simple
            $validator = Validator::make($input, [
                'verification_code' => 'required|string|max:255|exists:certifications,verification_code',
            ], [
                'verification_code.required' => 'Veuillez fournir un code de vérification.',
                'verification_code.exists' => 'Aucun certificat ne correspond à ce code. Veuillez vérifier et réessayer.',
            ]);

            // Si la validation échoue, on récupère le message d'erreur
            if ($validator->fails()) {
                $error = $validator->errors()->first('verification_code');
            } else {
                // Si la validation réussit, on récupère le certificat
                $certification = Certification::with(['user', 'course.category'])
                    ->where('verification_code', $submitted_code)
                    ->first();
            }
        }

        // Déterminer quelle vue utiliser selon la route
        $viewName = 'pages.verification-form';
        if (request()->routeIs('certificate.verification')) {
            $viewName = 'pages.certificate-verification';
        }

        // On retourne toujours la même vue, avec les données appropriées
        // return view($viewName, [
        // On retourne toujours la même vue, avec les données appropriées
        return view('pages.verification-form', [
            'certification' => $certification,
            'error' => $error,
            'submitted_code' => $submitted_code, // Pour ré-afficher le code dans le champ
        ]);
    }

    /**
     * Affiche publiquement un certificat.
     *
     * @param \App\Models\Certification $certification
     * @return \Illuminate\View\View
     */
    public function showPublicCertificate(Certification $certification)
    {
        // Le modèle est automatiquement injecté grâce au route model binding sur `verification_code`.
        // On charge les relations pour éviter les requêtes N+1 dans la vue.
        $certification->load(['user', 'course']);

        // On crée une vue dédiée pour l'affichage public afin de ne pas interférer
        // avec la vue privée de l'apprenant.
        return view('pages.public-certification', compact('certification'));
    }
} 