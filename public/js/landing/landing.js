const LandingModule = (function () {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    /**
     * Callback function yang dijalankan oleh Intersection Observer.
     * @param {IntersectionObserverEntry[]} entries - Daftar entri yang diamati
     */
    function handleIntersection(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }

    function init() {
        const observer = new IntersectionObserver(handleIntersection, observerOptions);
        const elementsToFadeIn = document.querySelectorAll('.fade-in');
        elementsToFadeIn.forEach(el => {
            observer.observe(el);
        });
    }

    return {
        init: init
    };

})();

document.addEventListener('DOMContentLoaded', LandingModule.init);