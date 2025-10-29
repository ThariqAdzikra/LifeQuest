/**
 * Fungsi untuk membuka tab yang dipilih.
 */
function openTab(event, tabName) {
    let i, tabContents, tabLinks;

    // Sembunyikan semua konten tab
    tabContents = document.getElementsByClassName('tab-content');
    for (i = 0; i < tabContents.length; i++) {
        tabContents[i].style.display = 'none';
    }

    // Hapus kelas 'active' dari semua tombol tab
    tabLinks = document.getElementsByClassName('tab-link');
    for (i = 0; i < tabLinks.length; i++) {
        tabLinks[i].className = tabLinks[i].className.replace(' active', '');
    }

    // Tampilkan tab yang diklik dan tambahkan kelas 'active' ke tombolnya
    document.getElementById(tabName).style.display = 'block';
    event.currentTarget.className += ' active';
}


/**
 * Event listener yang dijalankan saat halaman selesai dimuat.
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Bagian 1: Buka tab berdasarkan URL Hash (misal: #createQuest) ---
    if (window.location.hash) {
        let tabName = window.location.hash.substring(1);
        let tabButton = document.querySelector('.tab-link[onclick*="' + tabName + '"]');
        
        if (tabButton) {
            // Hapus 'active' dari tab default
            let defaultActiveTab = document.querySelector('.tab-link.active');
            let defaultActiveContent = document.querySelector('.tab-content.active');
            if (defaultActiveTab) defaultActiveTab.classList.remove('active');
            if (defaultActiveContent) defaultActiveContent.style.display = 'none';

            // Klik tab yang benar
            tabButton.click();
        }
    } else {
         // Jika tidak ada hash, pastikan tab 'active' (myQuests) benar-benar tampil
         let defaultActiveContent = document.querySelector('.tab-content.active');
         if (defaultActiveContent) {
            defaultActiveContent.style.display = 'block';
         }
    }

    // --- Bagian 2: Logika SweetAlert untuk tombol Hapus ---
    const deleteButtons = document.querySelectorAll('.btn-delete-quest');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Hentikan submit form
            let form = this.closest('form'); // Ambil form terdekat

            Swal.fire({
                title: 'Anda yakin?',
                text: "Apakah Anda yakin ingin menghapus quest ini? Ini tidak dapat diurungkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#cbd5e1',
                confirmButtonColor: '#00d4ff',
                cancelButtonColor: '#d33' 
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika dikonfirmasi, kirim form
                }
            });
        });
    });

    // --- Bagian 3: Logika Pagination Kustom (Hanya tampilkan max 3 halaman) ---
    limitPaginationDisplay();
    
    // --- Bagian 4: DEBUG & FIX Pagination Position ---
    debugPaginationPosition();
    // fixPaginationPosition(); // <-- DINONAKTIFKAN
});


/**
 * Fungsi untuk membatasi tampilan pagination maksimal 3 halaman.
 */
function limitPaginationDisplay() {
    const paginationContainers = document.querySelectorAll('.pagination');
    
    paginationContainers.forEach(pagination => {
        const pageItems = pagination.querySelectorAll('.page-item');
        
        if (pageItems.length === 0) return;
        
        let currentPage = 1;
        const activeItem = pagination.querySelector('.page-item.active');
        
        if (activeItem) {
            const activeLink = activeItem.querySelector('.page-link');
            if (activeLink && activeLink.textContent.trim() !== '') {
                currentPage = parseInt(activeLink.textContent.trim());
            }
        }
        
        pageItems.forEach(item => {
            const link = item.querySelector('.page-link');
            const linkText = link ? link.textContent.trim() : '';
            
            if (link && (link.getAttribute('rel') === 'prev' || link.getAttribute('rel') === 'next')) {
                return;
            }
            
            const pageNum = parseInt(linkText);
            if (isNaN(pageNum)) {
                return;
            }
            
            let minPage, maxPage;
            
            if (currentPage <= 2) {
                minPage = 1;
                maxPage = 3;
            } else {
                minPage = currentPage - 2;
                maxPage = currentPage;
            }
            
            if (pageNum < minPage || pageNum > maxPage) {
                item.style.display = 'none';
            } else {
                item.style.display = 'inline-block';
            }
        });
    });
}

/**
 * DEBUG: Log posisi pagination untuk troubleshooting
 */
function debugPaginationPosition() {
    const paginationContainers = document.querySelectorAll('.quest-pagination-container');
    
    paginationContainers.forEach((container, index) => {
        const section = container.getAttribute('data-section');
        const rect = container.getBoundingClientRect();
        const computedStyle = window.getComputedStyle(container);
        
        console.log(`%c[DEBUG] Pagination #${index + 1} (${section})`, 'color: #00d4ff; font-weight: bold;');
        console.log('  Position:', computedStyle.position);
        console.log('  Display:', computedStyle.display);
        console.log('  Top:', computedStyle.top);
        console.log('  Float:', computedStyle.float);
        console.log('  Coordinates:', {
            top: rect.top,
            bottom: rect.bottom,
            left: rect.left,
            right: rect.right
        });
        console.log('  Parent:', container.parentElement.className);
        console.log('---');
    });
}

/**
 * FIX: Paksa pagination ke posisi yang benar dengan JavaScript
 */
function fixPaginationPosition() {
    
    // SELURUH ISI FUNGSI INI DINONAKTIFKAN
    
    /*
    const paginationContainers = document.querySelectorAll('.quest-pagination-container');
    
    paginationContainers.forEach(container => {
        // Force positioning
        container.style.position = 'relative';
        container.style.display = 'block';
        container.style.width = '100%';
        container.style.marginTop = '2rem';
        container.style.clear = 'both';
        container.style.float = 'none';
        container.style.top = 'auto';
        container.style.bottom = 'auto';
        container.style.left = 'auto';
        container.style.right = 'auto';
        
        // Force nav dan pagination di dalamnya
        const nav = container.querySelector('nav');
        const pagination = container.querySelector('.pagination');
        
        if (nav) {
            nav.style.position = 'static';
            nav.style.display = 'block';
        }
        
        if (pagination) {
            pagination.style.position = 'static';
            pagination.style.top = 'auto';
            pagination.style.bottom = 'auto';
        }
    });
    */
}