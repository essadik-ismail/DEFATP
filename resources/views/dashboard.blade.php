@extends('layouts.app')

@section('title', 'Tableau de Bord - DEFATP')

@section('content')
    <div>
        <!-- Dashboard Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
            <p class="text-gray-500 text-base">Plan, prioritize, and accomplish your tasks with ease.</p>
            
            <!-- Action Buttons -->
            <div class="flex items-center gap-3 mt-6">
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus"></i>
                    Add Project
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-white text-green-600 border border-green-600 rounded-lg font-medium hover:bg-green-50 transition-colors">
                    <i class="fas fa-upload"></i>
                    Import Data
                </button>
            </div>
        </div>


            <!-- Welcome Guide for New Users -->
            <x-welcome-guide :show="true" />
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .stat-card {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add animation classes
                const statCards = document.querySelectorAll('.stat-card');
                statCards.forEach(card => {
                    card.classList.add('animate-fade-in-up');
                });
                
                // Animate progress bars on scroll
                const observerOptions = {
                    threshold: 0.5,
                    rootMargin: '0px'
                };
                
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const progressBar = entry.target.querySelector('.bg-gradient-to-r');
                            if (progressBar) {
                                const width = progressBar.style.width;
                                progressBar.style.width = '0%';
                                setTimeout(() => {
                                    progressBar.style.width = width;
                                }, 100);
                            }
                        }
                    });
                }, observerOptions);
                
                document.querySelectorAll('.bg-white\\/90').forEach(card => {
                    observer.observe(card);
                });
            });
        </script>
    @endpush
@endsection