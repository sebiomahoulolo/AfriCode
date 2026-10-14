@extends('admin.layouts.app')

@section('breadcrumb', 'Détails du cours')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="admin-card" data-aos="fade-up">
        <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="admin-card-title" style="font-size: 1.5rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-book"></i> {{ $course->title }}
                </h1>
                <p class="admin-card-subtitle">
                    @if($course->formateur)
                        Par {{ $course->formateur->first_name }} {{ $course->formateur->last_name }}
        @else
                        Formateur non assigné
        @endif
                </p>
    </div>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('admin.courses.edit', $course) }}" 
                   style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('courses.show', $course->slug) }}" target="_blank" 
                   style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
            <i class="fas fa-eye"></i> Voir sur le site
        </a>
                <a href="{{ route('admin.courses.index') }}" 
                   style="background: #6C757D; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.3s; display: inline-block;">
                    <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>
    </div>

    <!-- Statistiques -->
    <div class="admin-grid admin-grid-4" data-aos="fade-up" data-aos-delay="100" style="margin-bottom: 2rem;">
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="100">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #1EA38B, #27B371);">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="admin-stats-number">{{ $course->enrollments->count() }}</div>
            <div class="admin-stats-label">Inscriptions</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="200">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #27B371, #1EA38B);">
                <i class="fas fa-star"></i>
            </div>
            <div class="admin-stats-number">
                {{ number_format($course->ratings->avg('rating') ?? 0, 1) }}
            </div>
            <div class="admin-stats-label">Note moyenne</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="300">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="admin-stats-number">{{ $course->modules->count() }}</div>
            <div class="admin-stats-label">Modules</div>
        </div>
        
        <div class="admin-stats-card" data-aos="fade-up" data-aos-delay="400">
            <div class="admin-stats-icon" style="background: linear-gradient(135deg, #E32D31, #FF6B6B);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="admin-stats-number">
                @php
                    $totalRevenue = $course->enrollments
                        ->filter(function($enrollment) {
                            return $enrollment->payment && $enrollment->payment->status === 'completed';
                        })
                        ->sum(function($enrollment) {
                            return $enrollment->payment ? $enrollment->payment->amount : 0;
                        });
                @endphp
                {{ number_format($totalRevenue, 0, ',', ' ') }}
            </div>
            <div class="admin-stats-label">Revenus (FCFA)</div>
        </div>
    </div>

    <div class="admin-grid admin-grid-2" style="gap: 2rem; margin-bottom: 2rem;">
        <!-- Colonne gauche -->
        <div>
            <!-- Actions de validation -->
            @if($course->validation_status == 'pending_validation')
                <div class="admin-card" data-aos="fade-up" data-aos-delay="150" style="margin-bottom: 1.5rem; border-left: 4px solid #FF8E2A;">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title" style="color: #FF8E2A;">
                            <i class="fas fa-clock"></i> Cours en attente de validation
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div style="background: #FFF3E0; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="font-size: 0.9rem; color: #E65100; margin-bottom: 0.5rem;">
                                <i class="fas fa-info-circle"></i> <strong>Action requise :</strong> Ce cours a été soumis par le formateur et attend votre validation.
                            </div>
                            @if($course->submitted_for_validation_at)
                                <div style="font-size: 0.85rem; color: #6C757D;">
                                    Soumis le : {{ $course->submitted_for_validation_at->format('d/m/Y à H:i') }}
                                </div>
                            @endif
                        </div>
                        
                        <div style="display: grid; gap: 0.75rem;">
                            <button type="button" onclick="openModal('validateCourseModal')" 
                                    style="background: linear-gradient(135deg, #27B371, #1EA38B); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; width: 100%;">
                                <i class="fas fa-check-circle"></i> Valider et publier le cours
                            </button>
                          
                            <button type="button" onclick="openModal('rejectCourseModal')" 
                                    style="background: linear-gradient(135deg, #E32D31, #FF6B6B); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s; width: 100%;">
                                <i class="fas fa-times-circle"></i> Rejeter le cours
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($course->validation_status == 'needs_revision' && $course->revision_notes)
                <div class="admin-card" data-aos="fade-up" data-aos-delay="150" style="margin-bottom: 1.5rem; border-left: 4px solid #1EA38B;">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title" style="color: #1EA38B;">
                            <i class="fas fa-edit"></i> Corrections demandées
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div style="background: #E3F2FD; padding: 1rem; border-radius: 8px;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Notes pour le formateur :</div>
                            <div style="font-size: 0.9rem; color: #333; line-height: 1.6;">
                                {{ $course->revision_notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if($course->validation_status == 'rejected' && $course->admin_notes)
                <div class="admin-card" data-aos="fade-up" data-aos-delay="150" style="margin-bottom: 1.5rem; border-left: 4px solid #E32D31;">
                    <div class="admin-card-header">
                        <h3 class="admin-card-title" style="color: #E32D31;">
                            <i class="fas fa-times-circle"></i> Cours rejeté
                        </h3>
                    </div>
                    <div class="admin-card-body">
                        <div style="background: #FFEBEE; padding: 1rem; border-radius: 8px;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Raison du rejet :</div>
                            <div style="font-size: 0.9rem; color: #333; line-height: 1.6;">
                                {{ $course->admin_notes }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informations du cours -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="200" style="margin-bottom: 1.5rem;">
        <div class="admin-card-header">
                    <h3 class="admin-card-title">Informations du cours</h3>
        </div>
        <div class="admin-card-body">
                    @if($course->cover_image_path)
                        <div style="margin-bottom: 1.5rem;">
                            <img src="{{ $course->cover_image_url }}" alt="{{ $course->title }}" 
                                 style="width: 100%; height: 300px; object-fit: cover; border-radius: 8px;">
                        </div>
                    @endif
                    
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Statut</div>
                            @if($course->status == 'published')
                                <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-check-circle"></i> Publié
                                </span>
                            @else
                                <span style="background: rgba(108, 117, 125, 0.1); color: #6C757D; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-file"></i> Brouillon
                                </span>
                            @endif
                            
                            @php
                                $validationStatus = $course->validation_status ?? 'draft';
                                $validationStyles = [
                                    'draft' => ['bg' => 'rgba(108, 117, 125, 0.1)', 'color' => '#6C757D', 'icon' => 'fa-file'],
                                    'pending_validation' => ['bg' => 'linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1))', 'color' => '#FF8E2A', 'icon' => 'fa-clock'],
                                    'needs_revision' => ['bg' => 'linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1))', 'color' => '#1EA38B', 'icon' => 'fa-edit'],
                                    'approved' => ['bg' => 'rgba(39, 179, 113, 0.1)', 'color' => '#27B371', 'icon' => 'fa-check'],
                                    'rejected' => ['bg' => 'rgba(227, 45, 49, 0.1)', 'color' => '#E32D31', 'icon' => 'fa-times']
                                ];
                                $vStyle = $validationStyles[$validationStatus] ?? $validationStyles['draft'];
                            @endphp
                            <span style="background: {{ $vStyle['bg'] }}; color: {{ $vStyle['color'] }}; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block; margin-left: 0.5rem;">
                                <i class="fas {{ $vStyle['icon'] }}"></i> {{ ucfirst(str_replace('_', ' ', $validationStatus)) }}
                            </span>
                        </div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Formateur :
                            @if($course->formateur)
                                <a href="{{ route('admin.formateurs.show', $course->formateur->id) }}" 
                                   style="color: #1EA38B; text-decoration: none; font-weight: 500;">
                                            {{ $course->formateur->first_name }} {{ $course->formateur->last_name }}
                                        </a>
                            @else
                                <span style="color: #6C757D;">Non assigné</span>
                            @endif</div>
                        </div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Catégorie :
                            <span style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); color: #1EA38B; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                {{ $course->category->name ?? 'Non catégorisé' }}
                            </span></div>
                        </div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Prix :
                            @if($course->price > 0)
                                <span style="background: rgba(39, 179, 113, 0.1); color: #27B371; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem; font-weight: 600; display: inline-block;">
                                    {{ number_format($course->price, 0, ',', ' ') }} FCFA
                                </span>
                            @else
                                <span style="background: rgba(255, 142, 42, 0.1); color: #FF8E2A; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem; font-weight: 600; display: inline-block;">
                                    <i class="fas fa-gift"></i> Gratuit
                                        </span>
                            @endif</div>
                        </div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Niveau :
                            @php
                                $levelStyles = [
                                    'beginner' => ['bg' => 'rgba(30, 163, 139, 0.1)', 'color' => '#1EA38B', 'label' => 'Débutant'],
                                    'intermediate' => ['bg' => 'rgba(255, 142, 42, 0.1)', 'color' => '#FF8E2A', 'label' => 'Intermédiaire'],
                                    'advanced' => ['bg' => 'rgba(227, 45, 49, 0.1)', 'color' => '#E32D31', 'label' => 'Avancé']
                                ];
                                $lStyle = $levelStyles[$course->level ?? 'beginner'] ?? $levelStyles['beginner'];
                            @endphp
                            <span style="background: {{ $lStyle['bg'] }}; color: {{ $lStyle['color'] }}; padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.8rem; font-weight: 600; display: inline-block;">
                                {{ $lStyle['label'] }}
                            </span></div>
                        </div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Durée : {{ $course->duration ?? 'Non spécifiée' }}</div>
                            {{-- <div style="font-size: 0.9rem; color: #333;"></div> --}}
                        </div>
                        
                        <div>
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.25rem; font-weight: 500;">Créé le : {{ $course->created_at->format('d/m/Y à H:i') }}</div>
                                {{-- <div style="font-size: 0.9rem; color: #333;"></div> --}}
                    </div>
                </div>

                    @if($course->description)
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #E9ECEF;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Description :
                            <div style="font-size: 0.9rem; color: #333; line-height: 1.6;">
                    {!! nl2br(e($course->description)) !!}
                            </div></div>
                </div>
                    @endif

                @if($course->preview_video_url)
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #E9ECEF;">
                            <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Vidéo de présentation :
                            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">
                    @if(Str::contains($course->preview_video_url, ['youtube.com', 'youtu.be']))
                        @php
                            $videoId = null;
                            if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([^\/\n\s"&?]{11})/', $course->preview_video_url, $match)) {
                                $videoId = $match[1];
                            }
                        @endphp
                        @if($videoId)
                                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;" 
                                                allowfullscreen></iframe>
                        @else
                                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #F8F9FA; color: #6C757D;">
                                            Format de vidéo YouTube non reconnu
                                        </div>
                        @endif
                    @else
                                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #F8F9FA;">
                                        <a href="{{ $course->preview_video_url }}" target="_blank" 
                                           style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 500;">
                                            <i class="fas fa-play"></i> Voir la vidéo
                                        </a>
                                    </div></div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Contenu du cours -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="300">
                <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 class="admin-card-title">Contenu du cours</h3>
                    <button type="button" onclick="openModal('addModuleModal')" 
                            style="background: linear-gradient(135deg, #1EA38B, #27B371); color: white; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                        <i class="fas fa-plus-circle"></i> Ajouter un module
                    </button>
                </div>
                <div class="admin-card-body">
                    @php
                        $moduleCount = $course->modules->count();
                            $lessonCount = 0;
                            $quizCount = 0;
                            foreach ($course->modules as $module) {
                                $lessonCount += $module->lessons->count();
                                $quizCount += $module->quizzes->count();
                            }
                        @endphp
                        
                    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                        <span style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); color: #1EA38B; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">
                            <i class="fas fa-layer-group"></i> {{ $moduleCount }} {{ Str::plural('module', $moduleCount) }}
                        </span>
                        <span style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); color: #1EA38B; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">
                            <i class="fas fa-book-open"></i> {{ $lessonCount }} {{ Str::plural('leçon', $lessonCount) }}
                        </span>
                        <span style="background: linear-gradient(135deg, rgba(255, 142, 42, 0.1), rgba(255, 179, 102, 0.1)); color: #FF8E2A; padding: 0.5rem 1rem; border-radius: 15px; font-size: 0.85rem; font-weight: 600;">
                            <i class="fas fa-question-circle"></i> {{ $quizCount }} {{ Str::plural('quiz', $quizCount) }}
                        </span>
                </div>
                
                    @if($course->modules->count() > 0)
                        <div style="display: grid; gap: 1rem;">
                            @foreach($course->modules as $index => $module)
                                <div style="background: #F8F9FA; border-radius: 8px; padding: 1rem; border-left: 4px solid #1EA38B;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.75rem;">
                                        <div>
                                            <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">
                                                Module {{ $index + 1 }}: {{ $module->title }}
                                            </div>
                                            @if($module->description)
                                                <div style="font-size: 0.85rem; color: #6C757D;">{{ $module->description }}</div>
                                            @endif
                                        </div>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="{{ route('admin.modules.edit', $module->id) }}" 
                                               style="color: #1EA38B; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    @if($module->lessons->count() > 0 || $module->quizzes->count() > 0)
                                        <div style="display: grid; gap: 0.5rem;">
                                        @foreach($module->lessons as $lesson)
                                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: white; border-radius: 6px;">
                                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                        <i class="fas fa-book-open" style="color: #1EA38B;"></i>
                                                        <a href="{{ route('admin.lessons.show', $lesson->id) }}" 
                                                           style="color: #333; text-decoration: none; font-weight: 500;">
                                                        {{ $lesson->title }}
                                                    </a>
                                                </div>
                                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                        @if($lesson->type === 'video')
                                                            <span style="background: rgba(227, 45, 49, 0.1); color: #E32D31; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                                                <i class="fas fa-video"></i> Vidéo
                                                            </span>
                                                        @elseif($lesson->type === 'pdf')
                                                            <span style="background: rgba(227, 45, 49, 0.1); color: #E32D31; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                                                <i class="fas fa-file-pdf"></i> PDF
                                                            </span>
                                                        @else
                                                            <span style="background: rgba(108, 117, 125, 0.1); color: #6C757D; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                                                <i class="fas fa-file-alt"></i> Texte
                                                            </span>
                                                        @endif
                                                        <a href="{{ route('admin.lessons.edit', $lesson->id) }}" 
                                                           style="color: #1EA38B; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @foreach($module->quizzes as $quiz)
                                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: white; border-radius: 6px;">
                                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                        <i class="fas fa-question-circle" style="color: #FF8E2A;"></i>
                                                        <a href="{{ route('admin.quizzes.show', $quiz->id) }}" 
                                                           style="color: #333; text-decoration: none; font-weight: 500;">
                                                        {{ $quiz->title }}
                                                    </a>
                                                </div>
                                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                        <span style="background: rgba(255, 142, 42, 0.1); color: #FF8E2A; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                                            <i class="fas fa-tasks"></i> Quiz
                                                    </span>
                                                        <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" 
                                                           style="color: #1EA38B; padding: 0.5rem; border-radius: 6px; text-decoration: none; transition: all 0.2s;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @else
                                        <div style="text-align: center; padding: 1rem; color: #6C757D; font-size: 0.9rem;">
                                            Aucun contenu dans ce module
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 2rem; color: #6C757D;">
                            <i class="fas fa-layer-group" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                            <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Aucun module</div>
                            <div style="font-size: 0.9rem;">Ce cours n'a pas encore de modules.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Colonne droite -->
        <div>
            <!-- Inscriptions récentes -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="400" style="margin-bottom: 1.5rem;">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Inscriptions récentes</h3>
                </div>
                <div class="admin-card-body">
                    @if($course->enrollments->count() > 0)
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: #1EA38B; text-align: center; margin-bottom: 0.5rem;">
                                {{ $course->enrollments->count() }}
                            </div>
                            <div style="text-align: center; color: #6C757D; font-size: 0.9rem;">Total des inscriptions</div>
                        </div>
                        
                        <div style="display: grid; gap: 0.75rem;">
                            @foreach($course->enrollments->sortByDesc('created_at')->take(5) as $enrollment)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem; background: #F8F9FA; border-radius: 6px;">
                                    <div>
                                        <div style="font-weight: 500; color: #333;">{{ $enrollment->user->first_name }} {{ $enrollment->user->last_name }}</div>
                                        <div style="font-size: 0.8rem; color: #6C757D;">{{ $enrollment->user->email }}</div>
                                    </div>
                                    <div style="font-size: 0.85rem; color: #6C757D;">
                                {{ $enrollment->created_at->format('d/m/Y') }}
                            </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 1.5rem; color: #6C757D;">
                            <i class="fas fa-user-graduate" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                            <div style="font-size: 0.9rem;">Aucune inscription pour le moment</div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Évaluations -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="500" style="margin-bottom: 1.5rem;">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Évaluations</h3>
                </div>
                <div class="admin-card-body">
                @php
                    $avgRating = $course->ratings->avg('rating') ?? 0;
                    $ratingCount = $course->ratings->count();
                @endphp
                
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <div style="font-size: 2.5rem; font-weight: 700; color: #1EA38B; margin-bottom: 0.5rem;">
                            {{ number_format($avgRating, 1) }}
                        </div>
                        <div style="display: flex; justify-content: center; gap: 0.25rem; margin-bottom: 0.5rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="color: {{ $i <= round($avgRating) ? '#FFD700' : '#E9ECEF' }};"></i>
                            @endfor
                        </div>
                        <div style="color: #6C757D; font-size: 0.9rem;">
                            {{ $ratingCount }} {{ Str::plural('avis', $ratingCount) }}
                    </div>
                </div>
                
                    @if($ratingCount > 0)
                        <div style="display: grid; gap: 1rem;">
                            @foreach($course->ratings->sortByDesc('created_at')->take(3) as $rating)
                                <div style="padding: 1rem; background: #F8F9FA; border-radius: 8px;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                        <div>
                                            <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">
                                                {{ $rating->user->first_name }} {{ $rating->user->last_name }}
                            </div>
                                            <div style="display: flex; gap: 0.25rem; margin-bottom: 0.5rem;">
                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star" style="font-size: 0.75rem; color: {{ $i <= $rating->rating ? '#FFD700' : '#E9ECEF' }};"></i>
                                @endfor
                            </div>
                                        </div>
                                        <div style="font-size: 0.8rem; color: #6C757D;">
                                            {{ $rating->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    @if($rating->comment)
                                        <div style="font-size: 0.9rem; color: #333; line-height: 1.5;">
                                            {{ $rating->comment }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 1.5rem; color: #6C757D;">
                            <i class="fas fa-star" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.3;"></i>
                            <div style="font-size: 0.9rem;">Aucune évaluation pour le moment</div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Revenus -->
            <div class="admin-card" data-aos="fade-up" data-aos-delay="600">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Revenus</h3>
                </div>
                <div class="admin-card-body">
                    <div style="text-align: center; margin-bottom: 1.5rem;">
                        <div style="font-size: 2rem; font-weight: 700; color: #27B371; margin-bottom: 0.5rem;">
                            {{ number_format($totalRevenue, 0, ',', ' ') }} FCFA
                        </div>
                        <div style="color: #6C757D; font-size: 0.9rem;">Revenu total généré</div>
                </div>
                
                    <div style="background: linear-gradient(135deg, rgba(30, 163, 139, 0.1), rgba(39, 179, 113, 0.1)); padding: 1rem; border-radius: 8px; text-align: center;">
                        <a href="#" 
                           style="color: #1EA38B; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-chart-line"></i> Voir les détails des revenus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un module -->
<div id="addModuleModal" class="admin-confirm-modal" style="display: none;">
    <div class="admin-confirm-backdrop" onclick="closeModal('addModuleModal')"></div>
    <div class="admin-confirm-dialog" style="max-width: 500px;">
        <form action="{{ route('admin.modules.store') }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="admin-confirm-header">
                <h6>Ajouter un nouveau module</h6>
            </div>
            <div class="admin-confirm-body">
                <div style="margin-bottom: 1rem;">
                    <label for="module-title" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Titre du module *</label>
                    <input type="text" id="module-title" name="title" required 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label for="module-description" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Description</label>
                    <textarea id="module-description" name="description" rows="3" 
                              style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                </div>
                <div>
                    <label for="module-order" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Ordre</label>
                    <input type="number" id="module-order" name="order" value="{{ $course->modules->count() + 1 }}" min="1" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                </div>
            </div>
            <div class="admin-confirm-footer">
                <button type="button" onclick="closeModal('addModuleModal')" class="admin-btn admin-btn-secondary">Annuler</button>
                <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #1EA38B, #27B371);">Ajouter le module</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Valider Cours -->
@if($course->validation_status == 'pending_validation')
    <div id="validateCourseModal" class="admin-confirm-modal" style="display: none;">
        <div class="admin-confirm-backdrop" onclick="closeModal('validateCourseModal')"></div>
        <div class="admin-confirm-dialog" style="max-width: 500px;">
            <form action="{{ route('admin.formateurs.courses.validate', $course->id) }}" method="POST">
                @csrf
                <div class="admin-confirm-header">
                    <h6>Valider le cours</h6>
                </div>
                <div class="admin-confirm-body">
                    <div style="background: #E8F5E9; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #27B371;">
                        <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">{{ $course->title }}</div>
                        <div style="font-size: 0.85rem; color: #6C757D;">Ce cours sera validé et publié automatiquement sur la plateforme.</div>
                    </div>
                    
                    <div style="background: #F8F9FA; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        <div style="font-size: 0.85rem; color: #6C757D; margin-bottom: 0.5rem; font-weight: 500;">Informations du cours</div>
                        <div style="display: grid; gap: 0.5rem; font-size: 0.85rem;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #6C757D;">Formateur:</span>
                                <span style="font-weight: 500; color: #333;">{{ $course->formateur->first_name ?? 'N/A' }} {{ $course->formateur->last_name ?? '' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #6C757D;">Modules:</span>
                                <span style="font-weight: 500; color: #333;">{{ $course->modules->count() }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: #6C757D;">Prix:</span>
                                <span style="font-weight: 500; color: #333;">{{ $course->price > 0 ? number_format($course->price, 0, ',', ' ') . ' FCFA' : 'Gratuit' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <label for="quality_score" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Score de qualité (optionnel)</label>
                        <input type="number" id="quality_score" name="quality_score" min="0" max="100" 
                               placeholder="Ex: 85" 
                               style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem;">
                        <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                            Note sur 100 pour évaluer la qualité du cours
                        </div>
                    </div>
                </div>
                <div class="admin-confirm-footer">
                    <button type="button" onclick="closeModal('validateCourseModal')" class="admin-btn admin-btn-secondary">Annuler</button>
                    <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #27B371, #1EA38B);">Valider et publier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Demander Corrections -->
    <div id="revisionCourseModal" class="admin-confirm-modal" style="display: none;">
        <div class="admin-confirm-backdrop" onclick="closeModal('revisionCourseModal')"></div>
        <div class="admin-confirm-dialog" style="max-width: 500px;">
            <form action="{{ route('admin.formateurs.courses.revision', $course->id) }}" method="POST">
                @csrf
                <div class="admin-confirm-header">
                    <h6>Demander des corrections</h6>
                </div>
                <div class="admin-confirm-body">
                    <div style="background: #FFF3E0; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #FF8E2A;">
                        <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">{{ $course->title }}</div>
                        <div style="font-size: 0.85rem; color: #6C757D;">Le formateur devra apporter des corrections avant de pouvoir soumettre à nouveau.</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label for="revision_notes" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Notes de correction *</label>
                        <textarea id="revision_notes" name="revision_notes" required rows="4" 
                                  placeholder="Indiquez les corrections à apporter au formateur (contenu, qualité, structure, etc.)..." 
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                        <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                            Ces notes seront visibles par le formateur
                        </div>
                    </div>
                </div>
                <div class="admin-confirm-footer">
                    <button type="button" onclick="closeModal('revisionCourseModal')" class="admin-btn admin-btn-secondary">Annuler</button>
                    <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #FF8E2A, #FFB366);">Demander corrections</button>
                </div>
            </form>
    </div>
</div>

    <!-- Modal Rejeter Cours -->
    <div id="rejectCourseModal" class="admin-confirm-modal" style="display: none;">
        <div class="admin-confirm-backdrop" onclick="closeModal('rejectCourseModal')"></div>
        <div class="admin-confirm-dialog" style="max-width: 500px;">
            <form action="{{ route('admin.formateurs.courses.reject', $course->id) }}" method="POST">
                @csrf
                <div class="admin-confirm-header">
                    <h6>Rejeter le cours</h6>
                </div>
                <div class="admin-confirm-body">
                    <div style="background: #FFEBEE; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #E32D31;">
                        <div style="font-size: 0.9rem; font-weight: 600; color: #333; margin-bottom: 0.25rem;">{{ $course->title }}</div>
                        <div style="font-size: 0.85rem; color: #6C757D;">Cette action rejettera définitivement le cours. Le formateur devra créer un nouveau cours.</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label for="admin_notes" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">Raison du rejet *</label>
                        <textarea id="admin_notes" name="admin_notes" required rows="4" 
                                  placeholder="Expliquez pourquoi ce cours est rejeté (non-conformité, qualité insuffisante, contenu inapproprié, etc.)..." 
                                  style="width: 100%; padding: 0.75rem; border: 1px solid #E9ECEF; border-radius: 8px; font-size: 0.9rem; resize: vertical;"></textarea>
                        <div style="font-size: 0.8rem; color: #6C757D; margin-top: 0.25rem;">
                            Ces notes seront visibles par le formateur
                        </div>
                    </div>
                </div>
                <div class="admin-confirm-footer">
                    <button type="button" onclick="closeModal('rejectCourseModal')" class="admin-btn admin-btn-secondary">Annuler</button>
                    <button type="submit" class="admin-btn admin-btn-primary" style="background: linear-gradient(135deg, #E32D31, #FF6B6B);">Rejeter le cours</button>
                </div>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.admin-confirm-modal').forEach(modal => {
                if (modal.style.display === 'flex') {
                    closeModal(modal.id);
                }
            });
        }
    });
    
    // Hover effects pour les boutons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.admin-card-body a, .admin-card-body button').forEach(element => {
            if (element.style && element.style.color) {
                element.addEventListener('mouseenter', function() {
                    if (this.style.color === 'rgb(30, 163, 139)' || this.style.color === '#1EA38B') {
                        this.style.background = 'rgba(30, 163, 139, 0.1)';
                        this.style.transform = 'scale(1.05)';
                    }
                });
                
                element.addEventListener('mouseleave', function() {
                    if (this.style.color === 'rgb(30, 163, 139)' || this.style.color === '#1EA38B') {
                        this.style.background = 'none';
                        this.style.transform = 'scale(1)';
                    }
                });
            }
        });
        
        document.querySelectorAll('button[style*="background: linear-gradient"], a[style*="background: linear-gradient"]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });
</script>
@endpush
