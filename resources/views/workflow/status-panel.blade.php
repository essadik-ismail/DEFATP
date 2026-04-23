{{--
  Workflow Status Panel — include on articles.show and any dossier page.
  Usage: @include('workflow.status-panel', ['article' => $article, 'steps' => $steps, 'alerts' => $alerts])
--}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    {{-- Header --}}
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-semibold text-gray-800">État du dossier</h3>
            <p class="text-xs text-gray-500 mt-0.5">
                Étape actuelle :
                <span class="font-medium text-blue-700">
                    {{ \App\Services\ArticleWorkflowService::LABELS[$article->workflow_state ?? 'DRAFT_ARTICLE'] ?? $article->workflow_state }}
                </span>
            </p>
        </div>
        @if(count($alerts ?? []) > 0)
            <a href="{{ route('workflow.alerts', $article) }}"
               class="flex items-center gap-1.5 text-xs font-medium text-red-600 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                {{ count($alerts) }} alerte(s) active(s)
            </a>
        @endif
    </div>

    {{-- Step timeline --}}
    <div class="px-5 py-4">
        <ol class="relative border-l border-gray-200 ml-3 space-y-4">
            @foreach ($steps as $state => $step)
                <li class="ml-6">
                    {{-- Icon --}}
                    <span class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full ring-4 ring-white
                        @if($step['status'] === 'done') bg-green-500
                        @elseif($step['status'] === 'active') bg-blue-600
                        @elseif($step['status'] === 'blocked') bg-red-400
                        @else bg-gray-300 @endif">
                        @if($step['status'] === 'done')
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @elseif($step['status'] === 'active')
                            <span class="w-2 h-2 bg-white rounded-full"></span>
                        @elseif($step['status'] === 'blocked')
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @else
                            <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        @endif
                    </span>

                    <div class="flex flex-wrap items-center gap-2">
                        <p class="text-xs font-medium
                            @if($step['status'] === 'done') text-green-700
                            @elseif($step['status'] === 'active') text-blue-800
                            @elseif($step['status'] === 'blocked') text-red-600
                            @else text-gray-500 @endif">
                            {{ $step['label'] }}
                        </p>
                        @if($step['status'] === 'active')
                            <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded font-medium">En cours</span>
                        @endif
                    </div>

                    @if($step['blocked_reason'])
                        <p class="text-xs text-red-500 mt-0.5">{{ $step['blocked_reason'] }}</p>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    {{-- Active alerts summary --}}
    @if(($alerts ?? [])->isNotEmpty())
        <div class="px-5 py-3 border-t border-gray-100 bg-red-50">
            <p class="text-xs font-semibold text-red-700 mb-2">Alertes actives</p>
            <ul class="space-y-1">
                @foreach($alerts->take(5) as $alert)
                    <li class="flex items-center gap-2 text-xs">
                        <span class="inline-block w-2 h-2 rounded-full flex-shrink-0
                            @if($alert->severity === 'critical') bg-red-500
                            @elseif($alert->severity === 'warning') bg-yellow-500
                            @else bg-blue-400 @endif"></span>
                        <span class="text-gray-700">{{ $alert->title }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
