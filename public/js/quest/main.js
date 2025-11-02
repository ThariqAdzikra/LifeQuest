/**
 * QuestPageModule
 * Mengelola semua logika untuk halaman quest, termasuk fungsionalitas tab,
 * konfirmasi hapus, modal, dan logika pagination.
 */
const QuestPageModule = (function () {

    // --- Fungsi Private ---

    /**
     * Membatasi tampilan pagination maksimal 3 halaman.
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
                    // Cek jika NaN, misal '...', set ke 1
                    const pageNum = parseInt(activeLink.textContent.trim());
                    if (!isNaN(pageNum)) {
                        currentPage = pageNum;
                    }
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
                    return; // Abaikan item non-numerik seperti '...'
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
     * FIX: Paksa pagination ke posisi yang benar dengan JavaScript (DINONAKTIFKAN)
     */
    function fixPaginationPosition() {
        // (Fungsi ini sengaja dibiarkan kosong)
    }

    /**
     * Melampirkan listener SweetAlert ke tombol Hapus.
     */
    function initDeleteConfirmations() {
        const deleteButtons = document.querySelectorAll('.btn-delete-quest');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                let form = this.closest('form'); 

                Swal.fire({
                    title: 'Anda yakin?',
                    text: "Apakah Anda yakin ingin menghapus quest ini? Ini tidak dapat diurungkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    // Style kustom untuk tema gelap Anda
                    background: '#0f172a', // Latar belakang modal
                    color: '#cbd5e1',       // Warna teks
                    confirmButtonColor: '#00d4ff', // Warna tombol konfirmasi
                    cancelButtonColor: '#d33'  // Warna tombol batal
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                });
            });
        });
    }

    /**
     * Melampirkan listener ke Modal Submission Bootstrap.
     */
    function initSubmissionModalLogic() {
        const submissionModal = document.getElementById('submissionModal');
        if (submissionModal) {
            // Gunakan event standar Bootstrap 5
            submissionModal.addEventListener('show.bs.modal', function (event) {
                // Button yang memicu modal
                const button = event.relatedTarget;
                // Ambil URL dari atribut data-submit-url
                const submitUrl = button.getAttribute('data-submit-url');
                // Set action form di dalam modal
                const modalForm = submissionModal.querySelector('#submissionForm');
                
                if (modalForm) {
                    modalForm.setAttribute('action', submitUrl);
                }
            });
        }
    }

    /**
     * [PERBAIKAN] Memeriksa URL hash untuk membuka tab yang sesuai.
     * Selector diubah dari [onclick*="..."] menjadi [data-tab-target="..."]
     */
    function initTabFromHash() {
        if (window.location.hash) {
            let tabName = window.location.hash.substring(1);
            // Gunakan selector atribut data-tab-target yang baru
            let tabButton = document.querySelector('.tab-link[data-tab-target="' + tabName + '"]');
            
            if (tabButton) {
                // Hapus 'active' dari tab default
                let defaultActiveTab = document.querySelector('.tab-link.active');
                let defaultActiveContent = document.querySelector('.tab-content.active');
                if (defaultActiveTab) defaultActiveTab.classList.remove('active');
                if (defaultActiveContent) defaultActiveContent.style.display = 'none';
                
                // Panggil openTab secara internal
                openTab(null, tabName, tabButton);
            }
        } else {
             // Jika tidak ada hash, pastikan tab 'active' (myQuests) benar-benar tampil
             let defaultActiveContent = document.querySelector('.tab-content.active');
             if (defaultActiveContent) {
                defaultActiveContent.style.display = 'block';
             }
        }
    }

    /**
     * [PERBAIKAN BARU] Melampirkan event listener ke tombol tab.
     * Ini menggantikan kebutuhan 'onclick=""' di HTML.
     */
    function initTabListeners() {
        const tabLinks = document.querySelectorAll('.tab-link[data-tab-target]');
        tabLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                const tabName = this.dataset.tabTarget;
                openTab(event, tabName, this); // 'this' adalah tombol yang diklik
            });
        });
    }

    /**
     * [PERBAIKAN] Fungsi untuk membuka tab yang dipilih.
     * Sekarang menerima 'clickedButton' sebagai argumen opsional.
     */
    function openTab(event, tabName, clickedButton) {
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

        // Tampilkan tab yang diklik
        const activeTabContent = document.getElementById(tabName);
        if (activeTabContent) {
            activeTabContent.style.display = 'block';
        }
        
        // Tambahkan kelas 'active' ke tombol yang diklik
        // 'currentTarget' berasal dari event, 'clickedButton' berasal dari pemanggilan internal
        const buttonToActivate = (event ? event.currentTarget : clickedButton);
        if (buttonToActivate) {
            buttonToActivate.className += ' active';
        }
    }

    /**
     * [PUBLIK] Fungsi inisialisasi utama.
     */
    function init() {
        // [PERBAIKAN] Panggil initTabListeners
        initTabListeners(); 
        
        // Panggil ini SETELAH listener dipasang
        initTabFromHash(); 
        
        initDeleteConfirmations();
        initSubmissionModalLogic();
        limitPaginationDisplay();
        debugPaginationPosition();
        // fixPaginationPosition(); // <-- DINONAKTIFKAN
    }

    // Mengekspos (reveal) fungsi yang perlu diakses secara global
    return {
        init: init
        // 'openTab' tidak perlu diekspos lagi karena dipanggil secara internal
    };

})();

/**
 * Event listener yang dijalankan saat halaman selesai dimuat.
 * Memanggil inisialisasi modul.
 */
document.addEventListener('DOMContentLoaded', QuestPageModule.init);