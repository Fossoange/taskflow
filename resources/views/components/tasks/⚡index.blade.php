<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Project;
use App\Models\Task;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

new class extends Component
{
    use WithPagination;

    public $project;
    public $search = '';
    public $status = '';
    public $priority = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public function loadTasks()
    {
        // On ne stocke plus les tâches dans une propriété Livewire
        // La requête sera faite directement dans la vue.
    }

    #[Computed]
    public function tasks()
    {
        $query = $this->project->tasks();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->priority) {
            $query->where('priority', $this->priority);
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(5);
    }

    public function mount($projectId)
    {
        $this->project = Project::findOrFail($projectId);
        Gate::authorize('view', $this->project);
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);

        Gate::authorize('delete', $task);

        $task->delete();

        unset($this->tasks);

        $this->resetPage();

        session()->flash(
            'success',
            'Tâche supprimée avec succès.'
        );
    }
};
?>

<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                 M9 5a3 3 0 006 0M9 5a3 3 0 016 0"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Tâches du projet
                    </h1>

                    <p class="text-sm text-gray-500">
                        {{ $project->name }}
                    </p>
                </div>
            </div>
        </div>

        <a
            href="{{ route('tasks.create', $project->id) }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   bg-blue-600 px-5 py-3 text-sm font-semibold text-white
                   hover:bg-blue-700 transition shadow-sm"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>

            Nouvelle tâche
        </a>
    </div>


    {{-- Message de succès --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>

            <span class="text-sm font-medium">
                {{ session('success') }}
            </span>
        </div>
    @endif


    {{-- Barre de recherche et filtres --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Recherche --}}
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rechercher
                </label>

                <div class="relative">
                    <svg
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                    </svg>

                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Rechercher une tâche..."
                        class="w-full rounded-xl border border-gray-300 py-3 pl-10 pr-4 text-sm
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                               outline-none transition"
                    >
                </div>
            </div>


            {{-- Statut --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Statut
                </label>

                <select
                    wire:model.live="status"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                           outline-none transition"
                >
                    <option value="">Tous les statuts</option>
                    <option value="a_faire">À faire</option>
                    <option value="en_cours">En cours</option>
                    <option value="termine">Terminée</option>
                </select>
            </div>


            {{-- Priorité --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Priorité
                </label>

                <select
                    wire:model.live="priority"
                    class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                           outline-none transition"
                >
                    <option value="">Toutes les priorités</option>
                    <option value="basse">Basse</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="haute">Haute</option>
                </select>
            </div>

        </div>


        {{-- Tri --}}
        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-4 sm:items-center">

            <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 6h18M6 12h12m-9 6h6"/>
                </svg>

                <span>Trier par</span>
            </div>

            <select
                wire:model.live="sortBy"
                class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                       outline-none transition"
            >
                <option value="created_at">Date de création</option>
                <option value="due_date">Date limite</option>
                <option value="title">Titre</option>
            </select>

            <select
                wire:model.live="sortDirection"
                class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm
                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                       outline-none transition"
            >
                <option value="asc">Croissant</option>
                <option value="desc">Décroissant</option>
            </select>

        </div>
    </div>


    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        @if ($this->tasks->count())

            {{-- Version desktop --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Tâche
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Description
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Statut
                            </th>

                            <th class="text-left px-6 py-4 font-semibold text-gray-600">
                                Priorité
                            </th>

                            <th class="text-right px-6 py-4 font-semibold text-gray-600">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @foreach ($this->tasks as $task)

                            <tr wire:key="task-{{ $task->id }}" class="hover:bg-gray-50 transition">

                                {{-- Titre --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 5h6M9 9h6M9 13h4"/>
                                            </svg>
                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            {{ $task->title }}
                                        </span>

                                    </div>
                                </td>


                                {{-- Description --}}
                                <td class="px-6 py-4 max-w-xs">
                                    <p class="text-gray-500 truncate">
                                        {{ $task->description ?: 'Aucune description' }}
                                    </p>
                                </td>


                                {{-- Statut --}}
                                <td class="px-6 py-4">

                                    @if ($task->status === 'termine')

                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Terminé
                                        </span>

                                    @elseif ($task->status === 'en_cours')

                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                            En cours
                                        </span>

                                    @else

                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                            À faire
                                        </span>

                                    @endif

                                </td>


                                {{-- Priorité --}}
                                <td class="px-6 py-4">

                                    @if ($task->priority === 'haute')

                                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                            Haute
                                        </span>

                                    @elseif ($task->priority === 'moyenne')

                                        <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                            Moyenne
                                        </span>

                                    @else

                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                            Basse
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        <a
                                            href="{{ route('tasks.edit', $task->id) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300
                                                   px-3 py-2 text-xs font-medium text-gray-700
                                                   hover:bg-gray-50 transition"
                                        >
                                            Modifier
                                        </a>

                                        <button
                                            type="button"
                                            wire:click="delete({{ $task->id }})"
                                            wire:confirm="Êtes-vous sûr de vouloir supprimer cette tâche ?"
                                            class="inline-flex items-center gap-1.5 rounded-lg
                                                   bg-red-50 px-3 py-2 text-xs font-medium text-red-600
                                                   hover:bg-red-100 transition"
                                        >
                                            Supprimer
                                        </button>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>


            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $this->tasks->links() }}
            </div>

        @else

            {{-- État vide --}}
            <div class="px-6 py-16 text-center">

                <div class="mx-auto w-16 h-16 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                </div>

                <h3 class="text-lg font-semibold text-gray-900">
                    Aucune tâche trouvée
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    Commencez par créer une tâche pour ce projet.
                </p>

                <a
                    href="{{ route('tasks.create', $project->id) }}"
                    class="inline-flex items-center gap-2 mt-5 rounded-xl
                           bg-blue-600 px-5 py-3 text-sm font-semibold text-white
                           hover:bg-blue-700 transition"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    Créer une tâche
                </a>

            </div>

        @endif

    </div>

</div>