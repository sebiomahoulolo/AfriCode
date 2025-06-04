<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class ModuleLessonController extends Controller
{
    /**
     * Store a newly created module.
     */
    public function modulesStore(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
        ]);
        
        try {
            $module = Module::create($validated);
            
            // Create notification
            NotificationService::userAction('created', 'module', $module->title, [
                'model_id' => $module->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book',
                'color' => 'success'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Module ajouté avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'module', $validated['title']);
        }
    }
    
    /**
     * Show the form for editing the specified module.
     */
    public function modulesEdit(Module $module)
    {
        $course = $module->course;
        return view('admin.modules.edit', compact('module', 'course'));
    }
    
    /**
     * Update the specified module.
     */
    public function modulesUpdate(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:1',
        ]);
        
        try {
            $module->update($validated);
            
            // Create notification
            NotificationService::userAction('updated', 'module', $module->title, [
                'model_id' => $module->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book',
                'color' => 'info'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Module mis à jour avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'updated', 'module', $module->title);
        }
    }
    
    /**
     * Remove the specified module.
     */
    public function modulesDestroy(Module $module)
    {
        try {
            $courseId = $module->course_id;
            $title = $module->title;
            
            // Delete all lessons and quizzes associated with this module
            foreach ($module->lessons as $lesson) {
                if ($lesson->type === 'pdf' && Storage::disk('public')->exists($lesson->content)) {
                    Storage::disk('public')->delete($lesson->content);
                }
                $lesson->delete();
            }
            
            // Delete all quizzes
            $module->quizzes()->delete();
            
            // Delete the module
            $module->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'module', $title, [
                'action_url' => route('admin.courses.show', $courseId),
                'action_text' => 'Voir le cours',
                'icon' => 'trash',
                'color' => 'danger'
            ]);
            
            return redirect()->route('admin.courses.show', $courseId)
                ->with('success', 'Module supprimé avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'module', $module->title);
        }
    }
    
    //------------------------------------------------------------------------------
    // GESTION DES LEÇONS
    //------------------------------------------------------------------------------
    
    /**
     * Store a newly created lesson.
     */
    public function lessonsStore(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,pdf',
            'order' => 'nullable|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'video_url' => 'nullable|required_if:type,video|url',
            'text_content' => 'nullable|required_if:type,text|string',
            'pdf_file' => 'nullable|required_if:type,pdf|file|mimes:pdf|max:10240',
        ]);
        
        try {
            $lesson = new Lesson();
            $lesson->module_id = $validated['module_id'];
            $lesson->title = $validated['title'];
            $lesson->description = $validated['description'] ?? '';
            $lesson->type = $validated['type'];
            $lesson->order = $validated['order'] ?? Lesson::where('module_id', $validated['module_id'])->max('order') + 1;
            $lesson->duration = $validated['duration'] ?? null;
            
            // Handle content based on type
            if ($validated['type'] === 'video') {
                $lesson->content = $validated['video_url'];
            } elseif ($validated['type'] === 'text') {
                $lesson->content = $validated['text_content'];
            } elseif ($validated['type'] === 'pdf' && $request->hasFile('pdf_file')) {
                $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
                $lesson->content = $path;
            }
            
            $lesson->save();
            
            // Get module to access course
            $module = Module::findOrFail($validated['module_id']);
            
            // Create notification
            NotificationService::userAction('created', 'lesson', $lesson->title, [
                'model_id' => $lesson->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book-open',
                'color' => 'success'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon ajoutée avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'lesson', $validated['title']);
        }
    }
    
    /**
     * Show the form for editing the specified lesson.
     */
    public function lessonsEdit(Lesson $lesson)
    {
        $module = $lesson->module;
        $course = $module->course;
        return view('admin.lessons.edit', compact('lesson', 'module', 'course'));
    }
    
    /**
     * Update the specified lesson.
     */
    public function lessonsUpdate(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:video,text,pdf',
            'order' => 'nullable|integer|min:1',
            'duration' => 'nullable|integer|min:1',
            'video_url' => 'nullable|required_if:type,video|url',
            'text_content' => 'nullable|required_if:type,text|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);
        
        try {
            $lesson->title = $validated['title'];
            $lesson->description = $validated['description'] ?? '';
            $lesson->type = $validated['type'];
            $lesson->order = $validated['order'] ?? $lesson->order;
            $lesson->duration = $validated['duration'] ?? $lesson->duration;
            
            // Handle content based on type
            if ($validated['type'] === 'video') {
                $lesson->content = $validated['video_url'];
            } elseif ($validated['type'] === 'text') {
                $lesson->content = $validated['text_content'];
            } elseif ($validated['type'] === 'pdf' && $request->hasFile('pdf_file')) {
                // Delete old file if exists and is a PDF file
                if ($lesson->type === 'pdf' && Storage::disk('public')->exists($lesson->content)) {
                    Storage::disk('public')->delete($lesson->content);
                }
                $path = $request->file('pdf_file')->store('lesson_pdfs', 'public');
                $lesson->content = $path;
            }
            
            $lesson->save();
            
            // Get module and course for redirect
            $module = $lesson->module;
            
            // Create notification
            NotificationService::userAction('updated', 'lesson', $lesson->title, [
                'model_id' => $lesson->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'book-open',
                'color' => 'info'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon mise à jour avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'updated', 'lesson', $lesson->title);
        }
    }
    
    /**
     * Remove the specified lesson.
     */
    public function lessonsDestroy(Lesson $lesson)
    {
        try {
            $module = $lesson->module;
            $title = $lesson->title;
            
            // Delete PDF file if exists
            if ($lesson->type === 'pdf' && Storage::disk('public')->exists($lesson->content)) {
                Storage::disk('public')->delete($lesson->content);
            }
            
            // Delete the lesson
            $lesson->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'lesson', $title, [
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'trash',
                'color' => 'danger'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Leçon supprimée avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'lesson', $lesson->title);
        }
    }
    
    //------------------------------------------------------------------------------
    // GESTION DES QUIZ
    //------------------------------------------------------------------------------
    
    /**
     * Store a newly created quiz.
     */
    public function quizzesStore(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'related_type' => 'required|string',
            'related_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
        ]);
        
        try {
            $quiz = new Quiz();
            $quiz->related_type = $validated['related_type'];
            $quiz->related_id = $validated['related_id'];
            $quiz->title = $validated['title'];
            $quiz->description = $validated['description'] ?? '';
            $quiz->passing_score = $validated['passing_score'] ?? 70;
            $quiz->time_limit = $validated['time_limit'] ?? null;
            $quiz->is_required = $request->has('is_required');
            $quiz->save();
            
            // Get module for redirect
            $module = Module::findOrFail($validated['module_id']);
            
            // Create notification
            NotificationService::userAction('created', 'quiz', $quiz->title, [
                'model_id' => $quiz->id,
                'action_url' => route('admin.courses.show', $module->course_id),
                'action_text' => 'Voir le cours',
                'icon' => 'question-circle',
                'color' => 'success'
            ]);
            
            return redirect()->route('admin.courses.show', $module->course_id)
                ->with('success', 'Quiz créé avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'quiz', $validated['title']);
        }
    }
    
    /**
     * Show the form for editing the specified quiz.
     */
    public function quizzesEdit(Quiz $quiz)
    {
        $module = null;
        
        // Determine the parent module/course
        if ($quiz->related_type === 'App\Models\Module') {
            $module = Module::find($quiz->related_id);
            $course = $module ? $module->course : null;
        }
        
        return view('admin.quizzes.edit', compact('quiz', 'module', 'course'));
    }
    
    /**
     * Update the specified quiz.
     */
    public function quizzesUpdate(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
        ]);
        
        try {
            $quiz->title = $validated['title'];
            $quiz->description = $validated['description'] ?? '';
            $quiz->passing_score = $validated['passing_score'] ?? 70;
            $quiz->time_limit = $validated['time_limit'] ?? null;
            $quiz->is_required = $request->has('is_required');
            $quiz->save();
            
            // Get related module for redirect
            $module = null;
            if ($quiz->related_type === 'App\Models\Module') {
                $module = Module::find($quiz->related_id);
            }
            
            // Create notification
            NotificationService::userAction('updated', 'quiz', $quiz->title, [
                'model_id' => $quiz->id,
                'action_url' => $module ? route('admin.courses.show', $module->course_id) : route('admin.dashboard'),
                'action_text' => $module ? 'Voir le cours' : 'Dashboard',
                'icon' => 'question-circle',
                'color' => 'info'
            ]);
            
            return redirect()->route($module ? 'admin.courses.show' : 'admin.dashboard', $module ? $module->course_id : null)
                ->with('success', 'Quiz mis à jour avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'updated', 'quiz', $quiz->title);
        }
    }
    
    /**
     * Remove the specified quiz.
     */
    public function quizzesDestroy(Quiz $quiz)
    {
        try {
            $title = $quiz->title;
            
            // Get related module for redirect
            $module = null;
            if ($quiz->related_type === 'App\Models\Module') {
                $module = Module::find($quiz->related_id);
            }
            
            // Delete the quiz and its questions
            $quiz->questions()->delete();
            $quiz->delete();
            
            // Create notification
            NotificationService::userAction('deleted', 'quiz', $title, [
                'action_url' => $module ? route('admin.courses.show', $module->course_id) : route('admin.dashboard'),
                'action_text' => $module ? 'Voir le cours' : 'Dashboard',
                'icon' => 'trash',
                'color' => 'danger'
            ]);
            
            return redirect()->route($module ? 'admin.courses.show' : 'admin.dashboard', $module ? $module->course_id : null)
                ->with('success', 'Quiz supprimé avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'deleted', 'quiz', $quiz->title);
        }
    }
    
    /**
     * Show the form for creating questions for a quiz.
     */
    public function quizQuestionsCreate(Quiz $quiz)
    {
        return view('admin.quizzes.questions.create', compact('quiz'));
    }
    
    /**
     * Store a newly created quiz question.
     */
    public function quizQuestionsStore(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,short_answer',
            'options' => 'required_if:type,multiple_choice|array',
            'correct_answer' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'explanation' => 'nullable|string',
        ]);
        
        try {
            $question = new Question();
            $question->quiz_id = $quiz->id;
            $question->question = $validated['question'];
            $question->type = $validated['type'];
            
            if ($validated['type'] === 'multiple_choice') {
                $question->options = json_encode($validated['options']);
            } elseif ($validated['type'] === 'true_false') {
                $question->options = json_encode(['true', 'false']);
            }
            
            $question->correct_answer = $validated['correct_answer'];
            $question->points = $validated['points'] ?? 1;
            $question->explanation = $validated['explanation'] ?? null;
            $question->save();
            
            // Get related module for redirect
            $module = null;
            if ($quiz->related_type === 'App\Models\Module') {
                $module = Module::find($quiz->related_id);
            }
            
            return redirect()->route('admin.quizzes.edit', $quiz->id)
                ->with('success', 'Question ajoutée avec succès !');
        } catch (\Exception $e) {
            return $this->handleOperationError($e, 'created', 'question', $validated['question']);
        }
    }
    
    /**
     * Handle error operations.
     *
     * @param \Exception $e
     * @param string $action
     * @param string $modelType
     * @param string $modelName
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleOperationError(\Exception $e, $action, $modelType, $modelName)
    {
        // Log the error
        \Log::error("{$action} error for {$modelType} '{$modelName}': " . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        $actionMap = [
            'created' => 'la création',
            'updated' => 'la mise à jour',
            'deleted' => 'la suppression',
        ];
        
        $modelLabels = [
            'module' => 'du module',
            'lesson' => 'de la leçon',
            'quiz' => 'du quiz',
            'question' => 'de la question',
        ];
        
        $actionVerb = $actionMap[$action] ?? $action;
        $modelLabel = $modelLabels[$modelType] ?? 'de l\'élément';
        
        return redirect()->back()
            ->withInput()
            ->with('error', "Une erreur est survenue lors de {$actionVerb} {$modelLabel}. Veuillez réessayer.");
    }
}
