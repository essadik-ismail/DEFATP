{{--
    Shared role form partial.
    Vars expected:
      $role        – Spatie Role model (or null for create)
      $permissions – array keyed by module label → array of Permission models
      $rolePermissions – array of permission name strings currently on this role ([] for create)
    Optional:
      $isProtected – bool, disables name field when true
--}}

@php
    $isProtected   = $isProtected ?? false;
    $rolePerms     = $rolePermissions ?? [];
    $canAssignPerms = auth()->user()?->can('roles.assign_permissions') ?? false;
@endphp

{{-- Name field --}}
<div style="margin-bottom:1.25rem;">
    <label for="name" class="form-label">
        Nom du rôle <span style="color:#E53E3E;">*</span>
    </label>
    @if($isProtected)
        <input type="text" class="form-input" value="{{ old('name', $role?->name) }}"
               disabled style="background:#F0F4F2; color:#8FA89B; cursor:not-allowed;">
        <input type="hidden" name="name" value="{{ $role?->name }}">
        <p style="font-size:0.75rem; color:#9AB3A3; margin-top:0.375rem;">
            <i class="fas fa-lock" style="font-size:0.65rem;"></i>
            Ce rôle système ne peut pas être renommé.
        </p>
    @else
        <input type="text" id="name" name="name" class="form-input @error('name') border-red-400 @enderror"
               value="{{ old('name', $role?->name) }}"
               placeholder="Ex: gestionnaire, superviseur…"
               required autofocus>
        @error('name')
            <p style="font-size:0.75rem; color:#E53E3E; margin-top:0.25rem;">{{ $message }}</p>
        @enderror
    @endif
</div>

{{-- Permissions section --}}
@if($canAssignPerms)
<div style="margin-top:1.5rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.875rem; flex-wrap:wrap; gap:0.5rem;">
        <h3 style="font-size:0.9375rem; font-weight:700; color:#1A2D22; margin:0;">
            <i class="fas fa-key" style="color:#276749; margin-right:0.375rem;"></i>
            Permissions
        </h3>
        <div style="display:flex; gap:0.5rem;">
            <button type="button" onclick="selectAll()" class="btn-secondary" style="font-size:0.75rem; padding:0.25rem 0.625rem;">
                <i class="fas fa-check-double"></i> Tout cocher
            </button>
            <button type="button" onclick="deselectAll()" class="btn-secondary" style="font-size:0.75rem; padding:0.25rem 0.625rem;">
                <i class="fas fa-times"></i> Tout décocher
            </button>
        </div>
    </div>

    <div id="permissionCounter" style="font-size:0.8rem; color:#5F7A6B; margin-bottom:0.875rem;">
        <span id="checkedCount">{{ count($rolePerms) }}</span> permission(s) sélectionnée(s)
    </div>

    @foreach($permissions as $moduleLabel => $modulePerms)
        @php $moduleKey = Str::slug($moduleLabel); @endphp
        <div style="border:1px solid #DDE5E1; border-radius:0.625rem; margin-bottom:0.625rem; overflow:hidden;">
            {{-- Module header --}}
            <div style="background:#FAFCFB; padding:0.625rem 1rem; border-bottom:1px solid #EEF2EF;
                        display:flex; align-items:center; gap:0.75rem; cursor:pointer;"
                 onclick="toggleModule('{{ $moduleKey }}')">
                <input type="checkbox" id="module_{{ $moduleKey }}"
                       style="width:14px; height:14px; accent-color:#276749; cursor:pointer; flex-shrink:0;"
                       onclick="event.stopPropagation(); toggleModuleCheckbox('{{ $moduleKey }}', this.checked)"
                       {{ count(array_filter($modulePerms, fn($p) => in_array($p->name, $rolePerms))) === count($modulePerms) && count($modulePerms) > 0 ? 'checked' : '' }}>
                <label for="module_{{ $moduleKey }}"
                       style="font-size:0.8125rem; font-weight:600; color:#1A2D22; cursor:pointer; flex:1; margin:0;"
                       onclick="event.stopPropagation(); document.getElementById('module_{{ $moduleKey }}').click()">
                    {{ $moduleLabel }}
                    <span style="font-weight:400; color:#9AB3A3; margin-left:0.25rem;">({{ count($modulePerms) }})</span>
                </label>
                <i class="fas fa-chevron-down module-chevron" id="chevron_{{ $moduleKey }}"
                   style="font-size:0.625rem; color:#9AB3A3; transition:transform 0.2s;"></i>
            </div>

            {{-- Permission checkboxes --}}
            <div id="module_body_{{ $moduleKey }}"
                 style="padding:0.75rem 1rem; display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:0.375rem;">
                @foreach($modulePerms as $permission)
                    <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;
                                  padding:0.25rem 0.375rem; border-radius:0.375rem;
                                  transition:background 0.15s;"
                           onmouseover="this.style.background='#F0F4F2'" onmouseout="this.style.background='transparent'">
                        <input type="checkbox" name="permissions[]"
                               value="{{ $permission->name }}"
                               class="perm-check module-{{ $moduleKey }}"
                               style="width:13px; height:13px; accent-color:#276749; flex-shrink:0;"
                               onchange="updateCounter()"
                               {{ in_array($permission->name, $rolePerms) ? 'checked' : '' }}>
                        <span style="font-size:0.8rem; color:#1A2D22; font-family:monospace;">{{ $permission->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@else
    <div style="background:#FEFCE8; border:1px solid #FDE68A; border-radius:0.625rem; padding:0.875rem 1rem; margin-top:1rem;">
        <p style="font-size:0.8125rem; color:#92400E; margin:0;">
            <i class="fas fa-lock" style="margin-right:0.375rem;"></i>
            Vous n'avez pas la permission d'assigner des permissions aux rôles.
        </p>
    </div>
@endif
