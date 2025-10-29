document.addEventListener('DOMContentLoaded', function () {
    
    // Ambil semua tombol hapus
    const deleteButtons = document.querySelectorAll('.btn-delete-achievement');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // Hentikan form dari submit langsung
            event.preventDefault(); 
            
            const form = this.closest('form');

            Swal.fire({
                title: 'Anda yakin?',
                text: "Achievement ini akan dihapus permanen.",
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