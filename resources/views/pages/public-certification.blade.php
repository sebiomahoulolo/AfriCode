@extends('layouts.app')

@section('title', 'AfriCode')
@section('page-title', 'Certificat de Formation')

@push('styles')
{{-- Les styles sont les mêmes que pour la certification de l'apprenant --}}
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #1EA38B; /* Vert émeraude */
        --secondary-color: #FF8E2A; /* Orange */
        --accent-color: #E32D31; /* Rouge */
        --highlight-color: #27B371; /* Vert clair */
        --background-color: #F8F9FA;
        --light-accent: #ECF0F1;
        --text-color: #333333;
        --light-gray-pattern: rgba(224, 224, 224, 0.3);
    }

    body {
        background-color: var(--background-color);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .certificate-wrapper {
        width: 100%;
        padding: 2rem 1rem;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        overflow-x: auto;
    }

    .certificate-container {
        width: 297mm;
        height: 210mm;
        min-width: 297mm;
        min-height: 210mm;
        margin: 0;
        background-color: white;
        padding: 10mm;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        transform: scale(0.85);
        transform-origin: top center;
    }

    .certificate {
        border: 8px solid var(--primary-color);
        padding: 20px;
        height: 100%;
        position: relative;
        background-color: #ffffff;
        z-index: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: center;
    }

    .certificate::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23cccccc' fill-opacity='0.2' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
        background-repeat: repeat;
        opacity: 1;
        pointer-events: none;
        z-index: -1;
    }

    .header {
        text-align: center;
        margin-bottom: 15px;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 15px;
    }

    .logo-container {
        min-height: 80px;
    }

    .logo-bg {
        width: 150px;
        height: 80px;
        background-color: var(--primary-color);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
        padding: 10px;
        color: white;
        font-weight: bold;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .logo-symbol { display: flex; margin-bottom: 5px; }
    .logo-part-1 { height: 25px; width: 8px; background-color: var(--secondary-color); transform: skew(-15deg); margin-right: 2px; }
    .logo-part-2 { height: 25px; width: 8px; background-color: var(--accent-color); transform: skew(-15deg); margin-right: 2px; }
    .logo-part-3 { height: 25px; width: 8px; background-color: var(--highlight-color); transform: skew(-15deg); }
    .logo-text { font-size: 20px; letter-spacing: 2px; text-shadow: 1px 1px 2px rgba(0,0,0,0.2); }
    .logo-slogan { font-size: 9px; letter-spacing: 1px; opacity: 0.9; }

    .title {
        font-size: 32px;
        font-weight: 700;
        margin: 10px 0 5px 0;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .subtitle {
        font-size: 18px;
        color: var(--secondary-color);
        letter-spacing: 1px;
    }

    .content {
        text-align: center;
        margin-bottom: 15px;
        padding: 0 1rem;
    }

    .student-name {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-color);
        margin: 10px 0;
        border-bottom: 2px dashed var(--primary-color);
        display: inline-block;
        padding-bottom: 5px;
    }

    .course-name {
        font-size: 22px;
        font-weight: 600;
        color: var(--text-color);
        margin: 10px 0 5px 0;
        text-align: center;
    }

    .description {
        font-size: 15px;
        color: #555;
        margin: 8px 0;
        line-height: 1.6;
    }

    .details {
        font-size: 13px;
        color: #666;
        margin: 15px 0;
        text-align: center;
    }

    .detail-item {
        margin: 3px;
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .signatures {
        display: flex;
        justify-content: center;
        gap: 50px;
        margin-top: auto;
        padding: 0 20px;
    }

    .signature {
        text-align: center;
        max-width: 220px;
    }

    .signature-line {
        width: 100%;
        max-width: 220px;
        border-bottom: 1.5px solid var(--primary-color);
        margin: 0 auto 10px auto;
        height: 30px;
    }

    .signature-name {
        font-size: 15px;
        font-weight: 600;
        color: var(--text-color);
    }

    .signature-title {
        font-size: 12px;
        color: #666;
    }

    .stamp {
        position: absolute;
        bottom: 70px;
        right: 70px;
        width: 100px;
        height: 100px;
        border: 4px double var(--primary-color);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0.85;
        transform: rotate(-12deg);
        padding: 8px;
        background-color: rgba(255,255,255,0.8);
    }

    .stamp::after {
        content: '';
        position: absolute;
        top: 6px;
        left: 6px;
        right: 6px;
        bottom: 6px;
        border: 1px solid var(--primary-color);
        border-radius: 50%;
    }

    .stamp-text {
        text-align: center;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 11px;
        line-height: 1.3;
        text-transform: uppercase;
    }

    .slogan {
        position: absolute;
        bottom: 15px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--secondary-color);
        font-style: italic;
        letter-spacing: 1px;
    }

    .ribbon {
        width: 150px;
        height: 150px;
        overflow: hidden;
        position: absolute;
        z-index: 3;
    }

    .ribbon-top-right {
        top: -10px;
        right: -10px;
    }

    .ribbon-top-right::before,
    .ribbon-top-right::after {
        content: "";
        position: absolute;
        border: 5px solid transparent;
        border-top-color: #c16a1f;
        border-right-color: #c16a1f;
    }

    .ribbon-top-right::before {
        top: 0;
        left: 45px;
    }

    .ribbon-top-right::after {
        bottom: 45px;
        right: 0;
    }

    .ribbon-top-right span {
        position: absolute;
        display: block;
        width: 225px;
        padding: 8px 0;
        background-color: var(--secondary-color);
        box-shadow: 0 5px 10px rgba(0,0,0,.1);
        color: #fff;
        font-size: 14px;
        font-weight: bold;
        text-shadow: 0 1px 1px rgba(0,0,0,.2);
        text-align: center;
        text-transform: uppercase;
        left: -25px;
        top: 30px;
        transform: rotate(45deg);
    }

    .qr-code {
        position: absolute;
        bottom: 20px;
        left: 20px;
        width: 65px;
        height: 65px;
        padding: 5px;
        background-color: white;
    }

    .validation-info {
        margin-top: 25px;
        text-align: center;
        font-size: 13px;
        color: #777;
    }

    .validation-code {
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        color: var(--accent-color);
        letter-spacing: 1px;
        background-color: #f0f0f0;
        padding: 3px 6px;
        border-radius: 4px;
        border: 1px solid #ddd;
        display: inline-block;
    }

    .skills-container {
        margin: 15px 0 10px 0;
        text-align: center;
        min-height: 30px;
    }

    .skills-badge {
        display: inline-block;
        margin: 2px 3px;
        padding: 4px 12px;
        background-color: var(--light-accent);
        border-radius: 15px;
        font-size: 12px;
        border: 1px solid var(--primary-color);
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .certificate-number {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 10px;
        color: var(--primary-color);
        font-weight: bold;
        background-color: rgba(255,255,255,0.8);
        padding: 2px 6px;
        border-radius: 3px;
        border: 1px solid rgba(30, 163, 139, 0.5);
        font-family: 'Courier New', Courier, monospace;
        z-index: 3;
    }

    .pdf-export,
    @media print {
        body {
            background-color: white !important;
            padding: 0;
            margin: 0;
        }
        .main-content { /* Cible le conteneur principal du layout */
            padding: 0 !important;
        }

        .certificate-wrapper {
            padding: 0 !important;
            overflow: visible !important;
        }

        .certificate-container {
            transform: scale(1) !important;
            margin: 0 !important;
            box-shadow: none !important;
            border-radius: 0;
        }

        .print-btn-container {
            display: none !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .stamp {
            opacity: 0.7;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-3 print-btn-container">
        <div class="col text-end">
            <button class="btn btn-sm btn-success" id="downloadPDF">
                <i class="fas fa-download me-1"></i> Télécharger en PDF
            </button>
            <button class="btn btn-sm btn-primary ms-2" style="background-color: var(--primary-color); border-color: var(--primary-color);" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Imprimer
            </button>
            <a href="{{ route('verification.form', ['verification_code' => $certification->verification_code]) }}" class="btn btn-sm btn-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Retour à la vérification
            </a>
        </div>
    </div>
    <div class="certificate-wrapper">
        <div class="certificate-container" id="certificate-box">
            <div class="ribbon ribbon-top-right">
                <span>CERTIFIÉ</span>
            </div>

            <div class="certificate">
                <div class="certificate-number">Série: AF-{{ $certification->created_at->format('Y') }}-{{ str_pad($certification->id, 4, '0', STR_PAD_LEFT) }}</div>

                <div class="header">
                    <div class="logo-container">
                        <div class="logo-bg">
                            <div class="logo-symbol">
                                <div class="logo-part-1"></div>
                                <div class="logo-part-2"></div>
                                <div class="logo-part-3"></div>
                            </div>
                            <div class="logo-text">AFRICODE</div>
                            <div class="logo-slogan">NO CODE, NO FUTURE</div>
                        </div>
                    </div>
                    <h1 class="title">Certificat de Formation</h1>
                    <p class="subtitle">Centre de Formation Numérique AfriCode</p>
                </div>

                <div class="content">
                    <p class="description">Ce certificat est fièrement décerné à</p>
                    <h2 class="student-name">{{ $certification->user->first_name }} {{ $certification->user->last_name }}</h2>
                    <p class="description">pour avoir complété avec succès la formation</p>
                    <h3 class="course-name">{{ $certification->course->title }}</h3>

                    <div class="skills-container">
                        @forelse($certification->course->skills ?? [] as $skill)
                            <span class="skills-badge">{{ $skill }}</span>
                        @empty
                            <span class="skills-badge">Formation Complète</span>
                        @endforelse
                    </div>

                    <p class="description" style="margin-top: 15px;">ayant démontré compétence et excellence dans les domaines clés du programme.</p>

                    <div class="details">
                        <span class="detail-item">
                            <i class="fas fa-clock"></i>
                            Durée: {{ $certification->course->duration }}
                        </span>
                        <span class="detail-item">
                            <i class="fas fa-layer-group"></i>
                            Niveau: {{ $certification->course->level }}
                        </span>
                        <span class="detail-item">
                            <i class="fas fa-medal"></i>
                            Mention: {{ $certification->grade }}
                        </span>
                    </div>

                    <p class="date">Délivré le : {{ $certification->issued_at->format('d F Y') }}</p>

                    <div class="validation-info">
                        <p>Vérifiez l'authenticité sur <a href="{{ route('verification.form', ['verification_code' => $certification->verification_code]) }}" target="_blank"><strong>www.africode.tech/verify</strong></a> ou via le QR Code.</p>
                        <p>Code de validation: <span class="validation-code">{{ $certification->verification_code }}</span></p>
                    </div>
                </div>

                <div class="stamp">
                    <div class="stamp-text">
                        AfriCode<br>Formation<br>Certifiée<br>Qualité
                    </div>
                </div>

                <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{ urlencode(route('public.certificate.show', ['certification' => $certification->verification_code])) }}"
                     alt="QR Code de Validation" class="qr-code">

                <div class="slogan">
                    No Code, No Future
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('downloadPDF').addEventListener('click', function() {
        const element = document.getElementById('certificate-box');
        const opt = {
            margin: 0,
            filename: 'certificat-africode-{{ $certification->verification_code }}.pdf',
            image: { type: 'jpeg', quality: 1.0 },
            html2canvas: {
                scale: 3,
                useCORS: true,
                letterRendering: true,
                logging: false,
                width: element.scrollWidth,
                height: element.scrollHeight
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'landscape'
            }
        };

        element.classList.add('pdf-export');

        html2pdf().set(opt).from(element).save().then(function () {
            element.classList.remove('pdf-export');
        });
    });
</script>
@endpush 