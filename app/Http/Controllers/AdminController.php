<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


namespace App\Http\Controllers; // Ou App\Http\Controllers\Admin si vous préférez

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Certification;
use App\Models\Payment;
use App\Models\Message;
use App\Models\Event;
use App\Models\Task;
use Illuminate\Notifications\DatabaseNotification;
    

class AdminController extends Controller
{
public function index()
{
    return view('admin.dashboard');
}

    




// Importez d'autres modèles et façades nécessaires (Auth, DB, Storage, etc.)


    // Méthode constructeur pour appliquer le middleware (alternative au groupe de routes)
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'isAdmin']);
    // }

    // --- Tableau de Bord ---
    public function dashboard()
    {
        // Logique pour récupérer les données du tableau de bord
        // $userCount = User::count();
        // ...
        return view('admin.dashboard' /*, compact('userCount', ...) */);
    }

    // --- Gestion des Utilisateurs ---
    public function usersIndex()
    {
        // $users = User::paginate(10); // Exemple avec pagination
        return view('admin.users.index' /*, compact('users') */);
    }

    public function usersCreate()
    {
        return view('admin.users.create');
    }

    public function usersStore(Request $request)
    {
        // Validation des données $request->validate(...)
        // Création de l'utilisateur User::create(...)
        // Redirection return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé !');
    }

    public function usersShow(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        // Validation $request->validate(...)
        // Mise à jour $user->update(...)
        // Redirection return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour !');
    }

    public function usersDestroy(User $user)
    {
        // Suppression $user->delete();
        // Redirection return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé !');
    }

    public function usersExport()
    {
        // Logique d'exportation (par exemple avec un package comme Laravel Excel)
    }


    // --- Gestion des Cours ---
    public function coursesIndex()
    {
        // $courses = Course::paginate(10);
        return view('admin.courses.index' /*, compact('courses') */);
    }

    public function coursesCreate()
    {
         return view('admin.courses.create');
    }

    public function coursesStore(Request $request)
    {
        // Validation, Création Course::create(...)
        // Redirection
    }

    public function coursesShow(Course $course)
    {
         return view('admin.courses.show', compact('course'));
    }

    public function coursesEdit(Course $course)
    {
         return view('admin.courses.edit', compact('course'));
    }

    public function coursesUpdate(Request $request, Course $course)
    {
        // Validation, Mise à jour $course->update(...)
        // Redirection
    }

    public function coursesDestroy(Course $course)
    {
        // Suppression $course->delete();
        // Redirection
    }


    // --- Gestion des Certifications ---
    public function certificationsIndex()
    {
        // Lister les certifications
        return view('admin.certifications.index');
    }

     public function certificationsShow(Certification $certification)
    {
        // Afficher une certification
        return view('admin.certifications.show', compact('certification'));
    }

    // public function certificationsGenerate(Request $request) { /* Logique de génération */ }


    // --- Statistiques ---
    public function statisticsIndex()
    {
        // Calculer/Récupérer les statistiques
        return view('admin.statistics.index');
    }


    // --- Paiements ---
    public function paymentsIndex()
    {
        // Lister les paiements
        return view('admin.payments.index');
    }

    public function paymentsShow(Payment $payment)
    {
        // Afficher détails paiement
        return view('admin.payments.show', compact('payment'));
    }

    public function paymentsExport()
    {
        // Logique d'exportation
    }


    // --- Messages ---
    public function messagesIndex()
    {
        // Lister les messages
        return view('admin.messages.index');
    }

    public function messagesShow(Message $message)
    {
        // Afficher un message
        return view('admin.messages.show', compact('message'));
    }

    public function messagesDestroy(Message $message)
    {
        // Supprimer message
        // Redirection
    }

    // public function messagesReply(Request $request, Message $message) { /* Logique de réponse */ }


    // --- Événements (Calendrier) ---
    public function eventsIndex()
    {
        // Récupérer événements pour le calendrier (souvent via une requête API pour JS)
        // Peut retourner du JSON ou une vue de base
        // $events = Event::all();
        // return response()->json($events); ou return view('admin.events.index', compact('events'));
         return view('admin.events.index'); // Ou une vue pour le calendrier complet
    }

    public function eventsCreate() { return view('admin.events.create'); }
    public function eventsStore(Request $request) { /* Validation, Création, Redirection */ }
    public function eventsShow(Event $event) { /* Retourner détails pour modal ? */ }
    public function eventsEdit(Event $event) { return view('admin.events.edit', compact('event')); }
    public function eventsUpdate(Request $request, Event $event) { /* Validation, Mise à jour, Redirection */ }
    public function eventsDestroy(Event $event) { /* Suppression, Redirection ou JSON */ }


    // --- Tâches ---
    public function tasksIndex() { /* Lister tâches ? Ou géré dans le dashboard */ }
    public function tasksCreate() { return view('admin.tasks.create'); } // Probablement une modale
    public function tasksStore(Request $request) { /* Validation, Création */ }
    public function tasksEdit(Task $task) { return view('admin.tasks.edit', compact('task')); } // Probablement une modale
    public function tasksUpdate(Request $request, Task $task) { /* Validation, Mise à jour */ }
    public function tasksDestroy(Task $task) { /* Suppression */ }
    public function tasksMarkComplete(Task $task)
    {
        // $task->update(['completed' => true]); // Ou statut différent
        // Retourner réponse JSON ou rediriger
    }


    // --- Notifications ---
    public function notificationsIndex() { /* Lister notifications pour une page dédiée si besoin */ }
    public function notificationsMarkAllRead()
    {
        // auth()->user()->unreadNotifications->markAsRead();
        // Rediriger
    }
    public function notificationsMarkRead(DatabaseNotification $notification)
    {
        // $notification->markAsRead();
        // Rediriger ou réponse JSON
    }
    public function notificationsDestroy(DatabaseNotification $notification)
    {
        // $notification->delete();
        // Rediriger
    }


    // --- Paramètres ---
    public function settingsEdit()
    {
        // Récupérer les paramètres (via un package, config, ou table BDD)
        return view('admin.settings.edit');
    }

    public function settingsUpdate(Request $request)
    {
        // Validation
        // Sauvegarde des paramètres
        // Redirection avec message de succès
    }

}
