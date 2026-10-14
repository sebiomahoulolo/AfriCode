<?php

namespace App\Services;

use App\Mail\GenericMail;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\FormateurWithdrawal;
use App\Models\Mentorship;
use App\Models\Payment;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CourseMailService
{
    public function sendNewCourseNotification(Course $course, User $user): bool
    {
        try {
            $data = [
                'course'         => $course,
                'user'           => $user,
                'courseUrl'      => route('courses.show', $course->slug ?? $course->id),
                'unsubscribeUrl' => $this->getUnsubscribeUrl($user),
            ];

            Mail::to($user->email)->queue(new GenericMail(
                'emails.courses.new-course',
                'emails.courses.new-course-text',
                $data,
                'Nouveau cours disponible sur AfriCode'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email nouveau cours: ' . $e->getMessage(), [
                'course_id' => $course->id,
                'user_id'   => $user->id,
            ]);
            return false;
        }
    }

    public function sendCourseValidatedToFormateur(Course $course): bool
    {
        $formateur = $course->formateur;
        if (!$formateur || !$formateur->email) {
            Log::warning('Course validé sans formateur ou email', ['course_id' => $course->id]);
            return false;
        }

        try {
            $data = [
                'course'           => $course,
                'formateur'        => $formateur,
                'dashboardUrl'     => route('formateur.dashboard'),
                'courseManageUrl'  => route('formateur.manage.course', $course->id),
            ];

            Mail::to($formateur->email)->queue(new GenericMail(
                'emails.formateur.course-validated',
                'emails.formateur.course-validated-text',
                $data,
                'Votre cours a été validé et publié sur AfriCode'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email formateur (cours validé): ' . $e->getMessage(), [
                'course_id'    => $course->id,
                'formateur_id' => $formateur->id,
            ]);
            return false;
        }
    }

    public function sendFormateurAccountApproved(User $formateur): bool
    {
        if (!$formateur->email) {
            Log::warning('Formateur validé sans email', ['formateur_id' => $formateur->id]);
            return false;
        }

        try {
            $data = [
                'formateur'    => $formateur,
                'dashboardUrl' => route('formateur.dashboard'),
            ];

            Mail::to($formateur->email)->queue(new GenericMail(
                'emails.formateur.account-approved',
                'emails.formateur.account-approved-text',
                $data,
                'Votre compte formateur AfriCode a été validé'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email formateur (compte validé): ' . $e->getMessage(), [
                'formateur_id' => $formateur->id,
            ]);
            return false;
        }
    }

    public function sendStudentPaidToFormateur(Enrollment $enrollment, Payment $payment): bool
    {
        $course    = $enrollment->course;
        $formateur = $course?->formateur;
        $student   = $enrollment->user;

        if (!$formateur || !$formateur->email || !$student) {
            Log::warning('Notification formateur (étudiant a payé) ignorée: formateur ou étudiant manquant', [
                'enrollment_id' => $enrollment->id,
            ]);
            return false;
        }

        if ((float) $payment->amount <= 0) {
            Log::info('Notification formateur ignorée: cours gratuit ou montant nul', [
                'payment_id'    => $payment->id,
                'enrollment_id' => $enrollment->id,
            ]);
            return false;
        }

        try {
            $data = [
                'course'            => $course,
                'formateur'         => $formateur,
                'student'           => $student,
                'studentName'       => trim($student->first_name . ' ' . $student->last_name) ?: $student->email,
                'amountFormatted'   => number_format((float) $payment->amount, 0, ',', ' ') . ' ' . ($payment->currency ?? 'XOF'),
                'dashboardUrl'      => route('formateur.dashboard'),
                'courseStudentsUrl' => route('formateur.courses.students', $course->id),
            ];

            Mail::to($formateur->email)->queue(new GenericMail(
                'emails.formateur.student-paid',
                'emails.formateur.student-paid-text',
                $data,
                "Bonne nouvelle ! Un apprenant vient d'acheter votre cours"
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email formateur (étudiant a payé): ' . $e->getMessage(), [
                'enrollment_id' => $enrollment->id,
                'payment_id'    => $payment->id,
            ]);
            return false;
        }
    }

    public function sendWithdrawalRequestToAdmins(FormateurWithdrawal $withdrawal): bool
    {
        $adminEmails = User::where('role', 'administrateur')->pluck('email')->filter()->unique()->toArray();
        if (empty($adminEmails)) {
            Log::warning('Aucun administrateur trouvé pour notification demande de retrait', ['withdrawal_id' => $withdrawal->id]);
            return false;
        }

        $formateur       = $withdrawal->formateur ?? User::find($withdrawal->formateur_id);
        $formateurName   = $formateur ? trim($formateur->first_name . ' ' . $formateur->last_name) : 'Formateur #' . $withdrawal->formateur_id;
        $amountFormatted = number_format((float) $withdrawal->amount, 0, ',', ' ') . ' ' . ($withdrawal->currency ?? 'XOF');

        $data = [
            'withdrawal'      => $withdrawal,
            'formateur'       => $formateur,
            'formateurName'   => $formateurName,
            'amountFormatted' => $amountFormatted,
            'withdrawalsUrl'  => route('admin.formateurs.withdrawals'),
        ];

        $subject = 'Demande de retrait formateur – ' . $formateurName . ' – ' . $amountFormatted;

        foreach ($adminEmails as $email) {
            try {
                Mail::to($email)->queue(new GenericMail(
                    'emails.admin.withdrawal-request',
                    'emails.admin.withdrawal-request-text',
                    $data,
                    $subject
                ));
            } catch (\Exception $e) {
                Log::error('Erreur envoi email admin (demande retrait): ' . $e->getMessage(), [
                    'withdrawal_id' => $withdrawal->id,
                    'email'         => $email,
                ]);
            }
        }

        return true;
    }

    public function sendCourseSubmittedToAdmins(Course $course): bool
    {
        $adminEmails = User::where('role', 'administrateur')->pluck('email')->filter()->unique()->toArray();
        if (empty($adminEmails)) {
            Log::warning('Aucun administrateur trouvé pour notification cours soumis', ['course_id' => $course->id]);
            return false;
        }

        $formateur     = $course->formateur ?? User::find($course->formateur_id);
        $formateurName = $formateur ? trim($formateur->first_name . ' ' . $formateur->last_name) : 'Formateur #' . $course->formateur_id;

        $data = [
            'course'        => $course,
            'formateur'     => $formateur,
            'formateurName' => $formateurName,
            'courseUrl'     => route('admin.courses.show', $course),
        ];

        $subject = 'Cours soumis pour validation – ' . $course->title;

        foreach ($adminEmails as $email) {
            try {
                Mail::to($email)->queue(new GenericMail(
                    'emails.admin.course-submitted',
                    'emails.admin.course-submitted-text',
                    $data,
                    $subject
                ));
            } catch (\Exception $e) {
                Log::error('Erreur envoi email admin (cours soumis): ' . $e->getMessage(), [
                    'course_id' => $course->id,
                    'email'     => $email,
                ]);
            }
        }

        return true;
    }

    public function sendNewCourseNotificationToEmail(Course $course, string $email, ?NewsletterSubscriber $subscriber = null, ?string $recipientName = null): bool
    {
        try {
            $unsubscribeUrl = $subscriber?->unsubscribe_token
                ? route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token])
                : route('courses.index');

            $firstName = $recipientName ? explode(' ', $recipientName)[0] : 'Abonné';

            $data = [
                'course'         => $course,
                'user'           => ['email' => $email, 'first_name' => $firstName],
                'courseUrl'      => route('courses.show', $course->slug ?? $course->id),
                'unsubscribeUrl' => $unsubscribeUrl,
            ];

            Mail::to($email)->queue(new GenericMail(
                'emails.courses.new-course',
                'emails.courses.new-course-text',
                $data,
                'Nouveau cours disponible sur AfriCode — ' . $course->title
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email nouveau cours (newsletter): ' . $e->getMessage(), [
                'course_id' => $course->id,
                'email'     => $email,
            ]);
            return false;
        }
    }

    public function sendMentorshipRequestToFormateur(Mentorship $mentorship): bool
    {
        $formateur = $mentorship->mentor;
        $mentee    = $mentorship->mentee;

        if (!$formateur || !$formateur->email || !$mentee) {
            Log::warning('sendMentorshipRequestToFormateur: mentor ou mentee manquant', ['mentorship_id' => $mentorship->id]);
            return false;
        }

        try {
            $data = compact('mentorship', 'formateur', 'mentee') + ['requestsUrl' => route('mentorship.requests')];

            Mail::to($formateur->email)->queue(new GenericMail(
                'emails.mentorship.new-request',
                'emails.mentorship.new-request-text',
                $data,
                'Nouvelle demande de mentorat — AfriCode'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur email demande mentorat (formateur): ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            return false;
        }
    }

    public function sendMentorshipApprovedToApprenant(Mentorship $mentorship): bool
    {
        $formateur = $mentorship->mentor;
        $mentee    = $mentorship->mentee;

        if (!$mentee || !$mentee->email || !$formateur) {
            Log::warning('sendMentorshipApprovedToApprenant: mentor ou mentee manquant', ['mentorship_id' => $mentorship->id]);
            return false;
        }

        try {
            $data = compact('mentorship', 'formateur', 'mentee') + ['mentorshipUrl' => route('mentorship.show', $mentorship->id)];

            Mail::to($mentee->email)->queue(new GenericMail(
                'emails.mentorship.approved',
                'emails.mentorship.approved-text',
                $data,
                'Votre mentorat a été approuvé — AfriCode'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur email mentorat approuvé (apprenant): ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            return false;
        }
    }

    public function sendMentorshipDeclinedToApprenant(Mentorship $mentorship): bool
    {
        $formateur = $mentorship->mentor;
        $mentee    = $mentorship->mentee;

        if (!$mentee || !$mentee->email || !$formateur) {
            Log::warning('sendMentorshipDeclinedToApprenant: mentor ou mentee manquant', ['mentorship_id' => $mentorship->id]);
            return false;
        }

        try {
            $data = compact('mentorship', 'formateur', 'mentee') + ['mentorsUrl' => route('mentorship.mentors')];

            Mail::to($mentee->email)->queue(new GenericMail(
                'emails.mentorship.declined',
                'emails.mentorship.declined-text',
                $data,
                'Réponse à votre demande de mentorat — AfriCode'
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur email mentorat refusé (apprenant): ' . $e->getMessage(), ['mentorship_id' => $mentorship->id]);
            return false;
        }
    }

    public function sendEnrollmentConfirmedToStudent(Enrollment $enrollment, ?Payment $payment = null): bool
    {
        $user   = $enrollment->user;
        $course = $enrollment->course;

        if (!$user || !$user->email || !$course) {
            Log::warning('sendEnrollmentConfirmedToStudent: user ou course manquant', ['enrollment_id' => $enrollment->id]);
            return false;
        }

        try {
            $isFree = ($payment === null || (float) $payment->amount <= 0);
            $data   = compact('enrollment', 'user', 'course', 'payment', 'isFree') + [
                'courseUrl' => route('apprenant.course.access', ['courseId' => $course->id]),
            ];

            Mail::to($user->email)->queue(new GenericMail(
                'emails.enrollment.confirmed',
                'emails.enrollment.confirmed-text',
                $data,
                'Votre inscription est confirmée — ' . $course->title
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur email confirmation inscription: ' . $e->getMessage(), ['enrollment_id' => $enrollment->id]);
            return false;
        }
    }

    private function getUnsubscribeUrl(User $user): string
    {
        $subscriber = NewsletterSubscriber::where('email', $user->email)->first();
        if ($subscriber?->unsubscribe_token) {
            return route('newsletter.unsubscribe', ['token' => $subscriber->unsubscribe_token]);
        }
        return route('courses.index');
    }
}
