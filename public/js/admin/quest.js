document.addEventListener('DOMContentLoaded', function () {

    // ====================================
    // [FIX 1] PINDAHKAN MODAL KE BODY
    // Memperbaiki masalah z-index/stacking context
    // di mana footer halaman menimpa modal.
    // ====================================
    const modalToMove = document.getElementById('editQuestModal');
    if (modalToMove) {
        document.body.appendChild(modalToMove);
    }
    
    // ====================================
    // FUNGSI HAPUS QUEST (SWEETALERT)
    // ====================================
    const deleteButtons = document.querySelectorAll('.btn-delete-quest');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); 
            const form = this.closest('form');

            Swal.fire({
                title: 'Anda yakin?',
                text: "Quest ini akan dihapus permanen. Log player terkait juga akan terhapus.",
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
        });
    });

    // ====================================
    // FUNGSI EDIT QUEST (MODAL) [FIXED]
    // ====================================
    const editButtons = document.querySelectorAll('.btn-edit-quest');
    const modal = document.getElementById('editQuestModal'); 
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const editForm = document.getElementById('editQuestForm');
    const achievementSelect = document.getElementById('edit_achievement_id');

    // Buka modal saat tombol edit diklik
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const questId = this.getAttribute('data-quest-id');
            
            // Set select ke mode loading
            achievementSelect.innerHTML = '<option value="">Memuat achievements...</option>';
            
            // Fetch data quest via AJAX
            fetch(`/admin/quests/${questId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    
                    // [FIX 2] Ambil data dari response JSON
                    const questData = data.quest || data; 
                    const achievementsList = data.achievements || [];

                    // Isi form dengan data quest
                    document.getElementById('edit_title').value = questData.title || '';
                    document.getElementById('edit_description').value = questData.description || '';
                    document.getElementById('edit_difficulty').value = questData.difficulty || 'easy';
                    document.getElementById('edit_frequency').value = questData.frequency || 'once';
                    document.getElementById('edit_exp_reward').value = questData.exp_reward || 0;
                    document.getElementById('edit_gold_reward').value = questData.gold_reward || 0;
                    document.getElementById('edit_stat_reward_type').value = questData.stat_reward_type || '';
                    document.getElementById('edit_stat_reward_value').value = questData.stat_reward_value || 0;
                    
                    // [FIX 3] Bangun ulang (rebuild) dropdown achievement
                    achievementSelect.innerHTML = '<option value="">Tidak ada</option>';
                    
                    achievementsList.forEach(achievement => {
                        const option = document.createElement('option');
                        option.value = achievement.id;
                        option.textContent = achievement.title;
                        achievementSelect.appendChild(option);
                    });
                    
                    // Set value yang terpilih
                    achievementSelect.value = questData.achievement_id || '';
                    
                    // Set form action
                    editForm.action = `/admin/quests/${questId}`;
                    
                    // Tampilkan modal
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                })
                .catch(error => {
                    console.error('Error:', error);
                    achievementSelect.innerHTML = '<option value="">Gagal memuat</option>';
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memuat data quest atau achievements.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-popup',
                            confirmButton: 'swal2-confirm'
                        }
                    });
                });
        });
    });

    // Tutup modal
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    closeModalBtn.addEventListener('click', closeModal);
    cancelModalBtn.addEventListener('click', closeModal);
    
    // Tutup modal saat klik di luar modal
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Tutup modal dengan tombol ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

});