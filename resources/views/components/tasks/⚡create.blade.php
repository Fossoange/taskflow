<?php

use Livewire\Component;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

new class extends Component
{
    public $project;
    public $title = '';
    public $description = '';
    public $status = 'a_faire';
    public $priority = 'moyenne';
    public $due_date = '';

    public function mount($projectId)
    {
        $this->project = Project::findOrFail($projectId);
        Gate::authorize('view', $this->project);
    }

    public function save()
    {
        Gate::authorize('view', $this->project);
        
        $this->validate([
            'title' => 'required|string|max:180',
            'description' => 'nullable|string|max:3000',
            'status' => 'required|in:a_faire,en_cours,termine',
            'priority' => 'required|in:basse,moyenne,haute',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'project_id' => $this->project->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date ?: null,
        ]);

        session()->flash('success', 'Tâche créée avec succès.');

        $this->reset([
            'title',
            'description',
            'due_date',
        ]);

        $this->status = 'a_faire';
        $this->priority = 'moyenne';
    }
};
?>

<div class="max-w-3xl mx-auto">

    {{-- En-tête --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-11 h-11 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Créer une tâche
                </h1>

                <p class="text-sm text-gray-500">
                    Ajoutez une nouvelle tâche à votre projet.
                </p>
            </div>
        </div>

        {{-- Projet --}}
        <div class="mt-5 flex items-center gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
            </svg>

            <div>
                <p class="text-xs font-medium text-blue-600">
                    Projet
                </p>

                <p class="text-sm font-semibold text-blue-900">
                    {{ $project->name }}
                </p>
            </div>
        </div>
    </div>


    {{-- Message de succès --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>

            <span class="text-sm font-medium">
                {{ session('success') }}
            </span>
        </div>
    @endif


    {{-- Carte formulaire --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                Informations de la tâche
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Renseignez les informations nécessaires pour organiser votre tâche.
            </p>
        </div>


        <form wire:submit="save" class="p-6 space-y-6">

            {{-- Titre --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="title"
                    type="text"
                    wire:model="title"
                    placeholder="Ex : Préparer la présentation du projet"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                           outline-none transition
                           @error('title') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                >

                @error('title')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>

                <textarea
                    id="description"
                    wire:model="description"
                    rows="5"
                    placeholder="Décrivez ce qu'il faut accomplir..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                           outline-none transition resize-none
                           @error('description') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                ></textarea>

                <div class="mt-2 flex justify-end">
                    <span class="text-xs text-gray-400">
                        Maximum 3000 caractères
                    </span>
                </div>

                @error('description')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Statut + priorité --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Statut --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Statut
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="status"
                        wire:model="status"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                               outline-none transition"
                    >
                        <option value="a_faire">À faire</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                    </select>

                    @error('status')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>


                {{-- Priorité --}}
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priorité
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="priority"
                        wire:model="priority"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                               outline-none transition"
                    >
                        <option value="basse">Basse</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>

                    @error('priority')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

            </div>


            {{-- Date limite --}}
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Date limite
                </label>

                <input
                    id="due_date"
                    type="date"
                    wire:model="due_date"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                           outline-none transition
                           @error('due_date') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                >

                <p class="mt-2 text-xs text-gray-400">
                    Cette date permet de suivre les échéances de la tâche.
                </p>

                @error('due_date')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            {{-- Actions --}}
            <div class="pt-5 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                <a
                    href="{{ route('tasks.index', $project->id) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300
                           bg-white px-5 py-3 text-sm font-medium text-gray-700
                           hover:bg-gray-50 transition"
                >
                    Annuler
                </a>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           bg-blue-600 px-5 py-3 text-sm font-semibold text-white
                           hover:bg-blue-700 focus:outline-none focus:ring-2
                           focus:ring-blue-500 focus:ring-offset-2
                           disabled:opacity-60 disabled:cursor-not-allowed transition"
                >

                    {{-- Icône --}}
                    <svg
                        wire:loading.remove
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    {{-- Spinner --}}
                    <svg
                        wire:loading
                        class="w-5 h-5 animate-spin"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                        ></path>
                    </svg>

                    <span wire:loading.remove>
                        Créer la tâche
                    </span>

                    <span wire:loading>
                        Création...
                    </span>

                </button>

            </div>

        </form>
    </div>

</div>