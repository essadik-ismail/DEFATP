{{--
    Reusable Delete / Action Confirmation Modal (Alpine.js)
    =========================================================
    Place ONE instance of this component anywhere inside @section('content')
    (typically at the very end, before @endsection).

    Trigger from any element:
        @click="$dispatch('delete-confirm', {
            action : '{{ route('carnets.destroy', $carnet) }}',
            label  : 'carnet #{{ $carnet->num }}',
            method : 'DELETE',          // optional – defaults to DELETE
            title  : 'Supprimer …',     // optional – overrides the modal title
            btnText: 'Supprimer',       // optional – overrides the confirm button label
            danger : true               // optional – false = warning style (amber)
        })"

    The CSRF token is injected by Blade at page-render time.
--}}

@props([
    'id' => 'global-delete-confirm',
])

<div
    id="{{ $id }}"
    x-data="{
        open   : false,
        action : '',
        label  : '',
        method : 'DELETE',
        title  : 'Confirmer la suppression',
        btnText: 'Supprimer',
        danger : true,
        init() {
            window.addEventListener('delete-confirm', (e) => {
                const d     = e.detail ?? {};
                this.action  = d.action  ?? '';
                this.label   = d.label   ?? 'cet élément';
                this.method  = d.method  ?? 'DELETE';
                this.title   = d.title   ?? 'Confirmer la suppression';
                this.btnText = d.btnText ?? 'Supprimer';
                this.danger  = d.danger  !== false;
                this.open    = true;
            });
        }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[9998] flex items-end sm:items-center justify-center p-4"
    style="background: rgba(0,0,0,0.45); backdrop-filter: blur(3px);"
    @keydown.escape.window="open = false"
    @click.self="open = false"
>
    {{-- Dialog panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden"
        @click.stop
    >
        {{-- Icon strip --}}
        <div
            class="px-6 pt-6 pb-4 text-center"
            :class="danger ? '' : 'bg-amber-50'"
        >
            <div
                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full"
                :class="danger ? 'bg-red-100' : 'bg-amber-100'"
            >
                <i
                    class="text-xl"
                    :class="danger ? 'fas fa-trash-alt text-red-600' : 'fas fa-exclamation-triangle text-amber-600'"
                ></i>
            </div>

            <h3 class="text-lg font-semibold text-gray-900" x-text="title"></h3>

            <p class="mt-2 text-sm text-gray-500 leading-relaxed">
                Vous êtes sur le point de supprimer
                <strong class="font-semibold text-gray-800" x-text="label"></strong>.
                <br>Cette action est <span class="font-semibold text-red-600">irréversible</span>.
            </p>
        </div>

        {{-- Action buttons --}}
        <div class="flex gap-3 border-t border-gray-100 bg-gray-50 px-4 py-3">
            <button
                type="button"
                @click="open = false"
                class="btn-secondary flex-1 py-2.5 text-sm"
            >
                <i class="fas fa-times"></i>
                Annuler
            </button>

            <form :action="action" method="POST" class="flex-1" x-ref="deleteForm">
                @csrf
                <input type="hidden" name="_method" :value="method">
                <button
                    type="submit"
                    class="w-full py-2.5 text-sm inline-flex items-center justify-center gap-2 rounded-xl font-semibold transition-all duration-200 text-white focus:outline-none focus:ring-2 focus:ring-offset-2"
                    :class="danger
                        ? 'bg-gradient-to-br from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 focus:ring-red-500'
                        : 'bg-gradient-to-br from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 focus:ring-amber-500'"
                >
                    <i :class="danger ? 'fas fa-trash-alt' : 'fas fa-check'"></i>
                    <span x-text="btnText"></span>
                </button>
            </form>
        </div>
    </div>
</div>
