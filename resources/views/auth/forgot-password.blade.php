<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mot de passe oublié - TaskFlow</title>

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

<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">

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
                Récupérez l'accès à votre compte.
            </p>
        </div>

        <!-- Carte -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">

            <div class="mb-7">
                <h1 class="text-2xl font-bold text-slate-800">
                    Mot de passe oublié ?
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Entrez votre adresse email et nous vous enverrons
                    un lien pour réinitialiser votre mot de passe.
                </p>
            </div>

            <!-- Message de succès -->
            @if (session('status'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3">
                    <p class="text-sm text-green-600">
                        {{ session('status') }}
                    </p>
                </div>
            @endif

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

            <form method="POST" action="/forgot-password" class="space-y-5">
                @csrf

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
                        autofocus
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

                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-lg
                           bg-taskflow-600 hover:bg-taskflow-700
                           text-white font-semibold
                           shadow-sm hover:shadow-md
                           transition duration-200"
                >
                    Envoyer le lien
                </button>
            </form>

            <!-- Retour connexion -->
            <div class="mt-7 pt-6 border-t border-slate-200 text-center">
                <a
                    href="/login"
                    class="text-sm font-semibold text-taskflow-600 hover:text-taskflow-700"
                >
                    ← Retour à la connexion
                </a>
            </div>

        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-400 mt-6">
            © {{ date('Y') }} TaskFlow. Tous droits réservés.
        </p>

    </div>

</body>
</html>