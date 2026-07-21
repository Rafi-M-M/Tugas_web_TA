document.addEventListener('DOMContentLoaded', function () {
    function clearLoginFormValues() {
        var loginInput = document.getElementById('login');
        var passwordInput = document.getElementById('password');

        if (loginInput) {
            loginInput.value = '';
        }

        if (passwordInput) {
            passwordInput.value = '';
        }
    }

    function enableLoginInputs() {
        var loginInput = document.getElementById('login');
        var passwordInput = document.getElementById('password');

        if (loginInput) {
            loginInput.removeAttribute('readonly');
        }

        if (passwordInput) {
            passwordInput.removeAttribute('readonly');
        }
    }

    function bindPasswordToggleButtons() {
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                var inputId = this.getAttribute('data-toggle-password');
                var input = document.getElementById(inputId);
                var icon = this.querySelector('i');

                if (!input || !icon) {
                    return;
                }

                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('bi-eye', !isPassword);
                icon.classList.toggle('bi-eye-slash', isPassword);
                this.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });
    }

    clearLoginFormValues();
    window.requestAnimationFrame(clearLoginFormValues);
    window.requestAnimationFrame(clearLoginFormValues);
    enableLoginInputs();
    bindPasswordToggleButtons();

    window.addEventListener('pageshow', function () {
        clearLoginFormValues();
        enableLoginInputs();
    });
});