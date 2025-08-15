@props([
    'tourId' => 'functionality-tour',
    'functionalities' => [],
    'theme' => 'default', // default, forest, modern
    'autoStart' => false,
    'showHelpButton' => true
])

<div id="functionality-tour-{{ $tourId }}" class="functionality-tour-system" style="display: none;">
    <!-- Tour Overlay -->
    <div class="tour-overlay"></div>
    
    <!-- Functionality Spotlight -->
    <div class="functionality-spotlight"></div>
    
    <!-- Functionality Arrow -->
    <div class="functionality-arrow"></div>
    
    <!-- Functionality Popup -->
    <div class="functionality-popup">
        <div class="popup-header">
            <div class="popup-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div class="popup-title-section">
                <h4 class="popup-title">Fonctionnalité</h4>
                <div class="popup-progress">
                    <span class="progress-text">1 / 5</span>
                    <div class="progress-dots">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>
            </div>
            <button class="popup-close-btn" onclick="closeFunctionalityTour('{{ $tourId }}')" title="Fermer le tour">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="popup-content">
            <div class="functionality-description">
                <p class="description-text">Description de la fonctionnalité</p>
            </div>
            
            <!-- Functionality Details -->
            <div class="functionality-details">
                <div class="detail-item">
                    <i class="fas fa-check-circle text-success"></i>
                    <span class="detail-text">Détail de la fonctionnalité</span>
                </div>
            </div>
            
            <!-- Usage Instructions -->
            <div class="usage-instructions">
                <h6 class="instructions-title">
                    <i class="fas fa-play-circle me-2"></i>Comment utiliser
                </h6>
                <div class="instructions-list">
                    <div class="instruction-step">
                        <span class="step-number">1</span>
                        <span class="step-text">Première étape d'utilisation</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="popup-footer">
            <button class="popup-btn popup-btn-secondary popup-prev-btn" onclick="previousFunctionalityStep('{{ $tourId }}')" disabled>
                <i class="fas fa-chevron-left"></i> Précédent
            </button>
            <button class="popup-btn popup-btn-primary popup-next-btn" onclick="nextFunctionalityStep('{{ $tourId }}')">
                Suivant <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
    
    <!-- Tour Controls -->
    <div class="tour-controls">
        <button class="tour-control-btn tour-pause-btn" onclick="pauseFunctionalityTour('{{ $tourId }}')" title="Mettre en pause">
            <i class="fas fa-pause"></i>
        </button>
        <button class="tour-control-btn tour-skip-btn" onclick="skipFunctionalityTour('{{ $tourId }}')" title="Passer le tour">
            <i class="fas fa-forward"></i>
        </button>
        <button class="tour-control-btn tour-help-btn" onclick="showFunctionalityHelp('{{ $tourId }}')" title="Aide et raccourcis">
            <i class="fas fa-question-circle"></i>
        </button>
    </div>
    
    <!-- Help Button (if enabled) -->
    @if($showHelpButton)
    <div class="tour-help-button">
        <button class="help-btn" onclick="startFunctionalityTour('{{ $tourId }}')" title="Démarrer le tour des fonctionnalités">
            <i class="fas fa-question-circle"></i>
            <span class="help-text">Aide</span>
        </button>
    </div>
    @endif
</div>

