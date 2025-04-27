<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat AfriCode</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1EA38B; /* Vert émeraude */
            --secondary-color: #FF8E2A; /* Orange */
            --accent-color: #E32D31; /* Rouge */
            --highlight-color: #27B371; /* Vert clair */
            --background-color: #F8F9FA;
            --light-accent: #ECF0F1;
            --text-color: #333333;
            --light-gray-pattern: rgba(224, 224, 224, 0.3); /* Couleur pour le motif de fond */
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 20px; /* Espace en haut */
        }

        .certificate-container {
            max-width: 900px;
            margin: 20px auto 40px auto; /* Ajustement marge */
            background-color: white;
            padding: 15px; /* Léger padding extérieur */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            position: relative;
            overflow: hidden; /* Important pour le ruban */
        }

        .certificate {
            border: 10px solid var(--primary-color); /* Bordure principale */
            padding: 30px;
            position: relative;
            background-color: #ffffff; /* Fond blanc principal */
            z-index: 1; /* Pour être au-dessus du ::before */
        }

        /* Motif de fond subtil (inspiré exemple 2) */
        .certificate::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
             /* Motif de points SVG encodé */
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23cccccc' fill-opacity='0.2' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
            background-repeat: repeat;
            opacity: 1; /* Opacité contrôlée via la couleur fill-opacity dans le SVG */
            pointer-events: none;
            z-index: -1; /* Placer derrière le contenu .certificate */
        }

        /* Assure que le contenu est au-dessus du ::before */
        .header, .content, .signatures, .stamp, .qr-code, .slogan, .ribbon, .certificate-number, .accreditation, .partner-logo {
            position: relative;
            z-index: 2; /* Plus haut que le fond mais géré par le flux normal */
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 20px;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
            min-height: 100px; /* Hauteur minimale */
        }

        /* Styles pour le logo CSS avec ombre */
        .logo-bg {
            width: 180px;
            height: 100px;
            background-color: var(--primary-color);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            padding: 10px;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15); /* Ombre ajoutée */
        }
        .logo-symbol { display: flex; margin-bottom: 5px; }
        .logo-part-1 { height: 25px; width: 8px; background-color: var(--secondary-color); transform: skew(-15deg); margin-right: 2px; }
        .logo-part-2 { height: 25px; width: 8px; background-color: var(--accent-color); transform: skew(-15deg); margin-right: 2px; }
        .logo-part-3 { height: 25px; width: 8px; background-color: var(--highlight-color); transform: skew(-15deg); }
        .logo-text { font-size: 20px; letter-spacing: 2px; text-shadow: 1px 1px 2px rgba(0,0,0,0.2); }
        .logo-slogan { font-size: 9px; letter-spacing: 1px; opacity: 0.9; }

        /* Logo partenaire optionnel */
        .partner-logo {
            position: absolute;
            top: 0px; /* Ajusté pour être aligné avec le haut du conteneur */
            right: 0px; /* Ajusté */
            max-width: 100px;
            max-height: 60px; /* Hauteur max */
            border-radius: 4px;
            background-color: white;
            padding: 5px; /* Padding intérieur */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            object-fit: contain; /* Pour que l'image s'adapte sans déformation */
        }

        .title {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 15px 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.05);
        }

        .subtitle {
            font-size: 20px;
            color: var(--secondary-color);
            letter-spacing: 1px;
            font-weight: 600;
        }

        .content {
            text-align: center;
            margin-bottom: 30px;
        }

        .student-name {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-color);
            margin: 15px 0;
            border-bottom: 2px dashed var(--primary-color);
            display: inline-block;
            padding: 0 30px 5px 30px;
        }

        .course-name {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-color);
            margin: 15px 0 5px 0;
        }

        .description {
            font-size: 17px;
            color: #555;
            margin: 10px 0;
            line-height: 1.6;
        }

        .details {
            font-size: 15px;
            color: #666;
            margin: 20px 0;
            text-align: center;
        }

        .detail-item {
            margin: 5px 5px 10px 5px;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            background-color: var(--light-accent);
            border: 1px solid var(--primary-color);
            transition: all 0.2s ease;
        }
        .detail-item:hover {
            background-color: rgba(30, 163, 139, 0.1); /* Vert primaire très léger */
            transform: translateY(-2px);
        }
        .detail-item i {
            color: var(--primary-color);
            margin-right: 5px;
            width: 1.1em; /* Assurer alignement */
            text-align: center;
        }

        .date {
            font-size: 16px;
            color: #666;
            margin: 15px 0;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            padding: 0 20px;
        }

        .signature {
            text-align: center;
            max-width: 250px;
        }

        .signature-line {
            width: 100%;
            max-width: 220px;
            border-bottom: 1.5px solid var(--primary-color);
            margin: 0 auto 10px auto;
            height: 40px; /* Plus d'espace pour signature */
        }

        .signature-name {
            font-size: 17px;
            font-weight: 600;
            color: var(--text-color);
        }

        .signature-title {
            font-size: 13px;
            color: #666;
        }

        .stamp {
            position: absolute;
            bottom: 90px; /* Ajusté */
            right: 90px;  /* Ajusté */
            width: 120px; /* Réduit */
            height: 120px;/* Réduit */
            border: 3px double var(--primary-color); /* Double ligne pour effet tampon */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0.85;
            transform: rotate(-12deg); /* Angle léger */
            padding: 5px;
            background-color: rgba(255,255,255,0.8); /* Fond blanc translucide */
        }

        .stamp-text {
            text-align: center;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 11px; /* Réduit */
            line-height: 1.3;
            text-transform: uppercase;
        }

        .slogan {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: var(--secondary-color);
            font-style: italic;
            letter-spacing: 1px;
        }

        /* Styles du ruban */
        .ribbon { width: 150px; height: 150px; overflow: hidden; position: absolute; z-index: 3; }
        .ribbon-top-right { top: -10px; right: -10px; }
        /* Triangle pour effet coupé */
        .ribbon-top-right::before, .ribbon-top-right::after {
            content: "";
            position: absolute;
            border: 5px solid transparent; /* Ajuster taille si besoin */
            border-top-color: #c16a1f; /* Plus sombre que secondary */
            border-right-color: #c16a1f;
        }
        .ribbon-top-right::before { top: 0; left: 45px; } /* Positionner les triangles */
        .ribbon-top-right::after { bottom: 45px; right: 0; }

        .ribbon-top-right span {
            position: absolute;
            display: block;
            width: 225px;
            padding: 8px 0;
            background-color: var(--secondary-color);
            box-shadow: 0 5px 10px rgba(0,0,0,.1);
            color: #fff;
            font-size: 14px;
            font-weight: bold; /* Texte en gras */
            text-shadow: 0 1px 1px rgba(0,0,0,.2);
            text-align: center;
            text-transform: uppercase; /* Texte en majuscules */
            left: -25px;
            top: 30px;
            transform: rotate(45deg);
        }

        .qr-code {
            position: absolute;
            bottom: 25px;
            left: 25px;
            width: 75px;  /* Taille légèrement augmentée */
            height: 75px;
            padding: 5px; /* Padding intérieur */
            background-color: white;
            border: 1px solid var(--primary-color);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1); /* Ombre plus marquée */
            transition: transform 0.3s ease;
        }
        .qr-code:hover {
            transform: scale(1.1); /* Zoom léger au survol */
        }

        .validation-info {
            margin-top: 25px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }

        .validation-code {
            font-family: 'Courier New', Courier, monospace; /* Police différente */
            font-weight: bold;
            color: var(--accent-color);
            letter-spacing: 1px;
            background-color: #f0f0f0;
            padding: 3px 6px; /* Plus de padding */
            border-radius: 4px;
            border: 1px solid #ddd; /* Bordure légère */
            display: inline-block; /* Pour que padding/border s'appliquent bien */
        }

        .skills-container {
            margin: 20px 0 15px 0; /* Ajustement marge */
            text-align: center;
            min-height: 30px;
        }

        .skills-badge {
            display: inline-block;
            margin: 3px 5px;
            padding: 5px 14px; /* Padding ajusté */
            background-color: var(--light-accent);
            border-radius: 15px;
            font-size: 13px;
            color: var(--text-color);
            border: 1px solid var(--primary-color);
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .skills-badge:hover {
            background-color: rgba(30, 163, 139, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Nouveau: Classe pour l'accreditation */
        .accreditation {
            position: absolute;
            top: 15px; /* Plus près du bord */
            left: 15px;
            font-size: 10px; /* Plus petit */
            color: var(--primary-color); /* Utilise couleur primaire */
            background-color: rgba(255,255,255,0.8);
            padding: 2px 6px;
            border-radius: 3px;
            border: 1px solid rgba(30, 163, 139, 0.5); /* Bordure légère */
            font-weight: 500;
            z-index: 3;
        }

        /* Nouveau: Styles pour la série et le numéro de certificat */
        .certificate-number {
            position: absolute;
            top: 15px; /* Plus près du bord */
            right: 15px;
            font-size: 10px; /* Plus petit */
            color: var(--primary-color);
            font-weight: bold;
            background-color: rgba(255,255,255,0.8);
            padding: 2px 6px;
            border-radius: 3px;
            border: 1px solid rgba(30, 163, 139, 0.5);
            font-family: 'Courier New', Courier, monospace;
            z-index: 3;
        }

        /* Styles pour les onglets de langue (placés à l'extérieur en haut) */
        .language-selector {
            position: absolute;
            top: -35px; /* Positionné au-dessus du container */
            right: 15px;
            display: flex;
            gap: 5px;
            z-index: 10; /* Pour être au-dessus de tout */
        }

        .lang-tab {
            padding: 4px 10px;
            background-color: #e0e0e0;
            border-radius: 5px 5px 0 0; /* Onglet arrondi en haut */
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            border: 1px solid #ccc;
            border-bottom: none;
            transition: background-color 0.2s ease;
        }
        .lang-tab:hover {
            background-color: #d0d0d0;
        }
        .lang-tab.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Styles pour l'impression */
        @media print {
            body {
                background-color: white;
                padding-top: 0;
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            .certificate-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100%;
                border-radius: 0;
                 overflow: visible; /* Éviter coupure ruban */
            }
            .certificate {
                border-width: 8px; /* Bordure légèrement plus fine */
                box-shadow: none;
            }
            .certificate::before {
                 display: block !important; /* Forcer l'affichage du fond */
            }
            .print-btn-container, .language-selector {
                display: none !important;
            }
            /* Forcer l'impression des couleurs/fonds */
            .logo-bg, .ribbon span, .stamp, .accreditation, .certificate-number, .validation-code, .skills-badge, .detail-item {
                 -webkit-print-color-adjust: exact !important;
                 color-adjust: exact !important;
            }
            .stamp { opacity: 0.7; } /* Tampon légèrement plus léger */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sélecteur de langue (placé ici pour être hors du container principal) -->
        <div class="language-selector">
            <div class="lang-tab active" data-lang="fr">FR</div>
            <div class="lang-tab" data-lang="en">EN</div>
        </div>

        <!-- Conteneur pour les boutons -->
        <div class="row mb-3 mt-4 print-btn-container"> <!-- Ajout mt-4 pour espace avec langue -->
            <div class="col text-end">
                <button class="btn btn-sm btn-primary" style="background-color: var(--primary-color); border-color: var(--primary-color);" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> <span data-translate="print">Imprimer</span>
                </button>
                <button class="btn btn-sm btn-secondary ms-2" style="background-color: var(--secondary-color); border-color: var(--secondary-color);" id="customizeBtn">
                    <i class="fas fa-edit me-1"></i> <span data-translate="customize">Personnaliser</span>
                </button>
                <button class="btn btn-sm btn-success ms-2" id="saveLocalBtn"> <!-- Couleur verte pour sauvegarder -->
                    <i class="fas fa-save me-1"></i> <span data-translate="save">Sauvegarder</span>
                </button>
                <div class="dropdown d-inline-block ms-2">
                    <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="loadSavedBtn" data-bs-toggle="dropdown" aria-expanded="false"> <!-- Couleur bleue pour charger -->
                        <i class="fas fa-folder-open me-1"></i> <span data-translate="load">Charger</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" id="savedCertificatesList">
                        <!-- Les certificats sauvegardés seront listés ici -->
                        <li><a class="dropdown-item text-muted" data-translate="noSaved">Aucun certificat sauvegardé</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="certificate-container">
            <div class="ribbon ribbon-top-right">
                <span id="ribbon-text" data-translate="ribbonText">CERTIFIÉ</span>
            </div>

            <div class="certificate">
                <!-- Numéro de certificat et accréditation -->
                <div class="certificate-number" id="certificate-number">Série: AF-2025-0001</div>
                <div class="accreditation" id="accreditation" style="display: none;"></div> <!-- Caché par défaut -->

                <div class="header">
                    <div class="logo-container">
                        <!-- Logo en CSS -->
                        <div class="logo-bg">
                            <div class="logo-symbol">
                                <div class="logo-part-1"></div>
                                <div class="logo-part-2"></div>
                                <div class="logo-part-3"></div>
                            </div>
                            <div class="logo-text">AFRICODE</div>
                            <div class="logo-slogan" id="logo-slogan" data-translate="logoSlogan">NO CODE, NO FUTURE</div>
                        </div>
                        <!-- Logo partenaire (optionnel) -->
                        <img src="/api/placeholder/100/60" alt="Partner Logo" class="partner-logo" id="partner-logo" style="display: none;">
                    </div>
                    <h1 class="title" id="certificate-title" data-translate="title">Certificat de Formation</h1>
                    <p class="subtitle" id="certificate-subtitle" data-translate="subtitle">Centre de formation numérique AfriCode</p>
                </div>

                <div class="content">
                    <p class="description" id="awarded-text" data-translate="awardedText">Ce certificat est fièrement décerné à</p>
                    <h2 class="student-name" id="student-name">NOM DE L'ÉTUDIANT</h2>
                    <p class="description" id="completed-text" data-translate="completedText">pour avoir complété avec succès la formation</p>
                    <h3 class="course-name" id="course-name">NOM DE LA FORMATION</h3>

                    <!-- Conteneur pour les compétences acquises -->
                    <div class="skills-container" id="skills-list">
                        <!-- Les badges de compétences seront ajoutés ici par JS -->
                    </div>

                    <p class="description" id="excellence-text" data-translate="excellenceText" style="margin-top: 15px;">ayant démontré compétence et excellence dans les domaines clés du programme.</p>

                    <div class="details">
                        <span class="detail-item">
                            <i class="fas fa-clock"></i> <!-- Icône horloge -->
                            <span id="duration-label" data-translate="durationLabel">Durée:</span> <span id="duration">6 semaines</span>
                        </span>
                        <span class="detail-item">
                            <i class="fas fa-layer-group"></i>
                            <span id="level-label" data-translate="levelLabel">Niveau:</span> <span id="level">Intermédiaire</span>
                        </span>
                        <span class="detail-item">
                            <i class="fas fa-medal"></i>
                            <span id="grade-label" data-translate="gradeLabel">Mention:</span> <span id="grade">Excellent</span>
                        </span>
                    </div>

                    <p class="date"><span id="issued-date-label" data-translate="issuedDateLabel">Délivré le :</span> <span id="completion-date">27 Avril 2025</span></p>

                    <div class="validation-info">
                        <p id="verify-text" data-translate-html="verifyText">Vérifiez l'authenticité sur <strong>www.africode.tech/verify</strong> ou via le QR Code.</p>
                        <p><span id="validation-code-label" data-translate="validationCodeLabel">Code de validation:</span> <span class="validation-code" id="validation-code">AFC-XXXXXXXX</span></p>
                    </div>
                </div>

                <div class="signatures">
                    <div class="signature">
                        <div class="signature-line"></div>
                        <p class="signature-name" id="trainer-title" data-translate="trainerTitle">Le Formateur</p>
                        <p class="signature-title" id="trainer-position" data-translate="trainerPosition">Expert Technique Principal</p>
                    </div>

                    <div class="signature">
                        <div class="signature-line"></div>
                        <p class="signature-name">Mahoulolo SEBIO</p>
                        <p class="signature-title" id="director-position" data-translate="directorPosition">Fondateur & Directeur Général</p>
                    </div>
                </div>

                <div class="stamp">
                    <div class="stamp-text" id="stamp-text" data-translate-html="stampText">
                        AfriCode<br>Formation<br>Certifiée<br>Qualité
                    </div>
                </div>

                <!-- QR Code de validation -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data=https://www.africode.com/verify/AFC-XXXXXXXX" alt="QR Code de Validation" class="qr-code" id="qr-code-img">

                <div class="slogan" id="slogan" data-translate="slogan">
                    No Code, No Future
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Configuration & Initialisation ---
            let studentName = "NOM DE L'ÉTUDIANT";
            let courseName = "NOM DE LA FORMATION";
            let completionDate = new Date();
            let duration = "6 semaines";
            let level = "Intermédiaire"; // Doit correspondre aux clés dans translations.xx.levels
            let grade = "Excellent"; // Doit correspondre aux clés dans translations.xx.grades
            let skills = [];
            let validationCode = generateCertificateID();
            let certificateNumber = generateCertificateNumber();
            let currentLang = "fr";
            let accreditationText = ""; // Texte pour l'accréditation
            let showPartnerLogo = false; // Afficher le logo partenaire ?
            let partnerLogoSrc = "/api/placeholder/100/60"; // Source par défaut

            const translations = {
                fr: {
                    print: "Imprimer", customize: "Personnaliser", save: "Sauvegarder", load: "Charger", noSaved: "Aucun certificat sauvegardé", clearAllConfirm: "Supprimer tous les certificats sauvegardés ?", savedSuccess: "Certificat sauvegardé !", saveConfirm: "Sauvegarder ce certificat ?",
                    title: "Certificat de Formation", subtitle: "Centre de Formation Numérique AfriCode", awardedText: "Ce certificat est fièrement décerné à", completedText: "pour avoir complété avec succès la formation", excellenceText: "ayant démontré compétence et excellence dans les domaines clés du programme.",
                    durationLabel: "Durée:", levelLabel: "Niveau:", gradeLabel: "Mention:", issuedDateLabel: "Délivré le :", verifyText: "Vérifiez l'authenticité sur <strong>www.africode.tech/verify</strong> ou via le QR Code.", validationCodeLabel: "Code de validation:",
                    trainerTitle: "Le Formateur", trainerPosition: "Expert Technique Principal", directorPosition: "Fondateur & Directeur Général", stampText: "AfriCode<br>Formation<br>Certifiée<br>Qualité", slogan: "No Code, No Future", ribbonText: "CERTIFIÉ", logoSlogan: "NO CODE, NO FUTURE",
                    levels: { "Débutant": "Débutant", "Intermédiaire": "Intermédiaire", "Avancé": "Avancé", "Indéfini": "Indéfini" },
                    grades: { "Passable": "Passable", "Bien": "Bien", "Très Bien": "Très Bien", "Excellent": "Excellent", "Non évalué": "Non évalué" },
                    placeholders: { studentName: "NOM DE L'ÉTUDIANT", courseName: "NOM DE LA FORMATION", duration: "Durée", level: "Niveau", grade: "Mention", skills: "Compétences (séparées par virgules)", date: "Date (AAAA-MM-JJ)", accreditation: "Texte d'accréditation (ex: ISO 9001)", partnerLogoSrc: "URL du logo partenaire" }
                },
                en: {
                    print: "Print", customize: "Customize", save: "Save", load: "Load", noSaved: "No saved certificates", clearAllConfirm: "Delete all saved certificates?", savedSuccess: "Certificate saved!", saveConfirm: "Save this certificate?",
                    title: "Training Certificate", subtitle: "AfriCode Training Center numerique", awardedText: "This certificate is proudly awarded to", completedText: "for successfully completing the training", excellenceText: "having demonstrated competence and excellence in the key areas of the program.",
                    durationLabel: "Duration:", levelLabel: "Level:", gradeLabel: "Grade:", issuedDateLabel: "Issued on:", verifyText: "Verify authenticity at <strong>www.africode.tech/verify</strong> or via the QR Code.", validationCodeLabel: "Validation code:",
                    trainerTitle: "The Trainer", trainerPosition: "Principal Technical Expert", directorPosition: "Founder & CEO", stampText: "AfriCode<br>Certified<br>Quality<br>Training", slogan: "No Code, No Future", ribbonText: "CERTIFIED", logoSlogan: "NO CODE, NO FUTURE",
                    levels: { "Débutant": "Beginner", "Intermédiaire": "Intermediate", "Avancé": "Advanced", "Indéfini": "Undefined" },
                    grades: { "Passable": "Fair", "Bien": "Good", "Très Bien": "Very Good", "Excellent": "Excellent", "Non évalué": "Not Evaluated" },
                    placeholders: { studentName: "STUDENT'S NAME", courseName: "COURSE NAME", duration: "Duration", level: "Level", grade: "Grade", skills: "Skills (comma-separated)", date: "Date (YYYY-MM-DD)", accreditation: "Accreditation text (e.g., ISO 9001)", partnerLogoSrc: "Partner logo URL" }
                }
            };

             // --- Fonctions Utilitaires ---
            function formatDate(date, locale = 'fr-FR') {
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return (date instanceof Date && !isNaN(date)) ? date.toLocaleDateString(locale, options) : (locale === 'fr-FR' ? "Date non spécifiée" : "Date not specified");
            }

            function generateCertificateNumber() {
                const year = new Date().getFullYear();
                const randomNum = Math.floor(1000 + Math.random() * 9000); // 4 chiffres
                return `AF-${year}-${randomNum}`;
            }

            function generateCertificateID() {
                return 'AFC-' + Math.random().toString(36).substring(2, 10).toUpperCase();
            }

            function updateQrCode(code) {
                const verificationUrl = `https://www.africode.com/verify/${code}`; // Adapter si besoin
                document.getElementById('qr-code-img').src = `https://api.qrserver.com/v1/create-qr-code/?size=75x75&data=${encodeURIComponent(verificationUrl)}`;
                document.getElementById('qr-code-img').alt = `QR Code: ${code}`;
            }

            function updateSkillsBadges(skillsArray) {
                const container = document.getElementById('skills-list');
                container.innerHTML = '';
                if (skillsArray && skillsArray.length > 0 && skillsArray[0] !== "") {
                    skillsArray.forEach(skill => {
                        const badge = document.createElement('span');
                        badge.className = 'skills-badge';
                        badge.textContent = skill.trim();
                        container.appendChild(badge);
                    });
                }
            }

             // --- Mise à jour de l'affichage ---
            function updateDisplay() {
                document.getElementById('student-name').textContent = studentName;
                document.getElementById('course-name').textContent = courseName;
                document.getElementById('duration').textContent = duration;
                // Les textes pour level/grade seront mis à jour par applyTranslations
                document.getElementById('validation-code').textContent = validationCode;
                document.getElementById('certificate-number').textContent = certificateNumber;

                const accreditationEl = document.getElementById('accreditation');
                accreditationEl.textContent = accreditationText;
                accreditationEl.style.display = accreditationText ? 'block' : 'none';

                const partnerLogoEl = document.getElementById('partner-logo');
                partnerLogoEl.src = partnerLogoSrc;
                partnerLogoEl.style.display = showPartnerLogo ? 'block' : 'none';
                partnerLogoEl.onerror = function() { this.style.display = 'none'; }; // Cache si l'image ne charge pas


                updateQrCode(validationCode);
                updateSkillsBadges(skills);
                applyTranslations(currentLang); // Applique aussi la date formatée et les textes niveau/mention
            }

             // --- Traduction ---
            function applyTranslations(lang) {
                currentLang = lang; // Mettre à jour la langue courante
                const t = translations[lang];
                const locale = lang === 'fr' ? 'fr-FR' : 'en-US';

                document.querySelectorAll('[data-translate]').forEach(el => {
                    const key = el.dataset.translate;
                    if (t[key]) el.textContent = t[key];
                });
                document.querySelectorAll('[data-translate-html]').forEach(el => {
                     const key = el.dataset.translateHtml;
                    if (t[key]) el.innerHTML = t[key];
                });

                 // Traduire le niveau et la mention actuels
                const currentLevelKey = Object.keys(translations.fr.levels).find(key => translations.fr.levels[key] === level || translations.en.levels[key] === level) || level;
                document.getElementById('level').textContent = t.levels[currentLevelKey] || level;

                const currentGradeKey = Object.keys(translations.fr.grades).find(key => translations.fr.grades[key] === grade || translations.en.grades[key] === grade) || grade;
                document.getElementById('grade').textContent = t.grades[currentGradeKey] || grade;

                // Mettre à jour la date formatée
                document.getElementById('completion-date').textContent = formatDate(completionDate, locale);

                 // Mettre à jour les onglets de langue actifs
                document.querySelectorAll('.lang-tab').forEach(tab => {
                    tab.classList.toggle('active', tab.dataset.lang === lang);
                });
            }

             // --- Personnalisation ---
            document.getElementById('customizeBtn').addEventListener('click', function() {
                const p = translations[currentLang].placeholders;

                const newStudentName = prompt(p.studentName + ":", studentName);
                if (newStudentName !== null) studentName = newStudentName || p.studentName; // Utilise placeholder si vide

                const newCourseName = prompt(p.courseName + ":", courseName);
                if (newCourseName !== null) courseName = newCourseName || p.courseName;

                const newDateStr = prompt(p.date + ":", completionDate.toISOString().split('T')[0]);
                if (newDateStr !== null) {
                    const newDate = new Date(newDateStr + 'T00:00:00'); // Assure date locale
                    if (!isNaN(newDate)) completionDate = newDate;
                }

                const newDuration = prompt(p.duration + ":", duration);
                if (newDuration !== null) duration = newDuration;

                 // Utiliser les clés internes pour le prompt de niveau/mention pour robustesse
                const levelKeys = Object.keys(translations.fr.levels);
                const gradeKeys = Object.keys(translations.fr.grades);

                const newLevelKey = prompt(`${p.level} (${levelKeys.join('/')}):`, level);
                 if (newLevelKey !== null && levelKeys.includes(newLevelKey)) level = newLevelKey;


                const newGradeKey = prompt(`${p.grade} (${gradeKeys.join('/')}):`, grade);
                 if (newGradeKey !== null && gradeKeys.includes(newGradeKey)) grade = newGradeKey;


                const skillsStr = prompt(p.skills + ":", skills.join(', '));
                if (skillsStr !== null) {
                    skills = skillsStr.split(',').map(s => s.trim()).filter(s => s);
                }

                const newAccreditation = prompt(p.accreditation + ":", accreditationText);
                if (newAccreditation !== null) accreditationText = newAccreditation;

                showPartnerLogo = confirm("Afficher le logo partenaire ?");
                if (showPartnerLogo) {
                    const newPartnerLogoSrc = prompt(p.partnerLogoSrc + ":", partnerLogoSrc);
                    if (newPartnerLogoSrc !== null) partnerLogoSrc = newPartnerLogoSrc;
                }

                // Option pour regénérer les codes
                if (confirm("Générer un nouveau code de validation et numéro de série ?")) {
                    validationCode = generateCertificateID();
                    certificateNumber = generateCertificateNumber();
                }

                updateDisplay();
            });

             // --- Sauvegarde / Chargement Local ---
            const storageKey = 'africode_certificates_v2'; // Clé pour localStorage

            function saveCurrentCertificate() {
                const certData = {
                    v: 2, // Version des données
                    studentName, courseName, completionDate: completionDate.toISOString(), duration, level, grade, skills,
                    validationCode, certificateNumber, accreditationText, showPartnerLogo, partnerLogoSrc,
                    savedAt: new Date().toISOString()
                };
                let savedCerts = JSON.parse(localStorage.getItem(storageKey) || '[]');
                savedCerts.push(certData);
                if (savedCerts.length > 20) savedCerts = savedCerts.slice(-20); // Limiter
                localStorage.setItem(storageKey, JSON.stringify(savedCerts));
                updateSavedCertificatesList();
            }

            function updateSavedCertificatesList() {
                const savedCerts = JSON.parse(localStorage.getItem(storageKey) || '[]');
                const listEl = document.getElementById('savedCertificatesList');
                const t = translations[currentLang];
                listEl.innerHTML = ''; // Vider

                if (savedCerts.length === 0) {
                    listEl.innerHTML = `<li><a class="dropdown-item text-muted">${t.noSaved}</a></li>`;
                } else {
                    savedCerts.forEach((cert, index) => {
                        const date = new Date(cert.savedAt);
                        const locale = currentLang === 'fr' ? 'fr-FR' : 'en-US';
                        const formattedDate = date.toLocaleDateString(locale, { short: true }) + ' ' + date.toLocaleTimeString(locale, { hour: '2-digit', minute: '2-digit' });
                        const title = `${cert.studentName || 'N/A'} - ${cert.courseName || 'N/A'} (${formattedDate})`;

                        const li = document.createElement('li');
                        const a = document.createElement('a');
                        a.className = 'dropdown-item';
                        a.href = '#';
                        a.textContent = title;
                        a.title = title; // Tooltip avec plus d'infos
                        a.onclick = (e) => { e.preventDefault(); loadCertificate(index); };
                        li.appendChild(a);
                        listEl.appendChild(li);
                    });
                    // Ajouter séparateur et option "Tout effacer"
                     listEl.innerHTML += '<li><hr class="dropdown-divider"></li>';
                    listEl.innerHTML += `<li><a class="dropdown-item text-danger" href="#" id="clearAllSaved"><i class="fas fa-trash-alt me-1"></i> Tout Effacer</a></li>`;
                    document.getElementById('clearAllSaved').onclick = (e) => {
                        e.preventDefault();
                        if (confirm(t.clearAllConfirm)) {
                            localStorage.removeItem(storageKey);
                            updateSavedCertificatesList();
                        }
                    };
                }
            }

            function loadCertificate(index) {
                const savedCerts = JSON.parse(localStorage.getItem(storageKey) || '[]');
                if (index >= 0 && index < savedCerts.length) {
                    const data = savedCerts[index];
                    studentName = data.studentName;
                    courseName = data.courseName;
                    completionDate = new Date(data.completionDate);
                    duration = data.duration;
                    level = data.level;
                    grade = data.grade;
                    skills = data.skills || [];
                    validationCode = data.validationCode;
                    certificateNumber = data.certificateNumber;
                    accreditationText = data.accreditationText || "";
                    showPartnerLogo = data.showPartnerLogo || false;
                    partnerLogoSrc = data.partnerLogoSrc || "/api/placeholder/100/60";
                    updateDisplay();
                }
            }

            // --- Event Listeners ---
            document.getElementById('saveLocalBtn').addEventListener('click', () => {
                 const t = translations[currentLang];
                if (confirm(t.saveConfirm)) {
                    saveCurrentCertificate();
                    alert(t.savedSuccess);
                }
            });

            document.querySelectorAll('.lang-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    applyTranslations(this.dataset.lang);
                    updateSavedCertificatesList(); // Mettre à jour les dates/textes de la liste
                });
            });

            // --- Initialisation Finale ---
            updateDisplay(); // Afficher les valeurs initiales
            updateSavedCertificatesList(); // Charger la liste des sauvegardes

        });
    </script>
</body>
</html>