const QuestModule = (function () {
    // --- Variabel untuk Halaman Index/Modal (Edit & Delete) ---
    let modal;
    let closeModalBtn;
    let cancelModalBtn;
    let editForm;
    let achievementSelect;

    // --- Variabel untuk Halaman Create ---
    let createDifficultySelect;
    let createExpInput;

    function moveModalToBody() {
        const modalToMove = document.getElementById('editQuestModal');
        if (modalToMove) {
            document.body.appendChild(modalToMove);
        }
    }

    function showModal() {
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    function handleDeleteClick(event) {
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
    }

    function handleFetchError(error) {
        console.error('Error:', error);
        if (achievementSelect) {
            achievementSelect.innerHTML = '<option value="">Gagal memuat</option>';
        }
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
    }

    function populateModalForm(data, questId) {
        const questData = data.quest || data;
        const achievementsList = data.achievements || [];

        document.getElementById('edit_title').value = questData.title || '';
        document.getElementById('edit_description').value = questData.description || '';
        document.getElementById('edit_difficulty').value = questData.difficulty || 'easy';
        document.getElementById('edit_frequency').value = questData.frequency || 'once';
        document.getElementById('edit_exp_reward').value = questData.exp_reward || 0;
        document.getElementById('edit_gold_reward').value = questData.gold_reward || 0;
        document.getElementById('edit_stat_reward_type').value = questData.stat_reward_type || '';
        document.getElementById('edit_stat_reward_value').value = questData.stat_reward_value || 0;

        if (achievementSelect) {
            achievementSelect.innerHTML = '<option value="">Tidak ada</option>';
            achievementsList.forEach(achievement => {
                const option = document.createElement('option');
                option.value = achievement.id;
                option.textContent = achievement.title;
                achievementSelect.appendChild(option);
            });
            achievementSelect.value = questData.achievement_id || '';
        }

        if (editForm) {
            editForm.action = `/admin/quests/${questId}`;
        }

        showModal();
    }

    function handleEditClick() {
        const questId = this.getAttribute('data-quest-id');

        if (achievementSelect) {
            achievementSelect.innerHTML = '<option value="">Memuat achievements...</option>';
        }

        fetch(`/admin/quests/${questId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                populateModalForm(data, questId);
            })
            .catch(handleFetchError);
    }

    function handleOutsideModalClick(e) {
        if (e.target === modal) {
            closeModal();
        }
    }

    function handleEscKey(e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
            closeModal();
        }
    }

    function initCreateForm() {
        createDifficultySelect = document.getElementById('difficulty');
        createExpInput = document.getElementById('exp_reward');

        if (createDifficultySelect && createExpInput) {
            
            createDifficultySelect.addEventListener('change', function() {
                const difficulty = this.value;
                
                if (difficulty === 'easy') {
                    createExpInput.value = 10;
                } else if (difficulty === 'medium') {
                    createExpInput.value = 100;
                } else if (difficulty === 'hard') {
                    createExpInput.value = 1000; 
                }
            });
        }
    }

    function init() {
        modal = document.getElementById('editQuestModal');
        closeModalBtn = document.getElementById('closeModalBtn');
        cancelModalBtn = document.getElementById('cancelModalBtn');
        editForm = document.getElementById('editQuestForm');
        achievementSelect = document.getElementById('edit_achievement_id');

        moveModalToBody();

        const deleteButtons = document.querySelectorAll('.btn-delete-quest');
        deleteButtons.forEach(button => {
            button.addEventListener('click', handleDeleteClick);
        });

        const editButtons = document.querySelectorAll('.btn-edit-quest');
        editButtons.forEach(button => {
            button.addEventListener('click', handleEditClick);
        });

        if (modal && closeModalBtn && cancelModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
            cancelModalBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', handleOutsideModalClick);
        }
        
        document.addEventListener('keydown', handleEscKey);

        initCreateForm();
    }

    return {
        init: init
    };

})();

document.addEventListener('DOMContentLoaded', QuestModule.init);