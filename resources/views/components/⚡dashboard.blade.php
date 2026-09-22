<?php 
use Livewire\Component; 
use App\Models\Project; 
use App\Models\Task; 
use Illuminate\Support\Carbon; 
new class extends Component 
{ 
public $totalProjects; 
public $totalTasks; 
public $completedTasks; 
public $inProgressTasks; 
public $overdueTasks; 
public $progression; 
public $recentTasks; 
public $upcomingTasks; 

public function mount() 
{ 
    $userId = auth()->id();
    $this->totalProjects = Project::where('user_id', $userId)->count();
    $tasks = Task::whereHas('project', function ($query) use ($userId) { $query->where('user_id', $userId); }); 
    $this->totalTasks = $tasks->count(); 
    $this->completedTasks = (clone $tasks)->where('status', 'termine')->count(); 
    $this->inProgressTasks = (clone $tasks) ->where('status', 'en_cours') ->count(); 
    $this->overdueTasks = (clone $tasks) ->whereDate('due_date', '<', Carbon::today())->where('status', '!=', 'termine') ->count(); 
    $this->progression = $this->totalTasks > 0 ? round(($this->completedTasks / $this->totalTasks) * 100) : 0; 
    $this->recentTasks = (clone $tasks) ->with('project') ->latest('updated_at')->take(5)->get(); 
    $this->upcomingTasks = (clone $tasks) ->with('project') ->whereNotNull('due_date') ->where('status', '!=', 'termine') ->whereDate('due_date', '>=', Carbon::today()) ->orderBy('due_date') ->take(5) ->get();
} 
}; 
?>

<div class="max-w-7xl mx-auto">

<!-- En-tête -->
<div class="mb-8">

    <p class="text-sm font-medium text-taskflow-600 mb-1">
        Tableau de bord
    </p>

    <h1 class="text-3xl font-bold text-gray-900">
        Bonjour, {{ auth()->user()->name }} 👋
    </h1>

    <p class="mt-2 text-gray-500">
        Voici un aperçu de votre activité sur TaskFlow.
    </p>

</div>


<!-- Statistiques -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Projets -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm
                hover:shadow-md transition">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    Projets
                </p>

                <p class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $totalProjects }}
                </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-blue-100
                        flex items-center justify-center text-2xl">
                📁
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-4">
            Total de vos projets
        </p>

    </div>


    <!-- Tâches -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm
                hover:shadow-md transition">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    Tâches
                </p>

                <p class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $totalTasks }}
                </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-purple-100
                        flex items-center justify-center text-2xl">
                📋
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-4">
            Toutes vos tâches
        </p>

    </div>


    <!-- En cours -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm
                hover:shadow-md transition">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    En cours
                </p>

                <p class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $inProgressTasks }}
                </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-orange-100
                        flex items-center justify-center text-2xl">
                🔄
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-4">
            Tâches actuellement en cours
        </p>

    </div>


    <!-- Terminées -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm
                hover:shadow-md transition">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-sm text-gray-500">
                    Terminées
                </p>

                <p class="text-3xl font-bold text-gray-900 mt-2">
                    {{ $completedTasks }}
                </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-green-100
                        flex items-center justify-center text-2xl">
                ✅
            </div>

        </div>

        <p class="text-xs text-gray-400 mt-4">
            Tâches terminées
        </p>

    </div>

</div>


<!-- Progression + Retards -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <!-- Progression -->
    <div class="lg:col-span-2 bg-white border border-gray-200
                rounded-2xl p-6 shadow-sm">

        <div class="flex items-center justify-between mb-4">

            <div>
                <h2 class="text-lg font-bold text-gray-900">
                    Progression globale
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    État d'avancement de toutes vos tâches.
                </p>
            </div>

            <span class="text-2xl font-bold text-taskflow-600">
                {{ $progression }}%
            </span>

        </div>

        <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">

            <div
                class="bg-taskflow-600 h-4 rounded-full transition-all duration-500"
                style="width: {{ $progression }}%">
            </div>

        </div>

        <div class="flex justify-between mt-3 text-xs text-gray-400">

            <span>
                {{ $completedTasks }} terminée(s)
            </span>

            <span>
                {{ $totalTasks }} tâche(s)
            </span>

        </div>

    </div>


    <!-- Tâches en retard -->
    <div class="bg-white border border-gray-200 rounded-2xl
                p-6 shadow-sm">

        <div class="flex items-center gap-3">

            <div class="w-11 h-11 rounded-xl bg-red-100
                        flex items-center justify-center text-xl">
                ⚠️
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Tâches en retard
                </p>

                <p class="text-2xl font-bold text-red-600">
                    {{ $overdueTasks }}
                </p>
            </div>

        </div>

        <p class="text-sm text-gray-500 mt-5">
            Tâches dont la date d'échéance est dépassée.
        </p>

    </div>

