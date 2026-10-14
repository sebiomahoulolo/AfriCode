<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\NewsletterSubscriber;

class NewsletterMailService
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
            $encryption = config('mail.mailers.smtp.encryption') ?? env('MAIL_ENCRYPTION', 'tls');
            $this->mailer->isSMTP();
            $this->mailer->Host = config('mail.mailers.smtp.host', 'smtp.gmail.com');
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = config('mail.mailers.smtp.username');
            $this->mailer->Password = config('mail.mailers.smtp.password');
            $this->mailer->Port = (int) config('mail.mailers.smtp.port', 587);
            $this->mailer->SMTPSecure = $encryption === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : ($encryption === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : '');
            $this->mailer->CharSet = 'UTF-8';

            $this->mailer->setFrom(
                config('mail.mailers.smtp.username') === 'resend'
                    ? config('mail.from.address')
                    : config('mail.mailers.smtp.username'),
                config('mail.from.name', 'AfriCode')
            );
        } catch (Exception $e) {
            \Log::error('Erreur configuration PHPMailer: ' . $e->getMessage());
        }
    }

    public function sendWelcomeEmail(NewsletterSubscriber $subscriber)
    {
        try {
            $this->mailer->addAddress($subscriber->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Bienvenue à la newsletter AfriCode !';

            $unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]);
            
            $this->mailer->Body = $this->getWelcomeEmailTemplate($subscriber->email, $unsubscribeUrl);
            $this->mailer->AltBody = $this->getWelcomeEmailTextTemplate($subscriber->email, $unsubscribeUrl);

            $this->mailer->send();
            return true;
        } catch (\Throwable $e) {
            \Log::error('Erreur envoi email newsletter: ' . $e->getMessage());
            return false;
        }
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
                    <h1 style='color: #11c06d;'>Bienvenue à la newsletter AfriCode !</h1>
                </div>
                
                <p>Bonjour,</p>
                
                <p>Merci de vous être inscrit à notre newsletter ! Vous recevrez désormais nos dernières actualités, 
                conseils de programmation, et offres exclusives directement dans votre boîte mail.</p>
                
                <div style='background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                    <h3 style='color: #11c06d; margin-top: 0;'>Ce que vous recevrez :</h3>
                    <ul style='list-style:none;padding:0;margin:0;'>
                        <li style='padding:5px 0;'><span style='display:inline-block;width:20px;height:20px;background:#11c06d;border-radius:4px;text-align:center;line-height:20px;color:white;font-size:11px;font-weight:bold;margin-right:8px;vertical-align:middle;'>&#10003;</span>Nouveaux cours et formations</li>
                        <li style='padding:5px 0;'><span style='display:inline-block;width:20px;height:20px;background:#11c06d;border-radius:4px;text-align:center;line-height:20px;color:white;font-size:11px;font-weight:bold;margin-right:8px;vertical-align:middle;'>&#10003;</span>Compétitions et challenges</li>
                        <li style='padding:5px 0;'><span style='display:inline-block;width:20px;height:20px;background:#11c06d;border-radius:4px;text-align:center;line-height:20px;color:white;font-size:11px;font-weight:bold;margin-right:8px;vertical-align:middle;'>&#10003;</span>Conseils et astuces de programmation</li>
                        <li style='padding:5px 0;'><span style='display:inline-block;width:20px;height:20px;background:#fea837;border-radius:4px;text-align:center;line-height:20px;color:white;font-size:11px;font-weight:bold;margin-right:8px;vertical-align:middle;'>&#10003;</span>Offres exclusives pour nos abonnés</li>
                        <li style='padding:5px 0;'><span style='display:inline-block;width:20px;height:20px;background:#11c06d;border-radius:4px;text-align:center;line-height:20px;color:white;font-size:11px;font-weight:bold;margin-right:8px;vertical-align:middle;'>&#10003;</span>Actualités du monde tech</li>
                    </ul>
                </div>
                
                
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . route('courses.index') . "' 
                       style='background-color: #11c06d; color: white; padding: 12px 30px; 
                              text-decoration: none; border-radius: 5px; display: inline-block;'>
                        Découvrir nos formations
                    </a>
                </div>
                
                <!-- Réseaux sociaux -->
                <div style='text-align: center; margin: 30px 0;'>
                    <h4 style='color: #11c06d; margin-bottom: 15px;'>Suivez-nous sur les réseaux sociaux</h4>
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
                            ig
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
                    <a href='{$unsubscribeUrl}' style='color: #11c06d;'>cliquez ici pour vous désabonner</a>.
                </p>
                
                <p style='font-size: 12px; color: #666;'>
                    AfriCode - No Code, No Future<br>
                    Développé avec amour pour la communauté tech africaine
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
