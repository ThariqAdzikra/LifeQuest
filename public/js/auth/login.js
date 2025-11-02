const LoginModule = (function () {

    /**
     * Menangani event klik pada ikon toggle password.
     * @param {Event} event - Event klik
     */
    function handleToggleClick(event) {
        const icon = this; 
        const targetInputId = icon.getAttribute('data-target');
        const targetInput = document.getElementById(targetInputId);

        if (!targetInput) {
            console.error('Input target tidak ditemukan untuk toggle icon.');
            return;
        }

        if (targetInput.type === 'password') {
            targetInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            targetInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function init() {
        const toggleIcons = document.querySelectorAll('.toggle-password');
        toggleIcons.forEach(icon => {
            icon.addEventListener('click', handleToggleClick);
        });
    }

    return {
        init: init
    };

})();

document.addEventListener('DOMContentLoaded', LoginModule.init);