<?php

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

new class extends Component
{
    public $name = '';
    public $description = '';
    public $status = 'actif';

    public function save()
    {
        Gate::authorize('create', Project::class);

        $this->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:actif,en_pause,termine',
        ]);

        Project::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Projet créé avec succès.');

        $this->reset(['name', 'description']);
        $this->status = 'actif';
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
                    Créer un projet
                </h1>
                <p class="text-sm text-gray-500">
                    Ajoutez un nouveau projet à votre espace TaskFlow.
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
                Informations du projet
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Renseignez les informations principales de votre projet.
            </p>
        </div>

        {{-- Formulaire --}}
        <form wire:submit="save" class="p-6 space-y-6">

            {{-- Nom --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom du projet
                    <span class="text-red-500">*</span>
                </label>

                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    placeholder="Ex : Création de mon site web"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                           outline-none transition
                           @error('name') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                >

                @error('name')
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
                    placeholder="Décrivez brièvement votre projet..."
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                           outline-none transition resize-none
                           @error('description') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                ></textarea>

                <div class="mt-2 flex justify-between">
                    <p class="text-xs text-gray-400">
                        Une description claire facilite le suivi du projet.
                    </p>

                    <p class="text-xs text-gray-400">
                        Maximum 2000 caractères
                    </p>
                </div>

                @error('description')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

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
                           outline-none transition
                           @error('status') border-red-400 focus:border-red-500 focus:ring-red-100 @enderror"
                >
                    <option value="actif">Actif</option>
                    <option value="en_pause">En pause</option>
                    <option value="termine">Terminé</option>
                </select>

                @error('status')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="pt-5 border-t border-gray-100 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">

                <a
                    href="{{ route('projects.index') }}"
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
                        Créer le projet
                    </span>

                    <span wire:loading>
                        Création...
                    </span>
                </button>

            </div>

        </form>
    </div>
</div>