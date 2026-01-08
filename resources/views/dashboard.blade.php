@extends('layouts.app')

@section('title', 'Tableau de Bord - DEFATP')

@section('content')
    <div class="min-h-screen py-8">
        <div class="container mx-auto px-4">

            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 bg-clip-text text-transparent mb-2">
                            Tableau de Bord
                        </h1>
                        <p class="text-gray-600 text-lg">Vue d'ensemble de votre gestion forestière</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="bg-white/80 backdrop-blur-xl rounded-xl px-4 py-2 border border-gray-200 shadow-sm">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas fa-calendar-alt text-green-600"></i>
                                <span>{{ now()->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
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