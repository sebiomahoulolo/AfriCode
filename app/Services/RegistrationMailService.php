<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;

class RegistrationMailService
{
    private $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer()
    {
        try {
            // Configuration du serveur SMTP
            $this->mailer->isSMTP();
            $this->mailer->Host = config('mail.mailers.smtp.host', 'smtp.gmail.com');
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = config('mail.mailers.smtp.username', 'your-email@gmail.com');
            $this->mailer->Password = config('mail.mailers.smtp.password', 'your-password');
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = config('mail.mailers.smtp.port', 587);
            $this->mailer->CharSet = 'UTF-8';

            // Configuration de l'expéditeur
            $this->mailer->setFrom(
                config('mail.from.address', 'noreply@africode.com'),
                config('mail.from.name', 'AfriCode - Bienvenue')
            );
        } catch (Exception $e) {
            \Log::error('Erreur configuration PHPMailer Registration: ' . $e->getMessage());
        }
    }

    public function sendWelcomeEmail(User $user)
    {
        try {
            $this->mailer->addAddress($user->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Bienvenue sur AfriCode, ' . $user->first_name . ' !';

            $this->mailer->Body = $this->getWelcomeEmailTemplate($user);
            $this->mailer->AltBody = $this->getWelcomeEmailTextTemplate($user);

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            \Log::error('Erreur envoi email bienvenue: ' . $e->getMessage());
            return false;
        }
    }

    private function getWelcomeEmailTemplate($user)
    {
        $roleText = $user->role === 'apprenant' ? 'Apprenant' : 'Formateur';
        $dashboardUrl = $user->role === 'apprenant' ? route('apprenant.dashboard') : route('formateur.dashboard');
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Bienvenue sur AfriCode</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #1EA38B;'>Bienvenue sur AfriCode !</h1>
                    <p style='font-size: 1.2rem; color: #666;'>Votre aventure tech commence maintenant</p>
                </div>
                
                <p>Bonjour <strong>{$user->first_name} {$user->last_name}</strong>,</p>
                
                <p>Félicitations ! Votre compte AfriCode a été créé avec succès. Vous faites maintenant partie de notre communauté tech africaine qui façonne l'avenir numérique du continent.</p>
                
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h3 style='color: #1EA38B; margin-top: 0;'>Vos informations de compte :</h3>
                    <p><strong>Nom complet :</strong> {$user->first_name} {$user->last_name}</p>
                    <p><strong>Email :</strong> {$user->email}</p>
                    <p><strong>Profil :</strong> {$roleText}</p>
                    <p><strong>Date d'inscription :</strong> " . $user->created_at->format('d/m/Y') . "</p>
                </div>
                
                <div style='background-color: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h4 style='color: #1EA38B; margin-top: 0;'>Prochaines étapes :</h4>
                    <ol>
                        <li><strong>Accédez à votre tableau de bord</strong> pour découvrir nos formations</li>
                        <li><strong>Parcourez notre catalogue</strong> de cours et certifications</li>
                        <li><strong>Rejoignez notre communauté</strong> sur les réseaux sociaux</li>
                        <li><strong>Participez aux compétitions</strong> et challenges</li>
                    </ol>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$dashboardUrl}' 
                       style='background-color: #1EA38B; color: white; padding: 15px 30px; 
                              text-decoration: none; border-radius: 8px; display: inline-block; 
                              font-weight: 600; font-size: 1.1rem;'>
                        Accéder à mon tableau de bord
                    </a>
                </div>
                
                <div style='background-color: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #FF8E2A;'>
                    <h4 style='color: #FF8E2A; margin-top: 0;'>Conseils pour bien démarrer :</h4>
                    <ul>
                        <li>Complétez votre profil avec une photo et une bio</li>
                        <li>Explorez les formations gratuites pour commencer</li>
                        <li>Rejoignez nos forums pour échanger avec la communauté</li>
                        <li>Suivez nos actualités sur les réseaux sociaux</li>
                    </ul>
                </div>
                
                <!-- Réseaux sociaux -->
                <div style='text-align: center; margin: 30px 0;'>
                    <h4 style='color: #1EA38B; margin-bottom: 15px;'>Suivez-nous sur les réseaux sociaux</h4>
                    <div style='display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;'>
                        <a href='https://www.facebook.com/profile.php?id=61574681464943' 
                           style='background-color: #1877f2; color: white; padding: 10px; border-radius: 50%; 
                                  text-decoration: none; display: inline-block; width: 40px; height: 40px; 
                                  text-align: center; line-height: 20px; font-size: 16px;' 
                           target='_blank' title='Facebook'>
                            f
                        </a>
                        <a href='https://www.instagram.com/africode1?igsh=YzljYTk1ODg3Zg==' 
                           style='background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); 
                                  color: white; padding: 10px; border-radius: 50%; text-decoration: none; 
                                  display: inline-block; width: 40px; height: 40px; text-align: center; 
                                  line-height: 20px; font-size: 16px;' 
                           target='_blank' title='Instagram'>
                            📷
                        </a>
                        <a href='https://www.linkedin.com/company/africode-no-code-no-future/' 
                           style='background-color: #0077b5; color: white; padding: 10px; border-radius: 50%; 
                                  text-decoration: none; display: inline-block; width: 40px; height: 40px; 
                                  text-align: center; line-height: 20px; font-size: 16px;' 
                           target='_blank' title='LinkedIn'>
                            in
                        </a>
                        <a href='https://youtube.com/@africodetechsm?si=k8Y49FJR3tib1KNc' 
                           style='background-color: #ff0000; color: white; padding: 10px; border-radius: 50%; 
                                  text-decoration: none; display: inline-block; width: 40px; height: 40px; 
                                  text-align: center; line-height: 20px; font-size: 16px;' 
                           target='_blank' title='YouTube'>
                            ▶
                        </a>
                    </div>
                </div>
                
                <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h4 style='color: #1EA38B; margin-top: 0;'>Besoin d'aide ?</h4>
                    <p>Notre équipe support est là pour vous accompagner :</p>
                    <ul>
                        <li><strong>Email :</strong> contact@africode.tech</li>
                        <li><strong>Horaires :</strong> Lun - Ven : 8h - 18h</li>
                    </ul>
                </div>
                
                <p style='font-size: 12px; color: #666; text-align: center;'>
                    AfriCode - No Code, No Future<br>
                    Développé avec amour pour la communauté tech africaine<br>
                </p>
            </div>
        </body>
        </html>";
    }