</div>


<!-- Tâches -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Dernières tâches -->
    <div class="bg-white border border-gray-200 rounded-2xl
                shadow-sm overflow-hidden">

        <div class="p-6 border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Dernières tâches
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Vos 5 dernières tâches.
                    </p>
                </div>

                <span class="text-xl">
                    📋
                </span>

            </div>

        </div>


        <div class="divide-y divide-gray-100">

            @forelse ($recentTasks as $task)

                <div class="p-5 hover:bg-gray-50 transition">

                    <div class="flex items-start justify-between gap-4">

                        <div class="min-w-0">

                            <p class="font-semibold text-gray-800 truncate">
                                {{ $task->title }}
                            </p>

                            @if ($task->project)
                                <p class="text-xs text-gray-500 mt-1">
                                    📁 {{ $task->project->name }}
                                </p>
                            @endif

                        </div>


                        <span class="shrink-0 px-2.5 py-1 rounded-full text-xs font-medium
                            @if ($task->status === 'termine')
                                bg-green-100 text-green-700
                            @elseif ($task->status === 'en_cours')
                                bg-blue-100 text-blue-700
                            @else
                                bg-gray-100 text-gray-600
                            @endif">

                            @if ($task->status === 'termine')
                                Terminé
                            @elseif ($task->status === 'en_cours')
                                En cours
                            @else
                                À faire
                            @endif

                        </span>

                    </div>

                    <div class="mt-3">

                        <span class="text-xs px-2 py-1 rounded-md bg-gray-100 text-gray-600">
                            Priorité : {{ ucfirst($task->priority) }}
                        </span>

                    </div>

                </div>

            @empty

                <div class="p-8 text-center">

                    <div class="text-4xl mb-3">
                        📋
                    </div>

                    <p class="text-gray-500">
                        Aucune tâche pour le moment.
                    </p>

                </div>

            @endforelse

        </div>

    </div>


    <!-- Prochaines échéances -->
    <div class="bg-white border border-gray-200 rounded-2xl
                shadow-sm overflow-hidden">

        <div class="p-6 border-b border-gray-100">

            <div class="flex items-center justify-between">

                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Prochaines échéances
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Les tâches à venir.
                    </p>
                </div>

                <span class="text-xl">
                    📅
                </span>

            </div>

        </div>


        <div class="divide-y divide-gray-100">

            @forelse ($upcomingTasks as $task)

                <div class="p-5 hover:bg-gray-50 transition">

                    <div class="flex items-center justify-between gap-4">

                        <div class="min-w-0">

                            <p class="font-semibold text-gray-800 truncate">
                                {{ $task->title }}
                            </p>

                            @if ($task->project)
                                <p class="text-xs text-gray-500 mt-1">
                                    📁 {{ $task->project->name }}
                                </p>
                            @endif

                        </div>

                        <div class="text-right shrink-0">

                            <p class="text-sm font-semibold text-taskflow-600">
                                {{ $task->due_date->format('d/m/Y') }}
                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-8 text-center">

                    <div class="text-4xl mb-3">
                        🎉
                    </div>

                    <p class="text-gray-500">
                        Aucune échéance à venir.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>


<!-- Bouton projets -->
<div class="mt-8 text-center">

    <a href="{{ route('projects.index') }}"
       class="inline-flex items-center gap-2 px-6 py-3
              bg-taskflow-600 hover:bg-taskflow-700
              text-white font-medium rounded-xl
              shadow-sm transition">

        📁
        Gérer mes projets

    </a>

</div>

</div>