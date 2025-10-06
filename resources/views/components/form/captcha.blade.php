@props([
    'name' => 'captcha',
    'label' => 'Résolvez cette addition simple',
    'help' => '(Protection contre les attaques automatisées)',
    'question' => null,
    'answer' => null,
    'min' => 1,
    'max' => 10,
    'required' => true
])

<div class="form-group">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($help)
            <span class="form-text">{{ $help }}</span>
        @endif
    </label>

    <div class="captcha-container">
        <div class="captcha-question" id="{{ $name }}-question">
            <span>{{ $question ?? '5 + 3' }}</span>
            <button type="button" class="captcha-refresh" id="{{ $name }}-refresh" title="Nouvelle question">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <input type="number"
               class="form-control @error($name) is-invalid @enderror"
               id="{{ $name }}"
               name="{{ $name }}"
               placeholder="Votre réponse"
               min="{{ $min }}"
               max="{{ $max }}"
               {{ $required ? 'required' : '' }}
               autocomplete="off"
               aria-describedby="{{ $name }}-help">
    </div>

    @error($name)
        <div class="invalid-feedback">
            <i class="fas fa-exclamation-circle"></i>
            {{ $message }}
        </div>
    @enderror

    <div id="{{ $name }}-help" class="form-text">
        <i class="fas fa-info-circle"></i>
        Réponse attendue entre {{ $min }} et {{ $max }}
    </div>
</div>

@push('scripts')
<script>
(function(){
    const name = @json($name);
    let currentAnswer = @json($answer ?? 8);

    function generateCaptcha() {
        const maxSum = {{ (int) $max }};
        const num1 = Math.floor(Math.random() * (maxSum - 1)) + 1;
        const num2 = Math.floor(Math.random() * (maxSum - num1)) + 1;
        const question = `${num1} + ${num2}`;
        const answer = num1 + num2;

        document.getElementById(name + '-question').querySelector('span').textContent = question;
        document.getElementById(name).value = '';
        currentAnswer = answer;

        const q = document.getElementById(name + '-question');
        const input = document.getElementById(name);
        q.classList.remove('valid', 'invalid');
        input.classList.remove('is-valid', 'is-invalid');
    }

    function validateCaptcha() {
        const input = document.getElementById(name);
        const q = document.getElementById(name + '-question');
        const val = parseInt(input.value);

        q.classList.remove('valid', 'invalid');
        input.classList.remove('is-valid', 'is-invalid');

        if (input.value === '') return false;
        if (val < {{ (int) $min }} || val > {{ (int) $max }}) {
            q.classList.add('invalid');
            input.classList.add('is-invalid');
            return false;
        }
        if (val === currentAnswer) {
            q.classList.add('valid');
            input.classList.add('is-valid');
            return true;
        }
        q.classList.add('invalid');
        input.classList.add('is-invalid');
        return false;
    }

    document.addEventListener('DOMContentLoaded', function(){
        const refreshBtn = document.getElementById(name + '-refresh');
        const input = document.getElementById(name);
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function(){
                const original = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                fetch('/captcha/refresh')
                    .then(r => r.json())
                    .then(d => {
                        document.getElementById(name + '-question').querySelector('span').textContent = d.question;
                        input.value = '';
                        currentAnswer = d.answer;
                        const q = document.getElementById(name + '-question');
                        q.classList.remove('valid', 'invalid');
                        input.classList.remove('is-valid', 'is-invalid');
                    })
                    .catch(() => generateCaptcha())
                    .finally(() => {
                        this.innerHTML = original;
                        this.disabled = false;
                    });
            });
        }
        if (input) {
            input.addEventListener('input', validateCaptcha);
            input.addEventListener('blur', validateCaptcha);
        }
    });
})();
</script>
@endpush