    private function getWelcomeEmailTextTemplate($user)
    {
        $roleText = $user->role === 'apprenant' ? 'Apprenant' : 'Formateur';
        $dashboardUrl = $user->role === 'apprenant' ? route('apprenant.dashboard') : route('formateur.dashboard');
        
        return "
        Bienvenue sur AfriCode !
        
        Bonjour {$user->first_name} {$user->last_name},
        
        Félicitations ! Votre compte AfriCode a été créé avec succès. Vous faites maintenant partie de notre communauté tech africaine qui façonne l'avenir numérique du continent.
        
        Vos informations de compte :
        - Nom complet : {$user->first_name} {$user->last_name}
        - Email : {$user->email}
        - Profil : {$roleText}
        - Date d'inscription : " . $user->created_at->format('d/m/Y') . "
        
        Prochaines étapes :
        1. Accédez à votre tableau de bord pour découvrir nos formations
        2. Parcourez notre catalogue de cours et certifications
        3. Rejoignez notre communauté sur les réseaux sociaux
        4. Participez aux compétitions et challenges
        
        Accéder à votre tableau de bord : {$dashboardUrl}
        
        Conseils pour bien démarrer :
        - Complétez votre profil avec une photo et une bio
        - Explorez les formations gratuites pour commencer
        - Rejoignez nos forums pour échanger avec la communauté
        - Suivez nos actualités sur les réseaux sociaux
        
        Suivez-nous sur les réseaux sociaux :
        - Facebook : https://www.facebook.com/profile.php?id=61574681464943
        - Instagram : https://www.instagram.com/africode1?igsh=YzljYTk1ODg3Zg==
        - LinkedIn : https://www.linkedin.com/company/africode-no-code-no-future/
        - YouTube : https://youtube.com/@africodetechsm?si=k8Y49FJR3tib1KNc
        
        Besoin d'aide ?
        Notre équipe support est là pour vous accompagner :
        - Email : contact@africode.tech
        - Horaires : Lun - Ven : 8h - 18h
        
        AfriCode - No Code, No Future
        Développé avec amour pour la communauté tech africaine";
    }
}
