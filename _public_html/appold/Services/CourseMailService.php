<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FormateurWithdrawal;
use App\Models\Mentorship;
use App\Models\Payment;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class CourseMailService
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
            Log::error('Erreur configuration PHPMailer CourseMailService: ' . $e->getMessage());
        }
    }

    /**
     * Envoie l'email de notification "Nouveau cours" à un utilisateur (même mécanisme que Newsletter/Contact/Bienvenue).
     */
    public function sendNewCourseNotification(Course $course, User $user): bool
    {
        try {
            $courseUrl = route('courses.show', $course->slug ?? $course->id);
            $unsubscribeUrl = $this->getUnsubscribeUrl($user);

            $htmlBody = View::make('emails.courses.new-course', [
                'course' => $course,
                'user' => $user,
                'courseUrl' => $courseUrl,
                'unsubscribeUrl' => $unsubscribeUrl,
            ])->render();

            $textBody = View::make('emails.courses.new-course-text', [
                'course' => $course,
                'user' => $user,
                'courseUrl' => $courseUrl,
                'unsubscribeUrl' => $unsubscribeUrl,
            ])->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($user->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Nouveau cours disponible sur AfriCode';
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur envoi email nouveau cours: ' . $e->getMessage(), [
                'course_id' => $course->id,
                'user_id' => $user->id,
            ]);
            return false;
        }
    }

    /**
     * Notifie le formateur que son cours a été validé et publié (lien vers son tableau de bord).
     */
    public function sendCourseValidatedToFormateur(Course $course): bool
    {
        $formateur = $course->formateur;
        if (!$formateur || !$formateur->email) {
            Log::warning('Course validé sans formateur ou email', ['course_id' => $course->id]);
            return false;
        }

        try {
            $dashboardUrl = route('formateur.dashboard');
            $courseManageUrl = route('formateur.manage.course', $course->id);

            $htmlBody = View::make('emails.formateur.course-validated', [
                'course' => $course,
                'formateur' => $formateur,
                'dashboardUrl' => $dashboardUrl,
                'courseManageUrl' => $courseManageUrl,
            ])->render();

            $textBody = View::make('emails.formateur.course-validated-text', [
                'course' => $course,
                'formateur' => $formateur,
                'dashboardUrl' => $dashboardUrl,
                'courseManageUrl' => $courseManageUrl,
            ])->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($formateur->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Votre cours a été validé et publié sur AfriCode';
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur envoi email formateur (cours validé): ' . $e->getMessage(), [
                'course_id' => $course->id,
                'formateur_id' => $formateur->id,
            ]);
            return false;
        }
    }

    /**
     * Notifie le formateur que son compte a été validé par l'admin (candidat → actif).
     */
    public function sendFormateurAccountApproved(User $formateur): bool
    {
        if (!$formateur->email) {
            Log::warning('Formateur validé sans email', ['formateur_id' => $formateur->id]);
            return false;
        }

        try {
            $dashboardUrl = route('formateur.dashboard');

            $htmlBody = View::make('emails.formateur.account-approved', [
                'formateur' => $formateur,
                'dashboardUrl' => $dashboardUrl,
            ])->render();

            $textBody = View::make('emails.formateur.account-approved-text', [
                'formateur' => $formateur,
                'dashboardUrl' => $dashboardUrl,
            ])->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($formateur->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Votre compte formateur AfriCode a été validé';
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur envoi email formateur (compte validé): ' . $e->getMessage(), [
                'formateur_id' => $formateur->id,
            ]);
            return false;
        }
    }

    /**
     * Notifie le formateur qu'un étudiant a payé sa formation (message pro et simple).
     */
    public function sendStudentPaidToFormateur(Enrollment $enrollment, Payment $payment): bool
    {
        $course = $enrollment->course;
        $formateur = $course?->formateur;
        $student = $enrollment->user;

        if (!$formateur || !$formateur->email || !$student) {
            Log::warning('Notification formateur (étudiant a payé) ignorée: formateur ou étudiant manquant', [
                'enrollment_id' => $enrollment->id,
            ]);
            return false;
        }

        // Uniquement pour les formations payantes
        if ((float) $payment->amount <= 0) {
            Log::info('Notification formateur ignorée: cours gratuit ou montant nul', [
                'payment_id'    => $payment->id,
                'enrollment_id' => $enrollment->id,
            ]);
            return false;
        }

        try {
            $dashboardUrl = route('formateur.dashboard');
            $courseStudentsUrl = route('formateur.courses.students', $course->id);

            $amountFormatted = number_format((float) $payment->amount, 0, ',', ' ') . ' ' . ($payment->currency ?? 'XOF');
            $studentName = trim($student->first_name . ' ' . $student->last_name) ?: $student->email;

            $htmlBody = View::make('emails.formateur.student-paid', [
                'course' => $course,
                'formateur' => $formateur,
                'student' => $student,
                'studentName' => $studentName,
                'amountFormatted' => $amountFormatted,
                'dashboardUrl' => $dashboardUrl,
                'courseStudentsUrl' => $courseStudentsUrl,
            ])->render();

            $textBody = View::make('emails.formateur.student-paid-text', [
                'course' => $course,
                'formateur' => $formateur,
                'studentName' => $studentName,
                'amountFormatted' => $amountFormatted,
                'dashboardUrl' => $dashboardUrl,
                'courseStudentsUrl' => $courseStudentsUrl,
            ])->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($formateur->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Bonne nouvelle ! Un apprenant vient d\'acheter votre cours';
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur envoi email formateur (étudiant a payé): ' . $e->getMessage(), [
                'enrollment_id' => $enrollment->id,
                'payment_id' => $payment->id,
            ]);
            return false;
        }
    }

    /**
     * Notifie les administrateurs qu'un formateur a fait une demande de retrait.
     */
    public function sendWithdrawalRequestToAdmins(FormateurWithdrawal $withdrawal): bool
    {
        $adminEmails = User::where('role', 'administrateur')->pluck('email')->filter()->unique()->toArray();
        if (empty($adminEmails)) {
            Log::warning('Aucun administrateur trouvé pour notification demande de retrait', ['withdrawal_id' => $withdrawal->id]);
            return false;
        }

        $formateur = $withdrawal->formateur;
        if (!$formateur) {
            $formateur = User::find($withdrawal->formateur_id);
        }
        $formateurName = $formateur ? trim($formateur->first_name . ' ' . $formateur->last_name) : 'Formateur #' . $withdrawal->formateur_id;
        $amountFormatted = number_format((float) $withdrawal->amount, 0, ',', ' ') . ' ' . ($withdrawal->currency ?? 'XOF');
        $withdrawalsUrl = route('admin.formateurs.withdrawals');

        try {
            $htmlBody = View::make('emails.admin.withdrawal-request', [
                'withdrawal' => $withdrawal,
                'formateur' => $formateur,
                'formateurName' => $formateurName,
                'amountFormatted' => $amountFormatted,
                'withdrawalsUrl' => $withdrawalsUrl,
            ])->render();

            $textBody = View::make('emails.admin.withdrawal-request-text', [
                'withdrawal' => $withdrawal,
                'formateurName' => $formateurName,
                'amountFormatted' => $amountFormatted,
                'withdrawalsUrl' => $withdrawalsUrl,
            ])->render();

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Demande de retrait formateur – ' . $formateurName . ' – ' . $amountFormatted;
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            foreach ($adminEmails as $email) {
                try {
                    $this->mailer->clearAddresses();
                    $this->mailer->addAddress($email);
                    $this->mailer->send();
                } catch (Exception $e) {
                    Log::error('Erreur envoi email admin (demande retrait): ' . $e->getMessage(), [
                        'withdrawal_id' => $withdrawal->id,
                        'email' => $email,
                    ]);
                }
            }
            return true;
        } catch (Exception $e) {
            Log::error('Erreur préparation email admin (demande retrait): ' . $e->getMessage(), ['withdrawal_id' => $withdrawal->id]);
            return false;
        }
    }

    /**
     * Notifie les administrateurs qu'un formateur a soumis un cours pour validation.
     */
    public function sendCourseSubmittedToAdmins(Course $course): bool
    {
        $adminEmails = User::where('role', 'administrateur')->pluck('email')->filter()->unique()->toArray();
        if (empty($adminEmails)) {
            Log::warning('Aucun administrateur trouvé pour notification cours soumis', ['course_id' => $course->id]);
            return false;
        }

        $formateur = $course->formateur;
        if (!$formateur) {
            $formateur = User::find($course->formateur_id);
        }
        $formateurName = $formateur ? trim($formateur->first_name . ' ' . $formateur->last_name) : 'Formateur #' . $course->formateur_id;
        $courseUrl = route('admin.courses.show', $course);

        try {
            $htmlBody = View::make('emails.admin.course-submitted', [
                'course' => $course,
                'formateur' => $formateur,
                'formateurName' => $formateurName,
                'courseUrl' => $courseUrl,
            ])->render();

            $textBody = View::make('emails.admin.course-submitted-text', [
                'course' => $course,
                'formateurName' => $formateurName,
                'courseUrl' => $courseUrl,
            ])->render();

            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Cours soumis pour validation – ' . $course->title;
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            foreach ($adminEmails as $email) {
                try {
                    $this->mailer->clearAddresses();
                    $this->mailer->addAddress($email);
                    $this->mailer->send();
                } catch (Exception $e) {
                    Log::error('Erreur envoi email admin (cours soumis): ' . $e->getMessage(), [
                        'course_id' => $course->id,
                        'email' => $email,
                    ]);
                }
            }
            return true;
        } catch (Exception $e) {
            Log::error('Erreur préparation email admin (cours soumis): ' . $e->getMessage(), ['course_id' => $course->id]);
            return false;
        }
    }

    /**
     * Envoie la notification "Nouveau cours" à une adresse email (ex. abonné newsletter, candidat recrutement).
     */
    public function sendNewCourseNotificationToEmail(Course $course, string $email, ?NewsletterSubscriber $subscriber = null, ?string $recipientName = null): bool
    {
        try {
            $courseUrl = route('courses.show', $course->slug ?? $course->id);
            $unsubscribeUrl = $subscriber && $subscriber->unsubscribe_token
                ? route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token])
                : route('courses.index');

            $firstName = $recipientName ? explode(' ', $recipientName)[0] : 'Abonné';
            $fakeUser = (object) ['email' => $email, 'first_name' => $firstName, 'id' => 0];

            $htmlBody = View::make('emails.courses.new-course', [
                'course' => $course,
                'user' => $fakeUser,
                'courseUrl' => $courseUrl,
                'unsubscribeUrl' => $unsubscribeUrl,
            ])->render();

            $textBody = View::make('emails.courses.new-course-text', [
                'course' => $course,
                'user' => $fakeUser,
                'courseUrl' => $courseUrl,
                'unsubscribeUrl' => $unsubscribeUrl,
            ])->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Nouveau cours disponible sur AfriCode — ' . $course->title;
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur envoi email nouveau cours (newsletter): ' . $e->getMessage(), [
                'course_id' => $course->id,
                'email' => $email,
            ]);
            return false;
        }
    }

    /**
     * Notifie le formateur qu'un apprenant a fait une demande de mentorat.
     */
    public function sendMentorshipRequestToFormateur(Mentorship $mentorship): bool
    {
        $formateur = $mentorship->mentor;
        $mentee    = $mentorship->mentee;

        if (!$formateur || !$formateur->email || !$mentee) {
            Log::warning('sendMentorshipRequestToFormateur: mentor ou mentee manquant', ['mentorship_id' => $mentorship->id]);
            return false;
        }

        try {
            $requestsUrl = route('mentorship.requests');

            $htmlBody = View::make('emails.mentorship.new-request', compact('mentorship', 'formateur', 'mentee', 'requestsUrl'))->render();
            $textBody = View::make('emails.mentorship.new-request-text', compact('mentorship', 'formateur', 'mentee', 'requestsUrl'))->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($formateur->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Nouvelle demande de mentorat — AfriCode';
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur email demande mentorat (formateur): ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            return false;
        }
    }

    /**
     * Notifie l'apprenant que sa demande de mentorat a été approuvée.
     */
    public function sendMentorshipApprovedToApprenant(Mentorship $mentorship): bool
    {
        $formateur = $mentorship->mentor;
        $mentee    = $mentorship->mentee;

        if (!$mentee || !$mentee->email || !$formateur) {
            Log::warning('sendMentorshipApprovedToApprenant: mentor ou mentee manquant', ['mentorship_id' => $mentorship->id]);
            return false;
        }

        try {
            $mentorshipUrl = route('mentorship.show', $mentorship->id);

            $htmlBody = View::make('emails.mentorship.approved', compact('mentorship', 'formateur', 'mentee', 'mentorshipUrl'))->render();
            $textBody = View::make('emails.mentorship.approved-text', compact('mentorship', 'formateur', 'mentee', 'mentorshipUrl'))->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($mentee->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Votre mentorat a été approuvé — AfriCode';
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur email mentorat approuvé (apprenant): ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            return false;
        }
    }

    /**
     * Notifie l'apprenant que sa demande de mentorat a été refusée.
     */
    public function sendMentorshipDeclinedToApprenant(Mentorship $mentorship): bool
    {
        $formateur = $mentorship->mentor;
        $mentee    = $mentorship->mentee;

        if (!$mentee || !$mentee->email || !$formateur) {
            Log::warning('sendMentorshipDeclinedToApprenant: mentor ou mentee manquant', ['mentorship_id' => $mentorship->id]);
            return false;
        }

        try {
            $mentorsUrl = route('mentorship.mentors');

            $htmlBody = View::make('emails.mentorship.declined', compact('mentorship', 'formateur', 'mentee', 'mentorsUrl'))->render();
            $textBody = View::make('emails.mentorship.declined-text', compact('mentorship', 'formateur', 'mentee', 'mentorsUrl'))->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($mentee->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Réponse à votre demande de mentorat — AfriCode';
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur email mentorat refusé (apprenant): ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            return false;
        }
    }

    /**
     * Envoie la confirmation d'inscription à l'apprenant (cours gratuit ou payant).
     */
    public function sendEnrollmentConfirmedToStudent(Enrollment $enrollment, ?Payment $payment = null): bool
    {
        $user   = $enrollment->user;
        $course = $enrollment->course;

        if (!$user || !$user->email || !$course) {
            Log::warning('sendEnrollmentConfirmedToStudent: user ou course manquant', ['enrollment_id' => $enrollment->id]);
            return false;
        }

        try {
            $courseUrl = route('apprenant.course.access', ['courseId' => $course->id]);
            $isFree    = ($payment === null || (float) $payment->amount <= 0);

            $htmlBody = View::make('emails.enrollment.confirmed', compact('enrollment', 'user', 'course', 'payment', 'isFree', 'courseUrl'))->render();
            $textBody = View::make('emails.enrollment.confirmed-text', compact('enrollment', 'user', 'course', 'payment', 'isFree', 'courseUrl'))->render();

            $this->mailer->clearAddresses();
            $this->mailer->addAddress($user->email);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Votre inscription est confirmée — ' . $course->title;
            $this->mailer->Body    = $htmlBody;
            $this->mailer->AltBody = $textBody;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            Log::error('Erreur email confirmation inscription: ' . $e->getMessage(), ['enrollment_id' => $enrollment->id]);
            return false;
        }
    }

    private function getUnsubscribeUrl(User $user): string
    {
        $subscriber = NewsletterSubscriber::where('email', $user->email)->first();
        if ($subscriber && $subscriber->unsubscribe_token) {
            return route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]);
        }
        return route('courses.index');
    }
}
