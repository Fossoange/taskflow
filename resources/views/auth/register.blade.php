<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Inscription - TaskFlow</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        taskflow: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <div class="w-11 h-11 bg-taskflow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white text-xl font-bold">T</span>
                </div>

                <span class="text-3xl font-bold text-slate-800">
                    Task<span class="text-taskflow-600">Flow</span>
                </span>
            </a>

            <p class="mt-3 text-slate-500">
                Organisez vos projets. Suivez vos tâches.
            </p>
        </div>

        <!-- Carte -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">

            <div class="mb-7">
                <h1 class="text-2xl font-bold text-slate-800">
                    Créer un compte
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Rejoignez TaskFlow et commencez à organiser votre travail.
                </p>
            </div>

            <!-- Erreurs -->
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/register" class="space-y-5">
                @csrf

                <!-- Nom -->
                <div>
                    <label
                        for="name"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Nom
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Votre nom"
                        class="w-full px-4 py-3 rounded-lg border border-slate-300
                               text-slate-800 placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-taskflow-500
                               focus:border-taskflow-500 transition"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label
                        for="email"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Adresse email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        placeholder="exemple@email.com"
                        class="w-full px-4 py-3 rounded-lg border border-slate-300
                               text-slate-800 placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-taskflow-500
                               focus:border-taskflow-500 transition"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div>
                    <label
                        for="password"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Mot de passe
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-lg border border-slate-300
                               text-slate-800 placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-taskflow-500
                               focus:border-taskflow-500 transition"
                    >

                    @error('password')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirmation -->
                <div>
                    <label
                        for="password_confirmation"
                        class="block text-sm font-medium text-slate-700 mb-2"
                    >
                        Confirmer le mot de passe
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-lg border border-slate-300
                               text-slate-800 placeholder-slate-400
                               focus:outline-none focus:ring-2 focus:ring-taskflow-500
                               focus:border-taskflow-500 transition"
                    >
                </div>

                <!-- Bouton -->
                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-lg
                           bg-taskflow-600 hover:bg-taskflow-700
                           text-white font-semibold
                           shadow-sm hover:shadow-md
                           transition duration-200"
                >
                    Créer mon compte
                </button>
            </form>

            <!-- Connexion -->
            <div class="mt-7 pt-6 border-t border-slate-200 text-center">
                <p class="text-sm text-slate-500">
                    Vous avez déjà un compte ?
                    <a
                        href="/login"
                        class="font-semibold text-taskflow-600 hover:text-taskflow-700"
                    >
                        Se connecter
                    </a>
                </p>
            </div>

        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-400 mt-6">
            © {{ date('Y') }} TaskFlow. Tous droits réservés.
        </p>

    </div>

</body>
</html>