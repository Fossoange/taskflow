<!DOCTYPE html>

<html lang="fr"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>TaskFlow</title>

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

<body class="bg-gray-50 text-gray-800">

@auth

    <div class="min-h-screen flex">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">

            <!-- Logo -->
            <div class="h-20 flex items-center px-6 border-b border-gray-200">
                <a href="{{ route('dashboard') }}"
                   class="text-2xl font-bold text-taskflow-600">
                    Task<span class="text-gray-800">Flow</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2">

                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg
                          text-gray-700 hover:bg-taskflow-50 hover:text-taskflow-600
                          transition">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('projects.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg
                          text-gray-700 hover:bg-taskflow-50 hover:text-taskflow-600
                          transition">
                    <span>📁</span>
                    <span>Mes projets</span>
                </a>

            </nav>

            <!-- User -->
            <div class="border-t border-gray-200 p-4">

                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-taskflow-100
                                flex items-center justify-center
                                text-taskflow-600 font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div class="overflow-hidden">
                        <p class="font-semibold truncate">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-sm text-gray-500 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2
                                   px-4 py-2 rounded-lg
                                   bg-gray-100 hover:bg-red-50
                                   text-gray-700 hover:text-red-600
                                   transition">
                        <span>↪</span>
                        Déconnexion
                    </button>
                </form>

            </div>

        </aside>


        <!-- CONTENU PRINCIPAL -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOPBAR -->
            <header class="h-20 bg-white border-b border-gray-200
                           flex items-center justify-between px-6">

                <div>
                    <h1 class="text-xl font-semibold text-gray-800">
                        TaskFlow
                    </h1>

                    <p class="text-sm text-gray-500">
                        Gérez vos projets et vos tâches simplement
                    </p>
                </div>

                <!-- Version mobile -->
                <div class="md:hidden">
                    <a href="{{ route('projects.index') }}"
                       class="px-4 py-2 bg-taskflow-600 text-white
                              rounded-lg text-sm">
                        Projets
                    </a>
                </div>

            </header>


            <!-- PAGE -->
            <main class="flex-1 p-4 md:p-8">

                {{ $slot }}

            </main>

            <!-- FOOTER -->
            <footer class="border-t border-gray-200 bg-white
                           px-6 py-4 text-center text-sm text-gray-500">

                © {{ date('Y') }} TaskFlow — Gestion de projets et de tâches
            </footer>

        </div>

    </div>

@else

    <!-- Pages non authentifiées -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

@endauth

</body> </html>