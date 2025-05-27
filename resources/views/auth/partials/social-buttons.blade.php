<div class="social-auth-links text-center mb-3">
    @if(request()->routeIs('login'))
        <p class="mb-3">Ou se connecter avec</p>
    @else
        <p class="mb-3">Ou s'inscrire avec</p>
    @endif
    <div class="d-flex justify-content-center gap-2">
        <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-outline-danger">
            <i class="fab fa-google"></i>
        </a>
        <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-outline-primary">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="{{ route('social.login', ['provider' => 'github']) }}" class="btn btn-outline-dark">
            <i class="fab fa-github"></i>
        </a>
    </div>
</div>
