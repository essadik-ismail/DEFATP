@props([
    'name' => 'captcha',
    'label' => 'Resolvez cette addition simple',
    'help' => '(Protection contre les attaques automatisees)',
    'question' => null,
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
               placeholder="Votre reponse"
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
        Reponse attendue entre {{ $min }} et {{ $max }}
    </div>
</div>

@push('scripts')
<script>
(function () {
    const name = @json($name);
    const min = {{ (int) $min }};
    const max = {{ (int) $max }};

    function validateCaptcha() {
        const input = document.getElementById(name);
        const question = document.getElementById(name + '-question');

        if (!input || !question) {
            return false;
        }

        const value = Number.parseInt(input.value, 10);

        question.classList.remove('valid', 'invalid');
        input.classList.remove('is-valid', 'is-invalid');

        if (input.value === '' || Number.isNaN(value)) {
            return false;
        }

        if (value < min || value > max) {
            question.classList.add('invalid');
            input.classList.add('is-invalid');
            return false;
        }

        question.classList.add('valid');
        input.classList.add('is-valid');

        return true;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const refreshBtn = document.getElementById(name + '-refresh');
        const input = document.getElementById(name);
        const question = document.getElementById(name + '-question');

        if (refreshBtn && input && question) {
            refreshBtn.addEventListener('click', function () {
                const original = this.innerHTML;

                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;

                fetch('/captcha/refresh')
                    .then((response) => response.json())
                    .then((data) => {
                        question.querySelector('span').textContent = data.question;
                        input.value = '';
                        question.classList.remove('valid', 'invalid');
                        input.classList.remove('is-valid', 'is-invalid');
                    })
                    .catch(() => {
                        question.classList.remove('valid', 'invalid');
                        input.classList.remove('is-valid', 'is-invalid');
                    })
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
