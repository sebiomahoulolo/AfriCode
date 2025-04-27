
<x-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>



    <div class="social-login text-center mb-4">
        <p class="text-muted">Ou connectez-vous avec :</p>
        <a href="{{ route('social.login', 'facebook') }}" class="btn btn-facebook">
            <i class="fab fa-facebook-f"></i> Facebook
        </a>
        <a href="{{ route('social.login', 'google') }}" class="btn btn-google">
            <i class="fab fa-google"></i> Google
        </a>
        <a href="{{ route('social.login', 'github') }}" class="btn btn-github">
            <i class="fab fa-github"></i> GitHub
        </a>
        <a href="{{ route('social.login', 'linkedin') }}" class="btn btn-linkedin">
            <i class="fab fa-linkedin"></i> LinkedIn
        </a>
    </div>

    <style>
        .social-login {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px; /* Espacement entre les boutons */
        }

        .social-login a {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff; /* Texte en blanc pour contraste */
            transition: background-color 0.3s;
            font-size: 1rem; /* Taille du texte */
        }

        .social-login a i {
            margin-right: 10px;
            font-size: 24px; /* Taille de l'icône augmentée */
        }

        .social-login a:hover {
            background-color: rgb(39, 235, 140);
        }

        .btn-facebook {
            background-color: #3b5998;
            border-color: #3b5998;
        }

        .btn-google {
            background-color: #db4437;
            border-color: #db4437;
        }

        .btn-github {
            background-color: #333;
            border-color: #333;
        }

        .btn-linkedin {
            background-color: #0077b5;
            border-color: #0077b5;
        }
    </style>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Surname -->
        <div>
            <x-input-label for="surname" :value="__('Surname')" />
            <x-text-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required autofocus autocomplete="surname" />
            <x-input-error :messages="$errors->get('surname')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Déjà inscrit ?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-layout>
