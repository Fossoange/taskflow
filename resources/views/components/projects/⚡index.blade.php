<?php

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

new class extends Component
{
    public $projects;

    public function mount()
    {
        #recuperer les projets de l'utilisateur connecte
        $this->projects = Project::where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);

        Gate::authorize('delete', $project);

        $project->delete();

        $this->projects = Project::where('user_id', auth()->id())
            ->latest()
            ->get();

        session()->flash('success', 'Projet supprimé avec succès.');
        }
};
?>

<div class="max-w-7xl mx-auto">

<!-- En-tête -->
<div class="flex flex-col sm:flex-row sm:items-center
            sm:justify-between gap-4 mb-8">

    <div>
        <p class="text-sm font-medium text-taskflow-600 mb-1">
            Gestion des projets
        </p>

        <h1 class="text-3xl font-bold text-gray-900">
            Mes projets
        </h1>

        <p class="text-gray-500 mt-2">
            Organisez vos projets et suivez leur progression.
        </p>
    </div>

    <a href="{{ route('projects.create') }}"
       class="inline-flex items-center justify-center gap-2
              px-5 py-3 rounded-xl
              bg-taskflow-600 hover:bg-taskflow-700
              text-white font-medium shadow-sm
              transition">

        <span class="text-lg">+</span>
        Nouveau projet

    </a>

</div>


<!-- Message de succès -->
@if (session()->has('success'))

    <div class="mb-6 flex items-center gap-3
                bg-green-50 border border-green-200
                text-green-700 rounded-xl px-5 py-4">

        <span class="text-xl">✓</span>

        <p class="font-medium">
            {{ session('success') }}
        </p>

    </div>

@endif


@if ($projects->count())

    <!-- Tableau -->
    <div class="bg-white border border-gray-200
                rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        <th class="px-6 py-4 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Projet
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Description
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider
                                   text-right">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @foreach ($projects as $project)

                        <tr class="hover:bg-gray-50 transition">

                            <!-- Projet -->
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl
                                                bg-taskflow-100
                                                flex items-center justify-center
                                                text-taskflow-600">
                                        📁
                                    </div>

                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $project->name }}
                                        </p>

                                        <p class="text-xs text-gray-400 mt-1">
                                            Projet #{{ $project->id }}
                                        </p>
                                    </div>

                                </div>

                            </td>


                            <!-- Description -->
                            <td class="px-6 py-5 max-w-md">

                                <p class="text-sm text-gray-600 line-clamp-2">
                                    {{ $project->description ?: 'Aucune description' }}
                                </p>

                            </td>


                            <!-- Statut -->
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center
                                             px-3 py-1 rounded-full
                                             text-xs font-semibold

                                    @if ($project->status === 'termine')
                                        bg-green-100 text-green-700

                                    @elseif ($project->status === 'en_cours')
                                        bg-blue-100 text-blue-700

                                    @else
                                        bg-gray-100 text-gray-600
                                    @endif">

                                    @if ($project->status === 'termine')
                                        <span class="mr-1.5">✓</span>
                                        Terminé

                                    @elseif ($project->status === 'en_cours')
                                        <span class="mr-1.5">●</span>
                                        En cours

                                    @else
                                        <span class="mr-1.5">○</span>
                                        À faire
                                    @endif

                                </span>

                            </td>


                            <!-- Actions -->
                            <td class="px-6 py-5">

                                <div class="flex flex-wrap items-center
                                            justify-end gap-2">

                                    <!-- Tâches -->
                                    <a href="{{ route('tasks.index', $project->id) }}"
                                       class="inline-flex items-center gap-1.5
                                              px-3 py-2 rounded-lg
                                              bg-blue-50 text-blue-600
                                              hover:bg-blue-100
                                              text-sm font-medium transition">

                                        📋
                                        Tâches

                                    </a>


                                    <!-- Nouvelle tâche -->
                                    <a href="{{ route('tasks.create', $project->id) }}"
                                       class="inline-flex items-center gap-1.5
                                              px-3 py-2 rounded-lg
                                              bg-green-50 text-green-600
                                              hover:bg-green-100
                                              text-sm font-medium transition">

                                        +
                                        Tâche

                                    </a>


                                    <!-- Modifier -->
                                    <a href="{{ route('projects.edit', $project->id) }}"
                                       class="inline-flex items-center gap-1.5
                                              px-3 py-2 rounded-lg
                                              bg-gray-100 text-gray-700
                                              hover:bg-gray-200
                                              text-sm font-medium transition">

                                        ✏️
                                        Modifier

                                    </a>


                                    <!-- Supprimer -->
                                    <button
                                        type="button"
                                        wire:click="delete({{ $project->id }})"
                                        wire:confirm="Êtes-vous sûr de vouloir supprimer ce projet ?"
                                        class="inline-flex items-center gap-1.5
                                               px-3 py-2 rounded-lg
                                               bg-red-50 text-red-600
                                               hover:bg-red-100
                                               text-sm font-medium transition">

                                        🗑️
                                        Supprimer

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    <!-- Nombre de projets -->
    <div class="mt-4 text-sm text-gray-500">

        {{ $projects->count() }}
        {{ $projects->count() > 1 ? 'projets' : 'projet' }}

    </div>

@else

    <!-- État vide -->
    <div class="bg-white border border-gray-200 rounded-2xl
                shadow-sm p-12 text-center">

        <div class="w-20 h-20 mx-auto rounded-2xl
                    bg-taskflow-50
                    flex items-center justify-center
                    text-4xl mb-5">
            📁
        </div>

        <h2 class="text-xl font-bold text-gray-900">
            Aucun projet pour le moment
        </h2>

        <p class="text-gray-500 mt-2 max-w-md mx-auto">
            Commencez par créer votre premier projet pour organiser
            vos tâches et suivre votre progression.
        </p>

        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center gap-2
                  mt-6 px-5 py-3 rounded-xl
                  bg-taskflow-600 hover:bg-taskflow-700
                  text-white font-medium
                  transition">

            +
            Créer mon premier projet

        </a>

    </div>

@endif

</div>