@props([
    'tourId' => 'default-tour',
    'steps' => [],
    'theme' => 'default', // default, forest, modern
    'autoStart' => false
])

<div id="custom-tour-{{ $tourId }}" class="custom-tour-system" style="display: none;">
    <!-- Tour Overlay -->
    <div class="tour-overlay"></div>
    
    <!-- Tour Spotlight -->
    <div class="tour-spotlight"></div>
    
    <!-- Tour Arrow Indicator -->
    <div class="tour-arrow"></div>
    
    <!-- Tour Popover -->
    <div class="tour-popover">
        <div class="tour-header">
            <div class="tour-progress">
                <div class="tour-progress-bar">
                    <div class="tour-progress-fill"></div>
                </div>
                <span class="tour-progress-text">1 / 5</span>
            </div>
            <button class="tour-close-btn" onclick="closeCustomTour('{{ $tourId }}')" title="Fermer le tour">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="tour-content">
            <h4 class="tour-title">Bienvenue dans DEFATP!</h4>
            <p class="tour-description">Découvrez les fonctionnalités de votre application de gestion forestière.</p>
            
            <!-- Button Explanations -->
            <div class="tour-button-explanations">
                <div class="explanation-item">
                    <i class="fas fa-info-circle text-primary"></i>
                    <span class="explanation-text">Cliquez sur les éléments pour en savoir plus</span>
                </div>
            </div>
        </div>
        
        <div class="tour-footer">
            <button class="tour-btn tour-btn-secondary tour-prev-btn" onclick="previousTourStep('{{ $tourId }}')" disabled>
                <i class="fas fa-chevron-left"></i> Précédent
            </button>
            <button class="tour-btn tour-btn-primary tour-next-btn" onclick="nextTourStep('{{ $tourId }}')">
                Suivant <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Tour Navigation Dots -->
        <div class="tour-dots"></div>
    </div>
    
    <!-- Tour Controls -->
    <div class="tour-controls">
        <button class="tour-control-btn tour-pause-btn" onclick="pauseTour('{{ $tourId }}')" title="Mettre en pause le tour">
            <i class="fas fa-pause"></i>
        </button>
        <button class="tour-control-btn tour-skip-btn" onclick="skipTour('{{ $tourId }}')" title="Passer le tour">
            <i class="fas fa-forward"></i>
        </button>
        <button class="tour-control-btn tour-help-btn" onclick="showTourHelp('{{ $tourId }}')" title="Aide et raccourcis clavier">
            <i class="fas fa-question-circle"></i>
        </button>
    </div>
</div>

