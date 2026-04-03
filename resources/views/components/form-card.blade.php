{{--
    Form Card — standard wrapper used on every Create / Edit page.
    ================================================================
    Provides the white card container, optional section title + description,
    and a named footer slot for action buttons.

    Props:
        title       (string|null) – card section heading  (e.g. "Informations")
        description (string|null) – card section sub-text
        maxWidth    (string)      – Tailwind max-w-* suffix: sm|md|lg|xl|2xl|3xl|full
                                    Defaults to "2xl"

    Slots:
        $slot         – the form body (fields)
        $footer       – action buttons row (cancel + submit)
        $aside        – optional right-hand info/help panel

    Usage:
        <x-form-card title="Informations" max-width="xl">
            <x-form-input name="name" label="Nom" required />

            <x-slot name="footer">
                <x-button href="{{ route(...) }}" variant="secondary">Annuler</x-button>
                <x-button type="submit" icon="fas fa-save">Enregistrer</x-button>
            </x-slot>
        </x-form-card>

        With aside panel:
        <x-form-card max-width="full">
            ...fields...
            <x-slot name="aside">
                <p>Help text here</p>
            </x-slot>
        </x-form-card>
--}}

@props([
    'title'       => null,
    'description' => null,
    'maxWidth'    => '2xl',
])

@php
    $maxWClasses = [
        'sm'   => 'max-w-sm',
        'md'   => 'max-w-md',
        'lg'   => 'max-w-lg',
        'xl'   => 'max-w-xl',
        '2xl'  => 'max-w-2xl',
        '3xl'  => 'max-w-3xl',
        '4xl'  => 'max-w-4xl',
        'full' => 'max-w-full',
    ];
    $maxWClass = $maxWClasses[$maxWidth] ?? $maxWClasses['2xl'];
    $hasAside  = isset($aside) && $aside->isNotEmpty();
@endphp

<div class="{{ $hasAside ? 'grid gap-6 lg:grid-cols-3' : $maxWClass }}">

    {{-- ── Main form card ────────────────────────────────────────── --}}
    <div
        class="{{ $hasAside ? 'lg:col-span-2' : '' }} rounded-2xl border bg-white"
        style="border-color: rgba(154,179,163,0.4); box-shadow: 0 2px 8px rgba(0,0,0,0.04);"
    >
        {{-- Optional section header --}}
        @if($title)
        <div class="flex items-center gap-3 border-b border-gray-100 bg-gray-50/60 px-6 py-4">
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $title }}</p>
                @if($description)
                    <p class="mt-0.5 text-xs text-gray-500">{{ $description }}</p>
                @endif
            </div>
        </div>
        @endif

        {{-- Form body --}}
        <div class="p-6">
            {{ $slot }}
        </div>

        {{-- Footer actions --}}
        @if(isset($footer))
        <div class="flex items-center justify-end gap-3 border-t border-gray-100 bg-gray-50/40 px-6 py-4">
            {{ $footer }}
        </div>
        @endif
    </div>

    {{-- ── Optional aside panel ──────────────────────────────────── --}}
    @if($hasAside)
    <div class="space-y-4">
        {{ $aside }}
    </div>
    @endif

</div>
