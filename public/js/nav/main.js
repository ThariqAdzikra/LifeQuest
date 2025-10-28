// --- Skrip untuk Profile Dropdown (Sudah Ada) ---
function toggleDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('profileDropdown');
    const trigger = document.querySelector('.profile-trigger');
    
    if (dropdown && trigger) {
        if (!trigger.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown) {
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    }
});


// --- Skrip untuk Mobile Menu Toggle ---
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
}

// Tutup mobile menu jika klik di luar area nav
document.addEventListener('click', function(event) {
    const mobileMenu = document.getElementById('mobileMenu');
    const nav = document.querySelector('nav');
    
    if (mobileMenu && nav) {
        if (!nav.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.style.display = 'none';
        }
    }
});