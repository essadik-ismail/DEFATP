@props(['steps' => [], 'currentStep' => 0])

<div class="form-wizard" x-data="{ currentStep: {{ $currentStep }} }">
    <!-- Progress Bar -->
    <div class="wizard-progress mb-6">
        @foreach($steps as $index => $step)
            <div class="wizard-step-indicator {{ $index === $currentStep ? 'active' : ($index < $currentStep ? 'completed' : 'pending') }}"
                 x-text="{{ $index + 1 }}">
            </div>
        @endforeach
    </div>

    <!-- Steps -->
    @foreach($steps as $index => $step)
        <div class="wizard-step {{ $index === $currentStep ? 'active' : '' }}"
             x-show="currentStep === {{ $index }}"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-4"
             x-transition:enter-end="opacity-100 transform translate-x-0">
            
            <div class="step-header mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ $step['title'] }}</h3>
                @if(isset($step['description']))
                    <p class="text-sm text-gray-600 mt-1">{{ $step['description'] }}</p>
                @endif
            </div>

            <div class="step-content">
                {{ $step['content'] }}
            </div>
        </div>
    @endforeach

    <!-- Navigation -->
    <div class="wizard-navigation pt-4 border-t border-gray-200">
        <button type="button" 
                class="btn btn-secondary"
                x-show="currentStep > 0"
                @click="currentStep--">
            <i class="fas fa-arrow-left me-2"></i>Précédent
        </button>
        
        <div class="flex-1"></div>
        
        <button type="button" 
                class="btn btn-primary"
                x-show="currentStep < {{ count($steps) - 1 }}"
                @click="currentStep++">
            Suivant<i class="fas fa-arrow-right ms-2"></i>
        </button>
        
        <button type="submit" 
                class="btn btn-success"
                x-show="currentStep === {{ count($steps) - 1 }}">
            <i class="fas fa-check me-2"></i>Terminer
        </button>
    </div>
</div>
