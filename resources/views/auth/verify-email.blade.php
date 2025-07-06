@extends('layouts.layout')

@section('title', 'AfriCode')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow rounded-lg">
                <div class="card-header  text-white text-center py-3" style="background-color:  #1EA38B"  >
                    <h4 class="mb-0" >Vérifiez votre adresse e-mail</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-envelope-open-text fa-4x  mb-3" style="color:  #FF8E2A;"></i>
                        <p>Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer par e-mail ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons volontiers un autre.</p>
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mb-4">
                            Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de l'inscription.
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary py-2" style="background-color:  #1EA38B">
                                        <i class="fas fa-paper-plane me-2" style="color:  #FF8E2A;"></i>Renvoyer l'e-mail de vérification
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-danger py-2">
                                        <i class="fas fa-sign-out-alt me-2" style="color:  #FF8E2A;"></i>Se déconnecter et continue aprés ? 
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
