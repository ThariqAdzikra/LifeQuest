// Fallback jika CDN utama gagal
function loadCropperFallback() {
    console.warn('Primary CDN failed, trying fallback...');
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/cropperjs@1.6.1/dist/cropper.min.js';
    script.onload = function() {
        console.log('✅ Cropper loaded from fallback CDN');
    };
    script.onerror = function() {
        console.error('❌ All CDN sources failed');
    };
    document.head.appendChild(script);
}

// Tunggu sampai Cropper.js ter-load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Cek berkala apakah Cropper sudah tersedia
    let checkCount = 0;
    const checkInterval = setInterval(function() {
        checkCount++;
        
        if (typeof Cropper !== 'undefined') {
            console.log('✅ Cropper.js loaded successfully!');
            clearInterval(checkInterval);
        } else if (checkCount > 20) {
            console.error('❌ Cropper.js failed to load after 2 seconds');
            clearInterval(checkInterval);
        }
    }, 100);
});

let cropper = null;
let currentFile = null;

// Event listener untuk file input
const avatarInput = document.getElementById('avatar-input');
if (avatarInput) {
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (!file) {
            return;
        }

        // Validasi tipe file
        if (!file.type.match('image.*')) {
            console.error('File bukan gambar');
            e.target.value = '';
            return;
        }

        // Validasi ukuran file (2MB)
        if (file.size > 2 * 1024 * 1024) {
            console.error('File terlalu besar (max 2MB)');
            e.target.value = '';
            return;
        }

        currentFile = file;
        
        // Baca file dan tampilkan di cropper
        const reader = new FileReader();
        reader.onload = function(event) {
            const imageUrl = event.target.result;
            showCropModal(imageUrl);
        };
        reader.readAsDataURL(file);
    });
}

// Fungsi untuk menampilkan modal crop
function showCropModal(imageUrl) {
    // Cek apakah Cropper tersedia
    if (typeof Cropper === 'undefined') {
        console.error('Cropper is not defined');
        return;
    }

    const modal = document.getElementById('crop-modal');
    const image = document.getElementById('crop-image');
    
    if (!modal || !image) {
        console.error('Modal or crop image element not found');
        return;
    }

    // Set image source
    image.src = imageUrl;
    
    // Tampilkan modal
    modal.style.display = 'flex';
    
    // Destroy cropper lama jika ada
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    
    // Tunggu gambar dimuat, lalu inisialisasi cropper
    image.onload = function() {
        console.log('Image loaded, initializing cropper...');
        
        try {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                responsive: true,
                checkOrientation: true,
                modal: true,
                background: true,
                scalable: true,
                zoomable: true,
                zoomOnWheel: true,
                wheelZoomRatio: 0.1,
                minCropBoxWidth: 100,
                minCropBoxHeight: 100,
                ready: function() {
                    console.log('✅ Cropper initialized successfully!');
                }
            });
        } catch (error) {
            console.error('Error initializing cropper:', error);
        }
    };
    
    image.onerror = function() {
        console.error('Failed to load image');
        modal.style.display = 'none';
    };
}

// Fungsi untuk membatalkan crop
function cancelCrop() {
    const modal = document.getElementById('crop-modal');
    if (modal) {
        modal.style.display = 'none';
    }
    
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    
    // Reset file input
    const avatarInput = document.getElementById('avatar-input');
    if (avatarInput) {
        avatarInput.value = '';
    }
    currentFile = null;
    
    console.log('Crop cancelled');
}

// Fungsi untuk apply crop
function applyCrop() {
    if (!cropper) {
        console.error('Cropper is not initialized');
        return;
    }

    console.log('Applying crop...');

    try {
        // Dapatkan canvas hasil crop
        const canvas = cropper.getCroppedCanvas({
            width: 512,
            height: 512,
            minWidth: 256,
            minHeight: 256,
            maxWidth: 4096,
            maxHeight: 4096,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (!canvas) {
            throw new Error('Failed to create canvas');
        }

        // Convert canvas to blob
        canvas.toBlob(function(blob) {
            if (!blob) {
                console.error('Failed to create blob');
                return;
            }

            console.log('Blob created successfully');

            // Buat file baru dari blob
            const fileName = 'avatar_' + Date.now() + '.png';
            const croppedFile = new File([blob], fileName, { type: 'image/png' });

            // Update input file
            const avatarInput = document.getElementById('avatar-input');
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(croppedFile);
            
            if (avatarInput) {
                avatarInput.files = dataTransfer.files;
            }

            // Update preview
            const blobUrl = URL.createObjectURL(blob);
            updatePreview(blobUrl);

            // Tutup modal
            const modal = document.getElementById('crop-modal');
            if (modal) {
                modal.style.display = 'none';
            }
            
            // Destroy cropper
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            console.log('✅ Crop applied successfully!');
            
        }, 'image/png', 0.95);

    } catch (error) {
        console.error('Error during crop:', error);
    }
}

// Fungsi untuk update preview (HANYA di halaman profil, BUKAN navbar)
function updatePreview(blobUrl) {
    // Update HANYA preview di halaman profil
    const previewImg = document.getElementById('avatar-preview');
    const previewPlaceholder = document.getElementById('avatar-preview-placeholder');

    if (previewImg) {
        // Jika sudah ada img element, update src
        previewImg.src = blobUrl;
        // Pastikan img element visible
        previewImg.style.display = 'block';
    } else if (previewPlaceholder) {
        // Jika masih placeholder, replace dengan img
        const newImg = document.createElement('img');
        newImg.id = 'avatar-preview';
        newImg.src = blobUrl;
        newImg.alt = 'Avatar Preview';
        newImg.className = 'avatar-preview-img';
        previewPlaceholder.parentNode.replaceChild(newImg, previewPlaceholder);
    }
    
    console.log('✅ Preview updated in profile page only');
    console.log('Note: Navbar will update automatically after page reload from server');
}

// Close modal dengan ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('crop-modal');
        if (modal && modal.style.display === 'flex') {
            cancelCrop();
        }
    }
});

// Prevent default drag behavior
document.addEventListener('dragover', function(e) {
    e.preventDefault();
});

document.addEventListener('drop', function(e) {
    e.preventDefault();
});