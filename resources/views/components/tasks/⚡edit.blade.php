<?php

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Facades\Gate;

new class extends Component
{
    public $task;

    public $title = '';
    public $description = '';
    public $status = 'a_faire';
    public $priority = 'moyenne';
    public $due_date = '';

    public function mount(Task $task)
    {

        $task->load('project');
        Gate::authorize('update', $task);
        $this->task = $task;
        // Vérifier que la tâche appartient bien à l'utilisateur connecté
        //Gate::authorize('update', $this->task);

        $this->title = $task->title;
        $this->description = $task->description;
        $this->status = $task->status;
        $this->priority = $task->priority;
        $this->due_date = $task->due_date?->format('Y-m-d');
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|string|max:180',
            'description' => 'nullable|string|max:3000',
            'status' => 'required|in:a_faire,en_cours,termine',
            'priority' => 'required|in:basse,moyenne,haute',
            'due_date' => 'nullable|date',

        ]);

        $this->task->update([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date ?: null,
            'completed_at' => $this->status === 'termine' ? now() : null,
        ]);

        session()->flash('success', 'Tâche modifiée avec succès.');
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
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                             M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Modifier la tâche
                </h1>

                <p class="text-sm text-gray-500">
                    Mettez à jour les informations de votre tâche.
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
                    {{ $task->project->name }}
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


    {{-- Carte du formulaire --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- En-tête de la carte --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                Informations de la tâche
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Modifiez les informations puis enregistrez vos changements.
            </p>
        </div>


        {{-- Formulaire --}}
        <form wire:submit="update" class="p-6 space-y-6">

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
                    Modifiez l'échéance si nécessaire.
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
                    href="{{ route('tasks.index', $task->project->id) }}"
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

                    {{-- Icône normale --}}
                    <svg
                        wire:loading.remove
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
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
                        Enregistrer les modifications
                    </span>

                    <span wire:loading>
                        Enregistrement...
                    </span>

                </button>

            </div>

        </form>
    </div>

</div>