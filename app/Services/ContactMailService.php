<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Str;

class ContactMailService
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
                config('mail.from.name', 'AfriCode Contact')
            );
        } catch (Exception $e) {
            \Log::error('Erreur configuration PHPMailer Contact: ' . $e->getMessage());
        }
    }

    public function sendContactEmail($contactData)
    {
        try {
            // Email pour l'administrateur
            $this->mailer->addAddress('africodesm@gmail.com'); // Ton email
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Nouveau message de contact - ' . $contactData['subject'];

            $this->mailer->Body = $this->getContactEmailTemplate($contactData);
            $this->mailer->AltBody = $this->getContactEmailTextTemplate($contactData);

            $this->mailer->send();
            
            // Réinitialiser pour l'email de confirmation
            $this->mailer->clearAddresses();
            
            // Email de confirmation pour l'utilisateur
            $this->mailer->addAddress($contactData['email']);
            $this->mailer->Subject = 'Confirmation de votre message - AfriCode';
            $this->mailer->Body = $this->getConfirmationEmailTemplate($contactData);
            $this->mailer->AltBody = $this->getConfirmationEmailTextTemplate($contactData);
            
            $this->mailer->send();
            
            return true;
        } catch (Exception $e) {
            \Log::error('Erreur envoi email contact: ' . $e->getMessage());
            return false;
        }
    }

    public function subscribeToNewsletter($email)
    {
        try {
            // Vérifier si l'email existe déjà
            $existingSubscriber = NewsletterSubscriber::where('email', $email)->first();
            
            if ($existingSubscriber) {
                return ['success' => false, 'message' => 'Cet email est déjà inscrit à la newsletter.'];
            }

            // Créer un nouvel abonné
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'unsubscribe_token' => Str::random(32)
            ]);

            // Envoyer l'email de bienvenue
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Bienvenue à la newsletter AfriCode !';

            $unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]);
            
            $this->mailer->Body = $this->getWelcomeEmailTemplate($email, $unsubscribeUrl);
            $this->mailer->AltBody = $this->getWelcomeEmailTextTemplate($email, $unsubscribeUrl);

            $this->mailer->send();

            return ['success' => true, 'message' => 'Inscription à la newsletter réussie !'];
        } catch (Exception $e) {
            \Log::error('Erreur inscription newsletter: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de l\'inscription à la newsletter.'];
        }
    }

    private function getContactEmailTemplate($contactData)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Nouveau message de contact</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #1EA38B;'>📧 Nouveau message de contact</h1>
                </div>
                
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h3 style='color: #1EA38B; margin-top: 0;'>Informations du contact :</h3>
                    <p><strong>Nom complet :</strong> {$contactData['firstName']} {$contactData['lastName']}</p>
                    <p><strong>Email :</strong> {$contactData['email']}</p>
                    <p><strong>Sujet :</strong> {$contactData['subject']}</p>
                </div>
                
                <div style='background-color: #fff; padding: 20px; border-radius: 8px; border-left: 4px solid #1EA38B;'>
                    <h3 style='color: #1EA38B; margin-top: 0;'>Message :</h3>
                    <p style='white-space: pre-wrap;'>" . htmlspecialchars($contactData['message']) . "</p>
                </div>
                
                <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                
                <p style='font-size: 12px; color: #666;'>
                    Ce message a été envoyé depuis le formulaire de contact d'AfriCode.<br>
                    Répondez directement à cet email pour contacter {$contactData['firstName']} {$contactData['lastName']}.
                </p>
            </div>
        </body>
        </html>";
    }

    private function getContactEmailTextTemplate($contactData)
    {
        return "
        Nouveau message de contact - AfriCode
        
        Informations du contact :
        - Nom complet : {$contactData['firstName']} {$contactData['lastName']}
        - Email : {$contactData['email']}
        - Sujet : {$contactData['subject']}
        
        Message :
        " . $contactData['message'] . "
        
        Ce message a été envoyé depuis le formulaire de contact d'AfriCode.
        Répondez directement à cet email pour contacter {$contactData['firstName']} {$contactData['lastName']}.";
    }

    private function getConfirmationEmailTemplate($contactData)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Confirmation de votre message</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #1EA38B;'> Message reçu !</h1>
                </div>
                
                <p>Bonjour {$contactData['firstName']},</p>
                
                <p>Nous avons bien reçu votre message et nous vous en remercions. Notre équipe va l'étudier et vous répondre dans les plus brefs délais.</p>
                
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h3 style='color: #1EA38B; margin-top: 0;'>Récapitulatif de votre message :</h3>
                    <p><strong>Sujet :</strong> {$contactData['subject']}</p>
                    <p><strong>Message :</strong></p>
                    <p style='background: white; padding: 15px; border-radius: 5px; border-left: 3px solid #1EA38B;'>" . htmlspecialchars($contactData['message']) . "</p>
                </div>
                
                <div style='background-color: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h4 style='color: #1EA38B; margin-top: 0;'>📋 Prochaines étapes :</h4>
                    <ul>
                        <li>Notre équipe examine votre demande</li>
                        <li>Nous vous répondons sous 24-48h</li>
                        <li>Si nécessaire, nous planifions un appel</li>
                    </ul>
                </div>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . route('courses.index') . "' 
                       style='background-color: #1EA38B; color: white; padding: 12px 30px; 
                              text-decoration: none; border-radius: 5px; display: inline-block;'>
                        Découvrir nos formations
                    </a>
                </div>
                
                <hr style='border: none; border-top: 1px solid #eee; margin: 30px 0;'>
                
                <p style='font-size: 12px; color: #666;'>
                    AfriCode - No Code, No Future<br>
                    Développé avec ❤️ pour la communauté tech africaine
                </p>
            </div>
        </body>
        </html>";
    }

    private function getConfirmationEmailTextTemplate($contactData)
    {
        return "
        Confirmation de votre message - AfriCode
        
        Bonjour {$contactData['firstName']},
        
        Nous avons bien reçu votre message et nous vous en remercions. Notre équipe va l'étudier et vous répondre dans les plus brefs délais.
        
        Récapitulatif de votre message :
        - Sujet : {$contactData['subject']}
        - Message : {$contactData['message']}
        
        Prochaines étapes :
        - Notre équipe examine votre demande
        - Nous vous répondons sous 24-48h
        - Si nécessaire, nous planifions un appel
        
        Découvrir nos formations : " . route('courses.index') . "
        
        AfriCode - No Code, No Future
        Développé avec amour pour la communauté tech africaine";
    }

    private function getWelcomeEmailTemplate($email, $unsubscribeUrl)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Bienvenue à la newsletter AfriCode</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <h1 style='color: #1EA38B;'> Bienvenue à la newsletter AfriCode !</h1>
                </div>
                
                <p>Bonjour,</p>
                
                <p>Merci de vous être inscrit à notre newsletter ! Vous recevrez désormais nos dernières actualités, 
                conseils de programmation, et offres exclusives directement dans votre boîte mail.</p>
                
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h3 style='color: #1EA38B; margin-top: 0;'>Ce que vous recevrez :</h3>
                    <ul>
                        <li>📚 Nouveaux cours et formations</li>
                        <li>🏆 Compétitions et challenges</li>
                        <li>💡 Conseils et astuces de programmation</li>
                        <li>🎯 Offres exclusives pour nos abonnés</li>
                        <li>📢 Actualités du monde tech</li>
                    </ul>
                </div>
                                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . route('courses.index') . "' 
                       style='background-color: #1EA38B; color: white; padding: 12px 30px; 
                              text-decoration: none; border-radius: 5px; display: inline-block;'>
                        Découvrir nos formations
                    </a>
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
                
                <p style='font-size: 12px; color: #666;'>
                    Si vous ne souhaitez plus recevoir nos emails, 
                    <a href='{$unsubscribeUrl}' style='color: #1EA38B;'>cliquez ici pour vous désabonner</a>.
                </p>
                
                <p style='font-size: 12px; color: #666;'>
                    AfriCode - No Code, No Future<br>
                    Développé avec avec amour pour la communauté tech africaine
                </p>
            </div>
        </body>
        </html>";
    }

    private function getWelcomeEmailTextTemplate($email, $unsubscribeUrl)
    {
        return "
        Bienvenue à la newsletter AfriCode !
        
        Merci de vous être inscrit à notre newsletter ! Vous recevrez désormais nos dernières actualités, 
        conseils de programmation, et offres exclusives directement dans votre boîte mail.
        
        Ce que vous recevrez :
        - Nouveaux cours et formations
        - Compétitions et challenges
        - Conseils et astuces de programmation
        - Offres exclusives pour nos abonnés
        - Actualités du monde tech
        
        Votre adresse email : {$email}
        
        Découvrir nos formations : " . route('courses.index') . "
        
        Suivez-nous sur les réseaux sociaux :
        - Facebook : https://www.facebook.com/profile.php?id=61574681464943
        - Instagram : https://www.instagram.com/africode1?igsh=YzljYTk1ODg3Zg==
        - LinkedIn : https://www.linkedin.com/company/africode-no-code-no-future/
        - YouTube : https://youtube.com/@africodetechsm?si=k8Y49FJR3tib1KNc
        
        Pour vous désabonner : {$unsubscribeUrl}
        
        AfriCode - No Code, No Future
        Développé avec amour pour la communauté tech africaine";
    }
}
