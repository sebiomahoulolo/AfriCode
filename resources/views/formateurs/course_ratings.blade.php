@extends('formateurs.layouts.app')

@section('title', 'AfriCode - Évaluations du cours')
@section('page-title', $course->title)
@section('page-subtitle', 'Évaluations et commentaires (' . $course->ratings->count() . ' avis)')

@section('header-actions')
    <a href="{{ route('formateur.manage.course', $course) }}" class="btn btn-outline-secondary me-2">
        <i class="fas fa-arrow-left me-2"></i>Retour au cours
    </a>
    <div class="d-flex align-items-center">
        <span class="badge bg-success me-2">
            <i class="fas fa-star me-1"></i>{{ number_format($averageRating, 1) }}
        </span>
        <small class="text-muted">Note moyenne</small>
    </div>
@endsection

@section('styles')
<style>
    .card {
        overflow: hidden;
    }
    
    .rating-search {
        margin-bottom: 1.5rem;
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .star-rating {
        color: #ffc107;
        font-size: 1.2rem;
    }
    
    .star-rating .far {
        color: #e4e5e7;
    }
    
    .rating-card {
        border-bottom: 1px solid #eee;
        padding: 1.5rem 0;
    }
    
    .rating-card:last-child {
        border-bottom: none;
    }
    
    .rating-date {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .rating-stats {
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 1.5rem;
    }
    
    .rating-bar {
        height: 8px;
        flex-grow: 1;
        background-color: #e4e5e7;
        border-radius: 4px;
        margin: 0 10px;
        overflow: hidden;
    }
    
    .rating-bar-fill {
        height: 100%;
        background-color: var(--primary-color);
    }
    
    .filters {
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('formateur.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}">{{ $course->title }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('messages.ratings') }}</li>
        </ol>
    </nav>
    
    <div class="row">
        <!-- Ratings Statistics -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body rating-stats">
                    <h5 class="card-title mb-4">{{ __('messages.rating_stats') }}</h5>
                    
                    @php
                        $avgRating = $ratings->avg('rating') ?? 0;
                        $totalRatings = $ratings->count();
                        
                        // Calculer le nombre d'évaluations par note
                        $ratingCounts = [
                            5 => $ratings->where('rating', 5)->count(),
                            4 => $ratings->where('rating', 4)->count(),
                            3 => $ratings->where('rating', 3)->count(),
                            2 => $ratings->where('rating', 2)->count(),
                            1 => $ratings->where('rating', 1)->count(),
                        ];
                    @endphp
                    
                    <div class="text-center mb-4">
                        <h1 class="display-4 fw-bold">{{ number_format($avgRating, 1) }}</h1>
                        <div class="star-rating mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($avgRating))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted">{{ $totalRatings }} {{ __('messages.ratings_count') }}</p>
                    </div>
                    
                    <div class="rating-bars">
                        @for($i = 5; $i >= 1; $i--)
                            <div class="d-flex align-items-center mb-2">
                                <div style="width: 30px">{{ $i }}</div>
                                <div class="rating-bar">
                                    <div class="rating-bar-fill" style="width: {{ $totalRatings > 0 ? ($ratingCounts[$i] / $totalRatings) * 100 : 0 }}%"></div>
                                </div>
                                <div style="width: 40px; text-align: right;">{{ $ratingCounts[$i] }}</div>
                            </div>
                        @endfor
                    </div>
                </div>
                
                <div class="card-footer bg-white">
                    <a href="{{ route('formateur.manage.course', ['courseId' => $course->id]) }}" class="btn btn-outline-primary btn-block w-100">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_course') }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Ratings List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('messages.student_comments') }}</h5>
                        
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Filtrer
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                <li><a class="dropdown-item {{ request('filter') == '' ? 'active' : '' }}" href="{{ route('formateur.courses.ratings', ['courseId' => $course->id]) }}">{{ __('messages.all_ratings') }}</a></li>
                                <li><a class="dropdown-item {{ request('filter') == 'positive' ? 'active' : '' }}" href="{{ route('formateur.courses.ratings', ['courseId' => $course->id, 'filter' => 'positive']) }}">{{ __('messages.positive_ratings') }}</a></li>
                                <li><a class="dropdown-item {{ request('filter') == 'neutral' ? 'active' : '' }}" href="{{ route('formateur.courses.ratings', ['courseId' => $course->id, 'filter' => 'neutral']) }}">{{ __('messages.neutral_ratings') }}</a></li>
                                <li><a class="dropdown-item {{ request('filter') == 'negative' ? 'active' : '' }}" href="{{ route('formateur.courses.ratings', ['courseId' => $course->id, 'filter' => 'negative']) }}">{{ __('messages.negative_ratings') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @forelse($ratings as $rating)
                        <div class="rating-card px-4">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $rating->user->profile_image_path ? asset($rating->user->profile_image_path) : 'https://via.placeholder.com/40' }}" 
                                     alt="{{ $rating->user->first_name }}" 
                                     class="avatar-sm me-3">
                                <div>
                                    <h6 class="mb-0">{{ $rating->user->first_name }} {{ $rating->user->last_name }}</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="star-rating me-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-date">{{ $rating->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($rating->comment)
                                <p class="mb-0">{{ $rating->comment }}</p>
                            @else
                                <p class="text-muted fst-italic mb-0">Pas de commentaire fourni</p>
                            @endif
                            
                            @if($rating->formateur_reply)
                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex">
                                        <div class="bg-light p-3 rounded-3 w-100">
                                            <strong class="d-block mb-1">Votre réponse:</strong>
                                            <p class="mb-0">{{ $rating->formateur_reply }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mt-3">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#replyModal{{ $rating->id }}">
                                        <i class="fas fa-reply me-1"></i> Répondre
                                    </button>
                                </div>
                                
                                <!-- Reply Modal -->
                                <div class="modal fade" id="replyModal{{ $rating->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('formateur.courses.ratings.reply', ['ratingId' => $rating->id]) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Répondre à l'évaluation</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="reply{{ $rating->id }}" class="form-label">Votre réponse</label>
                                                        <textarea class="form-control" id="reply{{ $rating->id }}" name="reply" rows="4" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-primary">Publier la réponse</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="mb-1">Aucune évaluation pour ce cours.</p>
                            <p class="text-muted">Les évaluations apparaîtront ici lorsque les étudiants noteront votre cours.</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $ratings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