@push('styles')
<style>
    /* Functionality Tour System Styles */
    .functionality-tour-system {
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
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(3px);
        opacity: 0;
        transition: opacity 0.6s ease;
        pointer-events: auto;
    }
    
    .functionality-tour-system.active .tour-overlay {
        opacity: 1;
    }
    
    /* Functionality Spotlight */
    .functionality-spotlight {
        position: absolute;
        border-radius: 12px;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.8);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        z-index: 1;
    }
    
    .functionality-spotlight::before {
        content: '';
        position: absolute;
        top: -6px;
        left: -6px;
        right: -6px;
        bottom: -6px;
        border: 3px solid #4CAF50;
        border-radius: 18px;
        animation: functionalityPulse 3s infinite;
    }
    
    .functionality-spotlight::after {
        content: '';
        position: absolute;
        top: -12px;
        left: -12px;
        right: -12px;
        bottom: -12px;
        border: 1px solid rgba(76, 175, 80, 0.3);
        border-radius: 24px;
        animation: functionalityGlow 2s infinite alternate;
    }
    
    @keyframes functionalityPulse {
        0%, 100% { 
            box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.8);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 0 0 15px rgba(76, 175, 80, 0.2);
            transform: scale(1.02);
        }
    }
    
    @keyframes functionalityGlow {
        0% { 
            border-color: rgba(76, 175, 80, 0.3);
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.1);
        }
        100% { 
            border-color: rgba(76, 175, 80, 0.6);
            box-shadow: 0 0 30px rgba(76, 175, 80, 0.3);
        }
    }
    
    /* Functionality Arrow */
    .functionality-arrow {
        position: absolute;
        width: 0;
        height: 0;
        border: 16px solid transparent;
        z-index: 3;
        opacity: 0;
        transition: all 0.5s ease;
        filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.4));
    }
    
    .functionality-arrow.arrow-top {
        border-bottom-color: white;
        border-top: none;
        transform: translateY(-32px);
    }
    
    .functionality-arrow.arrow-bottom {
        border-top-color: white;
        border-bottom: none;
        transform: translateY(32px);
    }
    
    .functionality-arrow.arrow-left {
        border-right-color: white;
        border-left: none;
        transform: translateX(-32px);
    }
    
    .functionality-arrow.arrow-right {
        border-left-color: white;
        border-right: none;
        transform: translateX(32px);
    }
    
    .functionality-tour-system.active .functionality-arrow {
        opacity: 1;
    }
    
    /* Arrow Animations */
    @keyframes arrowFloat {
        0%, 100% { 
            transform: translateY(0) scale(1);
        }
        50% { 
            transform: translateY(-8px) scale(1.1);
        }
    }
    
    .functionality-arrow.arrow-top {
        animation: arrowFloat 2s infinite;
    }
    
    .functionality-arrow.arrow-bottom {
        animation: arrowFloat 2s infinite reverse;
    }
    
    .functionality-arrow.arrow-left {
        animation: arrowFloat 2s infinite;
    }
    
    .functionality-arrow.arrow-right {
        animation: arrowFloat 2s infinite reverse;
    }
    
    /* Functionality Popup */
    .functionality-popup {
        position: absolute;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(248, 250, 252, 0.98) 100%);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(76, 175, 80, 0.2);
        border-radius: 20px;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
        max-width: 450px;
        min-width: 350px;
        opacity: 0;
        transform: translateY(30px) scale(0.9);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
        z-index: 2;
    }
    
    .functionality-tour-system.active .functionality-popup {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    
    .popup-header {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 20px 15px;
        border-bottom: 1px solid rgba(76, 175, 80, 0.1);
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.05) 0%, rgba(139, 195, 74, 0.05) 100%);
        border-radius: 20px 20px 0 0;
    }
    
    .popup-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #4CAF50, #8BC34A);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }
    
    .popup-title-section {
        flex: 1;
    }
    
    .popup-title {
        margin: 0 0 5px 0;
        color: #2E7D32;
        font-size: 18px;
        font-weight: 700;
    }
    
    .popup-progress {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .progress-text {
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }
    
    .progress-dots {
        display: flex;
        gap: 6px;
    }
    
    .progress-dots .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #e0e0e0;
        transition: all 0.3s ease;
    }
    
    .progress-dots .dot.active {
        background: #4CAF50;
        transform: scale(1.2);
    }
    
    .popup-close-btn {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.2s ease;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .popup-close-btn:hover {
        background: rgba(76, 175, 80, 0.1);
        color: #4CAF50;
    }
    
    .popup-content {
        padding: 20px;
    }
    
    .functionality-description {
        margin-bottom: 20px;
    }
    
    .description-text {
        margin: 0;
        color: #333;
        line-height: 1.6;
        font-size: 15px;
        font-weight: 500;
    }
    
    /* Functionality Details */
    .functionality-details {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.05) 0%, rgba(139, 195, 74, 0.05) 100%);
        border: 1px solid rgba(76, 175, 80, 0.1);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #2E7D32;
        font-weight: 500;
    }
    
    .detail-item i {
        font-size: 16px;
    }
    
    /* Usage Instructions */
    .usage-instructions {
        background: rgba(33, 150, 243, 0.05);
        border: 1px solid rgba(33, 150, 243, 0.1);
        border-radius: 12px;
        padding: 15px;
    }
    
    .instructions-title {
        margin: 0 0 15px 0;
        color: #1976D2;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .instructions-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .instruction-step {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .step-number {
        width: 24px;
        height: 24px;
        background: #1976D2;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }
    
    .step-text {
        color: #1976D2;
        font-size: 13px;
        font-weight: 500;
    }
    
    .popup-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px 20px;
        gap: 15px;
        border-top: 1px solid rgba(76, 175, 80, 0.1);
        background: rgba(76, 175, 80, 0.02);
        border-radius: 0 0 20px 20px;
    }
    
    .popup-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        min-width: 120px;
        justify-content: center;
    }
    
    .popup-btn-primary {
        background: linear-gradient(135deg, #4CAF50, #8BC34A);
        color: white;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }
    
    .popup-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }
    
    .popup-btn-secondary {
        background: #f5f5f5;
        color: #666;
        border: 1px solid #e0e0e0;
    }
    
    .popup-btn-secondary:hover:not(:disabled) {
        background: #e8e8e8;
        color: #333;
        border-color: #4CAF50;
    }
    
    .popup-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }
    
    /* Tour Controls */
    .tour-controls {
        position: fixed;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        gap: 12px;
        z-index: 3;
        pointer-events: auto;
    }
    
    .tour-control-btn {
        width: 55px;
        height: 55px;
        border: none;
        border-radius: 50%;
        background: white;
        color: #666;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .tour-control-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        color: #4CAF50;
    }
    
    /* Help Button */
    .tour-help-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        pointer-events: auto;
    }
    
    .help-btn {
        background: linear-gradient(135deg, #4CAF50, #8BC34A);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 15px 25px;
        cursor: pointer;
        box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 14px;
    }
    
    .help-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(76, 175, 80, 0.4);
    }
    
    .help-btn i {
        font-size: 18px;
    }
    
    .help-text {
        display: none;
    }
    
    .help-btn:hover .help-text {
        display: inline;
    }
    
    /* Forest Theme */
    .functionality-tour-system[data-theme="forest"] .functionality-spotlight::before {
        border-color: #2E7D32;
    }
    
    .functionality-tour-system[data-theme="forest"] .functionality-spotlight::after {
        border-color: rgba(46, 125, 50, 0.3);
    }
    
    .functionality-tour-system[data-theme="forest"] .popup-icon {
        background: linear-gradient(135deg, #2E7D32, #4CAF50);
    }
    
    .functionality-tour-system[data-theme="forest"] .popup-btn-primary {
        background: linear-gradient(135deg, #2E7D32, #4CAF50);
    }
    
    /* Modern Theme */
    .functionality-tour-system[data-theme="modern"] .functionality-spotlight::before {
        border-color: #2196F3;
    }
    
    .functionality-tour-system[data-theme="modern"] .functionality-spotlight::after {
        border-color: rgba(33, 150, 243, 0.3);
    }
    
    .functionality-tour-system[data-theme="modern"] .popup-icon {
        background: linear-gradient(135deg, #2196F3, #03DAC6);
    }
    
    .functionality-tour-system[data-theme="modern"] .popup-btn-primary {
        background: linear-gradient(135deg, #2196F3, #03DAC6);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .functionality-popup {
            max-width: calc(100vw - 40px);
            min-width: auto;
            margin: 0 20px;
        }
        
        .tour-controls {
            right: 10px;
        }
        
        .tour-control-btn {
            width: 50px;
            height: 50px;
            font-size: 18px;
        }
        
        .help-btn {
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Functionality Tour System Class - Define once globally
    if (typeof FunctionalityTourSystem === 'undefined') {
        class FunctionalityTourSystem {
            constructor(tourId, functionalities = [], options = {}) {
                this.tourId = tourId;
                this.functionalities = functionalities;
                this.currentStep = 0;
                this.isActive = false;
                this.isPaused = false;
                this.options = {
                    theme: 'default',
                    autoStart: false,
                    smoothScrolling: true,
                    ...options
                };
                
                this.elements = {
                    container: null,
                    overlay: null,
                    spotlight: null,
                    arrow: null,
                    popup: null,
                    title: null,
                    description: null,
                    details: null,
                    instructions: null,
                    prevBtn: null,
                    nextBtn: null,
                    progressText: null,
                    progressDots: null
                };
                
                this.init();
            }
            
            init() {
                this.elements.container = document.getElementById(`functionality-tour-${this.tourId}`);
                if (!this.elements.container) {
                    console.error(`Functionality tour container not found: functionality-tour-${this.tourId}`);
                    return;
                }
                
                this.elements.overlay = this.elements.container.querySelector('.tour-overlay');
                this.elements.spotlight = this.elements.container.querySelector('.functionality-spotlight');
                this.elements.arrow = this.elements.container.querySelector('.functionality-arrow');
                this.elements.popup = this.elements.container.querySelector('.functionality-popup');
                this.elements.title = this.elements.container.querySelector('.popup-title');
                this.elements.description = this.elements.container.querySelector('.description-text');
                this.elements.details = this.elements.container.querySelector('.functionality-details');
                this.elements.instructions = this.elements.container.querySelector('.instructions-list');
                this.elements.prevBtn = this.elements.container.querySelector('.popup-prev-btn');
                this.elements.nextBtn = this.elements.container.querySelector('.popup-next-btn');
                this.elements.progressText = this.elements.container.querySelector('.progress-text');
                this.elements.progressDots = this.elements.container.querySelector('.progress-dots');
                
                this.setupEventListeners();
                this.createProgressDots();
                
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
            
            createProgressDots() {
                if (!this.elements.progressDots) return;
                
                this.elements.progressDots.innerHTML = '';
                this.functionalities.forEach((_, index) => {
                    const dot = document.createElement('span');
                    dot.className = 'dot';
                    this.elements.progressDots.appendChild(dot);
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
                if (stepIndex < 0 || stepIndex >= this.functionalities.length) {
                    this.close();
                    return;
                }
                
                const functionality = this.functionalities[stepIndex];
                this.currentStep = stepIndex;
                
                // Update content
                this.elements.title.textContent = functionality.title || 'Fonctionnalité';
                this.elements.description.textContent = functionality.description || '';
                
                // Update progress
                this.elements.progressText.textContent = `${stepIndex + 1} / ${this.functionalities.length}`;
                this.updateProgressDots(stepIndex);
                
                // Update buttons
                this.elements.prevBtn.disabled = stepIndex === 0;
                this.elements.nextBtn.textContent = stepIndex === this.functionalities.length - 1 ? 'Terminer' : 'Suivant';
                
                // Update details
                this.updateDetails(functionality.details || []);
                
                // Update instructions
                this.updateInstructions(functionality.instructions || []);
                
                // Position spotlight, arrow and popup
                this.positionElements(functionality);
                
                // Smooth scroll to element if needed
                if (this.options.smoothScrolling && functionality.element) {
                    this.scrollToElement(functionality.element);
                }
            }
            
            updateProgressDots(activeIndex) {
                if (!this.elements.progressDots) return;
                
                const dots = this.elements.progressDots.querySelectorAll('.dot');
                dots.forEach((dot, index) => {
                    dot.classList.remove('active');
                    if (index === activeIndex) {
                        dot.classList.add('active');
                    }
                });
            }
            
            updateDetails(details) {
                if (!this.elements.details) return;
                
                this.elements.details.innerHTML = '';
                details.forEach(detail => {
                    const detailItem = document.createElement('div');
                    detailItem.className = 'detail-item';
                    detailItem.innerHTML = `
                        <i class="fas ${detail.icon || 'fa-check-circle'} text-success"></i>
                        <span class="detail-text">${detail.text}</span>
                    `;
                    this.elements.details.appendChild(detailItem);
                });
            }
            
            updateInstructions(instructions) {
                if (!this.elements.instructions) return;
                
                this.elements.instructions.innerHTML = '';
                instructions.forEach((instruction, index) => {
                    const instructionStep = document.createElement('div');
                    instructionStep.className = 'instruction-step';
                    instructionStep.innerHTML = `
                        <span class="step-number">${index + 1}</span>
                        <span class="step-text">${instruction}</span>
                    `;
                    this.elements.instructions.appendChild(instructionStep);
                });
            }
            
            positionElements(functionality) {
                if (!functionality.element) {
                    this.hideSpotlight();
                    this.centerPopup();
                    return;
                }
                
                const element = typeof functionality.element === 'string' 
                    ? document.querySelector(functionality.element) 
                    : functionality.element;
                
                if (!element) {
                    console.warn('Functionality element not found:', functionality.element);
                    this.hideSpotlight();
                    this.centerPopup();
                    return;
                }
                
                // Position spotlight
                this.showSpotlight(element);
                
                // Position popup
                this.positionPopup(element, functionality.popupPosition || 'auto');
                
                // Position arrow
                this.positionArrow(element, functionality.popupPosition || 'auto');
            }
            
            showSpotlight(element) {
                const rect = element.getBoundingClientRect();
                const padding = 15;
                
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
            
            positionPopup(element, position) {
                const rect = element.getBoundingClientRect();
                const popupRect = this.elements.popup.getBoundingClientRect();
                
                let left, top;
                
                switch(position) {
                    case 'top':
                        left = rect.left + (rect.width / 2) - (popupRect.width / 2);
                        top = rect.top - popupRect.height - 30;
                        break;
                    case 'bottom':
                        left = rect.left + (rect.width / 2) - (popupRect.width / 2);
                        top = rect.bottom + 30;
                        break;
                    case 'left':
                        left = rect.left - popupRect.width - 30;
                        top = rect.top + (rect.height / 2) - (popupRect.height / 2);
                        break;
                    case 'right':
                        left = rect.right + 30;
                        top = rect.top + (rect.height / 2) - (popupRect.height / 2);
                        break;
                    default: // auto
                        // Try to position intelligently
                        if (rect.top > popupRect.height + 60) {
                            // Position above
                            left = rect.left + (rect.width / 2) - (popupRect.width / 2);
                            top = rect.top - popupRect.height - 30;
                        } else if (rect.bottom + popupRect.height + 60 < window.innerHeight) {
                            // Position below
                            left = rect.left + (rect.width / 2) - (popupRect.width / 2);
                            top = rect.bottom + 30;
                        } else if (rect.left > popupRect.width + 60) {
                            // Position to the left
                            left = rect.left - popupRect.width - 30;
                            top = rect.top + (rect.height / 2) - (popupRect.height / 2);
                        } else {
                            // Position to the right
                            left = rect.right + 30;
                            top = rect.top + (rect.height / 2) - (popupRect.height / 2);
                        }
                        break;
                }
                
                // Ensure popup stays within viewport
                left = Math.max(20, Math.min(left, window.innerWidth - popupRect.width - 20));
                top = Math.max(20, Math.min(top, window.innerHeight - popupRect.height - 20));
                
                this.elements.popup.style.left = left + 'px';
                this.elements.popup.style.top = top + 'px';
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
                        arrowLeft = rect.left + (rect.width / 2) - 16;
                        arrowTop = rect.top - 32;
                        break;
                    case 'bottom':
                        arrow.classList.add('arrow-top');
                        arrowLeft = rect.left + (rect.width / 2) - 16;
                        arrowTop = rect.bottom + 32;
                        break;
                    case 'left':
                        arrow.classList.add('arrow-right');
                        arrowLeft = rect.left - 32;
                        arrowTop = rect.top + (rect.height / 2) - 16;
                        break;
                    case 'right':
                        arrow.classList.add('arrow-left');
                        arrowLeft = rect.right + 32;
                        arrowTop = rect.top + (rect.height / 2) - 16;
                        break;
                    default: // auto
                        // Determine best position for arrow
                        const popupRect = this.elements.popup.getBoundingClientRect();
                        if (rect.top > popupRect.height + 60) {
                            // Position above
                            arrow.classList.add('arrow-bottom');
                            arrowLeft = rect.left + (rect.width / 2) - 16;
                            arrowTop = rect.top - 32;
                        } else if (rect.bottom + popupRect.height + 60 < window.innerHeight) {
                            // Position below
                            arrow.classList.add('arrow-top');
                            arrowLeft = rect.left + (rect.width / 2) - 16;
                            arrowTop = rect.bottom + 32;
                        } else if (rect.left > popupRect.width + 60) {
                            // Position to the left
                            arrow.classList.add('arrow-right');
                            arrowLeft = rect.left - 32;
                            arrowTop = rect.top + (rect.height / 2) - 16;
                        } else {
                            // Position to the right
                            arrow.classList.add('arrow-left');
                            arrowLeft = rect.right + 32;
                            arrowTop = rect.top + (rect.height / 2) - 16;
                        }
                        break;
                }
                
                arrow.style.left = arrowLeft + 'px';
                arrow.style.top = arrowTop + 'px';
                arrow.style.display = 'block';
            }
            
            centerPopup() {
                const popupRect = this.elements.popup.getBoundingClientRect();
                const left = (window.innerWidth - popupRect.width) / 2;
                const top = (window.innerHeight - popupRect.height) / 2;
                
                this.elements.popup.style.left = left + 'px';
                this.elements.popup.style.top = top + 'px';
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
                if (this.currentStep < this.functionalities.length - 1) {
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
                const event = new CustomEvent('functionalityTourCompleted', {
                    detail: { tourId: this.tourId, functionalities: this.functionalities.length }
                });
                document.dispatchEvent(event);
            }
            
            close() {
                this.isActive = false;
                this.elements.container.classList.remove('active');
                
                setTimeout(() => {
                    this.elements.container.style.display = 'none';
                }, 600);
            }
        }
    }
    
    // Global functionality tour instances
    window.functionalityTours = window.functionalityTours || {};
    
    // Tour management functions
    window.startFunctionalityTour = function(tourId) {
        console.log('Starting functionality tour:', tourId);
        if (window.functionalityTours[tourId]) {
            window.functionalityTours[tourId].start();
        } else {
            console.error('Functionality tour not found:', tourId);
        }
    };
    
    window.closeFunctionalityTour = function(tourId) {
        if (window.functionalityTours[tourId]) {
            window.functionalityTours[tourId].close();
        }
    };
    
    window.nextFunctionalityStep = function(tourId) {
        if (window.functionalityTours[tourId]) {
            window.functionalityTours[tourId].next();
        }
    };
    
    window.previousFunctionalityStep = function(tourId) {
        if (window.functionalityTours[tourId]) {
            window.functionalityTours[tourId].previous();
        }
    };
    
    window.pauseFunctionalityTour = function(tourId) {
        if (window.functionalityTours[tourId]) {
            window.functionalityTours[tourId].pause();
        }
    };
    
    window.skipFunctionalityTour = function(tourId) {
        if (window.functionalityTours[tourId]) {
            window.functionalityTours[tourId].skip();
        }
    };
    
    window.showFunctionalityHelp = function(tourId) {
        alert('Raccourcis clavier:\n\n' +
              '• Flèche gauche (←) : Étape précédente\n' +
              '• Flèche droite (→) : Étape suivante\n' +
              '• Échap : Fermer le tour\n' +
              '• Cliquez sur les éléments pour en savoir plus');
    };
    
    // Initialize tour when component is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing functionality tour: {{ $tourId }}');
        const tourContainer = document.getElementById('functionality-tour-{{ $tourId }}');
        if (tourContainer) {
            console.log('Functionality tour container found, creating tour instance');
            const tour = new FunctionalityTourSystem('{{ $tourId }}', @json($functionalities), {
                theme: '{{ $theme }}',
                autoStart: {{ $autoStart ? 'true' : 'false' }}
            });
            window.functionalityTours['{{ $tourId }}'] = tour;
            console.log('Functionality tour created successfully:', tour);
        } else {
            console.error('Functionality tour container not found: functionality-tour-{{ $tourId }}');
        }
    });
</script>
@endpush
