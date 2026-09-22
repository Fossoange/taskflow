<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::livewire('/dashboard', 'dashboard')
    ->middleware('auth')
    ->name('dashboard');

Route::livewire('/projects', 'projects.index')
    ->middleware('auth')
    ->name('projects.index');

Route::livewire('/projects/create', 'projects.create')
    ->middleware('auth')
    ->name('projects.create');

Route::livewire('/projects/{project}/edit', 'projects.edit')
    ->middleware('auth')
    ->name('projects.edit');

Route::livewire('/projects/{projectId}/tasks', 'tasks.index')
    ->middleware('auth')
    ->name('tasks.index');

Route::livewire('/projects/{projectId}/tasks/create', 'tasks.create')
    ->middleware('auth')
    ->name('tasks.create');

Route::livewire('/tasks/{task}/edit', 'tasks.edit')
    ->middleware('auth')
    ->name('tasks.edit');