@props(['stats'])

<div class="stats-grid mb-8">
    @foreach($stats as $stat)
        <div class="stat-card {{ $stat['color'] ?? 'purple' }}">
            <div class="stat-content">
                <div class="stat-icon">
                    <i class="{{ $stat['icon'] }}"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ $stat['value'] }}</h3>
                    <p class="stat-label">{{ $stat['title'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.1),
            0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--purple-color), var(--accent-color));
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 
            0 16px 48px rgba(0, 0, 0, 0.15),
            0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card.purple::before { background: linear-gradient(90deg, #7c3aed, #8b5cf6); }
    .stat-card.blue::before { background: linear-gradient(90deg, #2563eb, #3b82f6); }
    .stat-card.orange::before { background: linear-gradient(90deg, #ea580c, #f97316); }
    .stat-card.green::before { background: linear-gradient(90deg, #059669, #10b981); }
    .stat-card.rose::before { background: linear-gradient(90deg, #e11d48, #f43f5e); }
    .stat-card.indigo::before { background: linear-gradient(90deg, #4338ca, #6366f1); }
    .stat-card.teal::before { background: linear-gradient(90deg, #0d9488, #14b8a6); }
    .stat-card.amber::before { background: linear-gradient(90deg, #d97706, #f59e0b); }

    .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-card.purple .stat-icon { background: linear-gradient(135deg, #7c3aed, #8b5cf6); }
    .stat-card.blue .stat-icon { background: linear-gradient(135deg, #2563eb, #3b82f6); }
    .stat-card.orange .stat-icon { background: linear-gradient(135deg, #ea580c, #f97316); }
    .stat-card.green .stat-icon { background: linear-gradient(135deg, #059669, #10b981); }
    .stat-card.rose .stat-icon { background: linear-gradient(135deg, #e11d48, #f43f5e); }
    .stat-card.indigo .stat-icon { background: linear-gradient(135deg, #4338ca, #6366f1); }
    .stat-card.teal .stat-icon { background: linear-gradient(135deg, #0d9488, #14b8a6); }
    .stat-card.amber .stat-icon { background: linear-gradient(135deg, #d97706, #f59e0b); }

    .stat-icon i {
        color: white;
        font-size: 1.25rem;
    }

    .stat-info {
        flex: 1;
        min-width: 0;
    }

    .stat-number {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
        line-height: 1;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0;
        font-weight: 500;
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
