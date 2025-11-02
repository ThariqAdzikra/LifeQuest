const NavigationModule = (function () {
    const STORAGE_KEY = 'lifequest_last_seen_timestamp';

    function toggleDropdown() {
        const profileDropdown = document.getElementById('profileDropdown');
        const profileTrigger = document.querySelector('.profile-trigger');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationTrigger = document.querySelector('.notification-trigger');

        if (notificationDropdown && notificationDropdown.style.display === 'block') {
            notificationDropdown.style.display = 'none';
            if (notificationTrigger) {
                notificationTrigger.classList.remove('active');
            }
        }

        if (profileDropdown.style.display === 'none') {
            profileDropdown.style.display = 'block';
            if (profileTrigger) {
                profileTrigger.classList.add('active');
            }
        } else {
            profileDropdown.style.display = 'none';
            if (profileTrigger) {
                profileTrigger.classList.remove('active');
            }
        }
    }

    function toggleNotificationDropdown() {
        const profileDropdown = document.getElementById('profileDropdown');
        const profileTrigger = document.querySelector('.profile-trigger');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationTrigger = document.querySelector('.notification-trigger');
        const notificationBadge = document.querySelector('.notification-badge');

        if (profileDropdown && profileDropdown.style.display === 'block') {
            profileDropdown.style.display = 'none';
            if (profileTrigger) {
                profileTrigger.classList.remove('active');
            }
        }

        if (notificationDropdown.style.display === 'none') {
            notificationDropdown.style.display = 'block';
            if (notificationTrigger) {
                notificationTrigger.classList.add('active');
            }
            
            if (notificationBadge && notificationBadge.style.display !== 'none') {
                const currentTimestamp = notificationBadge.getAttribute('data-newest-timestamp');

                if (currentTimestamp) {
                    sessionStorage.setItem(STORAGE_KEY, currentTimestamp); 
                }
                notificationBadge.style.display = 'none';
            }

        } else {
            notificationDropdown.style.display = 'none';
            if (notificationTrigger) {
                notificationTrigger.classList.remove('active');
            }
        }
    }

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu) {
            if (mobileMenu.style.display === 'block') {
                mobileMenu.style.display = 'none';
            } else {
                mobileMenu.style.display = 'block';
            }
        }
    }

    function handleWindowClick(event) {
        const profileDropdown = document.getElementById('profileDropdown');
        const profileTrigger = document.querySelector('.profile-trigger');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationTrigger = document.querySelector('.notification-trigger');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileToggle = document.querySelector('.mobile-toggle');

        if (profileDropdown && profileTrigger) {
            if (!profileTrigger.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.style.display = 'none';
                profileTrigger.classList.remove('active');
            }
        }

        if (notificationDropdown && notificationTrigger) {
            if (!notificationTrigger.contains(event.target) && !notificationDropdown.contains(event.target)) {
                notificationDropdown.style.display = 'none';
                notificationTrigger.classList.remove('active');
            }
        }

        if (mobileMenu && mobileToggle) {
            if (!mobileToggle.contains(event.target) && !mobileMenu.contains(event.target) && !event.target.closest('nav')) {
                 mobileMenu.style.display = 'none';
            }
        }
    }

    function checkNotificationStatus() {
        const notificationBadge = document.querySelector('.notification-badge');
        if (!notificationBadge) {
            return; 
        }

        const currentTimestamp = notificationBadge.getAttribute('data-newest-timestamp');
        const lastSeenTimestamp = sessionStorage.getItem(STORAGE_KEY);

        if (!lastSeenTimestamp) {
            return; 
        }

        if (currentTimestamp === lastSeenTimestamp) {
            notificationBadge.style.display = 'none';
        }
    }

    function init() {
        checkNotificationStatus();
        window.addEventListener('click', handleWindowClick);
        
        const profileTrigger = document.querySelector('.profile-trigger');
        const notificationTrigger = document.querySelector('.notification-trigger');
        const mobileToggle = document.querySelector('.mobile-toggle');

        if (profileTrigger) {
            profileTrigger.addEventListener('click', function(event) {
                event.stopPropagation();
                toggleDropdown(); 
            });
        }

        if (notificationTrigger) {
            notificationTrigger.addEventListener('click', function(event) {
                event.stopPropagation();
                toggleNotificationDropdown(); 
            });
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', function(event) {
                event.stopPropagation();
                toggleMobileMenu(); 
            });
        }

        const profileDropdown = document.getElementById('profileDropdown');
        if (profileDropdown) {
            profileDropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        }

        const notificationDropdown = document.getElementById('notificationDropdown');
        if (notificationDropdown) {
            notificationDropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        }
    }

    return {
        init: init
    };

})();

document.addEventListener('DOMContentLoaded', NavigationModule.init);