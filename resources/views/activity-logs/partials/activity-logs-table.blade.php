<div class="table-responsive position-relative">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th>Utilisateur</th>
                <th>Action</th>
                <th>Description</th>
                <th>Modèle</th>
                <th>Adresse IP</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activityLogs as $log)
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="avatar-sm me-3">
                                @if($log->user && $log->user->image)
                                    <img src="{{ asset('storage/' . $log->user->image) }}" 
                                         alt="{{ $log->user->name }}" 
                                         class="rounded-circle" 
                                         width="32" height="32">
                                @else
                                    <div class="bg-primary text-white rounded-circle flex items-center justify-content-center" 
                                         style="width: 32px; height: 32px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-bold">{{ $log->user->name ?? 'Utilisateur supprimé' }}</div>
                                @if($log->user)
                                    <small class="text-muted">{{ $log->user->email }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $log->action_color }} rounded-pill">
                            <i class="{{ $log->action_icon }} mr-1"></i>
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td>
                        <div class="text-wrap" style="max-width: 300px;">
                            {{ $log->description }}
                        </div>
                    </td>
                    <td>
                        @if($log->model_type && $log->model_id)
                            <span class="badge bg-info rounded-pill">
                                <i class="fas fa-cube mr-1"></i>
                                {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <code class="small">{{ $log->ip_address ?? '-' }}</code>
                    </td>
                    <td>
                        <div class="flex flex-column">
                            <span class="fw-bold">{{ $log->formatted_date }}</span>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('activity-logs.show', $log) }}" 
                               class="tbl-action bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200" 
                               title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($log->user)
                                <a href="{{ route('activity-logs.user-activity', $log->user) }}" 
                                   class="tbl-action bg-cyan-50 hover:bg-cyan-100 text-cyan-700 border border-cyan-200" 
                                   title="Voir les activités de l'utilisateur">
                                    <i class="fas fa-user-clock"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p class="mb-0">Aucune activité trouvée</p>
                            <small>Les activités des utilisateurs apparaîtront ici</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Table scroll indicator -->
    <div class="table-scroll-indicator"></div>
</div>
