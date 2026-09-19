<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TaskFlow</title>
</head>
<body>

    <h1>Dashboard</h1>

    <p>Bienvenue {{ auth()->user()->name }}</p>

    <p>Voici votre espace personnel TaskFlow.</p>

    <a href="{{ route('projects.index') }}">
        Mes projets
    </a>

    <br><br>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">
            Déconnexion
        </button>
    </form>

</body>
</html>