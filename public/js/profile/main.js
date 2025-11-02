const AvatarCropModule = (function () {
    let cropper = null;
    let currentFile = null;

    /**
     * Menampilkan modal crop dengan gambar yang dipilih.
     * @param {string} imageUrl - URL Data (Base64) dari gambar
     */
    function showCropModal(imageUrl) {

        if (typeof Cropper === 'undefined') {
            console.error('Cropper is not defined. Tidak dapat membuka modal.');
            alert('Gagal memuat editor gambar. Silakan muat ulang halaman dan coba lagi.');
            return;
        }

        const modal = document.getElementById('crop-modal');
        const image = document.getElementById('crop-image');
        
        if (!modal || !image) {
            console.error('Modal or crop image element not found');
            return;
        }

        image.src = imageUrl;
        modal.style.display = 'flex';

        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

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
                    modal: false, 
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

    /**
     * Update preview avatar HANYA di halaman profil.
     * @param {string} blobUrl - URL object dari blob gambar
     */
    function updatePreview(blobUrl) {
        const previewImg = document.getElementById('avatar-preview');
        const previewPlaceholder = document.getElementById('avatar-preview-placeholder');

        if (previewImg) {
            previewImg.src = blobUrl;
            previewImg.style.display = 'block';
        } else if (previewPlaceholder) {
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

    /**
     * Menangani event 'change' pada input file.
     * @param {Event} e - Event 'change'
     */
    function handleAvatarChange(e) {
        const file = e.target.files[0];
        
        if (!file) {
            return;
        }

        if (!file.type.match('image.*')) {
            console.error('File bukan gambar');
            e.target.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) { // 2MB
            console.error('File terlalu besar (max 2MB)');
            alert('Ukuran file terlalu besar. Maksimum 2MB.');
            e.target.value = '';
            return;
        }

        currentFile = file;
        
        const reader = new FileReader();
        reader.onload = function(event) {
            showCropModal(event.target.result); 
        };
        reader.readAsDataURL(file);
    }

    /**
     * Menangani 'Escape' key untuk menutup modal.
     * @param {Event} e - Event 'keydown'
     */
    function handleEscKey(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('crop-modal');
            if (modal && modal.style.display === 'flex') {
                cancelCrop(); 
            }
        }
    }

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

    function cancelCrop() {
        const modal = document.getElementById('crop-modal');
        if (modal) {
            modal.style.display = 'none';
        }
        
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        
        const avatarInput = document.getElementById('avatar-input');
        if (avatarInput) {
            avatarInput.value = '';
        }
        currentFile = null;
        
        console.log('Crop cancelled');
    }

    function applyCrop() {
        if (!cropper) {
            console.error('Cropper is not initialized');
            return;
        }

        console.log('Applying crop...');
        try {
            const canvas = cropper.getCroppedCanvas({
                width: 512,
                height: 512,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            if (!canvas) {
                throw new Error('Failed to create canvas');
            }

            canvas.toBlob(function(blob) {
                if (!blob) {
                    console.error('Failed to create blob');
                    return;
                }

                console.log('Blob created successfully');

                const fileName = 'avatar_' + Date.now() + '.png';
                const croppedFile = new File([blob], fileName, { type: 'image/png' });

                const avatarInput = document.getElementById('avatar-input');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                
                if (avatarInput) {
                    avatarInput.files = dataTransfer.files;
                }

                const blobUrl = URL.createObjectURL(blob);
                updatePreview(blobUrl); 

                const modal = document.getElementById('crop-modal');
                if (modal) {
                    modal.style.display = 'none';
                }
                
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

    function init() {
        console.log('DOM loaded, AvatarCropModule initializing...');
        
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

        const avatarInput = document.getElementById('avatar-input');
        if (avatarInput) {
            avatarInput.addEventListener('change', handleAvatarChange); 
        }

        const cancelBtn = document.getElementById('btn-cancel-crop');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', cancelCrop); 
        }

        const applyBtn = document.getElementById('btn-apply-crop');
        if (applyBtn) {
            applyBtn.addEventListener('click', applyCrop); 
        }

        document.addEventListener('keydown', handleEscKey); 
        document.addEventListener('dragover', function(e) {
            e.preventDefault();
        });
        document.addEventListener('drop', function(e) {
            e.preventDefault();
        });
    }

    return {
        init: init,
        loadCropperFallback: loadCropperFallback,
        cancelCrop: cancelCrop,
        applyCrop: applyCrop
    };

})();

document.addEventListener('DOMContentLoaded', AvatarCropModule.init);