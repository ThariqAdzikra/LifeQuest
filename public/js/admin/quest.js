document.addEventListener('DOMContentLoaded', function () {
    
    // Ambil semua tombol hapus
    const deleteButtons = document.querySelectorAll('.btn-delete-quest');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // Hentikan form dari submit langsung
            event.preventDefault(); 
            
            const form = this.closest('form');

            Swal.fire({
                title: 'Anda yakin?',
                text: "Quest ini akan dihapus permanen. Log player terkait juga akan terhapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                
                // Gunakan style kustom dari CSS Anda
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, kirim form
                    form.submit();
                }
            });
        });
    });

});