<?php

namespace Database\Seeders;

use App\Models\JobPosition;
use App\Models\RecruitmentQuestion;
use Illuminate\Database\Seeder;

class RecruitmentSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            [
                'slug' => 'secretaire',
                'title' => 'Secrétaire',
                'places_count' => 1,
                'sort_order' => 1,
                'missions' => "Assurer l'accueil physique et téléphonique\nGérer les inscriptions des apprenants\nRédiger et classer les documents administratifs\nPlanifier les rendez-vous et réunions\nAssurer le suivi des dossiers administratifs",
                'profil' => "Être âgée de 18 à 27 ans\nÊtre présentable, accueillante et organisée\nAvoir au minimum le niveau Baccalauréat\nMaîtriser les outils bureautiques (Word, Excel, Internet, Email)\nAvoir une bonne expression écrite et orale",
                'dossier_fournir' => "Lettre de motivation adressée au Directeur Général de AFRICODE\nCV actualisé\nCopie du diplôme ou attestation",
            ],
            [
                'slug' => 'community-manager',
                'title' => 'Community Manager',
                'places_count' => 2,
                'sort_order' => 2,
                'missions' => "Gérer et animer les réseaux sociaux de AFRICODE\nCréer du contenu (textes, visuels, vidéos courtes)\nRépondre aux messages et commentaires\nLancer et suivre les campagnes publicitaires\nAnalyser les performances et proposer des stratégies",
                'profil' => "Être âgé(e) de 18 à 35 ans\nAvoir au minimum le niveau Baccalauréat\nAvoir suivi une formation en Community Management ou Marketing Digital\nÊtre à l'aise avec Facebook, Instagram, TikTok et WhatsApp Business\nÊtre créatif(ve), réactif(ve) et organisé(e)",
                'dossier_fournir' => "Lettre de motivation adressée au Directeur Général de AFRICODE\nCV actualisé\nCopie du diplôme ou attestation\nPortfolio ou liens des pages déjà gérées",
            ],
            [
                'slug' => 'monteur-video',
                'title' => 'Monteur Vidéo',
                'places_count' => 3,
                'sort_order' => 3,
                'missions' => "Réaliser des vidéos promotionnelles pour AFRICODE\nMonter des vidéos éducatives et publicitaires\nAjouter effets, transitions, sous-titres et musique\nAdapter les formats aux réseaux sociaux (Facebook, TikTok, Instagram)\nCollaborer avec l'équipe marketing",
                'profil' => "Être âgé(e) de 18 à 35 ans\nAvoir au minimum le niveau Baccalauréat\nMaîtriser au moins un logiciel de montage (Premiere Pro, CapCut, Filmora, DaVinci Resolve, etc.)\nÊtre créatif(ve), rapide et rigoureux(se)\nAvoir un portfolio ou des réalisations déjà effectuées",
                'dossier_fournir' => "Lettre de motivation adressée au Directeur Général de AFRICODE\nCV actualisé\nCopie du diplôme ou attestation\nPortfolio ou réalisations (obligatoire)",
            ],
            [
                'slug' => 'developpeur-fullstack',
                'title' => 'Développeur Fullstack',
                'places_count' => 3,
                'sort_order' => 4,
                'missions' => "Concevoir et développer des applications web complètes (Frontend & Backend)\nParticiper à la création et à l'amélioration des plateformes e-learning\nDévelopper des API et gérer les bases de données\nAssurer la maintenance, la sécurité et l'optimisation des performances\nCollaborer avec les designers, monteurs vidéo et community managers\nRédiger une documentation technique claire",
                'profil' => "Être âgé(e) de 18 à 35 ans\nNiveau minimum : Baccalauréat + formation en développement web ou bac+3\nMaîtriser au moins une des technologies : Laravel, React, Python, Angular, WordPress\nBonne maîtrise de HTML, CSS et JavaScript\nConnaissance des bases de données (MySQL, PostgreSQL ou autres)\nEsprit logique, autonomie et capacité à travailler en équipe\nAvoir des projets réalisés (GitHub ou portfolio obligatoire)",
                'dossier_fournir' => "Lettre de motivation adressée au Directeur Général de AFRICODE\nCV actualisé\nCopie du diplôme ou attestation\nLien GitHub ou portfolio (obligatoire)",
            ],
        ];

        foreach ($positions as $data) {
            JobPosition::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['is_active' => true])
            );
        }

        $this->seedSecretaireQuestions();
        $this->seedCommunityManagerQuestions();
        $this->seedMonteurVideoQuestions();
        $this->seedDeveloppeurQuestions();
    }

    protected function seedSecretaireQuestions(): void
    {
        $position = JobPosition::where('slug', 'secretaire')->first();
        if (!$position) return;

        $questions = [
            ['Quelle formule Excel permet de sommer une plage de cellules (ex. A1:A10) ?', '=TOTAL(A1:A10)', '=SUM(A1:A10)', '=SOMME(A1;A10)', '=ADD(A1,A10)', 'B'],
            ['Pour rédiger une lettre officielle avec en-tête, pied de page et mise en forme stricte, l\'outil le plus adapté est :', 'Excel', 'PowerPoint', 'Word', 'Bloc-notes', 'C'],
            ["En accueil, lorsqu'un visiteur se présente sans rendez-vous pour un entretien, la démarche prioritaire est :", "Prendre son identité et la raison de sa visite", "L'accueillir et l'orienter ou prendre rendez-vous", 'Lui demander de revenir plus tard', 'Transférer immédiatement sans annoncer', 'B'],
            ['Un classement méthodique des dossiers (alphabétique, thématique ou chronologique) a pour principal avantage :', 'Un gain de temps et une traçabilité', "Une occupation de l'espace de rangement", "Une augmentation de la charge de travail", 'La suppression systématique des archives', 'A'],
            ["Pour un envoi formel de courrier professionnel avec accusé de réception et trace écrite, l'outil recommandé est :", 'Email (messagerie professionnelle)', 'WhatsApp', 'Messenger', 'Story Instagram', 'A'],
            ['En milieu professionnel, le respect de la confidentialité implique :', 'Partager les informations avec les collègues proches', 'Ne divulguer aucune information sensible à des tiers non habilités', 'Publier les procès-verbaux sur l\'intranet', 'Transmettre les dossiers par réseaux sociaux', 'B'],
            ["Si vous détectez une erreur (chiffre, date, nom) dans un document officiel avant envoi :", "L'envoyer puis envoyer un erratum", 'Corriger le document puis le faire valider si besoin', 'Le supprimer et en refaire un nouveau sans prévenir', 'Le transmettre en indiquant "sous réserve"', 'B'],
            ['Pour gérer les rendez-vous de plusieurs personnes et éviter les doublons, l\'outil le plus pertinent est :', 'Un agenda partagé ou un outil de planification (ex. calendrier)', 'Un tableau Excel statique', 'Une liste papier mise à jour manuellement', 'Un groupe WhatsApp', 'A'],
            ['Les qualités attendues d\'une secrétaire en poste sont en priorité :', 'Rapidité seule', 'Disponibilité sans limites', 'Organisation, ponctualité et discrétion', 'Délégation systématique', 'C'],
            ['Face à plusieurs demandes urgentes simultanées (visiteur, téléphone, courriel), la bonne pratique est :', 'Traiter la première venue sans ordre', 'Prioriser selon l\'urgence réelle et la politique de l\'entreprise', 'Reportez tout à plus tard', 'Ne traiter que le courriel', 'B'],
        ];

        $this->upsertQuestions($position->id, $questions);
    }

    protected function seedCommunityManagerQuestions(): void
    {
        $position = JobPosition::where('slug', 'community-manager')->first();
        if (!$position) return;

        $questions = [
            ["La mission principale d'un Community Manager en entreprise est :", 'Gérer uniquement la publicité payante', 'Animer la communauté, créer du contenu et fidéliser l\'audience', 'Assurer la maintenance technique du site', 'Rédiger les contrats commerciaux', 'B'],
            ['Le taux d\'engagement (engagement rate) sur les réseaux sociaux correspond à :', "Le nombre total d'abonnés ou de followers", 'Le ratio interactions (likes, commentaires, partages) / portée ou impressions', 'Le budget alloué aux campagnes', 'La fréquence de connexion du CM', 'B'],
            ['Pour qu\'une publication performe en organique, elle doit prioritairement :', 'Contenir un message clair, un visuel adapté et un call-to-action', 'Être très longue sans visuel', 'Reprendre uniquement des infos des concurrents', 'Être postée une fois par an', 'A'],
            ['Quel outil permet de planifier et publier sur plusieurs réseaux (Facebook, Instagram) ?', 'Meta Business Suite (ex-Facebook Business Suite)', 'Google Analytics uniquement', 'Excel', 'Outlook', 'A'],
            ['Le format vidéo recommandé pour TikTok et les Reels (Instagram) est :', '16:9 paysage', '9:16 vertical (format story)', '1:1 carré uniquement', '4:5 portrait uniquement', 'B'],
            ['En gestion de communauté, face à un commentaire négatif ou une crise, la bonne pratique est :', 'Supprimer le commentaire et bloquer l\'utilisateur', 'Répondre de façon professionnelle et rapide, sans escalade', 'Ignorer jusqu\'à ce qu\'il disparaisse', 'Publier une réponse agressive', 'B'],
            ['Le branding en ligne désigne :', 'Le fait de copier le visuel d\'un concurrent', 'La construction cohérente de l\'image de marque (identité, ton, valeurs)', 'La suppression du logo sur les réseaux', 'Le changement d\'alias chaque semaine', 'B'],
            ['Une campagne sponsorisée (paid social) vise principalement à :', 'Augmenter la portée et cibler une audience définie', 'Réduire le nombre d\'abonnés', 'Remplacer toute la stratégie organique', 'Supprimer les anciennes publications', 'A'],
            ['Canva est principalement utilisé pour :', 'Montage vidéo professionnel (timeline)', 'Création de visuels et supports graphiques (posts, stories, bannières)', 'Développement d\'applications', 'Hébergement de sites web', 'B'],
            ['Une stratégie de contenu efficace repose sur :', 'L\'improvisation et la publication aléatoire', 'Un calendrier éditorial et des objectifs mesurables (KPI)', 'Une seule publication mensuelle', 'La reprise intégrale des posts des concurrents', 'B'],
        ];

        $this->upsertQuestions($position->id, $questions);
    }

    protected function seedMonteurVideoQuestions(): void
    {
        $position = JobPosition::where('slug', 'monteur-video')->first();
        if (!$position) return;

        $questions = [
            ['En montage, le "cut" (coupe) désigne :', 'L\'ajout d\'un effet de transition', 'La coupure entre deux plans ou deux séquences', 'Le changement de piste musicale', 'L\'accélération de la vitesse de lecture', 'B'],
            ['Pour une vidéo native sur le fil Facebook (feed), le format le plus couramment utilisé est :', '16:9 (paysage)', '1:3', '2:1', '9:16 uniquement', 'A'],
            ['Une vidéo promotionnelle percutante doit prioritairement :', 'Être la plus longue possible', 'Transmettre un message clair en peu de temps avec un accroche visuelle', 'Éviter tout sous-titrage', 'Rester sans musique', 'B'],
            ["L'étape qui produit le fichier final (MP4, etc.) à partir du montage s'appelle :", 'Exportation (ou rendu)', 'Importation', 'Compression manuelle seule', 'Enregistrement projet uniquement', 'A'],
            ['La notation 1080p (Full HD) fait référence à :', 'La durée en minutes', 'La résolution verticale en pixels (définition)', 'Le débit audio', 'Le format de compression', 'B'],
            ['Une transition (fondu, wipe, etc.) en montage sert à :', 'Couper définitivement le son', 'Enchaîner visuellement deux plans ou deux séquences', "Supprimer une image clé", 'Flouter une zone', 'B'],
            ['Le ratio 9:16 (vertical) est principalement utilisé pour :', 'Télévision et cinéma classique', 'TikTok, Reels Instagram et Stories', 'Vidéos YouTube uniquement', 'Podcasts audio', 'B'],
            ["L'étalonnage (color grading) en post-production consiste à :", 'Ajuster les couleurs et la luminosité pour une cohérence visuelle', 'Supprimer la piste son', 'Ajouter uniquement des sous-titres', 'Réduire la taille du fichier sans qualité', 'A'],
            ['Les qualités attendues d\'un monteur vidéo professionnel sont :', 'Rapidité seule sans relecture', 'Créativité, rigueur et respect des délais', 'Improvisation totale', 'Désintérêt pour le format de diffusion', 'B'],
            ['Avant d\'exporter le fichier final pour diffusion, il est indispensable de :', 'Vérifier qualité, durée, son et format cible', 'Supprimer toutes les pistes audio', 'Couper des séquences au hasard', 'Publier sans prévisualisation', 'A'],
        ];

        $this->upsertQuestions($position->id, $questions);
    }

    protected function seedDeveloppeurQuestions(): void
    {
        $position = JobPosition::where('slug', 'developpeur-fullstack')->first();
        if (!$position) return;

        $questions = [
            ["La mission d'un développeur Fullstack couvre :", 'Uniquement la partie visuelle (maquettes)', 'Le frontend (interface), le backend (logique, API) et souvent la base de données', 'Uniquement l\'hébergement et la maintenance serveur', 'Uniquement la rédaction de la documentation', 'B'],
            ['Laravel est :', 'Un langage de programmation', 'Un framework PHP pour applications web (MVC, Eloquent, routes)', 'Un logiciel de montage vidéo', 'Une base de données NoSQL', 'B'],
            ['React (bibliothèque JavaScript) est principalement utilisé pour :', 'Gérer uniquement les serveurs et les API', 'Construire des interfaces utilisateur (UI) dynamiques et composants réutilisables', 'Administrer des bases de données', 'Configurer des serveurs Linux', 'B'],
            ['Python est couramment utilisé dans les domaines suivants :', 'Uniquement le développement mobile', 'Développement web, data science, scripts, IA ; plusieurs domaines', 'Uniquement le montage vidéo', 'Uniquement la gestion de bases de données', 'B'],
            ['Angular (Google) est :', 'Un framework frontend (TypeScript) pour applications web', 'Un antivirus', 'Un éditeur de code', 'Un système de gestion de base de données', 'A'],
            ['WordPress est avant tout :', 'Un framework JavaScript', 'Un CMS (système de gestion de contenu) pour sites et blogs', 'Un langage de programmation', 'Un outil de versioning (type Git)', 'B'],
            ['Le rôle du HTML dans une page web est :', 'De styliser l\'apparence (couleurs, polices)', 'De structurer le contenu (titres, paragraphes, liens, médias)', 'D\'exécuter la logique métier côté serveur', 'De gérer les requêtes base de données', 'B'],
            ['Une base de données (MySQL, PostgreSQL, etc.) sert à :', 'Stocker, organiser et interroger des données de manière persistante', 'Uniquement à héberger des images', 'À compiler le code source', 'À déployer l\'application', 'A'],
            ['Une API (Application Programming Interface) REST permet typiquement :', 'D\'échanger des données entre applications (frontend/backend, tiers) via des requêtes HTTP', 'De supprimer le serveur', 'D\'installer le système d\'exploitation', 'De remplacer toute la base de données', 'A'],
            ['Avant la mise en production d\'une application, les bonnes pratiques incluent :', 'Déployer sans tests', 'Tester (unitaires, intégration, manuels), corriger les bugs et sécuriser', 'Supprimer les logs et la documentation', 'Ignorer les retours utilisateurs', 'B'],
        ];

        $this->upsertQuestions($position->id, $questions);
    }

    protected function upsertQuestions(int $jobPositionId, array $questions): void
    {
        foreach ($questions as $order => $q) {
            RecruitmentQuestion::updateOrCreate(
                [
                    'job_position_id' => $jobPositionId,
                    'sort_order' => $order + 1,
                ],
                [
                    'question_text' => $q[0],
                    'option_a' => $q[1],
                    'option_b' => $q[2],
                    'option_c' => $q[3],
                    'option_d' => $q[4],
                    'correct_option' => $q[5],
                ]
            );
        }
    }
}
