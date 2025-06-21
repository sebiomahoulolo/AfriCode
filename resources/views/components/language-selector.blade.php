<div class="language-selector">
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle text-white d-flex align-items-center" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ strtoupper(app()->getLocale()) }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" 
                   href="{{ route('language.switch', ['locale' => 'en']) }}">
                    <img src="{{ asset('images/flags/en.png') }}" alt="English" class="flag-icon">
                    English
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}" 
                   href="{{ route('language.switch', ['locale' => 'fr']) }}">
                    <img src="{{ asset('images/flags/fr.png') }}" alt="Français" class="flag-icon">
                    Français
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'de' ? 'active' : '' }}" 
                   href="{{ route('language.switch', ['locale' => 'de']) }}">
                    <img src="{{ asset('images/flags/de.png') }}" alt="Deutsch" class="flag-icon">
                    Deutsch
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'es' ? 'active' : '' }}" 
                   href="{{ route('language.switch', ['locale' => 'es']) }}">
                    <img src="{{ asset('images/flags/es.png') }}" alt="Español" class="flag-icon">
                    Español
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ app()->getLocale() === 'ja' ? 'active' : '' }}" 
                   href="{{ route('language.switch', ['locale' => 'ja']) }}">
                    <img src="{{ asset('images/flags/ja.png') }}" alt="日本語" class="flag-icon">
                    日本語
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
.language-selector {
    display: inline-block;
    margin: 0 10px;
}

.language-selector .dropdown-menu {
    min-width: 160px;
    padding: 0.5rem 0;
    margin: 0;
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.language-selector .dropdown-item {
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #333;
    text-decoration: none;
}

.language-selector .dropdown-item.active {
    background-color: #007bff;
    color: white;
}

.language-selector .dropdown-item:hover {
    background-color: #f8f9fa;
}

.language-selector .flag-icon {
    width: 20px;
    height: 15px;
    object-fit: cover;
    border-radius: 2px;
}

.language-selector .btn-link {
    text-decoration: none;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff !important;
}

.language-selector .btn-link:hover {
    opacity: 0.8;
    color: #fff !important;
}

.language-selector .btn-link:focus {
    box-shadow: none;
    color: #fff !important;
}

.language-selector .dropdown-toggle::after {
    margin-left: 0.5rem;
}
</style> 