@props(['text', 'position' => 'top'])

<div class="tooltip-container inline-block">
    <i class="fas fa-question-circle help-icon" data-tooltip="{{ $text }}"></i>
</div>
