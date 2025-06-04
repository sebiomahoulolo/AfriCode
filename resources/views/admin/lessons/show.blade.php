@extends('admin.layouts.app')

@section('title', 'Détails de la Leçon')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-book-open fa-fw text-primary"></i> {{ $lesson->title }}
        </h1>
        <div>
            <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left fa-fw"></i> Retour au cours
            </a>
            <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-primary">
                <i class="fas fa-edit fa-fw"></i> Modifier la leçon
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contenu de la leçon</h6>
                </div>
                <div class="card-body">
                    @if($lesson->content_type === 'video' && $lesson->video_url)
                        <div class="ratio ratio-16x9 mb-4">
                            <iframe src="{{ str_replace('watch?v=', 'embed/', $lesson->video_url) }}" title="{{ $lesson->title }}" allowfullscreen></iframe>
                        </div>
                    @elseif($lesson->content_type === 'text' && $lesson->text_content)
                        <div class="mb-4 p-4 border rounded bg-light">
                            @markdown($lesson->text_content)
                        </div>
                    @elseif($lesson->content_type === 'pdf' && $lesson->pdf_path)
                        <div class="mb-4">
                            <div class="ratio ratio-16x9">
                                <embed src="{{ Storage::url($lesson->pdf_path) }}" type="application/pdf" width="100%" height="600px" />
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ Storage::url($lesson->pdf_path) }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt fa-fw"></i> Ouvrir dans une nouvelle fenêtre
                                </a>
                                <a href="{{ Storage::url($lesson->pdf_path) }}" download class="btn btn-secondary">
                                    <i class="fas fa-download fa-fw"></i> Télécharger le PDF
                                </a>
                            </div>
                        </div>
                    @elseif($lesson->content_type === 'external' && $lesson->external_url)
                        <div class="mb-4 text-center">
                            <p class="mb-3">Cette leçon est liée à une ressource externe :</p>
                            <a href="{{ $lesson->external_url }}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt fa-fw"></i> Accéder à la ressource externe
                            </a>
                            <div class="mt-3 alert alert-info">
                                <small>URL : {{ $lesson->external_url }}</small>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle fa-fw"></i> Aucun contenu n'est disponible pour cette leçon.
                        </div>
                    @endif
                </div>
            </div>
            
            @if($resources->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ressources complémentaires</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resources as $resource)
                                <tr>
                                    <td>{{ $resource->title }}</td>
                                    <td>
                                        @if($resource->resource_type === 'file')
                                            <span class="badge bg-info"><i class="fas fa-file fa-fw"></i> Fichier</span>
                                        @elseif($resource->resource_type === 'link')
                                            <span class="badge bg-primary"><i class="fas fa-link fa-fw"></i> Lien</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($resource->resource_type === 'file')
                                            <a href="{{ Storage::url($resource->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye fa-fw"></i> Visualiser
                                            </a>
                                            <a href="{{ Storage::url($resource->file_path) }}" download class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-download fa-fw"></i> Télécharger
                                            </a>
                                        @elseif($resource->resource_type === 'link')
                                            <a href="{{ $resource->url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt fa-fw"></i> Accéder
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations</h6>
                </div>
                <div class="card-body">
                    <p><strong>Cours :</strong> <a href="{{ route('admin.courses.show', $course->id) }}">{{ $course->title }}</a></p>
                    <p><strong>Module :</strong> <a href="{{ route('admin.modules.edit', $module->id) }}">{{ $module->title }}</a></p>
                    <p><strong>Type de contenu :</strong> 
                        @if($lesson->content_type === 'video')
                            <span class="badge bg-danger"><i class="fas fa-video fa-fw"></i> Vidéo</span>
                        @elseif($lesson->content_type === 'text')
                            <span class="badge bg-success"><i class="fas fa-file-alt fa-fw"></i> Texte</span>
                        @elseif($lesson->content_type === 'pdf')
                            <span class="badge bg-warning text-dark"><i class="fas fa-file-pdf fa-fw"></i> PDF</span>
                        @elseif($lesson->content_type === 'external')
                            <span class="badge bg-primary"><i class="fas fa-external-link-alt fa-fw"></i> Lien externe</span>
                        @endif
                    </p>
                    <p><strong>Ordre :</strong> {{ $lesson->order ?? 'Non défini' }}</p>
                    <p><strong>Durée :</strong> {{ $lesson->duration_minutes ? $lesson->duration_minutes . ' minutes' : 'Non définie' }}</p>
                    <p><strong>Prévisualisable :</strong> 
                        @if($lesson->is_previewable)
                            <span class="badge bg-success"><i class="fas fa-check fa-fw"></i> Oui</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times fa-fw"></i> Non</span>
                        @endif
                    </p>
                    <p><strong>Date de création :</strong> {{ $lesson->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Dernière modification :</strong> {{ $lesson->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit fa-fw"></i> Modifier la leçon
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteLessonModal">
                            <i class="fas fa-trash fa-fw"></i> Supprimer la leçon
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression de la leçon -->
<div class="modal fade" id="deleteLessonModal" tabindex="-1" aria-labelledby="deleteLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLessonModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette leçon ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
