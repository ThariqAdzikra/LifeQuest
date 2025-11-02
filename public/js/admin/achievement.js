const AchievementModule = (function () {
    /**
     * Menangani event klik pada tombol hapus.
     * @param {Event} event - Event klik
     */
    function handleDeleteClick(event) {
        event.preventDefault();

        const form = this.closest('form');

        Swal.fire({
            title: 'Anda yakin?',
            text: "Achievement ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
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
        const deleteButtons = document.querySelectorAll('.btn-delete-achievement');
        deleteButtons.forEach(button => {
            button.addEventListener('click', handleDeleteClick);
        });
    }

    return {
        init: init
    };

})();

document.addEventListener('DOMContentLoaded', AchievementModule.init);