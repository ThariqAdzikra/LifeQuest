const SubmissionModule = (function () {

    /**
     * Menangani event klik pada tombol tolak.
     * @param {Event} event - Event klik
     */
    function handleRejectClick(event) {
        event.preventDefault();

        const form = this.closest('form');

        Swal.fire({
            title: 'Anda yakin?',
            text: "Anda yakin ingin menolak submission ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, tolak!',
            cancelButtonText: 'Batal',

            customClass: {
                popup: 'swal2-popup',
                title: 'swal2-title',
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    function init() {
        const rejectButtons = document.querySelectorAll('.btn-reject-submission');
        rejectButtons.forEach(button => {
            button.addEventListener('click', handleRejectClick);
        });
    }

    return {
        init: init
    };

})();

document.addEventListener('DOMContentLoaded', SubmissionModule.init);