@push('styles')
<style>
    /* Custom Tour System Styles */
    .custom-tour-system {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: 9999;
        pointer-events: none;
    }
    
    /* Tour Overlay */
    .tour-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(2px);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: auto;
    }
    
    .custom-tour-system.active .tour-overlay {
        opacity: 1;
    }
    
    /* Tour Spotlight */
    .tour-spotlight {
        position: absolute;
        border-radius: 8px;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.7);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        z-index: 1;
    }
    
    .tour-spotlight::before {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border: 2px solid #4CAF50;
        border-radius: 12px;
        animation: tourPulse 2s infinite;
    }
    
    /* Tour Arrow Indicator */
    .tour-arrow {
        position: absolute;
        width: 0;
        height: 0;
        border: 12px solid transparent;
        z-index: 3;
        opacity: 0;
        transition: all 0.4s ease;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
    }
    
    .tour-arrow.arrow-top {
        border-bottom-color: white;
        border-top: none;
        transform: translateY(-24px);
    }
    
    .tour-arrow.arrow-bottom {
        border-top-color: white;
        border-bottom: none;
        transform: translateY(24px);
    }
    
    .tour-arrow.arrow-left {
        border-right-color: white;
        border-left: none;
        transform: translateX(-24px);
    }
    
    .tour-arrow.arrow-right {
        border-left-color: white;
        border-right: none;
        transform: translateX(24px);
    }
    
    .custom-tour-system.active .tour-arrow {
        opacity: 1;
    }
    
    /* Tour Arrow Animation */
    @keyframes arrowBounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-8px);
        }
        60% {
            transform: translateY(-4px);
        }
    }
    
    .tour-arrow.arrow-top {
        animation: arrowBounce 2s infinite;
    }
    
    .tour-arrow.arrow-bottom {
        animation: arrowBounce 2s infinite reverse;
    }
    
    .tour-arrow.arrow-left {
        animation: arrowBounce 2s infinite;
    }
    
    .tour-arrow.arrow-right {
        animation: arrowBounce 2s infinite reverse;
    }
    
    @keyframes tourPulse {
        0%, 100% { 
            box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 0 0 10px rgba(76, 175, 80, 0.3);
            transform: scale(1.05);
        }
    }
    
    /* Tour Popover */
    .tour-popover {
        position: absolute;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        min-width: 300px;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
        z-index: 2;
    }
    
    .custom-tour-system.active .tour-popover {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    
    .tour-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 20px 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .tour-progress {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .tour-progress-bar {
        width: 60px;
        height: 4px;
        background: #e0e0e0;
        border-radius: 2px;
        overflow: hidden;
    }
    
    .tour-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50, #8BC34A);
        border-radius: 2px;
        transition: width 0.3s ease;
        width: 20%;
    }
    
    .tour-progress-text {
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }
    
    .tour-close-btn {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.2s ease;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .tour-close-btn:hover {
        background: #f5f5f5;
        color: #666;
    }
    
    .tour-content {
        padding: 20px;
    }
    
    .tour-title {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 18px;
        font-weight: 600;
    }
    
    .tour-description {
        margin: 0 0 15px 0;
        color: #666;
        line-height: 1.5;
        font-size: 14px;
    }
    
    /* Button Explanations */
    .tour-button-explanations {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.05) 0%, rgba(139, 195, 74, 0.05) 100%);
        border: 1px solid rgba(76, 175, 80, 0.1);
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 15px;
    }
    
    .explanation-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #4CAF50;
    }
    
    .explanation-item i {
        font-size: 14px;
    }
    
    .explanation-text {
        font-weight: 500;
    }
    
    .tour-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px 20px;
        gap: 10px;
    }
    
    .tour-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }
    
    .tour-btn-primary {
        background: linear-gradient(135deg, #4CAF50, #8BC34A);
        color: white;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }
    
    .tour-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }
    
    .tour-btn-secondary {
        background: #f5f5f5;
        color: #666;
        border: 1px solid #e0e0e0;
    }
    
    .tour-btn-secondary:hover:not(:disabled) {
        background: #e8e8e8;
        color: #333;
    }
    
    .tour-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }
    
    /* Tour Navigation Dots */
    .tour-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 0 20px 20px;
    }
    
    .tour-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #e0e0e0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .tour-dot.active {
        background: #4CAF50;
        transform: scale(1.2);
    }
    
    .tour-dot.completed {
        background: #8BC34A;
    }
    
    /* Tour Controls */
    .tour-controls {
        position: fixed;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 3;
        pointer-events: auto;
    }
    
    .tour-control-btn {
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 50%;
        background: white;
        color: #666;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .tour-control-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        color: #4CAF50;
    }
    
    /* Forest Theme */
    .custom-tour-system[data-theme="forest"] .tour-spotlight::before {
        border-color: #2E7D32;
    }
    
    .custom-tour-system[data-theme="forest"] .tour-progress-fill {
        background: linear-gradient(90deg, #2E7D32, #4CAF50);
    }
    
    .custom-tour-system[data-theme="forest"] .tour-btn-primary {
        background: linear-gradient(135deg, #2E7D32, #4CAF50);
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
    }
    
    /* Modern Theme */
    .custom-tour-system[data-theme="modern"] .tour-spotlight::before {
        border-color: #2196F3;
    }
    
    .custom-tour-system[data-theme="modern"] .tour-progress-fill {
        background: linear-gradient(90deg, #2196F3, #03DAC6);
    }
    
    .custom-tour-system[data-theme="modern"] .tour-btn-primary {
        background: linear-gradient(135deg, #2196F3, #03DAC6);
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .tour-popover {
            max-width: calc(100vw - 40px);
            min-width: auto;
            margin: 0 20px;
        }
        
        .tour-controls {
            right: 10px;
        }
        
        .tour-control-btn {
            width: 45px;
            height: 45px;
            font-size: 16px;
        }
    }
    
    /* Animation Classes */
    .tour-fade-in {
        animation: tourFadeIn 0.5s ease forwards;
    }
    
    .tour-slide-in {
        animation: tourSlideIn 0.5s ease forwards;
    }
    
    @keyframes tourFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes tourSlideIn {
        from { 
            opacity: 0;
            transform: translateY(30px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Custom Tour System Class - Define once globally
    if (typeof CustomTourSystem === 'undefined') {
        class CustomTourSystem {
            constructor(tourId, steps = [], options = {}) {
                this.tourId = tourId;
                this.steps = steps;
                this.currentStep = 0;
                this.isActive = false;
                this.isPaused = false;
                this.options = {
                    theme: 'default',
                    autoStart: false,
                    smoothScrolling: true,
                    highlightDuration: 2000,
                    ...options
                };
                
                this.elements = {
                    container: null,
                    overlay: null,
                    spotlight: null,
                    arrow: null,
                    popover: null,
                    progressFill: null,
                    progressText: null,
                    title: null,
                    description: null,
                    prevBtn: null,
                    nextBtn: null,
                    dots: null
                };
                
                this.init();
            }
            
            init() {
                this.elements.container = document.getElementById(`custom-tour-${this.tourId}`);
                if (!this.elements.container) {
                    console.error(`Tour container not found: custom-tour-${this.tourId}`);
                    return;
                }
                
                this.elements.overlay = this.elements.container.querySelector('.tour-overlay');
                this.elements.spotlight = this.elements.container.querySelector('.tour-spotlight');
                this.elements.arrow = this.elements.container.querySelector('.tour-arrow');
                this.elements.popover = this.elements.container.querySelector('.tour-popover');
                this.elements.progressFill = this.elements.container.querySelector('.tour-progress-fill');
                this.elements.progressText = this.elements.container.querySelector('.tour-progress-text');
                this.elements.title = this.elements.container.querySelector('.tour-title');
                this.elements.description = this.elements.container.querySelector('.tour-description');
                this.elements.prevBtn = this.elements.container.querySelector('.tour-prev-btn');
                this.elements.nextBtn = this.elements.container.querySelector('.tour-next-btn');
                this.elements.dots = this.elements.container.querySelector('.tour-dots');
                
                this.setupEventListeners();
                this.createNavigationDots();
                
                if (this.options.autoStart) {
                    this.start();
                }
            }
            
            setupEventListeners() {
                // Close tour when clicking overlay
                this.elements.overlay.addEventListener('click', () => {
                    this.close();
                });
                
                // Keyboard navigation
                document.addEventListener('keydown', (e) => {
                    if (!this.isActive) return;
                    
                    switch(e.key) {
                        case 'Escape':
                            this.close();
                            break;
                        case 'ArrowLeft':
                            this.previous();
                            break;
                        case 'ArrowRight':
                            this.next();
                            break;
                    }
                });
            }
            
            createNavigationDots() {
                this.elements.dots.innerHTML = '';
                this.steps.forEach((_, index) => {
                    const dot = document.createElement('div');
                    dot.className = 'tour-dot';
                    dot.onclick = () => this.goToStep(index);
                    this.elements.dots.appendChild(dot);
                });
            }
            
            start() {
                if (this.isActive) return;
                
                this.isActive = true;
                this.currentStep = 0;
                this.elements.container.style.display = 'block';
                
                // Add active class with delay for smooth animation
                setTimeout(() => {
                    this.elements.container.classList.add('active');
                }, 10);
                
                this.showStep(0);
            }
            
            showStep(stepIndex) {
                if (stepIndex < 0 || stepIndex >= this.steps.length) {
                    this.close();
                    return;
                }
                
                const step = this.steps[stepIndex];
                this.currentStep = stepIndex;
                
                // Update content
                this.elements.title.textContent = step.title || 'Étape ' + (stepIndex + 1);
                this.elements.description.textContent = step.description || '';
                
                // Update progress
                const progress = ((stepIndex + 1) / this.steps.length) * 100;
                this.elements.progressFill.style.width = progress + '%';
                this.elements.progressText.textContent = `${stepIndex + 1} / ${this.steps.length}`;
                
                // Update buttons
                this.elements.prevBtn.disabled = stepIndex === 0;
                this.elements.nextBtn.textContent = stepIndex === this.steps.length - 1 ? 'Terminer' : 'Suivant';
                
                // Update dots
                this.updateDots(stepIndex);
                
                // Position spotlight and popover
                this.positionElements(step);
                
                // Smooth scroll to element if needed
                if (this.options.smoothScrolling && step.element) {
                    this.scrollToElement(step.element);
                }
            }
            
            positionElements(step) {
                if (!step.element) {
                    this.hideSpotlight();
                    this.centerPopover();
                    return;
                }
                
                const element = typeof step.element === 'string' 
                    ? document.querySelector(step.element) 
                    : step.element;
                
                if (!element) {
                    console.warn('Tour element not found:', step.element);
                    this.hideSpotlight();
                    this.centerPopover();
                    return;
                }
                
                // Position spotlight
                this.showSpotlight(element);
                
                // Position popover
                this.positionPopover(element, step.popoverPosition || 'auto');
                
                // Position arrow
                this.positionArrow(element, step.popoverPosition || 'auto');
                
                // Update button explanations based on step
                this.updateButtonExplanations(step);
            }
            
            showSpotlight(element) {
                const rect = element.getBoundingClientRect();
                const padding = 10;
                
                this.elements.spotlight.style.left = (rect.left - padding) + 'px';
                this.elements.spotlight.style.top = (rect.top - padding) + 'px';
                this.elements.spotlight.style.width = (rect.width + padding * 2) + 'px';
                this.elements.spotlight.style.height = (rect.height + padding * 2) + 'px';
                this.elements.spotlight.style.display = 'block';
            }
            
            hideSpotlight() {
                this.elements.spotlight.style.display = 'none';
                if (this.elements.arrow) {
                    this.elements.arrow.style.display = 'none';
                }
            }
            
            positionPopover(element, position) {
                const rect = element.getBoundingClientRect();
                const popoverRect = this.elements.popover.getBoundingClientRect();
                
                let left, top;
                
                switch(position) {
                    case 'top':
                        left = rect.left + (rect.width / 2) - (popoverRect.width / 2);
                        top = rect.top - popoverRect.height - 20;
                        break;
                    case 'bottom':
                        left = rect.left + (rect.width / 2) - (popoverRect.width / 2);
                        top = rect.bottom + 20;
                        break;
                    case 'left':
                        left = rect.left - popoverRect.width - 20;
                        top = rect.top + (rect.height / 2) - (popoverRect.height / 2);
                        break;
                    case 'right':
                        left = rect.right + 20;
                        top = rect.top + (rect.height / 2) - (popoverRect.height / 2);
                        break;
                    default: // auto
                        // Try to position intelligently
                        if (rect.top > popoverRect.height + 40) {
                            // Position above
                            left = rect.left + (rect.width / 2) - (popoverRect.width / 2);
                            top = rect.top - popoverRect.height - 20;
                        } else if (rect.bottom + popoverRect.height + 40 < window.innerHeight) {
                            // Position below
                            left = rect.left + (rect.width / 2) - (popoverRect.width / 2);
                            top = rect.bottom + 20;
                        } else if (rect.left > popoverRect.width + 40) {
                            // Position to the left
                            left = rect.left - popoverRect.width - 20;
                            top = rect.top + (rect.height / 2) - (popoverRect.height / 2);
                        } else {
                            // Position to the right
                            left = rect.right + 20;
                            top = rect.top + (rect.height / 2) - (popoverRect.height / 2);
                        }
                        break;
                }
                
                // Ensure popover stays within viewport
                left = Math.max(20, Math.min(left, window.innerWidth - popoverRect.width - 20));
                top = Math.max(20, Math.min(top, window.innerHeight - popoverRect.height - 20));
                
                this.elements.popover.style.left = left + 'px';
                this.elements.popover.style.top = top + 'px';
            }
            
            positionArrow(element, position) {
                if (!this.elements.arrow || !element) {
                    return;
                }
                
                const rect = element.getBoundingClientRect();
                const arrow = this.elements.arrow;
                
                // Remove all arrow classes
                arrow.classList.remove('arrow-top', 'arrow-bottom', 'arrow-left', 'arrow-right');
                
                let arrowLeft, arrowTop;
                
                switch(position) {
                    case 'top':
                        arrow.classList.add('arrow-bottom');
                        arrowLeft = rect.left + (rect.width / 2) - 12;
                        arrowTop = rect.top - 24;
                        break;
                    case 'bottom':
                        arrow.classList.add('arrow-top');
                        arrowLeft = rect.left + (rect.width / 2) - 12;
                        arrowTop = rect.bottom + 24;
                        break;
                    case 'left':
                        arrow.classList.add('arrow-right');
                        arrowLeft = rect.left - 24;
                        arrowTop = rect.top + (rect.height / 2) - 12;
                        break;
                    case 'right':
                        arrow.classList.add('arrow-left');
                        arrowLeft = rect.right + 24;
                        arrowTop = rect.top + (rect.height / 2) - 12;
                        break;
                    default: // auto
                        // Determine best position for arrow
                        const popoverRect = this.elements.popover.getBoundingClientRect();
                        if (rect.top > popoverRect.height + 40) {
                            // Position above
                            arrow.classList.add('arrow-bottom');
                            arrowLeft = rect.left + (rect.width / 2) - 12;
                            arrowTop = rect.top - 24;
                        } else if (rect.bottom + popoverRect.height + 40 < window.innerHeight) {
                            // Position below
                            arrow.classList.add('arrow-top');
                            arrowLeft = rect.left + (rect.width / 2) - 12;
                            arrowTop = rect.bottom + 24;
                        } else if (rect.left > popoverRect.width + 40) {
                            // Position to the left
                            arrow.classList.add('arrow-right');
                            arrowLeft = rect.left - 24;
                            arrowTop = rect.top + (rect.height / 2) - 12;
                        } else {
                            // Position to the right
                            arrow.classList.add('arrow-left');
                            arrowLeft = rect.right + 24;
                            arrowTop = rect.top + (rect.height / 2) - 12;
                        }
                        break;
                }
                
                arrow.style.left = arrowLeft + 'px';
                arrow.style.top = arrowTop + 'px';
                arrow.style.display = 'block';
            }
            
            updateButtonExplanations(step) {
                const explanationsContainer = this.elements.container.querySelector('.tour-button-explanations');
                if (!explanationsContainer) return;
                
                // Clear existing explanations
                explanationsContainer.innerHTML = '';
                
                // Add step-specific explanations
                if (step.explanations && Array.isArray(step.explanations)) {
                    step.explanations.forEach(explanation => {
                        const explanationItem = document.createElement('div');
                        explanationItem.className = 'explanation-item';
                        explanationItem.innerHTML = `
                            <i class="fas ${explanation.icon || 'fa-info-circle'} text-primary"></i>
                            <span class="explanation-text">${explanation.text}</span>
                        `;
                        explanationsContainer.appendChild(explanationItem);
                    });
                } else {
                    // Default explanation
                    const defaultExplanation = document.createElement('div');
                    defaultExplanation.className = 'explanation-item';
                    defaultExplanation.innerHTML = `
                        <i class="fas fa-info-circle text-primary"></i>
                        <span class="explanation-text">Cliquez sur les éléments pour en savoir plus</span>
                    `;
                    explanationsContainer.appendChild(defaultExplanation);
                }
            }
            
            centerPopover() {
                const popoverRect = this.elements.popover.getBoundingClientRect();
                const left = (window.innerWidth - popoverRect.width) / 2;
                const top = (window.innerHeight - popoverRect.height) / 2;
                
                this.elements.popover.style.left = left + 'px';
                this.elements.popover.style.top = top + 'px';
            }
            
            updateDots(activeIndex) {
                const dots = this.elements.dots.querySelectorAll('.tour-dot');
                dots.forEach((dot, index) => {
                    dot.classList.remove('active', 'completed');
                    if (index === activeIndex) {
                        dot.classList.add('active');
                    } else if (index < activeIndex) {
                        dot.classList.add('completed');
                    }
                });
            }
            
            scrollToElement(element) {
                if (typeof element === 'string') {
                    element = document.querySelector(element);
                }
                
                if (element) {
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                        inline: 'center'
                    });
                }
            }
            
            next() {
                if (this.currentStep < this.steps.length - 1) {
                    this.showStep(this.currentStep + 1);
                } else {
                    this.complete();
                }
            }
            
            previous() {
                if (this.currentStep > 0) {
                    this.showStep(this.currentStep - 1);
                }
            }
            
            goToStep(stepIndex) {
                this.showStep(stepIndex);
            }
            
            pause() {
                this.isPaused = !this.isPaused;
                this.elements.container.classList.toggle('paused', this.isPaused);
            }
            
            skip() {
                this.complete();
            }
            
            complete() {
                this.close();
                // Trigger completion event
                const event = new CustomEvent('tourCompleted', {
                    detail: { tourId: this.tourId, steps: this.steps.length }
                });
                document.dispatchEvent(event);
            }
            
            close() {
                this.isActive = false;
                this.elements.container.classList.remove('active');
                
                setTimeout(() => {
                    this.elements.container.style.display = 'none';
                }, 500);
            }
        }
    }
    
    // Global tour instances
    window.customTours = window.customTours || {};
    
    // Tour management functions
    window.startCustomTour = function(tourId) {
        console.log('Starting tour:', tourId);
        if (window.customTours[tourId]) {
            window.customTours[tourId].start();
        } else {
            console.error('Tour not found:', tourId);
        }
    };
    
    window.closeCustomTour = function(tourId) {
        if (window.customTours[tourId]) {
            window.customTours[tourId].close();
        }
    };
    
    window.nextTourStep = function(tourId) {
        if (window.customTours[tourId]) {
            window.customTours[tourId].next();
        }
    };
    
    window.previousTourStep = function(tourId) {
        if (window.customTours[tourId]) {
            window.customTours[tourId].previous();
        }
    };
    
    window.pauseTour = function(tourId) {
        if (window.customTours[tourId]) {
            window.customTours[tourId].pause();
        }
    };
    
    window.skipTour = function(tourId) {
        if (window.customTours[tourId]) {
            window.customTours[tourId].skip();
        }
    };
    
    window.showTourHelp = function(tourId) {
        // Show help modal or tooltip
        alert('Utilisez les flèches du clavier pour naviguer ou cliquez sur les boutons.');
    };
    
    // Initialize tour when component is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing tour: {{ $tourId }}');
        const tourContainer = document.getElementById('custom-tour-{{ $tourId }}');
        if (tourContainer) {
            console.log('Tour container found, creating tour instance');
            const tour = new CustomTourSystem('{{ $tourId }}', @json($steps), {
                theme: '{{ $theme }}',
                autoStart: {{ $autoStart ? 'true' : 'false' }}
            });
            window.customTours['{{ $tourId }}'] = tour;
            console.log('Tour created successfully:', tour);
        } else {
            console.error('Tour container not found: custom-tour-{{ $tourId }}');
        }
    });
</script>
@endpush
