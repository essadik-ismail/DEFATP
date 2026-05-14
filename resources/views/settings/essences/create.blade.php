@extends('layouts.app')

@section('title', 'Nouvelle Essence')

@section('breadcrumb')
<li class="bc-item"><a href="{{ route('settings.index') }}">Paramètres</a></li>
<li class="bc-item"><a href="{{ route('settings.essences.index') }}">Essences</a></li>
<li class="bc-item active">Nouvelle</li>
@endsection

@section('content')
<div class="min-w-0 max-w-full overflow-x-hidden">

    {{-- ─── Page header ─────────────────────────────────────────────── --}}
    <x-page-header
        title="Nouvelle essence"
        subtitle="Ajouter une essence forestière au système"
        icon="fas fa-leaf"
    >
        <x-slot name="actions">
            <x-button
                href="{{ route('settings.essences.index') }}"
                variant="secondary"
                icon="fas fa-arrow-left"
                size="sm"
            >
                Retour à la liste
            </x-button>
        </x-slot>
    </x-page-header>

    {{-- ─── Validation errors ──────────────────────────────────────── --}}
    @if($errors->any())
        <x-alert type="error" title="Erreurs de validation" dismissible class="mb-4">
            <ul class="list-disc list-inside space-y-0.5 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ route('settings.essences.store') }}" method="POST">
        @csrf

        <x-form-card title="Informations de l'essence" max-width="xl">

            <x-form-input
                name="essence"
                label="Nom de l'essence"
                placeholder="Ex : Moabi, Sapelli, Ayous…"
                required
                :value="old('essence')"
            />

            <x-slot name="footer">
                <x-button href="{{ route('settings.essences.index') }}" variant="secondary">
                    Annuler
                </x-button>
                <x-button type="submit" icon="fas fa-save">
                    Créer l'essence
                </x-button>
            </x-slot>

            <x-slot name="aside">
                <div
                    class="rounded-2xl border bg-emerald-50 p-5"
                    style="border-color: rgba(5,150,105,0.2);"
                >
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-lightbulb text-emerald-600 text-sm"></i>
                        <h3 class="text-sm font-semibold text-emerald-800">À savoir</h3>
                    </div>
                    <ul class="space-y-2 text-xs text-emerald-700 leading-relaxed">
                        <li class="flex gap-2">
                            <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                            Le nom doit être <strong>unique</strong> dans le système.
                        </li>
                        <li class="flex gap-2">
                            <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                            Les essences sont utilisées lors de la création d'articles.
                        </li>
                        <li class="flex gap-2">
                            <i class="fas fa-check-circle mt-0.5 flex-shrink-0"></i>
                            Vous pouvez aussi importer des essences depuis un fichier Excel.
                        </li>
                    </ul>
                </div>
            </x-slot>

        </x-form-card>

    </form>

</div>
@endsection
