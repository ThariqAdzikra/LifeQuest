document.addEventListener('DOMContentLoaded', function () {
    
    // Ambil semua tombol tolak
    const rejectButtons = document.querySelectorAll('.btn-reject-submission');

    rejectButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // Hentikan form dari submit langsung
            event.preventDefault(); 
            
            const form = this.closest('form');

            Swal.fire({
                title: 'Anda yakin?',
                text: "Anda yakin ingin menolak submission ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, tolak!',
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