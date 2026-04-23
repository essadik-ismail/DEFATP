@if($activityLogs->hasPages())
    <div class="flex justify-between items-center mt-4">
        <div class="pagination-info">
            <p class="text-muted mb-0">
                Affichage de {{ $activityLogs->firstItem() ?? 0 }} à {{ $activityLogs->lastItem() ?? 0 }} 
                sur {{ $activityLogs->total() }} activités
            </p>
        </div>
        
        <nav aria-label="Navigation des pages d'activités">
            {{ $activityLogs->appends(request()->query())->links('pagination::bootstrap-5') }}
        </nav>
    </div>
@endif
