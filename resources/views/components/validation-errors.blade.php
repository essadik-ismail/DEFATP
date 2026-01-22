@if ($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
    <div class="flex items-center gap-2 font-semibold mb-2">
        <i class="fas fa-exclamation-circle"></i>
        <span>Erreurs de validation:</span>
    </div>
    <ul class="list-disc pl-5 space-y-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
