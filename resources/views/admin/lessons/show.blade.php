@extends('admin.layouts.app')

@section('title', 'Détails de la Leçon')

@section('styles')
<style>
    /* Styles pour le contenu texte dans la vue admin */
    .text-content {
        line-height: 1.8;
        font-size: 1rem;
        color: var(--gray-700);
    }

    .text-content h1, .text-content h2, .text-content h3, .text-content h4, .text-content h5, .text-content h6 {
        color: var(--gray-800);
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .text-content p {
        margin-bottom: 1rem;
    }

    .text-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.375rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin: 1rem 0;
        display: block;
    }

    .text-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
        background: white;
        border-radius: 0.375rem;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .text-content table th,
    .text-content table td {
        padding: 0.5rem 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .text-content table th {
        background: #3b82f6;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .text-content table tr:nth-child(even) {
        background: #f9fafb;
    }

    .text-content ul,
    .text-content ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }

    .text-content li {
        margin-bottom: 0.25rem;
    }

    .text-content blockquote {
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
        margin: 1rem 0;
        font-style: italic;
        color: #6b7280;
        background: #f3f4f6;
        padding: 0.75rem 1rem;
        border-radius: 0 0.375rem 0.375rem 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-book-open fa-fw" style="color:#1EA38B;"></i> {{ $lesson->title }}
        </h1>
        <div>
            <a href="{{ route('admin.courses.show', $course->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left fa-fw"></i> Retour au cours
            </a>
            <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
                <i class="fas fa-edit fa-fw"></i> Modifier la leçon
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold" style="color:#1EA38B;">Contenu de la leçon</h6>
                </div>
                <div class="card-body">
                    @if($lesson->content_type === 'video' && $lesson->video_url)
                        <div class="ratio ratio-16x9 mb-4">
                            <iframe src="{{ str_replace('watch?v=', 'embed/', $lesson->video_url) }}" title="{{ $lesson->title }}" allowfullscreen></iframe>
                        </div>
                    @elseif($lesson->content_type === 'text' && $lesson->text_content)
                        <div class="mb-4 p-4 border rounded bg-light text-content">
                            {!! $lesson->text_content !!}
                        </div>
                    @elseif($lesson->content_type === 'pdf' && $lesson->pdf_path)
                        <div class="mb-4">
                            <div class="ratio ratio-16x9">
                                <embed src="{{ Storage::url($lesson->pdf_path) }}" type="application/pdf" width="100%" height="600px" />
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ Storage::url($lesson->pdf_path) }}" target="_blank" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
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
                            <a href="{{ $lesson->external_url }}" target="_blank" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
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
                    <h6 class="m-0 font-weight-bold" style="color:#1EA38B;">Ressources complémentaires</h6>
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
                                            <span class="badge text-white" style="background:#1EA38B;"><i class="fas fa-file fa-fw"></i> Fichier</span>
                                        @elseif($resource->resource_type === 'link')
                                            <span class="badge text-white" style="background:#1EA38B;"><i class="fas fa-link fa-fw"></i> Lien</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($resource->resource_type === 'file')
                                            <a href="{{ Storage::url($resource->file_path) }}" target="_blank" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;">
                                                <i class="fas fa-eye fa-fw"></i> Visualiser
                                            </a>
                                            <a href="{{ Storage::url($resource->file_path) }}" download class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-download fa-fw"></i> Télécharger
                                            </a>
                                        @elseif($resource->resource_type === 'link')
                                            <a href="{{ $resource->url }}" target="_blank" class="btn btn-sm" style="color:#1EA38B;border:1px solid #1EA38B;">
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
                    <h6 class="m-0 font-weight-bold" style="color:#1EA38B;">Informations</h6>
                </div>
                <div class="card-body">
                    <p><strong>Cours :</strong> <a href="{{ route('admin.courses.show', $course->id) }}">{{ $course->title }}</a></p>
                    <p><strong>Module :</strong> <a href="{{ route('admin.modules.edit', $module->id) }}">{{ $module->title }}</a></p>
                    <p><strong>Type de contenu :</strong> 
                        @if($lesson->content_type === 'video')
                            <span class="badge bg-danger"><i class="fas fa-video fa-fw"></i> Vidéo</span>
                        @elseif($lesson->content_type === 'text')
                            <span class="badge text-white" style="background:#27B371;"><i class="fas fa-file-alt fa-fw"></i> Texte</span>
                        @elseif($lesson->content_type === 'pdf')
                            <span class="badge" style="background:#FF8E2A;color:#fff;"><i class="fas fa-file-pdf fa-fw"></i> PDF</span>
                        @elseif($lesson->content_type === 'external')
                            <span class="badge text-white" style="background:#1EA38B;"><i class="fas fa-external-link-alt fa-fw"></i> Lien externe</span>
                        @endif
                    </p>
                    <p><strong>Ordre :</strong> {{ $lesson->order ?? 'Non défini' }}</p>
                    <p><strong>Durée :</strong> {{ $lesson->duration_minutes ? $lesson->duration_minutes . ' minutes' : 'Non définie' }}</p>
                    <p><strong>Prévisualisable :</strong> 
                        @if($lesson->is_previewable)
                            <span class="badge text-white" style="background:#27B371;"><i class="fas fa-check fa-fw"></i> Oui</span>
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
                    <h6 class="m-0 font-weight-bold" style="color:#1EA38B;">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn text-white" style="background:linear-gradient(135deg,#1EA38B,#27B371);border:none;">
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
