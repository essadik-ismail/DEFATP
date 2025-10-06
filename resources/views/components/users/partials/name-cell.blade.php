@php /** @var \App\Models\User $user */ @endphp
<div class="d-flex align-items-center">
    @if($user->image)
        <img src="{{ asset('storage/' . $user->image) }}" 
             alt="{{ $user->name }}" 
             class="rounded-circle me-2" 
             width="32" height="32">
    @else
        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
             style="width: 32px; height: 32px;">
            <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
        </div>
    @endif
    <div>
        <div class="fw-bold">{{ $user->name }}</div>
    </div>
    </div>

